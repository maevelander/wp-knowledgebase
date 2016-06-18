<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Output breadcrumbs.
 *
 * Output the breadcrumbs. Used within the knowledgebase templates.
 *
 * @since 1.0
 */
function kbe_breadcrumbs() {
	$parts = array(
		array(
			'text' => __( 'Home', 'wp-knowledgebase' ),
			'href' => home_url(),
		),
		array(
			'text' => ucwords( strtolower( KBE_PLUGIN_SLUG ) ),
			'href' => home_url( KBE_PLUGIN_SLUG ),
		),
	);

	if ( is_tax( array( 'kbe_taxonomy', 'knowledgebase_category', 'kbe_tags', 'knowledgebase_tags' ) ) ) {
		$parts[] = array(
			'text' => get_queried_object()->name,
		);
	} elseif ( is_search() ) {
		$parts[] = array(
			'text' => esc_html( $_GET['s'] ),
		);
	} elseif ( is_single() ) {
		$kbe_bc_term = get_the_terms( get_the_ID(), KBE_POST_TAXONOMY );
		foreach ( $kbe_bc_term as $kbe_tax_term ) {
			$parts[] = array(
				'text' => $kbe_tax_term->name,
				'href' => get_term_link( $kbe_tax_term->slug, KBE_POST_TAXONOMY ),
			);
		}

		$title   = strlen( get_the_title() ) >= 50 ? substr( get_the_title(), 0, 50 ) . '&hellip;' : get_the_title();
		$parts[] = array(
			'text' => $title,
		);
	}

	$parts = apply_filters( 'wp_knowledgebase_breadcrumb_parts', $parts );
	?><ul><?php
	foreach ( $parts as $k => $part ) {
		$part = wp_parse_args( $part, array( 'text' => '', 'href' => '' ) );
		?><li class="breadcrumb-part"><a href="<?php echo esc_url( $part['href'] ); ?>"><?php echo wp_kses_post( $part['text'] ); ?></a></li><?php

		$keys = array_keys( $parts );
		if ( $k !== end( $keys ) ) {
			?><li class="separator"> / </li><?php
		}
	}
	?></ul><?php
}

/**
 * Output search form.
 *
 * Output the default search form, located at the top of KB pages by default.
 *
 * @since 1.0
 */
function kbe_search_form() {
	// Life search
	?><div id="live-search">
        <div class="kbe_search_field">
            <form role="search" method="get" id="searchform" class="clearfix" action="<?php echo home_url( '/' ); ?>" autocomplete="off">
                <input type="text" onfocus="if (this.value == '<?php _e( 'Search Articles...', 'wp-knowledgebase' ); ?>') {this.value = '';}" onblur="if (this.value == '')  {this.value = '<?php _e( 'Search Articles...', 'wp-knowledgebase' ); ?>';}" value="<?php _e( 'Search Articles...', 'wp-knowledgebase' ); ?>" name="s" id="s" />
                <!--<ul id="kbe_search_dropdown"></ul>-->
                <input type="hidden" name="post_type" value="kbe_knowledgebase" />
            </form>
        </div>
    </div><?php
}

/**
 * Load a template.
 *
 * Handles template usage so that we can use our own templates instead of the themes.
 *
 * Templates are in the 'templates' folder. knowledgebase looks for theme
 * overrides in /theme/wp_knowledgebase/ by default
 *
 * @param  mixed  $template
 * @return string
 */
function kbe_template_chooser( $template ) {
	$template_path = apply_filters( 'kbe_template_path', 'wp_knowledgebase/' );

	$find = array();
	$file = '';

	if ( is_single() && get_post_type() == 'kbe_knowledgebase' ) {
		$file   = 'single-kbe_knowledgebase.php';
		$find[] = $file;
		$find[] = $template_path . $file;
	} elseif ( is_tax( 'kbe_taxonomy' ) || is_tax( 'kbe_tags' ) ) {
		$term = get_queried_object();

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
			$template = trailingslashit( dirname( __FILE__ ) ) . '../template/' . $file;
		}
	}

	return $template;
}
add_filter( 'template_include', 'kbe_template_chooser' );

/**
 * Replace KB search template.
 *
 * @since 1.0
 *
 * @param $template
 * @return string
 */
function template_chooser( $template ) {
	global $wp_query;

	$post_type = get_query_var( 'post_type' );

	if ( $wp_query->is_search && $post_type == 'kbe_knowledgebase' ) {
		if ( file_exists( get_stylesheet_directory() . '/wp_knowledgebase/kbe_search.php' ) ) {
			return get_stylesheet_directory() . '/wp_knowledgebase/kbe_search.php';
		} else {
			return plugin_dir_path( __FILE__ ) . '/../template/kbe_search.php';
		}
	}

	return $template;
}
add_filter( 'template_include', 'template_chooser' );
