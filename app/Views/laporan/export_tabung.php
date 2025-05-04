<table border="1">
    <thead>
        <tr>
            <th colspan="6" style="text-align: center; font-size: 14pt; font-weight: bold;">LAPORAN TRANSAKSI TABUNG</th>
        </tr>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Tabung</th>
            <th>Jenis</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        <?php foreach ($transaksi as $t) : ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= date('d/m/Y', strtotime($t['transaction_date'])) ?></td>
                <td><?= $t['nama_tabung'] ?></td>
                <td><?= $t['type'] == 'masuk' ? 'Masuk' : 'Keluar' ?></td>
                <td><?= $t['quantity'] ?></td>
                <td><?= $t['notes'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table> 