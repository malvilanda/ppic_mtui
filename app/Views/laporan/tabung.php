<?= $this->include('dashboard/header') ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Laporan Stok Tabung</h2>
            <button onclick="exportToExcel()" 
                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>

        <!-- Filter Section -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Tabung</label>
                <select id="jenisTabung" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="3">Tabung 3kg</option>
                    <option value="5">Tabung 5kg</option>
                    <option value="12">Tabung 12kg</option>
                    <option value="15">Tabung 15kg</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Stok</label>
                <select id="statusStok" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="low">Stok Rendah</option>
                    <option value="normal">Stok Normal</option>
                </select>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-blue-800">Tabung 3kg</h3>
                <p class="text-2xl font-bold text-blue-900"><?= $stok_3kg ?></p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-green-800">Tabung 5kg</h3>
                <p class="text-2xl font-bold text-green-900"><?= $stok_5kg ?></p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-purple-800">Tabung 12kg</h3>
                <p class="text-2xl font-bold text-purple-900"><?= $stok_12kg ?></p>
            </div>
            <div class="bg-yellow-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-yellow-800">Tabung 15kg</h3>
                <p class="text-2xl font-bold text-yellow-900"><?= $stok_15kg ?></p>
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Tabung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Masuk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Keluar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Update</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($tabung as $item): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $item['id'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $item['name'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= number_format($item['stock'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= number_format($item['totalKeluar'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= number_format($item['stock'] - $item['totalKeluar'], 0, ',', '.') ?></td>
                        
                        
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
    const jenisTabung = document.getElementById('jenisTabung');
    const statusStok = document.getElementById('statusStok');
    const tableRows = document.querySelectorAll('tbody tr');
    
    function filterTable() {
        const selectedJenis = jenisTabung.value;
        const selectedStatus = statusStok.value;
        
        tableRows.forEach(row => {
            const jenisCell = row.querySelector('td:nth-child(2)');
            const statusCell = row.querySelector('td:nth-child(6)');
            
            const jenisMatch = !selectedJenis || jenisCell.textContent.includes(selectedJenis + 'kg');
            const statusMatch = !selectedStatus || 
                (selectedStatus === 'low' && statusCell.textContent.trim() === 'Stok Rendah') ||
                (selectedStatus === 'normal' && statusCell.textContent.trim() === 'Stok Normal');
            
            row.style.display = jenisMatch && statusMatch ? '' : 'none';
        });
    }
    
    jenisTabung.addEventListener('change', filterTable);
    statusStok.addEventListener('change', filterTable);
});

function exportToExcel() {
    window.location.href = '<?= base_url('laporan/export-tabung') ?>';
}
</script>

<?= $this->include('dashboard/footer') ?> 