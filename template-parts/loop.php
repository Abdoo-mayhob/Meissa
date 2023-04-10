
<a href="<?=the_permalink()?>" class="loop row">
    <div class="col-6">
        <!--img src="<?=SITE_URL .'/wp-content/themes/meissa/single-image.jpg'?>" alt="post preview" width="250" height="130" -->
        <? the_post_thumbnail('post-thumbnail', ['class' => '', 'title' => get_the_title()]) ?>
    </div>
    <div class="col-6 info">
        <h3><?the_title()?></h3>
        <span><?=get_the_date()?></span>
    </div>
</a>