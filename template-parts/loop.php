
<a href="<?=the_permalink()?>" class="loop row">
    <div class="col-4">
    <? the_post_thumbnail('post-thumbnail', ['class' => '', 'title' => get_the_title()]) ?>
    </div>
    <div class="col info">
        <h3><?the_title()?></h3>
        <span><?=get_the_date()?></span>
    </div>
</a>