<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ItemsPartController extends BaseController
{
    public function checkDuplicateItem()
    {
        $json = $this->request->getJSON();
        $part_number = $json->part_number;
        $warehouse_id = $json->warehouse_id;
        
        $db = \Config\Database::connect();
        $builder = $db->table('items_part');
        
        $exists = $builder->where('part_number', $part_number)
                         ->where('warehouse_id', $warehouse_id)
                         ->countAllResults() > 0;
        
        return $this->response->setJSON(['exists' => $exists]);
    }
} 