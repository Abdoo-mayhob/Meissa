<?php get_header() ?>
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1>
                <?= the_title() ?>
            </h1>
        </div>
        <main class="col-12">
            <? the_content() ?>
        </main>
    </div>
</div>

<?php get_footer() ?>