<?php
    get_header();
    
    $kbe_cat_slug = get_queried_object()->slug;
    $kbe_cat_name = get_queried_object()->name;
    
    $kbe_tax_post_args = array(
        'post_type' => KBE_POST_TYPE,
        'posts_per_page' => 999,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'tax_query' => array(
            array(
                'taxonomy' => KBE_POST_TAXONOMY,
                'field' => 'slug',
                'terms' => $kbe_cat_slug
            )
        )
    );
    $kbe_tax_post_qry = new WP_Query($kbe_tax_post_args);
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
            <!--leftcol--> 
            <div class="kbe_leftcol">

                <!--<articles>-->
                <div class="kbe_articles">
                    <h2><strong><?php echo $kbe_cat_name; ?></strong></h2>

                    <ul>
                <?php
                    if($kbe_tax_post_qry->have_posts()) :
                        while($kbe_tax_post_qry->have_posts()) :
                            $kbe_tax_post_qry->the_post();
                ?>
                            <li>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </li>
                <?php
                        endwhile;
                    endif;
                ?>
                    </ul>

                </div>
            </div>
            <!--/leftcol-->
        
	</div>
        <!--/content-->
    
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
<?php
    get_footer();
?>