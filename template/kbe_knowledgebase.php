<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*=========
Template Name: KBE
=========*/

global $wpdb;

get_header( 'knowledgebase' );

// load the style and script
wp_enqueue_style( 'kbe_theme_style' );
if ( KBE_SEARCH_SETTING == 1 ) {
	wp_enqueue_script( 'kbe_live_search' );
}

// Classes For main content div
if ( KBE_SIDEBAR_HOME == 0 ) {
	$kbe_content_class = 'class="kbe_content_full"';
} elseif ( KBE_SIDEBAR_HOME == 1 ) {
	$kbe_content_class = 'class="kbe_content_right"';
} elseif ( KBE_SIDEBAR_HOME == 2 ) {
	$kbe_content_class = 'class="kbe_content_left"';
}

// Classes For sidebar div
if ( KBE_SIDEBAR_HOME == 0 ) {
	$kbe_sidebar_class = 'kbe_aside_none';
} elseif ( KBE_SIDEBAR_HOME == 1 ) {
	$kbe_sidebar_class = 'kbe_aside_left';
} elseif ( KBE_SIDEBAR_HOME == 2 ) {
	$kbe_sidebar_class = 'kbe_aside_right';
}

// Breadcrumbs
?><div id="kbe_container"><?php

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
	?><div id="kbe_content" <?php echo $kbe_content_class; ?>>
        <h1><?php echo get_the_title( KBE_PAGE_TITLE ); ?></h1>

        <!--leftcol-->
        <div class="kbe_leftcol">
            <div class="kbe_categories"><?php

			$kbe_cat_args = array(
				'orderby'    => 'terms_order',
				'order'      => 'ASC',
				'hide_empty' => true,
				'parent'     => 0
			);

			$kbe_terms = get_terms( KBE_POST_TAXONOMY, $kbe_cat_args );

			foreach ( $kbe_terms as $kbe_taxonomy ) {
				$kbe_term_id   = $kbe_taxonomy->term_id;
				$kbe_term_slug = $kbe_taxonomy->slug;
				$kbe_term_name = $kbe_taxonomy->name;

				$kbe_taxonomy_parent_count = $kbe_taxonomy->count;

					$children = get_term_children( $kbe_term_id, KBE_POST_TAXONOMY );

					$kbe_count_sum = $wpdb->get_var( "
						SELECT Sum(count)
						FROM {$wpdb->prefix}term_taxonomy
						WHERE taxonomy = '" . KBE_POST_TAXONOMY . "'
						And parent = $kbe_term_id
					" );

					$kbe_count_sum_parent = '';

					if ( $children ) {
						$kbe_count_sum_parent = $kbe_count_sum + $kbe_taxonomy_parent_count;
					} else {
						$kbe_count_sum_parent = $kbe_taxonomy_parent_count;
					}

				?><div class="kbe_category">
                    <h2>
                        <span class="kbe_count"><?php
							echo sprintf( _n( '%d Article', '%d Articles', $kbe_count_sum_parent, 'wp-knowledgebase' ), $kbe_count_sum_parent );
						?></span>
                        <a href="<?php echo get_term_link( $kbe_term_slug, 'kbe_taxonomy' ); ?>"><?php
							echo $kbe_term_name;
						?></a>
                    </h2><?php

					$kbe_child_cat_args = array(
						'orderby'    => 'terms_order',
						'order'      => 'ASC',
						'parent'     => $kbe_term_id,
						'hide_empty' => true,
					);

					$kbe_child_terms = get_terms( KBE_POST_TAXONOMY, $kbe_child_cat_args );

					if ( $kbe_child_terms ) {

						?><div class="kbe_child_category" style="display: none;"><?php
							foreach ( $kbe_child_terms as $kbe_child_term ) {
								$kbe_child_term_id   = $kbe_child_term->term_id;
								$kbe_child_term_slug = $kbe_child_term->slug;
								$kbe_child_term_name = $kbe_child_term->name;

								?><h3>
                                    <span class="kbe_count"><?php
										echo sprintf( _n( '%d Article', '%d Articles', $kbe_child_term->count, 'wp-knowledgebase' ), $kbe_child_term->count );
									?></span>
                                    <a href="<?php echo get_term_link( $kbe_child_term_slug, 'kbe_taxonomy' ); ?>"><?php
										echo $kbe_child_term_name;
									?></a>
                                </h3>
                                <ul class="kbe_child_article_list"><?php

									$kbe_child_post_args = array(
										'post_type'      => KBE_POST_TYPE,
										'posts_per_page' => KBE_ARTICLE_QTY,
										'orderby'        => 'menu_order',
										'order'          => 'ASC',
										'tax_query'      => array(
											array(
												'taxonomy' => KBE_POST_TAXONOMY,
												'field'    => 'term_id',
												'terms'    => $kbe_child_term_id
											)
										)
									);

									$kbe_child_post_qry = new WP_Query( $kbe_child_post_args );
									if ( $kbe_child_post_qry->have_posts() ) :
										while ( $kbe_child_post_qry->have_posts() ) :
											$kbe_child_post_qry->the_post();

											?><li>
                                                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php
													the_title();
												?></a>
                                            </li><?php

										endwhile;
									else :
										echo 'No posts';
									endif;

								?></ul><?php
							}
					?></div><?php

				}

				?><ul class="kbe_article_list"><?php

					$kbe_tax_post_args = array(
						'post_type'      => KBE_POST_TYPE,
						'posts_per_page' => KBE_ARTICLE_QTY,
						'orderby'        => 'menu_order',
						'order'          => 'ASC',
						'post_parent'    => 0,
						'tax_query'      => array(
							array(
								'taxonomy' => KBE_POST_TAXONOMY,
								'field'    => 'slug',
								'terms'    => $kbe_term_slug,
								'include_children' => false
							)
						)
					);

					$kbe_tax_post_qry = new WP_Query( $kbe_tax_post_args );

					if ( $kbe_tax_post_qry->have_posts() ) :
						while ( $kbe_tax_post_qry->have_posts() ) :
							$kbe_tax_post_qry->the_post();

							?><li>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </li><?php

						endwhile;
					else :
						echo 'No posts';
					endif;

					?></ul>
                </div><?php

			}

			?></div>
        </div>
        <!--/leftcol-->

    </div>
    <!--content-->

    <!--aside-->
    <div class="kbe_aside <?php echo $kbe_sidebar_class; ?>"><?php

		if ( (KBE_SIDEBAR_HOME == 2) || (KBE_SIDEBAR_HOME == 1) ) {
			dynamic_sidebar( 'kbe_cat_widget' );
		}

	?></div>
    <!--/aside-->

</div><?php
get_footer( 'knowledgebase' );
