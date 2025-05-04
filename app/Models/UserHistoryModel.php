<?php

namespace App\Models;

use CodeIgniter\Model;

class UserHistoryModel extends Model
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
        'status'
    ];

    protected $useTimestamps = false;

    public function getUserHistory()
    {
        return $this->select('login_logs.*, users.username')
                    ->join('users', 'users.id = login_logs.user_id', 'left')
                    ->orderBy('login_time', 'DESC')
                    ->findAll();
    }

    public function logActivity($userId, $status = 'success')
    {
        return $this->insert([
            'user_id' => $userId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'mac_address' => null, // Perlu fungsi khusus untuk mendapatkan MAC address
            'location' => null, // Perlu integrasi dengan layanan geolocation
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '-',
            'login_time' => date('Y-m-d H:i:s'),
            'status' => $status
        ]);
    }
} 