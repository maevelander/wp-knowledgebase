<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


//=========> Enqueue KBE Style file in header.php
function kbe_styles(){
	if( file_exists( get_stylesheet_directory() . '/wp_knowledgebase/kbe_style.css' ) ){
		$stylesheet = get_stylesheet_directory_uri() . '/wp_knowledgebase/kbe_style.css';
	} else {
		$stylesheet = WP_KNOWLEDGEBASE. 'template/kbe_style.css';
	}
	wp_register_style ( 'kbe_theme_style', $stylesheet, array(), KBE_PLUGIN_VERSION );
	wp_enqueue_style('kbe_theme_style');

	wp_register_script( 'kbe_live_search', WP_KNOWLEDGEBASE.  '/assets/js/jquery.livesearch.js', array('jquery'), KBE_PLUGIN_VERSION, true );
	wp_enqueue_script('kbe_live_search');

}
add_action('wp_enqueue_scripts', 'kbe_styles');


//=========> Registering KBE widget area
function kbe_register_sidebar(  ) {
	register_sidebar(array(
		'name' => __('WP Knowledgebase Sidebar','kbe'),
		'id' => 'kbe_cat_widget',
		'description' => __('WP Knowledgebase sidebar area','kbe'),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h6>',
		'after_title' => '</h6>',
	));
}
add_action( 'widgets_init', 'kbe_register_sidebar' );


function kbe_search_drop(){
	if( KBE_SEARCH_SETTING == 1 ){
		?><script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('#s').keyup(function() {
					jQuery('#search-result').slideDown("slow");
				});
			});

			jQuery(document).ready(function(e) {
				jQuery('body').click(function(){
					jQuery('#search-result').slideDown("slow",function(){
						document.body.addEventListener('click', boxCloser, false);
					});
				});

				function boxCloser(e){
					if(e.target.id != 's'){
						document.body.removeEventListener('click', boxCloser, false);
						jQuery('#search-result').slideUp("slow");
					}
				}
			});

			jQuery(document).ready(function () {

				var tree_id = 0;
				jQuery('div.kbe_category:has(.kbe_child_category)').addClass('has-child').prepend('<span class="switch"><img src="<?php echo plugins_url('template/images/kbe_icon-plus.png',__FILE__) ?>" /></span>').each(function () {
					tree_id++;
					jQuery(this).attr('id', 'tree' + tree_id);
				});

				jQuery('div.kbe_category > span.switch').click(function () {
					var tree_id = jQuery(this).parent().attr('id');
					if (jQuery(this).hasClass('open')) {
						jQuery(this).parent().find('div:first').slideUp('fast');
						jQuery(this).removeClass('open');
						jQuery(this).html('<img src="<?php echo plugins_url('template/images/kbe_icon-plus.png',__FILE__) ?>" />');
					} else {
						jQuery(this).parent().find('div:first').slideDown('fast');
						jQuery(this).html('<img src="<?php echo plugins_url('template/images/kbe_icon-plus.png',__FILE__) ?>" />');
						jQuery(this).addClass('open');
					}
				});

			});
		</script><?php

		if( ( KBE_SEARCH_SETTING == 1 ) && ( wp_script_is( 'kbe_live_search', 'enqueued' ) ) ){

			?><script type="text/javascript">
				jQuery(document).ready(function() {
					var kbe = jQuery('#live-search #s').val();
					jQuery('#live-search #s').liveSearch({url: '<?php echo home_url(); ?>/?ajax=on&post_type=kbe_knowledgebase&s='});
				});
			</script><?php
		}
	}
}
add_action('wp_footer', 'kbe_search_drop');


//=========>  KBE Knowledgebase Shortcode
function kbe_shortcode( $atts, $content = null ){
	$return_string = require dirname( __FILE__ ) . '/../template/kbe_knowledgebase.php';
	wp_reset_query();
	return $return_string;
}

function register_kbe_shortcodes(){
	add_shortcode('kbe_knowledgebase', 'kbe_shortcode');
}
add_action('init', 'register_kbe_shortcodes');


//=========>  KBE Short Content
function kbe_short_content($limit) {
	$content = get_the_content();
	$pad="&hellip;";

	if(strlen($content) <= $limit) {
		return strip_tags($content);
	} else {
		$content = substr($content, 0, $limit) . $pad;
		return strip_tags($content);
	}
}

//=========> KBE Dynamic CSS
function count_bg_color(){
	if ( KBE_BG_COLOR ){
		$dynamic_css = "
            #kbe_content h2 span.kbe_count,
            #kbe_content .kbe_child_category h3 span.kbe_count {
                background-color: " . KBE_BG_COLOR . " !important;
            }
            .kbe_widget .kbe_tags_widget a,
            .kbe_widget .kbe_tags_widget a:hover{
                text-decoration: underline;
                color: " . KBE_BG_COLOR . " !important;
            }
        ";
		wp_add_inline_style( 'kbe_theme_style', $dynamic_css );
	}
}
add_action('wp_enqueue_scripts', 'count_bg_color');
