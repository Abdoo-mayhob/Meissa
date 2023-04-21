<?php
/*
 *
 * Theme Setup.
 * Including Customizer options, Theme Supports, Menus..
 * 
 */

// --------------------------------------------------------------------------------------
// Theme Support Setup

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
