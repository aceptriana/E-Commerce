<?= view('layouts/pemilik/header'); ?>
<?= view('layouts/pemilik/sidebar'); ?>
<?= view('layouts/pemilik/topbar'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4">Edit Staff (Pemilik)</h1>
    <p>Form edit staff for ID <?= esc($id) ?> - placeholder</p>
    <a class="btn btn-secondary" href="<?= base_url('pemilik/staff') ?>">Kembali</a>
</div>

<?= view('layouts/pemilik/footer'); ?>
