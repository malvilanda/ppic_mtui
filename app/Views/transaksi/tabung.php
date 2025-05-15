<?= $this->include('dashboard/header') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
.transition-section {
    transition: all 0.3s ease-in-out;
    max-height: 0;
    opacity: 0;
    overflow: hidden;
}

.transition-section.show {
    max-height: 500px;
    opacity: 1;
}
</style>

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
                            <option value="<?= $item['id'] ?>" data-stock="<?= $item['stock'] ?>">
                                <?= $item['name'] ?> (Stok: <?= $item['stock'] ?>)
                            </option>
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
                <div id="clientSection" class="transition-section">
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
    <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900">Riwayat Transaksi Tabung</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Delivery Order</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Transaksi</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lokasi</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gudang</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="sticky top-0 px-6 py-4 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Surat Jalan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(empty($transactions)): ?>
                        <tr>
                            <td colspan="10" class="px-6 py-8 text-center text-sm text-gray-500 bg-gray-50">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p>Tidak ada data transaksi</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $trans): ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-200 ease-in-out <?= ($trans['status'] ?? '') === 'pending' ? 'bg-yellow-50' : (($trans['status'] ?? '') === 'approve' ? 'bg-green-50/50' : '') ?>">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium"><?= $trans['delivery_order'] ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= date('d/m/Y H:i:s', strtotime($trans['transaction_date'])) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= $trans['item_name'] . ' - ' . $trans['kategori_tabung'] ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $trans['type'] === 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= ucfirst($trans['type']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= $trans['type'] === 'keluar' ? ($trans['client_name'] ?? '-') : '-' ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= $trans['delivery_address'] ?? '-' ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= $trans['warehouse_name'] ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium text-right"><?= number_format($trans['quantity']) ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= ($trans['status'] ?? '') === 'pending' ? 'bg-yellow-100 text-yellow-800' : (($trans['status'] ?? '') === 'approve' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') ?>">
                                    <?= ucfirst($trans['status'] ?? '-') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (($trans['status'] ?? '') === 'pending'): ?>
                                    <button disabled class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-400 bg-gray-100 rounded-md cursor-not-allowed transition-colors duration-200">
                                        <i class="fas fa-print mr-1.5 text-gray-400"></i>
                                        Cetak
                                    </button>
                                <?php else: ?>
                                    <a href="/transaksi/delivery-order/<?= $trans['id'] ?>" 
                                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                        <i class="fas fa-print mr-1.5"></i>
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

<!-- Tambahkan modal konfirmasi -->
<div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Konfirmasi Transaksi</h3>
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="text-gray-600">Jenis Transaksi:</div>
                <div id="confirmType" class="font-medium text-gray-900"></div>
                
                <div class="text-gray-600">Jenis Tabung:</div>
                <div id="confirmItem" class="font-medium text-gray-900"></div>
                
                <div class="text-gray-600">Jumlah:</div>
                <div id="confirmQuantity" class="font-medium text-gray-900"></div>
                
                <div class="text-gray-600">Gudang:</div>
                <div id="confirmWarehouse" class="font-medium text-gray-900"></div>
                
                <div id="confirmClientRow" class="contents">
                    <div class="text-gray-600">Client:</div>
                    <div id="confirmClient" class="font-medium text-gray-900"></div>
                </div>
                
                <div id="confirmAddressRow" class="contents">
                    <div class="text-gray-600">Alamat:</div>
                    <div id="confirmAddress" class="font-medium text-gray-900"></div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-4">
                <p class="text-sm text-gray-600">
                    Pastikan semua data yang dimasukkan sudah benar sebelum melanjutkan.
                </p>
            </div>
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" id="cancelTransaction" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Batal
            </button>
            <button type="button" id="confirmTransaction" 
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Konfirmasi
            </button>
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

    // Function to toggle sections with animation
    function toggleSections() {
        const isOutgoing = transactionType.value === 'keluar';
        const sections = [clientSection, addressSection, deliveryDetailSection, doSection, categorySection];
        
        sections.forEach(section => {
            if (isOutgoing) {
                section.classList.remove('hidden');
                setTimeout(() => section.classList.add('show'), 10);
            } else {
                section.classList.remove('show');
                setTimeout(() => section.classList.add('hidden'), 300);
            }
        });
        
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
            const selectedItem = itemSelect.options[itemSelect.selectedIndex];
            const currentStock = parseInt(selectedItem.dataset.stock) || 0;
            const quantity = parseInt(quantityInput.value) || 0;
            
            // Debug info
            console.log('Stock Check Debug:', {
                itemId: selectedItem.value,
                itemName: selectedItem.text,
                currentStock: currentStock,
                requestedQuantity: quantity,
                isExceeding: quantity > currentStock
            });
            
            if (quantity > currentStock) {
                stockWarning.classList.remove('hidden');
                stockWarning.textContent = `Stok tidak mencukupi. Stok tersedia: ${currentStock}`;
                quantityInput.classList.add('border-red-500');
                return false;
            }
        }
        stockWarning.classList.add('hidden');
        quantityInput.classList.remove('border-red-500');
        return true;
    }

    // Listen for changes
    transactionType.addEventListener('change', toggleSections);
    itemSelect.addEventListener('change', checkStock);
    quantityInput.addEventListener('input', checkStock);

    // Form submission handling
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            showConfirmationModal();
        }
    });
    
    function validateForm() {
        // Reset validation messages
        document.querySelectorAll('.validation-message').forEach(el => el.remove());
        
        let isValid = true;
        
        // Validate required fields
        form.querySelectorAll('[required]').forEach(field => {
            if (!field.value) {
                isValid = false;
                showError(field, 'Field ini harus diisi');
            }
        });
        
        // Validate quantity
        if (quantityInput.value) {
            const qty = parseInt(quantityInput.value);
            if (transactionType.value === 'keluar') {
                const selectedItem = itemSelect.options[itemSelect.selectedIndex];
                const stock = parseInt(selectedItem.dataset.stock);
                if (qty > stock) {
                    isValid = false;
                    showError(quantityInput, 'Jumlah melebihi stok yang tersedia');
                }
            }
            if (qty <= 0) {
                isValid = false;
                showError(quantityInput, 'Jumlah harus lebih dari 0');
            }
        }
        
        // Validate phone number format
        const phoneInput = document.querySelector('input[name="receiver_phone"]');
        if (phoneInput.value && !/^[0-9+\-\s()]*$/.test(phoneInput.value)) {
            isValid = false;
            showError(phoneInput, 'Format nomor telepon tidak valid');
        }
        
        return isValid;
    }
    
    function showConfirmationModal() {
        const modal = document.getElementById('confirmationModal');
        const type = transactionType.value;
        const item = itemSelect.options[itemSelect.selectedIndex].text;
        const warehouse = document.querySelector('select[name="warehouse_id"]').options[document.querySelector('select[name="warehouse_id"]').selectedIndex].text;
        const quantity = quantityInput.value;
        
        document.getElementById('confirmType').textContent = type === 'masuk' ? 'Masuk' : 'Keluar';
        document.getElementById('confirmItem').textContent = item;
        document.getElementById('confirmWarehouse').textContent = warehouse;
        document.getElementById('confirmQuantity').textContent = quantity;
        
        if (type === 'keluar') {
            const client = clientSelect.options[clientSelect.selectedIndex].text;
            const address = deliveryAddress.options[deliveryAddress.selectedIndex].text;
            document.getElementById('confirmClient').textContent = client;
            document.getElementById('confirmAddress').textContent = address;
            document.getElementById('confirmClientRow').classList.remove('hidden');
            document.getElementById('confirmAddressRow').classList.remove('hidden');
        } else {
            document.getElementById('confirmClientRow').classList.add('hidden');
            document.getElementById('confirmAddressRow').classList.add('hidden');
        }
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    // Modal event listeners
    document.getElementById('cancelTransaction').addEventListener('click', function() {
        const modal = document.getElementById('confirmationModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    });
    
    document.getElementById('confirmTransaction').addEventListener('click', function() {
        const modal = document.getElementById('confirmationModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        form.submit();
    });

    // Real-time stock validation
    function checkStock() {
        if (transactionType.value === 'keluar' && itemSelect.value && quantityInput.value) {
            const selectedItem = itemSelect.options[itemSelect.selectedIndex];
            const stock = parseInt(selectedItem.dataset.stock);
            const qty = parseInt(quantityInput.value);
            
            stockWarning.classList.toggle('hidden', qty <= stock);
            quantityInput.classList.toggle('border-red-500', qty > stock);
        }
    }

    // Add event listeners for real-time validation
    quantityInput.addEventListener('input', checkStock);
    itemSelect.addEventListener('change', checkStock);
    
    // Format phone number as typed
    const phoneInput = document.querySelector('input[name="receiver_phone"]');
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^\d+\-\s()]/g, '');
        e.target.value = value;
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