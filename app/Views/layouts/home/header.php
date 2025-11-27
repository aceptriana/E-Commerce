<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">

    <title>Mantra Jaya Tani</title>

    <!-- Favicons-->

    <!-- GOOGLE WEB FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- BASE CSS -->
    <link href="<?= base_url('css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">

    <!-- CSS SPESIFIK -->
    <link href="<?= base_url('css/home_1.css') ?>" rel="stylesheet">

    <!-- CSS KUSTOM -->
    <link href="<?= base_url('css/custom.css') ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
</head>

<body>

<div id="page">

    <header class="version_1">
        <div class="layer"></div><!-- Overlay menu mobile -->
        <div class="main_header">
            <div class="container">
                <div class="row small-gutters">
                    <div class="col-xl-3 col-lg-3 d-lg-flex align-items-center">
                        <div id="logo">
                            <a href="<?= base_url('/') ?>">
                                <h1 style="font-size: 24px; margin: 0; color: white;">Mantra Jaya Tani</h1>
                            </a>
                        </div>
                    </div>
                    <nav class="col-xl-6 col-lg-7">
                        <a class="open_close" href="javascript:void(0);">
                            <div class="hamburger hamburger--spin">
                                <div class="hamburger-box">
                                    <div class="hamburger-inner"></div>
                                </div>
                            </div>
                        </a>
                        <!-- Tombol menu mobile -->
                        <div class="main-menu">
                            <div id="header_menu">
                                <a href="<?= base_url('home/index') ?>">
                                    <h1 style="font-size: 24px; margin: 0; color: white;">Mantra Jaya Tani</h1>
                                </a>
                                <a href="#" class="open_close" id="close_in"><i class="ti-close"></i></a>
                            </div>
                            <ul>
                                <li><a href="<?= base_url('/') ?>">Beranda</a></li>
                                <li><a href="<?= base_url('produk') ?>">Produk</a></li>
                                <li class="submenu">
                                    <a href="javascript:void(0);" class="show-submenu">Kategori</a>
                                    <ul>
                                        <?php if(isset($header_kategori) && is_array($header_kategori)): ?>
                                            <?php foreach($header_kategori as $kat): ?>
                                                <li><a href="<?= base_url('produk/kategori/'.$kat['id']) ?>"><?= esc($kat['nama_kategori']) ?></a></li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li><a href="<?= base_url('produk') ?>">Semua Produk</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    <div class="col-xl-3 col-lg-2 d-lg-flex align-items-center justify-content-end text-end">
                        <a class="phone_top" href="https://wa.me/6285692831674" target="_blank">
                            <strong>
                                <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp Logo" style="width: 24px; height: 24px; margin-right: 8px;">
                                Butuh Bantuan? : +62 856-9283-1674
                            </strong>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main_nav Sticky">
            <div class="container">
                <div class="row small-gutters">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <nav class="categories">
                            <ul class="clearfix">
                                <!-- Kategori bisa ditambahkan di sini -->
                            </ul>
                        </nav>
                    </div>
                    <div class="col-xl-6 col-lg-7 col-md-6 d-none d-md-block">
                        <div class="custom-search-input">
                            <form action="<?= base_url('produk/search') ?>" method="get">
                                <input type="text" name="keyword" placeholder="Cari produk..." value="<?= isset($keyword) ? esc($keyword) : '' ?>">
                                <button type="submit"><i class="header-icon_search_custom"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-2 col-md-3">
                        <ul class="top_tools">
                            <li>
                                <div class="dropdown dropdown-cart">
                                    <a href="<?= base_url('cart') ?>" class="cart_bt">
                                        <?php if(isset($cart_count) && $cart_count > 0): ?>
                                            <strong><?= $cart_count ?></strong>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </li>
                         
                            <li>
                                <div class="dropdown dropdown-access">
                                    <a href="<?= base_url('#') ?>" class="access_link"><span>Akun</span></a>
                                    <?php if (session()->get('logged_in')): ?>
                                        <div class="dropdown-menu">
                                            <div class="text-center" style="padding: 10px; border-bottom: 1px solid #ededed;">
                                                <strong><?= esc(session()->get('nama')) ?></strong>
                                            </div>
                                                <ul>
                                                    <li><a href="<?= base_url('profile') ?>"><i class="ti-user"></i> Profil Saya</a></li>
                                                    <li><a href="<?= base_url('checkout/history') ?>"><i class="ti-package"></i> Pesanan Saya</a></li>
                                                    <li><a href="<?= base_url('returns') ?>"><i class="fas fa-undo me-1"></i> Return Saya</a></li>
                                                    <li><a href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
                                                </ul>
                                        </div>
                                    <?php else: ?>
                                        <div class="dropdown-menu">
                                            <a href="<?= base_url('auth') ?>" class="btn_1">Masuk / Daftar</a>
                                            <ul>
                                                <li><a href="<?= base_url('bantuan') ?>"><i class="ti-help-alt"></i>Bantuan & FAQ</a></li>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="btn_search_mob"><span>Cari</span></a>
                            </li>
                            <li>
                                <a href="#menu" class="btn_cat_mob">
                                    <div class="hamburger hamburger--spin" id="hamburger">
                                        <div class="hamburger-box">
                                            <div class="hamburger-inner"></div>
                                        </div>
                                    </div>
                                    Kategori
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="search_mob_wp">
                <form action="<?= base_url('produk/search') ?>" method="get">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari produk..." value="<?= isset($keyword) ? esc($keyword) : '' ?>">
                    <input type="submit" class="btn_1 full-width" value="Cari">
                </form>
            </div>
        </div>
    </header>
