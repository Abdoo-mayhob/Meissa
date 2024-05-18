<header class="py-3">
    <div class="container-fluid">
        <div class="row main-header-row">
            <div class="col-lg-2 col-3">
                <a href="<?=SITE_URL?>">
                    <img width="75" height="75" src="<?=meissa_get_logo_url()?>" alt="Site Logo" class="header-logo">
                </a>
            </div>
            <div class="col-lg-4 col-8">
                <? get_search_form() ?>
            </div>
            <div class="col-lg-6 menu-col">
                <? wp_nav_menu(['theme_location' => 'header-menu', 'container_class' => 'header-menu-container'] ) ?>
            </div>
        </div>
    </div>
</header>