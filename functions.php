<?php 

// Globals
define('SITE_URL' , site_url());
define('THEME_URI' , get_stylesheet_directory_uri());
define('CACHE_KEY_PREFIX' , 'meissa_cache');


require_once get_template_directory() . "/inc/theme-setup.php";
require_once get_template_directory() . "/inc/most-read.php";
require_once get_template_directory() . "/inc/newsletters.php";
require_once get_template_directory() . "/inc/enqueue.php";

// Dump Data
function dd($var, $display=false, $msg = ''){
    $display_class = ($display)? 'style="display:block;"' : '';
    echo "<pre $display_class class='dd'>$msg ";var_dump($var);echo'</pre>';
    
}
// Dump Data & Die
function ddd($var, $display=false, $msg = ''){
    dd($var, $display, $msg);
    die;   
}
// Dump Data to debug.log
function ldd($var,$msg = ''){
    error_log($msg . print_r($var,1));
}
// Get Backtrace function call
function get_backtrace(): string{
    $e = new \Exception;
    return $e->getTraceAsString();
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

    $cache_key = CACHE_KEY_PREFIX . '_latest_q';

	$q = get_transient($cache_key);
	if(!empty($q))
		return $q;
	

    $q =  new WP_Query([
        'posts_per_page' => '6',
        'post__not_in' => [get_the_ID()],
        'no_found_rows' => true, // Ignores pagination, Increases Performance
    ]);

    
	set_transient($cache_key, $q, 1 * DAY_IN_SECONDS);
	return $q;
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
    // return esc_url( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), $size )[0] ); // Old WP-Media Png Logo
    return SITE_URL . '/wp-content/uploads/Logo.svg';
}

// Used to help display bg img instead of <img> with minimal syntax
function meissa_bg_img($image_path, $height = '400px'){
    echo 'style="background-image: url(' . $image_path . ');height:' . $height . '"';
}
function meissa_post_thumb_url(){
    // return get_the_post_thumbnail_url(get_the_ID(),'thumbnail');
    // Both work, But this is a bit less code in wp core
    $url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail' ) ;
    return $url[0] ?? meissa_get_logo_url();
     
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
			<?php get_template_part('template-parts/loop', $template_to_use) ?>
		</div>
	<?php
	endwhile ;
	die;
}


// --------------------------------------------------------------------------------------
// Caching

/*
 * Cache the html of the template part. Becuase menus rarly change. 
 * This will save alot of DB Queries
 */ 

function cache_get_template_part($slug, $name = null, $args = [], $skip_cache = false){

    $key = CACHE_KEY_PREFIX . 'tp_' . $slug . $name . implode('_', $args);

    $html = get_transient($key);

    if(!empty($html)){
        echo '<!-- Cached ! -->' . $html;
        return;
    }

    // Set Cache
    ob_start();
    get_template_part($slug, $name, $args);
    $html = ob_get_clean();

    set_transient($key, $html, 1 * MONTH_IN_SECONDS);
    echo $html;

}

/*
 * Clear All Cache when any post is saved.
 * Will be recached on the next load btw.
 */
add_action('init', function () {
    if(!( $_GET['clear_meissa_cache'] ?? false ))return;
    clear_all_meissa_cache();
});

add_action('save_post', function ($post_id, $post, $updating ){
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;
    if ($post->post_status == 'auto-draft')return; // an auto-draft is generated on the hit of "Add New Post"

    clear_all_meissa_cache();

}, 999, 3);

function clear_all_meissa_cache(){
    global $wpdb;
    $prefix = CACHE_KEY_PREFIX;
    $option_name = "%transient_{$prefix}_%";
    $sql = "SELECT `option_name` AS `name`
            FROM  $wpdb->options
            WHERE `option_name` LIKE '$option_name'";

    $menus = $wpdb->get_results($sql);

    foreach($menus as $menu) {
        $option_name = $menu->name;
        delete_transient(str_replace('_transient_', '', $option_name));
    }
}


// --------------------------------------------------------------------------------------
// One Time Scripts

add_action('init', function(){

    $ots = $_GET['ots'] ?? false;

    if(empty($ots))return;


    if($ots == 'terminal_slash'){
        $args = [
            'posts_per_page' => -1,
            'post_type' => 'post',
            'post_status' => 'publish',
            'post__in' => [2937]
        ];
        

        $posts_array = get_posts($args);
        echo "You got total of " . count($posts_array) . " Posts <br>";
        
        foreach ($posts_array as $post) {
            echo "==================================================<br>";
            echo "Checking Post ID {$post->ID} {$post->post_title}:";
            $hrefs = [];
            $content = $post->post_content;
            if(empty($content)) continue;
        
            $dom = new DOMDocument;
            @$dom->loadHTML($content);
            $tags = $dom->getElementsByTagName('a');
            $edited = false;
            echo count($tags) . " Link: <br>";
            foreach ($tags as $tag) {
                $href = $tag->getAttribute('href');
                if(str_contains($href, 'meissa.space') == false)continue;
                if(substr($href, -1) == '/')continue;

                echo " - Editing: "  . $tag->nodeValue . '<br>';
                $newHref = $href . '/';
                $tag->setAttribute('href', $newHref);
                $edited = true;
            }
        
            if($edited){
                $newContent = $dom->saveHTML();
                wp_update_post([
                    'ID' => $post->ID,
                    'post_content' => $newContent
                ]);
                echo " Saving Post.<br>";
            }
        }
    } // if
    
    die;
},0);