<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('auth/authenticate', 'Auth::authenticate');
$routes->get('logout', 'Auth::logout');

// Dashboard routes (require authentication)
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('/api/stock-data', 'Dashboard::getStockData');

// Tambahkan route untuk bahan baku detail di dalam group dashboard
$routes->group('dashboard', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('bahan-baku-detail', 'Dashboard::bahanBakuDetail');
    $routes->get('export-bahan-baku', 'Dashboard::exportBahanBaku');
    $routes->get('laporan-bahan-baku', 'Laporan::bahanBaku');
    $routes->get('laporan-tabung', 'Laporan::tabung');
    $routes->get('laporan-transaksi-tabung', 'Laporan::transaksiTabung');
});

// Transaksi routes (require authentication)
$routes->group('transaksi', ['filter' => 'auth'], function($routes) {
    $routes->get('tabung', 'Transaksi::tabung');
    $routes->get('bahan-baku', 'Transaksi::bahanBaku');
    $routes->post('tabung/save', 'Transaksi::saveTabung');
    $routes->post('bahan-baku/save', 'Transaksi::saveBahanBaku');
    $routes->get('client/addresses/(:num)', 'Transaksi::getClientAddresses/$1');
    $routes->get('delivery-order/(:num)', 'Transaksi::viewDeliveryOrder/$1');
    $routes->get('print-do/(:num)', 'Transaksi::printDeliveryOrder/$1');
    $routes->get('generate-do-number', 'TransaksiController::generateDONumber');
});

// Stok routes (require authentication)
$routes->group('stok', ['filter' => 'auth'], function($routes) {
    $routes->get('gudang', 'Stok::perGudang');
    $routes->get('tabung', 'Stok::tabung');
    $routes->get('bahan-baku', 'Stok::bahanBaku');
    $routes->post('update-bahan-baku', 'Stok::updateBahanBaku');
    $routes->get('opname/tabung', 'Stok::opnameTabung');
    $routes->get('opname/bahan-baku', 'Stok::opnameBahanBaku');
    $routes->post('opname/save', 'Stok::saveOpname');
});

// Master routes (require authentication)
$routes->group('master', ['filter' => 'auth'], function($routes) {
    // Items Routes
    $routes->get('items/create', 'Items::create');
    $routes->post('items/store', 'Items::store');
    $routes->get('items/edit/(:num)', 'Items::edit/$1');
    $routes->post('items/update/(:num)', 'Items::update/$1');
    $routes->get('items/delete/(:num)', 'Items::delete/$1');
    $routes->get('dashboard/bahan-baku-detail', 'Items::bahanBakuDetail');
    $routes->get('dashboard/export-bahan-baku', 'Items::exportBahanBaku');

    // Client Routes
    $routes->get('client', 'Client::index');
    $routes->get('client/create', 'Client::create');
    $routes->post('client/store', 'Client::store');
    $routes->get('client/edit/(:num)', 'Client::edit/$1');
    $routes->post('client/update/(:num)', 'Client::update/$1');
    $routes->get('client/delete/(:num)', 'Client::delete/$1');
    $routes->get('client/view/(:num)', 'Client::view/$1');

    // Other master routes
    $routes->get('tabung', 'Master::tabung');
    $routes->get('bahan-baku', 'Master::bahanBaku');
    $routes->post('tabung/store', 'Master::storeTabung');
    $routes->post('bahan-baku/store', 'Master::storeBahanBaku');
    $routes->post('store-items-part', 'Master::storeItemsPart');
    $routes->get('items-part/delete/(:num)', 'Master::deleteItemsPart/$1');
    $routes->get('type/delete/(:num)', 'Master::deleteType/$1');
});

// Delivery Order Routes
$routes->get('delivery-order', 'DeliveryOrder::index');
$routes->get('delivery-order/create', 'DeliveryOrder::create');
$routes->post('delivery-order', 'DeliveryOrder::store');
$routes->get('delivery-order/(:num)', 'DeliveryOrder::view/$1');
$routes->get('delivery-order/(:num)/edit', 'DeliveryOrder::edit/$1');
$routes->put('delivery-order/(:num)', 'DeliveryOrder::update/$1');
$routes->delete('delivery-order/(:num)', 'DeliveryOrder::delete/$1');
$routes->get('approval/delivery', 'Approval::delivery');
$routes->get('delivery-approval/view/(:num)', 'Approval::view/$1');
$routes->post('delivery-approval/approve/(:num)', 'Approval::approve/$1');
$routes->post('delivery-approval/reject/(:num)', 'Approval::reject/$1');

// Routes untuk Laporan
$routes->group('laporan', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Laporan::index');
    $routes->get('bahan-baku', 'Laporan::bahanBaku');
    $routes->get('tabung', 'Laporan::tabung');
    $routes->get('transaksi-tabung', 'Laporan::transaksiTabung');
    $routes->get('export-bahan-baku', 'Laporan::exportBahanBaku');
    $routes->get('export-tabung', 'Laporan::exportTabung');
    $routes->get('export-transaksi-tabung', 'Laporan::exportTransaksiTabung');
    $routes->get('stok-opname', 'Laporan::stokOpname');
});

$routes->get('administrator/users', 'Administrator::users', ['filter' => 'role:admin,supervisor,manager']);
$routes->get('administrator/users/add', 'Administrator::add_user', ['filter' => 'role:admin,supervisor,manager']);
$routes->post('administrator/users/save', 'Administrator::save_user', ['filter' => 'role:admin,supervisor,manager']);

$routes->get('api/stock-data', 'Api::getStockData');
