<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            color: #333;
        }
        .header p {
            margin: 2px 0;
            color: #666;
        }
        .report-info {
            margin: 20px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        .report-info h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 11px;
        }
        th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        td {
            padding: 8px 10px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-masuk {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-keluar {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
        @page {
            margin: 0.5cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2><?= $company['name'] ?></h2>
        <p><?= $company['address'] ?></p>
        <p>Telp: <?= $company['phone'] ?> | Email: <?= $company['email'] ?></p>
    </div>

    <div class="report-info">
        <h3><?= $title ?></h3>
        <p>
            Periode: <?= $start_date ? date('d/m/Y', strtotime($start_date)) : 'Semua' ?> 
            s/d <?= $end_date ? date('d/m/Y', strtotime($end_date)) : 'Semua' ?>
            <?= $jenis ? ' | Jenis: ' . ucfirst($jenis) : '' ?>
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="12%">Tanggal</th>
                <th width="30%">Nama Bahan</th>
                <th width="10%">Transaksi</th>
                <th width="10%">Jumlah</th>
                <th width="33%">Unit Penerima</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($transaksi)): ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data transaksi</td>
                </tr>
            <?php else: ?>
                <?php 
                $i = 1; 
                $total_masuk = 0;
                $total_keluar = 0;
                foreach($transaksi as $t): 
                    if($t['type'] === 'masuk') {
                        $total_masuk += $t['quantity'];
                    } else {
                        $total_keluar += $t['quantity'];
                    }
                ?>
                    <tr>
                        <td class="text-center"><?= $i++ ?></td>
                        <td><?= date('d/m/Y', strtotime($t['transaction_date'])) ?></td>
                        <td><?= $t['nama_bahan'] ?></td>
                        <td class="text-center">
                            <span class="badge <?= $t['type'] === 'masuk' ? 'badge-masuk' : 'badge-keluar' ?>">
                                <?= ucfirst($t['type']) ?>
                            </span>
                        </td>
                        <td class="text-right"><?= number_format($t['quantity'], 0, ',', '.') ?></td>
                        <td><?= $t['unit'] ?? '-' ?></td>
                    </tr>
                <?php endforeach; ?>
                <!-- Summary Row -->
                <tr>
                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                    <td class="text-right">
                        <strong>Masuk: <?= number_format($total_masuk, 0, ',', '.') ?></strong><br>
                        <strong>Keluar: <?= number_format($total_keluar, 0, ',', '.') ?></strong>
                    </td>
                    <td></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <table style="border: none; width: 100%;">
            <tr>
                <td style="border: none; width: 50%; text-align: left;">
                    Dicetak pada: <?= date('d/m/Y H:i:s') ?>
                </td>
                <td style="border: none; width: 50%; text-align: right;">
                    Oleh: <?= session()->get('username') ?>
                </td>
            </tr>
        </table>
    </div>
</body>
</html> 