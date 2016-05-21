<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Enqueue scripts.
 *
 * Enqueue the required stylesheets and javascripts in the admin.
 *
 * @param string $hook_suffix Current page ID.
 */
function kbe_admin_scripts( $hook_suffix ) {
	// Settings page
	if ( $hook_suffix == 'kbe_knowledgebase_page_kbe_options' ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'cp-script-handle', WP_KNOWLEDGEBASE . '/assets/js/color_picker.js', array( 'wp-color-picker' ), false, true );
	}

	// Order page
	if ( $hook_suffix == 'kbe_knowledgebase_page_kbe_order' ) {
		wp_enqueue_script( 'jquery-ui-sortable' );
	}

	wp_register_style( 'kbe_admin_css', WP_KNOWLEDGEBASE . '/assets/css/kbe-admin-style.css' );
	if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'kbe_knowledgebase' ) {
		wp_enqueue_style( 'kbe_admin_css' );
	}
}
add_action( 'admin_enqueue_scripts', 'kbe_admin_scripts' );

/**
 * Add submenus.
 *
 * Add submenus for the custom added pages.
 *
 * @since 1.0
 */
function kbe_plugin_menu() {
	add_submenu_page( 'edit.php?post_type=kbe_knowledgebase', 'Order', 'Order', 'manage_options', 'kbe_order', 'wp_kbe_order' );
	add_submenu_page( 'edit.php?post_type=kbe_knowledgebase', 'Settings', 'Settings', 'manage_options', 'kbe_options', 'wp_kbe_options' );
}
add_action( 'admin_menu', 'kbe_plugin_menu' );

/**
 * Output order page.
 *
 * Output the HTML for the 'order' page.
 *
 * @since 1.0
 */
function wp_kbe_order() {
	require dirname( __FILE__ ) . '/../kbe-order.php';
}

/**
 * Output settings page.
 *
 * Output the HTML for the settings page.
 *
 * @since 1.0
 */
function wp_kbe_options() {
	require 'kbe-settings.php';
}


function kbe_register_settings() {
	register_setting( 'kbe_settings', 'kbe_settings', 'kbe_validate_settings' );
}
add_action( 'admin_init', 'kbe_register_settings' );

/**
 * Sanitize and validate plugin settings
 * @param  array $input
 * @return array
 * @since  1.1.0
 */
function kbe_validate_settings( $input ) {
	$input['kbe_plugin_slug'] = isset( $input['kbe_plugin_slug'] ) ? sanitize_title( $input['kbe_plugin_slug'] ) : '';
	$input['kbe_article_qty'] = intval( $input['kbe_article_qty'] );

	$input['kbe_search_setting']      =  isset( $input['kbe_search_setting'] ) && $input['kbe_search_setting'] ? 1 : 0 ;
	$input['kbe_breadcrumbs_setting'] =  isset( $input['kbe_breadcrumbs_setting'] ) && $input['kbe_breadcrumbs_setting'] ? 1 : 0 ;

	$sidebar_positions = array( 0, 1, 2 );

	$input['kbe_sidebar_home']  = isset( $input['kbe_sidebar_home'] ) && in_array( $input['kbe_sidebar_home'], $sidebar_positions ) ? intval( $input['kbe_sidebar_home'] ) : 0;
	$input['kbe_sidebar_inner'] = isset( $input['kbe_sidebar_inner'] ) && in_array( $input['kbe_sidebar_inner'], $sidebar_positions ) ? intval( $input['kbe_sidebar_inner'] ) : 0;

	$input['kbe_comments_setting'] =  isset( $input['kbe_comments_setting'] ) && $input['kbe_comments_setting'] ? 1 : 0 ;

	$input['kbe_bgcolor'] = isset( $input['kbe_bgcolor'] ) ?  $input['kbe_bgcolor'] : '';

	return $input;
}

//=========>  KBE Custom Taxonomy Order
function kbe_tax_order( $orderby, $args ) {
	$kbe_tax = 'kbe_taxonomy';

	if ( $args['orderby'] == 'terms_order' ) {
		return 't.terms_order';
	} elseif ( $kbe_tax == 1 && ! isset( $_GET['orderby'] ) ) {
		return 't.terms_order';
	} else {
		return $orderby;
	}
}
add_filter( 'get_terms_orderby', 'kbe_tax_order', 10, 2 );

function kbe_migrations_check() {
	require_once plugin_dir_path( __FILE__ ) . '../migrations/class-migration-manager.php';
	$migration_manager = new KBE_Migration_Manager( 'wp-knowledgebase' );
}
add_action( 'admin_init', 'kbe_migrations_check' );
register_activation_hook( __FILE__, 'kbe_migrations_check' );
