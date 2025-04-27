<?= $this->include('dashboard/header') ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold mb-6">Stok Opname Tabung</h2>
        
        <?php if (session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= session('success') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('stok/opname/save') ?>" method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jenis Tabung -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Tabung</label>
                    <select name="item_id" id="itemSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih Jenis Tabung</option>
                        <?php foreach ($items as $item): ?>
                            <option value="<?= $item['id'] ?>" data-stock="<?= $item['stock'] ?>">
                                <?= $item['name'] ?> (Stok Sistem: <?= $item['stock'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Gudang -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gudang</label>
                    <select name="warehouse_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih Gudang</option>
                        <?php foreach ($warehouses as $warehouse): ?>
                            <option value="<?= $warehouse['id'] ?>"><?= $warehouse['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Stok Sistem -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok Sistem</label>
                    <input type="number" name="system_stock" id="systemStock" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm" readonly>
                </div>

                <!-- Stok Aktual -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok Aktual</label>
                    <input type="number" name="actual_stock" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <!-- Catatan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <!-- Created By -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">PIC Opname</label>
                    <input type="text" name="created_by" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Simpan Opname
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Riwayat Opname -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold mb-4">Riwayat Opname Tabung</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
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
                                Tidak ada data opname
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($opname_history as $opname): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?= date('d/m/Y H:i:s', strtotime($opname['opname_date'])) ?></td>
                            <td class="px-6 py-4"><?= $opname['item_name'] ?></td>
                            <td class="px-6 py-4"><?= $opname['warehouse_name'] ?></td>
                            <td class="px-6 py-4"><?= number_format($opname['system_stock']) ?></td>
                            <td class="px-6 py-4"><?= number_format($opname['actual_stock']) ?></td>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.getElementById('itemSelect');
    const systemStock = document.getElementById('systemStock');

    itemSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        systemStock.value = selectedOption.dataset.stock || 0;
    });
});
</script>

<?= $this->include('dashboard/footer') ?> 