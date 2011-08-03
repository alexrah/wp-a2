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
  

  
<?php if ( have_posts() ) : ?> 

    <ul id="search_results">
 
    <?php 
	

		   
	while (have_posts()) : the_post(); 
	
	
	?>
        <li><a href="<?php the_permalink();?>"><?php the_title();?></a></li>

	<?php	endwhile;  ?>
    
    </ul>
    
    <?php	pagination();  ?>
    
    
<?php else : ?>
					<h3><?php _e( 'Nothing Found', 'cora' ); ?></h3>
					<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'cora' ); ?></p>
					<?php get_search_form(); ?>
<?php endif; ?>

      </div>
      
      <div id="sidebar_<?php echo $sidebar?>"> 
        <div class="sidebar_content_<?php echo $sidebar?> sidebar">
    
          <?php get_sidebar(); ?>
     
        </div>
      </div><!-- end sidebar -->
      
      </div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
