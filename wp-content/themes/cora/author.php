<?php

get_header(); 

$layout=get_option('blog_layout');
if($layout=='right sidebar') { $layout='left'; $sidebar='right'; }
else { $layout='right'; $sidebar='left'; }

?>



<?php
	/* Queue the first post, that way we know who
	 * the author is when we try to get their name,
	 * URL, description, avatar, etc.
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	 */
	if ( have_posts() )
		the_post();
?>

<div id="sliderwrap" style="height:200px;"></div><!-- end sliderwrap -->

</div>

<?php theme_functions('heading_wrap'); ?>


<div id="main">
   <div id="primary" style="float:<?php echo $layout?>;">
   
       <?php theme_functions('about_the_author'); ?>


<?php
	/* Since we called the_post() above, we need to
	 * rewind the loop back to the beginning that way
	 * we can run the loop properly, in full.
	 */
	rewind_posts();

	/* Run the loop for the author archive page to output the authors posts
	 * If you want to overload this in a child theme then include a file
	 * called loop-author.php and that will be used instead.
	 */
	 get_template_part( 'loop', 'author' );
?>	 

	 <?php echo pagination(); ?>

</div>

      <div id="sidebar_<?php echo $sidebar?>"> 
        <div class="sidebar_content_<?php echo $sidebar?> sidebar">
    
          <?php get_sidebar(); ?>
     
        </div>
      </div><!-- end sidebar -->
      
      </div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>