<?= view('layouts/admin/header'); ?>
<?= view('layouts/admin/sidebar'); ?>
<?= view('layouts/admin/topbar'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen Retur</h1>
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Tanggal</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($returns as $r): ?>
                        <tr>
                            <td><?= $r['id'] ?></td>
                            <td>#<?= $r['order_id'] ?></td>
                            <td><?= $r['user_id'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                            <td><?= esc($r['reason']) ?></td>
                            <td><?= ucfirst($r['status']) ?></td>
                            <td>
                                <form action="<?= base_url('admin/returns/approve/'.$r['id']) ?>" method="post" class="d-inline-block">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="admin_note" value="Approved by admin">
                                    <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                </form>
                                <form action="<?= base_url('admin/returns/reject/'.$r['id']) ?>" method="post" class="d-inline-block">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="admin_note" value="Ditolak karena ...">
                                    <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= view('layouts/admin/footer'); ?>
