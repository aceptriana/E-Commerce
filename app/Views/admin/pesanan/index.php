<?= view('layouts/admin/header'); ?>
<?= view('layouts/admin/sidebar'); ?>
<?= view('layouts/admin/topbar'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen Pesanan</h1>
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
    <?php endif; ?>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pesanan</h6>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="filterStatus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Filter Status
                </button>
                <div class="dropdown-menu" aria-labelledby="filterStatus">
                    <a class="dropdown-item <?= empty($selectedStatus) ? 'active' : '' ?>" href="<?= base_url('admin/pesanan') ?>">Semua</a>
                    <a class="dropdown-item <?= $selectedStatus == 'menunggu_pembayaran' ? 'active' : '' ?>" href="<?= base_url('admin/pesanan?status=menunggu_pembayaran') ?>">Menunggu Pembayaran</a>
                    <a class="dropdown-item <?= $selectedStatus == 'diproses' ? 'active' : '' ?>" href="<?= base_url('admin/pesanan?status=diproses') ?>">Diproses</a>
                    <a class="dropdown-item <?= $selectedStatus == 'dikirim' ? 'active' : '' ?>" href="<?= base_url('admin/pesanan?status=dikirim') ?>">Dikirim</a>
                    <a class="dropdown-item <?= $selectedStatus == 'selesai' ? 'active' : '' ?>" href="<?= base_url('admin/pesanan?status=selesai') ?>">Selesai</a>
                    <a class="dropdown-item <?= $selectedStatus == 'dibatalkan' ? 'active' : '' ?>" href="<?= base_url('admin/pesanan?status=dibatalkan') ?>">Dibatalkan</a>
                </div>
            </div>
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
                            <th>Status Pembayaran</th>
                            <th>Pre-Order</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($pesanan as $p) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>#<?= str_pad($p['id'], 5, '0', STR_PAD_LEFT); ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($p['tanggal_pesanan'])); ?></td>
                                <td><?= esc($p['nama_lengkap']); ?></td>
                                <td>Rp <?= number_format($p['total'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php
                                    $statusBadge = 'secondary';
                                    switch ($p['status']) {
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
                                </td>
                                <td>
                                    <?php
                                    $paymentBadge = 'secondary';
                                    switch ($p['status_pembayaran'] ?? 'pending') {
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
                                </td>
                                <td>
                                    <?php if ($p['is_preorder']) : ?>
                                        <span class="badge badge-info">Ya</span>
                                    <?php else : ?>
                                        <span class="badge badge-secondary">Tidak</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/pesanan/detail/' . $p['id']); ?>" class="btn btn-info btn-sm">Detail</a>
                                    <a href="<?= base_url('admin/pesanan/update-status/' . $p['id']); ?>" class="btn btn-warning btn-sm">Update Status</a>
                                    <?php if ($p['status'] == 'dikirim') : ?>
                                        <a href="<?= base_url('admin/pesanan/tracking/' . $p['id']); ?>" class="btn btn-primary btn-sm">Tracking</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($pesanan)) : ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data pesanan</td>
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
    $('#dataTable').DataTable({
        "order": [[ 2, "desc" ]]  // Sort by tanggal pesanan (kolom ke-3) descending
    });
});
</script>

<?= view('layouts/admin/footer'); ?>