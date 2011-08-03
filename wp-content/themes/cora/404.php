<?php


get_header(); 

$layout=get_option('blog_layout');
if($layout=='right sidebar') { $layout='left'; $sidebar='right'; }
else { $layout='right'; $sidebar='left'; }
?>

<div id="sliderwrap"></div><!-- end sliderwrap -->
</div>

<?php theme_functions('heading_wrap'); ?>


<div id="main">
  <div id="primary" style="float:<?php echo $layout?>;">
            
            
				<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'cora' ); ?></p>
				<?php get_search_form(); ?>
                
          </div>
          
       <div id="sidebar_<?php echo $sidebar?>"> 
        <div class="sidebar_content_<?php echo $sidebar?> sidebar">
    
          <?php get_sidebar(); ?>
     
        </div>
      </div><!-- end sidebar -->
      
      </div>

	<script type="text/javascript">
		// focus on search field after it has loaded
		document.getElementById('s') && document.getElementById('s').focus();
	</script>

<?php get_footer(); ?>