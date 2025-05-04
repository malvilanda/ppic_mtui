<?php

if (!function_exists('human_time_diff')) {
    function human_time_diff($timestamp) {
        $current_time = time();
        $diff = $current_time - $timestamp;
        
        $intervals = array(
            31536000 => 'tahun',
            2592000 => 'bulan',
            604800 => 'minggu',
            86400 => 'hari',
            3600 => 'jam',
            60 => 'menit',
            1 => 'detik'
        );
        
        foreach ($intervals as $secs => $str) {
            $d = $diff / $secs;
            
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . $str . ($r > 1 ? '' : '') . ' yang lalu';
            }
        }
        
        return 'Baru saja';
    }
} 