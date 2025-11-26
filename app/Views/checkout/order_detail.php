<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= base_url() ?>">Home</a></li>
                    <li><a href="<?= base_url('checkout/history') ?>">Riwayat Pesanan</a></li>
                    <li>Detail Order</li>
                </ul>
            </div>
            <h1>Detail Order #<?= $order['external_id'] ?></h1>
        </div>
        <!-- /page_header -->

        <div class="row">
            <div class="col-lg-8">
                <div class="box_general">
                    <h4>Detail Produk</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_details as $detail): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?= $detail['nama_produk'] ?>
                                            </div>
                                        </td>
                                        <td><?= $detail['jumlah'] ?></td>
                                        <td>Rp <?= number_format($detail['harga_satuan'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($detail['jumlah'] * $detail['harga_satuan'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <?php 
                                $subtotal = 0;
                                foreach ($order_details as $detail) {
                                    $subtotal += $detail['jumlah'] * $detail['harga_satuan'];
                                }
                                ?>
                                <tr>
                                    <th colspan="3" class="text-end">Subtotal:</th>
                                    <th>Rp <?= number_format($subtotal, 0, ',', '.') ?></th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Biaya Pengiriman:</th>
                                    <th>Rp <?= number_format($pengiriman['biaya_pengiriman'], 0, ',', '.') ?></th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th>Rp <?= number_format($order['total'], 0, ',', '.') ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="box_general">
                    <h4>Informasi Pengiriman</h4>
                    <p><strong>Nama:</strong> <?= $order['nama'] ?></p>
                    <p><strong>Email:</strong> <?= $order['email'] ?></p>
                    <p><strong>No. Telepon:</strong> <?= $order['no_telepon'] ?></p>
                    <p><strong>Alamat:</strong> <?= $order['alamat'] ?></p>
                    <p><strong>Jasa Pengiriman:</strong> <?= $order['shipping_service'] ?></p>
                    <p><strong>Deskripsi Pengiriman:</strong> <?= $order['shipping_description'] ?></p>
                    <?php if ($order['status'] === 'dikirim' && !empty($order['no_resi'])): ?>
                        <div class="alert alert-info">
                            <p class="mb-0"><strong>Nomor Resi:</strong> <?= $order['no_resi'] ?></p>
                            <?php if ($order['shipping_service'] === 'jne'): ?>
                                <small><a href="https://www.jne.co.id/tracking/trace" target="_blank" class="text-primary">Lacak Pengiriman JNE</a></small>
                            <?php elseif ($order['shipping_service'] === 'pos'): ?>
                                <small><a href="https://www.posindonesia.co.id/id/tracking" target="_blank" class="text-primary">Lacak Pengiriman POS</a></small>
                            <?php elseif ($order['shipping_service'] === 'tiki'): ?>
                                <small><a href="https://tiki.id/id/tracking" target="_blank" class="text-primary">Lacak Pengiriman TIKI</a></small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="box_general">
                    <h4>Status Pesanan</h4>
                    <?php
                    $statusClass = '';
                    $statusText = '';
                    switch ($order['status']) {
                        case 'pending':
                        case 'menunggu_pembayaran':
                            $statusClass = 'warning';
                            $statusText = 'Menunggu Pembayaran';
                            break;
                        case 'success':
                        case 'berhasil':
                        case 'diproses':
                            $statusClass = 'success';
                            $statusText = 'Pembayaran Berhasil';
                            break;
                        case 'dikirim':
                            $statusClass = 'primary';
                            $statusText = 'Sedang Dikirim';
                            break;
                        case 'selesai':
                        case 'delivered':
                            $statusClass = 'success';
                            $statusText = 'Pesanan Selesai';
                            break;
                        case 'failed':
                        case 'gagal':
                        case 'dibatalkan':
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
                    <p><strong>Status:</strong> <span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span></p>
                    <?php if (!empty($order['payment_type'])): ?>
                        <p><strong>Metode Pembayaran:</strong> <?= ucfirst($order['payment_type']) ?></p>
                    <?php endif; ?>
                    <p><strong>Tanggal Pesanan:</strong> <?= date('d/m/Y H:i', strtotime($order['tanggal_pesanan'])) ?></p>
                    <?php if (!empty($return_request)): ?>
                        <div class="alert alert-warning mt-2">
                            <strong>Permintaan Retur:</strong> <span class="badge bg-<?= $return_request['status'] === 'requested' ? 'warning' : ($return_request['status'] === 'approved' ? 'success' : 'danger') ?> ms-1"><?= ucfirst($return_request['status']) ?></span>
                            <div class="small mt-1">Alasan: <?= esc($return_request['reason']) ?></div>
                            <?php if (!empty($return_request['admin_note'])): ?>
                                <div class="small text-muted mt-1">Catatan Admin: <?= esc($return_request['admin_note']) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (in_array($order['status'], ['selesai', 'delivered'])): ?>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('returns/create/'.$order['id']) ?>" class="btn_1">Minta Retur / Pengembalian</a>
                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </div>
</main>

<?= view('layouts/home/footer'); ?> 