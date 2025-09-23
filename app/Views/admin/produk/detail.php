<?= view('layouts/admin/header'); ?>
<?= view('layouts/admin/sidebar'); ?>
<?= view('layouts/admin/topbar'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Detail Produk</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Produk</h6>
            <div>
                <a href="<?= base_url('admin/produk/edit/' . $produk['id']); ?>" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="<?= base_url('admin/produk'); ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Nama Produk</th>
                            <td width="70%"><?= esc($produk['nama_produk']); ?></td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td><?= esc($produk['nama_kategori']); ?></td>
                        </tr>
                        <tr>
                            <th>Harga</th>
                            <td>Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <th>Stok</th>
                            <td><?= esc($produk['stok']); ?></td>
                        </tr>
                        <tr>
                            <th>Pre-Order</th>
                            <td>
                                <?php if ($produk['is_preorder']) : ?>
                                    <span class="badge badge-info">Ya</span>
                                <?php else : ?>
                                    <span class="badge badge-secondary">Tidak</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if ($produk['is_preorder']) : ?>
                        <tr>
                            <th>Tanggal Rilis</th>
                            <td><?= date('d-m-Y', strtotime($produk['tanggal_rilis'])); ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Ditambahkan</th>
                            <td><?= date('d-m-Y H:i', strtotime($produk['dibuat_pada'])); ?></td>
                        </tr>
                        <tr>
                            <th>Terakhir Update</th>
                            <td><?= date('d-m-Y H:i', strtotime($produk['diperbarui_pada'])); ?></td>
                        </tr>
                    </table>
                </div>
                
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Deskripsi Produk</h5>
                    <div class="border p-3 bg-light">
                        <?= $produk['deskripsi']; ?>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="font-weight-bold">Foto Produk</h5>
                    <div class="row">
                        <?php if (!empty($foto_produk)) : ?>
                            <?php foreach ($foto_produk as $foto) : ?>
                                <div class="col-md-3 mb-3">
                                    <a href="<?= base_url($foto['url_foto']); ?>" target="_blank">
                                        <img src="<?= base_url($foto['url_foto']); ?>" class="img-thumbnail" alt="Foto Produk" style="height: 200px; width: 100%; object-fit: cover;">
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="col-12">
                                <p class="text-muted">Tidak ada foto produk</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('layouts/admin/footer'); ?>