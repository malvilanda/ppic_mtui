<?php
$uri = service('uri');
$currentPath = $uri->getPath();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPIC MTU Indonesia</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.min.css') ?>">
    <script src="<?= base_url('assets/js/chart.min.js') ?>"></script>
</head>
<body>
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="<?= base_url() ?>" class="text-xl font-bold text-blue-600">PPIC MTU Indonesia</a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="<?= base_url('dashboard') ?>" 
                                class="<?= $currentPath === 'dashboard' ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Dashboard
                            </a>

                            <!-- Stok Dropdown -->
                            <div class="relative inline-flex items-center">
                                <button type="button" onclick="toggleDropdown('stokDropdown')"
                                    class="<?= str_starts_with($currentPath, 'stok') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Stok
                                    <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div id="stokDropdown" class="hidden absolute top-full left-0 mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1" role="menu">
                                        <a href="<?= base_url('stok/tabung') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            <i class="fas fa-gas-pump mr-2"></i>Stok Tabung
                                        </a>
                                        <a href="<?= base_url('stok/bahan-baku') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            <i class="fas fa-box mr-2"></i>Stok Bahan Baku
                                        </a>
                                        <a href="<?= base_url('stok/opname/tabung') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            <i class="fas fa-clipboard-check mr-2"></i>Opname Tabung
                                        </a>
                                        <a href="<?= base_url('stok/opname/bahan-baku') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            <i class="fas fa-clipboard-list mr-2"></i>Opname Bahan Baku
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Dropdown Transaksi -->
                            <div class="relative inline-flex items-center">
                                <button type="button" onclick="toggleDropdown('transaksiDropdown')"
                                    class="<?= str_starts_with($currentPath, 'transaksi') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Transaksi
                                    <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div id="transaksiDropdown" class="hidden absolute top-full left-0 mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1" role="menu">
                                        <a href="<?= base_url('transaksi/tabung') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Transaksi Tabung</a>
                                        <a href="<?= base_url('transaksi/bahan-baku') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Transaksi Bahan Baku</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Dropdown Master -->
                            <div class="relative inline-flex items-center">
                                <button type="button" onclick="toggleDropdown('masterDropdown')"
                                    class="<?= str_starts_with($currentPath, 'master') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Master Data
                                    <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div id="masterDropdown" class="hidden absolute top-full left-0 mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1" role="menu">
                                        <a href="<?= base_url('master/tabung') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Master Tabung</a>
                                        <a href="<?= base_url('master/bahan-baku') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Master Bahan Baku</a>
                                        <a href="<?= base_url('master/client') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Master Client</a>
                                        
                                    </div>
                                </div>
                            </div>

                            <!-- Dropdown Laporan -->
                            <div class="relative inline-flex items-center">
                                <button type="button" onclick="toggleDropdown('laporanDropdown')"
                                    class="<?= str_starts_with($currentPath, 'laporan') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Laporan
                                    <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div id="laporanDropdown" class="hidden absolute top-full left-0 mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1" role="menu">
                                        <a href="<?= base_url('laporan/bahanbaku') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Laporan Bahan Baku</a>
                                        <a href="<?= base_url('laporan/tabung') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Laporan Tabung</a>
                                        <a href="<?= base_url('laporan/stok-opname') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Stok Opname</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <div class="relative">
                            <button type="button" onclick="toggleDropdown('profileDropdown')" 
                                class="flex text-sm rounded-full focus:outline-none">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </button>
                            <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-1" role="menu">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Profil</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Pengaturan</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Keluar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?= session()->getFlashdata('success') ?></span>
                    </div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('error')): ?>
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?= session()->getFlashdata('error') ?></span>
                    </div>
                <?php endif; ?>

<script>
function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    const allDropdowns = document.querySelectorAll('[id$="Dropdown"]');
    
    // Hide all other dropdowns
    allDropdowns.forEach(d => {
        if (d.id !== dropdownId && !d.classList.contains('hidden')) {
            d.classList.add('hidden');
        }
    });

    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const dropdowns = document.querySelectorAll('[id$="Dropdown"]');
    const buttons = document.querySelectorAll('button');
    
    // Check if click is outside any dropdown button
    let clickedOnButton = false;
    buttons.forEach(button => {
        if (button.contains(event.target)) {
            clickedOnButton = true;
        }
    });

    if (!clickedOnButton) {
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    }
});
</script>
</body>
</html> 