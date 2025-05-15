<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetModel extends Model
{
    protected $table = 'password_resets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'username',
        'token',
        'created_at',
        'expires_at'
    ];
    
    protected $useTimestamps = false;

    public function createToken($username)
    {
        // Hapus token lama jika ada
        $this->where('username', $username)->delete();
        
        // Generate token baru
        $token = bin2hex(random_bytes(32));
        
        // Set expiry time (1 jam)
        $data = [
            'username' => $username,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s'),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+1 hour'))
        ];
        
        // Simpan token baru
        $this->insert($data);
        
        return $token;
    }

    public function verifyToken($token)
    {
        $reset = $this->where([
            'token' => $token,
            'expires_at >=' => date('Y-m-d H:i:s')
        ])->first();
        
        return $reset;
    }

    public function deleteToken($token)
    {
        return $this->where('token', $token)->delete();
    }
} 