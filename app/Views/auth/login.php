<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= base_url('/'); ?>">Beranda</a></li>
                    <li><a href="#">Kategori</a></li>
                    <li>Halaman Aktif</li>
                </ul>
            </div>
            <h1>Masuk atau Buat Akun</h1>
        </div>
        <!-- /page_header -->

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
                    <h3 class="client">Sudah Punya Akun</h3>
                    <div class="form_container">
                        <form action="<?= base_url('/auth/processLogin'); ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email*" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password_in" id="password_in" placeholder="Kata Sandi*" required>
                            </div>
                            <div class="clearfix add_bottom_15">
                                <div class="checkboxes float-start">
                                    <label class="container_check">Ingat saya
                                        <input type="checkbox" name="remember">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="float-end"><a id="forgot" href="javascript:void(0);">Lupa Kata Sandi?</a></div>
                            </div>
                            <div class="text-center"><input type="submit" value="Masuk" class="btn_1 full-width"></div>
                        </form>
                        <div id="forgot_pw" style="display:none;">
                            <form action="<?= base_url('/auth/resetPassword'); ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" id="email_forgot" placeholder="Masukkan email Anda" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" id="password_forgot" placeholder="Kata Sandi Baru" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password_confirm" id="password_confirm_forgot" placeholder="Konfirmasi Kata Sandi" required>
                                </div>
                                <p>Masukkan email dan kata sandi baru. Jika email terdaftar, kata sandi akan diubah.</p>
                                <div class="text-center"><input type="submit" value="Ubah Kata Sandi" class="btn_1"></div>
                            </form>
                        </div>
                    </div>
                    <!-- /form_container -->
                </div>
                <!-- /box_account -->
            </div>

            <div class="col-xl-6 col-lg-6 col-md-8">
                <div class="box_account">
                    <h3 class="new_client">Pelanggan Baru</h3> <small class="float-right pt-2">* Wajib Diisi</small>
                    <div class="form_container">
                        <form action="<?= base_url('/auth/processRegister'); ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Lengkap*" required value="<?= old('nama') ?>">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" id="email_2" placeholder="Email*" required value="<?= old('email') ?>">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" id="password_in_2" placeholder="Kata Sandi*" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="Nomor HP*" required value="<?= old('no_hp') ?>">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="alamat" id="alamat" placeholder="Alamat Lengkap*" required rows="3"><?= old('alamat') ?></textarea>
                            </div>
                            <hr>
                            <div class="text-center"><input type="submit" value="Daftar" class="btn_1 full-width"></div>
                        </form>
                    </div>
                    <!-- /form_container -->
                </div>
                <!-- /box_account -->
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</main>
<!--/main-->

<?= view('layouts/home/footer'); ?>

<!-- Remove duplicate forgot handler: main.js handles the toggle -->