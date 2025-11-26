<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= base_url() ?>">Home</a></li>
                    <li>Profil</li>
                </ul>
            </div>
            <h1>Profil Saya</h1>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="post" action="<?= base_url('profile/update') ?>">
                            <?= csrf_field() ?>
                            <div class="form-group mb-3">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama_lengkap" value="<?= esc($user['nama_lengkap']) ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" value="<?= esc($user['email']) ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>No. Telepon</label>
                                <input type="text" class="form-control" name="no_telepon" value="<?= esc($user['no_telepon'] ?? '') ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label>Alamat</label>
                                <textarea class="form-control" name="alamat" rows="3"><?= esc($user['alamat'] ?? '') ?></textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn_1">Simpan Perubahan</button>
                                <a href="<?= base_url('profile/change-password') ?>" class="btn btn-outline-secondary">Ubah Kata Sandi</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= view('layouts/home/footer'); ?>
