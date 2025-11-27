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
                                                    case 'menunggu_pembayaran':
                                                        $statusClass = 'warning';
                                                        $statusText = 'Menunggu Pembayaran';
                                                        break;
                                                    case 'diproses':
                                                        $statusClass = 'info';
                                                        $statusText = 'Diproses';
                                                        break;
                                                    case 'dikirim':
                                                        $statusClass = 'primary';
                                                        $statusText = 'Dikirim';
                                                        break;
                                                    case 'selesai':
                                                        $statusClass = 'success';
                                                        $statusText = 'Selesai';
                                                        break;
                                                    case 'dibatalkan':
                                                        $statusClass = 'danger';
                                                        $statusText = 'Dibatalkan';
                                                        break;
                                                    default:
                                                        $statusClass = 'secondary';
                                                        $statusText = ucfirst($order['status']);
                                                }
                                                ?>
                                                <span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('checkout/order/' . ($order['external_id'] ?? $order['id'])) ?>" class="btn_1 small">Detail</a>
                                                <?php if ($order['status'] === 'menunggu_pembayaran'): ?>
                                                    <?php if(isset($order['metode_pembayaran']) && $order['metode_pembayaran'] === 'xendit'): ?>
                                                        <a href="<?= base_url('checkout/pay-xendit/' . ($order['external_id'] ?? $order['id'])) ?>" class="btn btn-sm btn-success ms-1">Bayar</a>
                                                    <?php else: ?>
                                                        <a href="<?= base_url('checkout/pay/' . ($order['external_id'] ?? $order['id'])) ?>" class="btn btn-sm btn-success ms-1">Bayar</a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if (in_array($order['status'], ['selesai', 'dikirim', 'delivered'])): ?>
                                                    <a href="<?= base_url('returns/create/' . $order['id']) ?>" class="btn btn-sm btn-outline-danger ms-1">Minta Retur</a>
                                                <?php endif; ?>
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