<div class="wrap"> 
<div id="icon-upload" class="icon32"><br /></div><h2>Bulk WP Smush.it </h2>

<?php 

  if ( sizeof($attachments) < 1 ):
    echo '<p>You don’t appear to have uploaded any images yet.</p>';
  else: 
    if ( empty($_POST) ): // instructions page
?>
  <p>This tool will run all of the images in your media library through the WP Smush.it web service.</p>
  
  <p>It uploads each and every file to Yahoo! and then downloads the resulting file. It can take a long time.</p>
  
  <p>We found <?php echo sizeof($attachments); ?> images in your media library. Be forewarned, <strong>it will take <em>at least</em> <?php echo (sizeof($attachments) * 3 / 60); ?> minutes</strong> to process all these images.</p>
  
  <p><em>N.B. If your server <tt>gzip</tt>s content you may not see the progress updates as your files are processed.</em></p>
  
  <p><strong>This is an experimental feature.</strong> Please post any feedback to the <a href="http://wordpress.org/tags/wp-smushit">WordPress WP Smush.it forums</a>.</p>

  <form method="post" action="">
    <?php wp_nonce_field( 'wp-smushit-bulk', '_wp-smushit-bulk-nonce'); ?>
    <button type="submit" class="button-secondary action">Run all my images through WP Smush.it right now</button>
  </form>
  
<?php
      else: // run the script
  
      if (!wp_verify_nonce( $_POST['_wp-smushit-bulk-nonce'], 'wp-smushit-bulk' ) || !current_user_can( 'edit_others_posts' ) ) {
  				wp_die( __( 'Cheatin&#8217; uh?' ) );
      }

      ob_implicit_flush(true);
      ob_end_flush();

      foreach( $attachments as $attachment ) {
        printf( "<p>Processing <strong>%s</strong>&hellip;<br>", esc_html($attachment->post_name) );
        $meta = wp_smushit_resize_from_meta_data( wp_get_attachment_metadata( $attachment->ID, true ), $attachment->ID );
        foreach( $meta['sizes'] as $size ) {
          printf( "– %s<br>", $size['wp_smushit'] );
        }
        echo "</p>";
        
        wp_update_attachment_metadata( $attachment->ID, $meta );

        // rate limiting is good manners, let's be nice to Yahoo!
        sleep(0.5); 
        @ob_flush();
        flush();
      }
    endif; 
  endif; 
?>
</div>