<?php 

define('SITE_URL' , site_url());
define('MEISSA_NEWSLETTER_SUBS_TABLE' , 'meissa_newsletter_subs');


add_action( 'after_setup_theme', 'meissa_theme_setup' );
function meissa_theme_setup() {

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

// Remove some unwanted wp default bloat styles
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

function meissa_register_menus() {
    register_nav_menu('header-menu',__( 'Header Menu' ));
    register_nav_menu('footer-menu',__( 'Footer Menu' ));
}
add_action( 'init', 'meissa_register_menus' );


// ------------------------------------------------------------------------------------------------
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
// --------------------------------------------------------------------------------

function meissa_breadcrumb(){
    if ( function_exists('yoast_breadcrumb') ) {
        return yoast_breadcrumb( '<div id="breadcrumbs"','</div>', false);
    }
    else {
        return "ACTIVATE YOAST SEO!";
    }
}


function meissa_get_related_posts(){
    return new WP_Query([
        'category__in' => get_the_category(),
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


function meissa_get_logo_url(){
    return esc_url( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0] );
}


add_filter( 'get_the_archive_title', 'remove_wp_default_arhive_title_prefix', 10, 3 );
function remove_wp_default_arhive_title_prefix( $title, $original_title, $prefix){
	return $original_title;
}

add_action('init', 'meissa_newsletter_subs_init_db');
function meissa_newsletter_subs_init_db() {
	global $wpdb;

	$table_name = $wpdb->prefix . MEISSA_NEWSLETTER_SUBS_TABLE;
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		email varchar(255) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

}

add_action( 'wp_ajax_meissa_load_more_posts_archive', 'meissa_load_more_posts' );
add_action( 'wp_ajax_nopriv_meissa_load_more_posts_archive', 'meissa_load_more_posts' );
function meissa_load_more_posts() {

	// Get the same used wp_query and fetch the next page of it
	// I already know the risks of query_posts() but here it's fine

	$paged = $_POST['page'];
	$org_wp_query_vars = json_decode( stripslashes( $_POST['org_wp_query_vars'] ), true );

	// echo '<pre style="direction: ltr;">';var_dump($org_wp_query_vars);echo'</pre>';
	$org_wp_query_vars['paged'] = $paged;
	$more_posts = new WP_Query($org_wp_query_vars);

	if($more_posts->have_posts() === false)
		die('0');

	while ( $more_posts->have_posts() ): $more_posts->the_post();?>
		<div class="col-md-6">
			<? get_template_part('template-parts/loop','excerpt') ?>
		</div>
	<?
	endwhile ;
	die;
}

add_action( 'wp_ajax_meissa_newsletter_subs_add_sub', 'meissa_newsletter_subs_add_sub' );
add_action( 'wp_ajax_nopriv_meissa_newsletter_subs_add_sub', 'meissa_newsletter_subs_add_sub' );
function meissa_newsletter_subs_add_sub() {

	// Get Email and Validate
	$email = $_POST['email'];
	$email = is_email(sanitize_email($email));
	if($email === false){
		$reponse = [
			"status"  => 403,
			"message" => "هذا الايميل غير صالح, حاول بايميل اّخر",
			"email"   => $email,
			"debug"   => 'email validation faild'
		];
		echo json_encode($reponse);
		die;
	}

	// Check if email already exits
	global $wpdb;
	$table_name = $wpdb->prefix . MEISSA_NEWSLETTER_SUBS_TABLE;
	$query = "SELECT * FROM {$table_name} WHERE email = '$email'";
	$result = $wpdb->get_results($query);
	if(count($result) !== 0){
		$reponse = [
			"status"  => 409,
			"message" => "هذا الايميل موجود مسبقاً !",
			"email"   => $email
		];
		echo json_encode($reponse);
		die;
	}

	$debug = $wpdb->insert( 
		$table_name, 
		[
			'time' => current_time( 'mysql' ), 
			'email' => $email, 
		] 
	);
	$reponse = [
		"status"  => 200,
		"message" => "تم التسجيل في النشرة البريدية",
		"email"   => $email,
		"debug"   => $debug
	];
	echo json_encode($reponse);
	die;
}


/*
function meissa_newsletter_subs_remove_sub() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'liveshoutbox';

	$wpdb->insert( 
		$table_name, 
		[
			'time' => current_time( 'mysql' ), 
			'email' => $welcome_name, 
		] 
	);
}*/