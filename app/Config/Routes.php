<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 * Showroom Mobil Bekas - Routing
 */

// =============================================
// PUBLIC / SHOP
// =============================================
$routes->get('/',              'Home::index');

// =============================================
// AUTH
// =============================================
$routes->get('/login',         'Auth::index');
$routes->post('/login/proses', 'Auth::proses');
$routes->get('/logout',        'Auth::logout');

// =============================================
// DASHBOARD
// =============================================
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);

// =============================================
// SUPPLIER
// =============================================
$routes->group('supplier', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',             'Supplier::index');
    $routes->get('create',        'Supplier::create');
    $routes->post('store',        'Supplier::store');
    $routes->get('edit/(:num)',   'Supplier::edit/$1');
    $routes->post('update/(:num)','Supplier::update/$1');
    $routes->get('delete/(:num)', 'Supplier::delete/$1');
});

// =============================================
// MOBIL
// =============================================
$routes->group('mobil', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',             'Mobil::index');
    $routes->get('create',        'Mobil::create');
    $routes->post('store',        'Mobil::store');
    $routes->get('edit/(:num)',   'Mobil::edit/$1');
    $routes->post('update/(:num)','Mobil::update/$1');
    $routes->get('delete/(:num)', 'Mobil::delete/$1');
});

// =============================================
// CUSTOMER
// =============================================
$routes->group('customer', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',             'Customer::index');
    $routes->get('create',        'Customer::create');
    $routes->post('store',        'Customer::store');
    $routes->get('edit/(:num)',   'Customer::edit/$1');
    $routes->post('update/(:num)','Customer::update/$1');
    $routes->get('delete/(:num)', 'Customer::delete/$1');
});

// =============================================
// PEMBELIAN (Beli dari Supplier)
// =============================================
$routes->group('pembelian', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',             'Pembelian::index');
    $routes->get('create',        'Pembelian::create');
    $routes->post('store',        'Pembelian::store');
    $routes->get('edit/(:num)',   'Pembelian::edit/$1');
    $routes->post('update/(:num)','Pembelian::update/$1');
    $routes->get('delete/(:num)', 'Pembelian::delete/$1');
    $routes->get('selesai/(:num)','Pembelian::selesai/$1');
});

// =============================================
// PEMESANAN (Booking dari Customer)
// =============================================
$routes->group('pemesanan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',                     'Pemesanan::index');
    $routes->get('create',                'Pemesanan::create');
    $routes->post('store',                'Pemesanan::store');
    $routes->get('edit/(:num)',           'Pemesanan::edit/$1');
    $routes->post('update/(:num)',        'Pemesanan::update/$1');
    $routes->get('delete/(:num)',         'Pemesanan::delete/$1');
    $routes->get('detail/(:num)',         'Pemesanan::detail/$1');
    $routes->get('batal/(:num)',          'Pemesanan::batal/$1');
    $routes->get('cek-tempo',            'Pemesanan::cekTempo');
});

// =============================================
// PENJUALAN
// =============================================
$routes->group('penjualan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',                       'Penjualan::index');
    $routes->get('create/(:num)',           'Penjualan::create/$1');
    $routes->post('store',                  'Penjualan::store');
    $routes->get('edit/(:num)',             'Penjualan::edit/$1');
    $routes->post('update/(:num)',          'Penjualan::update/$1');
    $routes->get('delete/(:num)',           'Penjualan::delete/$1');
    $routes->get('detail/(:num)',           'Penjualan::detail/$1');
    $routes->get('update-stnk/(:num)',      'Penjualan::updateStnk/$1');
    $routes->get('update-bpkb/(:num)',      'Penjualan::updateBpkb/$1');
});

// =============================================
// PEMBAYARAN
// =============================================
$routes->group('pembayaran', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',                        'Pembayaran::index');
    $routes->get('create/(:num)',            'Pembayaran::create/$1');
    $routes->post('store',                   'Pembayaran::store');
    $routes->get('verifikasi/(:num)',        'Pembayaran::verifikasi/$1');
    $routes->get('tolak/(:num)',             'Pembayaran::tolak/$1');
    $routes->get('detail/(:num)',            'Pembayaran::detail/$1');
    $routes->get('cetak-kwitansi/(:num)',    'Pembayaran::cetakKwitansi/$1');
});

// =============================================
// PENYERAHAN MOBIL
// =============================================
$routes->group('penyerahan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',                      'PenyerahanMobil::index');
    $routes->get('create/(:num)',          'PenyerahanMobil::create/$1');
    $routes->post('store',                 'PenyerahanMobil::store');
    $routes->get('edit/(:num)',            'PenyerahanMobil::edit/$1');
    $routes->post('update/(:num)',         'PenyerahanMobil::update/$1');
    $routes->get('update-stnk/(:num)',     'PenyerahanMobil::updateStnk/$1');
    $routes->get('update-bpkb/(:num)',     'PenyerahanMobil::updateBpkb/$1');
    $routes->get('cetak-surat-jalan/(:num)','PenyerahanMobil::cetakSuratJalan/$1');
});

// =============================================
// LAPORAN
// =============================================
$routes->group('laporan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/',               'Laporan::index');
    $routes->post('generate',       'Laporan::generate');
    $routes->get('cetak/(:num)',    'Laporan::cetak/$1');
});
