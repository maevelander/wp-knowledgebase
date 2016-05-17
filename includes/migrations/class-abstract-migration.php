<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Abstract_Migration
 */
abstract class KBE_Abstract_Migration {

	
	/**
	 * @var bool
	 */
//	protected $background_migrate = false;


	/**
	 * User roles allowed to migrate.
	 * 
	 * A list of user roles that are allowed to perform the migration.
	 * 
	 * @var array
	 */
	protected $allowed_roles = array( 'administrator' );


	/**
	 * When set to 'admin_block', the admin will be blocked until the migration has been run.
	 * This is similar to how WP runs its updates.
	 *
	 * If set to 'notice' there will be a admin notice shown to the admin (or other roles if setup).
	 * 
	 * @var string
	 */
	protected $notice_type = 'notice'; // 'notice', 'admin_block'
	
	
	/**
	 * Constructor.
	 * 
	 * Initialize the migration.
	 */
	public function __construct() {
		$this->init();
	}

	
	/**
	 *
	 */
	public function init() {

		// Show a notice before updating
		if ( $this->notice_type == 'notice' ) {
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );

			// Block admin area till upgraded
		} elseif ( $this->notice_type == 'block' ) {

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



	public function run_migration() {

		if ( $this->has_run_before() ) {
			return false;
		}

		$this->migrate();
		
		$this->mark_as_ran();

	}

	public function has_run_before() {
		
	}

	public function mark_as_ran() {
		
	}
	
	
	public function admin_notice() {
		?><div class="notice notice-warning">
			<p><?php echo $this->get_notice_text(); ?></p>
		</div><?php
	}

	protected function get_notice_text() {
		return 'Hey! You should upgrade!';
	}
	
	
}