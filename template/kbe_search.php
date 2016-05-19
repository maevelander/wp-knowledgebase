<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Classes For main content div
if ( KBE_SIDEBAR_INNER == 0 ) {
	$kbe_content_class = 'class="kbe_content_full"';
} elseif ( KBE_SIDEBAR_INNER == 1 ) {
	$kbe_content_class = 'class="kbe_content_right"';
} elseif ( KBE_SIDEBAR_INNER == 2 ) {
	$kbe_content_class = 'class="kbe_content_left"';
}

// Classes For sidebar div
if ( KBE_SIDEBAR_INNER == 0 ) {
	$kbe_sidebar_class = 'kbe_aside_none';
} elseif ( KBE_SIDEBAR_INNER == 1 ) {
	$kbe_sidebar_class = 'kbe_aside_left';
} elseif ( KBE_SIDEBAR_INNER == 2 ) {
	$kbe_sidebar_class = 'kbe_aside_right';
}

if ( ! empty( $_GET['ajax'] ) ? $_GET['ajax'] : null ) {

	if ( have_posts() ) {

		?><ul id="search-result"><?php

			while ( have_posts() ) : the_post();
				?><li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li><?php
			endwhile;
		?></ul><?php

	} else {
		?><span class="kbe_no_result"><?php _e( 'Search result not found...', 'wp-knowledgebase' ); ?></span><?php
	}

} else {

	get_header( 'knowledgebase' );
	// load the style and script
	wp_enqueue_style( 'kbe_theme_style' );
	if ( KBE_SEARCH_SETTING == 1 ) {
		wp_enqueue_script( 'kbe_live_search' );
	}

	?><div id="kbe_container"><?php

		// Breadcrumbs
		if ( KBE_BREADCRUMBS_SETTING == 1 ) {
			?><div class="kbe_breadcrum"><?php
				kbe_breadcrumbs();
			?></div><?php
		}

		// Search field
		if ( KBE_SEARCH_SETTING == 1 ) {
			kbe_search_form();
		}

		// Content
		?><div id="kbe_content" <?php echo $kbe_content_class; ?>><?php

			?><h1><?php echo sprintf( __( 'Search Results for: %s', 'wp-knowledgebase' ), esc_html( $_GET['s'] ) ); ?></h1>

            <!--leftcol-->
            <div class="kbe_leftcol" >
                <!--<articles>-->
                <div class="kbe_articles_search">
                    <ul><?php

						while ( have_posts() ) :
							the_post();
							?><li>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <span class="post-meta">Post By <?php the_author(); ?> | Date : <?php the_time( 'j F Y' ); ?></span>
                                <p><?php echo kbe_short_content( 300 ); ?></p>
                                <div class="kbe_read_more">
                                    <a href="<?php the_permalink(); ?>"><?php _e( 'Read more...', 'wp-knowledgebase' ); ?></a>
                                </div>
                            </li><?php
						endwhile;

					?></ul>
                </div>
            </div>

        </div>

        <!--aside-->
        <div class="kbe_aside <?php echo $kbe_sidebar_class; ?>"><?php
			if ( (KBE_SIDEBAR_INNER == 2) || (KBE_SIDEBAR_INNER == 1) ) {
				dynamic_sidebar( 'kbe_cat_widget' );
			}
		?></div>

    </div><?php

	get_footer( 'knowledgebase' );

}
