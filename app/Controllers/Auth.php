<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
        
        // Konfigurasi session timeout (1 jam = 3600 detik)
        $this->session->set('session_timeout', 3600);
        
        // Cek session timeout di setiap request
        $this->checkSessionTimeout();
    }

    private function checkSessionTimeout()
    {
        if ($this->session->get('logged_in')) {
            $lastActivity = $this->session->get('last_activity');
            $currentTime = time();
            $timeoutDuration = $this->session->get('session_timeout');

            // Jika waktu terakhir aktivitas lebih dari timeout duration
            if ($lastActivity && ($currentTime - $lastActivity > $timeoutDuration)) {
                // Hapus session dan redirect ke halaman login
                $this->session->destroy();
                return redirect()->to(base_url('login'))
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
        $username = esc($this->request->getPost('username'));
        $password = $this->request->getPost('password');

        // Tambahan keamanan dengan rate limiting
        $throttler = \Config\Services::throttler();
        $allowed = $throttler->check(md5($username), 5, MINUTE);

        if (!$allowed) {
            return redirect()->back()
                ->with('error', 'Terlalu banyak percobaan login. Silakan tunggu beberapa saat.');
        }

        $user = $this->userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            $sessionData = [
                'user_id' => (int)$user['id'],
                'username' => esc($user['username']),
                'name' => esc($user['name']),
                'role' => esc($user['role']),
                'logged_in' => true,
                'last_activity' => time() // Tambahkan timestamp aktivitas terakhir
            ];
            session()->set($sessionData);

            // Regenerasi ID session untuk mencegah session fixation
            session()->regenerate();

            return redirect()->to(base_url('dashboard'))->with('success', 'Login berhasil');
        }

        return redirect()->back()->with('error', 'Username atau password salah');
    }

    public function logout()
    {
        // Pastikan session dimulai sebelum menghancurkannya
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Hapus data session
        session()->remove(['user_id', 'username', 'role']);
        
        // Hancurkan session
        session()->destroy();
        
        // Redirect ke halaman login
        return redirect()->to('login')->with('message', 'Anda telah berhasil logout');
    }
} 