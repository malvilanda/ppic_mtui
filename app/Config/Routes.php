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
$routes->group('dashboard', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('bahan-baku-detail', 'Dashboard::bahanBakuDetail');
    $routes->get('export-bahan-baku', 'Dashboard::exportBahanBaku');
    $routes->get('laporan-bahan-baku', 'Laporan::bahanBaku');
    $routes->get('laporan-tabung', 'Laporan::tabung');
});

// API routes
$routes->get('api/stock-data', 'Dashboard::getStockData');

// Transaksi routes (require authentication)
$routes->group('transaksi', ['filter' => 'auth'], function($routes) {
    $routes->get('tabung', 'Transaksi::tabung');
    $routes->get('bahan-baku', 'Transaksi::bahanBaku');
    $routes->post('tabung/save', 'Transaksi::saveTabung');
    $routes->post('bahan-baku/save', 'Transaksi::saveBahanBaku');
    $routes->get('client/addresses/(:num)', 'Transaksi::getClientAddresses/$1');
    $routes->get('delivery-order/(:num)', 'Transaksi::viewDeliveryOrder/$1');
    $routes->get('print-do/(:num)', 'Transaksi::printDeliveryOrder/$1');
    $routes->get('generate-do-number', 'Transaksi::generateDONumber');
    $routes->get('check-part-number/(:num)/(:num)', 'Transaksi::checkPartNumber/$1/$2');
    $routes->get('bahan-baku/list', 'Transaksi::getTransaksiBahanBaku');
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

// Auth Routes
$routes->group('auth', ['namespace' => 'App\\Controllers'], function($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::attemptLogin');
    $routes->get('logout', 'Auth::logout');
    $routes->get('users', 'Auth::users');
    $routes->get('login-history', 'Auth::loginHistory');
    $routes->get('login-history/(:num)', 'Auth::loginHistory/$1');
});

// Master routes (require authentication)
$routes->group('master', ['filter' => 'auth'], function($routes) {
    // Items Routes
    $routes->get('items/create', 'Items::create');
    $routes->post('items/store', 'Items::store');
    $routes->get('items/edit/(:num)', 'Items::edit/$1');
    $routes->post('items/update/(:num)', 'Items::update/$1');
    $routes->get('items/delete/(:num)', 'Items::delete/$1');
    
    // Client Routes
    $routes->get('client', 'Client::index');
    $routes->get('client/create', 'Client::create');
    $routes->post('client/store', 'Client::store');
    $routes->get('client/edit/(:num)', 'Client::edit/$1');
    $routes->post('client/update/(:num)', 'Client::update/$1');
    $routes->get('client/delete/(:num)', 'Client::delete/$1');
    $routes->get('client/view/(:num)', 'Client::view/$1');

    // User History Routes
    $routes->get('user-history', 'Master::userHistory');
    $routes->get('user-history/export', 'Master::exportUserHistory');

    // Other master routes
    $routes->get('tabung', 'Master::tabung');
    $routes->get('bahan-baku', 'Master::bahanBaku');
    $routes->post('tabung/store', 'Master::storeTabung');
    $routes->post('bahan-baku/store', 'Master::storeBahanBaku');
    $routes->post('store-items-part', 'Master::storeItemsPart');
    $routes->get('items-part/delete/(:num)', 'Master::deleteItemsPart/$1');
    $routes->get('type/delete/(:num)', 'Master::deleteType/$1');
});

// Laporan routes (require authentication)
$routes->group('laporan', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Laporan::index');
    $routes->get('bahan-baku', 'Laporan::bahanBaku');
    $routes->get('tabung', 'Laporan::tabung');
    $routes->get('export-bahan-baku', 'Laporan::exportBahanBaku');
    $routes->get('export-tabung', 'Laporan::exportTabung');
    $routes->get('stok-opname', 'Laporan::stokOpname');
    $routes->get('downloadPdfTabung', 'Laporan::downloadPdfTabung');
    $routes->get('downloadPdfBahanBaku', 'Laporan::downloadPdfBahanBaku');
});

// Administrator routes (require authentication and role)
$routes->group('administrator', ['filter' => ['auth', 'role:admin,supervisor,manager']], function($routes) {
    $routes->get('users', 'Administrator::users');
    $routes->get('users/add', 'Administrator::add_user');
    $routes->post('users/save', 'Administrator::save_user');
    $routes->get('user-history', 'Administrator::userHistory');
    $routes->get('user-history/export', 'Administrator::exportUserHistory');
});

// Delivery Order routes (require authentication)
$routes->group('delivery-order', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'DeliveryOrder::index');
    $routes->get('create', 'DeliveryOrder::create');
    $routes->post('/', 'DeliveryOrder::store');
    $routes->get('(:num)', 'DeliveryOrder::view/$1');
    $routes->get('(:num)/edit', 'DeliveryOrder::edit/$1');
    $routes->put('(:num)', 'DeliveryOrder::update/$1');
    $routes->delete('(:num)', 'DeliveryOrder::delete/$1');
});

// Approval routes (require authentication)
$routes->group('approval', ['filter' => 'auth'], function($routes) {
    $routes->get('delivery', 'Approval::delivery');
    $routes->get('delivery/view/(:num)', 'Approval::view/$1');
    $routes->post('delivery/approve/(:num)', 'Approval::approve/$1');
    $routes->post('delivery/reject/(:num)', 'Approval::reject/$1');
});

$routes->get('transaksi/tabung', 'Transaksi::tabung');
$routes->get('approval/transaksi', 'Approval::transaksi');
$routes->post('approval/approve', 'Approval::approve');
$routes->post('approval/reject', 'Approval::reject');
