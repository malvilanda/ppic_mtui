<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><?= $title ?></h1>
            <p class="text-gray-600 mt-1">Laporan transaksi tabung masuk dan keluar</p>
        </div>
        <div class="flex space-x-3">
            <a href="<?= base_url('laporan/exportTabung?' . $_SERVER['QUERY_STRING']) ?>" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-file-excel mr-2"></i>
                Export Excel
            </a>
            <a href="<?= base_url('laporan/downloadPdfTabung?' . $_SERVER['QUERY_STRING']) ?>" 
               class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-file-pdf mr-2"></i>
                Download PDF
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Transaksi -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-blue-600 uppercase">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2"><?= count($transaksi) ?></p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-flask text-xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Masuk -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-green-600 uppercase">Total Masuk</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2">
                        <?= array_reduce($transaksi, function($carry, $item) {
                            return $carry + ($item['type'] == 'masuk' ? $item['quantity'] : 0);
                        }, 0) ?>
                    </p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-arrow-up text-xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Keluar -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-red-600 uppercase">Total Keluar</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2">
                        <?= array_reduce($transaksi, function($carry, $item) {
                            return $carry + ($item['type'] == 'keluar' ? $item['quantity'] : 0);
                        }, 0) ?>
                    </p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-arrow-down text-xl text-red-600"></i>
                </div>
            </div>
        </div>

        <!-- Rata-rata Transaksi -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-purple-600 uppercase">Rata-rata Transaksi</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2">
                        <?= count($transaksi) > 0 ? number_format(array_reduce($transaksi, function($carry, $item) {
                            return $carry + $item['quantity'];
                        }, 0) / count($transaksi), 1) : 0 ?>
                    </p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-chart-line text-xl text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-filter mr-2 text-gray-600"></i>Filter Laporan
            </h2>
        </div>
        <div class="p-6">
            <form action="" method="get" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Tanggal Mulai -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                            <input type="date" id="start_date" name="start_date" 
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   value="<?= $_GET['start_date'] ?? '' ?>" required>
                        </div>
                    </div>

                    <!-- Tanggal Akhir -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                            <input type="date" id="end_date" name="end_date" 
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   value="<?= $_GET['end_date'] ?? '' ?>" required>
                        </div>
                    </div>

                    <!-- Jenis Transaksi -->
                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Jenis Transaksi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-exchange-alt text-gray-400"></i>
                            </div>
                            <select id="jenis" name="jenis" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none">
                                <option value="">Semua Transaksi</option>
                                <option value="masuk" <?= ($_GET['jenis'] ?? '') == 'masuk' ? 'selected' : '' ?>>Masuk</option>
                                <option value="keluar" <?= ($_GET['jenis'] ?? '') == 'keluar' ? 'selected' : '' ?>>Keluar</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Field Klien (Muncul ketika Keluar dipilih) -->
                    <div id="clientField" class="<?= ($_GET['jenis'] ?? '') != 'keluar' ? 'hidden' : '' ?>">
                        <label for="client" class="block text-sm font-medium text-gray-700 mb-2">Klien</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" id="client" name="client" 
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   value="<?= $_GET['client'] ?? '' ?>" 
                                   placeholder="Nama Klien">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-search mr-2"></i>
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-table mr-2 text-gray-600"></i>Data Transaksi Tabung
            </h2>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table id="dataTable" class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tabung</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $i = 1; ?>
                        <?php foreach ($transaksi as $t) : ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500"><?= $i++ ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($t['transaction_date'])) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $t['nama_tabung'] ?? '(Tidak ada nama)' ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($t['type'] == 'masuk') : ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-arrow-up mr-1"></i>Masuk
                                        </span>
                                    <?php else : ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-arrow-down mr-1"></i>Keluar
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500"><?= number_format($t['quantity'], 0, ',', '.') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $t['type'] == 'keluar' ? ($t['nama_client'] ?? '-') : '-' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $t['notes'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    // Initialize DataTable with custom settings
    $('#dataTable').DataTable({
        pageLength: 25,
        ordering: true,
        info: true,
        searching: true,
        language: {
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan halaman _PAGE_ dari _PAGES_",
            infoEmpty: "Tidak ada data yang tersedia",
            infoFiltered: "(difilter dari _MAX_ total data)",
            search: "Pencarian:",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });

    // Date range validation
    $('#end_date').on('change', function() {
        var startDate = $('#start_date').val();
        var endDate = $(this).val();
        
        if (startDate && endDate && startDate > endDate) {
            alert('Tanggal akhir harus lebih besar dari tanggal mulai');
            $(this).val('');
        }
    });

    // Toggle client field based on transaction type
    $('#jenis').on('change', function() {
        if ($(this).val() === 'keluar') {
            $('#clientField').removeClass('hidden').addClass('block');
        } else {
            $('#clientField').removeClass('block').addClass('hidden');
            $('#client').val('');
        }
    });
});
</script>
<?= $this->endSection(); ?> 