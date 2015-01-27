<?php
if(!empty($_GET['ajax']) ? $_GET['ajax'] : null) {
    if ( have_posts() ) {
?>
        <ul id="search-result">
    <?php
        while (have_posts()) : the_post();
    ?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            
    <?php
        endwhile;
    ?>
        </ul>

<?php
    } else {
?>
        <span class="kbe_no_result"><?php _e('Search result not found......','kbe'); ?></span>
<?php
    }
} else {
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
        $kbe_search_term = $_GET['s'];
?>
	
            <h1><?php _e('Search Results for: '.$kbe_search_term, 'kbe'); ?></h1>
        
            <!--leftcol-->
            <div class="kbe_leftcol" >
                <!--<articles>-->
                <div class="kbe_articles_search">
                    <ul>
                <?php
                    while(have_posts()) :
                        the_post();
                ?>
                    <li>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                        <span class="post-meta">
                            Post By
                            <?php the_author(); ?>
                            | Date : <?php the_time('j F Y'); ?>
                        </span>
                        <p><?php echo kbe_short_content(300); ?></p>
                        <div class="kbe_read_more">
                            <a href="<?php the_permalink(); ?>">
                                <?php _e('Read more...','kbe') ?>
                            </a>
                        </div>
                    </li>
                <?php
                    endwhile;
                ?>
                    </ul>
                </div>
            </div>
            <!--/leftcol-->
        
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
<?php
    get_footer();
}
?>