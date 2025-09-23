<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="box_general">
                    <div class="text-center">
                        <i class="fa fa-check-circle text-success" style="font-size: 48px;"></i>
                        <h2 class="mt-3">Pembayaran Berhasil!</h2>
                        <p class="lead">Terima kasih telah berbelanja di Mantra Jaya Tani</p>
                    </div>
                    
                    <div class="mt-4">
                        <h4>Informasi Pesanan</h4>
                        <p>Pesanan Anda telah berhasil diproses dan akan segera kami kirimkan.</p>
                        <p>Anda dapat melihat status pesanan Anda di halaman riwayat pesanan.</p>
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