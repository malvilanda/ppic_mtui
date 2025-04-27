<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?? 'Detail Delivery Order' ?></h1>
    
    <?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5>Informasi Pengiriman</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="150">No. DO</td>
                            <td>: <?= esc($transaction['delivery_order']) ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>: <?= date('d/m/Y', strtotime($transaction['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <td>Penerima</td>
                            <td>: <?= esc($transaction['receiver_name']) ?></td>
                        </tr>
                        <tr>
                            <td>No. Telepon</td>
                            <td>: <?= esc($transaction['receiver_phone']) ?></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>: <?= esc($transaction['delivery_address']) ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Informasi Barang</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="150">Nama Barang</td>
                            <td>: <?= esc($transaction['item_name']) ?></td>
                        </tr>
                        <tr>
                            <td>Jumlah</td>
                            <td>: <?= esc($transaction['quantity']) ?> unit</td>
                        </tr>
                        <tr>
                            <td>Catatan</td>
                            <td>: <?= esc($transaction['notes'] ?? '-') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 text-center">
                    <a href="<?= base_url('transaksi/print-do/' . $transaction['id']) ?>" class="btn btn-primary" target="_blank">
                        <i class="fas fa-print"></i> Cetak Delivery Order
                    </a>
                    <a href="<?= base_url('transaksi/tabung') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 