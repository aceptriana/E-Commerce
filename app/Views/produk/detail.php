<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= base_url('/'); ?>">Home</a></li>
                    <li><a href="<?= base_url('kategori/' . $kategori['id']); ?>"><?= $kategori['nama_kategori']; ?></a></li>
                    <li><?= $produk['nama_produk']; ?></li>
                </ul>
            </div>
            <h1><?= $produk['nama_produk']; ?></h1>
        </div>
        <!-- /page_header -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="owl-carousel owl-theme prod_pics magnific-gallery">
                    <?php if (!empty($foto_produk)): ?>
                        <?php foreach($foto_produk as $foto): ?>
                            <div class="item">
                                <a href="<?= base_url($foto['url_foto']); ?>" title="<?= $produk['nama_produk']; ?>" data-effect="mfp-zoom-in">
                                    <img src="<?= base_url($foto['url_foto']); ?>" alt="<?= $produk['nama_produk']; ?>">
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="item">
                            <a href="<?= base_url('img/products/product_placeholder_detail_1.jpg'); ?>" title="<?= $produk['nama_produk']; ?>" data-effect="mfp-zoom-in">
                                <img src="<?= base_url('img/products/product_placeholder_detail_1.jpg'); ?>" alt="<?= $produk['nama_produk']; ?>">
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- /carousel -->
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
    
    <div class="bg_white">
        <div class="container margin_60_35">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <div class="prod_info version_2">
                        <span class="rating">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php if($i <= round($rating)): ?>
                                    <i class="icon-star voted"></i>
                                <?php else: ?>
                                    <i class="icon-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <em><?= count($ulasan); ?> ulasan</em>
                        </span>
                        <p><small>SKU: PDK-<?= sprintf('%03d', $produk['id']); ?></small><br><?= $produk['deskripsi']; ?></p>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="prod_options version_2">
                        <?php if($produk['is_preorder']): ?>
                            <div class="alert alert-info">
                                Produk ini adalah Pre-Order. Tanggal Rilis: <?= date('d F Y', strtotime($produk['tanggal_rilis'])); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="row">
                            <label class="col-xl-7 col-lg-5 col-md-6 col-6"><strong>Ukuran</strong></label>
                            <div class="col-xl-5 col-lg-5 col-md-6 col-6">
                                <div class="custom-select-form">
                                    <select class="wide" id="size">
                                        <option value="S" selected>Small (S)</option>
                                        <option value="M">Medium (M)</option>
                                        <option value="L">Large (L)</option>
                                        <option value="XL">Extra Large (XL)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xl-7 col-lg-5 col-md-6 col-6"><strong>Warna</strong></label>
                            <div class="col-xl-5 col-lg-5 col-md-6 col-6 colors">
                                <ul>
                                    <li><a href="#0" class="color color_1 active"></a></li>
                                    <li><a href="#0" class="color color_2"></a></li>
                                    <li><a href="#0" class="color color_3"></a></li>
                                    <li><a href="#0" class="color color_4"></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xl-7 col-lg-5  col-md-6 col-6"><strong>Jumlah</strong></label>
                            <div class="col-xl-5 col-lg-5 col-md-6 col-6">
                                <div class="numbers-row">
                                    <input type="text" value="1" id="quantity" class="qty2" name="quantity">
                                    <div class="inc button_inc">+</div>
                                    <div class="dec button_inc">-</div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-7 col-md-6">
                                <div class="price_main">
                                    <span class="new_price">Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></span>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-6">
                                <div class="btn_add_to_cart">
                                    <a href="#0" class="btn_1" onclick="addToCart(<?= $produk['id']; ?>)">Tambah ke Keranjang</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /row -->
        </div>
    </div>
    <!-- /bg_white -->

    <div class="tabs_product bg_white version_2">
        <div class="container">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a id="tab-A" href="#pane-A" class="nav-link active" data-bs-toggle="tab" role="tab">Deskripsi</a>
                </li>
                <li class="nav-item">
                    <a id="tab-B" href="#pane-B" class="nav-link" data-bs-toggle="tab" role="tab">Ulasan</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- /tabs_product -->

    <div class="tab_content_wrapper">
        <div class="container">
            <div class="tab-content" role="tablist">
                <div id="pane-A" class="card tab-pane fade active show" role="tabpanel" aria-labelledby="tab-A">
                    <div class="card-header" role="tab" id="heading-A">
                        <h5 class="mb-0">
                            <a class="collapsed" data-bs-toggle="collapse" href="#collapse-A" aria-expanded="false" aria-controls="collapse-A">
                                Deskripsi
                            </a>
                        </h5>
                    </div>

                    <div id="collapse-A" class="collapse" role="tabpanel" aria-labelledby="heading-A">
                        <div class="card-body">
                            <div class="row justify-content-between">
                                <div class="col-lg-6">
                                    <h3>Detail</h3>
                                    <?= $produk['deskripsi']; ?>
                                </div>
                                <div class="col-lg-5">
                                    <h3>Spesifikasi</h3>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Stok</strong></td>
                                                    <td><?= $produk['stok']; ?> tersisa</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Kategori</strong></td>
                                                    <td><?= $kategori['nama_kategori']; ?></td>
                                                </tr>
                                                <?php if($produk['is_preorder']): ?>
                                                <tr>
                                                    <td><strong>Pre-Order</strong></td>
                                                    <td>Ya</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Rilis</strong></td>
                                                    <td><?= date('d F Y', strtotime($produk['tanggal_rilis'])); ?></td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /table-responsive -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /TAB A -->
                <div id="pane-B" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-B">
                    <div class="card-header" role="tab" id="heading-B">
                        <h5 class="mb-0">
                            <a class="collapsed" data-bs-toggle="collapse" href="#collapse-B" aria-expanded="false" aria-controls="collapse-B">
                                Ulasan
                            </a>
                        </h5>
                    </div>
                    <div id="collapse-B" class="collapse" role="tabpanel" aria-labelledby="heading-B">
                        <div class="card-body">
                            <?php if (!empty($ulasan)): ?>
                                <?php $i = 0; ?>
                                <?php foreach(array_chunk($ulasan, 2) as $ulasan_row): ?>
                                    <div class="row justify-content-between">
                                        <?php foreach($ulasan_row as $review): ?>
                                            <div class="col-lg-5">
                                                <div class="review_content">
                                                    <div class="clearfix add_bottom_10">
                                                        <span class="rating">
                                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                                <?php if($i <= $review['rating']): ?>
                                                                    <i class="icon-star voted"></i>
                                                                <?php else: ?>
                                                                    <i class="icon-star"></i>
                                                                <?php endif; ?>
                                                            <?php endfor; ?>
                                                            <em><?= $review['rating']; ?>/5.0</em>
                                                        </span>
                                                        <em><?= date('d M Y', strtotime($review['tanggal'])); ?></em>
                                                    </div>
                                                    <h4>"<?= htmlspecialchars(substr($review['komentar'], 0, 30)); ?>"</h4>
                                                    <p><?= htmlspecialchars($review['komentar']); ?></p>
                                                    <p><small>Oleh: <?= $review['nama_lengkap']; ?></small></p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="row">
                                    <div class="col-12">
                                        <p>Belum ada ulasan untuk produk ini.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(session()->get('logged_in')): ?>
                                <p class="text-end">
                                    <a href="<?= base_url('ulasan/tulis/' . $produk['id']); ?>" class="btn_1">Tulis Ulasan</a>
                                </p>
                            <?php else: ?>
                                <p class="text-end">
                                    <a href="<?= base_url('login?redirect=produk/detail/' . $produk['id']); ?>" class="btn_1">Login untuk Menulis Ulasan</a>
                                </p>
                            <?php endif; ?>
                        </div>
                        <!-- /card-body -->
                    </div>
                </div>
                <!-- /tab B -->
            </div>
            <!-- /tab-content -->
        </div>
        <!-- /container -->
    </div>
    <!-- /tab_content_wrapper -->

    <div class="bg_white">
        <div class="container margin_60_35">
            <div class="main_title">
                <h2>Produk</h2>
                <span>Terkait</span>
                <p>Produk lain yang mungkin Anda sukai</p>
            </div>
            <div class="owl-carousel owl-theme products_carousel">
                <?php foreach($related_products as $related): ?>
                    <div class="item">
                        <div class="grid_item">
                            <?php if($related['is_preorder']): ?>
                                <span class="ribbon new">Pre-Order</span>
                            <?php endif; ?>
                            <figure>
                                <a href="<?= base_url('produk/detail/' . $related['id']); ?>">
                                    <?php 
                                    $related_photo = $this->fotoModel->where('produk_id', $related['id'])->orderBy('urutan', 'ASC')->first();
                                    $photo_url = $related_photo ? $related_photo['url_foto'] : 'img/products/product_placeholder_square_medium.jpg';
                                    ?>
                                    <img class="owl-lazy" src="<?= base_url('img/products/product_placeholder_square_medium.jpg'); ?>" data-src="<?= base_url($photo_url); ?>" alt="<?= $related['nama_produk']; ?>">
                                </a>
                            </figure>
                            <a href="<?= base_url('produk/detail/' . $related['id']); ?>">
                                <h3><?= $related['nama_produk']; ?></h3>
                            </a>
                            <div class="price_box">
                                <span class="new_price">Rp <?= number_format($related['harga'], 0, ',', '.'); ?></span>
                            </div>
                            <ul>
                                <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Tambah ke favorit"><i class="ti-heart"></i><span>Tambah ke favorit</span></a></li>
                                <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Bandingkan"><i class="ti-control-shuffle"></i><span>Bandingkan</span></a></li>
                                <li><a href="#0" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Tambah ke keranjang" onclick="addToCart(<?= $related['id']; ?>)"><i class="ti-shopping-cart"></i><span>Tambah ke keranjang</span></a></li>
                            </ul>
                        </div>
                        <!-- /grid_item -->
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- /products_carousel -->
        </div>
        <!-- /container -->
    </div>
    <!-- /bg_white -->
</main>
<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script>
function addToCart(product_id) {
    const quantity = document.getElementById('quantity').value;
    const size = document.getElementById('size').value;
    
    // Get selected color (if any)
    let color = '';
    const colorElements = document.querySelectorAll('.colors ul li a');
    for (let i = 0; i < colorElements.length; i++) {
        if (colorElements[i].classList.contains('active')) {
            color = colorElements[i].getAttribute('class').replace('color ', '').replace(' active', '');
            break;
        }
    }
    
    $.ajax({
        url: '<?= base_url('cart/add'); ?>',
        type: 'POST',
        data: {
            product_id: product_id,
            quantity: quantity,
            size: size,
            color: color
        },
        success: function(response) {
            const data = JSON.parse(response);
            if (data.status === 'success') {
                // Update cart icon count
                $('#cart-count').text(data.cart_count);
                
                // Show success message
                alert('Produk berhasil ditambahkan ke keranjang!');
            } else {
                alert(data.message);
            }
        },
        error: function() {
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    });
}

$(document).ready(function() {
    // Initialize owl carousel
    $('.owl-carousel.prod_pics').owlCarousel({
        items: 1,
        loop: false,
        margin: 0,
        dots: true,
        nav: false,
        lazyLoad: true
    });
    
    // Initialize owl carousel for related products
    $('.owl-carousel.products_carousel').owlCarousel({
        loop: false,
        margin: 10,
        nav: true,
        dots: false,
        responsive: {
            0: {
                items: 2
            },
            560: {
                items: 3
            },
            768: {
                items: 4
            },
            1024: {
                items: 5
            }
        }
    });
    
    // Handle color selection
    $('.colors a').on('click', function(e) {
        e.preventDefault();
        $('.colors a').removeClass('active');
        $(this).addClass('active');
    });
    
    // Initialize collapse for tabs
    $('#collapse-A').collapse('show');
});
</script>
<?= $this->endSection(); ?>
