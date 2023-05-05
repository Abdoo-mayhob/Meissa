<? get_header() ?>
<div class="container py-4">
    <div class="row content-row">
        <main class="col-md-8 px-4">
            <?= meissa_get_breadcrumb()?>
            <div class="content">
                <? the_content() ?>
            </div>
            <div class="post-meta date dashicons-calendar">
                <? the_date() ?>
            </div>
            <div class="post-meta tags dashicons-tag">
                <? the_tags('') ?>
            </div>
        </main>
        <aside class="col-md-4 px-4">
            <h2>آخر المقالات</h2>
            <? $latest_posts = meissa_get_latest_posts(); ?>
            <? while (  $latest_posts->have_posts() ):  $latest_posts->the_post();?>
                <? get_template_part('template-parts/loop','vert') ?>
            <? endwhile ?>
            <? wp_reset_postdata() ?>
            <div class="seperator"></div>
            <h2>الأكثر قراءة</h2>
            <? $most_read_posts = meissa_get_most_read_posts(); ?>
            <? while (  $most_read_posts->have_posts() ):  $most_read_posts->the_post();?>
                <? get_template_part('template-parts/loop','vert') ?>
            <? endwhile ?>
            <? wp_reset_postdata() ?>
        </aside>
    </div>
</div>
<div class="read-more-wrap-for-bg">
    <div class="container py-5">
        <div class="row gy-4 read-more">
            <h1 class="col-12 read-more-title">
                اقرأ أيضاً
            </h1>
            <? $related_post_query = meissa_get_related_posts();
            while ( $related_post_query->have_posts() ) {
                $related_post_query->the_post();
                ?>
                <div class="col-md-6">
                    <? get_template_part('template-parts/loop') ?>
                </div>
                <?
            }
            ?>
        </div>
    </div>
    <? wp_reset_postdata() ?>
</div>
<? get_footer() ?>