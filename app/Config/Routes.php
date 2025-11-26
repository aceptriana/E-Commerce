<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', 'Home::index');
$routes->get('produk/detail/(:num)', 'Produk::detail/$1');
$routes->post('cart/add', 'Cart::add');
$routes->get('kategori/(:num)', 'Kategori::show/$1');

// Ulasan (Review) routes
$routes->get('ulasan/tulis/(:num)', 'Ulasan::tulis/$1');
$routes->post('ulasan/simpan', 'Ulasan::simpan');
$routes->get('ulasan/hapus/(:num)', 'Ulasan::hapus/$1');

// Auth routes
$routes->group('auth', function($routes) {
    $routes->get('/', 'Auth::index');
    $routes->post('processLogin', 'Auth::processLogin');
    $routes->post('processRegister', 'Auth::processRegister');
    $routes->get('forgot-password', 'Auth::forgotPassword');
    $routes->post('resetPassword', 'Auth::resetPassword');
});

// Logout route (outside auth group for easier access)
$routes->get('/logout', 'Auth::logout');

// Role-based redirect
$routes->get('/redirect', 'Auth::redirectByRole', ['filter' => 'roleRedirect']);

// Profile management routes (accessible to all logged-in users)
$routes->group('profile', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Profile::index');
    $routes->post('update', 'Profile::update');
    $routes->get('change-password', 'Profile::changePasswordForm');
    $routes->post('change-password', 'Profile::changePassword');
});

// Returns (Retur) routes
$routes->group('returns', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Returns::index');
    $routes->get('create/(:num)', 'Returns::create/$1');
    $routes->get('create', 'Returns::create');
    $routes->post('store', 'Returns::store');
});

// Favorit (Wishlist) routes
$routes->group('favorit', ['filter' => 'auth'], function($routes) {
	$routes->get('/', 'Favorit::index');
	$routes->get('tambah/(:num)', 'Favorit::tambah/$1');
	$routes->get('hapus/(:num)', 'Favorit::hapus/$1');
});

// Pelanggan routes
$routes->group('pelanggan', ['filter' => 'auth'], function($routes) {
    $routes->get('beranda', 'Pelanggan::beranda');
    $routes->get('produk', 'Pelanggan::produk');
    $routes->get('produk/(:num)', 'Pelanggan::detailProduk/$1');
    $routes->get('keranjang', 'Pelanggan::keranjang');
    $routes->post('keranjang/tambah', 'Pelanggan::tambahKeranjang');
    $routes->post('keranjang/update', 'Pelanggan::updateKeranjang');
    $routes->get('keranjang/hapus/(:num)', 'Pelanggan::hapusKeranjang/$1');
    $routes->get('checkout', 'Pelanggan::checkout');
    $routes->post('checkout/proses', 'Pelanggan::prosesCheckout');
    $routes->get('pesanan', 'Pelanggan::daftarPesanan');
    $routes->get('pesanan/(:num)', 'Pelanggan::detailPesanan/$1');
});

// Admin routes
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');

        // Kategori Routes
        $routes->get('kategori', 'Admin\Kategori::index');
        $routes->get('kategori/create', 'Admin\Kategori::create');
        $routes->post('kategori/store', 'Admin\Kategori::store');
        $routes->get('kategori/edit/(:num)', 'Admin\Kategori::edit/$1');
        $routes->post('kategori/update/(:num)', 'Admin\Kategori::update/$1');
        $routes->get('kategori/delete/(:num)', 'Admin\Kategori::delete/$1');
   
    // Produk Routes
    $routes->get('produk', 'Admin\Produk::index');
    $routes->get('produk/create', 'Admin\Produk::create');
    $routes->post('produk/store', 'Admin\Produk::store');
    $routes->get('produk/edit/(:num)', 'Admin\Produk::edit/$1');
    $routes->post('produk/update/(:num)', 'Admin\Produk::update/$1');
    $routes->get('produk/delete/(:num)', 'Admin\Produk::delete/$1');
    $routes->get('produk/detail/(:num)', 'Admin\Produk::detail/$1');

    // Pesanan Routes
    $routes->get('pesanan', 'Admin\Pesanan::index');
        // Admin Returns (Retur) management
        $routes->get('returns', 'Admin\Returns::index');
        $routes->post('returns/approve/(:num)', 'Admin\Returns::approve/$1');
        $routes->post('returns/reject/(:num)', 'Admin\Returns::reject/$1');
    $routes->get('pesanan/detail/(:num)', 'Admin\Pesanan::detail/$1');
    $routes->get('pesanan/update-status/(:num)', 'Admin\Pesanan::updateStatusForm/$1');
    $routes->post('pesanan/update-status/(:num)', 'Admin\Pesanan::updateStatus/$1');
    $routes->get('pesanan/tracking/(:num)', 'Admin\Pesanan::tracking/$1');
    $routes->post('pesanan/update-resi/(:num)', 'Admin\Pesanan::updateResi/$1');

   // Laporan Routes (Baru)
   $routes->get('laporan', 'Admin\Laporan::index');
   $routes->get('laporan/export', 'Admin\Laporan::export');
});

// Pemilik routes
$routes->group('pemilik', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Pemilik::dashboard');
    
    // Reports
    $routes->get('laporan/penjualan', 'Pemilik::laporanPenjualan');
    $routes->get('laporan/penjualan/cetak', 'Pemilik::cetakLaporanPenjualan');
    $routes->get('laporan/pendapatan', 'Pemilik::laporanPendapatan');
    $routes->get('laporan/pendapatan/cetak', 'Pemilik::cetakLaporanPendapatan');
    $routes->get('laporan/produk', 'Pemilik::laporanProduk');
    $routes->get('laporan/produk/cetak', 'Pemilik::cetakLaporanProduk');
    
    // Statistics and analytics
    $routes->get('statistik', 'Pemilik::statistik');
    $routes->get('statistik/produk-terlaris', 'Pemilik::produkTerlaris');
    $routes->get('statistik/pelanggan-teraktif', 'Pemilik::pelangganTeraktif');
    
    // Staff management (admins)
    $routes->get('staff', 'Pemilik::staff');
    $routes->get('staff/add', 'Pemilik::addStaff');
    $routes->post('staff/add', 'Pemilik::saveStaff');
    $routes->get('staff/edit/(:num)', 'Pemilik::editStaff/$1');
    $routes->post('staff/update/(:num)', 'Pemilik::updateStaff/$1');
    $routes->get('staff/delete/(:num)', 'Pemilik::deleteStaff/$1');
});

// Produk Routes
$routes->get('produk', 'Produk::index');
$routes->get('produk/search', 'Produk::search');
$routes->get('produk/kategori/(:num)', 'Produk::kategori/$1');
$routes->get('produk/preorder', 'Produk::preorder');

// Cart Routes
$routes->get('cart', 'Cart::index');
$routes->get('keranjang', 'Cart::index'); // Alias untuk keranjang
$routes->post('cart/add', 'Cart::add');
$routes->post('cart/update', 'Cart::update');
$routes->post('cart/remove/(:num)', 'Cart::remove/$1');

// Checkout routes - PERBAIKAN UTAMA
$routes->get('checkout', 'Checkout::index');
$routes->get('checkout/searchCity', 'Checkout::searchCity');
$routes->post('checkout/cek-ongkir', 'Checkout::cekOngkir'); // Perbaikan: konsisten dengan JS
$routes->post('checkout/process', 'Checkout::process');
$routes->post('checkout/notification', 'Checkout::notification');
$routes->get('checkout/success', 'Checkout::success');
$routes->get('checkout/pending', 'Checkout::pending');
$routes->get('checkout/test-rajaongkir', 'Checkout::testRajaOngkir'); // Test API RajaOngkir
$routes->get('checkout/failed', 'Checkout::failed');
$routes->get('checkout/order/(:any)', 'Checkout::order/$1');
$routes->get('checkout/history', 'Checkout::history');

// Error pages
$routes->get('403', 'ErrorPages::forbidden');
$routes->get('404', 'ErrorPages::notFound');

// API routes (jika dibutuhkan)
$routes->group('api', function($routes) {
    $routes->get('produk', 'Api::getProduk');
    $routes->get('produk/(:num)', 'Api::getProdukById/$1');
    $routes->get('kategori', 'Api::getKategori');
    // Tambahkan route API lainnya sesuai kebutuhan
});