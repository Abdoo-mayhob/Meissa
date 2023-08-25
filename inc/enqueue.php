<?php
/*
 *
 * Scripts and Styles Handeling.
 * 
 */

add_action( 'wp_enqueue_scripts', 'meissa_theme_scripts' );
function meissa_theme_scripts() {

	// Inlining Critical Css
	// wp_enqueue_style( 'meissa-style-reset', get_stylesheet_directory_uri() . '/css/css-reset.css', [], '1.1');
	// wp_enqueue_style( 'bootstrap-grid-rtl', get_stylesheet_directory_uri() . '/css/bootstrap-grid.rtl.min.css', [], '5.3.0');
	// wp_enqueue_style( 'meissa-style', get_stylesheet_uri(), [], null);
	// wp_enqueue_style('dashicons');
	$css_files_to_inline = [
		'meissa-style-reset' => get_template_directory() . '/css/css-reset.css',
		'bootstrap-grid-rtl' => get_template_directory() . '/css/bootstrap-grid.rtl.min.css',
		'meissa-style' 		 => get_template_directory() . '/style.css',
		'meissa-dashicons' 	 => get_template_directory() . '/css/meissa-dashicons.min.css'
	];

	foreach($css_files_to_inline as $handle => $path){
		meissa_inline_css_file($path, $handle);
	}

	// wp_enqueue_style( 'meissa-font-tajawal', "https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500&display=swap", [], null);


	// This will also include jquery (without jquery migrate) 
    wp_enqueue_script('meissa-js', get_stylesheet_directory_uri() . '/js/main.js', [], '1.2');

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
		wp_enqueue_script('advanced-line-clamp-js', get_stylesheet_directory_uri() . '/js/advanced-line-clamp.js', [], '1.1');
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

function meissa_inline_css_file($css_file_path, $handle){
	// Minify on the fly
	ob_start();
	require_once($css_file_path); // Note that file_get_contents will load https (maybe cached) files
	$buffer = ob_get_clean();
	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    $buffer = str_replace(': ', ':', $buffer);
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

	// Add as inline by the recommended method
	wp_register_style( $handle, false );
	wp_enqueue_style( $handle );
	wp_add_inline_style( $handle, $buffer );
}