<?php 
// Used in footer.php
$GLOBALS['start_ms']  = microtime(true);

// Globals
define('SITE_URL' , site_url());
define('THEME_URI' , get_stylesheet_directory_uri());


require_once get_template_directory() . "/inc/theme-setup.php";
require_once get_template_directory() . "/inc/most-read.php";
require_once get_template_directory() . "/inc/newsletters.php";
require_once get_template_directory() . "/inc/enqueue.php";

// Required Plugins
// Note to Devs: This has almost no impact on performance
require_once get_template_directory() . "/inc/class-tgm-plugin-activation.php";

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
        'post__not_in' => [get_the_ID()],
        'no_found_rows' => true, // Ignores pagination, Increases Performance
    ]);
}

function meissa_get_featured_posts($number_of_posts){
    return new WP_Query([
        'tag' => 'مقالات-مختارة',
        'posts_per_page' => (string) $number_of_posts,
        'post__not_in' => [get_the_ID()],
        // 'no_found_rows' => true, // Ignores pagination, Increases Performance
    ]);
}


function meissa_get_logo_url($size = 'full'){
    return esc_url( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), $size )[0] );
}

// Used to help display bg img instead of <img> with minimal syntax
function meissa_bg_img($image_path, $height = '400px'){
    echo 'style="background-image: url(' . $image_path . ');height:' . $height . '"';
}
function meissa_post_thumb_url(){
    // return get_the_post_thumbnail_url(get_the_ID(),'thumbnail');
    // Both work, But this is a bit less code in wp core
    return  wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail' )[0];
}


// --------------------------------------------------------------------------------------
// 

add_filter( 'get_the_archive_title', 'remove_wp_default_arhive_title_prefix', 10, 3 );
function remove_wp_default_arhive_title_prefix( $title, $original_title, $prefix){
	return $original_title;
}


// --------------------------------------------------------------------------------------
// Ajax

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

