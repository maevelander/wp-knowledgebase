<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Class KBE_Migration_Install
 *
 * Migration that runs the install process of the plugin.
 *
 * @since 1.1.5
 */
class KBE_Migration_1_2_0 extends KBE_Abstract_Migration {


	/**
	 * Constructor.
	 *
	 * @since 1.1.5
	 */
	public function __construct() {
		$this->id          = 'kbe-install-1-2-0';
		$this->notice_type = 'none';

		parent::__construct();
	}

	/**
	 * Actually run the migration.
	 *
	 * @since 1.1.5
	 *
	 * @return bool True when everything went as planned, false otherwise.
	 */
	public function migrate() {

		kbe_articles();
		kbe_taxonomies();
		kbe_custom_tags();
		flush_rewrite_rules();

		global $wpdb;
		/*Create "term_order" Field in "wp_terms" Table for sortable order*/
		$term_order_qry = $wpdb->query( "SHOW COLUMNS FROM $wpdb->terms LIKE 'terms_order'" );
		if ( $term_order_qry == 0 ) {
			$wpdb->query( "ALTER TABLE $wpdb->terms ADD `terms_order` INT(4) NULL DEFAULT '0'" );
		}

		// Save settings as separate options
		$settings = wp_parse_args( get_option( 'kbe_settings' ), array(
			'kbe_article_qty' => get_option( 'kbe_article_qty'),
			'kbe_plugin_slug' => get_option( 'kbe_plugin_slug' ),
			'kbe_search_setting' => get_option( 'kbe_search_setting' ),
			'kbe_breadcrumbs_setting' => get_option( 'kbe_breadcrumbs_setting' ),
			'kbe_sidebar_home' => get_option( 'kbe_sidebar_home' ),
			'kbe_sidebar_inner' => get_option( 'kbe_sidebar_inner' ),
			'kbe_comments_setting' => get_option( 'kbe_comments_setting' ),
			'kbe_bgcolor' => get_option( 'kbe_bgcolor' ),
		) );

		update_option( 'kbe_settings', $settings );

		//delete_option( 'kbe_settings' );

		return true;
	}


}
