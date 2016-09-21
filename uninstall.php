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

// Delete `terms_order` Column From terms Table
$wpdb->query( "ALTER TABLE {$wpdb->terms} DROP COLUMN `terms_order`" );

// Delete Knowledgebase page
if ( $post_id = get_option( 'kbe_page_id', false ) ) {
	wp_delete_post( $post_id, true );
}
delete_option( 'kbe_page_id' );

// Delete the articles
$kbe_get_posts = $wpdb->get_results( "SELECT ID From {$wpdb->posts} WHERE post_type = 'kbe_knowledgebase' LIMIT 500" );
foreach ( $kbe_get_posts as $post ) {
	wp_delete_post( $post->ID, true );
}

//=========> Delete All Categories and Tags of Knowledgebase
$kbe_get_terms = $wpdb->get_results(
	"SELECT kbe_term.term_id, kbe_tax.term_id
	From {$wpdb->terms} As kbe_term
	Inner join {$wpdb->term_taxonomy} As kbe_tax
	On kbe_term.term_id = kbe_tax.term_id
	WHERE kbe_tax.taxonomy = 'kbe_taxonomy'
	OR kbe_tax.taxonomy = 'kbe_tags'"
);

foreach ( $kbe_get_terms as $term ) {
	$wpdb->query( "DELETE FROM {$wpdb->terms} WHERE term_id = $term->term_id" );
}

//=========> Delete All Taxonomies and Tags of Knowledgebase
$wpdb->query( "DELETE FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'kbe_taxonomy'" );

$wpdb->query( "DELETE FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'kbe_tags'" );
