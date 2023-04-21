<? get_header() ?>
<div class="container py-4">
    <div class="row content-row">
        <main class="col-md-8">
            <?= meissa_get_breadcrumb()?>
            <h1 class="title">
                <? wp_title(); ?>
            </h1>
            <div class="description">
                <? is_archive() ? the_archive_description() : the_excerpt() ?>
            </div>
            <div class="container py-5">
                <div class="row g-5 archive-posts" data-template="vert-excerpt">
                    <? while (have_posts() ): the_post();?>
                        <article class="col-md-6 p-4">
                            <? get_template_part('template-parts/loop', 'vert-excerpt') ?>
                        </article>
                    <? endwhile ?>
                </div>
                <div class="row">
                    <div class="col-12 pt-4">
                        <button role="button" type="button" id="load-more-button">تحميل المزيد</button>
                    </div>
                </div>
            </div>
        </main>
        <aside class="col-md-4">
            <h2>آخر المقالات</h2>
            <? $latest_posts = meissa_get_latest_posts(); ?>
            <? while (  $latest_posts->have_posts() ):  $latest_posts->the_post();?>
                <? get_template_part('template-parts/loop','vert') ?>
            <? endwhile ?>
        </aside>
    </div>
</div>
<? get_footer() ?>