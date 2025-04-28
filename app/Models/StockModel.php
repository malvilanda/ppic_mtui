<?php

namespace App\Models;

use CodeIgniter\Model;

class StockModel extends Model
{
    protected $table = 'stock_history';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['type', 'quantity', 'date'];

    public function getMonthlyStock($type, $month)
    {
        return $this->select('SUM(quantity) as total')
            ->where('type', $type)
            ->where('DATE_FORMAT(date, "%Y-%m")', $month)
            ->get()
            ->getRow()
            ->total ?? 0;
    }
} 