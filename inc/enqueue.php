<?php
/*
 *
 * Scripts and Styles Handeling.
 * 
 */

add_action( 'wp_enqueue_scripts', 'meissa_theme_scripts' );
function meissa_theme_scripts() {

	// Inlining Critical Css (and fonts to avoid FOUT)
	// wp_enqueue_style( 'meissa-style-reset', get_stylesheet_directory_uri() . '/css/css-reset.css', [], '1.1');
	$MEISSA_CSS = file_get_contents( get_stylesheet_directory_uri() . '/css/css-reset.css');
	wp_register_style( 'meissa-style-reset', false );
	wp_enqueue_style( 'meissa-style-reset' );
	wp_add_inline_style( 'meissa-style-reset', $MEISSA_CSS );

	// wp_enqueue_style( 'bootstrap-grid-rtl', get_stylesheet_directory_uri() . '/css/bootstrap-grid.rtl.min.css', [], '5.3.0');
	$MEISSA_CSS = file_get_contents( get_stylesheet_directory_uri() . '/css/bootstrap-grid.rtl.min.css');
	wp_register_style( 'bootstrap-grid-rtl', false );
	wp_enqueue_style( 'bootstrap-grid-rtl' );
	wp_add_inline_style( 'bootstrap-grid-rtl', $MEISSA_CSS );
	
	// wp_enqueue_style( 'meissa-style', get_stylesheet_uri(), [], null);
	$MEISSA_CSS = file_get_contents( get_stylesheet_uri());
	wp_register_style( 'meissa-style', false );
	wp_enqueue_style( 'meissa-style' );
	wp_add_inline_style( 'meissa-style', $MEISSA_CSS );


	// wp_enqueue_style( 'meissa-font-tajawal', "https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500&display=swap", [], null);
	wp_enqueue_style('dashicons');

	// This will also include jquery (without jquery migrate) 
    wp_enqueue_script('meissa-js', get_stylesheet_directory_uri() . '/js/main.js', ['jquery-core'], '1.1');

	//
	if(defined('WP_DEBUG') && true === WP_DEBUG){ 
		wp_localize_script( 'meissa-js', 'MEISSA_WP_DEBUG', [ 
			'is_on' => true,
		]);
	}

	// Only Load the following scripts on pages containing "Load More" Button
	// is_page(MEISSA_MOST_READ_PAGE_NAME) will not work here cuz we modified the default wp_query
	if(is_archive() || get_the_title() == MEISSA_MOST_READ_PAGE_NAME){ 
		wp_enqueue_script('load-more-js', get_stylesheet_directory_uri() . '/js/load-more.js', ['jquery'], '1.1');
		global $wp_query;
		wp_localize_script( 'load-more-js', 'meissa_globals', [ 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'current_wp_query_vars' => json_encode( $wp_query->query_vars ),
		]);
	}
	if(is_404()){ 
		wp_enqueue_script('particles-js', get_stylesheet_directory_uri() . '/js/particles.min.js',[], '2.0',true);
		wp_enqueue_script('404-js', get_stylesheet_directory_uri() . '/js/404.js',['particles-js'], '1.0',true);
	}
}