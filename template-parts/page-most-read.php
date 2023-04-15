<? 
/* Template Name: Most-Read-Template */
/* 
 *
 * Notes for Developers:
 * Instead of overwriting the main query with a new query. I tried modifing the org main query early with the add_filter( 'request', ... hook
 * My intentions were to edit the Main query vars so early with the mentiond filter so that I trick wp of thinking that the most-read page is
 *  actually an archive and should load the archive.php templete with the rest of the custom query vars (meta query and etc)
 * But I faild, I even asked GPT-4 and it insisted on using another wp_query and not to early edit the main one. So here I'm :)
 *
 */

$most_read_posts_query =  meissa_get_most_read_posts();
query_posts($most_read_posts_query->query_vars); // Yeeh I know it's not recomended, but it is used fine here.
require_once get_template_directory() . '/archive.php';
?>

