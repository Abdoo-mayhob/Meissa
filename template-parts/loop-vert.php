
<a href="<?=the_permalink()?>" class="loop loop-vert p-2">
    <? the_post_thumbnail('post-thumbnail', ['class' => '', 'title' => get_the_title()]) ?>
    <h3><?the_title()?></h3>
</a>