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
	 * 
	 */
	public function init() {
		
		if ( $this->notice_type == 'notice' ) {
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		}
		
	}

	public function admin_notice() {
		?><div class="notice">
			<p><?php echo $this->get_notice_text(); ?></p>
		</div><?php
	}

	protected function get_notice_text() {
		return 'Hey! You should upgrade!';
	}
	
	
}