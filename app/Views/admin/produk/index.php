<?= view('layouts/admin/header'); ?>
<?= view('layouts/admin/sidebar'); ?>
<?= view('layouts/admin/topbar'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen Produk</h1>
    <a href="<?= base_url('admin/produk/create'); ?>" class="btn btn-primary mb-3">Tambah Produk</a>
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
    <?php endif; ?>
    
    <!-- Search Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pencarian Produk</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= base_url('admin/produk'); ?>" class="form-inline">
                <div class="form-group mr-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari produk, kategori, atau deskripsi..." value="<?= esc($search ?? ''); ?>" style="width: 300px;">
                </div>
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-search"></i> Cari
                </button>
                <?php if (!empty($search)) : ?>
                    <a href="<?= base_url('admin/produk'); ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6>
            <div class="text-muted">
                Menampilkan <?= count($produk); ?> dari <?= $totalRecords; ?> produk
                <?php if (!empty($search)) : ?>
                    untuk pencarian "<?= esc($search); ?>"
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Pre-Order</th>
                            <th>Tanggal Rilis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = (($currentPage - 1) * 10) + 1; foreach ($produk as $p) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= esc($p['nama_produk']); ?></td>
                                <td><?= esc($p['nama_kategori']); ?></td>
                                <td>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></td>
                                <td><?= esc($p['stok']); ?></td>
                                <td>
                                    <?php if ($p['is_preorder']) : ?>
                                        <span class="badge badge-info">Ya</span>
                                    <?php else : ?>
                                        <span class="badge badge-secondary">Tidak</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= $p['tanggal_rilis'] ? date('d-m-Y', strtotime($p['tanggal_rilis'])) : '-'; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/produk/detail/' . $p['id']); ?>" class="btn btn-info btn-sm">Detail</a>
                                    <a href="<?= base_url('admin/produk/edit/' . $p['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="<?= base_url('admin/produk/delete/' . $p['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($produk)) : ?>
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data produk</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1) : ?>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Halaman <?= $currentPage; ?> dari <?= $totalPages; ?>
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            <!-- Previous Page -->
                            <?php if ($currentPage > 1) : ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= base_url('admin/produk?' . http_build_query(array_merge($_GET, ['page' => $currentPage - 1]))); ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php else : ?>
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                </li>
                            <?php endif; ?>
                            
                            <!-- Page Numbers -->
                            <?php
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);
                            
                            if ($startPage > 1) : ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= base_url('admin/produk?' . http_build_query(array_merge($_GET, ['page' => 1]))); ?>">1</a>
                                </li>
                                <?php if ($startPage > 2) : ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php for ($i = $startPage; $i <= $endPage; $i++) : ?>
                                <li class="page-item <?= $i == $currentPage ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?= base_url('admin/produk?' . http_build_query(array_merge($_GET, ['page' => $i]))); ?>">
                                        <?= $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($endPage < $totalPages) : ?>
                                <?php if ($endPage < $totalPages - 1) : ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= base_url('admin/produk?' . http_build_query(array_merge($_GET, ['page' => $totalPages]))); ?>">
                                        <?= $totalPages; ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <!-- Next Page -->
                            <?php if ($currentPage < $totalPages) : ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= base_url('admin/produk?' . http_build_query(array_merge($_GET, ['page' => $currentPage + 1]))); ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php else : ?>
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= view('layouts/admin/footer'); ?>