<?php
/*
  Plugin Name: WP Knowledgebase
  Plugin URI: http://wordpress.org/plugins/wp-knowledgebase
  Description: Simple and flexible knowledgebase plugin for WordPress
  Author: Enigma Plugins
  Version: 1.0.9
  Author URI: http://enigmaplugins.com
  Requires at least: 2.7
 */
 
 define( 'KBE_PLUGIN_VERSION', '1.0.9' );

//=========> Create language folder
add_action( 'init', 'kbe_plugin_load_textdomain' );
function kbe_plugin_load_textdomain() {
    load_plugin_textdomain( 'kbe', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

//=========> Require Files
//  kbe_settings.php
function wp_kbe_options(){
    require "kbe_settings.php";
}
//  Require File kbe_order.php
function wp_kbe_order(){
    require "kbe_order.php";
}
//  Require File kbe_articles.php
require "articles/kbe_articles.php";

//  Require Category Widget file
require "widget/kbe_widget_category.php";
//  Require Articles Widget file
require "widget/kbe_widget_article.php";
//  Require Search Articles Widget file
require "widget/kbe_widget_search.php";
//  Require Tags Widget file
require "widget/kbe_widget_tags.php";

//=========> Create Hooks for WP Knowledgebase
function wp_kbe_hooks($kbe_networkwide) {
    
    kbe_articles();
    kbe_taxonomies();
    kbe_custom_tags();
    flush_rewrite_rules();
    
    global $wpdb;
    /*Creat "term_order" Field in "wp_terms" Table for sortable order*/
    $term_order_qry = $wpdb->query("SHOW COLUMNS FROM $wpdb->terms LIKE 'terms_order'");
    if($term_order_qry == 0){
        $wpdb->query("ALTER TABLE $wpdb->terms ADD `terms_order` INT(4) NULL DEFAULT '0'");
    }
    
    $kbe_prefix = $wpdb->prefix;

    $kbe_pageSql = $wpdb->get_results("Select *
                                       From ".$kbe_prefix."posts
                                       Where post_content like '%[kbe_knowledgebase]%'
                                       And post_type = 'page'");

    if(!$kbe_pageSql){
        //  Insert a "Knowledgebase" page
        $kbe_max_page_Sql = $wpdb->get_results("SELECT Max(ID) As kbe_maxId FROM ".$kbe_prefix."posts");
        foreach($kbe_max_page_Sql as $kbe_max_page_row) {
            $kbe_maxId = $kbe_max_page_row->kbe_maxId;
            $kbe_maxId = $kbe_maxId + 1;
        }

        $kbe_now = date('Y-m-d H:i:s');
        $kbe_now_gmt = gmdate('Y-m-d H:i:s');
        $kbe_guid = get_option('home') . '/?page_id='.$kbe_maxId;
        $kbe_user_id = get_current_user_id();

        $kbe_table_posts = $wpdb->prefix.'posts';

        $kbe_data_posts = array(
                            'post_author'           =>  $kbe_user_id,
                            'post_date'             =>  $kbe_now,
                            'post_date_gmt'         =>  $kbe_now_gmt,
                            'post_content'          =>  '[kbe_knowledgebase]',
                            'post_title'            =>  'Knowledgebase',
                            'post_excerpt'          =>  '',
                            'post_status'           =>  'publish',
                            'comment_status'        =>  'closed',
                            'ping_status'           =>  'closed',
                            'post_password'         =>  '',
                            'post_name'             =>  'knowledgebase',
                            'to_ping'               =>  '',
                            'pinged'                =>  '',
                            'post_modified'         =>  $kbe_now,
                            'post_modified_gmt'     =>  $kbe_now_gmt,
                            'post_content_filtered' =>  '',
                            'post_parent'           =>  '0',
                            'guid'                  =>  $kbe_guid,
                            'menu_order'            =>  '0',
                            'post_type'             =>  'page',
                            'post_mime_type'        =>  '',
                            'comment_count'         =>  '0',
                        );
        $wpdb->insert($kbe_table_posts,$kbe_data_posts) or die(mysql_error());

        //  Insert a page template for knowlwdgebase
        $kbe_tempTableSql = $wpdb->get_results("Select post_content, ID
                                                From ".$kbe_prefix."posts
                                                Where post_content Like '%[kbe_knowledgebase]%'
                                                And post_type <> 'revision'");
        foreach($kbe_tempTableSql as $kbe_tempTableRow) {
            $tempPageId = $kbe_tempTableRow->ID;

            //  Set Knowledgebase page template
            add_post_meta($tempPageId, '_wp_page_template', 'wp_knowledgebase/kbe_knowledgebase.php');
        }
    }

    $kbe_optSlugSql = $wpdb->get_results("Select * From ".$kbe_prefix."options Where option_name like '%kbe_plugin_slug%'");

    if(!$kbe_optSlugSql){
        add_option( 'kbe_plugin_slug', 'knowledgebase', '', 'yes' );
    }

    $kbe_optPageSql = $wpdb->get_results("Select * From ".$kbe_prefix."options Where option_name like '%kbe_article_qty%'");

    if(!$kbe_optPageSql){
        add_option( 'kbe_article_qty', '5', '', 'yes' );
    }
    
    if (function_exists('is_multisite') && is_multisite()) {
        // check if it is a network activation - if so, run the activation function for each blog id
        if ($kbe_networkwide) {
            $kbe_old_blog = $wpdb->blogid;
            // Get all blog ids
            $kbe_blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($kbe_blog_ids as $kbe_blog_id) {
                switch_to_blog($kbe_blog_id);
            }
            switch_to_blog($kbe_old_blog);
            return;
        }   
    } 
}
register_activation_hook(__FILE__, 'wp_kbe_hooks');

//=========> Define plugin path
define( 'WP_KNOWLEDGEBASE', plugin_dir_url(__FILE__));

//=========> Register plugin settings
add_action('admin_init', 'kbe_register_settings');
function kbe_register_settings() {
    register_setting('kbe_settings_group', 'kbe_plugin_slug');
    register_setting('kbe_settings_group', 'kbe_article_qty');
    register_setting('kbe_settings_group', 'kbe_search_setting');
    register_setting('kbe_settings_group', 'kbe_breadcrumbs_setting');
    register_setting('kbe_settings_group', 'kbe_sidebar_home');
    register_setting('kbe_settings_group', 'kbe_sidebar_inner');
    register_setting('kbe_settings_group', 'kbe_comments_setting');
    register_setting('kbe_settings_group', 'kbe_bgcolor');
}

//  define options values
define('KBE_ARTICLE_QTY', get_option('kbe_article_qty'));
define('KBE_PLUGIN_SLUG', get_option('kbe_plugin_slug'));
define('KBE_SEARCH_SETTING', get_option('kbe_search_setting'));
define('KBE_BREADCRUMBS_SETTING', get_option('kbe_breadcrumbs_setting'));
define('KBE_SIDEBAR_HOME', get_option('kbe_sidebar_home'));
define('KBE_SIDEBAR_INNER', get_option('kbe_sidebar_inner'));
define('KBE_COMMENT_SETTING', get_option('kbe_comments_setting'));
define('KBE_BG_COLOR', get_option('kbe_bgcolor'));
define('KBE_LINK_STRUCTURE', get_option('permalink_structure'));
define('KBE_POST_TYPE', 'kbe_knowledgebase');
define('KBE_POST_TAXONOMY', 'kbe_taxonomy');
define('KBE_POST_TAGS', 'kbe_tags');

//=========> Get Knowledgebase title
global $wpdb;
$getSql = $wpdb->get_results("Select ID From $wpdb->posts Where post_content Like '%[kbe_knowledgebase]%' And post_type <> 'revision'");

foreach($getSql as $getRow) {
    $pageId = $getRow->ID;
}
define('KBE_PAGE_TITLE', $pageId);

//=========> Plugin menu
add_action('admin_menu', 'kbe_plugin_menu');
function kbe_plugin_menu() {
    add_submenu_page('edit.php?post_type=kbe_knowledgebase', 'Order', 'Order', 'manage_options', 'kbe_order', 'wp_kbe_order');
    add_submenu_page('edit.php?post_type=kbe_knowledgebase', 'Settings', 'Settings', 'manage_options', 'kbe_options', 'wp_kbe_options');
}


//=========> Enqueue KBE frontend scripts/styles
add_action( 'wp_enqueue_scripts', 'kbe_frontend_scripts');
function kbe_frontend_scripts(){
	if( file_exists( get_stylesheet_directory() . '/wp_knowledgebase/kbe_style.css' ) ){
		$stylesheet = get_stylesheet_directory_uri() . '/wp_knowledgebase/kbe_style.css'; 
	} else {
		$stylesheet = WP_KNOWLEDGEBASE . '/template/kbe_style.css';
	}
	wp_enqueue_style ('kbe_theme_style', get_stylesheet_directory_uri() . '/wp_knowledgebase/kbe_style.css');
	wp_enqueue_script('kbe_live_search', WP_KNOWLEDGEBASE.'js/jquery.livesearch.js', array('jquery'), KBE_PLUGIN_VERSION, true );
}


//=========> Enqueue admin scripts/styles
add_action( 'current_screen', 'kbe_admin_settings_scripts' );
function kbe_admin_settings_scripts($screen) {
	// first check that $hook_suffix is appropriate for your admin page
	if( $screen->id == 'kbe_knowledgebase_page_kbe_options' ){
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('cp-script-handle', WP_KNOWLEDGEBASE.'js/color_picker.js', array( 'wp-color-picker' ), false, true);
	} elseif ( $screen->id == 'kbe_knowledgebase_page_kbe_order' ){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-sortable' );
	}

	if( $screen->post_type == 'kbe_knowledgebase' ){
		wp_enqueue_style('kbe_admin_css', WP_KNOWLEDGEBASE.'css/kbe_admin_style.css');
	}

}

//=========> Deprecated functions
function kbe_styles(){
    _deprecated_function( 'kbe_styles', '1.1.0', 'kbe_frontend_scripts' );
}


function kbe_live_search(){
    _deprecated_function( 'kbe_live_search', '1.1.0', 'kbe_frontend_scripts' );
}


function wp_kbe_scripts(){
    _deprecated_function( 'wp_kbe_scripts', '1.1.0', 'kbe_admin_settings_scripts' );
}

function enqueue_color_picker($hook_suffix) {
	_deprecated_function( 'enqueue_color_picker', '1.1.0', 'kbe_admin_settings_scripts' );
}


function load_all_jquery() {
	_deprecated_function( 'load_all_jquery', '1.1.0', 'kbe_admin_settings_scripts' );
}

function st_add_live_search () {
?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            var kbe = jQuery('#live-search #s').val();
            jQuery('#live-search #s').liveSearch({url: '<?php echo home_url(); ?>/?ajax=on&post_type=kbe_knowledgebase&s='});
        });
    </script>
<?php
}
add_action('wp_head', 'st_add_live_search');


/**
 * Load a template.
 *
 * Handles template usage so that we can use our own templates instead of the themes.
 *
 * Templates are in the 'templates' folder. knowledgebase looks for theme
 * overrides in /theme/wp-knowledgebase/ by default
 *
 * @param mixed $template
 * @return string
 */
function kbe_template_chooser($template){

	$template_path = apply_filters( 'kbe_template_path', 'wp_knowledgebase/' );
	
	$find = array();
	$file = '';

	if ( is_single() && get_post_type() == 'kbe_knowledgebase' ) {

		$file   = 'single-kbe_knowledgebase.php';
		$find[] = $file;
		$find[] = $template_path . $file;

	} elseif ( is_tax('kbe_taxonomy') || is_tax( 'kbe_tags') ) {

		$term   = get_queried_object();

		if ( is_tax( 'kbe_taxonomy' ) || is_tax( 'kbe_tags' ) ) {
			$file = 'taxonomy-' . $term->taxonomy . '.php';
		} else {
			$file = 'archive.php';
		}

		$find[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
		$find[] = $template_path . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
		$find[] = 'taxonomy-' . $term->taxonomy . '.php';
		$find[] = $template_path . 'taxonomy-' . $term->taxonomy . '.php';
		$find[] = $file;
		$find[] = $template_path . $file;

	} elseif ( is_post_type_archive( 'kbe_knowledgebase' ) || is_page( get_option('kbe_archive_page_id' ) ) ) {

		$file   = 'archive-kbe_knowledgebase.php';
		$find[] = $file;
		$find[] = $template_path . $file;

	}

	if ( $file ) {
		$template       = locate_template( array_unique( $find ) );
		if ( ! $template ) {
			$template = trailingslashit( dirname(__FILE__) ) . 'template/' . $file;
		}
	}

	  return $template;

}
add_filter('template_include', 'kbe_template_chooser');

//=========> Registering KBE widget area
register_sidebar(array(
    'name' => __('WP Knowledgebase Sidebar','kbe'),
    'id' => 'kbe_cat_widget',
    'description' => __('WP Knowledgebase sidebar area','kbe'),
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '<h6>',
    'after_title' => '</h6>',
));

//=========> KBE Search Form
function kbe_search_form(){
?>
<!-- #live-search -->
<div id="live-search">
    <div class="kbe_search_field">
        <form role="search" method="get" id="searchform" class="clearfix" action="<?php echo home_url( '/' ); ?>" autocomplete="off">
            <input type="text" onfocus="if (this.value == '<?php _e("Search Articles...", "kbe") ?>') {this.value = '';}" onblur="if (this.value == '')  {this.value = '<?php _e("Search Articles...", "kbe") ?>';}" value="<?php _e("Search Articles...", "kbe") ?>" name="s" id="s" />
            <!--<ul id="kbe_search_dropdown"></ul>-->
            <input type="hidden" name="post_type" value="kbe_knowledgebase" />
        </form>
    </div>
</div>
<!-- /#live-search -->
<?php
}

add_action('wp_head', 'kbe_search_drop');
function kbe_search_drop(){
?>
<script type="text/javascript">
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
        jQuery('div.kbe_category:has(.kbe_child_category)').addClass('has-child').prepend('<span class="switch"><img src="<?php echo get_stylesheet_directory_uri() ?>/kbe_images/kbe_icon-plus.png" /></span>').each(function () {
            tree_id++;
            jQuery(this).attr('id', 'tree' + tree_id);
        });

        jQuery('div.kbe_category > span.switch').click(function () {
            var tree_id = jQuery(this).parent().attr('id');
            if (jQuery(this).hasClass('open')) {
                jQuery(this).parent().find('div:first').slideUp('fast');
                jQuery(this).removeClass('open');
                jQuery(this).html('<img src="<?php echo get_stylesheet_directory_uri() ?>/kbe_images/kbe_icon-plus.png" />');
            } else {
                jQuery(this).parent().find('div:first').slideDown('fast');
                jQuery(this).html('<img src="<?php echo get_stylesheet_directory_uri() ?>/kbe_images/kbe_icon-minus.png" />');
                jQuery(this).addClass('open');
            }
        });

    });
</script>
<?php
}

//=========> KBE Plugin Breadcrumbs
function kbe_breadcrumbs(){
    global $post;
    
    $kbe_slug_case = ucwords(strtolower(KBE_PLUGIN_SLUG));
                        
    $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    
    if(strpos($url, 'knowledgebase_category') || strpos($url, 'kbe_taxonomy')){
        $kbe_bc_name = get_queried_object()->name;
?>
        <ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><a href="<?php echo home_url()."/".KBE_PLUGIN_SLUG; ?>"><?php _e($kbe_slug_case ,'kbe'); ?></a></li>
            <li><?php echo $kbe_bc_name; ?></li>
        </ul>
<?php
    }elseif(strpos($url, 'kbe_tags') || strpos($url, 'knowledgebase_tags')){
        $kbe_bc_tag_name = get_queried_object()->name;
?>
	<ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><a href="<?php echo home_url()."/".KBE_PLUGIN_SLUG; ?>"><?php _e($kbe_slug_case ,'kbe'); ?></a></li>
            <li><?php echo $kbe_bc_tag_name; ?></li>
        </ul>
<?php
    }elseif(strpos($url, '?s')){
	$kbe_search_word = $_GET['s'];
?>
	<ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><a href="<?php echo home_url()."/".KBE_PLUGIN_SLUG; ?>"><?php _e($kbe_slug_case ,'kbe'); ?></a></li>
            <li><?php echo $kbe_search_word; ?></li>
        </ul>
<?php
    }elseif(is_single()){
        $kbe_bc_term = get_the_terms( $post->ID , KBE_POST_TAXONOMY );
?>
        <ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><a href="<?php echo home_url()."/".KBE_PLUGIN_SLUG; ?>"><?php _e($kbe_slug_case ,'kbe'); ?></a></li>
        <?php
            foreach($kbe_bc_term as $kbe_tax_term){
        ?>
                <li>
                    <a href="<?php echo get_term_link($kbe_tax_term->slug, KBE_POST_TAXONOMY) ?>">
                        <?php echo $kbe_tax_term->name ?>
                    </a>
                </li>
        <?php
            }
        ?>
            <li>
                <?php
                    if(strlen(the_title('', '', FALSE) >= 50)) {
                        echo substr(the_title('', '', FALSE), 0, 50)."....";
                    } else {
                        the_title();
                    }
                ?>
            </li>
        </ul>
<?php
    }else{
?>
        <ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><?php _e($kbe_slug_case ,'kbe'); ?></li>
        </ul>
<?php
    }
}

//=========>  KBE Knowledgebase Shortcode
function kbe_shortcode( $atts, $content = null ){
    $return_string = require 'template/archive-kbe_knowledgebase.php';
    wp_reset_query();
    return $return_string;
}
add_action('init', 'register_kbe_shortcodes');
function register_kbe_shortcodes(){
    add_shortcode('kbe_knowledgebase', 'kbe_shortcode');
}

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

//=========>  KBE Custom Taxonomy Order
function kbe_tax_order($orderby, $args){
    $kbe_tax = "kbe_taxonomy";
    
    if($args['orderby'] == 'terms_order'){
        return 't.terms_order';
    }elseif($kbe_tax == 1 && !isset($_GET['orderby'])){
        return 't.terms_order';
    }else{
        return $orderby;
    }
}
add_filter('get_terms_orderby', 'kbe_tax_order', 10, 2);

//=========>  KBE Search Template
function template_chooser($template){
    global $wp_query;
	
    $post_type = get_query_var('post_type');
    
    if( $wp_query->is_search && $post_type == 'kbe_knowledgebase' ){
        return locate_template('wp_knowledgebase/kbe_search.php');  //  redirect to kbe_search.php
    }
	
    return $template;   
}
add_filter('template_include', 'template_chooser');

//=========> KBE Article Tags
function kbe_show_tags(){
    $kbe_tags_term = get_the_terms( $post->ID , KBE_POST_TAGS );
    if($kbe_tags_term){
?>
    <div class="kbe_tags_div">
        <div class="kbe_tags_icon"></div>
        <ul>
        <?php
            foreach($kbe_tags_term as $kbe_tag){
        ?>
            <li>
                <a href="<?php echo get_term_link($kbe_tag->slug, KBE_POST_TAGS) ?>">
                    <?php echo $kbe_tag->name; ?>
                </a>
            </li>
        <?php
            }
        ?>
        </ul>
    </div>
<?php
    }
}

//=========> KBE Dynamic CSS
add_action('wp_head', 'count_bg_color');
function count_bg_color(){
?>
<style type="text/css">
<?php
    $kbe_bgcolor = get_option('kbe_bgcolor');
?>
    #kbe_content h2 span.kbe_count {
        background-color: <?php echo $kbe_bgcolor; ?> !important;
    }
    #kbe_content .kbe_child_category h3 span.kbe_count {
        background-color: <?php echo $kbe_bgcolor; ?> !important;
    }
    .kbe_widget .kbe_tags_widget a{
        text-decoration: none;
        color: <?php echo $kbe_bgcolor; ?> !important;
    }
    .kbe_widget .kbe_tags_widget a:hover{
        text-decoration: underline;
        color: <?php echo $kbe_bgcolor; ?> !important;
    }
</style>
<?php
}