<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Transaksi Masuk</h1>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= session()->getFlashdata('success'); ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= session()->getFlashdata('error'); ?></span>
            </div>
        <?php endif; ?>

        <!-- Form Transaksi Masuk -->
        <form action="<?= base_url('transaksi/bahan-baku/save'); ?>" method="POST" class="space-y-6">
            <input type="hidden" name="type" value="masuk">
            
            <!-- Pilih Item -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Item</label>
                <select name="item_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Pilih Item</option>
                    <?php foreach ($items as $item): ?>
                        <option value="<?= $item['id'] ?>">
                            <?= $item['name'] ?> (Stok: <?= $item['stock'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Pilih Gudang -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Gudang</label>
                <select name="warehouse_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Pilih Gudang</option>
                    <?php foreach ($warehouses as $warehouse): ?>
                        <option value="<?= $warehouse['id'] ?>"><?= $warehouse['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Jumlah -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                <input type="number" name="quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required min="1">
            </div>

            <!-- Unit -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                <select name="unit_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Pilih Unit</option>
                    <?php foreach ($units as $unit): ?>
                        <option value="<?= $unit['id'] ?>"><?= $unit['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>

            <!-- Tombol Submit -->
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Simpan Transaksi
                </button>
            </div>
        </form>

        <!-- Tabel Riwayat Transaksi -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4">Riwayat Transaksi Masuk</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gudang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PIC</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($transactions as $transaction): ?>
                            <?php if ($transaction['type'] === 'masuk'): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= date('d/m/Y H:i', strtotime($transaction['created_at'])) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= $transaction['item_name'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= $transaction['warehouse_name'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= $transaction['quantity'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= $transaction['unit_name'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= $transaction['pic_name'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= $transaction['notes'] ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?> 