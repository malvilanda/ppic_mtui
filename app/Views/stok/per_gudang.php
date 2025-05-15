<?= $this->include('dashboard/header') ?>

<div class="container mx-auto px-4 py-8">
    <!-- Ringkasan Gudang -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <?php foreach ($warehouses as $warehouse): ?>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold"><?= $warehouse['name'] ?></h3>
                <span class="text-sm text-gray-500"><?= $warehouse['location'] ?></span>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Transaksi</span>
                    <span class="font-semibold"><?= number_format($warehouse['total_transactions']) ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Barang Masuk</span>
                    <span class="text-green-600 font-semibold"><?= number_format($warehouse['total_incoming']) ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Barang Keluar</span>
                    <span class="text-red-600 font-semibold"><?= number_format($warehouse['total_outgoing']) ?></span>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t">
                <h4 class="text-sm font-semibold mb-2">Stok Saat Ini:</h4>
                <?php if (isset($warehouse_stocks[$warehouse['id']])): ?>
                    <?php foreach ($warehouse_stocks[$warehouse['id']] as $stock): ?>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600"><?= $stock['name'] ?></span>
                        <span class="font-semibold"><?= number_format($stock['current_stock']) ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-sm text-gray-500">Belum ada stok</p>
                <?php endif; ?>
            </div>
            <div class="mt-4">
                <a href="<?= base_url('transaksi/gudang/' . $warehouse['id']) ?>" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Detail →
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Ringkasan Stok Tabung -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Stok Tabung</h2>
            <button onclick="fetchStockData()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                <i class="fas fa-sync-alt mr-2"></i> Refresh Data
            </button>
        </div>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Minimum Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tabungTableBody">
                        <!-- Data akan diisi melalui JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Ringkasan Stok Bahan Baku -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">Stok Bahan Baku</h2>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Minimum Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="bahanBakuTableBody">
                        <!-- Data akan diisi melalui JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Grafik Perbandingan Stok -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Grafik Stok Tabung -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-4">Perbandingan Stok Tabung</h3>
            <canvas id="tabungChart" height="300"></canvas>
        </div>
        
        <!-- Grafik Stok Bahan Baku -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-4">Perbandingan Stok Bahan Baku</h3>
            <canvas id="bahanBakuChart" height="300"></canvas>
        </div>
    </div>
</div>

<!-- Loading Indicator -->
<div id="loadingIndicator" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-4 rounded-lg shadow-lg">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
        <p class="mt-2 text-gray-700">Memuat data...</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadingIndicator = document.getElementById('loadingIndicator');

    // Tambahkan auto refresh setiap 5 menit
    setInterval(fetchStockData, 300000);
    
    // Fungsi untuk mengambil data stok
    async function fetchStockData() {
        try {
            loadingIndicator.classList.remove('hidden');
            
            const response = await fetch('<?= base_url('stok/data') ?>');
            const data = await response.json();
            
            if (data.status === 'success') {
                renderTabungTable(data.data.tabung);
                renderBahanBakuTable(data.data.bahan_baku);
                renderTabungChart(data.data.tabung);
                renderBahanBakuChart(data.data.bahan_baku);
            } else {
                console.error('Error fetching data:', data.message);
                alert('Gagal mengambil data: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil data');
        } finally {
            loadingIndicator.classList.add('hidden');
        }
    }

    // Fungsi untuk format angka
    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // Fungsi untuk menampilkan tabel tabung
    function renderTabungTable(tabungData) {
        const tbody = document.getElementById('tabungTableBody');
        const types = ['3kg', '5kg', '12kg', '15kg'];
        
        tbody.innerHTML = types.map(type => {
            const item = tabungData[type] || {
                name: `Tabung ${type}`,
                sisa_stok: 0,
                minimum_stock: 0
            };
            
            return `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Tabung ${type}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatNumber(item.sisa_stok)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatNumber(item.minimum_stock)}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${getStatusBadge(item.sisa_stok, item.minimum_stock)}
                </td>
            </tr>`;
        }).join('');
    }

    // Fungsi untuk menampilkan tabel bahan baku
    function renderBahanBakuTable(bahanBakuData) {
        const tbody = document.getElementById('bahanBakuTableBody');
        let html = '';

        // Urutkan kategori
        const categories = Object.keys(bahanBakuData).sort();
        
        categories.forEach(category => {
            // Header kategori
            html += `
            <tr class="bg-gray-50">
                <td colspan="5" class="px-6 py-3 text-left text-sm font-semibold text-gray-900">
                    ${category}
                </td>
            </tr>`;

            // Items dalam kategori
            bahanBakuData[category].forEach(item => {
                html += `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.part_number}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatNumber(item.sisa_stok)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatNumber(item.minimum_stock)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${getStatusBadge(item.sisa_stok, item.minimum_stock)}
                    </td>
                </tr>`;
            });
        });

        tbody.innerHTML = html;
    }

    // Fungsi untuk mendapatkan warna status yang lebih spesifik
    function getStatusBadge(stock, minStock) {
        if (stock <= 0) {
            return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Habis</span>';
        } else if (stock <= minStock) {
            return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Stok Minimum</span>';
        } else if (stock <= minStock * 1.5) {
            return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Stok Menipis</span>';
        } else {
            return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Stok Aman</span>';
        }
    }

    // Fungsi untuk menampilkan grafik tabung
    function renderTabungChart(tabungData) {
        const ctx = document.getElementById('tabungChart').getContext('2d');
        const types = ['3kg', '5kg', '12kg', '15kg'];
        const labels = types.map(type => `Tabung ${type}`);
        const currentStock = types.map(type => (tabungData[type] || {sisa_stok: 0}).sisa_stok);
        const minStock = types.map(type => (tabungData[type] || {minimum_stock: 0}).minimum_stock);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Stok Saat Ini',
                    data: currentStock,
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }, {
                    label: 'Minimum Stok',
                    data: minStock,
                    backgroundColor: 'rgba(239, 68, 68, 0.5)',
                    borderColor: 'rgb(239, 68, 68)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Fungsi untuk menampilkan grafik bahan baku
    function renderBahanBakuChart(bahanBakuData) {
        const ctx = document.getElementById('bahanBakuChart').getContext('2d');
        
        // Siapkan data untuk grafik
        const categories = Object.keys(bahanBakuData);
        const datasets = [];
        
        // Dataset untuk stok saat ini
        const currentStockData = [];
        const minStockData = [];
        const labels = [];
        
        categories.forEach(category => {
            bahanBakuData[category].forEach(item => {
                labels.push(item.name);
                currentStockData.push(item.sisa_stok);
                minStockData.push(item.minimum_stock);
            });
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Stok Saat Ini',
                    data: currentStockData,
                    backgroundColor: 'rgba(16, 185, 129, 0.5)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1
                }, {
                    label: 'Minimum Stok',
                    data: minStockData,
                    backgroundColor: 'rgba(245, 158, 11, 0.5)',
                    borderColor: 'rgb(245, 158, 11)',
                    borderWidth: 1
                }]
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

    // Panggil fungsi untuk mengambil data
    fetchStockData();
});
</script> 