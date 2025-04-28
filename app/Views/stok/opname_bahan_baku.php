<?= $this->include('dashboard/header') ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold mb-6">Stok Opname Bahan Baku</h2>
        
        <?php if (session()->getFlashdata('pesan')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= session()->getFlashdata('pesan') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('stok/simpan_opname_bahan'); ?>" method="POST" class="space-y-6">
            <?= csrf_field(); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" name="tanggal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <!-- Bahan Baku -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bahan Baku</label>
                    <select name="bahan_id" id="bahan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih Bahan Baku</option>
                        <?php foreach ($bahan_baku as $bb): ?>
                            <option value="<?= $bb['id'] ?>" data-stock="<?= $bb['stock'] ?>">
                                <?= $bb['id'] . ' - ' . $bb['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Stok Sistem -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok Sistem</label>
                    <input type="number" name="stok_sistem" id="stok_sistem" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm" readonly>
                </div>

                <!-- Stok Fisik -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok Fisik</label>
                    <input type="number" name="stok_fisik" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <!-- Keterangan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
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
        <h3 class="text-xl font-semibold mb-4">Riwayat Opname Bahan Baku</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Bahan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Bahan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Sistem</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Fisik</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Selisih</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(empty($opname_bahan)): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data opname
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $i = 1; ?>
                        <?php foreach ($opname_bahan as $ob): ?>
                        <tr>
                            <td class="px-6 py-4"><?= $i++; ?></td>
                            <td class="px-6 py-4"><?= date('d/m/Y', strtotime($ob['tanggal'])) ?></td>
                            <td class="px-6 py-4"><?= $ob['kode_bahan'] ?></td>
                            <td class="px-6 py-4"><?= $ob['nama_bahan'] ?></td>
                            <td class="px-6 py-4"><?= number_format($ob['stok_sistem']) ?></td>
                            <td class="px-6 py-4"><?= number_format($ob['stok_fisik']) ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $ob['selisih'] >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= number_format($ob['selisih']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4"><?= $ob['keterangan'] ?? '-' ?></td>
                            <td class="px-6 py-4">
                                <button type="button" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 mr-1" data-bs-toggle="modal" data-bs-target="#formEditOpname<?= $ob['id']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="<?= base_url('stok/hapus_opname_bahan/' . $ob['id']); ?>" method="post" class="inline">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" onclick="return confirm('Apakah anda yakin?');">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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
    const bahanSelect = document.getElementById('bahan');
    const stokSistem = document.getElementById('stok_sistem');

    bahanSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (this.value) {
            const stockValue = selectedOption.getAttribute('data-stock');
            stokSistem.value = stockValue || '0';
        } else {
            stokSistem.value = '';
        }
    });
});
</script>

<?= $this->include('dashboard/footer') ?> 