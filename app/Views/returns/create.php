<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= base_url() ?>">Home</a></li>
                    <li>Retur</li>
                </ul>
            </div>
            <h1>Permintaan Retur</h1>
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
                        <form action="<?= base_url('returns/store') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-group mb-3">
                                <label>Order ID (masukkan ID pesanan)</label>
                                <input type="text" name="order_id" value="<?= isset($order['id']) ? $order['id'] : '' ?>" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Alasan Retur</label>
                                <textarea name="reason" class="form-control" rows="4" required><?= old('reason') ?></textarea>
                            </div>
                            <button type="submit" class="btn_1">Kirim Permintaan Retur</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= view('layouts/home/footer'); ?>
