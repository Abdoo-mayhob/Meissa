<?php
/*
 *
 * All "Most Read Posts" releated Codes.
 * 
 */

define('MEISSA_POST_VIEWS_COUNT_META_KEY' , 'views');
define('MEISSA_MOST_READ_PAGE_NAME' , 'الأكثر قراءة');


// --------------------------------------------------------------------------------------
// Getters

function meissa_get_most_read_posts(){
	
	$cache_key = CACHE_KEY_PREFIX . '_most_read_q';

	$q = get_transient($cache_key);
	if(!empty($q))
		return $q;
	

    $q = new WP_Query([
        'posts_per_page' => '6',
		'meta_key'      => MEISSA_POST_VIEWS_COUNT_META_KEY,
		'orderby'       => 'meta_value_num',
		'order'         => 'DESC',
        'no_found_rows' => true, // Ignores pagination, Increases Performance
    ]);

	set_transient($cache_key, $q, 1 * DAY_IN_SECONDS);
	return $q;
}

function meissa_get_post_views($post_id) {
	$count = get_post_meta($post_id, MEISSA_POST_VIEWS_COUNT_META_KEY, true);
	return ($count === '' || $count === false) ? 0 : $count;
}


// --------------------------------------------------------------------------------------
// Generate Most Read Page

// add_action( 'init', 'meissa_create_most_read_page' );
// function meissa_create_most_read_page() {
// 	$title = MEISSA_MOST_READ_PAGE_NAME;
// 	$page = get_page_by_path( sanitize_title( $title ), OBJECT, 'page' );
// 	if ( $page ) return;
// 	wp_insert_post([
// 		'post_title'   => $title,
// 		'post_content' => '<!-- This page is generated with code, And cant be deleted. To hide this page convert it to Draft. -->',
// 		'post_type'    => 'page',
// 		'post_status'  => 'publish',
// 		'page_template'=> 'template-parts/page-most-read.php'
// 	]);
// }


// --------------------------------------------------------------------------------------
// Increase Post Views on Visit

add_action('wp_ajax_set_post_views', 'meissa_set_post_views');
add_action('wp_ajax_nopriv_set_post_views', 'meissa_set_post_views');

function meissa_set_post_views() {
    $count = meissa_get_post_views($_POST['post_id']);
    update_post_meta($_POST['post_id'], MEISSA_POST_VIEWS_COUNT_META_KEY, ++$count);
    die($count);
}

// add_action( 'wp_head', 'meissa_set_post_views');
// function meissa_set_post_views() {
// 	// Only Run in pages or posts, in terms $post->ID will be the first post id not the term's
// 	if( ! is_single(get_the_ID()))return;
// 	global $post;
// 	$post_id = $post->ID;
// 	$count = meissa_get_post_views($post_id);
// 	update_post_meta($post_id, MEISSA_POST_VIEWS_COUNT_META_KEY, ++$count);
// }
// keeps the count accurate by removing prefetching
// remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);


// --------------------------------------------------------------------------------------
// Custom Admin Columns & Sorting

add_filter('manage_post_posts_columns', 'meissa_add_postviews_column');
add_filter('manage_page_posts_columns', 'meissa_add_postviews_column');
function meissa_add_postviews_column($defaults) {
	$defaults['views'] =  'Views';
	return $defaults;
}

add_action( 'manage_post_posts_custom_column', 'meissa_add_postviews_column_content', 10, 2);
add_action( 'manage_page_posts_custom_column', 'meissa_add_postviews_column_content', 10, 2);
function meissa_add_postviews_column_content($column_name, $post_id) {
	if ($column_name === 'views' )
		echo get_post_meta($post_id, MEISSA_POST_VIEWS_COUNT_META_KEY, true);
}

add_filter( 'manage_edit-post_sortable_columns', 'meissa_sort_postviews_column');
add_filter( 'manage_edit-page_sortable_columns', 'meissa_sort_postviews_column' );
function meissa_sort_postviews_column( $defaults ) {
	$defaults['views'] = 'views';
	return $defaults;
}

add_action('pre_get_posts', 'meissa_sort_postviews');
function meissa_sort_postviews($query) {
	if ( ! is_admin() ) {
		return;
	}
	$orderby = $query->get('orderby');
	if ( 'views' === $orderby ) {
		$query->set( 'meta_key', 'views' );
		$query->set( 'orderby', 'meta_value_num' );
	}
}
