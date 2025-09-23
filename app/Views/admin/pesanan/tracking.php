<?= view('layouts/admin/header'); ?>
<?= view('layouts/admin/sidebar'); ?>
<?= view('layouts/admin/topbar'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tracking Pesanan #<?= str_pad($pesanan['id'], 5, '0', STR_PAD_LEFT); ?></h1>
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Tracking</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($pesanan['no_resi'])) : ?>
                        <div class="alert alert-warning">
                            Nomor resi belum diinput. Silakan update nomor resi di halaman detail pesanan.
                        </div>
                    <?php else : ?>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nomor Resi:</strong> <?= $pesanan['no_resi']; ?></p>
                                <?php if (isset($pengiriman) && $pengiriman) : ?>
                                    <p><strong>Jasa Pengiriman:</strong> <?= esc($pengiriman['jasa_pengiriman']); ?></p>
                                    <p><strong>Estimasi Pengiriman:</strong> <?= esc($pengiriman['estimasi_pengiriman']); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status Pesanan:</strong> 
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
                                <?php if (isset($pengiriman) && $pengiriman) : ?>
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
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="alert alert-info">
                            <p><strong>Catatan:</strong> Untuk melacak pengiriman secara detail, silakan kunjungi situs resmi kurir yang digunakan dengan memasukkan nomor resi yang tertera.</p>
                        </div>
                        
                        <!-- Timeline Status -->
                        <div class="mt-4">
                            <h5>Timeline Status</h5>
                            <div class="timeline-tracking">
                                <ul class="timeline">
                                    <li class="<?= $pesanan['status'] != 'dibatalkan' ? 'complete' : '' ?>">
                                        <div class="timeline-badge"><i class="fa fa-check"></i></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h6 class="timeline-title">Pesanan Dibuat</h6>
                                                <p><small class="text-muted"><?= date('d-m-Y H:i', strtotime($pesanan['tanggal_pesanan'])); ?></small></p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="<?= in_array($pesanan['status'], ['diproses', 'dikirim', 'selesai']) ? 'complete' : '' ?>">
                                        <div class="timeline-badge"><i class="fa <?= in_array($pesanan['status'], ['diproses', 'dikirim', 'selesai']) ? 'fa-check' : 'fa-clock' ?>"></i></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h6 class="timeline-title">Pesanan Diproses</h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="<?= in_array($pesanan['status'], ['dikirim', 'selesai']) ? 'complete' : '' ?>">
                                        <div class="timeline-badge"><i class="fa <?= in_array($pesanan['status'], ['dikirim', 'selesai']) ? 'fa-check' : 'fa-clock' ?>"></i></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h6 class="timeline-title">Pesanan Dikirim</h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="<?= in_array($pesanan['status'], ['selesai']) ? 'complete' : '' ?>">
                                        <div class="timeline-badge"><i class="fa <?= in_array($pesanan['status'], ['selesai']) ? 'fa-check' : 'fa-clock' ?>"></i></div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h6 class="timeline-title">Pesanan Selesai</h6>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Alamat Pengiriman</h6>
                </div>
                <div class="card-body">
                    <p>
                        <?= nl2br(esc($pesanan['alamat_pengiriman'])); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mb-4">
        <a href="<?= base_url('admin/pesanan/detail/' . $pesanan['id']); ?>" class="btn btn-secondary">Kembali ke Detail Pesanan</a>
        <a href="<?= base_url('admin/pesanan'); ?>" class="btn btn-primary">Kembali ke Daftar Pesanan</a>
    </div>
</div>

<style>
/* Timeline Styling */
.timeline {
    list-style: none;
    padding: 20px 0 20px;
    position: relative;
}

.timeline:before {
    top: 0;
    bottom: 0;
    position: absolute;
    content: " ";
    width: 3px;
    background-color: #eeeeee;
    left: 25px;
    margin-right: -1.5px;
}

.timeline > li {
    margin-bottom: 20px;
    position: relative;
}

.timeline > li:before,
.timeline > li:after {
    content: " ";
    display: table;
}

.timeline > li:after {
    clear: both;
}

.timeline > li > .timeline-panel {
    width: calc(100% - 65px);
    float: right;
    border: 1px solid #d4d4d4;
    border-radius: 2px;
    padding: 10px 15px;
    position: relative;
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
}

.timeline > li > .timeline-badge {
    color: #fff;
    width: 40px;
    height: 40px;
    line-height: 40px;
    font-size: 1.4em;
    text-align: center;
    position: absolute;
    top: 10px;
    left: 10px;
    margin-right: -25px;
    background-color: #999999;
    z-index: 100;
    border-top-right-radius: 50%;
    border-top-left-radius: 50%;
    border-bottom-right-radius: 50%;
    border-bottom-left-radius: 50%;
}

.timeline > li.complete > .timeline-badge {
    background-color: #4caf50;
}

.timeline-title {
    margin-top: 0;
    color: inherit;
}
</style>

<?= view('layouts/admin/footer'); ?>