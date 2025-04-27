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
        .security-pos {
            margin-top: 30px;
            border: 1px solid #000;
            padding: 15px;
            background-color: #fff;
        }
        .security-pos-title {
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
            text-decoration: underline;
        }
        .security-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .security-table th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
            padding: 8px;
            border: 1px solid #000;
        }
        .security-table td {
            text-align: center;
            padding: 20px 8px;
            border: 1px solid #000;
            vertical-align: top;
            position: relative;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 5px;
            width: 100%;
            position: absolute;
            bottom: 30px;
            left: 0;
        }
        .security-note {
            margin-top: 10px;
            font-size: 10px;
            text-align: center;
            font-style: italic;
            color: #666;
        }
        .security-info {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
            position: absolute;
            bottom: 10px;
            left: 0;
            width: 100%;
            text-align: center;
        }
        .security-header {
            background-color: #f8f8f8;
            padding: 5px;
            margin-bottom: 5px;
            border-radius: 3px;
        }
        .signature-container {
            position: relative;
            height: 60px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php if ($showCompanyData): ?>
    <div class="header">
        <div class="company-name"><?= $company['name'] ?></div>
        <div><?= $company['address'] ?></div>
        <div>Telp: <?= $company['phone'] ?> | Email: <?= $company['email'] ?></div>
    </div>
    <?php endif; ?>

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
            <span class="info-label">Kepada</span>: <?= !$showCompanyData ? $transaction['client_code'] : $transaction['client_name'] . ' (' . $transaction['client_code'] . ')' ?>
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
            <div class="signature-line"></div>
            <div>Penerima</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div>Pengirim</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div>Mengetahui</div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="security-pos">
        <div class="security-pos-title">SECURITY POS</div>
        <table class="security-table">
            <thead>
                <tr>
                    <th style="width: 25%">Security</th>
                    <th style="width: 25%">Nama Sopir</th>
                    <th style="width: 20%">No. Polisi</th>
                    <th style="width: 15%">Jam Masuk</th>
                    <th style="width: 15%">Jam Keluar</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="security-header">Petugas Security</div>
                        <div class="signature-container">
                            <div class="signature-line"></div>
                            <div class="security-info">(Tanda Tangan & Nama Jelas)</div>
                        </div>
                    </td>
                    <td>
                        <div class="security-header">Nama Pengemudi</div>
                        <div class="signature-container">
                            <div class="signature-line"></div>
                            <div class="security-info">(Nama Lengkap)</div>
                        </div>
                    </td>
                    <td>
                        <div class="security-header">Nomor Kendaraan</div>
                        <div class="signature-container">
                            <div class="signature-line"></div>
                            <div class="security-info">(Contoh: B 1234 ABC)</div>
                        </div>
                    </td>
                    <td>
                        <div class="security-header">Waktu Masuk</div>
                        <div class="signature-container">
                            <div class="signature-line"></div>
                            <div class="security-info">(HH:MM)</div>
                        </div>
                    </td>
                    <td>
                        <div class="security-header">Waktu Keluar</div>
                        <div class="signature-container">
                            <div class="signature-line"></div>
                            <div class="security-info">(HH:MM)</div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="security-note">
            * Harap diisi lengkap dan ditandatangani oleh petugas security. Dokumen ini merupakan bukti sah keluar masuk kendaraan.
        </div>
    </div>

    <div style="margin-top: 30px; font-size: 10px;">
        <i>* Surat jalan ini adalah bukti resmi pengiriman barang. Harap disimpan dengan baik.</i>
    </div>
</body>
</html> 