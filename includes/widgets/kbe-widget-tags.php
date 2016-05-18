<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*===============
	KBE Tags Widget
	===============*/

//========= Custom Knowledgebase Tags Widget
function kbe_tags_widgets() {
	register_widget( 'kbe_Tags_Widget' );
}
add_action( 'widgets_init', 'kbe_tags_widgets' );

//========= Custom Knowledgebase Tags Widget Body
class kbe_Tags_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'kbe_tags_widgets', // Base ID
			__( 'Knowledgebase Tags', 'wp-knowledgebase' ), // Name
			array(
				'description' => __( 'WP Knowledgebase tags widget to show tags on the site', 'wp-knowledgebase' ),
				'classname'   => 'kbe'
			)
		);
	}

	//=======> How to display the widget on the screen.
	function widget( $args, $widgetData ) {
		//=======> Our variables from the widget settings.
		$kbe_widget_tag_title = $widgetData['txtKbeTagsHeading'];
		$kbe_widget_tag_count = $widgetData['txtKbeTagsCount'];
		$kbe_widget_tag_style = $widgetData['txtKbeTagsStyle'];

		//=======> widget body
		echo $args['before_widget'];
		echo '<div class="kbe_widget kbe_widget_article">';

			if ( $kbe_widget_tag_title ) {
				echo '<h2>' . $kbe_widget_tag_title . '</h2>';
			}

			?><div class="kbe_tags_widget"><?php

					$tag_cloud_args = array(
						'smallest'  => 	12,
						'largest'   => 	30,
						'unit'      => 	'px',
						'number'    => 	$kbe_widget_tag_count,
						'format'    => 	$kbe_widget_tag_style,
						'separator' => 	"\n",
						'orderby'   => 	'name',
						'order'     => 	'ASC',
						'exclude'   => 	null,
						'include'   => 	null,
						'link'      => 	'view',
						'taxonomy'  => 	KBE_POST_TAGS,
						'echo'      => 	true
					);

					wp_tag_cloud( $tag_cloud_args );

					wp_reset_query();

				?></div><?php

		echo '</div>';
		echo $args['after_widget'];
	}

	//Update the widget
	function update( $new_widgetData, $old_widgetData ) {
		$widgetData = $old_widgetData;

		//Strip tags from title and name to remove HTML
		$widgetData['txtKbeTagsHeading'] = $new_widgetData['txtKbeTagsHeading'];
		$widgetData['txtKbeTagsCount']   = $new_widgetData['txtKbeTagsCount'];
		$widgetData['txtKbeTagsStyle']   = $new_widgetData['txtKbeTagsStyle'];

		return $widgetData;
	}

	function form( $widgetData ) {
		//Set up some default widget settings.
		$widgetData = wp_parse_args( (array) $widgetData, array(
			'txtKbeTagsHeading' => '',
			'txtKbeTagsCount'   => 10,
			'txtKbeTagsStyle'   => 'flat',
		) );

		?><p>
            <label for="<?php echo $this->get_field_id( 'txtKbeTagsHeading' ); ?>"><?php _e( 'Title:', 'wp-knowledgebase' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'txtKbeTagsHeading' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'txtKbeTagsHeading' ); ?>" value="<?php echo $widgetData['txtKbeTagsHeading']; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'txtKbeTagsCount' ); ?>"><?php _e( 'Tags Quantity:', 'wp-knowledgebase' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'txtKbeTagsCount' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'txtKbeTagsCount' ); ?>" value="<?php echo $widgetData['txtKbeTagsCount']; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'txtKbeTagsStyle' ); ?>"><?php _e( 'Tags Style:', 'wp-knowledgebase' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'txtKbeTagsStyle' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'txtKbeTagsStyle' ); ?>">
                <option <?php selected( $widgetData['txtKbeTagsStyle'], 'flat' ); ?> value="flat"><?php _e( 'Flat', 'wp-knowledgebase' ); ?></option>
                <option <?php selected( $widgetData['txtKbeTagsStyle'], 'list' ); ?> value="list"><?php _e( 'List', 'wp-knowledgebase' ); ?></option>
            </select>
        </p><?php
	}

}
