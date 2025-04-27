<?= $this->include('dashboard/header') ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold mb-6"><?= isset($client) ? 'Edit Client' : 'Tambah Client Baru' ?></h2>
        
        <?php if (session()->has('errors')): ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= isset($client) ? base_url('master/client/update/' . $client['client_id']) : base_url('master/client/store') ?>" 
              method="POST" 
              class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Client -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Client</label>
                    <input type="text" name="code" 
                           class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           value="<?= isset($client) ? $client['code'] : $code ?>" readonly>
                    <p class="mt-1 text-sm text-gray-500">Kode client dibuat otomatis oleh sistem</p>
                </div>

                <!-- Nama Client -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Client</label>
                    <input type="text" name="name" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           value="<?= isset($client) ? $client['name'] : old('name') ?>" required>
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea name="address" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                              required><?= isset($client) ? $client['address'] : old('address') ?></textarea>
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="text" name="phone" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           value="<?= isset($client) ? $client['phone'] : old('phone') ?>" required>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           value="<?= isset($client) ? $client['email'] : old('email') ?>">
                </div>

                <!-- Nama PIC -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama PIC</label>
                    <input type="text" name="pic_name" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           value="<?= isset($client) ? $client['pic_name'] : old('pic_name') ?>" required>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="<?= base_url('master/client') ?>" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    <?= isset($client) ? 'Update Client' : 'Simpan Client' ?>
                </button>
            </div>
        </form>
    </div>
</div> 