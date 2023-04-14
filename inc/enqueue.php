<?php
/*
 *
 * Scripts and Styles Handeling.
 * 
 */

add_action( 'wp_enqueue_scripts', 'meissa_theme_scripts' );
function meissa_theme_scripts() {
	wp_enqueue_style( 'bootstrap-grid-rtl', get_stylesheet_directory_uri() . '/css/bootstrap-grid.rtl.min.css', [], '5.3.0');
	wp_enqueue_style( 'meissa-style-reset', get_stylesheet_directory_uri() . '/css/css-reset.css', [], '1.1');
	wp_enqueue_style( 'meissa-style', get_stylesheet_uri(), [], null);
	wp_enqueue_style( 'meissa-font-tajawal', "https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500&display=swap", [], null);
	wp_enqueue_style( 'dashicons');
    wp_enqueue_script('meissa-js', get_stylesheet_directory_uri() . '/js/main.js', ['jquery'], '1.1');
	
	global $wp_query;
	wp_localize_script( 'meissa-js', 'meissa_globals', [ 
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'current_wp_query_vars' => json_encode( $wp_query->query_vars ),

	]);
}