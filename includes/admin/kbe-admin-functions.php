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
	require dirname( __FILE__ ) . '/kbe-order.php';
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

	// Register each setting for automated $_POST handling
	foreach ( kbe_get_settings() as $id => $setting ) {

		switch ( $setting['type'] ) {
			case 'number' :
				$sanitize_callback = 'absint';
				break;
			default:
			case 'text' :
				$sanitize_callback = 'sanitize_text_field';
				break;
			case 'title' :
				$sanitize_callback = 'sanitize_title';
				break;
			case 'kbe_radio_switch' :
				$sanitize_callback = 'sanitize_kbe_radio_switch';
				break;
			case 'color' :
				$sanitize_callback = 'wp_kses_post';
				break;

		}
		register_setting( 'kbe_settings', $id, $sanitize_callback );
	}
}
add_action( 'admin_init', 'kbe_register_settings' );

function kbe_radio_switch_on_off( $v ) {
	if ( $v == 1 ) {
		return 1;
	}
	return 0;
}

function kbe_radio_switch_lrn( $v ) {
	if ( $v == 1 ) {
		return 1;
	} elseif ( $v == 2 ) {
		return 2;
	}
	return 0;
}

function kbe_get_settings() {

	return array(
		'kbe_plugin_slug' => array(
			'type' => 'title',
		),
		'kbe_article_qty' => array(
			'type' => 'number',
		),
		'kbe_search_setting' => array(
			'type' => 'kbe_radio_switch_on_off',
		),
		'kbe_breadcrumbs_setting' => array(
			'type' => 'kbe_radio_switch_on_off',
		),
		'kbe_sidebar_home' => array(
			'type' => 'kbe_radio_switch_lrn', // left, right, none option
		),
		'kbe_sidebar_inner' => array(
			'type' => 'kbe_radio_switch_lrn', // left, right, none option
		),
		'kbe_comments_setting' => array(
			'type' => 'kbe_radio_switch_on_off',
		),
		'kbe_bgcolor' => array(
			'type' => 'color',
		),
		'kbe_wipe_uninstall' => array(
			'type' => 'kbe_radio_switch_on_off',
		),
	);

}

function kbe_migrations_check() {
	require_once plugin_dir_path( __FILE__ ) . '../migrations/class-migration-manager.php';
	$migration_manager = new KBE_Migration_Manager( 'wp-knowledgebase' );
}
add_action( 'admin_init', 'kbe_migrations_check' );
register_activation_hook( __FILE__, 'kbe_migrations_check' );
