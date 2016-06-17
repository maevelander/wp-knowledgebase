<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;
?>
<div id="wpbody">
    <div id="wpbody-content">
        <div class="wrap">

            <h2><?php _e( 'Re-Order', 'wp-knowledgebase' ); ?></h2>

            <?php
				$message = '';
				if ( isset( $_POST['kbe_order_submit'] ) ) {
					parent_article_order_update();
				}

				$message = '';

				if ( isset( $_POST['kbe_article_submit'] ) ) {
					custom_article_order_update();
				}
			?>

            <div class="kbe_admin_left_bar">
                <!--=============== Re Order Categories ===============-->
                <div class="kbe_admin_left_content">
                    <div class="kbe_admin_left_heading">
                        <h3><?php _e( 'Category Order', 'wp-knowledgebase' ); ?></h3>
                    </div>
                    <div class="kbe_admin_body">
                        <form name="custom_order_form" method="post" action="">
                        <?php
							$kbe_parent_ID = 0;
							$kbe_args      = array(
								'orderby'    => 'terms_order',
								'order'      => 'ASC',
								'hide_empty' => false
							);
							$kbe_terms     = get_terms( 'kbe_taxonomy', $kbe_args );
							if ( $kbe_terms ) {
						?>
                            <p><?php _e( 'Drag and drop items to customise the order of categories in WP Knowledgebase', 'wp-knowledgebase' ); ?></p>

                            <ul id="kbe_order_sortable" class="kbe_admin_order">
                            <?php
								foreach ( $kbe_terms as $kbe_term ) :
							?>
                                    <li id="kbe_parent_id_<?php echo $kbe_term->term_id; ?>" class="lineitem ui-state-default">
                                        <?php echo $kbe_term->name; ?>
                                    </li>
                            <?php
								endforeach;
							?>
                            </ul>
                            <img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" id="kbe_custom_loading" style="display:none" alt="" />
                            <input type="submit" name="kbe_order_submit" id="kbe_order_submit" class="button-primary" value="<?php _e( 'Save Order', 'wp-knowledgebase' ); ?>" />
                            <input type="hidden" id="kbe_parent_custom_order" name="kbe_parent_custom_order" />
                            <input type="hidden" id="kbe_parent_id" name="kbe_parent_id" value="<?php echo $kbe_parent_ID; ?>" />
                        <?php
							} else {
						?>
                            <p>
                                <?php _e( 'No terms found', 'wp-knowledgebase' ); ?>
                            </p>
                        <?php
							}
						?>
                        </form>
                    </div>
                    <script type="text/javascript">
                        jQuery(document).ready(function() {
                            jQuery("#kbe_custom_loading").hide();
                            jQuery("#kbe_order_submit").click(function() {
                                kbeOrderSubmit();
                            });
                        });

                        function kbe_custome_order() {
                            //alert("hello 2");
                            jQuery("#kbe_order_sortable").sortable({
                                    placeholder: "sortable-placeholder",
                                    revert: false,
                                    tolerance: "pointer"
                            });
                        };

                        addLoadEvent(kbe_custome_order);
                        function kbeOrderSubmit() {
                            var kbeParentNewOrder = jQuery("#kbe_order_sortable").sortable("toArray");
                            //alert(kbeParentNewOrder);
                            //var newChildOrder = jQuery("#kbe_order_sortable").sortable("toArray");
                            jQuery("#kbe_custom_loading").show();
                            jQuery("#kbe_parent_custom_order").val(kbeParentNewOrder);
                            //jQuery("#hidden-custom-child-order").val(newChildOrder);
                            return true;
                        }

                    </script>
                </div>

                <!--=============== Re Order Articles ===============-->
                <div class="kbe_admin_left_content">
                    <div class="kbe_admin_left_heading">
                        <h3><?php _e( 'Article Order', 'wp-knowledgebase' ); ?></h3>
                    </div>
                    <div class="kbe_admin_body">
                        <form name="custom_order_form" method="post" action="">
                        <?php
							$kbe_article_args = new WP_Query( array(
														'post_type' => 'kbe_knowledgebase',
														'order'     => 'ASC',
														'orderby'   => 'menu_order',
														'nopaging'  => true,
													) );
							if ( $kbe_article_args->have_posts() ) {
						?>
                            <p><?php _e( 'Drag and drop items to customise the order of articles in WP Knowledgebase', 'wp-knowledgebase' ); ?></p>

                            <ul id="kbe_article_sortable" class="kbe_admin_order">
                            <?php $i = 1;
								while ( $kbe_article_args->have_posts() ) :
									$kbe_article_args->the_post();
							?>
                                    <li id="kbe_article_id_<?php the_ID(); ?>" class="lineitem <?php echo ($i % 2 == 0 ? 'alternate ' : ''); ?>ui-state-default">
                                        <?php echo _draft_or_post_title(); ?>
                                    </li>
                            <?php $i++;
								endwhile;
							?>
                            </ul>
                            <img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" id="kbe_custom_loading_article" style="display:none" alt="" />
                            <input type="submit" name="kbe_article_submit" id="kbe_article_submit" class="button-primary" value="<?php _e( 'Save Order', 'wp-knowledgebase' ); ?>" />
                            <input type="hidden" id="kbe_article_custom_order" name="kbe_article_custom_order" />
                        <?php
							} else {
						?>
                            <p>
                                <?php _e( 'No Articles found', 'wp-knowledgebase' ); ?>
                            </p>
                        <?php
							}
						?>
                        </form>
                    </div>
                    <script type="text/javascript">
                        jQuery(document).ready(function() {
                            jQuery("#kbe_custom_loading_article").hide();
                            jQuery("#kbe_article_submit").click(function() {
                                kbeArticleSubmit();
                            });
                        });

                        function kbe_custome_order_article() {
                            //alert("hello 2");
                            jQuery("#kbe_article_sortable").sortable({
                                    placeholder: "sortable-placeholder",
                                    revert: false,
                                    tolerance: "pointer"
                            });
                        };

                        addLoadEvent(kbe_custome_order_article);
                        function kbeArticleSubmit() {
                            var kbeArticleNewOrder = jQuery("#kbe_article_sortable").sortable("toArray");
                            jQuery("#kbe_custom_loading_article").show();
                            jQuery("#kbe_article_custom_order").val(kbeArticleNewOrder);
                            return true;
                        }

                    </script>
                </div>
            </div>

            <div class="kbe_admin_sidebar">
            <table cellpadding="0" class="widefat donation" style="margin-bottom:10px; border:solid 2px #008001;" width="50%" valign="top">
                <thead>
                    <th scope="col">
                        <strong style="color:#008001;"><?php _e( 'Help Improve This Plugin!', 'wp-knowledgebase' ); ?></strong>
                    </th>
        	</thead>
      		<tbody>
                    <tr>
          		<td style="border:0;">
                            <?php _e( 'Enjoyed this plugin? All donations are used to improve and further develop this plugin. Thanks for your contribution.', 'wp-knowledgebase' ); ?>
                        </td>
                    </tr>
                    <tr>
          		<td style="border:0;">
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                            <input type="hidden" name="cmd" value="_s-xclick">
                            <input type="hidden" name="hosted_button_id" value="A74K2K689DWTY">
                            <input type="image" src="https://www.paypalobjects.com/en_AU/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
                            <img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
            		</form>
                  	</td>
                    </tr>
                    <tr>
          		<td style="border:0;"><?php _e( 'you can also help by', 'wp-knowledgebase' ); ?>
                            <a href="http://wordpress.org/support/view/plugin-reviews/wp_knowledgebase" target="_blank">
                                <?php _e( 'rating this plugin on wordpress.org', 'wp-knowledgebase' ); ?>
                            </a>
                      	</td>
                    </tr>
                </tbody>
            </table>

            <table cellpadding="0" class="widefat" border="0">
                <thead>
                    <th scope="col"><?php _e( 'Need Support?', 'wp-knowledgebase' ); ?></th>
                </thead>
                <tbody>
                    <tr>
                        <td style="border:0;">
                            <?php _e( 'Check out the', 'wp-knowledgebase' ); ?>
                            <a href="http://enigmaplugins.com/documentation/" target="_blank"><?php _e('FAQs','wp-knowledgebase'); ?></a>
                            <?php _e( 'and', 'wp-knowledgebase' ); ?>
                            <a href="http://wordpress.org/support/plugin/wp_knowledgebase" target="_blank"><?php _e('Support Forums','wp-knowledgebase'); ?></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>

            <?php
				/*====================>>_ Update Category Query _<<====================*/
				function parent_article_order_update() {
					if ( isset( $_POST['kbe_parent_custom_order'] ) && $_POST['kbe_parent_custom_order'] != '' ) {

						global $wpdb;

						$parent_new_order = $_POST['kbe_parent_custom_order'];
						//echo $parent_new_order.'<br />';
						$parent_IDs       = explode( ',', $parent_new_order );
						//print_r($parent_IDs).'<br />';
						$parent_result    = count( $parent_IDs );
						for ( $p = 0; $p < $parent_result; $p++ ) {
							$parent_str  = str_replace( 'kbe_parent_id_', '', $parent_IDs[ $p ] );
							//echo $parent_str."<br />";
							$term_update = $wpdb->update( $wpdb->terms, array( 'terms_order' => $p ), array( 'term_id' => $parent_str ) );
						}
						echo '<div id="message" class="updated fade"><p>' . __( 'Category Order updated successfully.', 'wp-knowledgebase' ) . '</p></div>';
					} else {
						echo '<div id="message" class="error fade"><p>' . __( 'An error occured, order has not been saved.', 'wp-knowledgebase' ) . '</p></div>';
					}
				}

				/*====================>>_ Update Articles Query _<<====================*/
				function custom_article_order_update() {
					if ( isset( $_POST['kbe_article_custom_order'] ) && $_POST['kbe_article_custom_order'] != '' ) {
						global $wpdb;

						$article_new_order = $_POST['kbe_article_custom_order'];
						//echo $article_new_order.'<br />';
						$article_IDs       = explode( ',', $article_new_order );
						//print_r($article_IDs).'<br />';
						$article_result    = count( $article_IDs );

						for ( $a = 0; $a < $article_result; $a++ ) {
							$article_str    = str_replace( 'kbe_article_id_', '', $article_IDs[ $a ] );
							//echo $article_str."<br />";
							$article_update = $wpdb->update( $wpdb->posts, array( 'menu_order' => $a ), array( 'ID' => $article_str ) );
						}
						echo '<div id="message" class="updated fade"><p>' . __( 'Article Order updated successfully.', 'wp-knowledgebase' ) . '</p></div>';
					} else {
						echo '<div id="message" class="error fade"><p>' . __( 'An error occured, order has not been saved.', 'wp-knowledgebase' ) . '</p></div>';
					}
				}

			?>

	    </div>
    </div>
</div>
