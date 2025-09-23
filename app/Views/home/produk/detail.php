<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= base_url() ?>">Home</a></li>
                    <li><a href="<?= base_url('produk') ?>">Produk</a></li>
                    <li><?= $produk['nama_produk'] ?></li>
                </ul>
            </div>
            <h1><?= $produk['nama_produk'] ?></h1>
        </div>
        <!-- /page_header -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="owl-carousel owl-theme prod_pics magnific-gallery">
                    <?php foreach ($fotos as $foto): ?>
                    <div class="item">
                        <a href="<?= base_url($foto['url_foto']) ?>" title="<?= $produk['nama_produk'] ?>" data-effect="mfp-zoom-in">
                            <img src="<?= base_url($foto['url_foto']) ?>" alt="<?= $produk['nama_produk'] ?>">
                        </a>
                    </div>
                    <?php endforeach; ?>
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
                        <div class="btn_add_to_cart">
                            <?php if(session()->get('logged_in')): ?>
                                <form action="<?= base_url('cart/add') ?>" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $produk['id'] ?>">
                                    <input type="hidden" name="quantity" id="quantity" value="1">
                                    <button type="submit" class="btn_1">Tambah ke Keranjang</button>
                                </form>
                            <?php else: ?>
                                <a href="<?= base_url('auth') ?>" class="btn_1">Login untuk Menambah ke Keranjang</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="prod_options version_2">
                        <?php if(isset($produk['variasi']) && !empty($produk['variasi'])): ?>
                        <div class="row">
                            <label class="col-xl-7 col-lg-5 col-md-6 col-6"><strong>Variasi</strong></label>
                            <div class="col-xl-5 col-lg-5 col-md-6 col-6">
                                <div class="custom-select-form">
                                    <select class="wide" name="variasi" id="variasi">
                                        <?php foreach($produk['variasi'] as $var): ?>
                                        <option value="<?= $var['id'] ?>"><?= $var['nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="row">
                            <label class="col-xl-7 col-lg-5 col-md-6 col-6"><strong>Quantity</strong></label>
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
                                    <?php if(isset($produk['harga_diskon']) && $produk['harga_diskon'] > 0): ?>
                                    <span class="percentage">-<?= round((($produk['harga'] - $produk['harga_diskon']) / $produk['harga']) * 100) ?>%</span>
                                    <span class="old_price">Rp <?= number_format($produk['harga_diskon'], 0, ',', '.') ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-6">
                                <div class="btn_add_to_cart">
                                    <?php if(session()->get('logged_in')): ?>
                                        <form action="<?= base_url('cart/add') ?>" method="POST">
                                            <input type="hidden" name="product_id" value="<?= $produk['id'] ?>">
                                            <input type="hidden" name="quantity" id="quantity" value="1">
                                            <button type="submit" class="btn_1">Tambah ke Keranjang</button>
                                        </form>
                                    <?php else: ?>
                                        <a href="<?= base_url('auth') ?>" class="btn_1">Login untuk Menambah ke Keranjang</a>
                                    <?php endif; ?>
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

    <div class="container margin_60_35">
        <div class="row">
            <div class="col-lg-8">
                <div class="prod_info">
                    <span class="rating mb-3">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="icon-star <?= ($i <= (isset($produk['rating']) ? $produk['rating'] : 0)) ? 'voted' : '' ?>"></i>
                        <?php endfor; ?>
                        <em><?= count($produk['ulasan'] ?? []) ?> reviews</em>
                    </span>
                    <p>
                        <small>SKU: <?= $produk['sku'] ?? 'N/A' ?></small><br>
                        <?= $produk['deskripsi_singkat'] ?? 'Tidak ada deskripsi singkat' ?>
                    </p>
                    <p><?= $produk['deskripsi'] ?? 'Tidak ada deskripsi' ?></p>
                </div>
            </div>
        </div>
    </div>

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
                                    <span class="rating mb-3">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="icon-star <?= ($i <= (isset($produk['rating']) ? $produk['rating'] : 0)) ? 'voted' : '' ?>"></i>
                                        <?php endfor; ?>
                                        <em><?= count($produk['ulasan'] ?? []) ?> reviews</em>
                                    </span>
                                    <p>
                                        <small>SKU: <?= $produk['sku'] ?? 'N/A' ?></small><br>
                                        <?= $produk['deskripsi_singkat'] ?? 'Tidak ada deskripsi singkat' ?>
                                    </p>
                                    <p><?= $produk['deskripsi'] ?? 'Tidak ada deskripsi' ?></p>
                                </div>
                                <div class="col-lg-5">
                                    <h3>Spesifikasi</h3>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped">
                                            <tbody>
                                                <?php if(isset($produk['spesifikasi']) && is_array($produk['spesifikasi'])): ?>
                                                    <?php foreach($produk['spesifikasi'] as $key => $value): ?>
                                                    <tr>
                                                        <td><strong><?= $key ?></strong></td>
                                                        <td><?= $value ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
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
                            <?php if(isset($produk['ulasan']) && !empty($produk['ulasan'])): ?>
                                <?php foreach($produk['ulasan'] as $ulasan): ?>
                                <div class="row justify-content-between">
                                    <div class="col-lg-5">
                                        <div class="review_content">
                                            <div class="clearfix add_bottom_10">
                                                <span class="rating">
                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                        <i class="icon-star <?= ($i <= $ulasan['rating']) ? 'voted' : '' ?>"></i>
                                                    <?php endfor; ?>
                                                    <em><?= $ulasan['rating'] ?>/5.0</em>
                                                </span>
                                                <em>Published <?= date('d M Y', strtotime($ulasan['created_at'])) ?></em>
                                            </div>
                                            <h4>"<?= $ulasan['judul'] ?>"</h4>
                                            <p><?= $ulasan['isi'] ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-center">Belum ada ulasan untuk produk ini.</p>
                            <?php endif; ?>
                            
                            <p class="text-end">
                                <a href="<?= base_url('ulasan/tambah/'.$produk['id']) ?>" class="btn_1">Tulis Ulasan</a>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- /tab B -->
            </div>
            <!-- /tab-content -->
        </div>
        <!-- /container -->
    </div>
    <!-- /tab_content_wrapper -->

    <?php if(!empty($relatedProducts)): ?>
    <div class="bg_white">
        <div class="container margin_60_35">
            <div class="main_title">
                <h2>Produk Terkait</h2>
                <span>Produk</span>
                <p>Produk lain dalam kategori yang sama</p>
            </div>
            <div class="owl-carousel owl-theme products_carousel">
                <?php foreach($relatedProducts as $related): ?>
                <div class="item">
                    <div class="grid_item">
                        <?php if($related['is_preorder']): ?>
                        <span class="ribbon off">Pre Order</span>
                        <?php endif; ?>
                        <figure>
                            <a href="<?= base_url('produk/detail/'.$related['id']) ?>">
                                <img class="owl-lazy" 
                                     src="img/products/product_placeholder_square_medium.jpg" 
                                     data-src="<?= base_url($related['foto'] ?? 'img/products/product_placeholder_square_medium.jpg') ?>" 
                                     alt="<?= $related['nama_produk'] ?>">
                            </a>
                        </figure>
                        <div class="rating">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="icon-star <?= ($i <= ($related['rating'] ?? 0)) ? 'voted' : '' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <a href="<?= base_url('produk/detail/'.$related['id']) ?>">
                            <h3><?= $related['nama_produk'] ?></h3>
                        </a>
                        <div class="price_box">
                            <span class="new_price">Rp <?= number_format($related['harga'], 0, ',', '.') ?></span>
                            <?php if(isset($related['harga_diskon']) && $related['harga_diskon'] > 0): ?>
                            <span class="old_price">Rp <?= number_format($related['harga_diskon'], 0, ',', '.') ?></span>
                            <?php endif; ?>
                        </div>
                        <ul>
                            <li><a href="<?= base_url('favorit/tambah/'.$related['id']) ?>" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Tambah ke favorit"><i class="ti-heart"></i><span>Tambah ke favorit</span></a></li>
                            <li><a href="<?= base_url('bandingkan/tambah/'.$related['id']) ?>" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Bandingkan"><i class="ti-control-shuffle"></i><span>Bandingkan</span></a></li>
                            <li><a href="<?= base_url('keranjang/tambah/'.$related['id']) ?>" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Tambah ke keranjang"><i class="ti-shopping-cart"></i><span>Tambah ke keranjang</span></a></li>
                        </ul>
                    </div>
                    <!-- /grid_item -->
                </div>
                <!-- /item -->
                <?php endforeach; ?>
            </div>
            <!-- /products_carousel -->
        </div>
        <!-- /container -->
    </div>
    <!-- /bg_white -->
    <?php endif; ?>

</main>
<!-- /main -->

<?= view('layouts/home/footer'); ?>


<?= $this->section('js'); ?>
<script>
// Update variasi_id when selection changes
document.getElementById('variasi')?.addEventListener('change', function() {
    document.getElementById('variasi_id').value = this.value;
});

// Initialize variasi_id if exists
if (document.getElementById('variasi')) {
    document.getElementById('variasi_id').value = document.getElementById('variasi').value;
}
</script>
<?= $this->endSection(); ?>
