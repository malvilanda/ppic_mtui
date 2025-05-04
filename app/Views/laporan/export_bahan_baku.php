<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Bahan Baku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        .report-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .report-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2d3748;
            margin: 0;
        }
        .report-title {
            font-size: 20px;
            color: #4a5568;
            margin: 10px 0;
        }
        .report-period {
            font-size: 14px;
            color: #718096;
            margin: 5px 0 20px;
        }
        .report-meta {
            margin: 20px 0;
            padding: 15px;
            background: #f7fafc;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
        }
        .meta-item {
            text-align: center;
        }
        .meta-label {
            font-size: 12px;
            color: #718096;
        }
        .meta-value {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }
        thead tr:first-child {
            background-color: #2d3748;
        }
        thead tr:first-child th {
            color: white;
            padding: 15px;
            font-size: 18px;
        }
        thead tr:last-child {
            background-color: #4a5568;
            color: white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #e2e8f0;
        }
        th {
            font-weight: bold;
            background-color: #4a5568;
            color: white;
        }
        tbody tr:nth-child(even) {
            background-color: #f7fafc;
        }
        tbody tr:hover {
            background-color: #edf2f7;
        }
        .status-masuk {
            color: #048848;
            font-weight: 600;
            padding: 4px 8px;
            background: #def7ec;
            border-radius: 4px;
            display: inline-block;
        }
        .status-keluar {
            color: #c81e1e;
            font-weight: 600;
            padding: 4px 8px;
            background: #fde8e8;
            border-radius: 4px;
            display: inline-block;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
            color: #718096;
        }
        @media print {
            body {
                background: white;
                margin: 0;
                padding: 20px;
            }
            .report-container {
                box-shadow: none;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <h1 class="company-name">PT. MTU INDONESIA</h1>
            <h2 class="report-title">LAPORAN TRANSAKSI BAHAN BAKU</h2>
            <p class="report-period">Periode: <?= date('d F Y') ?></p>
        </div>

        <div class="report-meta">
            <div class="meta-item">
                <div class="meta-label">Total Transaksi</div>
                <div class="meta-value"><?= count($transaksi) ?></div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Total Masuk</div>
                <div class="meta-value"><?= array_reduce($transaksi, function($carry, $item) {
                    return $carry + ($item['type'] == 'masuk' ? $item['quantity'] : 0);
                }, 0) ?></div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Total Keluar</div>
                <div class="meta-value"><?= array_reduce($transaksi, function($carry, $item) {
                    return $carry + ($item['type'] == 'keluar' ? $item['quantity'] : 0);
                }, 0) ?></div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th colspan="6">DETAIL TRANSAKSI BAHAN BAKU</th>
                </tr>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="30%">Nama Bahan</th>
                    <th width="15%">Jenis</th>
                    <th width="15%">Jumlah</th>
                    <th width="20%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($transaksi as $t) : ?>
                    <tr>
                        <td style="text-align: center"><?= $i++ ?></td>
                        <td><?= date('d/m/Y', strtotime($t['transaction_date'])) ?></td>
                        <td><?= $t['nama_bahan'] ?></td>
                        <td>
                            <span class="status-<?= $t['type'] ?>">
                                <?= $t['type'] == 'masuk' ? 'Masuk' : 'Keluar' ?>
                            </span>
                        </td>
                        <td style="text-align: right"><?= number_format($t['quantity'], 0, ',', '.') ?></td>
                        <td><?= $t['notes'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="footer">
            <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
        </div>
    </div>
</body>
</html> 