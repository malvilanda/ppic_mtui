<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900">Persetujuan Transaksi</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No. Transaksi</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(empty($transactions)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500 bg-gray-50">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p>Tidak ada data transaksi yang perlu disetujui</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($transactions as $trans): ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-200 ease-in-out">
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $no++ ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= date('d/m/Y H:i', strtotime($trans['created_at'])) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium"><?= $trans['delivery_order'] ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $trans['type'] === 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= ucfirst($trans['type']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= $trans['client_name'] ?? '-' ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Menunggu Persetujuan
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium space-x-2">
                                <button onclick="showApprovalModal('<?= $trans['id'] ?>')" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <i class="fas fa-check mr-1.5"></i>
                                    Setujui
                                </button>
                                <button onclick="showRejectModal('<?= $trans['id'] ?>')" class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <i class="fas fa-times mr-1.5"></i>
                                    Tolak
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Approval -->
<div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Konfirmasi Persetujuan</h3>
        <p class="text-sm text-gray-600 mb-4">Apakah Anda yakin ingin menyetujui transaksi ini?</p>
        <form action="<?= base_url('approval/approve') ?>" method="POST">
            <input type="hidden" name="transaction_id" id="approvalTransactionId">
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="hideApprovalModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Setujui
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Konfirmasi Penolakan</h3>
        <form action="<?= base_url('approval/reject') ?>" method="POST">
            <input type="hidden" name="transaction_id" id="rejectTransactionId">
            <div class="mb-4">
                <label for="reject_reason" class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                <textarea name="reject_reason" id="reject_reason" rows="3" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md" required></textarea>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="hideRejectModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showApprovalModal(transactionId) {
    document.getElementById('approvalTransactionId').value = transactionId;
    document.getElementById('approvalModal').classList.remove('hidden');
    document.getElementById('approvalModal').classList.add('flex');
}

function hideApprovalModal() {
    document.getElementById('approvalModal').classList.remove('flex');
    document.getElementById('approvalModal').classList.add('hidden');
}

function showRejectModal(transactionId) {
    document.getElementById('rejectTransactionId').value = transactionId;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.remove('flex');
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('reject_reason').value = '';
}
</script>

<?= $this->endSection() ?> 