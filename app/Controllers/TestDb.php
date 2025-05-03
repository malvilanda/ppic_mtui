<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TestDb extends Controller
{
    public function index()
    {
        try {
            $db = \Config\Database::connect();
            
            // Test koneksi
            $tables = $db->listTables();
            
            echo "Koneksi database berhasil!<br>";
            echo "Database: " . $db->getDatabase() . "<br>";
            echo "Daftar tabel:<br>";
            echo "<pre>";
            print_r($tables);
            echo "</pre>";
            
            // Test query ke tabel items_part
            $query = $db->query("SHOW CREATE TABLE items_part");
            $result = $query->getResultArray();
            
            echo "Struktur tabel items_part:<br>";
            echo "<pre>";
            print_r($result);
            echo "</pre>";
            
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} 