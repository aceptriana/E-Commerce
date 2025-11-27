<?= view('layouts/home/header'); ?>

<main>
    <div class="container margin_60_35">
        <div class="main_title">
            <h2>Bantuan & FAQ</h2>
            <p>Pertanyaan yang sering ditanyakan tentang Mantra Jaya Tani</p>
        </div>

        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Bagaimana cara melakukan pemesanan?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Untuk melakukan pemesanan, Anda dapat mengikuti langkah-langkah berikut:
                        <ol>
                            <li>Pilih produk yang diinginkan</li>
                            <li>Klik tombol "Tambah ke Keranjang"</li>
                            <li>Periksa keranjang belanja Anda</li>
                            <li>Lakukan checkout dan isi data pengiriman</li>
                            <li>Pilih metode pembayaran</li>
                            <li>Selesaikan pembayaran</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Apa metode pembayaran yang tersedia?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Kami menerima berbagai metode pembayaran:
                        <ul>
                            <li>Transfer Bank (BCA, Mandiri, BNI, BRI)</li>
                            <li>E-wallet (GoPay, OVO, Dana, LinkAja)</li>
                            <li>Virtual Account</li>
                            <li>Credit Card</li>
                            <li>Cash on Delivery (COD) untuk area tertentu</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Berapa lama waktu pengiriman?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Waktu pengiriman tergantung pada lokasi Anda:
                        <ul>
                            <li>Jabodetabek: 1-2 hari kerja</li>
                            <li>Jawa: 2-4 hari kerja</li>
                            <li>Luar Jawa: 3-7 hari kerja</li>
                        </ul>
                        Waktu pengiriman dapat berubah tergantung pada kondisi cuaca dan ketersediaan stok.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Bagaimana cara mengembalikan produk?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Untuk pengembalian produk, Anda dapat:
                        <ol>
                            <li>Masuk ke akun Anda</li>
                            <li>Pilih menu "Returns" atau "Pengembalian"</li>
                            <li>Pilih pesanan yang ingin dikembalikan</li>
                            <li>Isi formulir pengembalian dengan alasan</li>
                            <li>Tim kami akan memproses dalam 1-3 hari kerja</li>
                        </ol>
                        Syarat pengembalian: Produk dalam kondisi baik, belum digunakan, dan masih dalam kemasan asli.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Apakah ada biaya pengiriman?
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Ya, biaya pengiriman ditentukan berdasarkan:
                        <ul>
                            <li>Berat dan dimensi paket</li>
                            <li>Jarak pengiriman</li>
                            <li>Layanan pengiriman yang dipilih</li>
                        </ul>
                        Biaya pengiriman akan ditampilkan saat checkout sebelum Anda menyelesaikan pembayaran.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSix">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                        Bagaimana cara menghubungi customer service?
                    </button>
                </h2>
                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Anda dapat menghubungi customer service kami melalui:
                        <ul>
                            <li>Email: support@mantrajayatani.com</li>
                            <li>WhatsApp: +62 812-3456-7890</li>
                            <li>Telepon: (021) 1234-5678</li>
                            <li>Jam operasional: Senin-Jumat 08.00-17.00 WIB</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSeven">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                        Apakah produk memiliki garansi?
                    </button>
                </h2>
                <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Ya, produk kami memiliki garansi sesuai dengan ketentuan masing-masing:
                        <ul>
                            <li>Pupuk dan pestisida: Garansi kualitas 30 hari</li>
                            <li>Alat pertanian: Garansi 6-12 bulan tergantung jenis</li>
                            <li>Bibit tanaman: Garansi tumbuh 7-14 hari</li>
                        </ul>
                        Garansi tidak berlaku untuk kerusakan akibat penggunaan yang tidak sesuai petunjuk.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingEight">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                        Bagaimana cara mendaftar akun?
                    </button>
                </h2>
                <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Untuk mendaftar akun baru:
                        <ol>
                            <li>Klik menu "Login" di pojok kanan atas</li>
                            <li>Pilih "Daftar Akun Baru"</li>
                            <li>Isi formulir pendaftaran dengan data lengkap</li>
                            <li>Verifikasi email Anda</li>
                            <li>Akun Anda siap digunakan</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="mb-2">Tidak menemukan jawaban yang Anda cari?</p>
            <a href="mailto:support@mantrajayatani.com" class="btn_1 rounded">Hubungi Kami</a>
        </div>
    </div>
</main>

<?= view('layouts/home/footer'); ?>