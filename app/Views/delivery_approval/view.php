<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="<?= base_url('delivery-approval') ?>" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Delivery Order Details -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Detail Surat Jalan</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. Surat Jalan</label>
                        <p class="mt-1 text-sm text-gray-900"><?= $approval['delivery_order'] ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Permintaan</label>
                        <p class="mt-1 text-sm text-gray-900"><?= date('d M Y H:i', strtotime($approval['requested_at'])) ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-1">
                            <?php
                            $statusClass = match($approval['status']) {
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                            $statusText = match($approval['status']) {
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'pending' => 'Menunggu Persetujuan',
                                default => 'Unknown'
                            };
                            ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                                <?= $statusText ?>
                            </span>
                        </p>
                    </div>
                    <?php if ($approval['status'] !== 'pending'): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal <?= $approval['status'] === 'approved' ? 'Persetujuan' : 'Penolakan' ?></label>
                        <p class="mt-1 text-sm text-gray-900"><?= date('d M Y H:i', strtotime($approval['approved_at'])) ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <p class="mt-1 text-sm text-gray-900"><?= $approval['notes'] ?? '-' ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Transaction Details -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Detail Transaksi</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Item</label>
                        <p class="mt-1 text-sm text-gray-900"><?= $approval['item_name'] ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                        <p class="mt-1 text-sm text-gray-900"><?= number_format($approval['quantity']) ?> unit</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Client</label>
                        <p class="mt-1 text-sm text-gray-900"><?= $approval['client_name'] ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Diminta Oleh</label>
                        <p class="mt-1 text-sm text-gray-900"><?= $approval['requester_name'] ?></p>
                    </div>
                    <?php if ($approval['status'] !== 'pending'): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700"><?= $approval['status'] === 'approved' ? 'Disetujui' : 'Ditolak' ?> Oleh</label>
                        <p class="mt-1 text-sm text-gray-900"><?= $approval['approver_name'] ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($approval['status'] === 'pending' && $is_manager): ?>
        <div class="mt-8 flex justify-end space-x-4">
            <button onclick="showRejectModal(<?= $approval['id'] ?>)" 
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="fas fa-times mr-2"></i>Tolak
            </button>
            <button onclick="showApprovalModal(<?= $approval['id'] ?>)" 
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-check mr-2"></i>Setujui
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Approval Modal -->
<div id="approvalModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="approvalForm" onsubmit="submitApproval(event)">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Setujui Surat Jalan</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan (opsional)
                        </label>
                        <textarea name="notes" rows="3" 
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Setujui
                    </button>
                    <button type="button" onclick="hideApprovalModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="rejectForm" onsubmit="submitReject(event)">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Surat Jalan</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Penolakan <span class="text-red-600">*</span>
                        </label>
                        <textarea name="notes" rows="3" required
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Tolak
                    </button>
                    <button type="button" onclick="hideRejectModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentApprovalId = <?= $approval['id'] ?>;

function showApprovalModal() {
    document.getElementById('approvalModal').classList.remove('hidden');
}

function hideApprovalModal() {
    document.getElementById('approvalModal').classList.add('hidden');
    document.getElementById('approvalForm').reset();
}

function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

function submitApproval(event) {
    event.preventDefault();
    const form = event.target;
    const notes = form.querySelector('textarea[name="notes"]').value;

    fetch(`/delivery-approval/approve/${currentApprovalId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ notes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses persetujuan');
    });
}

function submitReject(event) {
    event.preventDefault();
    const form = event.target;
    const notes = form.querySelector('textarea[name="notes"]').value;

    if (!notes.trim()) {
        alert('Alasan penolakan harus diisi');
        return;
    }

    fetch(`/delivery-approval/reject/${currentApprovalId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ notes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses penolakan');
    });
}
</script>
<?= $this->endSection() ?> 