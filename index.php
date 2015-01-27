<?php
/*
  Plugin Name: WP Knowledgebase
  Plugin URI: http://wordpress.org/plugins/wp-knowledgebase
  Description: Simple and flexible knowledgebase plugin for WordPress
  Author: Enigma Plugins
  Version: 1.0.2
  Author URI: http://enigmaplugins.com
 */
 
//=========> Hide all Reporting Errors
error_reporting(0);

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
function wp_kbe_hooks() {
    global $wpdb;
    /*Creat "term_order" Field in "wp_terms" Table for sortable order*/
    $term_order_qry = $wpdb->query("SHOW COLUMNS FROM $wpdb->terms LIKE 'terms_order'");
    if($term_order_qry == 0){
        $wpdb->query("ALTER TABLE $wpdb->terms ADD `terms_order` INT(4) NULL DEFAULT '0'");
    }
    
    $kbe_pre = $wpdb->prefix;

    $kbe_pageSql = "Select * From ".$kbe_pre."posts Where post_content like '%[kbe_knowledgebase]%' and post_type = 'page'";
    $kbe_pageQry = mysql_query($kbe_pageSql);
    $kbe_pageNum = mysql_num_rows($kbe_pageQry);

    if($kbe_pageNum == 0){
        //  Insert a "Knowledgebase" page
        $tableSql = "SELECT Max(ID) As maxId FROM wp_posts";
        $tableQry = mysql_query($tableSql);
        $tableRow = mysql_fetch_array($tableQry);

        $maxId = $tableRow['maxId'];
        $maxId = $maxId + 1;

        $now = date('Y-m-d H:i:s');
        $now_gmt = gmdate('Y-m-d H:i:s');
        $guid = get_option('home') . '/?page_id='.$maxId;
        $user_id = get_current_user_id();

        $table_posts	=	$wpdb->prefix.'posts';

        $data_posts = array(
                            'post_author'       =>  $user_id,
                            'post_date'         =>  $now,
                            'post_date_gmt'     =>  $now_gmt,
                            'post_content'      =>  '[kbe_knowledgebase]',
                            'post_title'        =>  'Knowledgebase',
                            'post_excerpt'      =>  '',
                            'post_status'       =>  'publish',
                            'comment_status'         =>  'closed',
                            'ping_status'       =>  'closed',
                            'post_password'      =>  '',
                            'post_name'        =>  'knowledgebase',
                            'to_ping'      =>  '',
                            'pinged'       =>  '',
                            'post_modified'         =>  $now,
                            'post_modified_gmt'     =>  $now_gmt,
                            'post_content_filtered'      =>  '',
                            'post_parent'        =>  '0',
                            'guid'      =>  $guid,
                            'menu_order'       =>  '0',
                            'post_type'         =>  'page',
                            'post_mime_type'     =>  '',
                            'comment_count'      =>  '0',
                        );
        $wpdb->insert($table_posts,$data_posts) or die(mysql_error());

        //  Insert a page template for knowlwdgebase
        $tempTableSql = "Select post_content, ID From ".$kbe_pre."posts Where post_content Like '%[kbe_knowledgebase]%' And post_type <> 'revision'";
        $tempTableQry = mysql_query($tempTableSql);
        $tempTableRow = mysql_fetch_array($tempTableQry);

        $tempPageId = $tempTableRow['ID'];

        //  Set Knowledgebase page template
        $table_post_meta = $wpdb->prefix.'postmeta';

        $meta_data = array(
            'post_id'       =>  $tempPageId,
            'meta_key'      =>  '_wp_page_template',
            'meta_value'    =>  'kbe_knowledgebase.php'
        );
        $wpdb->insert($table_post_meta,$meta_data) or die(mysql_error());
    }

    $kbe_optSlugSql = "Select * From ".$kbe_pre."options Where option_name like '%kbe_plugin_slug%'";
    $kbe_optSlugQry = mysql_query($kbe_optSlugSql);
    $kbe_optSlugNum = mysql_num_rows($kbe_optSlugQry);

    if($kbe_optSlugNum == 0){
        $table_slug_option = $wpdb->prefix.'options';

        $kbe_slug_data = array(
            'option_name'   => 'kbe_plugin_slug',
            'option_value'   => 'knowledgebase'
        );
        $wpdb->insert($table_slug_option, $kbe_slug_data) or die(mysql_error());
    }

    $kbe_optPageSql = "Select * From ".$kbe_pre."options Where option_name like '%kbe_article_qty%'";
    $kbe_optPageQry = mysql_query($kbe_optPageSql);
    $kbe_optPageNum = mysql_num_rows($kbe_optPageQry);

    if($kbe_optPageNum == 0){
        $table_page_option = $wpdb->prefix.'options';

        $kbe_page_data = array(
            'option_name'   => 'kbe_article_qty',
            'option_value'   => '5'
        );
        $wpdb->insert($table_page_option, $kbe_page_data) or die(mysql_error());
    }
}
register_activation_hook(__FILE__, 'wp_kbe_hooks');

//=========> Delete Data on uninstall
register_uninstall_hook('uninstall.php', $callback);

//=========> Create language folder
load_plugin_textdomain('kbe', false, dirname(plugin_basename(__FILE__)) . '/languages/');

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
	
$getSql = "Select ID From wp_posts Where post_content Like '%[kbe_knowledgebase]%' And post_type <> 'revision'";
$getQry = mysql_query($getSql);
$getRow = mysql_fetch_array($getQry);
$pageId = $getRow['ID'];

define('KBE_PAGE_TITLE', $pageId);

//=========> Plugin menu
add_action('admin_menu', 'kbe_plugin_menu');
function kbe_plugin_menu() {
    add_submenu_page('edit.php?post_type=kbe_knowledgebase', 'Order', 'Order', 'manage_options', 'kbe_order', 'wp_kbe_order');
    add_submenu_page('edit.php?post_type=kbe_knowledgebase', 'Settings', 'Settings', 'manage_options', 'kbe_options', 'wp_kbe_options');
}

//=========> Enqueue KBE Style file in header.php
add_action('wp_head', 'kbe_article_style');
function kbe_article_style(){
    wp_register_style('kbe_theme_css', get_template_directory_uri().'/kbe_style.css');
    wp_enqueue_style('kbe_theme_css');
}

add_action('wp_print_scripts', 'kbe_live_search');
function kbe_live_search(){
    wp_register_script('kbe_live_search', WP_KNOWLEDGEBASE.'js/jquery.livesearch.js');
    wp_enqueue_script('kbe_live_search');
}

//=========> Enqueue plugin files
add_action('admin_init', 'wp_kbe_scripts');
function wp_kbe_scripts(){
    wp_register_style('kbe_admin_css', WP_KNOWLEDGEBASE.'css/kbe_admin_style.css');
    wp_enqueue_style('kbe_admin_css');
}

//=========> Enqueue color picker
add_action('admin_init', 'enqueue_color_picker');
function enqueue_color_picker($hook_suffix) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('cp-script-handle', WP_KNOWLEDGEBASE.'js/color_picker.js', array( 'wp-color-picker' ), false, true);
}

add_action('admin_init', 'load_all_jquery');
function load_all_jquery() {
    wp_enqueue_script("jquery");
    $jquery_ui = array(
        "jquery-ui-sortable"
    );

    foreach($jquery_ui as $script){
        wp_enqueue_script($script);
    }
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

//=========> Move files from Plugin to Current Theme
    
$kbe_theme_dir = get_template_directory();

define('KBE_THEME_DIR', $kbe_theme_dir);

$kbe_archive = KBE_THEME_DIR.'/archive-kbe_knowledgebase.php';
$kbe_kbe = KBE_THEME_DIR.'/kbe_knowledgebase.php';
$kbe_style = KBE_THEME_DIR.'/kbe_style.css';
$kbe_single = KBE_THEME_DIR.'/single-kbe_knowledgebase.php';
$kbe_taxonomy = KBE_THEME_DIR.'/taxonomy-kbe_taxonomy.php';
$kbe_tags = KBE_THEME_DIR.'/taxonomy-kbe_tags.php';
$kbe_comment = KBE_THEME_DIR.'/kbe_comments.php';
$kbe_search = KBE_THEME_DIR.'/kbe_search.php';

if((!file_exists($kbe_style)) or (!file_exists($kbe_kbe))or
   (!file_exists($kbe_single)) or (!file_exists($kbe_taxonomy)) or
   (!file_exists($kbe_tags)) or (!file_exists($kbe_comment)) or
   (!file_exists($kbe_archive)) or (!file_exists($kbe_search))){
    $kbe_plugin_dir = plugin_dir_path( __FILE__ );

    $kbe_plugin_img_dir = $kbe_plugin_dir.'template/images/';
    $kbe_image_dir = KBE_THEME_DIR."/images";

    define('KBE_IMAGE_THEME_DIR', $kbe_image_dir);
    //  check if images folder not exist in kbe folder
    if(!file_exists(KBE_IMAGE_THEME_DIR)){
        mkdir(KBE_THEME_DIR.'/images', 0777, true);
    }

    //  Move Images from plugin folder to theme/kbe/images folder
    $kbe_images = opendir($kbe_plugin_img_dir);
    while($kbe_read_image = readdir($kbe_images)){
        if($kbe_read_image != '.' && $kbe_read_image != '..'){
            if (!file_exists($kbe_read_image)){
                copy($kbe_plugin_img_dir.$kbe_read_image, KBE_IMAGE_THEME_DIR.'/'.$kbe_read_image);
            }
        }
    }

    // Move files plugin kbe folder to theme/kbe folder
    copy($kbe_plugin_dir.'template/archive-kbe_knowledgebase.php', KBE_THEME_DIR.'/archive-kbe_knowledgebase.php');
    copy($kbe_plugin_dir.'template/single-kbe_knowledgebase.php', KBE_THEME_DIR.'/single-kbe_knowledgebase.php');
    copy($kbe_plugin_dir.'template/taxonomy-kbe_taxonomy.php', KBE_THEME_DIR.'/taxonomy-kbe_taxonomy.php');
    copy($kbe_plugin_dir.'template/kbe_knowledgebase.php', KBE_THEME_DIR.'/kbe_knowledgebase.php');
    copy($kbe_plugin_dir.'template/taxonomy-kbe_tags.php', KBE_THEME_DIR.'/taxonomy-kbe_tags.php');
    copy($kbe_plugin_dir.'template/kbe_comments.php', KBE_THEME_DIR.'/kbe_comments.php');
    copy($kbe_plugin_dir.'template/kbe_search.php', KBE_THEME_DIR.'/kbe_search.php');
    copy($kbe_plugin_dir.'template/kbe_style.css', KBE_THEME_DIR.'/kbe_style.css');
}
	
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
</script>
<?php
}

//=========> KBE Plugin Breadcrumbs
function kbe_breadcrumbs(){
    global $post;
                        
    $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    
    if(strpos($url, 'knowledgebase_category') || strpos($url, 'kbe_taxonomy')){
        $kbe_bc_name = get_queried_object()->name;
?>
        <ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><a href="<?php echo get_permalink( get_page_by_path(KBE_PLUGIN_SLUG)); ?>"><?php _e('Knowledgebase' ,'kbe'); ?></a></li>
            <li><?php echo $kbe_bc_name; ?></li>
        </ul>
<?php
    }elseif(strpos($url, 'kbe_tags') || strpos($url, 'knowledgebase_tags')){
        $kbe_bc_tag_name = get_queried_object()->name;
?>
		<ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><a href="<?php echo get_permalink( get_page_by_path(KBE_PLUGIN_SLUG)); ?>"><?php _e('Knowledgebase' ,'kbe'); ?></a></li>
            <li><?php echo $kbe_bc_tag_name; ?></li>
        </ul>
<?php
    }elseif(strpos($url, '?s')){
	$kbe_search_word = $_GET['s'];
?>
	<ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><a href="<?php echo get_permalink( get_page_by_path(KBE_PLUGIN_SLUG)); ?>"><?php _e('Knowledgebase' ,'kbe'); ?></a></li>
            <li><?php echo $kbe_search_word; ?></li>
        </ul>
<?php
    }elseif(is_single()){
        $kbe_bc_term = get_the_terms( $post->ID , KBE_POST_TAXONOMY );
?>
        <ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><a href="<?php echo get_permalink( get_page_by_path(KBE_PLUGIN_SLUG)); ?>"><?php _e('Knowledgebase' ,'kbe'); ?></a></li>
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
            <li><?php echo substr(the_title('', '', FALSE), 0, 50); ?>....</li>
        </ul>
<?php
    }else{
?>
        <ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><?php _e('Knowledgebase' ,'kbe'); ?></li>
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
        echo $content;
    } else {
        $content = substr($content, 0, $limit) . $pad;
        echo $content;
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
        return locate_template('kbe_search.php');  //  redirect to kbe_search.php
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