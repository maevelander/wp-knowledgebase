<?php
    get_header();
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
<?php
    if(KBE_SIDEBAR_INNER == 0){
?>
        <div id="kbe_content" class="kbe_content_full">
<?php
    }elseif(KBE_SIDEBAR_INNER == 1){
?>
        <div id="kbe_content" class="kbe_content_right">
<?php
    }elseif(KBE_SIDEBAR_INNER == 2){
?>
        <div id="kbe_content" class="kbe_content_left">
<?php
    }
?>
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
                            comments_template("/kbe_comments.php");
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
<?php
    if(KBE_SIDEBAR_INNER == 0){
?>
        <div class="kbe_aside kbe_aside_none">
<?php
    }elseif(KBE_SIDEBAR_INNER == 1){
?>
	<div class="kbe_aside kbe_aside_left">
<?php
    }elseif(KBE_SIDEBAR_INNER == 2){
?>
	<div class="kbe_aside kbe_aside_right">
<?php
    }
        if((KBE_SIDEBAR_INNER == 2) || (KBE_SIDEBAR_INNER == 1)){
            dynamic_sidebar('kbe_cat_widget');
        }
?>
        </div>
        <!--/aside-->
    
</div>
<?php get_footer(); ?>