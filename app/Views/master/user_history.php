<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Riwayat User</h2>
            <a href="<?= base_url('master/user-history/export') ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-file-excel mr-2"></i>
                Export Excel
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Username</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aktivitas</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">IP Address</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(empty($history)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 bg-gray-50">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p>Tidak ada data riwayat user</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($history as $item): ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-200 ease-in-out">
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $no++ ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= date('d/m/Y H:i:s', strtotime($item['created_at'])) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $item['username'] ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= $item['activity'] ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= $item['ip_address'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?> 