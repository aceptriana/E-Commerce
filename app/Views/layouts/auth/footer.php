<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <h3 data-bs-target="#collapse_1">Quick Links</h3>
                <div class="collapse dont-collapse-sm links" id="collapse_1">
                    <ul>
                        <li><a href="<?= base_url('about') ?>">About us</a></li>
                        <li><a href="<?= base_url('help') ?>">Faq</a></li>
                        <li><a href="<?= base_url('help') ?>">Help</a></li>
                        <li><a href="<?= base_url('account') ?>">My account</a></li>
                        <li><a href="<?= base_url('blog') ?>">Blog</a></li>
                        <li><a href="<?= base_url('contacts') ?>">Contacts</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h3 data-bs-target="#collapse_2">Categories</h3>
                <div class="collapse dont-collapse-sm links" id="collapse_2">
                    <ul>
                        <li><a href="<?= base_url('listing-grid-1-full') ?>">Clothes</a></li>
                        <li><a href="<?= base_url('listing-grid-2-full') ?>">Electronics</a></li>
                        <li><a href="<?= base_url('listing-grid-1-full') ?>">Furniture</a></li>
                        <li><a href="<?= base_url('listing-grid-3') ?>">Glasses</a></li>
                        <li><a href="<?= base_url('listing-grid-1-full') ?>">Shoes</a></li>
                        <li><a href="<?= base_url('listing-grid-1-full') ?>">Watches</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h3 data-bs-target="#collapse_3">Contacts</h3>
                <div class="collapse dont-collapse-sm contacts" id="collapse_3">
                    <ul>
                        <li><i class="ti-home"></i>Perum Griya intan blok f no 4 harjamukti cirebon</li>
                        <li><i class="ti-headphone-alt"></i>+62 856-6666-6666</li>
                       
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h3 data-bs-target="#collapse_4">Keep in touch</h3>
                <div class="collapse dont-collapse-sm" id="collapse_4">
                    <div id="newsletter">
                        <div class="form-group">
                            <input type="email" name="email_newsletter" id="email_newsletter" class="form-control" placeholder="Your email">
                            <button type="submit" id="submit-newsletter"><i class="ti-angle-double-right"></i></button>
                        </div>
                    </div>
                    <div class="follow_us">
                        <h5>Follow Us</h5>
                        <ul>
                            <li><a href="#"><i class="bi bi-facebook"></i></a></li>
                           
                            <li><a href="#"><i class="bi bi-instagram"></i></a></li>
                            <li><a href="#"><i class="bi bi-tiktok"></i></a></li>
                            <li><a href="#"><i class="bi bi-whatsapp"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- /row-->
        <hr>
        <div class="row add_bottom_25">
            <div class="col-lg-6">
                <ul class="footer-selector clearfix">
                   
                </ul>
            </div>
            <div class="col-lg-6">
                <ul class="additional_links">
                    <li><a href="<?= base_url('terms-and-conditions') ?>">Terms and conditions</a></li>
                    <li><a href="<?= base_url('privacy') ?>">Privacy</a></li>
                    <li><span>© 2025 Mantra Jaya Tani</span></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<!--/footer-->
</div>
<!-- page -->

<div id="toTop"></div><!-- Back to top button -->

<!-- COMMON SCRIPTS -->
<script src="<?= base_url('js/common_scripts.min.js') ?>"></script>
<script src="<?= base_url('js/main.js') ?>"></script>

<script>
    // Client type Panel
    $('input[name="client_type"]').on("click", function() {
        var inputValue = $(this).attr("value");
        var targetBox = $("." + inputValue);
        $(".box").not(targetBox).hide();
        $(targetBox).show();
    });
</script>
