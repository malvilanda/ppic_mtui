<?= $this->include('dashboard/header') ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Laporan Stok Opname <?= ucfirst($type) ?></h2>
            <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                <i class="fas fa-print mr-2"></i>Cetak
            </button>
        </div>

        <!-- Filter Form -->
        <form action="" method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="tabung" <?= $type == 'tabung' ? 'selected' : '' ?>>Tabung</option>
                    <option value="bahan_baku" <?= $type == 'bahan_baku' ? 'selected' : '' ?>>Bahan Baku</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="<?= $start_date ?>" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                <input type="date" name="end_date" value="<?= $end_date ?>" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 w-full">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </div>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gudang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Sistem</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Aktual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Selisih</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">PIC</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(empty($opname_history)): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data opname untuk periode ini
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($opname_history as $opname): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?= date('d/m/Y H:i', strtotime($opname['opname_date'])) ?></td>
                            <td class="px-6 py-4"><?= $opname['item_name'] ?></td>
                            <td class="px-6 py-4"><?= $opname['warehouse_name'] ?></td>
                            <td class="px-6 py-4 text-right"><?= number_format($opname['system_stock']) ?></td>
                            <td class="px-6 py-4 text-right"><?= number_format($opname['actual_stock']) ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $opname['difference'] >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= number_format($opname['difference']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4"><?= $opname['created_by'] ?></td>
                            <td class="px-6 py-4"><?= $opname['notes'] ?? '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style type="text/css" media="print">
    @page { size: landscape; }
    .no-print { display: none !important; }
    .container { max-width: none !important; padding: 0 !important; }
    .shadow-md { box-shadow: none !important; }
    button { display: none !important; }
    form { display: none !important; }
</style>

<?= $this->include('dashboard/footer') ?> 