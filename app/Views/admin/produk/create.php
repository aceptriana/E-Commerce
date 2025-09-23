<?= view('layouts/admin/header'); ?>
<?= view('layouts/admin/sidebar'); ?>
<?= view('layouts/admin/topbar'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tambah Produk Baru</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Produk</h6>
        </div>
        <div class="card-body">
            <?php if (session()->has('errors')) : ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach (session('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>
            
            <form action="<?= base_url('admin/produk/store'); ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?= old('nama_produk'); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select class="form-control" id="kategori_id" name="kategori_id" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($kategori as $k) : ?>
                                    <option value="<?= $k['id']; ?>" <?= old('kategori_id') == $k['id'] ? 'selected' : ''; ?>>
                                        <?= esc($k['nama_kategori']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="harga">Harga (Rp)</label>
                            <input type="number" class="form-control" id="harga" name="harga" value="<?= old('harga'); ?>" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" value="<?= old('stok'); ?>" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="berat">Berat (gram)</label>
                            <input type="number" class="form-control" id="berat" name="berat" value="<?= old('berat', 1000); ?>" min="1" required>
                            <small class="text-muted">Berat dalam gram (1 kg = 1000 gram)</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi Produk</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="6" required><?= old('deskripsi'); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_preorder" name="is_preorder" value="1" <?= old('is_preorder') ? 'checked' : ''; ?>>
                                <label class="custom-control-label" for="is_preorder">Produk Pre-Order</label>
                            </div>
                        </div>
                        
                        <div class="form-group" id="tanggal_rilis_container" <?= old('is_preorder') ? '' : 'style="display: none;"'; ?>>
                            <label for="tanggal_rilis">Tanggal Rilis</label>
                            <input type="date" class="form-control" id="tanggal_rilis" name="tanggal_rilis" value="<?= old('tanggal_rilis'); ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="foto_produk">Foto Produk (Bisa pilih lebih dari satu)</label>
                    <input type="file" class="form-control-file" id="foto_produk" name="foto_produk[]" accept="image/*" multiple required>
                    <small class="text-muted">Format: JPG, PNG, JPEG. Maks: 4MB per foto</small>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                    <a href="<?= base_url('admin/produk'); ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Tampilkan/sembunyikan tanggal rilis berdasarkan status pre-order
    $('#is_preorder').change(function() {
        if (this.checked) {
            $('#tanggal_rilis_container').show();
        } else {
            $('#tanggal_rilis_container').hide();
        }
    });
    
    // Inisialisasi editor teks untuk deskripsi
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('deskripsi');
    }
});
</script>

<?= view('layouts/admin/footer'); ?>