<?php
/*
  Plugin Name: WP Knowledgebase
  Plugin URI: http://wordpress.org/plugins/wp-knowledgebase
  Description: Simple and flexible knowledgebase plugin for WordPress
  Author: Enigma Plugins
  Version: 1.1.4
  Author URI: http://enigmaplugins.com
  Requires at least: 2.7
 */
 
 define( 'KBE_PLUGIN_VERSION', '1.1.4' );

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

//=========> Register plugin settings
add_action('admin_init', 'kbe_register_settings');
function kbe_register_settings() {
    register_setting( 'kbe_settings', 'kbe_settings', 'kbe_validate_settings' );
}

/**
 * Sanitize and validate plugin settings
 * @param  array $input
 * @return array
 * @since  1.1.0
 */
function kbe_validate_settings( $input ) {
    $input['kbe_plugin_slug'] = isset( $input['kbe_plugin_slug'] ) ? sanitize_title( $input['kbe_plugin_slug'] ) : '';
    $input['kbe_article_qty'] = intval( $input['kbe_article_qty'] );

    $input['kbe_search_setting'] =  isset( $input['kbe_search_setting'] ) && $input['kbe_search_setting'] ? 1 : 0 ;
    $input['kbe_breadcrumbs_setting'] =  isset( $input['kbe_breadcrumbs_setting'] ) && $input['kbe_breadcrumbs_setting'] ? 1 : 0 ;

    $sidebar_positions = array( 0, 1, 2 );

    $input['kbe_sidebar_home'] = isset( $input['kbe_sidebar_home'] ) && in_array( $input['kbe_sidebar_home'], $sidebar_positions ) ? intval( $input['kbe_sidebar_home'] ) : 0;
    $input['kbe_sidebar_inner'] = isset( $input['kbe_sidebar_inner'] ) && in_array( $input['kbe_sidebar_inner'], $sidebar_positions ) ? intval( $input['kbe_sidebar_inner'] ) : 0;

    $input['kbe_comments_setting'] =  isset( $input['kbe_comments_setting'] ) && $input['kbe_comments_setting'] ? 1 : 0 ;

    $input['kbe_bgcolor'] = isset( $input['kbe_bgcolor'] ) ?  $input['kbe_bgcolor'] : '';

    return $input;
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
	
    // Delete index file from directory
    $kbe_get_index_file = plugin_dir_path(__FILE__).'index.php';
    if (file_exists($kbe_get_index_file)){
        unlink($kbe_get_index_file);
    }
    
    // serialize settings data
    $kbe_settings = get_option( 'kbe_settings' );

    $kbe_article_qty = get_option('kbe_article_qty');
    $kbe_plugin_slug = get_option('kbe_plugin_slug');
    $kbe_search_setting = get_option('kbe_search_setting');
    $kbe_breadcrumbs_setting = get_option('kbe_breadcrumbs_setting');
    $kbe_sidebar_home = get_option('kbe_sidebar_home');
    $kbe_sidebar_inner = get_option('kbe_sidebar_inner');
    $kbe_comments_setting = get_option('kbe_comments_setting');
    $kbe_bgcolor = get_option('kbe_bgcolor');
    
    if($kbe_article_qty || $kbe_plugin_slug || $kbe_search_setting || $kbe_breadcrumbs_setting || $kbe_sidebar_home
       || $kbe_sidebar_inner || $kbe_comments_setting || $kbe_bgcolor) {
        $kbe_settings_arr = array(
                                'kbe_plugin_slug' => $kbe_plugin_slug,
                                'kbe_article_qty' => $kbe_article_qty,
                                'kbe_search_setting' => $kbe_search_setting,
                                'kbe_breadcrumbs_setting' => $kbe_breadcrumbs_setting,
                                'kbe_sidebar_home' => $kbe_sidebar_home,
                                'kbe_sidebar_inner' => $kbe_sidebar_inner,
                                'kbe_comments_setting' => $kbe_comments_setting,
                                'kbe_bgcolor' => $kbe_bgcolor,
                            );
        $kbe_settings_ser = serialize($kbe_settings_arr);

        add_option('kbe_settings', $kbe_settings_ser, '', 'yes');

        delete_option('kbe_article_qty');
        delete_option('kbe_plugin_slug');
        delete_option('kbe_search_setting');
        delete_option('kbe_breadcrumbs_setting');
        delete_option('kbe_sidebar_home');
        delete_option('kbe_sidebar_inner');
        delete_option('kbe_comments_setting');
        delete_option('kbe_bgcolor');
   }
}
register_activation_hook(__FILE__, 'wp_kbe_hooks');

//=========> Define plugin path
define( 'WP_KNOWLEDGEBASE', plugin_dir_url(__FILE__));

//  define options values
$kbe_settings = get_option('kbe_settings');
if (isset($kbe_settings['kbe_article_qty'])){
    define('KBE_ARTICLE_QTY', $kbe_settings['kbe_article_qty']);
}
if (isset($kbe_settings['kbe_plugin_slug'])){
    define('KBE_PLUGIN_SLUG', $kbe_settings['kbe_plugin_slug']);
}
if (isset($kbe_settings['kbe_search_setting'])){
    define('KBE_SEARCH_SETTING', $kbe_settings['kbe_search_setting']);
}
if (isset($kbe_settings['kbe_breadcrumbs_setting'])){
    define('KBE_BREADCRUMBS_SETTING', $kbe_settings['kbe_breadcrumbs_setting']);
}
if (isset($kbe_settings['kbe_sidebar_home'])){
    define('KBE_SIDEBAR_HOME', $kbe_settings['kbe_sidebar_home']);
}
if (isset($kbe_settings['kbe_sidebar_inner'])){
    define('KBE_SIDEBAR_INNER', $kbe_settings['kbe_sidebar_inner']);
}
if (isset($kbe_settings['kbe_comments_setting'])){
    define('KBE_COMMENT_SETTING', $kbe_settings['kbe_comments_setting']);
}
if (isset($kbe_settings['kbe_bgcolor'])){
    define('KBE_BG_COLOR', $kbe_settings['kbe_bgcolor']);
}
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

//=========> Enqueue KBE Style file in header.php
function kbe_styles(){
    if( file_exists( get_stylesheet_directory() . '/wp_knowledgebase/kbe_style.css' ) ){
        $stylesheet = get_stylesheet_directory_uri() . '/wp_knowledgebase/kbe_style.css'; 
    } else {
        $stylesheet = WP_KNOWLEDGEBASE. 'template/kbe_style.css';
    }
    wp_register_style ( 'kbe_theme_style', $stylesheet, array(), KBE_PLUGIN_VERSION );
    wp_enqueue_style('kbe_theme_style');
}
add_action('wp_enqueue_scripts', 'kbe_styles');

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

add_action('wp_enqueue_scripts', 'kbe_live_search');
function kbe_live_search(){
    wp_register_script( 'kbe_live_search', WP_KNOWLEDGEBASE.  'js/jquery.livesearch.js', array('jquery'), KBE_PLUGIN_VERSION, true );
    wp_enqueue_script('kbe_live_search');
}

//=========> Enqueue plugin files
$kbe_address_bar = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if(strpos($kbe_address_bar, "post_type=kbe_knowledgebase")) {
    add_action('admin_init', 'wp_kbe_scripts');
    function wp_kbe_scripts(){
        wp_register_style('kbe_admin_css', WP_KNOWLEDGEBASE.'css/kbe_admin_style.css');
        wp_enqueue_style('kbe_admin_css');
    }
}

//=========> Enqueue color picker
add_action('admin_init', 'enqueue_color_picker');
function enqueue_color_picker($hook_suffix) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('cp-script-handle', WP_KNOWLEDGEBASE.'js/color_picker.js', array( 'wp-color-picker' ), false, true);
}

function st_add_live_search () {
    if( ( KBE_SEARCH_SETTING == 1 ) && ( wp_script_is( 'kbe_live_search', 'enqueued' ) ) ){
?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            var kbe = jQuery('#live-search #s').val();
            jQuery('#live-search #s').liveSearch({url: '<?php echo home_url(); ?>/?ajax=on&post_type=kbe_knowledgebase&s='});
        });
    </script>
<?php }
}
add_action('wp_footer', 'st_add_live_search');

/**
 * Load a template.
 *
 * Handles template usage so that we can use our own templates instead of the themes.
 *
 * Templates are in the 'templates' folder. knowledgebase looks for theme
 * overrides in /theme/wp_knowledgebase/ by default
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
    } elseif ( is_post_type_archive( 'kbe_knowledgebase' ) || is_page( KBE_PAGE_TITLE ) ) {
        $file   = 'kbe_knowledgebase.php';
        $find[] = $file;
        $find[] = $template_path . $file;
    }

    if ( $file ) {
        $template = locate_template( array_unique( $find ) ) ;
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
    if( KBE_SEARCH_SETTING == 1 ){
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
</script>
<?php }
}

//=========> KBE Plugin Breadcrumbs
function kbe_breadcrumbs(){
    global $post;
    
    $kbe_slug_case = ucwords(strtolower(KBE_PLUGIN_SLUG));
                        
    $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    
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
    $return_string = require 'template/kbe_knowledgebase.php';
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
        if(file_exists(get_stylesheet_directory() . '/wp_knowledgebase/kbe_search.php')) {
            return get_stylesheet_directory() . '/wp_knowledgebase/kbe_search.php';
        } else {
            return plugin_dir_path(__FILE__)."template/kbe_search.php";
        }  //  redirect to kbe_search.php
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
add_action('wp_enqueue_scripts', 'count_bg_color');
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