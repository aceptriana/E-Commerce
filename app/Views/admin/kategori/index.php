<?= view('layouts/admin/header'); ?>
<?= view('layouts/admin/sidebar'); ?>
<?= view('layouts/admin/topbar'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen Kategori</h1>
    <a href="<?= base_url('admin/kategori/create'); ?>" class="btn btn-primary mb-3">Tambah Kategori</a>
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
    <?php endif; ?>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Kategori</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Foto Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php $no = 1; foreach ($kategori as $k) : ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= esc($k['nama_kategori']); ?></td>
            <td>
                <?php if (!empty($k['foto_kategori']) && file_exists($k['foto_kategori'])) : ?>
                    <img src="<?= base_url($k['foto_kategori']); ?>" alt="Foto Kategori" width="80" height="80" style="object-fit: cover;">
                <?php else : ?>
                    <span class="text-muted">Tidak ada gambar</span>
                <?php endif; ?>
            </td>
            <td>
                <a href="<?= base_url('admin/kategori/edit/' . $k['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="<?= base_url('admin/kategori/delete/' . $k['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">Hapus</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($kategori)) : ?>
        <tr>
            <td colspan="4" class="text-center">Tidak ada data kategori</td>
        </tr>
    <?php endif; ?>
</tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<?= view('layouts/admin/footer'); ?>