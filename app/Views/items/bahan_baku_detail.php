<?= $this->include('dashboard/header') ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Detail Stok Bahan Baku</h2>
            <a href="<?= base_url('dashboard/export-bahan-baku') ?>" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Part Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Minimum Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Stok</th>
                        <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Kadaluarsa</th> -->
                        <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th> -->
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data bahan baku
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($items as $item): ?>
                            <tr>
                                <td class="px-6 py-4"><?= $no++ ?></td>
                                <td class="px-6 py-4"><?= $item['part_number'] ?></td>
                                <td class="px-6 py-4"><?= $item['name'] ?></td>
                                <td class="px-6 py-4"><?= number_format($item['stock']) ?></td>
                                <td class="px-6 py-4"><?= number_format($item['minimum_stock']) ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        <?= $item['status'] === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' ?>">
                                        <?= $item['status'] === 'warning' ? 'Stok Menipis' : 'Normal' ?>
                                    </span>
                                </td>
                                
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tambahkan fungsi untuk highlight row jika stok menipis atau kadaluarsa
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const statusStok = row.querySelector('td:nth-child(6) span').textContent.trim();
        const statusExpired = row.querySelector('td:nth-child(8) span').textContent.trim();
        
        if (statusStok === 'Stok Menipis') {
            row.classList.add('bg-yellow-50');
        }
        // if (statusExpired === 'Kadaluarsa') {
        //     row.classList.add('bg-red-50');
        // }
    });
});
</script> 