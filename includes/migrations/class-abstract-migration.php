<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Abstract_Migration
 *
 * @package WP Data Migrations
 */
abstract class KBE_Abstract_Migration {

	/**
	 * @var string  Unique ID for this migration.
	 */
	protected $id;

	/**
	 * @var bool Is this migration dismissible?
	 */
	protected $dismissible = false;

	/**
	 * User roles allowed to migrate.
	 *
	 * A list of user roles that are allowed to perform the migration.
	 *
	 * @var array
	 */
//	protected $allowed_roles = array( 'administrator' );

	/**
	 * When set to 'admin_block', the admin will be blocked until the migration has been run.
	 * This is similar to how WP runs its updates.
	 *
	 * If set to 'notice' there will be a admin notice shown to the admin (or other roles if setup).
	 *
	 * @var string
	 */
	protected $notice_type = 'notice'; // 'notice', 'admin_block', 'nonce'

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->redirect = remove_query_arg( array( 'action', 'migration', '_wp_nonce' ) );

		if ( ! $this->has_run_before() ) {
			$this->init();
		}
	}

	/**
	 * Initialize this migration.
	 *
	 * Initialize the components this migration needs to function.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		// Show a notice before updating
		if ( $this->notice_type == 'notice' ) {
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
			add_action( 'admin_init', array( $this, 'check_for_migrate_actions' ), 50 );

		// Block admin area till upgraded
		} elseif ( $this->notice_type == 'block' ) {
			// @todo

		// Automatically update in the background
		} elseif ( $this->notice_type == 'none' ) {
			$this->run_migration();
		}
	}

	/**
	 * Initialize the migration.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Returns true when everything went according plan, false otherwise.
	 */
	abstract public function migrate();

	/**
	 * Revert the migration.
	 *
	 * If wanted, you can revert the changes made in the migration, allowing your users
	 * to revert back to a old version if needed.
	 *
	 * @since 1.0.0
	 */
	public function revert() {}

	/**
	 * Run the migration.
	 *
	 * This function handles the running of the migration.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function run_migration() {
		do_action( 'before_migration', $this );

		$this->migrate();

		do_action( 'after_migration', $this );

		$this->mark_as_ran();

		// Redirect after the migration has been completed.
		if ( ! empty( $this->redirect ) ) {
			wp_redirect( $this->redirect );
			die;
		}
	}

	/**
	 * Migration ran before?
	 *
	 * Check if this migration has been run before.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True when the migration has run before, false otherwise.
	 */
	public function has_run_before() {
		return in_array( $this->id, array_keys( KBE_Migration_Manager::get_ran_migrations() ) );
	}

	/**
	 * Mark as ran.
	 *
	 * Update the migration to be marked as ran.
	 *
	 * @since 1.0.0
	 */
	public function mark_as_ran() {
		KBE_Migration_Manager::update( $this->id );
	}

	/******************************************
	 * 'notice' migration type
	 *****************************************/

	/**
	 * Check for actions.
	 *
	 * Check for any migration actions being taken.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function check_for_migrate_actions() {
		// Bail if currently no migrations are being run
		if ( ! isset( $_GET['action'] ) || ! in_array( $_GET['action'], array( 'kbe-knowledgebase-migrate', 'kbe-knowledgebase-migrate-dismiss' ) ) ) {
			return false;
		}

		// Bail if its not this migration being run
		if ( ! isset( $_GET['migration'] ) || $_GET['migration'] != $this->id ) {
			return false;
		}

		if ( $_GET['action'] == 'kbe-knowledgebase-migrate-dismiss' ) {
			KBE_Migration_Manager::update( $this->id, 'dismissed' );
			wp_redirect( remove_query_arg( array( 'action', 'migration', '_wpnonce' ) ) );
			die;
		}

		// Run the migration
		return $this->run_migration();
	}

	/**
	 * Admin notice output.
	 *
	 * Output the admin notice in the 'admin_notices' hook.
	 *
	 * @since 1.0.0
	 */
	public function admin_notice() {
		$this->get_notice();
	}

	/**
	 * Get the full notice.
	 *
	 * Get the full notice HTML used to output the admin notice.
	 * This function can be overridden in a migration class if needed.
	 *
	 * @since 1.0.0
	 */
	protected function get_notice() {
		$href         = wp_nonce_url( add_query_arg( array( 'action' => 'kbe-knowledgebase-migrate', 'migration' => $this->id ) ), 'migrate_kbe' );
		$dismiss_href = wp_nonce_url( add_query_arg( array( 'action' => 'kbe-knowledgebase-migrate-dismiss', 'migration' => $this->id ) ), 'migrate_kbe' );
		?><div class="notice notice-success">
			<p><?php echo wp_kses_post( $this->get_notice_text() ); ?></p>
			<p>
				<a href="<?php echo esc_url( $href ); ?>" class="button button-primary"><?php _e( 'Migrate' ); ?></a><?php
				if ( $this->dismissible ) {
					?>&nbsp;&nbsp;<a href="<?php esc_url( $dismiss_href ); ?>" class="button button-secondary"><?php _e( 'No thanks', 'wp-knowledgebase' ); ?></a></p><?php
				}
		?></div><?php
	}

	/**
	 * Notice text.
	 *
	 * Get the default notice text for when there is data to be migrated.
	 * Feel free to override this in your migration class.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_notice_text() {
		return 'Thank you for updating! We need to migrate some data as there has been changes that require that.';
	}

}
