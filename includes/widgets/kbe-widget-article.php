<?php
/*===============
    KBE Articles Widget
 ===============*/
 
//========= Custom Knowledgebase Article Widget
add_action( 'widgets_init', 'kbe_article_widgets' );
function kbe_article_widgets() {
    register_widget( 'kbe_Article_Widget' );
}

//========= Custom Knowledgebase Article Widget Body
class kbe_Article_Widget extends WP_Widget {
    
    //=======> Widget setup
    function __construct() {
        parent::__construct(
            'kbe_article_widgets', // Base ID
            __( 'Knowledgebase Article', 'kbe' ), // Name
            array( 'description' => __('WP Knowledgebase article widget to show articles on the site', 'kbe'), 
                    'classname' => 'kbe' ), // Args
            array( 'width' => 300, 'height' => 300, 'id_base' => 'kbe_article_widgets' )
        );
    }
    
    //=======> How to display the widget on the screen.
    function widget($args, $widgetData) {
        extract($args);
        
        //=======> Our variables from the widget settings.
        $kbe_widget_article_title = $widgetData['txtKbeArticleHeading'];
        $kbe_widget_article_count = $widgetData['txtKbeArticleCount'];
        $kbe_widget_article_order = $widgetData['txtKbeArticleOrder'];
        $kbe_widget_article_orderby = $widgetData['txtKbeArticleOrderBy'];
        
        //=======> widget body
        echo $before_widget;
        echo '<div class="kbe_widget kbe_widget_article">';
        
                if($kbe_widget_article_title){
                    echo '<h2>'.$kbe_widget_article_title.'</h2>';
                }
                
                if($kbe_widget_article_orderby == 'popularity'){
                    $kbe_widget_article_args = array( 
                        'posts_per_page' => $kbe_widget_article_count, 
                        'post_type'  => 'kbe_knowledgebase',
                        'orderby' => 'meta_value_num',
                        'order'	=>	$kbe_widget_article_order,
                        'meta_key' => 'kbe_post_views_count'
                    );
                }
                else{
                    $kbe_widget_article_args = array(
                        'post_type' => 'kbe_knowledgebase',
                        'posts_per_page' => $kbe_widget_article_count,
                        'order' => $kbe_widget_article_order,
                        'orderby' => $kbe_widget_article_orderby
                   );
                }
                
                $kbe_widget_articles = new WP_Query($kbe_widget_article_args);
                if($kbe_widget_articles->have_posts()) :
            ?>
                <ul>
            <?php
                    while($kbe_widget_articles->have_posts()) :
                        $kbe_widget_articles->the_post();
            ?>
                        <li>
                            <a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>">
                                <?php the_title() ?>
                            </a>
                        </li>
            <?php
                    endwhile;
            ?>
                </ul>
            <?php
                endif;
                
                wp_reset_query();
                
        echo "</div>";
        echo $after_widget;
    }
    
    //Update the widget 
    function update($new_widgetData, $old_widgetData) {
        $widgetData = $old_widgetData;
		
        //Strip tags from title and name to remove HTML 
        $widgetData['txtKbeArticleHeading'] = $new_widgetData['txtKbeArticleHeading'];
        $widgetData['txtKbeArticleCount'] = $new_widgetData['txtKbeArticleCount'];
        $widgetData['txtKbeArticleOrder'] = $new_widgetData['txtKbeArticleOrder'];
        $widgetData['txtKbeArticleOrderBy'] = $new_widgetData['txtKbeArticleOrderBy'];
		
        return $widgetData;
    }
    
    function form($widgetData) {
        //Set up some default widget settings.
        $widgetData = wp_parse_args((array) $widgetData);
?>
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeArticleHeading'); ?>"><?php _e('Article Title:','kbe'); ?></label>
            <input id="<?php echo $this->get_field_id('txtKbeArticleHeading'); ?>" name="<?php echo $this->get_field_name('txtKbeArticleHeading'); ?>" value="<?php echo $widgetData['txtKbeArticleHeading']; ?>" style="width:275px;" />
        </p>    
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeArticleCount'); ?>"><?php _e('Articles Quantity:','kbe') ?></label>
            <input id="<?php echo $this->get_field_id('txtKbeArticleCount'); ?>" name="<?php echo $this->get_field_name('txtKbeArticleCount'); ?>" value="<?php echo $widgetData['txtKbeArticleCount']; ?>" style="width:275px;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeArticleOrder'); ?>"><?php _e('Articles Order:','kbe') ?></label>
            <select id="<?php echo $this->get_field_id('txtKbeArticleOrder'); ?>" name="<?php echo $this->get_field_name('txtKbeArticleOrder'); ?>">
                <option <?php selected($widgetData['txtKbeArticleOrder'], 'ASC') ?> value="ASC"><?php _e('ASC','kbe'); ?></option>
                <option <?php selected($widgetData['txtKbeArticleOrder'], 'DESC') ?> value="DESC"><?php _e('DESC','kbe'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('txtKbeArticleOrderBy'); ?>"><?php _e('Articles Order by:','kbe') ?></label>
            <select id="<?php echo $this->get_field_id('txtKbeArticleOrderBy'); ?>" name="<?php echo $this->get_field_name('txtKbeArticleOrderBy'); ?>">
                <option <?php selected($widgetData['txtKbeArticleOrderBy'], 'name') ?> value="name"><?php _e('By Name','kbe'); ?></option>
                <option <?php selected($widgetData['txtKbeArticleOrderBy'], 'date') ?> value="date"><?php _e('By Date','kbe'); ?></option>
                <option <?php selected($widgetData['txtKbeArticleOrderBy'], 'rand') ?> value="rand"><?php _e('By Random','kbe'); ?></option>
                <option <?php selected($widgetData['txtKbeArticleOrderBy'], 'popularity') ?> value="popularity"><?php _e('By Popularity','kbe'); ?></option>
                <option <?php selected($widgetData['txtKbeArticleOrderBy'], 'comment_count') ?> value="comment_count"><?php _e('By Comments','kbe') ?></option>
            </select>
        </p>
<?php
    }
}
?>