<?= view('layouts/home/header'); ?>

<main>
<!-- Carousel Section -->
<div id="carousel-home">
        <div class="owl-carousel owl-theme">
            <div class="owl-slide cover" style="background-image: url('<?= base_url('img/slides/1.png') ?>');">
                <div class="opacity-mask d-flex align-items-center" data-opacity-mask="rgba(0, 0, 0, 0.5)">
                    <div class="container">
                        <div class="row justify-content-center justify-content-md-end">
                            <div class="col-lg-6 static">
                                <div class="slide-text text-end white">
                                    <h2 class="owl-slide-animated owl-slide-title">Pupuk Organik<br>Kualitas Terbaik</h2>
                                    <p class="owl-slide-animated owl-slide-subtitle">
                                        Produk unggulan untuk hasil panen maksimal
                                    </p>
                                    <div class="owl-slide-animated owl-slide-cta"><a class="btn_1" href="<?= base_url('produk/kategori/1') ?>" role="button">Belanja Sekarang</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/owl-slide-->
            <div class="owl-slide cover" style="background-image: url('<?= base_url('img/slides/2.png') ?>');">
                <div class="opacity-mask d-flex align-items-center" data-opacity-mask="rgba(0, 0, 0, 0.5)">
                    <div class="container">
                        <div class="row justify-content-center justify-content-md-start">
                            <div class="col-lg-6 static">
                                <div class="slide-text white">
                                    <h2 class="owl-slide-animated owl-slide-title">Bibit Unggul<br>Terbaru 2025</h2>
                                    <p class="owl-slide-animated owl-slide-subtitle">
                                        Tersedia berbagai varietas tanaman
                                    </p>
                                    <div class="owl-slide-animated owl-slide-cta"><a class="btn_1" href="<?= base_url('produk/kategori/2') ?>" role="button">Belanja Sekarang</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/owl-slide-->
            <div class="owl-slide cover" style="background-image: url('<?= base_url('img/slides/3.png') ?>');">
                <div class="opacity-mask d-flex align-items-center" data-opacity-mask="rgba(0, 0, 0, 0.5)">
                    <div class="container">
                        <div class="row justify-content-center justify-content-md-start">
                            <div class="col-lg-12 static">
                                <div class="slide-text text-center white">
                                    <h2 class="owl-slide-animated owl-slide-title">Alat Pertanian<br>Tersedia Sekarang</h2>
                                    <p class="owl-slide-animated owl-slide-subtitle">
                                        Lengkapi kebutuhan pertanian Anda dengan mudah
                                    </p>
                                    <div class="owl-slide-animated owl-slide-cta"><a class="btn_1" href="<?= base_url('produk/preorder') ?>" role="button">Belanja Sekarang</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/owl-slide-->
            </div>
        </div>
        <div id="icon_drag_mobile"></div>
    </div>
<!-- Category Banners -->
<ul id="banners_grid" class="clearfix">
    <?php 
    // Display all categories
    if (isset($kategori) && is_array($kategori) && count($kategori) > 0): 
        foreach($kategori as $kat): 
            // Cek apakah foto kategori ada
            $fotoKategori = !empty($kat['foto_kategori']) && file_exists($kat['foto_kategori']) 
                ? base_url($kat['foto_kategori']) 
                : base_url('img/banners_cat_placeholder.jpg'); // Placeholder jika foto tidak ada
    ?>
    <li>
        <a href="<?= base_url('produk/kategori/'.$kat['id']) ?>" class="img_container">
            <img src="<?= $fotoKategori ?>" alt="<?= $kat['nama_kategori'] ?>" class="lazy">
            <div class="short_info opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.5)">
                <h3><?= esc($kat['nama_kategori']) ?></h3>
                <div><span class="btn_1">Belanja Sekarang</span></div>
            </div>
        </a>
    </li>
    <?php 
        endforeach; 
    else:
        // Default banners if no categories found
        for ($i = 1; $i <= 3; $i++):
    ?>
    <li>
        <a href="<?= base_url('produk') ?>" class="img_container">
            <img src="img/banners_cat_placeholder.jpg" data-src="img/banner_<?= $i ?>.jpg" alt="Kategori" class="lazy">
            <div class="short_info opacity-mask" data-opacity-mask="rgba(0, 0, 0, 0.5)">
                <h3>Koleksi <?= $i ?></h3>
                <div><span class="btn_1">Belanja Sekarang</span></div>
            </div>
        </a>
    </li>
    <?php 
        endfor;
    endif; 
    ?>
</ul>
<!--/banners_grid -->
    <!-- Featured Products -->
    <div class="container margin_60_35">
        <div class="main_title">
            <h2>Produk Terbaru</h2>
            <p>Koleksi produk terbaru dari Mantra Jaya Tanil</p>
        </div>
        <div class="row small-gutters">
            <?php 
            // Check if produk exists and has data - display up to 8 products
            if (isset($produk) && is_array($produk) && count($produk) > 0): 
                foreach($produk as $item): 
            ?>
            <div class="col-6 col-md-3 col-xl-3">
                <div class="grid_item">
                    <figure>
                        <?php if(isset($item['is_preorder']) && $item['is_preorder']): ?>
                        <span class="ribbon off">Pre Order</span>
                        <?php endif; ?>
                        <a href="<?= base_url('produk/detail/'.$item['id']) ?>">
                            <?php 
                            // Access product images correctly based on database structure
                            if(isset($item['foto']) && is_array($item['foto']) && count($item['foto']) > 0): 
                                $photoCount = 0;
                                foreach($item['foto'] as $foto): 
                                    $photoCount++;
                            ?>
                                <img class="img-fluid lazy" 
                                     src="img/products/product_placeholder_square_medium.jpg" 
                                     data-src="<?= base_url($foto['url_foto']) ?>" 
                                     alt="<?= $item['nama_produk'] ?>">
                            <?php 
                                    if($photoCount >= 2) break;
                                endforeach; 
                            else: 
                            ?>
                                <!-- Default image if no photos -->
                                <img class="img-fluid lazy" 
                                     src="img/products/product_placeholder_square_medium.jpg" 
                                     data-src="img/products/product_placeholder_square_medium.jpg" 
                                     alt="<?= $item['nama_produk'] ?>">
                                     
                                <!-- Second default image (different view) -->
                                <img class="img-fluid lazy" 
                                     src="img/products/product_placeholder_square_medium.jpg" 
                                     data-src="img/products/product_placeholder_square_medium.jpg" 
                                     alt="<?= $item['nama_produk'] ?>">
                            <?php endif; ?>
                        </a>
                        <?php if(isset($item['is_preorder']) && $item['is_preorder'] && isset($item['tanggal_rilis']) && $item['tanggal_rilis']): ?>
                        <div data-countdown="<?= $item['tanggal_rilis'] ?>" class="countdown"></div>
                        <?php endif; ?>
                    </figure>
                    <div class="rating">
                        <i class="icon-star voted"></i>
                        <i class="icon-star voted"></i>
                        <i class="icon-star voted"></i>
                        <i class="icon-star voted"></i>
                        <i class="icon-star"></i>
                    </div>
                    <a href="<?= base_url('produk/detail/'.$item['id']) ?>">
                        <h3><?= $item['nama_produk'] ?></h3>
                    </a>
                    <div class="price_box">
                        <span class="new_price">Rp <?= number_format($item['harga'], 0, ',', '.') ?></span>
                    </div>
                </div>
                <!-- /grid_item -->
            </div>
            <!-- /col -->
            <?php 
                endforeach; 
            else:
                // Display placeholder products if no products found
                for ($i = 1; $i <= 8; $i++):
            ?>
            <div class="col-6 col-md-3 col-xl-3">
                <div class="grid_item">
                    <figure>
                        <a href="<?= base_url('produk') ?>">
                            <img class="img-fluid lazy" 
                                 src="img/products/product_placeholder_square_medium.jpg" 
                                 data-src="img/products/product_placeholder_square_medium.jpg" 
                                 alt="Produk <?= $i ?>">
                            <img class="img-fluid lazy" 
                                 src="img/products/product_placeholder_square_medium.jpg" 
                                 data-src="img/products/product_placeholder_square_medium.jpg" 
                                 alt="Produk <?= $i ?>">
                        </a>
                    </figure>
                    <div class="rating">
                        <i class="icon-star voted"></i>
                        <i class="icon-star voted"></i>
                        <i class="icon-star voted"></i>
                        <i class="icon-star voted"></i>
                        <i class="icon-star"></i>
                    </div>
                    <a href="<?= base_url('produk') ?>">
                        <h3>Produk <?= $i ?></h3>
                    </a>
                    <div class="price_box">
                        <span class="new_price">Rp 0</span>
                    </div>
                </div>
                <!-- /grid_item -->
            </div>
            <!-- /col -->
            <?php 
                endfor;
            endif; 
            ?>
        </div>
        <!-- /row -->
        <div class="text-center mt-3">
            <a href="<?= base_url('produk') ?>" class="btn_1 rounded">Lihat Semua Produk</a>
        </div>
    </div>
    <!-- /container -->

    

</main>
<!-- /main -->

<?= view('layouts/home/footer'); ?>