<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= base_url('/'); ?>">Beranda</a></li>
                    <li>Profil Saya</li>
                </ul>
            </div>
            <h1>Profil Saya</h1>
        </div>
        <!-- /page_header -->
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-8 col-md-10">
                <div class="box_account">
                    <h3 class="client">Detail Akun</h3>
                    <div class="form_container">
                        <!-- Tampilkan pesan sukses/error jika ada -->
                        <?php if(session()->getFlashdata('success')): ?>
                            <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
                        <?php endif; ?>
                        <?php if(session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control" value="<?= esc($user['nama_lengkap'] ?? ''); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" value="<?= esc($user['email'] ?? ''); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <input type="text" class="form-control" value="<?= esc($user['no_telepon'] ?? ''); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea class="form-control" readonly><?= esc($user['alamat'] ?? ''); ?></textarea>
                        </div>
                        <!-- Anda bisa menambahkan tombol untuk edit profil di sini -->
                    </div>
                    <!-- /form_container -->
                </div>
                <!-- /box_account -->
            </div>
        </div>
    </div>
</main>

<?= view('layouts/home/footer'); ?>
