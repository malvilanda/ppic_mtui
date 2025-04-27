<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Transaction Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url(); ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Transaction</li>
    </ol>

    <div class="row">
        <!-- Transaction Form Card -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-edit me-1"></i>
                    Transaction Form
                </div>
                <div class="card-body">
                    <form id="transactionForm" method="post">
                        <div class="mb-3">
                            <label for="item" class="form-label">Item</label>
                            <select class="form-select" id="item" name="item_id" required>
                                <option value="">Select Item</option>
                                <?php foreach ($items as $item): ?>
                                    <option value="<?= $item['id']; ?>"><?= $item['name']; ?> (Stock: <?= $item['stock']; ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Transaction Type</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="type" id="type_in" value="in" required>
                                <label class="btn btn-outline-success" for="type_in">Stock In</label>
                                <input type="radio" class="btn-check" name="type" id="type_out" value="out">
                                <label class="btn btn-outline-danger" for="type_out">Stock Out</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit Transaction</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Recent Transactions Card -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Recent Transactions
                </div>
                <div class="card-body">
                    <table id="transactionsTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Item</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Description</th>
                                <th>Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_transactions as $transaction): ?>
                                <tr>
                                    <td><?= date('Y-m-d H:i', strtotime($transaction['created_at'])); ?></td>
                                    <td><?= $transaction['item_name']; ?></td>
                                    <td>
                                        <?php if ($transaction['type'] == 'in'): ?>
                                            <span class="badge bg-success">Stock In</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Stock Out</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $transaction['quantity']; ?></td>
                                    <td><?= $transaction['description']; ?></td>
                                    <td><?= $transaction['user_name']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#transactionsTable').DataTable({
        order: [[0, 'desc']],
        pageLength: 10,
        responsive: true
    });

    // Form submission handling
    $('#transactionForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '<?= base_url('transaction/save'); ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while processing your request.'
                });
            }
        });
    });

    // Stock validation for outgoing transactions
    $('#type_out').on('change', function() {
        let selectedItem = $('#item option:selected');
        let currentStock = parseInt(selectedItem.text().match(/Stock: (\d+)/)[1]);
        $('#quantity').attr('max', currentStock);
    });
});
</script>
<?= $this->endSection(); ?> 