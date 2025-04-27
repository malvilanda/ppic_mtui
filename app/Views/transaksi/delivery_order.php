<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Jalan #<?= $transaction['id'] ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
        }
        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            text-decoration: underline;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .signatures {
            margin-top: 50px;
        }
        .signature-box {
            float: left;
            width: 30%;
            text-align: center;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name"><?= $company['name'] ?></div>
        <div><?= $company['address'] ?></div>
        <div>Telp: <?= $company['phone'] ?> | Email: <?= $company['email'] ?></div>
    </div>

    <div class="document-title">SURAT JALAN</div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">No. Surat Jalan</span>: SJ-<?= date('Ymd', strtotime($transaction['transaction_date'])) ?>-<?= str_pad($transaction['id'], 4, '0', STR_PAD_LEFT) ?>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal</span>: <?= date('d/m/Y', strtotime($transaction['transaction_date'])) ?>
        </div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Kepada</span>: <?= $transaction['client_name'] ?> (<?= $transaction['client_code'] ?>)
        </div>
        <div class="info-row">
            <span class="info-label">Alamat</span>: <?= $transaction['delivery_address'] ?>
        </div>
        <div class="info-row">
            <span class="info-label">PIC Penerima</span>: <?= $transaction['receiver_name'] ?>
        </div>
        <div class="info-row">
            <span class="info-label">No. Telepon</span>: <?= $transaction['receiver_phone'] ?>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td><?= $transaction['item_name'] ?></td>
                <td><?= number_format($transaction['quantity']) ?></td>
                <td>unit</td>
                <td><?= $transaction['notes'] ?: '-' ?></td>
            </tr>
        </tbody>
    </table>

    <div class="signatures">
        <div class="signature-box">
            <div>Pengirim,</div>
            <div class="signature-line"><?= $transaction['pic_name'] ?></div>
        </div>
        <div class="signature-box">
            <div>Pengangkut,</div>
            <div class="signature-line">_____________</div>
        </div>
        <div class="signature-box">
            <div>Penerima,</div>
            <div class="signature-line"><?= $transaction['receiver_name'] ?></div>
        </div>
        <div class="clear"></div>
    </div>

    <div style="margin-top: 30px; font-size: 10px;">
        <i>* Surat jalan ini adalah bukti resmi pengiriman barang. Harap disimpan dengan baik.</i>
    </div>
</body>
</html> 