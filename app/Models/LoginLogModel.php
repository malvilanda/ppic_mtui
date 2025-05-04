<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginLogModel extends Model
{
    protected $table = 'login_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'user_id',
        'ip_address',
        'mac_address',
        'location',
        'user_agent',
        'login_time',
        'last_activity',
        'is_active',
        'status'
    ];
    
    protected $useTimestamps = false;

    public function logLogin($userId, $status = 'success', $additionalData = [])
    {
        try {
            $request = service('request');
            
            $data = [
                'user_id' => $userId,
                'ip_address' => $request->getIPAddress(),
                'mac_address' => $additionalData['mac_address'] ?? null,
                'location' => $additionalData['location'] ?? null,
                'user_agent' => $additionalData['user_agent'] ?? null,
                'login_time' => date('Y-m-d H:i:s'),
                'last_activity' => date('Y-m-d H:i:s'),
                'is_active' => 1,
                'status' => $status
            ];

            // Log data untuk debugging
            log_message('debug', 'Attempting to log login with data: ' . json_encode($data));
            
            return $this->insert($data);
        } catch (\Exception $e) {
            log_message('error', 'Error logging login: ' . $e->getMessage());
            return false;
        }
    }

    public function getLoginHistory()
    {
        try {
            $builder = $this->db->table($this->table);
            $builder->select('login_logs.*, users.username, users.name');
            $builder->join('users', 'users.id = login_logs.user_id');
            $builder->orderBy('login_time', 'DESC');
            
            return $builder->get()->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error getting login history: ' . $e->getMessage());
            return [];
        }
    }

    public function updateLastActivity($userId)
    {
        try {
            return $this->where('user_id', $userId)
                       ->where('is_active', 1)
                       ->set([
                           'last_activity' => date('Y-m-d H:i:s')
                       ])
                       ->update();
        } catch (\Exception $e) {
            log_message('error', 'Error updating last activity: ' . $e->getMessage());
            return false;
        }
    }

    public function logLogout($userId)
    {
        try {
            // Set status logout untuk log terakhir yang aktif
            return $this->where('user_id', $userId)
                       ->where('is_active', 1)
                       ->set([
                           'is_active' => 0,
                           'status' => 'logout',
                           'last_activity' => date('Y-m-d H:i:s')
                       ])
                       ->update();
        } catch (\Exception $e) {
            log_message('error', 'Error logging logout: ' . $e->getMessage());
            return false;
        }
    }
} 