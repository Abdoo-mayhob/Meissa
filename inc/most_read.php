<?php
/*
 *
 * All "Most Read Posts" releated Codes.
 * 
 */

define('MEISSA_POST_VIEWS_COUNT_META_KEY' , 'views');

// --------------------------------------------------------------------------------------
// Getters

function meissa_get_most_read_posts(){
    return new WP_Query([
        'posts_per_page' => '6',
        'no_found_rows' => true, // Ignores pagination, Increases Performance
		'meta_key'      => MEISSA_POST_VIEWS_COUNT_META_KEY,
		'orderby'       => 'meta_value_num',
		'order'         => 'DESC'
    ]);
}

function meissa_get_post_views($post_id) {
	$count = get_post_meta($post_id, MEISSA_POST_VIEWS_COUNT_META_KEY, true);
	return ($count === '' || $count === false) ? 0 : $count;
}


// --------------------------------------------------------------------------------------
// Increase Post Views on Visit

add_action( 'wp_head', 'meissa_set_post_views');
function meissa_set_post_views() {
	global $post;
	$post_id = $post->ID;
	$count = meissa_get_post_views($post_id);
	update_post_meta($post_id, MEISSA_POST_VIEWS_COUNT_META_KEY, ++$count);
}
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
