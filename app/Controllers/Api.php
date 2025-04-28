<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\StockModel;

class Api extends ResourceController
{
    protected $format = 'json';

    public function getStockData()
    {
        // Get stock model
        $stockModel = new StockModel();
        
        // Get last 6 months data
        $months = [];
        $historical = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $months[] = $date;
            
            // Get stock data for each month
            $monthData = [
                'tabung_3kg' => $stockModel->getMonthlyStock('3kg', $date) ?? 0,
                'tabung_5kg' => $stockModel->getMonthlyStock('5kg', $date) ?? 0,
                'tabung_12kg' => $stockModel->getMonthlyStock('12kg', $date) ?? 0,
                'tabung_15kg' => $stockModel->getMonthlyStock('15kg', $date) ?? 0,
            ];
            
            $historical[$date] = $monthData;
        }
        
        return $this->respond([
            'labels' => $months,
            'historical' => $historical
        ]);
    }
} 