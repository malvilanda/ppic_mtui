<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Daftar Surat Jalan</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Surat Jalan</li>
            </ol>
        </nav>
    </div>

    <!-- Tombol Tambah -->
    <div class="mb-3">
        <a href="/surat-jalan/create" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Buat Surat Jalan
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tabel -->
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-dark">Data Surat Jalan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="deliveryOrderTable" class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center">No. Surat Jalan</th>
                            <th class="text-center">ID Transaksi</th>
                            <th class="text-center">Tanggal Kirim</th>
                            <th>Penerima</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($suratJalan)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <img src="/assets/images/no-data.svg" alt="No Data" class="img-fluid mb-3" style="max-width: 200px">
                                    <p class="text-muted mb-0">Tidak ada data surat jalan</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($suratJalan as $sj): ?>
                            <tr>
                                <td class="text-center"><?= $sj['nomor'] ?></td>
                                <td class="text-center"><?= $sj['id_transaksi'] ?></td>
                                <td class="text-center"><?= date('d/m/Y', strtotime($sj['tanggal_kirim'])) ?></td>
                                <td><?= $sj['penerima'] ?></td>
                                <td class="text-center">
                                    <?php if($sj['status'] == 'Selesai'): ?>
                                        <span class="badge bg-success-subtle text-success px-3 py-2">Selesai</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning px-3 py-2">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="/surat-jalan/view/<?= $sj['id'] ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/surat-jalan/edit/<?= $sj['id'] ?>" class="btn btn-sm btn-warning text-white">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="hapusSuratJalan(<?= $sj['id'] ?>)" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">Aktivitas Terakhir</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr class="text-center">
                                <th class="text-uppercase fw-semibold">Tanggal</th>
                                <th class="text-uppercase fw-semibold">Item</th>
                                <th class="text-uppercase fw-semibold">Jumlah</th>
                                <th class="text-uppercase fw-semibold">Partner</th>
                                <th class="text-uppercase fw-semibold">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($aktivitas)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="text-muted mb-0">Belum ada aktivitas</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($aktivitas as $item): ?>
                                <tr>
                                    <td class="text-center"><?= $item['tanggal'] ?></td>
                                    <td><?= $item['item'] ?></td>
                                    <td class="text-center"><?= $item['jumlah'] ?></td>
                                    <td><?= $item['partner'] ?></td>
                                    <td><?= $item['catatan'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus surat jalan ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btnKonfirmasiHapus">Hapus</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function hapusSuratJalan(id) {
    const modal = new bootstrap.Modal(document.getElementById('modalHapus'));
    modal.show();
    
    document.getElementById('btnKonfirmasiHapus').onclick = function() {
        window.location.href = `/surat-jalan/delete/${id}`;
    }
}
</script>

<script>
    $(document).ready(function() {
        $('#deliveryOrderTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            order: [[2, 'desc']], // Sort by delivery date descending
            columnDefs: [
                { orderable: false, targets: 5 }, // Disable sorting on action column
                { className: 'text-center', targets: [0, 1, 2, 4, 5] }
            ],
            pageLength: 10,
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            initComplete: function() {
                // Add shadow to DataTables elements
                $('.dataTables_wrapper').addClass('shadow-sm border p-3 bg-white rounded');
            }
        });
    });
</script>
<style>
    /* Reset dan Base */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
        color: #1a1f36;
    }

    /* Card Styling */
    .card {
        border: none;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border-radius: 0.75rem;
    }

    .card-header {
        border-bottom: 1px solid #e9ecef;
    }

    /* Table Styling */
    .table {
        margin-bottom: 0;
    }

    .table thead tr {
        background-color: #f8f9fa;
    }

    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 0.75rem;
        border-bottom: 2px solid #e9ecef;
    }

    .table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }

    /* Badge Styling */
    .badge {
        font-weight: 500;
        border-radius: 6px;
    }

    .bg-success-subtle {
        background-color: #e6f4ea;
    }

    .bg-warning-subtle {
        background-color: #fff4e5;
    }

    .text-success {
        color: #1e8e3e !important;
    }

    .text-warning {
        color: #f9a825 !important;
    }

    /* Button Styling */
    .btn-group .btn {
        padding: 0.5rem;
        margin: 0 2px;
    }

    .btn-primary {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }

    .btn-warning {
        background-color: #f59e0b;
        border-color: #f59e0b;
    }

    .btn-danger {
        background-color: #ef4444;
        border-color: #ef4444;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }
        
        .table thead th {
            font-size: 0.7rem;
        }
        
        .badge {
            font-size: 0.7rem;
        }
    }
</style>
<?= $this->endSection() ?>
