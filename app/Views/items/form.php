<?= $this->include('dashboard/header') ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold"><?= isset($item) ? 'Edit' : 'Tambah' ?> Item</h2>
        </div>

        <form action="<?= base_url('items/' . (isset($item) ? 'update' : 'store')) ?>" method="POST" class="space-y-6">
            <?php if(isset($item)): ?>
                <input type="hidden" name="id" value="<?= $item['id'] ?>">
            <?php endif; ?>

            <!-- Nama Item -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Item</label>
                <input type="text" name="name" id="name" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    value="<?= isset($item) ? $item['name'] : '' ?>" required>
            </div>

            <!-- Kategori -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                <select name="category" id="category" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Pilih Kategori</option>
                    <option value="bahan_baku" <?= (isset($item) && $item['category'] == 'bahan_baku') ? 'selected' : '' ?>>
                        Bahan Baku Umum
                    </option>
                    <option value="tabung_bahan_baku" <?= (isset($item) && $item['category'] == 'tabung_bahan_baku') ? 'selected' : '' ?>>
                        Bahan Baku Tabung
                    </option>
                    <option value="tabung_produksi" <?= (isset($item) && $item['category'] == 'tabung_produksi') ? 'selected' : '' ?>>
                        Tabung Jadi
                    </option>
                </select>
            </div>

            <!-- Tipe -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Tipe</label>
                <select name="type" id="type" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Pilih Tipe</option>
                    <!-- Options will be populated by JavaScript -->
                </select>
            </div>

            <!-- Stok Awal -->
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700">Stok Awal</label>
                <input type="number" name="stock" id="stock" step="0.01"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    value="<?= isset($item) ? $item['stock'] : '0' ?>" required>
            </div>

            <!-- Unit/Satuan -->
            <div>
                <label for="unit" class="block text-sm font-medium text-gray-700">Satuan</label>
                <select name="unit" id="unit" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Pilih Satuan</option>
                    <option value="Pcs" <?= (isset($item) && $item['unit'] == 'Pcs') ? 'selected' : '' ?>>Pcs</option>
                    <option value="Kg" <?= (isset($item) && $item['unit'] == 'Kg') ? 'selected' : '' ?>>Kg</option>
                    <option value="Liter" <?= (isset($item) && $item['unit'] == 'Liter') ? 'selected' : '' ?>>Liter</option>
                    <option value="Meter" <?= (isset($item) && $item['unit'] == 'Meter') ? 'selected' : '' ?>>Meter</option>
                </select>
            </div>

            <!-- Minimum Stok -->
            <div>
                <label for="minimum_stock" class="block text-sm font-medium text-gray-700">Minimum Stok</label>
                <input type="number" name="minimum_stock" id="minimum_stock" step="0.01"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    value="<?= isset($item) ? $item['minimum_stock'] : '0' ?>" required>
            </div>

            <!-- Tombol Submit -->
            <div class="flex justify-end space-x-3">
                <a href="<?= base_url('items') ?>" 
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" 
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <?= isset($item) ? 'Update' : 'Simpan' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    const typeSelect = document.getElementById('type');
    const unitSelect = document.getElementById('unit');

    // Mapping kategori ke tipe
    const typeOptions = {
        'bahan_baku': [
            { value: 'bahan_plastik', label: 'Bahan Plastik' },
            { value: 'bahan_kimia', label: 'Bahan Kimia' },
            { value: 'bahan_logam', label: 'Bahan Logam' }
        ],
        'tabung_bahan_baku': [
            { value: 'tabung_bahan_body', label: 'Bahan Body Tabung' },
            { value: 'tabung_bahan_valve', label: 'Bahan Valve' },
            { value: 'tabung_bahan_cap', label: 'Bahan Cap/Tutup' }
        ],
        'tabung_produksi': [
            { value: 'tabung_3kg', label: 'Tabung 3 Kg' },
            { value: 'tabung_12kg', label: 'Tabung 12 Kg' }
        ]
    };

    // Mapping kategori ke unit default
    const defaultUnits = {
        'bahan_baku': 'Kg',
        'tabung_bahan_baku': 'Kg',
        'tabung_produksi': 'Pcs'
    };

    // Update tipe berdasarkan kategori yang dipilih
    function updateTypeOptions() {
        const selectedCategory = categorySelect.value;
        typeSelect.innerHTML = '<option value="">Pilih Tipe</option>';
        
        if (selectedCategory && typeOptions[selectedCategory]) {
            typeOptions[selectedCategory].forEach(option => {
                const optionElement = document.createElement('option');
                optionElement.value = option.value;
                optionElement.textContent = option.label;
                typeSelect.appendChild(optionElement);
            });
        }

        // Set unit default
        if (selectedCategory && defaultUnits[selectedCategory]) {
            unitSelect.value = defaultUnits[selectedCategory];
        }
    }

    // Event listener untuk perubahan kategori
    categorySelect.addEventListener('change', updateTypeOptions);

    // Initialize type options if editing
    if (categorySelect.value) {
        updateTypeOptions();
        <?php if(isset($item)): ?>
        typeSelect.value = '<?= $item['type'] ?>';
        <?php endif; ?>
    }
});
</script> 