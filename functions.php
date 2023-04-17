<?php 

define('SITE_URL' , site_url());

require_once get_template_directory() . "/inc/most-read.php";
require_once get_template_directory() . "/inc/newsletters.php";
require_once get_template_directory() . "/inc/optimizations.php";
require_once get_template_directory() . "/inc/enqueue.php";

// Required Plugins
// Note to Devs: This has almost no impact on performance
require_once get_template_directory() . "/inc/class-tgm-plugin-activation.php";

// --------------------------------------------------------------------------------------
// Theme Setup

add_action( 'after_setup_theme', 'meissa_theme_setup' );
function meissa_theme_setup() {

	add_post_type_support( 'page', 'excerpt' );
	add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size(250,130);
    add_theme_support( 'custom-logo' );
	add_theme_support(
		'html5',
		[
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
        ]
	);
}

add_action( 'init', 'meissa_register_menus' );
function meissa_register_menus() {
    register_nav_menu('header-menu', 'Header Menu' );
    register_nav_menu('footer-menu', 'Footer Menu' );
}


// --------------------------------------------------------------------------------------
// Getters

function meissa_get_breadcrumb(){
    if ( function_exists('yoast_breadcrumb') ) {
        return yoast_breadcrumb( '<div id="breadcrumbs"','</div>', false);
    }
    else {
        return "<!-- Please Install & Activate YOAST SEO -->";
    }
}

function meissa_get_related_posts(){
    return new WP_Query([
        'category__in' => wp_get_post_categories(get_the_ID(),['fields'=>'ids']),
        'post__not_in' => [get_the_ID()],
        'posts_per_page' => '6',
        'no_found_rows' => true, // Ignores pagination, Increases Performance
    ]);
}

function meissa_get_latest_posts(){
    return new WP_Query([
        'posts_per_page' => '6',
        'no_found_rows' => true, // Ignores pagination, Increases Performance
    ]);
}


function meissa_get_logo_url($size = 'full'){
    return esc_url( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), $size )[0] );
}


add_filter( 'get_the_archive_title', 'remove_wp_default_arhive_title_prefix', 10, 3 );
function remove_wp_default_arhive_title_prefix( $title, $original_title, $prefix){
	return $original_title;
}


add_action( 'wp_ajax_meissa_load_more_posts_archive', 'meissa_load_more_posts' );
add_action( 'wp_ajax_nopriv_meissa_load_more_posts_archive', 'meissa_load_more_posts' );
function meissa_load_more_posts() {

	$template_to_use = $_POST['template_to_use'];
	$paged = $_POST['page'];
	$org_wp_query_vars = json_decode( stripslashes( $_POST['org_wp_query_vars'] ), true );
	$org_wp_query_vars['paged'] = $paged;
	$more_posts = new WP_Query($org_wp_query_vars);

	if($more_posts->have_posts() === false)
		die('0');

	while ( $more_posts->have_posts() ): $more_posts->the_post();?>
		<div class="col-md-6">
			<? get_template_part('template-parts/loop', $template_to_use) ?>
		</div>
	<?
	endwhile ;
	die;
}
