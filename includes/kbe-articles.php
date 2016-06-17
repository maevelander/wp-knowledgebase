<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Register 'article' post type.
 *
 * Register the main 'article' (actual slug is 'kbe_knowledgebase' post type that the plugin uses.
 *
 * @since 1.0
 */
function kbe_articles() {
	$labels = array(
		'name'               => 	__( 'Knowledgebase', 'wp-knowledgebase' ),
		'singular_name'      => 	__( 'Knowledgebase', 'wp-knowledgebase' ),
		'all_items'          => 	__( 'Articles', 'wp-knowledgebase' ),
		'add_new'            => 	__( 'New Article', 'wp-knowledgebase' ),
		'add_new_item'       => 	__( 'Add New Article', 'wp-knowledgebase' ),
		'edit_item'          => 	__( 'Edit Article', 'wp-knowledgebase' ),
		'new_item'           => 	__( 'New Article', 'wp-knowledgebase' ),
		'view_item'          => 	__( 'View Articles', 'wp-knowledgebase' ),
		'search_items'       => 	__( 'Search Articles', 'wp-knowledgebase' ),
		'not_found'          => 	__( 'Nothing found', 'wp-knowledgebase' ),
		'not_found_in_trash' => 	__( 'Nothing found in Trash', 'wp-knowledgebase' ),
		'parent_item_colon'  => 	''
	);

	$kbe_rewrite = array(
		'slug'       => 	KBE_PLUGIN_SLUG,
		'with_front' => 	true,
		'pages'      => 	false,
		'feeds'      => 	true,
	);

	$args = array(
		'labels'              => 	$labels,
		'public'              => 	true,
		'publicly_queryable'  => 	true,
		'show_ui'             => 	true,
		'query_var'           => 	true,
		'menu_icon'           => 	WP_KNOWLEDGEBASE . 'assets/images/icon-kbe.png',
		'capability_type'     => 	'post',
		'hierarchical'        => 	false,
		'supports'            => 	array( 'title', 'editor', 'thumbnail', 'comments', 'tags', 'revisions', 'author' ),
		'rewrite'             => 	$kbe_rewrite,
		'show_in_menu'        => 	true,
		'show_in_nav_menus'   => 	true,
		'show_in_admin_bar'   => 	true,
		'can_export'          => 	true,
		'has_archive'         => 	true,
		'exclude_from_search' => 	true
	);

	register_post_type( 'kbe_knowledgebase', $args );
}
add_action( 'init', 'kbe_articles' );

/**
 * Register KB taxonomies.
 *
 * Register the 'category' taxonomy for the Articles post type.
 *
 * @since 1.0.0
 */
function kbe_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => 	__( 'Knowledgebase Category', 'wp-knowledgebase' ),
		'singular_name'     => 	__( 'Knowledgebase Category', 'wp-knowledgebase' ),
		'search_items'      => 	__( 'Search Knowledgebase Category', 'wp-knowledgebase' ),
		'all_items'         => 	__( 'All Knowledgebase Categories', 'wp-knowledgebase' ),
		'parent_item'       => 	__( 'Parent Knowledgebase Category', 'wp-knowledgebase' ),
		'parent_item_colon' => 	__( 'Parent Knowledgebase Category:', 'wp-knowledgebase' ),
		'edit_item'         => 	__( 'Edit Knowledgebase Category', 'wp-knowledgebase' ),
		'update_item'       => 	__( 'Update Knowledgebase Category', 'wp-knowledgebase' ),
		'add_new_item'      => 	__( 'Add New Knowledgebase Category', 'wp-knowledgebase' ),
		'new_item_name'     => 	__( 'New Knowledgebase Category Name', 'wp-knowledgebase' ),
		'menu_name'         => 	__( 'Categories', 'wp-knowledgebase' )
	);

	register_taxonomy( 'kbe_taxonomy', array( 'kbe_knowledgebase' ), array(
		'hierarchical'      => 	true,
		'labels'            => 	$labels,
		'singular_label'    => 	__( 'Knowledgebase Category', 'wp-knowledgebase' ),
		'show_admin_column' => 	true,
		'show_ui'           => 	true,
		'query_var'         => 	true,
		'rewrite'           => 	array( 'slug' => 'knowledgebase_category', 'with_front' => true )
	) );
}
add_action( 'init', 'kbe_taxonomies', 0 );

/**
 * Register KB taxonomies.
 *
 * Register the 'tag' taxonomy for the Articles post type.
 *
 * @since 1.0.0
 */
function kbe_custom_tags() {
	$labels = array(
		'name'          =>  __( 'Knowledgebase Tags', 'wp-knowledgebase' ),
		'singular_name' =>  __( 'Knowledgebase Tag', 'wp-knowledgebase' ),
		'search_items'  =>  __( 'Search Knowledgebase Tags', 'wp-knowledgebase' ),
		'all_items'     =>  __( 'All Knowledgebase Tags', 'wp-knowledgebase' ),
		'edit_item'     =>  __( 'Edit Knowledgebase Tag', 'wp-knowledgebase' ),
		'update_item'   =>  __( 'Update Knowledgebase Tag', 'wp-knowledgebase' ),
		'add_new_item'  =>  __( 'Add New Knowledgebase Tag', 'wp-knowledgebase' ),
		'new_item_name' =>  __( 'New Knowledgebase Tag Name', 'wp-knowledgebase' ),
		'menu_name'     =>  __( 'Tags', 'wp-knowledgebase' )
	);

	register_taxonomy( 'kbe_tags', 'kbe_knowledgebase', array(
		'hierarchical' =>  false,
		'labels'       =>  $labels,
		'show_admin_column' => 	true,
		'show_ui'      =>  true,
		'query_var'    =>  true,
		'rewrite'      =>  array( 'slug' => 'knowledgebase_tags', 'with_front' => true ),
	) );
}
add_action( 'init', 'kbe_custom_tags', 0 );

/**
 * Update article view count.
 *
 * Update/set the article view count by adding a count.
 *
 * @since 1.0
 *
 * @param int $postID Post ID to add the count to.
 */
function kbe_set_post_views( $postID ) {
	$count_key = 'kbe_post_views_count';
	$count     = get_post_meta( $postID, $count_key, true );

	if ( $count=='' ) {
		delete_post_meta( $postID, $count_key );
		add_post_meta( $postID, $count_key, 1 );
	} else {
		$count++;
		update_post_meta( $postID, $count_key, $count );
	}
}

/**
 * Get article view count.
 *
 * Get the view count for a article post.
 *
 * @since 1.0
 *
 * @param int $postID Post ID to get the view count for.
 * @return string View count with string text.
 */
function kbe_get_post_views( $postID ) {
	$count_key = 'kbe_post_views_count';
	$count     = get_post_meta( $postID, $count_key, true );

	if ( $count=='' ) {
		delete_post_meta( $postID, $count_key );
		add_post_meta( $postID, $count_key, '1' );
		return '1 View';
	}
	return $count . ' Views';
}

/**
 * Article custom columns.
 *
 * Add custom columns to the Article post type.
 *
 * @since 1.0
 *
 * @param array $existing_columns List of existing columns.
 * @return array List of modified columns.
 */
function kbe_edit_columns( $existing_columns ) {
	$columns = array( 'views' => __( 'Views', 'kbe' ) );
	return array_merge( $existing_columns, $columns );
}
add_filter( 'manage_edit-kbe_knowledgebase_columns', 'kbe_edit_columns', 10 );

/**
 * Fill custom columns.
 *
 * Fill the newly added custom columns with the proper content.
 *
 * @since 1.0.
 *
 * @param string $column Column being processed/output.
 */
function kbe_custom_columns( $column ) {
	global $post;
	switch ( $column ) {
		case 'views':
			$views = get_post_meta( $post->ID, 'kbe_post_views_count', true );
			echo sprintf( _n( '%d view', '%d views', $views, 'wp-knowledgebase' ), $views );
		break;
	}
}
add_action( 'manage_kbe_knowledgebase_posts_custom_column', 'kbe_custom_columns' );

/**
 * Make custom columns sortable.
 *
 * Make the custom added columns sortable.
 *
 * @since 1.2.0
 *
 * @param array $columns List of existing sortable columns.
 * @return mixed List of modified sortable columns.
 */
function kbe_sortable_custom_columns( $columns ) {
	$columns['views'] = 'views';
	return $columns;
}
add_filter( 'manage_edit-kbe_knowledgebase_sortable_columns', 'kbe_sortable_custom_columns' );

/**
 * Actually sort columns.
 *
 * Actually modify the query that sorts the articles to sort them by the new order option.
 *
 * @since 1.2.0
 *
 * @param WP_Query $query Query that is being processed.
 */
function kbe_sort_custom_columns( $query ) {

	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $orderby = $query->get( 'orderby' ) == 'views' && $query->get( 'post_type' ) == 'kbe_knowledgebase' ) {
		$query->set( 'meta_key', 'kbe_post_views_count' );
		$query->set( 'orderby', 'meta_value_num' );
	}

}
add_action( 'pre_get_posts', 'kbe_sort_custom_columns' );