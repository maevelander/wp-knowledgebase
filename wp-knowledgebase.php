<?php
/*
	Plugin Name: WP Knowledgebase
	Plugin URI: http://wordpress.org/plugins/wp-knowledgebase
	Description: Simple and flexible knowledgebase plugin for WordPress
	Author: Enigma Plugins
	Version: 1.2.0
	Author URI: http://enigmaplugins.com
	Requires at least: 2.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'KBE_PLUGIN_VERSION', '1.2.0' );

//=========> Create language folder
function kbe_plugin_load_textdomain() {

	$locale = apply_filters( 'plugin_locale', get_locale(), 'wp-knowledgebase' );

	// Load textdomain
	load_textdomain( 'wp-knowledgebase', WP_LANG_DIR . '/wp-knowledgebase/wp-knowledgebase-' . $locale . '.mo' );
	load_plugin_textdomain( 'wp-knowledgebase', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}
add_action( 'init', 'kbe_plugin_load_textdomain' );

//  Require File kbe_articles.php
require 'includes/kbe-articles.php';
require 'includes/kbe-template-functions.php';
require 'includes/kbe-core-functions.php';

//  Require Category Widget file
require 'includes/widgets/kbe-widget-category.php';
//  Require Articles Widget file
require 'includes/widgets/kbe-widget-article.php';
//  Require Search Articles Widget file
require 'includes/widgets/kbe-widget-search.php';
//  Require Tags Widget file
require 'includes/widgets/kbe-widget-tags.php';


// Include admin file(s)
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	require 'includes/admin/kbe-admin-functions.php';
}

// Get Page Title
global $wpdb;
$getSql = $wpdb->get_results("Select ID From $wpdb->posts Where post_content Like '%[kbe_knowledgebase]%' And post_type <> 'revision'");

foreach($getSql as $getRow) {
    $pageId = $getRow->ID;
}

$kbe_settings = get_option('kbe_settings');

//=========> Define plugin path
define( 'WP_KNOWLEDGEBASE', plugin_dir_url( __FILE__ ) );
define( 'KBE_ARTICLE_QTY', $kbe_settings['kbe_article_qty'] );
define( 'KBE_PLUGIN_SLUG', $kbe_settings['kbe_plugin_slug'] );
define( 'KBE_SEARCH_SETTING', $kbe_settings['kbe_search_setting'] );
define( 'KBE_BREADCRUMBS_SETTING', $kbe_settings['kbe_breadcrumbs_setting'] );
define( 'KBE_SIDEBAR_HOME', $kbe_settings['kbe_sidebar_home'] );
define( 'KBE_SIDEBAR_INNER', $kbe_settings['kbe_sidebar_inner'] );
define( 'KBE_COMMENT_SETTING', $kbe_settings['kbe_comments_setting'] );
define( 'KBE_BG_COLOR', $kbe_settings['kbe_bgcolor'] );
define( 'KBE_LINK_STRUCTURE', $kbe_settings['permalink_structure'] );
define( 'KBE_POST_TYPE', 'kbe_knowledgebase' );
define( 'KBE_POST_TAXONOMY', 'kbe_taxonomy' );
define( 'KBE_POST_TAGS', 'kbe_tags' );
define( 'KBE_PAGE_TITLE', $pageId );


require 'includes/migrations/class-abstract-migration.php';
require 'includes/migrations/migration-install.php';
