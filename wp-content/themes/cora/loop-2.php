<ul class="blogcol">

<?php

	 if(is_page_template('template_blog2.php')) {
	   
		 $paged = get_query_var('paged'); 
		 if($paged=='') $paged=get_query_var('page');
		 if($paged=='') $paged=1;
		 query_posts("paged=$paged&showposts=5"); 
	 }
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

    <li id="post-<?php the_ID(); ?>">
      <div class="frame">
        <div class="teaser">
      
      <?php 
	  $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
	  
	  $image_src = THEME_SCRIPTS.'/timthumb.php?src='.$image_src_array[0].'&amp;h=120&amp;w=218&amp;zc=1';
	  
	  if (!has_post_thumbnail())  $image_src = THEME_SCRIPTS.'/timthumb.php?src='.THEME_IMAGES.'/post_thumbnail.png'.'&amp;h=120&amp;w=190&amp;zc=1';
	  
	   ?>
      
         <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><img src="<?php echo $image_src;?>" alt="<?php the_title(); ?>"/></a>
         <div class="meta">
           <div class="date"><a href="<?php echo get_month_link(get_the_time('Y'), get_the_time('m')); ?>"><?php echo get_the_date(); ?></a></div>
           <div class="comments"><?php comments_popup_link('0','1','%'); ?></div>
          </div><!-- end meta -->
        </div><!-- end teaser -->
      </div><!-- end frame -->

  
      <div class="blog_title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></div>
      <p><?php echo wp_html_excerpt(get_the_excerpt(),200); ?></p>
      <p><a class="more" href="<?php the_permalink(); ?>"><?php echo __('Continua... &raquo;','cora')?><span></span></a></p>
      
    </li>

<?php endwhile; wp_reset_postdata();?>

</ul>
