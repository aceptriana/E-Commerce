<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= base_url() ?>">Home</a></li>
                    <li><a href="<?= base_url('produk') ?>">Produk</a></li>
                    <li>Hasil Pencarian</li>
                </ul>
            </div>
            <h1>Hasil Pencarian: "<?= esc($keyword) ?>"</h1>
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
                </div>
            </aside>
            <!-- /aside -->

            <div class="col-lg-9">
                <div class="row">
                    <?php if (!empty($produk)): ?>
                        <?php foreach($produk as $item): ?>
                        <div class="col-6 col-md-4">
                            <div class="grid_item">
                                <figure>
                                    <a href="<?= base_url('produk/'.$item['id']) ?>">
                                        <img class="img-fluid lazy" src="<?= base_url($item['foto']) ?>" alt="<?= esc($item['nama_produk']) ?>">
                                    </a>
                                </figure>
                                <div class="rating">
                                    <i class="icon-star voted"></i>
                                    <i class="icon-star voted"></i>
                                    <i class="icon-star voted"></i>
                                    <i class="icon-star voted"></i>
                                    <i class="icon-star"></i>
                                </div>
                                <a href="<?= base_url('produk/'.$item['id']) ?>">
                                    <h3><?= esc($item['nama_produk']) ?></h3>
                                </a>
                                <div class="price_box">
                                    <span class="new_price">Rp <?= number_format($item['harga'], 0, ',', '.') ?></span>
                                </div>
                                <ul>
                                    <li><a href="<?= base_url('favorit/tambah/'.$item['id']) ?>" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Tambah ke favorit"><i class="ti-heart"></i><span>Tambah ke favorit</span></a></li>
                                    <li><a href="<?= base_url('keranjang/tambah/'.$item['id']) ?>" class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Tambah ke keranjang"><i class="ti-shopping-cart"></i><span>Tambah ke keranjang</span></a></li>
                                </ul>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                Tidak ditemukan produk yang sesuai dengan pencarian "<?= esc($keyword) ?>".
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?= view('layouts/home/footer'); ?> 