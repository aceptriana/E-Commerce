<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <h1>Tulis Ulasan</h1>
        </div>
        <!-- /page_header -->
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="box_general write_review">
                    <div class="main_title_3">
                        <h2><span><i class="icon-pencil-1"></i></span>Tulis ulasan untuk <?= esc($produk['nama_produk']); ?></h2>
                    </div>
                    
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('ulasan/simpan'); ?>" method="post">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="produk_id" value="<?= $produk['id']; ?>">
                        
                        <div class="form-group">
                            <label>Rating Anda</label>
                            <div class="rating-input">
                                <input type="radio" name="rating" value="5" id="star5" required>
                                <label for="star5" title="5 stars">★</label>
                                
                                <input type="radio" name="rating" value="4" id="star4">
                                <label for="star4" title="4 stars">★</label>
                                
                                <input type="radio" name="rating" value="3" id="star3">
                                <label for="star3" title="3 stars">★</label>
                                
                                <input type="radio" name="rating" value="2" id="star2">
                                <label for="star2" title="2 stars">★</label>
                                
                                <input type="radio" name="rating" value="1" id="star1">
                                <label for="star1" title="1 star">★</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Ulasan Anda</label>
                            <textarea class="form-control" name="komentar" rows="6" placeholder="Tulis ulasan Anda tentang produk ini..." required><?= old('komentar'); ?></textarea>
                            <small class="form-text text-muted">Minimal 10 karakter</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn_1">Kirim Ulasan</button>
                            <a href="<?= base_url('produk/detail/' . $produk['id']); ?>" class="btn_1 gray">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</main>

<style>
.rating-input {
    direction: rtl;
    unicode-bidi: bidi-override;
    font-size: 40px;
    margin-bottom: 20px;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input label {
    color: #ddd;
    cursor: pointer;
    padding: 0 5px;
}

.rating-input label:hover,
.rating-input label:hover ~ label,
.rating-input input[type="radio"]:checked ~ label {
    color: #fec348;
}

.write_review .form-group {
    margin-bottom: 25px;
}

.write_review .btn_1.gray {
    background-color: #999;
    margin-left: 10px;
}

.write_review .btn_1.gray:hover {
    background-color: #777;
}
</style>

<?= view('layouts/home/footer'); ?>
