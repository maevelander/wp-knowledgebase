<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class KBE_Migrate1 extends KBE_Abstract_Migration {


	public function __construct() {
		parent::__construct();
	}


	public function migrate() {
		
		update_option( 'test_migrate_value', date_i18n( 'd-m-Y H:i:s' ) );
		
	}


}
