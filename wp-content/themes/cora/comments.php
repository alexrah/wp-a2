<?php
if(!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])){
	die('Please do not load this page directly!');
} 
if(post_password_required()){
?>
		<p class="nopassword"><?php _e( 'This post is password protected.', 'cora' ); ?></p>
<?php return; } ?>

<?php
//Display the comment loop
if(have_comments()) :
?>

    
    
    <h3 id="comments_title" class="bold_title"><?php
	printf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'cora' ),
	number_format_i18n( get_comments_number() ));
	?></h3>
    
	<ul id="comments_section">
		<?php wp_list_comments( array( 'callback' => 'theme_comments' ) ); ?>
	</ul>
    
	<div class="pagination"><?php paginate_comments_links( array('prev_text' => '&laquo;', 'next_text' => '&raquo;') ) ?></div>
    <div class="clearboth"></div>
	<?php 
	else ://If no comments so far 
	?>
	

	<?php endif; ?>
<?php if('open' == $post->comment_status) : ?>

<div id='respond'>    
    <h3 class="bold_title"><?php comment_form_title( __('Leave a Reply','cora')); ?></h3>
	
	<p><?php cancel_comment_reply_link(__('Click here to cancel the reply','cora')); ?></p>
	
	<?php if(get_option('comment_registration') && !$user_ID): ?>
        
        <p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment','cora'),wp_login_url( get_permalink() )); ?></p>
        
	<?php else : ?>
    
	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
   
	<?php if($user_ID) :?>    
    <p><?php printf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out">Log out?</a>','cora'), admin_url( 'profile.php' ), $user_identity, wp_logout_url( get_permalink()  ) )?></p>
	
	<?php else : ?>
		<p><input type='text' class="text_input" name='author' id='author' size="25" value="<?php echo $comment_author;?>" />
		<label for='author'><?php _e('Name','cora');  if ($req) echo " *"; ?></label></p>
        
		<p><input type='text' class="text_input" name='email' id='email' size="25" value="<?php echo $comment_author_email;?>" />
		<label for='email'><?php _e('Email','cora');  if ($req) echo " *"; ?></label>
		</p>
		<p>
		<input type='text' class="text_input" name='url' id='url' size="25" value="<?php echo $comment_author_url; ?>" />
		<label for='url'><?php _e('Website','cora'); ?></label>
		</p>
        
       
        
	<?php endif; ?>
	<div><?php comment_id_fields(); ?>
	<input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'])?>" />
	</div>
	
	<p><textarea id='comment' class="textarea" name='comment' cols="60" rows="10"></textarea></p>
      
   	<p><a class="button" href="#" onclick="jQuery('#commentform').submit();return false;"><?php _e('Post Comment','cora');?></a><?php comment_id_fields(); ?></p>
	<p><?php do_action('comment_form', $post->ID); ?></p>
   
   
   
	</form>
	<?php endif; ?>
</div>
<?php endif; ?>



<?php

function theme_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
    
		<div id="comment-<?php comment_ID(); ?>" class="comment_wrap">
			<div class="gravatar"><?php echo get_avatar($comment,$size='60',$default=''); ?>
            
                    <div class="reply">
					<?php comment_reply_link(array_merge( $args, array('reply_text' => __('Reply','cora'),'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
			</div>
            
            
            </div>

			<div class="comment_content">
				<div class="comment_meta">
                
					<?php printf( '<div class="comment_author">%s', get_comment_author_link()) ?>	&nbsp;<?php edit_comment_link(__('Edit', 'cora' ),'  ',''); ?></div>

                    
                    <div class="comment_date"><?php echo get_comment_date(' M j , Y , h:i'); ?></div>
                    
				</div>
				<div class='comment_text'>
					<?php comment_text() ?>
<?php if ($comment->comment_approved == '0') : ?>
					<span class="unapproved"><?php _e('Your comment is awaiting moderation.','cora') ?></span>
<?php endif; ?>
				</div>

			</div>
		</div>
<?php
}?>