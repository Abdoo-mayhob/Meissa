<?
/* Template Name: 404-Template */
get_header() 
?>
<main id="particles-js" class="particles-js">
    <div class="content">
        <h1 class="title">لم يتم العثور على الصفحة 😐</h1>
        <h2 class="sub-title">استمتع بتأثير الparticles بدلاً عن ذلك 😁</h2>
        <a href="<?=SITE_URL?>" class="back-to-home">عودة إلى ميسان</a>
    </div>
    <!-- div class="seperator"></div -->
    <div class="container-fluid py-5 latest-articles">
        <div class="row pb-4">
            <h2>أو تصفح آخر مقالاتنا</h2>
        </div>
        <div class="row">
        <? $latest_posts = meissa_get_latest_posts(); ?>
        <? while (  $latest_posts->have_posts() ):  $latest_posts->the_post();?>
            <article class="col-lg-2 col-md-4">
                <? get_template_part('template-parts/loop','vert') ?>
            </article>
        <? endwhile ?>
        </div>
    </div -->  
</main>
<? get_footer() ?>
