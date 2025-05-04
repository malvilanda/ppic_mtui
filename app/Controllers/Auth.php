<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\LoginLogModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Auth extends BaseController
{
    protected $userModel;
    protected $loginLogModel;
    protected $session;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->userModel = new UserModel();
        $this->loginLogModel = new LoginLogModel();
        
        // Konfigurasi session timeout (1 jam = 3600 detik)
        $this->session = session();
        
        // Cek session timeout di setiap request
        $this->checkSessionTimeout();
    }

    private function checkSessionTimeout()
    {
        if ($this->session->get('logged_in')) {
            $lastActivity = $this->session->get('last_activity');
            $currentTime = time();
            $timeoutDuration = 3600; // 1 jam

            // Jika waktu terakhir aktivitas lebih dari timeout duration
            if ($lastActivity && ($currentTime - $lastActivity > $timeoutDuration)) {
                // Hapus session dan redirect ke halaman login
                $this->session->destroy();
                return redirect()->to('login')
                    ->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
            }

            // Update waktu aktivitas terakhir
            $this->session->set('last_activity', $currentTime);
        }
    }

    public function index()
    {
        return redirect()->to('/auth/login');
    }

    public function login()
    {
        if (session()->get('user_id')) {
            return redirect()->to(base_url('dashboard'));
        }
        
        return view('auth/login');
    }

    private function getMacAddress() {
        try {
            // Coba dapatkan MAC address dari $_SERVER variables
            $mac = null;
            
            // Cek beberapa kemungkinan server variables
            $possible_keys = array(
                'HTTP_X_FORWARDED_FOR',
                'HTTP_CLIENT_IP',
                'HTTP_X_REAL_IP',
                'REMOTE_ADDR'
            );
            
            $client_ip = '';
            foreach ($possible_keys as $key) {
                if (!empty($_SERVER[$key])) {
                    $client_ip = $_SERVER[$key];
                    break;
                }
            }
            
            // Jika tidak bisa mendapatkan MAC address, gunakan hash dari IP dan User Agent
            $unique_identifier = md5($client_ip . $_SERVER['HTTP_USER_AGENT']);
            
            // Format menjadi format yang mirip MAC address
            $mac = substr($unique_identifier, 0, 2);
            for ($i = 2; $i < 12; $i += 2) {
                $mac .= ':' . substr($unique_identifier, $i, 2);
            }
            
            return strtoupper($mac);
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting device identifier: ' . $e->getMessage());
            return md5($_SERVER['REMOTE_ADDR']); // fallback ke hash dari IP address
        }
    }

    private function getLocation($ip) {
        try {
            // Jika IP adalah localhost atau private IP, return info tersebut
            if ($ip == '127.0.0.1' || $ip == '::1' || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) === false) {
                return json_encode([
                    'ip_type' => 'Local/Private IP',
                    'is_private' => true
                ]);
            }

            // Gunakan IP-API untuk mendapatkan lokasi
            $url = "http://ip-api.com/json/" . $ip . "?fields=status,message,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,query";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5); // timeout 5 detik
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 200 && $response) {
                $data = json_decode($response, true);
                if ($data['status'] == 'success') {
                    return json_encode([
                        'city' => $data['city'] ?? 'Unknown',
                        'region' => $data['regionName'] ?? 'Unknown',
                        'country' => $data['country'] ?? 'Unknown',
                        'provider' => $data['isp'] ?? 'Unknown',
                        'latitude' => $data['lat'] ?? 0,
                        'longitude' => $data['lon'] ?? 0,
                        'timezone' => $data['timezone'] ?? 'Unknown'
                    ]);
                }
            }

            // Jika gagal mendapatkan lokasi
            return json_encode([
                'error' => 'Tidak dapat mendapatkan informasi lokasi',
                'ip_type' => filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? 'IPv4' : 'IPv6',
                'is_private' => false
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting location: ' . $e->getMessage());
            return null;
        }
    }

    public function authenticate()
    {
        // Validasi input
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|alpha_numeric',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Input tidak valid');
        }

        // Escape dan bersihkan input
        $request = service('request');
        $username = esc($request->getPost('username'));
        $password = $request->getPost('password');

        // Tambahan keamanan dengan rate limiting
        $throttler = \Config\Services::throttler();
        $allowed = $throttler->check(md5($username), 5, MINUTE);

        if (!$allowed) {
            return redirect()->back()
                ->with('error', 'Terlalu banyak percobaan login. Silakan coba lagi nanti.');
        }

        // Cek user di database
        $user = $this->userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            // Set session data
            $sessionData = [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'name' => $user['name'],
                'role' => $user['role'],
                'logged_in' => true,
                'last_activity' => time()
            ];
            session()->set($sessionData);

            // Dapatkan informasi MAC address dan lokasi
            $macAddress = $this->getMacAddress();
            $location = $this->getLocation($_SERVER['REMOTE_ADDR']);

            // Simpan log login menggunakan model dengan informasi tambahan
            $this->loginLogModel->logLogin(
                $user['id'], 
                'success',
                [
                    'mac_address' => $macAddress,
                    'location' => $location,
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
                ]
            );

            // Regenerasi ID session untuk mencegah session fixation
            session_regenerate_id(true);

            return redirect()->to('dashboard')
                ->with('success', 'Login berhasil');
        }

        // Jika login gagal
        return redirect()->back()
            ->withInput()
            ->with('error', 'Username atau password salah');
    }

    public function logout()
    {
        // Pastikan session sudah dimulai
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Catat logout di log
        if (session()->get('user_id')) {
            $this->loginLogModel->logLogout(session()->get('user_id'));
        }
        
        // Hapus data session
        session()->destroy();

        return redirect()->to('login')
            ->with('success', 'Anda telah berhasil logout');
    }

    public function loginHistory()
    {
        // Cek apakah user sudah login
        if (!session()->get('logged_in')) {
            return redirect()->to('login');
        }

        // Cek apakah user adalah admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk melihat history login semua user');
        }

        $data = [
            'title' => 'Riwayat Login',
            'loginHistory' => $this->loginLogModel->getLoginHistory()
        ];

        return view('auth/login_history', $data);
    }
} 