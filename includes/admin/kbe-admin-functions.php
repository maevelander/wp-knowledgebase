<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


//=========> Enqueue plugin files
$kbe_address_bar = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if(strpos($kbe_address_bar, "post_type=kbe_knowledgebase")) {
	add_action('admin_init', 'wp_kbe_scripts');
	function wp_kbe_scripts(){
		wp_register_style('kbe_admin_css', WP_KNOWLEDGEBASE.'/assets/css/kbe-admin-style.css');
		wp_enqueue_style('kbe_admin_css');
	}
}

//=========> Enqueue color picker
add_action('admin_init', 'enqueue_color_picker');
function enqueue_color_picker($hook_suffix) {
	// first check that $hook_suffix is appropriate for your admin page
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('cp-script-handle', WP_KNOWLEDGEBASE.'/assets/js/color_picker.js', array( 'wp-color-picker' ), false, true);
}


add_action('admin_init', 'load_all_jquery');
function load_all_jquery() {
	wp_enqueue_script("jquery");
	$jquery_ui = array(
		"jquery-ui-sortable"
	);

	foreach($jquery_ui as $script){
		wp_enqueue_script($script);
	}
}

//=========> Plugin menu
add_action('admin_menu', 'kbe_plugin_menu');
function kbe_plugin_menu() {
	add_submenu_page('edit.php?post_type=kbe_knowledgebase', 'Order', 'Order', 'manage_options', 'kbe_order', 'wp_kbe_order');
	add_submenu_page('edit.php?post_type=kbe_knowledgebase', 'Settings', 'Settings', 'manage_options', 'kbe_options', 'wp_kbe_options');
}

//  Require File kbe_order.php
function wp_kbe_order(){
	require "includes/kbe-order.php";
}

//=========> Require Files
//  kbe_settings.php
function wp_kbe_options(){
	require "kbe-settings.php";
}

//=========> Register plugin settings
add_action('admin_init', 'kbe_register_settings');
function kbe_register_settings() {
	register_setting( 'kbe_settings', 'kbe_settings', 'kbe_validate_settings' );
}


/**
 * Sanitize and validate plugin settings
 * @param  array $input
 * @return array
 * @since  1.1.0
 */
function kbe_validate_settings( $input ) {
	$input['kbe_plugin_slug'] = isset( $input['kbe_plugin_slug'] ) ? sanitize_title( $input['kbe_plugin_slug'] ) : '';
	$input['kbe_article_qty'] = intval( $input['kbe_article_qty'] );

	$input['kbe_search_setting'] =  isset( $input['kbe_search_setting'] ) && $input['kbe_search_setting'] ? 1 : 0 ;
	$input['kbe_breadcrumbs_setting'] =  isset( $input['kbe_breadcrumbs_setting'] ) && $input['kbe_breadcrumbs_setting'] ? 1 : 0 ;

	$sidebar_positions = array( 0, 1, 2 );

	$input['kbe_sidebar_home'] = isset( $input['kbe_sidebar_home'] ) && in_array( $input['kbe_sidebar_home'], $sidebar_positions ) ? intval( $input['kbe_sidebar_home'] ) : 0;
	$input['kbe_sidebar_inner'] = isset( $input['kbe_sidebar_inner'] ) && in_array( $input['kbe_sidebar_inner'], $sidebar_positions ) ? intval( $input['kbe_sidebar_inner'] ) : 0;

	$input['kbe_comments_setting'] =  isset( $input['kbe_comments_setting'] ) && $input['kbe_comments_setting'] ? 1 : 0 ;

	$input['kbe_bgcolor'] = isset( $input['kbe_bgcolor'] ) ?  $input['kbe_bgcolor'] : '';

	return $input;
}


//=========>  KBE Custom Taxonomy Order
function kbe_tax_order($orderby, $args){
	$kbe_tax = "kbe_taxonomy";

	if($args['orderby'] == 'terms_order'){
		return 't.terms_order';
	}elseif($kbe_tax == 1 && !isset($_GET['orderby'])){
		return 't.terms_order';
	}else{
		return $orderby;
	}
}
add_filter('get_terms_orderby', 'kbe_tax_order', 10, 2);
