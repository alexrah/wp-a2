<?php $footer_layout = get_option('footer_layout');  ?>

<div id="footerwrap">


  <div id="footer"> 
  
<?php
  switch($footer_layout):
		case 1:
?>
		<?php sidebar('get_footer_sidebar'); ?>

<?php	break;
		case 2:  ?>
          <div class="one_half"><?php sidebar('get_footer_sidebar'); ?></div>
          <div class="one_half last"><?php sidebar('get_footer_sidebar'); ?></div>
        
<?php	break;	
        case 3:  ?>
          <div class="one_third"><?php sidebar('get_footer_sidebar'); ?></div>
          <div class="one_third"><?php sidebar('get_footer_sidebar'); ?></div>
          <div class="one_third last"><?php sidebar('get_footer_sidebar'); ?></div>
        
<?php	break;
        case 4:  ?>
          <div class="one_half"><?php sidebar('get_footer_sidebar'); ?></div>
          <div class="one_fourth"><?php sidebar('get_footer_sidebar'); ?></div>
          <div class="one_fourth last"><?php sidebar('get_footer_sidebar'); ?></div>
        
<?php	break;
        case 5:  ?>
          <div class="one_fourth"><?php sidebar('get_footer_sidebar'); ?></div>
          <div class="one_fourth"><?php sidebar('get_footer_sidebar'); ?></div>
          <div class="one_fourth"><?php sidebar('get_footer_sidebar'); ?></div>
          <div class="one_fourth last"><?php sidebar('get_footer_sidebar'); ?></div>
        
<?php	break;
        case 6:  ?>
          <div class="one_fourth"><?php sidebar('get_footer_sidebar'); ?></div>
          <div class="one_fourth"><?php sidebar('get_footer_sidebar'); ?></div>
          <div class="one_half last"><?php sidebar('get_footer_sidebar'); ?></div>
        
<?php	break;
  endswitch; ?>
   
          <div class="clearboth"></div>
  
    
  </div><!-- end footer -->
  

</div><!-- end footerwrap -->

<?php if(get_option('subfooter')==on): ?>

<div id="subfooterwrap">
  <div id="subfooter">
     <div id="copy_right"><?php echo stripslashes(get_option('subfooter_text'));?></div>
     
     <?php wp_nav_menu(array( 'theme_location' => 'footer-menu','container_id' => 'footer_menu','fallback_cb' => '','depth'=>0 )); ?>
     
  </div><!-- end subfooter -->   
</div><!-- end subfooterwrap --> 

<?php endif; ?>

		
<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
	
	if(get_option('google_analytics')!=''){
		echo '<div class="hidden">'.stripslashes(get_option('google_analytics')).'</div>';
	}
?>
</body>
</html>