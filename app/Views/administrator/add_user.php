<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="bg-white shadow-sm rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Tambah User Baru</h2>
        </div>

        <form action="<?= base_url('administrator/users/save') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           value="<?= old('username') ?>">
                    <?php if (session('errors.username')) : ?>
                        <p class="mt-2 text-sm text-red-600"><?= session('errors.username') ?></p>
                    <?php endif ?>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                           title="Password harus memiliki minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan karakter khusus"
                           onkeyup="checkPasswordStrength(this.value)">
                    <div id="password-strength" class="mt-2">
                        <div class="flex space-x-1">
                            <div class="h-1 w-1/4 rounded transition-colors duration-200" id="strength-1"></div>
                            <div class="h-1 w-1/4 rounded transition-colors duration-200" id="strength-2"></div>
                            <div class="h-1 w-1/4 rounded transition-colors duration-200" id="strength-3"></div>
                            <div class="h-1 w-1/4 rounded transition-colors duration-200" id="strength-4"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1" id="strength-text">Password harus memenuhi kriteria berikut:</p>
                        <ul class="text-xs text-gray-500 mt-1 list-disc list-inside">
                            <li id="length">Minimal 8 karakter</li>
                            <li id="lowercase">Minimal 1 huruf kecil</li>
                            <li id="uppercase">Minimal 1 huruf besar</li>
                            <li id="number">Minimal 1 angka</li>
                            <li id="special">Minimal 1 karakter khusus (@$!%*?&)</li>
                        </ul>
                    </div>
                    <?php if (session('errors.password')) : ?>
                        <p class="mt-2 text-sm text-red-600"><?= session('errors.password') ?></p>
                    <?php endif ?>
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="role" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Role</option>
                        <option value="admin" <?= old('role') == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="supervisor" <?= old('role') == 'supervisor' ? 'selected' : '' ?>>Supervisor</option>
                        <option value="manager" <?= old('role') == 'manager' ? 'selected' : '' ?>>Manager</option>
                        <option value="staff" <?= old('role') == 'staff' ? 'selected' : '' ?>>Staff</option>
                    </select>
                    <?php if (session('errors.role')) : ?>
                        <p class="mt-2 text-sm text-red-600"><?= session('errors.role') ?></p>
                    <?php endif ?>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="<?= base_url('administrator/users') ?>" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function checkPasswordStrength(password) {
    const criteria = {
        length: password.length >= 8,
        lowercase: /[a-z]/.test(password),
        uppercase: /[A-Z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[@$!%*?&]/.test(password)
    };

    // Update checklist items
    Object.keys(criteria).forEach(key => {
        const element = document.getElementById(key);
        if (criteria[key]) {
            element.classList.add('text-green-500');
            element.classList.remove('text-gray-500');
        } else {
            element.classList.remove('text-green-500');
            element.classList.add('text-gray-500');
        }
    });

    // Calculate strength
    const strength = Object.values(criteria).filter(Boolean).length;
    const strengthBars = ['strength-1', 'strength-2', 'strength-3', 'strength-4'];
    const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500'];
    const texts = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat'];

    // Update strength bars
    strengthBars.forEach((bar, index) => {
        const element = document.getElementById(bar);
        if (index < strength) {
            element.className = `h-1 w-1/4 rounded ${colors[strength-1]}`;
        } else {
            element.className = 'h-1 w-1/4 rounded bg-gray-200';
        }
    });

    // Update strength text
    document.getElementById('strength-text').textContent = 
        strength > 0 ? `Kekuatan Password: ${texts[strength-1]}` : 'Password harus memenuhi kriteria berikut:';
}
</script>

<?= $this->endSection() ?> 