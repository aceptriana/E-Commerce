<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= base_url() ?>">Home</a></li>
                    <li>Retur & Pengembalian</li>
                </ul>
            </div>
            <h1>Retur & Pengembalian</h1>
        </div>
        <div class="row">
            <div class="col-md-8">
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <a href="<?= base_url('returns/create') ?>" class="btn_1 mb-3">Buat Permintaan Retur</a>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID Retur</th>
                                    <th>Order ID</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($returns)): ?>
                                    <tr><td colspan="5" class="text-center">Belum ada permintaan retur</td></tr>
                                <?php else: foreach ($returns as $r): ?>
                                    <tr>
                                        <td><?= $r['id'] ?></td>
                                        <td>#<?= $r['order_id'] ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                                        <td><span class="badge bg-<?= $r['status'] === 'requested' ? 'warning' : ($r['status'] === 'approved' ? 'success' : ($r['status'] === 'rejected' ? 'danger' : 'info')) ?>"><?= ucfirst($r['status']) ?></span></td>
                                        <td>
                                            <a href="<?= base_url('checkout/order/'.$r['order_id']) ?>" class="btn btn-sm btn-outline-primary">Lihat Order</a>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= view('layouts/home/footer'); ?>
