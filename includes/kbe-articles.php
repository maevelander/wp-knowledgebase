<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
============
	Article Post type
============
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

// Article taxonomy
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
		'show_ui'           => 	true,
		'query_var'         => 	true,
		'rewrite'           => 	array( 'slug' => 'knowledgebase_category', 'with_front' => true )
	) );
}
add_action( 'init', 'kbe_taxonomies', 0 );

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
		'show_ui'      =>  true,
		'query_var'    =>  true,
		'rewrite'      =>  array( 'slug' => 'knowledgebase_tags', 'with_front' => true ),
	) );
}
add_action( 'init', 'kbe_custom_tags', 0 );

function kbe_set_post_views( $postID ) {
	$count_key = 'kbe_post_views_count';
	$count     = get_post_meta( $postID, $count_key, true );

	if ( $count=='' ) {
		$count = 1;
		delete_post_meta( $postID, $count_key );
		add_post_meta( $postID, $count_key, '1' );
	} else {
		$count++;
		update_post_meta( $postID, $count_key, $count );
	}
}

//To keep the count accurate, lets get rid of prefetching
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );

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

function kbe_edit_columns( $columns ) {
	$columns = array(
		'cb'      => 	"<input type=\"checkbox\" />",
		'title'   => 	__( 'Title', 'wp-knowledgebase' ),
		'author'  => 	__( 'Author', 'wp-knowledgebase' ),
		'cat'     => 	__( 'Category', 'wp-knowledgebase' ),
		'tag'     => 	__( 'Tags', 'wp-knowledgebase' ),
		'comment' => 	__( 'Comments', 'wp-knowledgebase' ),
		'views'   => 	__( 'Views', 'wp-knowledgebase' ),
		'date'    => 	__( 'Date', 'wp-knowledgebase' )
	);
	return $columns;
}
add_filter( 'manage_edit-kbe_knowledgebase_columns', 'kbe_edit_columns' );

function kbe_custom_columns( $column ) {
	global $post;
	switch ( $column ) {
		case 'title':
			the_title();
		break;
		case 'author':
			the_author();
		break;
		case 'cat':
			echo get_the_term_list( $post->ID, 'kbe_taxonomy', ' ', ', ', '' );
		break;
		case 'tag':
			echo get_the_term_list( $post->ID, 'kbe_tags', ' ', ', ', '' );
		break;
		case 'comment':
			comments_number( __( 'No Comments', 'wp-knowledgebase' ), __( '1 Comment', 'wp-knowledgebase' ), __( '% Comments', 'wp-knowledgebase' ) );
		break;
		case 'views':
			$views = get_post_meta( $post->ID, 'kbe_post_views_count', true );
			if ( $views ) {
				echo $views . __( ' Views', 'wp-knowledgebase' );
			} else {
				echo __( 'No Views', 'wp-knowledgebase' );
			}
		break;
		case 'date':
			the_date();
		break;
	}
}
add_action( 'manage_posts_custom_column', 'kbe_custom_columns' );
