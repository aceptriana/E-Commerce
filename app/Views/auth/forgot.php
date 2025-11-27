<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= base_url('/') ?>">Beranda</a></li>
                    <li>Lupa Kata Sandi</li>
                </ul>
            </div>
            <h1>Lupa Kata Sandi</h1>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-6 col-md-8">
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('error')): ?>
                    <?php if(is_array(session()->getFlashdata('error'))): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach(session()->getFlashdata('error') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="box_account">
                    <div class="form_container">
                        <form action="<?= base_url('/auth/resetPassword'); ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required value="<?= old('email') ?>">
                            </div>
                            <div class="form-group mt-2">
                                <label for="password">Kata Sandi Baru</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Kata Sandi Baru" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="password_confirm">Konfirmasi Kata Sandi</label>
                                <input type="password" class="form-control" name="password_confirm" id="password_confirm" placeholder="Konfirmasi Kata Sandi" required>
                            </div>
                            <div class="text-center mt-3"><input type="submit" value="Ubah Kata Sandi" class="btn_1 full-width"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<?= view('layouts/home/footer'); ?>