<?= $this->include('dashboard/header') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold mb-6">Transaksi Tabung Gas</h2>
        
        <form action="<?= base_url('transaksi/tabung/save') ?>" method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kategori Tabung -->
                <div id="categorySection">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Tabung</label>
                    <select name="kategori_tabung" id="categorySelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih Kategori Tabung</option>
                        <option value="A">Tabung A</option>
                        <option value="B">Tabung B</option>
                    </select>
                </div>

                <!-- Jenis Tabung -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Tabung</label>
                    <select name="item_id" id="itemSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Pilih Jenis Tabung</option>
                        <?php foreach ($items as $item): ?>
                            <option value="<?= $item['id'] ?>" data-stock="<?= $item['stock'] ?>"><?= $item['name'] ?> (Stok: <?= $item['stock'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <p id="stockWarning" class="hidden mt-2 text-sm text-red-600">Stok tidak mencukupi untuk transaksi keluar</p>
                </div>

                <!-- Jenis Transaksi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Transaksi</label>
                    <select name="type" id="transactionType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="masuk">Masuk</option>
                        <option value="keluar">Keluar</option>
                    </select>
                </div>

                <!-- Client Pembeli (hanya muncul jika transaksi keluar) -->
                <div id="clientSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                    <select name="client_id" id="clientSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Client</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= $client['client_id'] ?>" 
                                    data-name="<?= htmlspecialchars($client['name']) ?>"
                                    data-code="<?= htmlspecialchars($client['code']) ?>"
                                    data-pic="<?= htmlspecialchars($client['pic_name']) ?>"
                                    data-phone="<?= htmlspecialchars($client['phone']) ?>">
                                <?= $client['name'] ?> (<?= $client['code'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Alamat Pengiriman (hanya muncul jika transaksi keluar) -->
                <div id="addressSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengiriman</label>
                    <div class="relative">
                        <select name="delivery_address" id="deliveryAddress" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Alamat</option>
                        </select>
                        <div id="addressLoading" class="hidden absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    <p id="addressError" class="hidden mt-2 text-sm text-red-600"></p>
                </div>

                <!-- Detail Pengiriman (hanya muncul jika transaksi keluar) -->
                <div id="deliveryDetailSection" class="hidden md:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">PIC Penerima</label>
                            <input type="text" name="receiver_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon Penerima</label>
                            <input type="text" name="receiver_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Gudang -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gudang</label>
                    <select name="warehouse_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Gudang</option>
                        <?php foreach ($warehouses as $warehouse): ?>
                            <option value="<?= $warehouse['id'] ?>"><?= $warehouse['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Jumlah -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <input type="number" name="quantity" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <!-- Catatan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <!-- Delivery Order (hanya muncul jika transaksi keluar) -->
                <div id="doSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Delivery Order</label>
                    <div class="relative">
                        <input type="text" name="delivery_order" id="doNumber" 
                               class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               readonly>
                        <input type="hidden" name="do_number" id="doNumberHidden">
                    </div>
                </div>

                <!-- Created By -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">PIC Transaksi</label>
                    <input type="text" name="created_by" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Riwayat Transaksi -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold mb-4">Riwayat Transaksi Tabung</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase min-w-[120px]">Delivery Order</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase min-w-[160px]">Tanggal</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase min-w-[200px]">Tipe</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase min-w-[100px]">Transaksi</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase min-w-[200px]">Client</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase min-w-[250px]">Lokasi</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase min-w-[150px]">Gudang</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase min-w-[100px]">Jumlah</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase min-w-[120px]">Status</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase min-w-[150px]">Surat jalan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(empty($transactions)): ?>
                        <tr>
                            <td colspan="10" class="px-8 py-6 text-center text-gray-500">
                                Tidak ada data transaksi
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $trans): ?>
                        <tr class="<?= ($trans['status'] ?? '') === 'pending' ? 'bg-yellow-50' : (($trans['status'] ?? '') === 'approve' ? 'bg-green-50' : '') ?>">
                            <td class="px-8 py-6 whitespace-nowrap"><?= $trans['delivery_order'] ?></td>
                            <td class="px-8 py-6 whitespace-nowrap"><?= date('d/m/Y H:i:s', strtotime($trans['transaction_date'])) ?></td>
                            <td class="px-8 py-6"><?= $trans['item_name'] ?></td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-2 text-xs font-semibold rounded-full <?= $trans['type'] === 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= ucfirst($trans['type']) ?>
                                </span>
                            </td>
                            <td class="px-8 py-6"><?= $trans['client_name'] ?></td>
                            <td class="px-8 py-6"><?= $trans['delivery_address'] ?? '-' ?></td>
                            <td class="px-8 py-6"><?= $trans['warehouse_name'] ?></td>
                            <td class="px-8 py-6 text-right"><?= number_format($trans['quantity']) ?></td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-2 text-xs font-semibold rounded-full <?= ($trans['status'] ?? '') === 'pending' ? 'bg-red-100 text-red-800' : (($trans['status'] ?? '') === 'approve' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') ?>">
                                    <?= $trans['status'] ?? '-' ?>
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <?php if (($trans['status'] ?? '') === 'pending'): ?>
                                    <button disabled class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-200 rounded-md cursor-not-allowed">
                                        <i class="fas fa-print mr-2"></i>
                                        Cetak
                                    </button>
                                <?php else: ?>
                                    <a href="/transaksi/delivery-order/<?= $trans['id'] ?>" 
                                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                        <i class="fas fa-print mr-2"></i>
                                        Cetak
                                    </a>
                                <?php endif; ?>
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
    const form = document.querySelector('form');
    const transactionType = document.getElementById('transactionType');
    const clientSection = document.getElementById('clientSection');
    const addressSection = document.getElementById('addressSection');
    const deliveryDetailSection = document.getElementById('deliveryDetailSection');
    const doSection = document.getElementById('doSection');
    const categorySection = document.getElementById('categorySection');
    const clientSelect = document.querySelector('select[name="client_id"]');
    const deliveryAddress = document.getElementById('deliveryAddress');
    const addressLoading = document.getElementById('addressLoading');
    const addressError = document.getElementById('addressError');
    const itemSelect = document.getElementById('itemSelect');
    const stockWarning = document.getElementById('stockWarning');
    const quantityInput = document.querySelector('input[name="quantity"]');

    // Function to toggle sections
    function toggleSections() {
        const isOutgoing = transactionType.value === 'keluar';
        clientSection.classList.toggle('hidden', !isOutgoing);
        addressSection.classList.toggle('hidden', !isOutgoing);
        deliveryDetailSection.classList.toggle('hidden', !isOutgoing);
        doSection.classList.toggle('hidden', !isOutgoing);
        categorySection.classList.toggle('hidden', !isOutgoing);
        
        clientSelect.required = isOutgoing;
        deliveryAddress.required = isOutgoing;
        document.getElementById('categorySelect').required = isOutgoing;
        
        if (isOutgoing) {
            checkStock();
            handleCompanyNameDisplay();
        } else {
            stockWarning.classList.add('hidden');
        }
    }

    // Function to handle company name display in delivery order
    function handleCompanyNameDisplay() {
        const categorySelect = document.getElementById('categorySelect');
        const clientSelect = document.getElementById('clientSelect');
        const selectedClient = clientSelect.options[clientSelect.selectedIndex];
        
        if (categorySelect.value === 'B' && selectedClient) {
            // Simpan nama perusahaan asli di data attribute
            if (!selectedClient.dataset.originalName) {
                selectedClient.dataset.originalName = selectedClient.textContent;
            }
            // Hapus nama perusahaan dari tampilan
            selectedClient.textContent = selectedClient.dataset.code;
        } else if (selectedClient && selectedClient.dataset.originalName) {
            // Kembalikan nama perusahaan jika ada
            selectedClient.textContent = selectedClient.dataset.originalName;
        }
    }

    // Function to check stock availability
    function checkStock() {
        if (transactionType.value === 'keluar' && itemSelect.value) {
            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
            const currentStock = parseInt(selectedOption.dataset.stock);
            const quantity = parseInt(quantityInput.value) || 0;
            
            if (quantity > currentStock) {
                stockWarning.classList.remove('hidden');
                return false;
            }
        }
        stockWarning.classList.add('hidden');
        return true;
    }

    // Listen for changes
    transactionType.addEventListener('change', toggleSections);
    itemSelect.addEventListener('change', checkStock);
    quantityInput.addEventListener('input', checkStock);

    // Form validation
    form.addEventListener('submit', function(e) {
        if (transactionType.value === 'keluar') {
            if (!checkStock()) {
                e.preventDefault();
                alert('Stok tidak mencukupi untuk transaksi keluar');
                return;
            }
            
            const receiverName = document.querySelector('input[name="receiver_name"]');
            const receiverPhone = document.querySelector('input[name="receiver_phone"]');
            const deliveryOrder = document.querySelector('input[name="delivery_order"]');
            
            if (!receiverName.value.trim()) {
                e.preventDefault();
                alert('PIC Penerima harus diisi');
                receiverName.focus();
                return;
            }
            
            if (!receiverPhone.value.trim()) {
                e.preventDefault();
                alert('No. Telepon Penerima harus diisi');
                receiverPhone.focus();
                return;
            }
            
            if (!deliveryOrder.value.trim()) {
                e.preventDefault();
                alert('Nomor Delivery Order harus diisi');
                deliveryOrder.focus();
                return;
            }
        }
    });

    // Function to show loading state
    function showAddressLoading(show) {
        addressLoading.classList.toggle('hidden', !show);
        deliveryAddress.classList.toggle('opacity-50', show);
        deliveryAddress.disabled = show;
    }

    // Function to load client addresses
    async function loadClientAddresses(clientId) {
        if (!clientId) {
            deliveryAddress.innerHTML = '<option value="">Pilih Alamat</option>';
            return;
        }

        showAddressLoading(true);
        addressError.classList.add('hidden');

        try {
            const response = await fetch(`<?= base_url('transaksi/client/addresses/') ?>${clientId}`);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Server error');
            }

            if (data.success && Array.isArray(data.addresses)) {
                deliveryAddress.innerHTML = '<option value="">Pilih Alamat</option>';
                data.addresses.forEach(address => {
                    const option = document.createElement('option');
                    option.value = address.address;
                    option.textContent = `${address.address}${address.is_main ? ' (Utama)' : ''}`;
                    deliveryAddress.appendChild(option);
                });
            } else {
                throw new Error('Format data tidak valid');
            }
        } catch (error) {
            console.error('Error loading addresses:', error);
            addressError.textContent = 'Gagal memuat alamat: ' + error.message;
            addressError.classList.remove('hidden');
            deliveryAddress.innerHTML = '<option value="">Error memuat alamat</option>';
        } finally {
            showAddressLoading(false);
        }
    }

    // Listen for client selection changes
    clientSelect.addEventListener('change', function() {
        const selectedId = this.value;
        loadClientAddresses(selectedId);
        
        // Auto-fill receiver details
        if (selectedId) {
            const selectedOption = this.options[this.selectedIndex];
            document.querySelector('input[name="receiver_name"]').value = selectedOption.dataset.pic || '';
            document.querySelector('input[name="receiver_phone"]').value = selectedOption.dataset.phone || '';
        } else {
            document.querySelector('input[name="receiver_name"]').value = '';
            document.querySelector('input[name="receiver_phone"]').value = '';
        }
    });

    // Function untuk generate nomor DO
    async function generateDONumber() {
        try {
            const response = await fetch('<?= base_url('transaksi/generate-do-number') ?>');
            const data = await response.json();
            
            if (data.success) {
                const doNumber = data.do_number;
                document.getElementById('doNumber').value = doNumber;
                document.getElementById('doNumberHidden').value = doNumber;
            } else {
                throw new Error(data.message || 'Gagal generate nomor DO');
            }
        } catch (error) {
            console.error('Error generating DO number:', error);
            alert('Gagal generate nomor DO: ' + error.message);
        }
    }

    // Generate DO number ketika transaksi type berubah ke 'keluar'
    transactionType.addEventListener('change', function() {
        if (this.value === 'keluar') {
            generateDONumber();
        }
    });

    // Generate DO number saat halaman pertama kali load jika type = keluar
    if (transactionType.value === 'keluar') {
        generateDONumber();
    }

    // Listen for category changes
    document.getElementById('categorySelect').addEventListener('change', function() {
        if (transactionType.value === 'keluar') {
            handleCompanyNameDisplay();
        }
    });

    // Listen for client changes
    clientSelect.addEventListener('change', function() {
        if (transactionType.value === 'keluar') {
            handleCompanyNameDisplay();
        }
    });

    // Initial toggle
    toggleSections();
});
</script> 