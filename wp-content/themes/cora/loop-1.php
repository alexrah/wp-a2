<?php  
     $images_height=get_option('post_image_height');
	   
	   
	 if(is_page_template('template_blog1.php')) {
	   
		 $paged = get_query_var('paged'); 
		 if($paged=='') $paged=get_query_var('page');
		 if($paged=='') $paged=1;
		 query_posts("paged=$paged&showposts=5"); 
	 }


	 
?>
        <?php while (have_posts()) : the_post(); ?>

    <div class="blogpost">

        <?php
        $video=get_post_meta($post->ID, 'video_value', true);  
		
		$set = get_post_meta($post->ID,'slideshow_set_value',true);

if($video) 	

	echo "<div class='video_frame'>
	<iframe src='http://player.vimeo.com/video/".$video."?title=0&amp;byline=0&amp;portrait=0' width='630' height='".$images_height."' frameborder='0'></iframe>
	</div>";
else

        
        if( $set!='off'):

		?>
          
          <div id="slides_<?php the_ID();?>" class="slides" style="height:<?php echo $images_height; ?>px">
              <div class="slides_container" style="height:<?php echo $images_height; ?>px">
              
                  <?php 
				  
				       $slides= theme_functions('slideShow_getImages');

					   foreach ($slides as $slide_image) 
                          echo '<div><a href="'.get_permalink().'"><img src="'.THEME_SCRIPTS.'/timthumb.php?src='.$slide_image['src'].'&amp;h='.$images_height.'&amp;w=630&amp;zc=1'.'" alt="'.get_the_title().'" /></a></div>';

                  ?>
                  
              </div>
          </div>
          
          <script>
		  $(document).ready(function(){
		     $('#slides_<?php the_ID();?>').slides({
				generateNextPrev: false,
				preload: true,
				play: 2500,
				hoverPause:true,
				effect:'fade',
				crossfade:true
			});
		  });
		  </script>
		
		<?php elseif (has_post_thumbnail()):  
		

 
        $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
        $image_src = THEME_SCRIPTS.'/timthumb.php?src='.$image_src_array[0].'&amp;h='.$images_height.'&amp;w=630&amp;zc=1';
		
		
		
		
        ?>
      
        
        <div class="frame">    
      
        <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><img src="<?php echo $image_src;?>" alt="<?php the_title(); ?>"/></a>
        
        

        
        <?php if ( get_post_meta($post->ID, 'caption_value', true) ) : ?>
           
            <div class="caption">
               <?php echo get_post_meta($post->ID, 'caption_value', true); ?>
            </div><!-- caption -->
        
        <?php endif; ?>
        
      </div><!-- end frame -->

        
       <?php endif; ?>
       
       <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><h2><?php the_title();?></h2></a>
       
       <div class="metabox">
           <div class="subheading date"><a href="<?php echo get_month_link(get_the_time('Y'), get_the_time('m')); ?>"><?php echo the_time( get_option( 'date_format' ) ); ?></a><span></span></div>
           <div class="subheading author"><span></span><?php the_author_posts_link(); ?></div>
           <div class="subheading category"><span></span><?php the_category(', ');?></div>
           <div class="subheading comments"><span></span><?php comments_popup_link(__('No Comments','cora'), __('1 Comment','cora'), __('% Comments','cora')); ?></div>
       </div>
      
    
       <div class="content">
       <?php the_excerpt(); ?>
       <a class="more" href="<?php the_permalink(); ?>"><?php _e('Read more','cora')?></a>
       <span class="handwritten3"><span></span><?php _e('Read more','cora')?></span>
       </div><!-- end content -->
    </div><!-- end blogpost -->


<?php endwhile; wp_reset_postdata();?>

</ul>
