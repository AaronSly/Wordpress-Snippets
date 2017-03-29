<?php 
/*Plugin Name: ##ADD SITE NAME HERE## Custom Post Types
Description: This plugin registers the various custom post types for ##ADD SITE NAME HERE## and contains all the necessary template files. It also makes some changes to URLs for Main Categories.
Version: 1.0
Author: Aaron Sly
Author URI: http://www.aaronsly.com
License: GPLv2
*/

/**
 *Change as needed and copy paste where directed to create another CPT
 */
function create_post_types() {
	// set up taxonomy - COPY FROM HERE
	register_taxonomy(
		'news_categories',
		'news',
		array(
			'label' => __( 'News Categories' ),
			'rewrite' => array( 'slug' => 'news-categories' ),
      'hierarchical' => true,
      'query_var' => true,
		)
	);
	// set up labels
	$labels = array(
 		'name' => 'News',
    	'singular_name' => 'News',
    	'menu_name' => 'News',
    );
    //register post type
	register_post_type( 'news', array(
		'labels' => $labels,
 		'public' => true,
		'show_in_menu' => true,
		'supports' => array( 'title', 'editor', 'excerpt', 'page-attributes', 'post-formats'),
		'exclude_from_search' => false,
		'capability_type' => 'post',
		'hierarchical' => true,
		'taxonomies' => array('news_categories'),
		'menu_position' => 5,
		'menu_icon' => 'dashicons-megaphone',
		'rewrite' => array( 'slug' => 'news' ),
		)
	);// TO HERE

}
add_action( 'init', 'create_post_types' );

/**
 * Remove the slug from published post permalinks. Only affect our custom post type, though.
 */
function remove_cpt_slug( $post_link, $post, $leavename ) {
 
    if ( 'main_categories' != $post->post_type || 'publish' != $post->post_status ) {
        return $post_link;
    }
 
    $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
 
    return $post_link;
}
add_filter( 'post_type_link', 'remove_cpt_slug', 10, 3 );

/**
 * Have WordPress match postname to any of our public post types (post, page, race)
 * All of our public post types can have /post-name/ as the slug, so they better be unique across all posts
 * By default, core only accounts for posts and pages where the slug is /post-name/
 */
function parse_request_trick( $query ) {
 
    // Only noop the main query
    if ( ! $query->is_main_query() )
        return;
 
    // Only noop our very specific rewrite rule match
    if ( 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
        return;
    }
 
    // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
    if ( ! empty( $query->query['name'] ) ) {
        $query->set( 'post_type', array( 'post', 'page', 'main_categories' ) );
    }
}
add_action( 'pre_get_posts', 'parse_request_trick' );

/**
 *Get custom post type templates from plugin instead of theme
 */
/*function get_custom_post_type_template($single_template) {
     global $post;

     if ($post->post_type == 'my_post_type') {
          $single_template = dirname( __FILE__ ) . '/templates/post-type-template.php';
     }
     return $single_template;
}
add_filter( 'single_template', 'get_custom_post_type_template' );
*/
?>
