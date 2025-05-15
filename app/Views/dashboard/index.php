<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <!-- Ringkasan Stok -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Stok Bahan Baku -->
        <!-- <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-box fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500">Stok Bahan Baku</p>
                    <p class="text-2xl font-semibold"><?= number_format($stock_summary['bahan_baku']) ?></p>
                </div>
            </div>
        </div> -->
        
        <!-- Stok Tabung 3kg -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-flask fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500">Stok Tabung 3kg</p>
                    <p class="text-2xl font-semibold"><?= number_format($tabung_summary['3kg']['stock'] ?? 0) ?></p>
                </div>
            </div>
        </div>

        <!-- Stok Tabung 12kg -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-flask fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500">Stok Tabung 12kg</p>
                    <p class="text-2xl font-semibold"><?= number_format($tabung_summary['12kg']['stock'] ?? 0) ?></p>
                </div>
            </div>
        </div>

        <!-- Stok Tabung 5kg -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-flask fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500">Stok Tabung 5kg</p>
                    <p class="text-2xl font-semibold"><?= number_format($tabung_summary['5kg']['stock'] ?? 0) ?></p>
                </div>
            </div>
        </div>

        <!-- Stok Tabung 15kg -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                    <i class="fas fa-flask fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500">Stok Tabung 20kg</p>
                    <p class="text-2xl font-semibold"><?= number_format($tabung_summary['15kg']['stock'] ?? 0) ?></p>
                </div>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-exchange-alt fa-2x"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500">Total Transaksi</p>
                    <p class="text-2xl font-semibold"><?= number_format($total_transactions) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik dan Tabel -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Grafik Pergerakan Stok -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Pergerakan Stok Tabung Gas</h2>
            <div class="flex items-center justify-end mb-4 text-sm">
                <div class="flex items-center mr-4">
                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                    <span>Tabung 3kg</span>
                </div>
                <div class="flex items-center mr-4">
                    <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                    <span>Tabung 5kg</span>
                </div>
                <div class="flex items-center mr-4">
                    <span class="w-3 h-3 bg-purple-500 rounded-full mr-2"></span>
                    <span>Tabung 12kg</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-indigo-500 rounded-full mr-2"></span>
                    <span>Tabung 15kg</span>
                </div>
            </div>
            <canvas id="stockChart" class="w-full" height="300"></canvas>
        </div>

        <!-- Ringkasan Stok Bahan Baku -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Ringkasan Stok Bahan Baku</h2>
                <a href="/dashboard/bahan-baku-detail" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                    <i class="fas fa-external-link-alt mr-1"></i>
                    Detail Bahan Baku
                </a>
            </div>
            <div class="overflow-x-auto overflow-y-auto" style="max-height: 400px;">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-50">Nama Bahan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-50">Stok</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-50">Gudang</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-50">Minimum Stok</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-50">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($bahan_baku_summary as $item): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= $item['name'] ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= number_format($item['stock']) ?> unit
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= $item['warehouse_name'] ?? '-' ?>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    <?= ucfirst($item['minimum_stock']) ?>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <?php if ($item['stock'] <= $item['minimum_stock']): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Stok Rendah
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Stok Aman
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ringkasan Stok Tabung -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Ringkasan Stok Tabung</h2>
            <div class="space-y-6">
                <!-- Tabung 3kg -->
                <div class="border-b pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">
                        <i class="fas fa-flask text-green-600 mr-2"></i>Tabung 3kg
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Stok Saat Ini</p>
                            <p class="text-2xl font-semibold text-blue-600">
                                <?= number_format($tabung_summary['3kg']['stock'] ?? 0) ?>
                            </p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Total Keluar <?= date('Y') ?></p>
                            <p class="text-2xl font-semibold text-red-600">
                                <?= number_format($tabung_summary['3kg']['total_keluar'] ?? 0) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tabung 12kg -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">
                        <i class="fas fa-flask text-purple-600 mr-2"></i>Tabung 12kg
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Stok Saat Ini</p>
                            <p class="text-2xl font-semibold text-blue-600">
                                <?= number_format($tabung_summary['12kg']['stock'] ?? 0) ?>
                            </p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Total Keluar <?= date('Y') ?></p>
                            <p class="text-2xl font-semibold text-red-600">
                                <?= number_format($tabung_summary['12kg']['total_keluar'] ?? 0) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tabung 5kg -->
                <div class="border-b pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">
                        <i class="fas fa-flask text-yellow-600 mr-2"></i>Tabung 5kg
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Stok Saat Ini</p>
                            <p class="text-2xl font-semibold text-blue-600">
                                <?= number_format($tabung_summary['5kg']['stock'] ?? 0) ?>
                            </p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Total Keluar <?= date('Y') ?></p>
                            <p class="text-2xl font-semibold text-red-600">
                                <?= number_format($tabung_summary['5kg']['total_keluar'] ?? 0) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tabung 15kg -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">
                        <i class="fas fa-flask text-indigo-600 mr-2"></i>Tabung 20kg
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Stok Saat Ini</p>
                            <p class="text-2xl font-semibold text-blue-600">
                                <?= number_format($tabung_summary['15kg']['stock'] ?? 0) ?>
                            </p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Total Keluar <?= date('Y') ?></p>
                            <p class="text-2xl font-semibold text-red-600">
                                <?= number_format($tabung_summary['15kg']['total_keluar'] ?? 0) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aktivitas Terakhir -->
        <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
            <h2 class="text-xl font-semibold mb-4">Aktivitas Terakhir</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Partner</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($incoming_transactions as $transaction): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                <?= date('d M Y', strtotime($transaction['transaction_date'])) ?>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= $transaction['item_name'] ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= number_format($transaction['quantity']) ?> unit
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= $transaction['partner_name'] ?? '-' ?>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    <?= $transaction['notes'] ?? '-' ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch stock data for chart
    fetch('/api/stock-data')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('stockChart').getContext('2d');
            
            // Format dates to Indonesian month names
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                              'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const formattedLabels = data.labels.map(date => {
                const [year, month] = date.split('-');
                return monthNames[parseInt(month) - 1] + ' ' + year;
            });

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: formattedLabels,
                    datasets: [{
                        label: 'Tabung 3kg',
                        data: Object.values(data.historical).map(month => month.tabung_3kg),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }, {
                        label: 'Tabung 5kg',
                        data: Object.values(data.historical).map(month => month.tabung_5kg),
                        borderColor: 'rgb(234, 179, 8)',
                        backgroundColor: 'rgba(234, 179, 8, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }, {
                        label: 'Tabung 12kg',
                        data: Object.values(data.historical).map(month => month.tabung_12kg),
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }, {
                        label: 'Tabung 15kg',
                        data: Object.values(data.historical).map(month => month.tabung_15kg),
                        borderColor: 'rgb(79, 70, 229)',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID').format(context.parsed.y) + ' unit';
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Stok (Unit)',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Periode',
                                font: {
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching stock data:', error);
        });
});
</script>

<style>
/* Styling untuk scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Memastikan header tetap terlihat saat scroll */
thead.sticky {
    position: sticky;
    top: 0;
    z-index: 10;
}
</style>
<?= $this->endSection() ?> 