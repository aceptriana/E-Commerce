<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= base_url() ?>">Home</a></li>
                    <li>Produk</li>
                </ul>
            </div>
            <h1>Semua Produk</h1>
        </div>
        <!-- /page_header -->
        <div class="row">
            <aside class="col-lg-3" id="sidebar_fixed">
                <div class="filter_col">
                    <div class="inner_bt"><a href="#" class="open_filters"><i class="ti-close"></i></a></div>
                    <div class="filter_type version_2">
                        <h4><a href="#filter_1" data-bs-toggle="collapse" class="opened">Kategori</a></h4>
                        <div class="collapse show" id="filter_1">
                            <ul>
                                <?php foreach($kategori as $kat): ?>
                                <li>
                                    <label class="container_check">
                                        <?= $kat['nama_kategori'] ?> 
                                        <small>(<?= $kat['jumlah_produk'] ?? 0 ?>)</small>
                                        <input type="checkbox" name="kategori[]" value="<?= $kat['id'] ?>">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <!-- /filter_type -->
                    <div class="filter_type version_2">
                        <h4><a href="#filter_4" data-bs-toggle="collapse" class="opened">Harga</a></h4>
                        <div class="collapse show" id="filter_4">
                            <ul>
                                <li>
                                    <label class="container_check">
                                        Termurah (Rp 0 — Rp 100.000)
                                        <small>(<?= $jumlah_termurah ?? 0 ?>)</small>
                                        <input type="checkbox" name="harga" value="termurah">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                                <li>
                                    <label class="container_check">
                                        Termahal (Rp 100.000+)
                                        <small>(<?= $jumlah_termahal ?? 0 ?>)</small>
                                        <input type="checkbox" name="harga" value="termahal">
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- /filter_type -->
                    <div class="buttons">
                        <button type="button" class="btn_1" id="apply_filters">Filter</button>
                        <button type="button" class="btn_1 gray" id="reset_filters">Reset</button>
                    </div>
                </div>
            </aside>
            <!-- /col -->
            <div class="col-lg-9">
                <div class="row small-gutters">
                    <?php if(!empty($produk)): ?>
                        <?php foreach($produk as $item): ?>
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="grid_item">
                                <?php if($item['is_preorder']): ?>
                                <span class="ribbon off">Pre Order</span>
                                <?php endif; ?>
                                <figure>
                                    <a href="<?= base_url('produk/detail/'.$item['id']) ?>">
                                        <img class="img-fluid lazy" 
                                             src="img/products/product_placeholder_square_medium.jpg" 
                                             data-src="<?= base_url($item['foto']) ?>" 
                                             alt="<?= $item['nama_produk'] ?>">
                                    </a>
                                </figure>
                                <div class="rating">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="icon-star <?= ($i <= ($item['rating'] ?? 0)) ? 'voted' : '' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <a href="<?= base_url('produk/detail/'.$item['id']) ?>">
                                    <h3><?= $item['nama_produk'] ?></h3>
                                </a>
                                <div class="price_box">
                                    <span class="new_price">Rp <?= number_format($item['harga'], 0, ',', '.') ?></span>
                                    <?php if(isset($item['harga_diskon']) && $item['harga_diskon'] > 0): ?>
                                    <span class="old_price">Rp <?= number_format($item['harga_diskon'], 0, ',', '.') ?></span>
                                    <?php endif; ?>
                                </div>
                                <ul>
                                    <li><a href="<?= base_url('favorit/tambah/'.$item['id']) ?>" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Tambah ke favorit"><i class="ti-heart"></i><span>Tambah ke favorit</span></a></li>
                                    <li><a href="<?= base_url('bandingkan/tambah/'.$item['id']) ?>" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Bandingkan"><i class="ti-control-shuffle"></i><span>Bandingkan</span></a></li>
                                    <li><a href="<?= base_url('keranjang/tambah/'.$item['id']) ?>" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Tambah ke keranjang"><i class="ti-shopping-cart"></i><span>Tambah ke keranjang</span></a></li>
                                </ul>
                            </div>
                            <!-- /grid_item -->
                        </div>
                        <!-- /col -->
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                Belum ada produk yang tersedia.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- /row -->
                <div class="pagination__wrapper">
                    <ul class="pagination">
                        <li><a href="#0" class="prev" title="previous page">&#10094;</a></li>
                        <li>
                            <a href="#0" class="active">1</a>
                        </li>
                        <li>
                            <a href="#0">2</a>
                        </li>
                        <li>
                            <a href="#0">3</a>
                        </li>
                        <li>
                            <a href="#0">4</a>
                        </li>
                        <li><a href="#0" class="next" title="next page">&#10095;</a></li>
                    </ul>
                </div>
            </div>
            <!-- /col -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Apply filters
    document.getElementById('apply_filters').addEventListener('click', function() {
        const selectedKategori = Array.from(document.querySelectorAll('input[name="kategori[]"]:checked')).map(cb => cb.value);
        const selectedHarga = document.querySelector('input[name="harga"]:checked')?.value;
        
        let url = '<?= base_url('produk') ?>';
        const params = new URLSearchParams();
        
        if (selectedKategori.length > 0) {
            params.append('kategori', selectedKategori.join(','));
        }
        if (selectedHarga) {
            params.append('harga', selectedHarga);
        }
        
        if (params.toString()) {
            url += '?' + params.toString();
        }
        
        window.location.href = url;
    });

    // Reset filters
    document.getElementById('reset_filters').addEventListener('click', function() {
        window.location.href = '<?= base_url('produk') ?>';
    });
});
</script>

<?= view('layouts/home/footer'); ?> 