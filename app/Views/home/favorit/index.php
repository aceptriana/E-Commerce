<?= view('layouts/home/header'); ?>

<div class="container mt-4">
	<h2 class="mb-4">Favorit Saya</h2>

	<?php if (session()->getFlashdata('success')) : ?>
		<div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
	<?php endif; ?>
	<?php if (session()->getFlashdata('error')) : ?>
		<div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
	<?php endif; ?>

	<?php if (empty($favorit)) : ?>
		<div class="text-center text-muted py-5">
			<p>Belum ada produk favorit.</p>
			<a href="<?= base_url('produk'); ?>" class="btn btn-primary">Belanja Sekarang</a>
		</div>
	<?php else : ?>
		<div class="row">
			<?php foreach ($favorit as $item) : ?>
				<div class="col-md-3 mb-4">
					<div class="card h-100">
						<img src="<?= base_url($item['gambar'] ?: 'img/products/product_placeholder_square_medium.jpg'); ?>" class="card-img-top" alt="<?= esc($item['nama_produk']); ?>">
						<div class="card-body d-flex flex-column">
							<h5 class="card-title" style="font-size: 1rem; min-height: 2.5rem;">
								<?= esc($item['nama_produk']); ?>
							</h5>
							<p class="card-text mb-2 text-muted" style="font-size: 0.9rem;">Kategori: <?= esc($item['nama_kategori'] ?? '-'); ?></p>
							<p class="card-text mb-3"><strong>Rp <?= number_format($item['harga'], 0, ',', '.'); ?></strong></p>
							<div class="mt-auto d-flex justify-content-between">
								<a href="<?= base_url('produk/detail/' . $item['id']); ?>" class="btn btn-sm btn-outline-primary">Detail</a>
								<a href="<?= base_url('favorit/hapus/' . $item['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus dari favorit?');">Hapus</a>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

<?= view('layouts/home/footer'); ?>


