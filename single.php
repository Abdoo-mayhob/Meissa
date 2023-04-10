<? get_header() ?>

<div class="container py-4">
    <div class="row content-row">
        <main class="col-md-7">
            <?= meissa_breadcrumb()?>
            <h1 class="title">
                <? the_title() ?>
            </h1>
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
        <side class="col-md-5">
            <h2>sidebar</h2>
        </side>
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