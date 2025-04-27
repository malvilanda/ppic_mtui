<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title; ?></h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itemModal">
                <i class="fas fa-plus"></i> Tambah Barang Baru
            </button>
        </div>
        <div class="card-body">
            <table id="itemsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Deskripsi</th>
                        <th>Satuan</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= esc($item['name']); ?></td>
                        <td><?= esc($item['description']); ?></td>
                        <td><?= esc($item['unit']); ?></td>
                        <td><?= esc($item['stock']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-item" data-id="<?= $item['id']; ?>"
                                data-name="<?= esc($item['name']); ?>"
                                data-description="<?= esc($item['description']); ?>"
                                data-unit="<?= esc($item['unit']); ?>"
                                data-stock="<?= esc($item['stock']); ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-item" data-id="<?= $item['id']; ?>">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemModalLabel">Tambah Barang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="itemForm">
                <div class="modal-body">
                    <input type="hidden" id="item_id" name="item_id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="unit" class="form-label">Satuan</label>
                        <input type="text" class="form-control" id="unit" name="unit" required>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok Awal</label>
                        <input type="number" class="form-control" id="stock" name="stock" required min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    // Initialize DataTable dengan bahasa Indonesia
    $('#itemsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        }
    });

    // Form submission
    $('#itemForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#item_id').val();
        const url = id ? '<?= base_url('item/update/') ?>' + id : '<?= base_url('item/save') ?>';

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.messages);
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat memproses permintaan');
            }
        });
    });

    // Edit item
    $('.edit-item').on('click', function() {
        const id = $(this).data('id');
        $('#item_id').val(id);
        $('#name').val($(this).data('name'));
        $('#description').val($(this).data('description'));
        $('#unit').val($(this).data('unit'));
        $('#stock').val($(this).data('stock'));
        $('#itemModalLabel').text('Edit Barang');
        $('#itemModal').modal('show');
    });

    // Delete item
    $('.delete-item').on('click', function() {
        if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
            const id = $(this).data('id');
            $.ajax({
                url: '<?= base_url('item/delete/') ?>' + id,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.messages);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menghapus barang');
                }
            });
        }
    });

    // Reset form when modal is closed
    $('#itemModal').on('hidden.bs.modal', function() {
        $('#itemForm')[0].reset();
        $('#item_id').val('');
        $('#itemModalLabel').text('Tambah Barang Baru');
    });
});
</script>
<?= $this->endSection(); ?> 