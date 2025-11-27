<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="box_general">
                    <div class="text-center">
                        <i class="fa fa-hourglass-half text-warning" style="font-size: 48px;"></i>
                        <h2 class="mt-3">Pembayaran Menunggu</h2>
                        <p class="lead">Pembayaran Anda sedang menunggu konfirmasi. Silakan cek kembali nanti atau lihat riwayat pesanan Anda.</p>
                    </div>

                    <div class="mt-4">
                        <h4>Informasi</h4>
                        <p>Transaksi Anda belum selesai. Jika Anda belum melakukan pembayaran, silakan klik tombol "Bayar" pada riwayat pesanan untuk melanjutkan pembayaran.</p>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="<?= base_url('checkout/history') ?>" class="btn_1">Lihat Riwayat Pesanan</a>
                        <a href="<?= base_url() ?>" class="btn_1 outline">Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= view('layouts/home/footer'); ?>