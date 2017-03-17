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
		$this->id          = 'kbe-install-migration';
		$this->notice_type = 'notice';
		$this->dismissible = true;

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
		// Insert KB page if it doens'nt exist already
		if (!kbe_get_knowledgebase_page_id()) {

			$page_id = wp_insert_post( array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_content' => '[kbe_knowledgebase]',
				'post_title'   => __( 'Knowledgebase', 'wp-knowledgebase' ),
			) );
			update_option( 'kbe_page_id', $page_id );

		}

		return true;
	}

	/**
	 * Notice.
	 *
	 * The notice that is being displayed to inform the user.
	 *
	 * @since 1.1.5
	 */
	public function get_notice() {
		$href         = wp_nonce_url( add_query_arg( array( 'action' => 'kbe-knowledgebase-migrate', 'migration' => $this->id ) ), 'migrate_kbe' );
		$dismiss_href = wp_nonce_url( add_query_arg( array( 'action' => 'kbe-knowledgebase-migrate-dismiss', 'migration' => $this->id ) ), 'migrate_kbe' );
		?><div class="notice notice-success">
			<p><strong><?php _e( 'Thank you for using WP Knowledgebase! :-) ', 'wp-knowledgebase' ); ?></strong> <?php _e( 'A page is needed to display the knowledgebase on. Do you want us to create a page fo you?', 'wp-knowledgebase' ); ?></p>
			<p><a href="<?php echo esc_url( $href ); ?>" class="button button-primary"><?php _e( 'Create page', 'wp-knowledgebase' ); ?></a>&nbsp;&nbsp;<a href="<?php echo esc_url( $dismiss_href ); ?>" class="button button-secondary"><?php _e( 'No thanks', 'wp-knowledgebase' ); ?></a></p>
		</div><?php
	}

}
