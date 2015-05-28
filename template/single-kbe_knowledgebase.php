<?php
    get_header();
    
    // Classes For main content div
    if(KBE_SIDEBAR_INNER == 0) {
        $kbe_content_class = 'class="kbe_content_full"';
    } elseif(KBE_SIDEBAR_INNER == 1) {
        $kbe_content_class = 'class="kbe_content_right"';
    } elseif(KBE_SIDEBAR_INNER == 2) {
        $kbe_content_class = 'class="kbe_content_left"';
    }
    
    // Classes For sidebar div
    if(KBE_SIDEBAR_INNER == 0) {
        $kbe_sidebar_class = 'kbe_aside_none';
    } elseif(KBE_SIDEBAR_INNER == 1) {
        $kbe_sidebar_class = 'kbe_aside_left';
    } elseif(KBE_SIDEBAR_INNER == 2) {
        $kbe_sidebar_class = 'kbe_aside_right';
    }
?>
<div id="kbe_container">
    <!--Breadcrum-->
    <?php
        if(KBE_BREADCRUMBS_SETTING == 1){
    ?>
        <div class="kbe_breadcrum">
            <?php echo kbe_breadcrumbs(); ?>
        </div>
    <?php
        }
    ?>
    <!--/Breadcrum-->
        
    <!--search field-->
    <?php
        if(KBE_SEARCH_SETTING == 1){
            kbe_search_form();
        }
    ?>
    <!--/search field-->
        
    <!--content-->
    <div id="kbe_content" <?php echo $kbe_content_class; ?>>
        <!--Content Body-->
        <div class="kbe_leftcol" >
        <?php
            while(have_posts()) :
                the_post();

                //  Never ever delete it !!!
                kbe_set_post_views(get_the_ID());
        ?>
                <h1><?php the_title(); ?></h1>
            <?php 
                the_content();
                if(KBE_COMMENT_SETTING == 1){
            ?>
                    <div class="kbe_reply">
            <?php
                        comments_template("wp_knowledgebase/kbe_comments.php");
            ?>
                    </div> 
        <?php
                }
            endwhile;

            //  Never ever delete it !!!
            kbe_get_post_views(get_the_ID());
        ?>
        </div>
        <!--/Content Body-->

    </div>
	
    <!--aside-->
    <div class="kbe_aside <?php echo $kbe_sidebar_class; ?>">
    <?php
        if((KBE_SIDEBAR_INNER == 2) || (KBE_SIDEBAR_INNER == 1)){
            dynamic_sidebar('kbe_cat_widget');
        }
    ?>
    </div>
    <!--/aside-->
    
</div>
<?php get_footer(); ?>