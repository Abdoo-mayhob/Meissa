<a href="<?=the_permalink()?>" class="loop loop-vert-excerpt d-block">
    <? the_post_thumbnail('post-thumbnail', ['class' => '', 'title' => get_the_title()]) ?>
    <h3><? the_title() ?></h3>
        <p class="excerpt advanced-line-clamp">
            <?= get_the_excerpt() ?>
        </p>
</a>