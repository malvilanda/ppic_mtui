<?= $this->include('dashboard/header') ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Master Bahan Baku</h2>
            <div class="flex space-x-2">
                <button onclick="scrollToTable()" 
                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    Scroll ke Data
                </button>
                <button onclick="openModal()" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    + Tambah Jenis Bahan Baku
                </button>
            </div>
        </div>
    </div>
      
    <!-- Tabel Items Part -->
    <div id="items_part_table" class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Data Bahan Baku (items_part)</h2>
        
        <!-- Search Form -->
        <div class="mb-6">
            <form action="<?= base_url('master/bahan-baku') ?>" method="GET" class="flex">
                <div class="flex-grow mr-2">
                    <input 
                        type="text" 
                        name="keyword" 
                        value="<?= $keyword ?? '' ?>" 
                        placeholder="Cari nama atau part number..." 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-search mr-1"></i> Cari
                </button>
                <?php if (!empty($keyword)): ?>
                <a href="<?= base_url('master/bahan-baku') ?>" class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    Reset
                </a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Search Results Summary -->
        <div class="mb-4 text-sm text-gray-600">
            Menampilkan <?= count($items_part) ?> dari <?= $total ?? '0' ?> data
            <?= !empty($keyword) ? " untuk pencarian \"$keyword\"" : "" ?>
        </div>
        
        <div class="overflow-x-auto">
            <div class="max-h-[500px] overflow-y-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Minimum Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Update Terakhir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($items_part)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <?= !empty($keyword) ? "Tidak ada data yang cocok dengan pencarian \"$keyword\"" : "Tidak ada data" ?>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($items_part as $item): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?= $item['part_number'] ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?= $item['name'] ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?= $item['stock'] ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?= $item['minimum_stock'] ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500"><?= $item['updated_at'] ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="javascript:void(0)" 
                                   onclick="confirmDelete(<?= $item['id'] ?>, '<?= $item['name'] ?>')"
                                   class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if (isset($pager)): ?>
            <div class="mt-4 px-6 py-3 flex items-center justify-between border-t border-gray-200">
                <div class="flex-1 flex justify-between sm:hidden">
                    <?php if ($pager->getCurrentPage() > 1): ?>
                    <a href="<?= $pager->getFirstPage() ?><?= !empty($keyword) ? "&keyword=$keyword" : "" ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Awal
                    </a>
                    <?php endif; ?>
                    <?php if ($pager->getCurrentPage() < $pager->getPageCount()): ?>
                    <a href="<?= $pager->getLastPage() ?><?= !empty($keyword) ? "&keyword=$keyword" : "" ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Akhir
                    </a>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium"><?= count($items_part) ?></span> dari 
                            <span class="font-medium"><?= $total ?></span> data
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <?php if ($pager->getCurrentPage() > 1): ?>
                            <a href="<?= site_url('master/bahan-baku?page=' . ($pager->getCurrentPage() - 1)) ?><?= !empty($keyword) ? "&keyword=$keyword" : "" ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $pager->getPageCount(); $i++) : ?>
                                <?php $active = $i == $pager->getCurrentPage(); ?>
                                <a href="<?= $pager->getPageURI($i) ?><?= !empty($keyword) ? "&keyword=$keyword" : "" ?>" 
                                   class="relative inline-flex items-center px-4 py-2 border <?= $active ? 'border-blue-500 bg-blue-50 text-blue-600' : 'border-gray-300 bg-white text-gray-500 hover:bg-gray-50' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($pager->getCurrentPage() < $pager->getPageCount()): ?>
                            <a href="<?= site_url('master/bahan-baku?page=' . ($pager->getCurrentPage() + 1)) ?><?= !empty($keyword) ? "&keyword=$keyword" : "" ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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
            
            <div class="mt-4 flex justify-center">
                <button onclick="scrollToTop()" 
                    class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    Kembali ke Atas
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Modal Tambah Jenis Bahan Baku -->
<div id="modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Tambah Bahan Baku</h3>
            <form action="<?= site_url('master/store-items-part') ?>" method="POST">
                <!-- Part Number -->
                <div class="mb-4">
                    <label for="part_number" class="block text-sm font-medium text-gray-700">Part Number</label>
                    <input type="text" name="part_number" id="part_number" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Nama -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" id="name" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <!-- Tombol -->
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('modal');
    if (event.target == modal) {
        closeModal();
    }
}

// Fungsi untuk scroll ke tabel data
function scrollToTable() {
    document.getElementById('items_part_table').scrollIntoView({ behavior: 'smooth' });
}

// Fungsi untuk scroll ke atas halaman
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Fungsi untuk konfirmasi penghapusan dengan SweetAlert
function confirmDelete(id, name) {
    console.log("confirmDelete called with id:", id, "name:", name);
    
    if (typeof Swal === 'undefined') {
        console.error("SweetAlert2 is not loaded!");
        alert("Terjadi kesalahan: SweetAlert2 tidak ditemukan.");
        return;
    }
    
    console.log("SweetAlert2 is loaded, showing confirmation dialog");
    
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: `Apakah Anda yakin ingin menghapus bahan baku "${name}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        console.log("SweetAlert result:", result);
        if (result.isConfirmed) {
            const deleteUrl = `<?= base_url('master/items-part/delete/') ?>${id}`;
            console.log("Redirecting to:", deleteUrl);
            window.location.href = deleteUrl;
        }
    });
}

// Filter table by category
document.getElementById('categoryFilter')?.addEventListener('change', function() {
    const selectedCategory = this.value;
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const rowCategory = row.getAttribute('data-category');
        if (!selectedCategory || rowCategory === selectedCategory) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script> 