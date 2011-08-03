<?php
get_header(); 

$layout=get_option('blog_layout');
if($layout=='right sidebar') { $layout='left'; $sidebar='right'; }
else { $layout='right'; $sidebar='left'; }



?>





<?php if(is_home()&&is_front_page()): theme_functions('slider');?>

<?php elseif(is_home()): ?>


<div id="sliderwrap"></div><!-- end sliderwrap -->

</div>

<?php theme_functions('heading_wrap'); ?>



<?php endif; ?>



<div id="top_bg"></div>
<div id="main">
  <div id="primary" style="float:<?php echo $layout?>;">
  
			<?php  get_template_part( 'loop', 'index' ); ?>
            
            
            <?php pagination(); ?>
	      <div>
      <?php if ( !function_exists('dynamic_sidebar') dynamic_sidebar('Post Widget Right') ) : ?>
      <?php if ( !function_exists('dynamic_sidebar') dynamic_sidebar('Post Widget Left') ) : ?>
      <?php endif; ?>
       </div>
		    
  </div><!-- end primary -->
  
      <div id="sidebar_<?php echo $sidebar?>"> 
        <div class="sidebar_content_<?php echo $sidebar?> sidebar">
    
          <?php get_sidebar(); ?>
     
        </div>
      </div><!-- end sidebar -->
  
</div><!-- end main -->




<?php get_footer(); ?>
