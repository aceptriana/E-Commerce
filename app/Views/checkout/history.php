<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= base_url() ?>">Home</a></li>
                    <li>Riwayat Pesanan</li>
                </ul>
            </div>
            <h1>Riwayat Pesanan</h1>
        </div>
        <!-- /page_header -->

        <div class="row">
            <div class="col-lg-12">
                <div class="box_general">
                    <?php if (empty($orders)): ?>
                        <div class="text-center">
                            <p>Anda belum memiliki pesanan.</p>
                            <a href="<?= base_url() ?>" class="btn_1">Mulai Belanja</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Pesanan</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td><?= $order['external_id'] ?? 'N/A' ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($order['tanggal_pesanan'])) ?></td>
                                            <td>Rp <?= number_format($order['total'], 0, ',', '.') ?></td>
                                            <td>
                                                <?php
                                                $statusClass = '';
                                                $statusText = '';
                                                switch ($order['status']) {
                                                    case 'pending':
                                                        $statusClass = 'warning';
                                                        $statusText = 'Menunggu Pembayaran';
                                                        break;
                                                    case 'success':
                                                        $statusClass = 'success';
                                                        $statusText = 'Pembayaran Berhasil';
                                                        break;
                                                    case 'failed':
                                                        $statusClass = 'danger';
                                                        $statusText = 'Pembayaran Gagal';
                                                        break;
                                                    case 'expired':
                                                        $statusClass = 'secondary';
                                                        $statusText = 'Kadaluarsa';
                                                        break;
                                                    default:
                                                        $statusClass = 'info';
                                                        $statusText = ucfirst($order['status']);
                                                }
                                                ?>
                                                <span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('checkout/order/' . ($order['external_id'] ?? $order['id'])) ?>" class="btn_1 small">Detail</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?= view('layouts/home/footer'); ?> 