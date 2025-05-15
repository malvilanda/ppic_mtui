<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPIC - MTU Indonesia</title>
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.min.css') ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/css/fontawesome.min.css') ?>">
    <!-- Chart.js -->
    <script src="<?= base_url('assets/js/chart.min.js') ?>"></script>
    <!-- Bootstrap CSS -->
    <link href="/assets/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="/assets/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Navigation Styles */
        .nav-container {
            position: relative;
            z-index: 40;
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .nav-item {
            position: relative;
            height: 64px;
            display: flex;
            align-items: center;
            margin: 0 0.25rem;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4B5563;
            border-radius: 0.375rem;
            transition: all 0.2s ease-in-out;
        }

        .nav-link:hover {
            color: #1F2937;
            background-color: #F3F4F6;
        }

        .nav-link.active {
            color: #2563EB;
            background-color: #EBF5FF;
        }

        .nav-link i {
            margin-right: 0.5rem;
            font-size: 1rem;
        }

        /* Dropdown Styles */
        .nav-dropdown {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .nav-dropdown-content {
            position: absolute;
            top: calc(100% + 0.25rem);
            right: 0;
            min-width: 240px;
            padding: 0.5rem;
            background-color: white;
            border-radius: 0.5rem;
            border: 1px solid #E5E7EB;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.2s ease-in-out;
            z-index: 50;
        }

        .nav-dropdown-content.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .nav-dropdown-content a {
            display: flex;
            align-items: center;
            padding: 0.625rem 1rem;
            color: #4B5563;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.375rem;
            transition: all 0.15s ease-in-out;
        }

        .nav-dropdown-content a:hover {
            color: #2563EB;
            background-color: #EBF5FF;
        }

        .nav-dropdown-content a i {
            width: 1.25rem;
            margin-right: 0.75rem;
            font-size: 1rem;
            color: #6B7280;
        }

        .nav-dropdown-content a:hover i {
            color: #2563EB;
        }

        /* Profile Menu */
        .profile-menu {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .profile-menu:hover {
            background-color: #F3F4F6;
        }

        .profile-avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 9999px;
            background: linear-gradient(to right, #3B82F6, #2563EB);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 500;
            margin-right: 0.75rem;
        }

        .profile-info {
            margin-right: 0.5rem;
        }

        .profile-name {
            font-size: 0.875rem;
            font-weight: 500;
            color: #1F2937;
        }

        .profile-role {
            font-size: 0.75rem;
            color: #6B7280;
        }

        /* Mobile Menu */
        @media (max-width: 640px) {
            .mobile-menu {
                background-color: white;
                border-top: 1px solid #E5E7EB;
                padding: 0.5rem 0;
            }

            .mobile-menu a {
                display: flex;
                align-items: center;
                padding: 0.75rem 1.5rem;
                color: #4B5563;
                font-size: 0.875rem;
                font-weight: 500;
            }

            .mobile-menu a:hover {
                background-color: #F3F4F6;
                color: #2563EB;
            }

            .mobile-menu a i {
                width: 1.5rem;
                margin-right: 0.75rem;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="<?= base_url() ?>" class="flex items-center space-x-2">
                            <i class="fas fa-industry text-blue-600 text-2xl"></i>
                            <span class="text-xl font-semibold text-gray-900">PPIC MTU Indonesia</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-4">
                        <div class="nav-item">
                            <a href="<?= base_url('dashboard') ?>" 
                                class="nav-link <?= current_url() == base_url('dashboard') ? 'active text-blue-600' : 'text-gray-500 hover:text-gray-900' ?> inline-flex items-center px-3 text-sm font-medium">
                                <i class="fas fa-chart-line mr-2"></i>
                                Dashboard
                            </a>
                        </div>
                        
                        <!-- Stok Dropdown -->
                        <div class="nav-item">
                            <div class="relative nav-dropdown">
                                <button class="nav-link <?= strpos(current_url(), base_url('stok')) === 0 ? 'active text-blue-600' : 'text-gray-500 hover:text-gray-900' ?> inline-flex items-center px-3 text-sm font-medium" id="stok-menu-button">
                                    <i class="fas fa-boxes mr-2"></i>
                                    Stok
                                    <svg class="ml-1 h-4 w-4 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                    </svg>
                                </button>
                                <div class="nav-dropdown-content absolute z-10 mt-0 w-48 rounded-md bg-white py-2 shadow-lg ring-1 ring-black ring-opacity-5" id="stok-menu">
                                    <a href="<?= base_url('stok/gudang') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-warehouse mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                        Stok per Gudang
                                    </a>
                                    <a href="<?= base_url('stok/tabung') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-flask mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                        Stok Tabung
                                    </a>
                                    <a href="<?= base_url('stok/bahan-baku') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-box mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                        Stok Bahan Baku
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Transaksi Dropdown -->
                        <div class="nav-item">
                            <div class="relative nav-dropdown">
                                <button class="nav-link <?= strpos(current_url(), base_url('transaksi')) === 0 || strpos(current_url(), base_url('approval')) === 0 ? 'active text-blue-600' : 'text-gray-500 hover:text-gray-900' ?> inline-flex items-center px-3 text-sm font-medium" id="transaksi-menu-button">
                                    <i class="fas fa-exchange-alt mr-2"></i>
                                    Transaksi
                                    <svg class="ml-1 h-4 w-4 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                    </svg>
                                </button>
                                <div class="nav-dropdown-content absolute z-10 mt-0 w-48 rounded-md bg-white py-2 shadow-lg ring-1 ring-black ring-opacity-5" id="transaksi-menu">
                                    <a href="<?= base_url('transaksi/tabung') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-arrow-right mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                        Transaksi Tabung
                                    </a>
                                    <a href="<?= base_url('transaksi/bahan_baku') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-arrow-left mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                        Transaksi Bahan Baku
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <a href="<?= base_url('approval/delivery') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-clipboard-check mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                        Persetujuan Surat Jalan
                                    </a>
                                    <!-- <a href="<?= base_url('approval/transaksi') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-check-double mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                        Persetujuan Transaksi
                                    </a> -->
                                </div>
                            </div>
                        </div>

                        <!-- Laporan Dropdown -->
                        <div class="nav-item">
                            <div class="relative nav-dropdown">
                                <button class="nav-link <?= strpos(current_url(), base_url('laporan')) === 0 ? 'active text-blue-600' : 'text-gray-500 hover:text-gray-900' ?> inline-flex items-center px-3 text-sm font-medium" id="laporan-menu-button">
                                    <i class="fas fa-file-alt mr-2"></i>
                                    Laporan
                                    <svg class="ml-1 h-4 w-4 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                    </svg>
                                </button>
                                <div class="nav-dropdown-content absolute z-10 mt-0 w-48 rounded-md bg-white py-2 shadow-lg ring-1 ring-black ring-opacity-5" id="laporan-menu">
                                    <a href="<?= base_url('laporan/dashboard') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-chart-bar mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                        Dashboard
                                    </a>
                                    <a href="<?= base_url('laporan/bahan-baku') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-box mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                        Bahan Baku
                                    </a>
                                    <a href="<?= base_url('laporan/tabung') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-flask mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                        Tabung
                                    </a>
                                    <a href="<?= base_url('laporan/stok-opname') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-clipboard-check mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                        Stok Opname
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Master Data Dropdown -->
                        <div class="nav-item">
                            <div class="relative nav-dropdown">
                                <button class="nav-link <?= strpos(current_url(), base_url('master')) === 0 ? 'active text-blue-600' : 'text-gray-500 hover:text-gray-900' ?> inline-flex items-center px-3 text-sm font-medium" id="master-menu-button">
                                    <i class="fas fa-database mr-2"></i>
                                    Master
                                    <svg class="ml-1 h-4 w-4 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                    </svg>
                                </button>
                                <div class="nav-dropdown-content absolute z-10 mt-0 w-48 rounded-md bg-white py-2 shadow-lg ring-1 ring-black ring-opacity-5" id="master-menu">
                                    <a href="<?= base_url('master/client') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-users mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                        Data Client
                                    </a>
                                    <a href="<?= base_url('master/tabung') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-flask mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                        Data Tabung
                                    </a>
                                    <a href="<?= base_url('master/bahan-baku') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-box mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                        Data Bahan Baku
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Administrator Dropdown -->
                        <?php if (in_array(session()->get('role'), ['admin', 'supervisor', 'manager'])): ?>
                        <div class="nav-item">
                            <div class="relative nav-dropdown">
                                <button class="nav-link <?= strpos(current_url(), base_url('administrator')) === 0 ? 'active text-blue-600' : 'text-gray-500 hover:text-gray-900' ?> inline-flex items-center px-3 text-sm font-medium" id="administrator-menu-button">
                                    <i class="fas fa-cog mr-2"></i>
                                    Administrator
                                    <svg class="ml-1 h-4 w-4 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                    </svg>
                                </button>
                                <div class="nav-dropdown-content absolute z-10 mt-0 w-48 rounded-md bg-white py-2 shadow-lg ring-1 ring-black ring-opacity-5" id="administrator-menu">
                                    <a href="<?= base_url('administrator/users') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-users mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                        Tambah User
                                    </a>
                                    <a href="<?= base_url('administrator/user-history') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                        <i class="fas fa-history mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                        Riwayat User
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="sm:hidden flex items-center">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" id="mobile-menu-button">
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Profile Dropdown -->
                <div class="hidden sm:flex sm:items-center">
                    <div class="relative">
                        <button type="button" class="flex items-center max-w-xs rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" id="user-menu-button">
                            <span class="sr-only">Open user menu</span>
                            <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-medium shadow-inner">
                                <?= substr(session()->get('username'), 0, 1) ?>
                            </div>
                            <span class="ml-3 text-gray-700 font-medium"><?= session()->get('username') ?></span>
                            <svg class="ml-2 h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                            </svg>
                        </button>
                        <div class="hidden absolute right-0 mt-2 w-48 rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" id="user-menu">
                            <div class="px-4 py-2 text-xs text-gray-500">
                                Logged in as
                                <div class="font-medium text-gray-900"><?= session()->get('email') ?></div>
                            </div>
                            <div class="border-t border-gray-100"></div>
                            <a href="<?= base_url('profile') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                                <i class="fas fa-user mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                Profile
                            </a>
                            <a href="<?= base_url('logout') ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600">
                                <i class="fas fa-sign-out-alt mr-3 text-gray-400 group-hover:text-red-500"></i>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="hidden sm:hidden bg-white border-t border-gray-200" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="<?= base_url('dashboard') ?>" 
                    class="<?= current_url() == base_url('dashboard') ? 'bg-blue-50 border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    <i class="fas fa-chart-line mr-2"></i>
                    Dashboard
                </a>

                <!-- Stok Mobile -->
                <div class="mobile-dropdown">
                    <button class="w-full text-left <?= strpos(current_url(), base_url('stok')) === 0 ? 'bg-blue-50 border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?> flex items-center justify-between pl-3 pr-4 py-2 border-l-4 text-base font-medium" data-target="stok-mobile-menu">
                        <div class="flex items-center">
                            <i class="fas fa-boxes mr-2"></i>
                            Stok
                        </div>
                        <svg class="h-5 w-5 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                        </svg>
                    </button>
                    <div class="hidden bg-gray-50" id="stok-mobile-menu">
                        <a href="<?= base_url('stok/gudang') ?>" class="block pl-8 pr-4 py-2 text-base text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-warehouse mr-2"></i>
                            Stok per Gudang
                        </a>
                        <a href="<?= base_url('stok/tabung') ?>" class="block pl-8 pr-4 py-2 text-base text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-flask mr-2"></i>
                            Stok Tabung
                        </a>
                        <a href="<?= base_url('stok/bahan-baku') ?>" class="block pl-8 pr-4 py-2 text-base text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-box mr-2"></i>
                            Stok Bahan Baku
                        </a>
                    </div>
                </div>

                <!-- Transaksi Mobile -->
                <div class="mobile-dropdown">
                    <button class="w-full text-left <?= strpos(current_url(), base_url('transaksi')) === 0 || strpos(current_url(), base_url('approval')) === 0 ? 'bg-blue-50 border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?> flex items-center justify-between pl-3 pr-4 py-2 border-l-4 text-base font-medium" data-target="transaksi-mobile-menu">
                        <div class="flex items-center">
                            <i class="fas fa-exchange-alt mr-2"></i>
                            Transaksi
                        </div>
                        <svg class="h-5 w-5 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                        </svg>
                    </button>
                    <div class="hidden bg-gray-50" id="transaksi-mobile-menu">
                        <a href="<?= base_url('transaksi/bahan-baku') ?>" class="block pl-8 pr-4 py-2 text-base text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-box mr-2"></i>
                            Bahan Baku
                        </a>
                        <a href="<?= base_url('transaksi/tabung') ?>" class="block pl-8 pr-4 py-2 text-base text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-flask mr-2"></i>
                            Tabung
                        </a>
                        <a href="<?= base_url('approval/transaksi') ?>" class="block pl-8 pr-4 py-2 text-base text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-check-circle mr-2"></i>
                            Persetujuan
                        </a>
                    </div>
                </div>

                <!-- Delivery Order Mobile -->
                <!-- <div class="mobile-dropdown">
                    <button class="w-full text-left <?= strpos(current_url(), base_url('delivery-order')) === 0 ? 'bg-blue-50 border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?> flex items-center justify-between pl-3 pr-4 py-2 border-l-4 text-base font-medium" data-target="delivery-order-mobile-menu">
                        <div class="flex items-center">
                            <i class="fas fa-truck mr-2"></i>
                            Surat Jalan
                        </div>
                        <svg class="h-5 w-5 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                        </svg>
                    </button>
                    <div class="hidden bg-gray-50" id="delivery-order-mobile-menu">
                        <a href="<?= base_url('delivery-order') ?>" class="block pl-8 pr-4 py-2 text-base text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-list mr-2"></i>
                            Daftar Surat Jalan
                        </a>
                        <a href="<?= base_url('delivery-order/create') ?>" class="block pl-8 pr-4 py-2 text-base text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-plus mr-2"></i>
                            Buat Surat Jalan
                        </a>
                    </div>
                </div> -->

                <!-- Master Data Mobile -->
                <div class="mobile-dropdown">
                    <button class="w-full text-left <?= strpos(current_url(), base_url('master')) === 0 ? 'bg-blue-50 border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?> flex items-center justify-between pl-3 pr-4 py-2 border-l-4 text-base font-medium" data-target="master-mobile-menu">
                        <div class="flex items-center">
                            <i class="fas fa-database mr-2"></i>
                            Master Data
                        </div>
                        <svg class="h-5 w-5 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                        </svg>
                    </button>
                    <div class="hidden bg-gray-50" id="master-mobile-menu">
                        <a href="<?= base_url('master/client') ?>" class="block pl-8 pr-4 py-2 text-base text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-users mr-2"></i>
                            Data Client
                        </a>
                        <a href="<?= base_url('master/tabung') ?>" class="block pl-8 pr-4 py-2 text-base text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-flask mr-2"></i>
                            Data Tabung
                        </a>
                        <a href="<?= base_url('master/bahan-baku') ?>" class="block pl-8 pr-4 py-2 text-base text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-box mr-2"></i>
                            Data Bahan Baku
                        </a>
                    </div>
                </div>

                <!-- Laporan Mobile -->
                <div class="mobile-dropdown">
                    <button class="w-full text-left <?= strpos(current_url(), base_url('laporan')) === 0 ? 'bg-blue-50 border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:text-gray-900' ?> flex items-center justify-between pl-3 pr-4 py-2 border-l-4 text-base font-medium" data-target="laporan-mobile-menu">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt mr-2"></i>
                            Laporan
                        </div>
                        <svg class="h-5 w-5 transform transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                        </svg>
                    </button>
                    <div class="hidden bg-gray-50" id="laporan-mobile-menu">
                        <a href="<?= base_url('laporan/dashboard') ?>" class="block pl-8 pr-4 py-2 text-base text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Dashboard
                        </a>
                        <a href="<?= base_url('laporan/bahan-baku') ?>" class="block pl-8 pr-4 py-2 text-base text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-box mr-2"></i>
                            Bahan Baku
                        </a>
                        <a href="<?= base_url('laporan/tabung') ?>" class="block pl-8 pr-4 py-2 text-base text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-flask mr-2"></i>
                            Tabung
                        </a>
                        <a href="<?= base_url('laporan/stok-opname') ?>" class="block pl-8 pr-4 py-2 text-base text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-clipboard-check mr-2"></i>
                            Stok Opname
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile Profile Menu -->
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-medium shadow-inner">
                            <?= substr(session()->get('username'), 0, 1) ?>
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800"><?= session()->get('username') ?></div>
                        <div class="text-sm font-medium text-gray-500"><?= session()->get('email') ?></div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <a href="<?= base_url('profile') ?>" class="flex items-center px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-900 hover:bg-gray-100">
                        <i class="fas fa-user mr-3"></i>
                        Profile
                    </a>
                    <a href="<?= base_url('logout') ?>" class="flex items-center px-4 py-2 text-base font-medium text-gray-500 hover:text-red-600 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4" id="success-alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700"><?= session()->getFlashdata('success') ?></p>
                    </div>
                </div>
            </div>
        </div>
        <script>
            setTimeout(function() {
                document.getElementById('success-alert').style.transition = 'opacity 0.5s';
                document.getElementById('success-alert').style.opacity = '0';
                setTimeout(function() {
                    document.getElementById('success-alert').style.display = 'none';
                }, 500);
            }, 3000);
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700"><?= session()->getFlashdata('error') ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <script>
        // Toggle user menu
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');
        
        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', (e) => {
                e.stopPropagation();
                userMenu.classList.toggle('hidden');
                // Close other dropdowns
                document.querySelectorAll('.nav-dropdown-content').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            });
        }

        // Toggle mobile menu
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Desktop dropdowns
        ['stok', 'transaksi', 'master', 'delivery-order', 'administrator', 'approval', 'laporan'].forEach(menu => {
            const button = document.getElementById(`${menu}-menu-button`);
            const dropdown = document.getElementById(`${menu}-menu`);
            
            if (button && dropdown) {
                button.addEventListener('click', () => {
                    dropdown.classList.toggle('show');
                    // Close other dropdowns
                    document.querySelectorAll('.nav-dropdown-content').forEach(dropdown => {
                        if (dropdown !== dropdown) {
                            dropdown.classList.remove('show');
                        }
                    });
                });
            }
        });

        // Mobile dropdowns
        document.querySelectorAll('.mobile-dropdown button').forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.getAttribute('data-target');
                const targetMenu = document.getElementById(targetId);
                
                if (targetMenu) {
                    // Toggle current menu
                    targetMenu.classList.toggle('hidden');
                    
                    // Close other mobile menus
                    document.querySelectorAll('.mobile-dropdown > div').forEach(menu => {
                        if (menu.id !== targetId) {
                            menu.classList.add('hidden');
                        }
                    });
                    
                    // Toggle arrow rotation
                    const arrow = button.querySelector('svg');
                    if (arrow) {
                        arrow.classList.toggle('rotate-180');
                    }
                }
            });
        });
    </script>

    <!-- Session Timeout Script -->
    <?php if (session()->get('logged_in')): ?>
    <script src="<?= base_url('assets/js/session-timeout.js') ?>"></script>
    <?php endif; ?>
</body>
</html>