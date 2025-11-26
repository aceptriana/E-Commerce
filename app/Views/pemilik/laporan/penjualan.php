<?= view('layouts/pemilik/header'); ?>
<?= view('layouts/pemilik/sidebar'); ?>
<?= view('layouts/pemilik/topbar'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Laporan Penjualan (Pemilik)</h1>
    
    <!-- Filter Periode Laporan -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Periode</h6>
        </div>
        <div class="card-body">
            <form action="<?= base_url('pemilik/laporan/penjualan'); ?>" method="get" class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Dari Tanggal</label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="<?= $tanggal_mulai ?? date('Y-m-01'); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Sampai Tanggal</label>
                        <input type="date" name="tanggal_akhir" class="form-control" value="<?= $tanggal_akhir ?? date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="row">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pendapatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_pendapatan ?? 0, 0, ',', '.'); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pesanan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_pesanan ?? 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Laporan Penjualan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Pre-Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; if (!empty($pesanan)): foreach ($pesanan as $p) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>#<?= str_pad($p['id'], 5, '0', STR_PAD_LEFT); ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($p['tanggal_pesanan'])); ?></td>
                                <td><?= esc($p['nama_lengkap']); ?></td>
                                <td>Rp <?= number_format($p['total'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php
                                    switch ($p['status']) {
                                        case 'menunggu_pembayaran':
                                            echo '<span class="badge badge-warning">Menunggu Pembayaran</span>';
                                            break;
                                        case 'diproses':
                                            echo '<span class="badge badge-info">Diproses</span>';
                                            break;
                                        case 'dikirim':
                                            echo '<span class="badge badge-primary">Dikirim</span>';
                                            break;
                                        case 'selesai':
                                            echo '<span class="badge badge-success">Selesai</span>';
                                            break;
                                        case 'dibatalkan':
                                            echo '<span class="badge badge-danger">Dibatalkan</span>';
                                            break;
                                        default:
                                            echo '<span class="badge badge-secondary">Unknown</span>';
                                    }
                                    ?>
                                </td>
                                <td><?= $p['is_preorder'] ? 'Ya' : 'Tidak'; ?></td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data pesanan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Produk Terlaris -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Produk Terlaris</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="bestProductsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Total Terjual</th>
                            <th>Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; if (!empty($produk_terlaris)): foreach ($produk_terlaris as $p) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= esc($p['nama_produk']); ?></td>
                                <td><?= esc($p['nama_kategori']); ?></td>
                                <td>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></td>
                                <td><?= $p['jumlah_terjual']; ?></td>
                                <td>Rp <?= number_format($p['total_pendapatan'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data produk terlaris</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
    $('#bestProductsTable').DataTable();
});
</script>

<?= view('layouts/pemilik/footer'); ?>
