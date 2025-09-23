<?= view('layouts/admin/header'); ?>
<?= view('layouts/admin/sidebar'); ?>
<?= view('layouts/admin/topbar'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Detail Pesanan #<?= str_pad($pesanan['id'], 5, '0', STR_PAD_LEFT); ?></h1>
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Informasi Pesanan -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pesanan</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID Pesanan:</strong> #<?= str_pad($pesanan['id'], 5, '0', STR_PAD_LEFT); ?></p>
                            <p><strong>Tanggal Order:</strong> <?= date('d-m-Y H:i', strtotime($pesanan['tanggal_pesanan'])); ?></p>
                            <p><strong>Status:</strong> 
                                <?php
                                $statusBadge = 'secondary';
                                switch ($pesanan['status']) {
                                    case 'menunggu_pembayaran':
                                        $statusBadge = 'warning';
                                        $statusText = 'Menunggu Pembayaran';
                                        break;
                                    case 'diproses':
                                        $statusBadge = 'info';
                                        $statusText = 'Diproses';
                                        break;
                                    case 'dikirim':
                                        $statusBadge = 'primary';
                                        $statusText = 'Dikirim';
                                        break;
                                    case 'selesai':
                                        $statusBadge = 'success';
                                        $statusText = 'Selesai';
                                        break;
                                    case 'dibatalkan':
                                        $statusBadge = 'danger';
                                        $statusText = 'Dibatalkan';
                                        break;
                                    default:
                                        $statusText = 'Unknown';
                                }
                                ?>
                                <span class="badge badge-<?= $statusBadge; ?>"><?= $statusText; ?></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total:</strong> Rp <?= number_format($pesanan['total'], 0, ',', '.'); ?></p>
                            <!-- Removed Pre-Order display as it is no longer needed -->
                            <!-- Removed Estimasi Pengiriman related to is_preorder -->
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Update Status</h5>
                            <a href="<?= base_url('admin/pesanan/update-status/' . $pesanan['id']); ?>" class="btn btn-warning btn-sm">Update Status</a>
                        </div>
                        <div class="col-md-6">
                            <?php if ($pesanan['status'] == 'dikirim' || $pesanan['status'] == 'diproses') : ?>
                                <h5>Nomor Resi</h5>
                                <form action="<?= base_url('admin/pesanan/update-resi/' . $pesanan['id']); ?>" method="post">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="no_resi" value="<?= $pesanan['no_resi'] ?? '' ?>" placeholder="Input nomor resi">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">Update</button>
                                        </div>
                                    </div>
                                </form>
                            <?php elseif ($pesanan['no_resi']) : ?>
                                <h5>Nomor Resi</h5>
                                <p><?= $pesanan['no_resi']; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informasi Pelanggan -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pelanggan</h6>
                </div>
                <div class="card-body">
                    <p><strong>Nama:</strong> <?= esc($pesanan['nama_lengkap']); ?></p>
                    <p><strong>Email:</strong> <?= esc($pesanan['email']); ?></p>
                    <p><strong>No. Telepon:</strong> <?= esc($pesanan['no_telepon']); ?></p>
                    <p><strong>Alamat Pengiriman:</strong> <?= esc($pesanan['alamat_pengiriman']); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Detail Pesanan -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Produk</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Opsi</th>
                                    <!-- Removed Pre-Order column header -->
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $subtotal = 0; foreach ($detailPesanan as $detail) : ?>
                                    <tr>
                                        <td><?= esc($detail['nama_produk']); ?></td>
                                        <td>Rp <?= number_format($detail['harga_satuan'], 0, ',', '.'); ?></td>
                                        <td><?= $detail['jumlah']; ?></td>
                                        <td>
                                            <?php 
                                            $options = [];
                                            if (!empty($detail['ukuran'])) $options[] = 'Ukuran: ' . $detail['ukuran'];
                                            if (!empty($detail['warna'])) $options[] = 'Warna: ' . $detail['warna'];
                                            if (!empty($detail['bahan'])) $options[] = 'Bahan: ' . $detail['bahan'];
                                            if (!empty($detail['finishing'])) $options[] = 'Finishing: ' . $detail['finishing'];
                                            echo implode('<br>', $options);
                                            ?>
                                        </td>
                                        <!-- Removed is_preorder badge display -->
                                        <td>Rp <?= number_format($detail['harga_satuan'] * $detail['jumlah'], 0, ',', '.'); ?></td>
                                    </tr>
                                <?php $subtotal += ($detail['harga_satuan'] * $detail['jumlah']); endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Subtotal:</th>
                                    <th>Rp <?= number_format($subtotal, 0, ',', '.'); ?></th>
                                </tr>
                                <?php if (isset($pengiriman) && $pengiriman) : ?>
                                <tr>
                                    <th colspan="5" class="text-right">Biaya Pengiriman:</th>
                                    <th>Rp <?= number_format($pengiriman['biaya_pengiriman'], 0, ',', '.'); ?></th>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th colspan="5" class="text-right">Total:</th>
                                    <th>Rp <?= number_format($pesanan['total'], 0, ',', '.'); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informasi Pembayaran & Pengiriman -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pembayaran</h6>
                </div>
                <div class="card-body">
                    <p><strong>Status:</strong> 
                        <?php
                        $paymentBadge = 'secondary';
                        switch ($pesanan['status_pembayaran'] ?? 'pending') {
                            case 'pending':
                                $paymentBadge = 'warning';
                                $paymentText = 'Pending';
                                break;
                            case 'berhasil':
                                $paymentBadge = 'success';
                                $paymentText = 'Berhasil';
                                break;
                            case 'gagal':
                                $paymentBadge = 'danger';
                                $paymentText = 'Gagal';
                                break;
                            default:
                                $paymentText = 'Unknown';
                        }
                        ?>
                        <span class="badge badge-<?= $paymentBadge; ?>"><?= $paymentText; ?></span>
                    </p>
                    <p><strong>Metode Pembayaran:</strong> <?= $pesanan['metode_pembayaran'] ?? '-'; ?></p>
                    <p><strong>Total Bayar:</strong> Rp <?= number_format($pesanan['total_bayar'] ?? 0, 0, ',', '.'); ?></p>
                    <p><strong>Waktu Pembayaran:</strong> <?= $pesanan['waktu_bayar'] ? date('d-m-Y H:i', strtotime($pesanan['waktu_bayar'])) : '-'; ?></p>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pengiriman</h6>
                </div>
                <div class="card-body">
                    <?php if (isset($pengiriman) && $pengiriman) : ?>
                        <p><strong>Jasa Pengiriman:</strong> <?= esc($pengiriman['jasa_pengiriman']); ?></p>
                        <p><strong>Biaya Pengiriman:</strong> Rp <?= number_format($pengiriman['biaya_pengiriman'], 0, ',', '.'); ?></p>
                        <p><strong>Estimasi:</strong> <?= esc($pengiriman['estimasi_pengiriman']); ?></p>
                        <p><strong>Status Pengiriman:</strong> 
                            <?php
                            $shipBadge = 'secondary';
                            switch ($pengiriman['status_pengiriman']) {
                                case 'belum_dikirim':
                                    $shipBadge = 'warning';
                                    $shipText = 'Belum Dikirim';
                                    break;
                                case 'dalam_perjalanan':
                                    $shipBadge = 'info';
                                    $shipText = 'Dalam Perjalanan';
                                    break;
                                case 'sampai':
                                    $shipBadge = 'success';
                                    $shipText = 'Sampai';
                                    break;
                                default:
                                    $shipText = 'Unknown';
                            }
                            ?>
                            <span class="badge badge-<?= $shipBadge; ?>"><?= $shipText; ?></span>
                        </p>
                        <p><strong>Nomor Resi:</strong> <?= $pesanan['no_resi'] ?? '-'; ?></p>
                    <?php else : ?>
                        <p class="text-center">Informasi pengiriman belum tersedia</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mb-4">
        <a href="<?= base_url('admin/pesanan'); ?>" class="btn btn-secondary">Kembali ke Daftar Pesanan</a>
    </div>
</div>

<?= view('layouts/admin/footer'); ?>