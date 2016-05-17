<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Class KBE_Migration_Install
 * 
 * Migration that runs the install process of the plugin.
 * 
 * @since 1.1.5
 */
class KBE_Migration_Install extends KBE_Abstract_Migration {


	/**
	 * Constructor.
	 * 
	 * @since 1.1.5
	 */
	public function __construct() {
		
		$this->id = 'kbe-install-migration';
		$this->notice_type = 'notice';
		$this->dismissible = true;

		parent::__construct();

	}


	public function migrate() {
		
		update_option( 'test_migrate_value', date_i18n( 'd-m-Y H:i:s' ) );
		
		return true;
	}

	public function get_notice() {
		$href = wp_nonce_url( add_query_arg( array( 'action' => 'kbe-knowledgebase-migrate', 'migration' => $this->id ) ), 'migrate_kbe' );
		$dismiss_href = wp_nonce_url( add_query_arg( array( 'action' => 'kbe-knowledgebase-migrate-dismiss', 'migration' => $this->id ) ), 'migrate_kbe' );
		?><div class="notice notice-success">
			<p><strong><?php _e( 'Thank you for using WP Knowledgebase! :-) ', 'kbe' ); ?></strong> <?php _e( 'A page is needed to display the knowledgebase on. Do you want us to create a page fo you?', 'kbe' ); ?></p>
			<p><a href="<?php echo esc_url( $href ); ?>" class="button button-primary"><?php _e( 'Create page', 'kbe' ); ?></a>&nbsp;&nbsp;<a href="<?php echo esc_url( $dismiss_href ); ?>" class="button button-secondary"><?php _e( 'No thanks', 'kbe'); ?></a></p>
		</div><?php
	}


}

