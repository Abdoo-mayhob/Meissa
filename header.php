<!DOCTYPE html>
<html <?php language_attributes()?>>
<head>
    <meta charset="<?php bloginfo('charset')?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php if (is_singular() && pings_open(get_queried_object())): ?>
        <link rel="pingback" href="<?php bloginfo('pingback_url')?>">
    <?php endif?> 
    <?php wp_head();?>
</head>
<body <?php body_class()?>>

<header class="py-3">
        <div class="container-fluid">
            <div class="row main-header-row">
                <div class="col-lg-2 col-3">
                    <a href="<?=SITE_URL?>">
                        <img width="75" height="75" src="<?=get_stylesheet_directory_uri() . '/meissa-assets/logo.png'?>" alt="Site Logo" class="header-logo">
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