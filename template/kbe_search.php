<?php
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
        <span class="kbe_no_result">Search result not found......</span>
<?php
    }
} else {
    get_header('knowledgebase');
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
    <?php
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
                            Read more...
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
    <div class="kbe_aside <?php echo $kbe_sidebar_class; ?>">
    <?php
        if((KBE_SIDEBAR_INNER == 2) || (KBE_SIDEBAR_INNER == 1)){
            dynamic_sidebar('kbe_cat_widget');
        }
    ?>
    </div>
    <!--/aside-->
    
</div>
<?php
    get_footer('knowledgebase');
}
?>