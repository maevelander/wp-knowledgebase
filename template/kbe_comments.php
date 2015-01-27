<?php
// Do not delete these lines
    if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
        die ('Please do not load this page directly. Thanks!');
    if ( post_password_required() ) {
?>
        <p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','kbe') ?></p>
<?php
        return;
    }
?>

<!-- You can start editing here. -->
<div class="clear"></div>
<?php
    if ( have_comments() ) : ?>
        <h4 id="comments">
            <?php echo comments_number(__('No Comments','kbe'), __('1 Comment','kbe'), __('% Comments','kbe')); ?>
        </h4>
    
        <ol class="commentlist">
            <?php wp_list_comments(); ?>
        </ol>
    
        <div class="clear"></div>
 <?php
    else : // this is displayed if there are no comments so far
        if ( comments_open() ) :
	/*If comments are open, but there are no comments. */
        else : // comments are closed
?>
            <!-- If comments are closed. -->
            <p class="nocomments"><?php _e('Comments are closed.','kbe'); ?></p>
<?php
        endif;
    endif;
    
    if ( comments_open() ) :
?>
        <div id="respond">
            <h4><?php comment_form_title( __('Leave a comment','kbe'), __('Leave a comment to %s','kbe') ); ?></h4>

            <div class="cancel-comment-reply">
                <small><?php cancel_comment_reply_link(); ?></small>
            </div>

        <?php
            if ( get_option('comment_registration') && !is_user_logged_in() ) :
        ?>
                <p>
					<?php _e('You must be ','kbe'); ?>
                    	<a href="<?php echo wp_login_url( get_permalink() ); ?>">
							<?php _e('logged in','kbe'); ?>
                      	</a>
                  	<?php _e('to post a comment.','kbe'); ?>
             	</p>
        <?php
            else :
        ?>

                <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
            <?php
                if ( is_user_logged_in() ) :
            ?>
                    <p>
                        <?php _e('Logged in as ','kbe'); ?>
                        <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php">
							<?php echo $user_identity; ?>
                      	</a>. 
                        <a href="<?php echo wp_logout_url(	get_permalink()); ?>" title="<?php _e('Log out of this account','kbe') ?>">
                        	<?php _e('Log out &raquo;','kbe'); ?>
                       	</a>
                    </p>
            <?php
                else :
            ?>
                    <input type="text" name="author" id="author" placeholder="<?php _e('Your Name','kbe'); ?>" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
                    <input type="text" name="email" id="email" placeholder="<?php _e('Your Email','kbe'); ?>" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
                    <input type="hidden" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" />
            <?php
                endif;
            ?>

            <!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->
                    <textarea name="comment" id="comment" cols="58" rows="10" tabindex="4"></textarea>
                    <input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Post Comment','kbe'); ?>" />

                    <?php comment_id_fields(); ?>
                    <?php do_action('comment_form', $post->ID); ?>
                </form>

        <?php
            endif; // If registration required and not logged in
        ?>
        </div>
<?php
    endif; // if you delete this the sky will fall on your head
?>