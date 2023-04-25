<? get_header() ?>
<main class="container py-4">
    <section class="row">
        <div class="col-md-6 center-vertically">
            <h1 class="title">مدونة ميسان للفضاء</h1>
            <p class="sub-title">كل ما يخص الفضاء و الكون وتاريخه وتقنياته
            شاركنا رحلة التعلم !</p>
        </div>
        <div class="meissa-hide-md col-md-6"
            <? meissa_bg_img(THEME_URI.'/meissa-assets/James-Web-Telescope.webp','400px') ?>>
        </div>
    </section>
    <section class="row">
        <div class="col-md-6 center-vertically">
            <h2 class="h1">مقالات مختارة</h2>
            <div class="row">
                <? $featured_posts = meissa_get_featured_posts(6); ?>
                <? while (  $featured_posts->have_posts() ):  $featured_posts->the_post();?>
                    <article class="col-12 pb-3">
                        <? get_template_part('template-parts/loop') ?>
                    </article>
                <? endwhile ?>
            </div>
        </div>
        <div id="Floating-Astro" class="meissa-hide-md col-md-6"
            <? meissa_bg_img(THEME_URI.'/meissa-assets/Floating-Astro.webp','580px') ?>>
        </div>
    </section>
    <section class="row">
        <h2 class="col-12 h1">الأكثر قراءة</h2>
        <? $most_read_posts = meissa_get_most_read_posts(); ?>
        <? while (  $most_read_posts->have_posts() ):  $most_read_posts->the_post();?>
            <article class="col-md-6 pb-3">
                <? get_template_part('template-parts/loop') ?>
            </article>
        <? endwhile ?>
        <? wp_reset_postdata() ?>
    </section>
    <section class="row mt-4">
        <h2 class="col-12 h1">أٌضيف مؤخراً</h2>
        <? $latest_posts = meissa_get_latest_posts(); ?>
        <? while (  $latest_posts->have_posts() ):  $latest_posts->the_post();?>
            <article class="col-md-6 pb-3">
                <? get_template_part('template-parts/loop') ?>
            </article>
        <? endwhile ?>
        <? wp_reset_postdata() ?>
    </section>
</main>
<? get_footer() ?>