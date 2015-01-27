<?php
/*===============
    KBE Tags Widget
 ===============*/
 
//========= Custom Knowledgebase Tags Widget
add_action( 'widgets_init', 'kbe_tags_widgets' );
function kbe_tags_widgets() {
    register_widget( 'kbe_Tags_Widget' );
}

//========= Custom Knowledgebase Tags Widget Body
class kbe_Tags_Widget extends WP_Widget {
    
    //=======> Widget setup
    function kbe_Tags_Widget() {
        /* Widget settings. */
	$widget_ops = array( 'classname' => 'kbe', 'description' => __('WP Knowledgebase article widget to show tags on the site', 'kbe') );
        
        /* Widget control settings. */
	$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'kbe_tags_widgets' );
        
	/* Create the widget. */
	$this->WP_Widget( 'kbe_tags_widgets', __('Knowledgebase Tags', 'kbe'), $widget_ops, $control_ops );
    }
    
    //=======> How to display the widget on the screen.
    function widget($args, $widgetData) {
        extract($args);
        
        //=======> Our variables from the widget settings.
        $kbe_widget_tag_title = $widgetData['txtKbeTagsHeading'];
        $kbe_widget_tag_count = $widgetData['txtKbeTagsCount'];
        $kbe_widget_tag_style = $widgetData['txtKbeTagsStyle'];
        
        //=======> widget body
        echo $before_widget;
        echo '<div class="kbe_widget kbe_widget_article">';
        
                if($kbe_widget_tag_title){
                    echo '<h2>'.$kbe_widget_tag_title.'</h2>';
                }
        ?>
        		<div class="kbe_tags_widget">
        <?php
					$args = array(
								'smallest'                  => 	12,
								'largest'                   => 	30,
								'unit'                      => 	'px',
								'number'                    => 	$kbe_widget_tag_count,
								'format'                    => 	$kbe_widget_tag_style,
								'separator'                 => 	"\n",
								'orderby'                   => 	'name',
								'order'                     => 	'ASC',
								'exclude'                   => 	null,
								'include'                   => 	null,
								'topic_count_text_callback' => 	default_topic_count_text,
								'link'                      => 	'view',
								'taxonomy'                  => 	KBE_POST_TAGS,
								'echo'                      => 	true
							);
						
					wp_tag_cloud($args);
					
					wp_reset_query();
		?>
        		</div>
        <?php      
        echo "</div>";
        echo $after_widget;
    }
    
    //Update the widget 
    function update($new_widgetData, $old_widgetData) {
        $widgetData = $old_widgetData;
		
        //Strip tags from title and name to remove HTML 
        $widgetData['txtKbeTagsHeading'] = $new_widgetData['txtKbeTagsHeading'];
        $widgetData['txtKbeTagsCount'] = $new_widgetData['txtKbeTagsCount'];
        $widgetData['txtKbeTagsStyle'] = $new_widgetData['txtKbeTagsStyle'];
		
        return $widgetData;
    }
    
    function form($widgetData) {
        //Set up some default widget settings.
        $widgetData = wp_parse_args((array) $widgetData);
?>
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeTagsHeading'); ?>"><?php _e('Tag Title:','kbe'); ?></label>
            <input id="<?php echo $this->get_field_id('txtKbeTagsHeading'); ?>" name="<?php echo $this->get_field_name('txtKbeTagsHeading'); ?>" value="<?php echo $widgetData['txtKbeTagsHeading']; ?>" style="width:275px;" />
        </p>    
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeTagsCount'); ?>"><?php _e('Tags Quantity:','kbe'); ?></label>
            <input id="<?php echo $this->get_field_id('txtKbeTagsCount'); ?>" name="<?php echo $this->get_field_name('txtKbeTagsCount'); ?>" value="<?php echo $widgetData['txtKbeTagsCount']; ?>" style="width:275px;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeTagsStyle'); ?>"><?php _e('Tags Style:','kbe'); ?></label>
            <select id="<?php echo $this->get_field_id('txtKbeTagsStyle'); ?>" name="<?php echo $this->get_field_name('txtKbeTagsStyle'); ?>">
                <option <?php selected($widgetData['txtKbeTagsStyle'], 'flat') ?> value="flat"><?php _e('Flat','kbe'); ?></option>
                <option <?php selected($widgetData['txtKbeTagsStyle'], 'list') ?> value="list"><?php _e('List','kbe'); ?></option>
            </select>
        </p>
<?php
    }
}
?>