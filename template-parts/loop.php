
<a href="<?=the_permalink()?>" class="loop row">
    <div class="col-4" 
        <? meissa_bg_img(meissa_post_thumb_url(),'130px')?>>
    </div>
    <div class="col info">
        <h3><?the_title()?></h3>
        <span><?=get_the_date()?></span>
    </div>
</a>