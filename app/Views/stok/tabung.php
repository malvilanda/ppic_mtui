<?= $this->include('dashboard/header') ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-semibold">Stok Tabung Gas</h2>
                <p class="text-gray-500">
                    Periode: <?= date('F Y', mktime(0, 0, 0, isset($current_month) ? $current_month : date('m'), 1, isset($current_year) ? $current_year : date('Y'))) ?>
                </p>
            </div>
            <!-- <a href="<?= base_url('transaksi/tabung') ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                + Transaksi Baru
            </a> -->
        </div>

        <!-- Filter Bulan dan Tahun -->
        <div class="mb-6">
            <form id="filterForm" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                    <select id="month" name="month" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <?php
                        $currentMonth = isset($current_month) ? $current_month : date('m');
                        for ($i = 1; $i <= 12; $i++) {
                            $month = str_pad($i, 2, '0', STR_PAD_LEFT);
                            $selected = $month == $currentMonth ? 'selected' : '';
                            $monthName = date('F', mktime(0, 0, 0, $i, 1));
                            echo "<option value=\"$month\" $selected>$monthName</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                    <select id="year" name="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <?php
                        $currentYear = isset($current_year) ? $current_year : date('Y');
                        for ($i = $currentYear - 2; $i <= $currentYear; $i++) {
                            $selected = $i == $currentYear ? 'selected' : '';
                            echo "<option value=\"$i\" $selected>$i</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Ringkasan Stok -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <?php 
            $total_3kg = 0;
            $total_12kg = 0;
            $total_3kg_keluar = 0;
            $total_12kg_keluar = 0;
            
            foreach ($items as $item) {
                if (stripos($item['name'], '3kg') !== false) {
                    $total_3kg += $item['stock'];
                    $total_3kg_keluar += $item['total_keluar'];
                } else if (stripos($item['name'], '12kg') !== false) {
                    $total_12kg += $item['stock'];
                    $total_12kg_keluar += $item['total_keluar'];
                }
            }
            ?>
            <!-- Tabung 3kg -->
            <div class="bg-gray-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-flask fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500">Total Tabung 3kg</p>
                        <p class="text-2xl font-semibold"><?= number_format($total_3kg - $total_3kg_keluar) ?></p>
                    </div>
                </div>
            </div>

            <!-- Tabung 12kg -->
            <div class="bg-gray-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-flask fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500">Total Tabung 12kg</p>
                        <p class="text-2xl font-semibold"><?= number_format($total_12kg - $total_12kg_keluar) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Stok -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Tabung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Saat Ini</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Minimum Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Update</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?= $item['name'] ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?= number_format($item['stock'] - $item['total_keluar']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?= number_format($item['minimum_stock']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($item['stock'] <= $item['minimum_stock']): ?>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Stok Rendah
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Stok Aman
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500"><?= date('d/m/Y H:i', strtotime($item['updated_at'])) ?></div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Grafik Stok -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold mb-4">
            Grafik Perbandingan Stok
            <span class="text-gray-500 font-normal text-base ml-2">
                Periode: <?= date('F Y', mktime(0, 0, 0, isset($current_month) ? $current_month : date('m'), 1, isset($current_year) ? $current_year : date('Y'))) ?>
            </span>
        </h3>
        <canvas id="stockChart" height="300"></canvas>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi grafik
    updateChart();
    
    // Filter handler
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updateData();
    });
    
    function updateData() {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;
        
        window.location.href = `<?= base_url('stok/tabung') ?>?month=${month}&year=${year}`;
    }
    
    function updateChart() {
        const ctx = document.getElementById('stockChart').getContext('2d');
        const items = <?= json_encode($items) ?>;
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: items.map(item => item.name),
                datasets: [
                    {
                        label: 'Stok Saat Ini',
                        data: items.map(item => item.stock - item.total_keluar),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    },
                    {
                        label: 'Minimum Stok',
                        data: items.map(item => item.minimum_stock),
                        backgroundColor: 'rgba(239, 68, 68, 0.5)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }
});
</script> 