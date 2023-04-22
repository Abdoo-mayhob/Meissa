<?php
/*
 *
 * Theme Setup.
 * Including Customizer options, Theme Supports, Menus..
 * in addition to wp core optimization
 */

// --------------------------------------------------------------------------------------
// Theme Support Setup

add_action( 'after_setup_theme', 'meissa_theme_setup' );
function meissa_theme_setup() {

    // ------------------------------------------------------
    // Make sure that new uploaded media are only 2 sizes: the org and thumbnail

    // This Generates a totaly new size for somereason
    // set_post_thumbnail_size(250,130);

    // This will remove all non default image sizes. 
    // Defaults are : thumbnail, medium, medium_large, large
    foreach ( get_intermediate_image_sizes() as $size ) {
        remove_image_size( $size );
    }

    // Remove the medium default size
    update_option( 'medium_size_w', 0 );
    update_option( 'medium_size_h', 0 );

    // Remove the medium_large default size
    update_option( 'medium_large_size_w', 0 );
    update_option( 'medium_large_size_h', 0 );

    // Remove the large default size
    update_option( 'large_size_w', 0 );
    update_option( 'large_size_h', 0 );

    // Upadte the Thumbnail default size
    update_option( 'thumbnail_size_w', 250 );
    update_option( 'thumbnail_size_h', 130 );


    // Show excerpt field in pages
	add_post_type_support( 'page', 'excerpt' );

    // Show featured image media selector
	add_theme_support( 'post-thumbnails' ); 

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
    add_theme_support( 'title-tag' );

    // ------------------------------------------------------
    // Create Featured Posts Tag Setup
    if( !term_exists( 'مقالات مختارة', 'post_tag' ) ) {
        wp_insert_term( 'مقالات مختارة', 'post_tag' );
    }

    // ------------------------------------------------------
    // Menus Setup
    register_nav_menu('header-menu', 'Header Menu' );
    register_nav_menu('footer-menu', 'Footer Menu' );
}


// ------------------------------------------------------------------------------------------------
// Optimization Related Codes.

// Remove some unwanted wp default bloat styles
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

// ------------------------------------------------------
// Remove comments completely
add_action('admin_init', 'remove_comments_completely');
function remove_comments_completely () {
    // Redirect any user trying to access comments page
    global $pagenow;

    if ($pagenow === 'edit-comments.php') {
        wp_safe_redirect(admin_url());
        exit;
    }

    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
};

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});


// --------------------------------------------------------------------------------------
// Customizer API Setup

add_action( 'customize_register', 'meissa_theme_customizer' );
function meissa_theme_customizer( $wp_customize ) {
	$wp_customize->add_panel( 'meissa_theme_setup_panel', [
        'title'      => __( 'Meissa Theme Options', 'mytheme' ),
        'priority'   => 30,
    ] );

	// Footer Logo Section
    $wp_customize->add_section( 'meissa_footer_logo_section' , [
        'title'      => __( 'Footer Logo', 'meissa' ),
        'priority'   => 30,
		'panel'      => 'meissa_theme_setup_panel',
    ] );

    $wp_customize->add_setting( 'meissa_footer_logo' );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'meissa_footer_logo', [
        'label'    => __( 'Footer Logo', 'meissa' ),
        'section'  => 'meissa_footer_logo_section',
        'settings' => 'meissa_footer_logo',
    ] ) );

	// Social Links Section
	$wp_customize->add_section( 'meissa_social_links_section' , [
        'title'      => __( 'Social Media Links', 'meissa' ),
        'priority'   => 20,
        'panel'      => 'meissa_theme_setup_panel',
    ] );

    $wp_customize->add_setting( 'meissa_facebook_link' );
    $wp_customize->add_setting( 'meissa_instagram_link' );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'meissa_facebook_link', [
        'label'       => __( 'Facebook Link', 'meissa' ),
        'section'     => 'meissa_social_links_section',
        'settings'    => 'meissa_facebook_link',
        'type'        => 'url',
    ] ) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'meissa_instagram_link', [
        'label'       => __( 'Instagram Link', 'meissa' ),
        'section'     => 'meissa_social_links_section',
        'settings'    => 'meissa_instagram_link',
        'type'       => 'url',
    ] ) );

}
