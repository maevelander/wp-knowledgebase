<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * 
 */
class KBE_Migration_Manager {

	
	/**
	 * @var array List of migration files.
	 * 
	 * @since 1.0.0
	 */
	public $migrations = array();


	/**
	 * @var array Migrations that have already been ran before.
	 */
	private $ran_migrations = array();

	
	/**
	 * @var string A unique name used for identifying settings. Use your plugin's/theme's name.
	 */
	private $identifier;
	

	/**
	 * Constructor.
	 * 
	 * @since 1.0.0
	 */
	public function __construct( $identifier ) {

		$this->identifier = sanitize_title( $identifier );
		$this->ran_migrations = $this->get_ran_migrations();
		
		require_once plugin_dir_path( __FILE__ ) . 'class-abstract-migration.php';

		$this->migrations = array(
			'migration-test.php' => 'KBE_Migrate1',
		);
		
		foreach ( $this->migrations as $file => $class ) {
			include_once $file;
			$migration = new $class();
			$migration->migrate();
		}
		
	}


	/**
	 * Get ran migrations.
	 * 
	 * Get the list of migrations that already have been done.
	 * 
	 * @since 1.0.0
	 * 
	 * @return array List of migrations that already have been ran.
	 */
	private function get_ran_migrations() {
		
		if ( empty( $this->ran_migrations ) ) {
			$this->ran_migrations = get_option( $this->identifier . '_migrations', array() );
		}
		return $this->ran_migrations;
	}


}
