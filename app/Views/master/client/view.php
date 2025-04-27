<?= $this->include('dashboard/header') ?>

<div class="container mx-auto px-4 py-8">
    <!-- Detail Client -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Detail Client</h2>
            <div class="space-x-2">
                <a href="<?= base_url('master/client/edit/' . $client['id']) ?>" 
                   class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Edit Client
                </a>
                <a href="<?= base_url('master/client') ?>" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold mb-4">Informasi Client</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500">Kode Client</label>
                        <p class="text-gray-900"><?= $client['code'] ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Nama Client</label>
                        <p class="text-gray-900"><?= $client['name'] ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Status</label>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $client['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= ucfirst($client['status']) ?>
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500">PIC</label>
                        <p class="text-gray-900"><?= $client['pic_name'] ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Telepon</label>
                        <p class="text-gray-900"><?= $client['phone'] ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Email</label>
                        <p class="text-gray-900"><?= $client['email'] ?: '-' ?></p>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold mb-4">Alamat</h3>
                <p class="text-gray-900"><?= nl2br($client['address']) ?></p>
            </div>
        </div>
    </div>

    <!-- Riwayat Transaksi -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold mb-4">Riwayat Transaksi</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gudang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Belum ada transaksi
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $trans): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?= date('d/m/Y H:i', strtotime($trans['transaction_date'])) ?>
                            </td>
                            <td class="px-6 py-4"><?= $trans['item_name'] ?></td>
                            <td class="px-6 py-4"><?= $trans['warehouse_name'] ?></td>
                            <td class="px-6 py-4"><?= number_format($trans['quantity']) ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $trans['type'] === 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= ucfirst($trans['type']) ?>
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