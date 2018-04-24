<?php
/*
	Plugin Name: WP Knowledgebase
	Plugin URI: http://wordpress.org/plugins/wp-knowledgebase
	Description: Simple and flexible knowledgebase plugin for WordPress
	Author: Maeve Lander
	Version: 1.1.9
	Author URI: https://profiles.wordpress.org/enigmaweb
	Requires at least: 2.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'KBE_PLUGIN_VERSION', '1.1.8' );

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


//=========> Define plugin path
define( 'WP_KNOWLEDGEBASE', plugin_dir_url( __FILE__ ) );
define( 'KBE_ARTICLE_QTY', get_option( 'kbe_article_qty' ) );
define( 'KBE_PLUGIN_SLUG', get_option( 'kbe_plugin_slug' ) );
define( 'KBE_SEARCH_SETTING', get_option( 'kbe_search_setting' ) );
define( 'KBE_BREADCRUMBS_SETTING', get_option( 'kbe_breadcrumbs_setting' ) );
define( 'KBE_SIDEBAR_HOME', get_option( 'kbe_sidebar_home' ) );
define( 'KBE_SIDEBAR_INNER', get_option( 'kbe_sidebar_inner' ) );
define( 'KBE_COMMENT_SETTING', get_option( 'kbe_comments_setting' ) );
define( 'KBE_BG_COLOR', get_option( 'kbe_bgcolor' ) );
define( 'KBE_LINK_STRUCTURE', get_option( 'permalink_structure' ) );
define( 'KBE_POST_TYPE', 'kbe_knowledgebase' );
define( 'KBE_POST_TAXONOMY', 'kbe_taxonomy' );
define( 'KBE_POST_TAGS', 'kbe_tags' );
define( 'KBE_PAGE_TITLE', kbe_get_knowledgebase_page_id() );


require 'includes/migrations/class-abstract-migration.php';
require 'includes/migrations/migration-install.php';
