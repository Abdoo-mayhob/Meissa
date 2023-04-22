<div class="container-fluid">
    <div class="row decor-space"></div>
</div>
<footer>
    <div class="footer-wrapper p-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 ">
                    <img width="190" height="190" loading="lazy" src="<?=get_theme_mod('meissa_footer_logo')?>" alt="Site Logo" class="footer-logo">
                    <div class="footer-logo-caption">Meissa - ميسان</div>
                    <div class="footer-logo-desc">كل ما يخص الفضاء و الكون وتاريخه وتقنياته. شاركنا رحلة التعلم ! </div>
                </div>
                <div class="col-md-4 menu-col">
                    <? wp_nav_menu(['theme_location' => 'footer-menu', 'container_class' => 'footer-menu-container'] ) ?>
                </div>
                <div class="col-md-4 newsletter-form-col">

                    <h3 class="mb-0">تابعنا على السوشل ميديا</h3>
                    <div class="footer-social-icons pb-4 pt-1">
                        <a href='<?= get_theme_mod('meissa_facebook_link')?>' class="dashicons-facebook-alt"></a>
                        <a href='<?= get_theme_mod('meissa_instagram_link')?>' class="dashicons-instagram"></a>
                    </div>
                    <h3 class="mb-0">و اشترك في النشرة البريدية</h3>
                    <form class="newsletter" action="/" method="post">
                        <input placeholder="اكتب الإيميل هنا" type="email" id="email" name="email" title="email" required="required" >
                        <button type="submit" class="dashicons-arrow-left-alt2" title="Subscribe" > </button>
                    </form>
                    <h3 class="mt-4 mb-0">إبق دوماً على علم !</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="container p-2 d-flex flex-wrap justify-content-center justify-content-md-between colophon" id="colophon">
        <p class="copywrite">جميع الحقوق محفوظة. ميسان 2022 - <?=date("Y")?></p>
        <p class="credit">تصميم و تطوير:<a href="https://abdoo.me"> Abdullatif Al-Mayhob</a></p>
    </div>
</footer>
<?php wp_footer()?>

<?
if ( defined('WP_DEBUG') && true === WP_DEBUG) {
    $start = $GLOBALS['start_ms'] ;
    $end = microtime(true);
    echo "<pre style='direction: ltr;'>Entire Load Time (PHP): ", $end - $start;
    echo '<script>$ = jQuery;</script>' ;
}
?>

</body>
</html>