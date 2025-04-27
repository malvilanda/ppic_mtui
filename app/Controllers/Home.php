<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function testDb()
    {
        try {
            $db = \Config\Database::connect();
            $query = $db->query('SELECT VERSION() as version');
            $result = $query->getRow();
            
            return 'Database connection successful. MySQL version: ' . ($result->version ?? 'unknown');
        } catch (\Exception $e) {
            return 'Database connection failed: ' . $e->getMessage();
        }
    }
}
