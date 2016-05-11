<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


//=========> KBE Plugin Breadcrumbs
function kbe_breadcrumbs(){
    global $post;

    $kbe_slug_case = ucwords(strtolower(KBE_PLUGIN_SLUG));

    $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    if(strpos($url, 'knowledgebase_category') || strpos($url, 'kbe_taxonomy')){
        $kbe_bc_name = get_queried_object()->name;

        ?><ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><a href="<?php echo home_url()."/".KBE_PLUGIN_SLUG; ?>"><?php _e($kbe_slug_case ,'kbe'); ?></a></li>
            <li><?php echo $kbe_bc_name; ?></li>
        </ul><?php
        
    }elseif(strpos($url, 'kbe_tags') || strpos($url, 'knowledgebase_tags')){
        $kbe_bc_tag_name = get_queried_object()->name;

        ?><ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><a href="<?php echo home_url()."/".KBE_PLUGIN_SLUG; ?>"><?php _e($kbe_slug_case ,'kbe'); ?></a></li>
            <li><?php echo $kbe_bc_tag_name; ?></li>
        </ul><?php

    }elseif(strpos($url, '?s')){
	$kbe_search_word = $_GET['s'];

        ?><ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><a href="<?php echo home_url()."/".KBE_PLUGIN_SLUG; ?>"><?php _e($kbe_slug_case ,'kbe'); ?></a></li>
            <li><?php echo $kbe_search_word; ?></li>
        </ul><?php

    }elseif(is_single()){
        $kbe_bc_term = get_the_terms( $post->ID , KBE_POST_TAXONOMY );

        ?><ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><a href="<?php echo home_url()."/".KBE_PLUGIN_SLUG; ?>"><?php _e($kbe_slug_case ,'kbe'); ?></a></li><?php
        
            foreach($kbe_bc_term as $kbe_tax_term){
        
                ?><li>
                     <a href="<?php echo get_term_link($kbe_tax_term->slug, KBE_POST_TAXONOMY) ?>">
                        <?php echo $kbe_tax_term->name ?>
                    </a>
                </li><?php
                
            }
        
            ?><li><?php
                
                    if(strlen(the_title('', '', FALSE) >= 50)) {
                        echo substr(the_title('', '', FALSE), 0, 50)."....";
                    } else {
                        the_title();
                    }
                
            ?></li>
        </ul><?php

    }else{

        ?><ul>
            <li><a href="<?php echo home_url(); ?>"><?php _e('Home','kbe'); ?></a></li>
            <li><?php _e($kbe_slug_case ,'kbe'); ?></li>
        </ul><?php

    }
}



//=========> KBE Search Form
function kbe_search_form(){
    // Life search
    ?><div id="live-search">
        <div class="kbe_search_field">
            <form role="search" method="get" id="searchform" class="clearfix" action="<?php echo home_url( '/' ); ?>" autocomplete="off">
                <input type="text" onfocus="if (this.value == '<?php _e("Search Articles...", "kbe") ?>') {this.value = '';}" onblur="if (this.value == '')  {this.value = '<?php _e("Search Articles...", "kbe") ?>';}" value="<?php _e("Search Articles...", "kbe") ?>" name="s" id="s" />
                <!--<ul id="kbe_search_dropdown"></ul>-->
                <input type="hidden" name="post_type" value="kbe_knowledgebase" />
            </form>
        </div>
    </div><?php
}