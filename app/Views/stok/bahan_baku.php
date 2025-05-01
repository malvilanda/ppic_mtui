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
                <thead class="bg-gray-50">
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bahan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gudang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Minimum Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php 
                    $no = 1 + (8 * ((int)$current_page - 1));
                    foreach ($items as $item): 
                    ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $no++ ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $item['part_number'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $item['name'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $item['warehouse_name'] ?? '-' ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= number_format($item['stock']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= number_format($item['minimum_stock']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($item['stock'] <= $item['minimum_stock']): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Stok Minimum
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Stok Aman
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button type="button" 
                                        onclick='editItem(<?= json_encode($item) ?>)' 
                                        class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($pager): ?>
        <div class="mt-6 flex justify-between items-center px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <?php 
                $currentPage = (int)$_GET['page'] ?? 1;
                $pageCount = ceil($total / 8); // 8 adalah items per page
                ?>
                <?php if ($currentPage > 1): ?>
                    <a href="<?= site_url('stok/bahan-baku?page=' . ($currentPage - 1)) ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                <?php endif; ?>
                <?php if ($currentPage < $pageCount): ?>
                    <a href="<?= site_url('stok/bahan-baku?page=' . ($currentPage + 1)) ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </a>
                <?php endif; ?>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Menampilkan
                        <span class="font-medium"><?= (($currentPage - 1) * 8) + 1 ?></span>
                        sampai
                        <span class="font-medium"><?= min($currentPage * 8, $total) ?></span>
                        dari
                        <span class="font-medium"><?= $total ?></span>
                        data
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="<?= site_url('stok/bahan-baku?page=' . ($currentPage - 1)) ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        <?php endif; ?>

                        <?php
                        // Calculate page range
                        $delta = 2;
                        $range = [];
                        $rangeWithDots = [];
                        
                        for ($i = 1; $i <= $pageCount; $i++) {
                            if ($i == 1 || $i == $pageCount || ($i >= $currentPage - $delta && $i <= $currentPage + $delta)) {
                                $range[] = $i;
                            }
                        }
                        
                        // Add dots and numbers
                        $prev = null;
                        foreach ($range as $i) {
                            if ($prev && $i - $prev > 1) {
                                if ($i - $prev == 2) {
                                    $rangeWithDots[] = $prev + 1;
                                } else {
                                    $rangeWithDots[] = '...';
                                }
                            }
                            $rangeWithDots[] = $i;
                            $prev = $i;
                        }
                        
                        // Output page numbers and dots
                        foreach ($rangeWithDots as $i): ?>
                            <?php if ($i === '...'): ?>
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                    ...
                                </span>
                            <?php else: ?>
                                <a href="<?= site_url('stok/bahan-baku?page=' . $i) ?>" 
                                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 <?= $i == $currentPage ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white text-gray-500 hover:bg-gray-50' ?> text-sm font-medium">
                                    <?= $i ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?php if ($currentPage < $pageCount): ?>
                            <a href="<?= site_url('stok/bahan-baku?page=' . ($currentPage + 1)) ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Riwayat Transaksi -->
    

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
                        <label class="block text-gray-700 text-sm font-bold mb-2">Part Number</label>
                        <input type="text" id="editPartNumber" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Bahan</label>
                        <input type="text" id="editName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Gudang</label>
                        <input type="text" id="editWarehouse" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" readonly>
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
        const searchTerm = e.target.value.toLowerCase().trim();
        let visibleRowCount = 0;
        
        console.log('Search term:', searchTerm);
        console.log('Total rows before filtering:', tableRows.length);
        
        // Debug: Log all table data
        const tableData = [];
        tableRows.forEach((row, index) => {
            const cells = row.getElementsByTagName('td');
            const rowData = {
                index: index + 1,
                partNumber: cells[1].textContent.trim(),
                namaBahan: cells[2].textContent.trim(),
                gudang: cells[3].textContent.trim(),
                stok: cells[4].textContent.trim(),
                minimumStok: cells[5].textContent.trim()
            };
            tableData.push(rowData);
        });
        console.log('Table data:', tableData);
        
        // Group rows by part number for checking duplicates
        const groupedByPartNumber = {};
        tableRows.forEach((row, index) => {
            const partNumber = row.cells[1].textContent.trim();
            if (!groupedByPartNumber[partNumber]) {
                groupedByPartNumber[partNumber] = [];
            }
            groupedByPartNumber[partNumber].push(row);
        });
        console.log('Grouped by part number:', groupedByPartNumber);
        
        // Iterate through each group of rows
        Object.entries(groupedByPartNumber).forEach(([partNumber, rows]) => {
            const shouldShow = rows.some(row => {
                const cells = row.getElementsByTagName('td');
                const rowData = {
                    partNumber: cells[1].textContent.toLowerCase().trim(),
                    namaBahan: cells[2].textContent.toLowerCase().trim(),
                    gudang: cells[3].textContent.toLowerCase().trim()
                };
                
                return rowData.partNumber.includes(searchTerm) || 
                       rowData.namaBahan.includes(searchTerm) || 
                       rowData.gudang.includes(searchTerm);
            });
            
            // Show/hide all rows with the same part number
            rows.forEach(row => {
                row.style.display = shouldShow ? '' : 'none';
                if (shouldShow) visibleRowCount++;
            });
        });
        
        console.log('\nSearch Results Summary:');
        console.log('Total rows:', tableRows.length);
        console.log('Visible rows:', visibleRowCount);
        console.log('Hidden rows:', tableRows.length - visibleRowCount);
        
        // Update informasi jumlah data yang ditampilkan
        const infoText = document.querySelector('.text-sm.text-gray-700');
        if (infoText) {
            if (visibleRowCount === 0) {
                infoText.textContent = 'Tidak ada data yang sesuai dengan pencarian';
            } else {
                infoText.textContent = `Menampilkan ${visibleRowCount} data hasil pencarian`;
            }
        }
        
        // Update grafik
        updateChartData();
    });
    
    function updateChartData() {
        const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
        
        console.log('\nUpdating Chart:');
        console.log('Visible rows for chart:', visibleRows.length);
        
        // Debug: Log data for each visible row
        visibleRows.forEach((row, index) => {
            const rowData = {
                name: row.cells[2].textContent.trim(),
                warehouse: row.cells[3].textContent.trim(),
                stock: parseInt(row.cells[4].textContent.replace(/[,.]/g, '')),
                minStock: parseInt(row.cells[5].textContent.replace(/[,.]/g, ''))
            };
            console.log(`Chart data row ${index + 1}:`, rowData);
        });
        
        const labels = visibleRows.map(row => {
            const name = row.cells[2].textContent.trim();
            const warehouse = row.cells[3].textContent.trim();
            return `${name} (${warehouse})`;
        });
        
        const stockData = visibleRows.map(row => 
            parseInt(row.cells[4].textContent.replace(/[,.]/g, ''))
        );
        
        const minStockData = visibleRows.map(row => 
            parseInt(row.cells[5].textContent.replace(/[,.]/g, ''))
        );
        
        console.log('Chart data:', {
            labels,
            stockData,
            minStockData
        });
        
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
    document.getElementById('editItemId').value = item.id;
    document.getElementById('editPartNumber').value = item.part_number;
    document.getElementById('editName').value = item.name;
    document.getElementById('editWarehouse').value = item.warehouse_name || '-';
    document.getElementById('editStock').value = parseInt(item.stock);
    document.getElementById('editMinStock').value = parseInt(item.minimum_stock);
    
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