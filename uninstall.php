<?php
//if uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

$options = get_option( 'kbe_wipe_uninstall' );
if ( ! isset( $options ) || false == $options ) {
	return;
}


global $wpdb;

$kbe_tbl_prefix = $wpdb->prefix;

//=========> Delete Plugin Settings From options Table
delete_option( 'kbe_settings' );
delete_option( 'kbe_bgcolor' );
delete_option( 'kbe_plugin_slug' );
delete_option( 'kbe_article_qty' );
delete_option( 'kbe_sidebar_home' );
delete_option( 'kbe_sidebar_inner' );
delete_option( 'kbe_search_setting' );
delete_option( 'kbe_comments_setting' );
delete_option( 'kbe_taxonomy_children' );
delete_option( 'kbe_breadcrumbs_setting' );
delete_option( 'widget_kbe_tags_widgets' );
delete_option( 'widget_kbe_search_widget' );
delete_option( 'widget_kbe_article_widget' );
delete_option( 'widget_kbe_category_widget' );

//=========> Delete `terms_order` Column From trms Table
$wpdb->query( 'ALTER TABLE ' . $kbe_tbl_prefix . 'terms DROP COLUMN `terms_order`' );

//=========> Get Knowledgebase page and Delete all relivent Data
$kbe_get_page = $wpdb->get_results( 'Select * From ' . $wpdb->prefix . "posts
									Where post_content like '%[kbe_knowledgebase]%'
									and post_type = 'page'" );

foreach ( $kbe_get_page as $get_page ) {
	$kbe_page_ID = $get_page->ID;

	//Delete all Knowledgebase page Relivent data from `postmeta` Table
	$wpdb->query( 'Delete From ' . $wpdb->prefix . "postmeta Where post_id = $kbe_page_ID" );

	//Delete all Knowledgebase page Child data from `posts` Table
	$wpdb->query( 'Delete From ' . $wpdb->prefix . "posts Where post_parent = $kbe_page_ID" );

	//Delete Knowledgebase page from `posts` Table
	$wpdb->query( 'Delete From ' . $wpdb->prefix . "posts Where ID = $kbe_page_ID" );
}

//=========> Get all Images of `kbe_knowledgebase` post type and Delete all Images Data
$kbe_get_post_images = $wpdb->get_results( 'Select * From ' . $wpdb->prefix . "posts Where post_type = 'kbe_knowledgebase'" );

$kbe_upload_dir = wp_upload_dir();

foreach ( $kbe_get_post_images as $get_post_images ) {
	$kbe_posts_img_ID = $get_post_images->ID;

	$kbe_post_imgs_qry = $wpdb->get_results( 'Select * From ' . $wpdb->prefix . "posts
											Where post_parent = $kbe_posts_img_ID
											And post_type = 'attachment'
											And post_mime_type = 'image/jpeg'" );
	foreach ( $kbe_post_imgs_qry as $get_post_img ) {
		$kbe_img_ID = $get_post_img->ID;

		// Extract path from images
		$kbe_img_path      = get_post_meta( $kbe_img_ID, '_wp_attached_file', true );
		$kbe_main_img_name = substr( $kbe_img_path, strrpos( $kbe_img_path, '/' )+1 );
		$kbe_sub_path      = substr( $kbe_img_path, 0, strrpos( $kbe_img_path, '/' ) );

		$kbe_img_meta = get_post_meta( $kbe_img_ID, '_wp_attachment_metadata', true );

		$kbe_thumbnail      = $kbe_img_meta['sizes']['thumbnail']['file'];
		$kbe_medium         = $kbe_img_meta['sizes']['medium']['file'];
		$kbe_post_thumbnail = $kbe_img_meta['sizes']['post-thumbnail']['file'];

		$kbe_upload_path = $kbe_upload_dir['basedir'];

		unlink( $kbe_upload_path . '/' . $kbe_sub_path . '/' . $kbe_main_img_name );
		unlink( $kbe_upload_path . '/' . $kbe_sub_path . '/' . $kbe_thumbnail );
		unlink( $kbe_upload_path . '/' . $kbe_sub_path . '/' . $kbe_medium );
		unlink( $kbe_upload_path . '/' . $kbe_sub_path . '/' . $kbe_post_thumbnail );

		//Delete all Knowledgebase Posts from `posts` Table
		$wpdb->query( 'Delete From ' . $wpdb->prefix . "postmeta Where post_id = $kbe_img_ID" );
	}
}

//=========> Get all Posts of `kbe_knowledgebase` post type and Delete all relevant Data
$kbe_get_posts = $wpdb->get_results( 'Select * From ' . $wpdb->prefix . "posts Where post_type = 'kbe_knowledgebase'" );

foreach ( $kbe_get_posts as $get_posts ) {
	$kbe_posts_ID = $get_posts->ID;

	//Delete all Comments of `kbe_knowledgebase` posts from `comments` Table
	$wpdb->query( 'Delete From ' . $wpdb->prefix . "comments Where comment_post_ID = $kbe_posts_ID" );

	//Delete all Meta Data of `kbe_knowledgebase` posts from `postmeta` Table
	$wpdb->query( 'Delete From ' . $wpdb->prefix . "postmeta Where post_id = $kbe_posts_ID" );

	//Delete all `kbe_knowledgebase` posts Realtion Data from `term_relationships` Table
	$wpdb->query( 'Delete From ' . $wpdb->prefix . "term_relationships Where object_id = $kbe_posts_ID" );

	//Delete all `kbe_knowledgebase` Child data from `posts` Table
	$wpdb->query( 'Delete From ' . $wpdb->prefix . "posts Where post_parent = $kbe_posts_ID" );

	//Delete all Knowledgebase Posts from `posts` Table
	$wpdb->query( 'Delete From ' . $wpdb->prefix . "posts Where ID = $kbe_posts_ID" );
}

//=========> Delete All Categories and Tags of Knowledgebase
$kbe_get_terms = $wpdb->get_results( 'Select kbe_term.*, kbe_tax.*
									From ' . $wpdb->prefix . 'terms As kbe_term
									Inner join ' . $wpdb->prefix . "term_taxonomy As kbe_tax
									On kbe_term.term_id = kbe_tax.term_id
									Where kbe_tax.taxonomy = 'kbe_taxonomy'
									Or kbe_tax.taxonomy = 'kbe_tags'" );

foreach ( $kbe_get_terms as $get_term ) {
	$kbe_term_ID = $get_term->term_id;

	$wpdb->query( 'Delete From ' . $wpdb->prefix . "terms Where term_id = $kbe_term_ID" );
}

//=========> Delete All Taxonomies and Tags of Knowledgebase
$wpdb->query( 'Delete From ' . $wpdb->prefix . "term_taxonomy Where taxonomy = 'kbe_taxonomy'" );

$wpdb->query( 'Delete From ' . $wpdb->prefix . "term_taxonomy Where taxonomy = 'kbe_tags'" );
