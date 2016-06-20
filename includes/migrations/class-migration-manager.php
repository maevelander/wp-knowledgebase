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
	public static $migrations = array();


	/**
	 * @var array Migrations that have already been ran before.
	 */
	private static $ran_migrations = array();


	/**
	 * @var string A unique name used for identifying settings. Use your plugin's/theme's name.
	 */
	private static $identifier;


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $identifier ) {
		self::$identifier     = sanitize_title( $identifier );
		self::$ran_migrations = self::get_ran_migrations();

		require_once plugin_dir_path( __FILE__ ) . 'class-abstract-migration.php';

		self::$migrations = array(
			'migration-install.php' => 'KBE_Migration_Install',
			'migration-1-2-0.php' => 'KBE_Migration_1_2_0',
		);

		foreach ( self::$migrations as $file => $class ) {
			include_once $file;
			$migration = new $class();
//			$migration->init();
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
	public static function get_ran_migrations() {
		if ( empty( self::$ran_migrations ) ) {
			self::$ran_migrations = get_option( self::$identifier . '_migrations', array() );
		}
		return self::$ran_migrations;
	}

	/**
	 * Update migration.
	 *
	 * Update a migration status in the DB.
	 *
	 * @since 1.0.0
	 *
	 * @param        $migration_id
	 * @param string $status
	 */
	public static function update( $migration_id, $status = 'migrated' ) {
		$migrations                  = self::get_ran_migrations();
		$migrations[ $migration_id ] = array(
			'status' => sanitize_title( $status ),
			'time'   => time(),
		);
		update_option( self::$identifier . '_migrations', $migrations );

		self::$migrations = $migrations;
	}

}
