<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( KBE_COMMENT_SETTING == 1 ) {
	?><div class="kbe_reply"><?php
		comments_template();
	?></div><?php
}
