<?= $this->include('dashboard/header') ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Stok Bahan Baku</h2>
            <!-- <a href="<?= base_url('transaksi/bahan-baku') ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                + Transaksi Baru
            </a> -->
        </div>

        <!-- Filter Kategori -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Bahan Baku:</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" 
                       id="searchInput" 
                       class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md text-sm" 
                       placeholder="Cari nama bahan baku...">
            </div>
        </div>


        <!-- Tabel Stok -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bahan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Saat Ini</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Minimum Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Update</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?= $item['part_number'] ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?= $item['name'] ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?= number_format($item['stock'], 0, ',', '.') ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?= number_format($item['minimum_stock'], 0, ',', '.') ?></div>
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
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button type="button" 
                                    onclick='editItem(<?= json_encode($item) ?>)' 
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="px-6 py-4 bg-white border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Menampilkan <?= count($items) ?> dari total data
                    </div>
                    <div>
                        <?php if (isset($pager)): ?>
                            <?= $pager->links('default', 'default_tailwind') ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Stok -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold mb-4">Grafik Perbandingan Stok</h3>
        <canvas id="stockChart" height="300"></canvas>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="editForm" onsubmit="updateItem(event)">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Bahan Baku</h3>
                    <input type="hidden" id="editItemId">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Bahan</label>
                        <input type="text" id="editName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Stok Saat Ini</label>
                        <input type="number" 
                               id="editStock" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                               min="0" 
                               step="1" 
                               onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Minimum Stok</label>
                        <input type="number" 
                               id="editMinStock" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                               min="0" 
                               step="1" 
                               onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button type="button" onclick="closeEditModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Setup Chart
    const ctx = document.getElementById('stockChart').getContext('2d');
    const items = <?= json_encode($items) ?>;
    
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: items.map(item => item.name),
            datasets: [
                {
                    label: 'Stok Saat Ini',
                    data: items.map(item => item.stock),
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

    // Setup search input
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('tbody tr');
    
    searchInput.addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        
        tableRows.forEach(row => {
            const namaBahan = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            
            // Cari berdasarkan nama bahan
            if (namaBahan.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Update grafik jika ada
        if (typeof chart !== 'undefined') {
            updateChartData(searchTerm);
        }
    });
    
    function updateChartData(searchTerm) {
        const visibleRows = Array.from(tableRows).filter(row => 
            row.style.display !== 'none'
        );
        
        const labels = visibleRows.map(row => 
            row.querySelector('td:nth-child(2)').textContent
        );
        
        const stockData = visibleRows.map(row => 
            parseFloat(row.querySelector('td:nth-child(3)').textContent.replace(/,/g, ''))
        );
        
        const minStockData = visibleRows.map(row => 
            parseFloat(row.querySelector('td:nth-child(5)').textContent.replace(/,/g, ''))
        );
        
        chart.data.labels = labels;
        chart.data.datasets[0].data = stockData;
        chart.data.datasets[1].data = minStockData;
        chart.update();
    }

    // Setup event listeners dan inisialisasi
    setupModalEvents();
});

function setupModalEvents() {
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target == modal) {
            closeEditModal();
        }
    }
}

function editItem(item) {
    console.log('Edit item:', item);
    
    // Set values to form dengan memastikan nilai integer
    document.getElementById('editItemId').value = item.id;
    document.getElementById('editName').value = item.name;
    document.getElementById('editStock').value = parseInt(item.stock);
    document.getElementById('editMinStock').value = parseInt(item.minimum_stock);
    
    // Show modal
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function updateItem(event) {
    event.preventDefault();
    
    const id = parseInt(document.getElementById('editItemId').value);
    const stock = parseInt(document.getElementById('editStock').value);
    const minStock = parseInt(document.getElementById('editMinStock').value);
    
    // Validasi input
    if (isNaN(stock) || isNaN(minStock) || stock < 0 || minStock < 0) {
        Swal.fire({
            icon: 'error',
            title: 'Input Tidak Valid',
            text: 'Stok dan minimum stok harus berupa angka positif'
        });
        return;
    }
    
    // Show loading state
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = 'Menyimpan...';
    submitBtn.disabled = true;
    
    fetch('/stok/update-bahan-baku', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        },
        body: JSON.stringify({
            id: id,
            stock: stock,
            minimum_stock: minStock
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response:', data); // Debug log
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data berhasil diupdate',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: data.message || 'Gagal mengupdate data'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan sistem'
        });
    });
}
</script>

<!-- Tambahkan custom style untuk pagination -->
<style>
.pagination {
    @apply flex justify-center space-x-1;
}

.pagination li {
    @apply inline-block;
}

.pagination li a,
.pagination li span {
    @apply px-4 py-2 text-sm border rounded-md;
}

.pagination li.active span {
    @apply bg-blue-600 text-white border-blue-600;
}

.pagination li a {
    @apply text-gray-700 hover:bg-gray-50;
}

.pagination li.disabled span {
    @apply text-gray-400 cursor-not-allowed;
}
</style> 