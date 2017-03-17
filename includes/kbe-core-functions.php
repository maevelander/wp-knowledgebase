<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Enqueue scripts.
 *
 * Enqueue the required stylesheets and javascripts on the front end.
 *
 * @since 1.0
 */
function kbe_styles() {
	if ( file_exists( get_stylesheet_directory() . '/wp_knowledgebase/kbe_style.css' ) ) {
		$stylesheet = get_stylesheet_directory_uri() . '/wp_knowledgebase/kbe_style.css';
	} else {
		$stylesheet = WP_KNOWLEDGEBASE . 'template/kbe_style.css';
	}
	wp_register_style( 'kbe_theme_style', $stylesheet, array(), KBE_PLUGIN_VERSION );
	wp_enqueue_style( 'kbe_theme_style' );

	wp_register_script( 'kbe_live_search', WP_KNOWLEDGEBASE . '/assets/js/jquery.livesearch.js', array( 'jquery' ), KBE_PLUGIN_VERSION, true );
	wp_enqueue_script( 'kbe_live_search' );
}
add_action( 'wp_enqueue_scripts', 'kbe_styles' );

/**
 * Register widget area.
 *
 * Register a widget area that is used on the KB pages.
 *
 * @since 1.0
 */
function kbe_register_sidebar() {
	register_sidebar( array(
		'name'          => __( 'WP Knowledgebase Sidebar', 'wp-knowledgebase' ),
		'id'            => 'kbe_cat_widget',
		'description'   => __( 'WP Knowledgebase sidebar area', 'wp-knowledgebase' ),
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '<h6>',
		'after_title'   => '</h6>',
	) );
}
add_action( 'widgets_init', 'kbe_register_sidebar' );

/**
 * Required search JS code.
 *
 * Javascript code required for the Search feature to work properly.
 *
 * @since 1.0
 */
function kbe_search_drop() {
	if ( KBE_SEARCH_SETTING == 1 ) {
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

				function boxCloser(e) {
					if (e.target.id != 's') {
						document.body.removeEventListener('click', boxCloser, false);
						jQuery('#search-result').slideUp("slow");
					}
				}
			});

			jQuery(document).ready(function () {

				var tree_id = 0;
				jQuery('div.kbe_category:has(.kbe_child_category)').addClass('has-child').prepend('<span class="switch"><img src="<?php echo plugins_url( '../template/images/kbe_icon-plus.png', __FILE__ ); ?>" /></span>').each(function () {
					tree_id++;
					jQuery(this).attr('id', 'tree' + tree_id);
				});

				jQuery('div.kbe_category > span.switch').click(function () {
					var tree_id = jQuery(this).parent().attr('id');
					if (jQuery(this).hasClass('open')) {
						jQuery(this).parent().find('div:first').slideUp('fast');
						jQuery(this).removeClass('open');
						jQuery(this).html('<img src="<?php echo plugins_url( '../template/images/kbe_icon-plus.png', __FILE__ ); ?>" />');
					} else {
						jQuery(this).parent().find('div:first').slideDown('fast');
						jQuery(this).html('<img src="<?php echo plugins_url( '../template/images/kbe_icon-minus.png', __FILE__ ); ?>" />');
						jQuery(this).addClass('open');
					}
				});

			});
		</script><?php

		if ( ( KBE_SEARCH_SETTING == 1 ) && ( wp_script_is( 'kbe_live_search', 'enqueued' ) ) ) {

			?><script type="text/javascript">
				jQuery(document).ready(function() {
					var kbe = jQuery('#live-search #s').val();
					jQuery('#live-search #s').liveSearch({url: '<?php echo home_url(); ?>/?ajax=on&post_type=kbe_knowledgebase&s='});
				});
			</script><?php
		}
	}
}
add_action( 'wp_footer', 'kbe_search_drop' );

/**
 * Knowledgebase shortcode.
 *
 * Register the [kbe_knowledgebase] shortcode.
 *
 * @since 1.0
 *
 * @param array $atts Attributes used with the shortcode (none available).
 * @param null $content Content passed through the shortcode.
 * @return mixed Knowledgebase page contents.
 */
function kbe_shortcode( $atts, $content = null ) {
	$return_string = require dirname( __FILE__ ) . '/../template/kbe_knowledgebase.php';
	wp_reset_query();
	return $return_string;
}
add_shortcode( 'kbe_knowledgebase', 'kbe_shortcode' );

/**
 * Get short content excerpt.
 *
 * Get a short content excerpt that is being used in the search results.
 *
 * @since 1.0
 *
 * @param  int    $limit Maximum content length (in characters).
 * @return string        Short content to show in the search results.
 */
function kbe_short_content( $limit ) {
	$content = get_the_content();
	$pad = '&hellip;';

	if ( strlen( $content ) <= $limit ) {
		return strip_tags( $content );
	} else {
		$content = substr( $content, 0, $limit ) . $pad;
		return strip_tags( $content );
	}
}

/**
 * Dynamic CSS.
 *
 * Include the dynamic CSS that can be set on the settings page.
 * This includes the color for the number of articles badge.
 *
 * @since 1.0
 */
function kbe_count_bg_color() {
	if ( KBE_BG_COLOR ) {
		$dynamic_css = '
			#kbe_content h2 span.kbe_count,
			#kbe_content .kbe_child_category h3 span.kbe_count {
				background-color: ' . KBE_BG_COLOR . ' !important;
			}
			.kbe_widget .kbe_tags_widget a,
			.kbe_widget .kbe_tags_widget a:hover{
				text-decoration: underline;
				color: ' . KBE_BG_COLOR . ' !important;
			}
		';
		wp_add_inline_style( 'kbe_theme_style', $dynamic_css );
	}
}
add_action( 'wp_enqueue_scripts', 'kbe_count_bg_color' );

/**
 * Get page ID of KB.
 *
 * Get the page ID of the page that holds the Knowledgebase.
 *
 * @since 1.1.5
 *
 * @return int|bool Post ID when it has been found, false otherwise.
 */
function kbe_get_knowledgebase_page_id() {
	return get_option( 'kbe_plugin_slug', false );
}

/**
 * Sort by custom order
 *
 * Sort categories by custom order defined on the Order admin page
 *
 * @param  string $orderby ORDERBY clause of the terms query
 * @param  array  $args    array of terms query arguments
 *
 * @ since 1.1.5
 *
 * @return string string to replace $orderby
 */
function kbe_tax_order( $orderby, $args ) {
	$kbe_tax = 'kbe_taxonomy';
	if ( $args['orderby'] == 'terms_order' ) {
		return 't.terms_order';
	} elseif ( $kbe_tax == 1 && ! isset( $_GET['orderby'] ) ) {
		return 't.terms_order';
	} else {
		return $orderby;
	}
}
add_filter( 'get_terms_orderby', 'kbe_tax_order', 10, 2 );
