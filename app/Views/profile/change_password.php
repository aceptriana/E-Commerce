<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= base_url() ?>">Home</a></li>
                    <li>Ubah Kata Sandi</li>
                </ul>
            </div>
            <h1>Ubah Kata Sandi</h1>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="post" action="<?= base_url('profile/change-password') ?>">
                            <?= csrf_field() ?>
                            <div class="form-group mb-3">
                                <label>Kata Sandi Lama</label>
                                <input type="password" class="form-control" name="old_password" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Kata Sandi Baru</label>
                                <input type="password" class="form-control" name="new_password" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Konfirmasi Kata Sandi Baru</label>
                                <input type="password" class="form-control" name="confirm_password" required>
                            </div>
                            <div>
                                <button type="submit" class="btn_1">Ubah Kata Sandi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= view('layouts/home/footer'); ?>
