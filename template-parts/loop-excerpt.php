<a href="<?=the_permalink()?>" class="loop row">
    <? the_post_thumbnail('post-thumbnail', ['class' => '', 'title' => get_the_title()]) ?>
    <h3><? the_title() ?></h3>
        <p class="excerpt advanced-line-clamp" data-loop-url="<?=the_permalink()?>">
            <?= get_the_excerpt() ?>
        </p>
</a>