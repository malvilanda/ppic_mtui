<?= $this->include('dashboard/header') ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Laporan Stok Bahan Baku</h2>
            <button onclick="exportToExcel()" 
                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>

        <!-- Filter -->
        <div class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Stok</label>
                    <select id="statusFilter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua</option>
                        <option value="low">Stok Rendah</option>
                        <option value="normal">Stok Normal</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tabel -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bahan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Saat Ini</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Minimum Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Update</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $item['part_number'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $item['name'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= number_format($item['stock'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= number_format($item['minimum_stock'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($item['stock'] <= $item['minimum_stock']): ?>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Stok Rendah</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Stok Normal</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= date('d/m/Y H:i', strtotime($item['updated_at'])) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const tableRows = document.querySelectorAll('tbody tr');
    
    statusFilter.addEventListener('change', function() {
        const selectedStatus = this.value;
        
        tableRows.forEach(row => {
            const statusCell = row.querySelector('td:nth-child(5)');
            const statusText = statusCell.textContent.trim().toLowerCase();
            
            if (selectedStatus === '' || 
                (selectedStatus === 'low' && statusText === 'stok rendah') ||
                (selectedStatus === 'normal' && statusText === 'stok normal')) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});

function exportToExcel() {
    window.location.href = '<?= base_url('laporan/export-bahan-baku') ?>';
}
</script>

<?= $this->include('dashboard/footer') ?> 