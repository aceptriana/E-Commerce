<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= base_url() ?>">Home</a></li>
                    <li>Simulasi Pembayaran</li>
                </ul>
            </div>
            <h1>Simulasi Webhook Midtrans</h1>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="box_general">
                    <?php if (empty($pendingPayments)): ?>
                        <div class="text-center">
                            <p>Tidak ada pembayaran berstatus pending untuk disimulasikan.</p>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">Jika kolom <strong>External ID</strong> kosong, sistem akan menggunakan <strong>Pesanan ID</strong> untuk melakukan simulasi.</div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>External ID</th>
                                        <th>Pesanan ID</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pendingPayments as $p): ?>
                                        <tr>
                                            <td><?= empty($p['external_id']) ? '<em>Belum di-set</em>' : esc($p['external_id']) ?></td>
                                            <td><?= esc($p['pesanan_id']) ?></td>
                                            <td>Rp <?= number_format($p['total_bayar'] ?? 0, 0, ',', '.') ?></td>
                                            <td><?= esc($p['status']) ?></td>
                                            <td>
                                                <form action="<?= base_url('checkout/simulate') ?>" method="post">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="external_id" value="<?= esc($p['external_id']) ?>">
                                                    <input type="hidden" name="pesanan_id" value="<?= esc($p['pesanan_id']) ?>">
                                                    <button type="submit" class="btn btn-sm btn-success">Simulasikan Sukses</button>
                                                </form>
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