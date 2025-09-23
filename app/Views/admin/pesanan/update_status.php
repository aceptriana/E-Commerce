<?= view('layouts/admin/header'); ?>
<?= view('layouts/admin/sidebar'); ?>
<?= view('layouts/admin/topbar'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Update Status Pesanan #<?= str_pad($pesanan['id'], 5, '0', STR_PAD_LEFT); ?></h1>
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Update Status</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/pesanan/update-status/' . $pesanan['id']); ?>" method="post">
                        <div class="form-group">
                            <label for="status">Status Pesanan</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="menunggu_pembayaran" <?= $pesanan['status'] == 'menunggu_pembayaran' ? 'selected' : ''; ?>>Menunggu Pembayaran</option>
                                <option value="diproses" <?= $pesanan['status'] == 'diproses' ? 'selected' : ''; ?>>Diproses</option>
                                <option value="dikirim" <?= $pesanan['status'] == 'dikirim' ? 'selected' : ''; ?>>Dikirim</option>
                                <option value="selesai" <?= $pesanan['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                                <option value="dibatalkan" <?= $pesanan['status'] == 'dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                            </select>
                        </div>
                        
                        <hr>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Status</button>
                            <a href="<?= base_url('admin/pesanan/detail/' . $pesanan['id']); ?>" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Alur Status Pesanan</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5>Panduan Update Status:</h5>
                        <ol>
                            <li><strong>Menunggu Pembayaran</strong> - Status awal ketika pesanan dibuat</li>
                            <li><strong>Diproses</strong> - Setelah pembayaran berhasil dan pesanan sedang diproses</li>
                            <li><strong>Dikirim</strong> - Pesanan telah dikirim ke alamat pelanggan</li>
                            <li><strong>Selesai</strong> - Pesanan telah diterima oleh pelanggan</li>
                            <li><strong>Dibatalkan</strong> - Pesanan dibatalkan</li>
                        </ol>
                        <p><strong>Catatan:</strong> Update status akan otomatis mengubah status pengiriman jika diperlukan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('layouts/admin/footer'); ?>