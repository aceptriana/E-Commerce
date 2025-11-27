<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="box_general text-center">
                    <i class="fa fa-times-circle text-danger" style="font-size: 48px;"></i>
                    <h2 class="mt-3">Pembayaran Gagal</h2>
                    <p class="lead">Pembayaran Anda belum berhasil. Silakan coba kembali atau hubungi layanan pelanggan jika masalah berlanjut.</p>

                    <div class="mt-4">
                        <a href="<?= base_url('checkout/history') ?>" class="btn_1">Lihat Riwayat Pesanan</a>
                        <a href="<?= base_url('checkout') ?>" class="btn_1 outline">Kembali ke Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= view('layouts/home/footer'); ?>