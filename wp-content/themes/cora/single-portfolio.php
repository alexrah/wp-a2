<?php
$layout = get_post_meta($post->ID, 'layout_value', true);
if(empty($layout) || $layout == 'default'){
	$layout=get_option('portfolio_layout');
}
if($layout=='right sidebar') { $layout='left'; $sidebar='right'; } 
if($layout=='left sidebar') { $layout='right'; $sidebar='left'; }

?>

<?php

wp_enqueue_script('jquery144', THEME_JS . '/jquery-1.4.4.min.js');

global $post;

$slider=get_post_meta($post->ID, 'slideshow_type_value', true);  

switch($slider){
			case 'Nivo Slider':
				theme_functions('sliderHeader_nivo');
				break;
			case 'Kwicks Slider':
				theme_functions('sliderHeader_kwicks');
				break;	
		    case 'Full Width Slider':
				theme_functions('sliderHeader_full');
				break;	
			case 'Piecemaker Slider':
				theme_functions('sliderHeader_piecemaker');
				break;
            case 'Grid Slider':
				theme_functions('sliderHeader_blox');
				break;	
			case 'Grid Slider V2':
				theme_functions('sliderHeader_blox2');
				break;	
		}

get_header(); 

$video=get_post_meta($post->ID, 'video_value', true);  


if($video) 	

	echo "<div id='videowrap'>
	<div id='page_video'>
	<iframe src='http://player.vimeo.com/video/".$video."?title=0&amp;byline=0&amp;portrait=0' width='960' height='400' frameborder='0'></iframe>
	</div></div>";
else


switch($slider){
			case 'Static Image':
			    echo '<div id="static_image" style="height:'.get_post_meta($post->ID,'slideshow_height_value',true).'px;"></div>';
			    break;
			case 'off':
			    echo '<div style="margin-top:190px;"></div>';
			    break;
			case '':
			    echo '<div style="margin-top:190px;"></div>';
			    break;
			case 'Nivo Slider':
				theme_functions('slider_nivo');
				break;
			case 'Kwicks Slider':
				theme_functions('slider_kwicks');
				break;	
			case 'Piecemaker Slider':
				theme_functions('slider_piecemaker');
				break;
		    case 'Full Width Slider':
				theme_functions('slider_full');
				break;	
		    case 'Grid Slider':
				theme_functions('slider_blox');
				break;	
			case 'Grid Slider V2':
				theme_functions('slider_blox2');
				break;	
		}
		

?>
    

      </div>
      
      <?php if ( have_posts() ) while ( have_posts() ) : the_post();   ?>
      
      <?php theme_functions('heading_wrap'); ?>
      
       
     <div id="main">
      <?php if($layout!='full width'): ?>
          <div id="primary" style="float:<?php echo $layout?>;">
      <?php endif;?>
      
     
      
      <div id="blog_content"><?php the_content(); ?></div>
      <div class="clearboth"></div>
      

      
      <?php edit_post_link(__('Edit this Page', 'cora'),'<p>','</p>'); ?>
      <div class="clearboth"></div>
      
        <?php if(get_option('share_this')=='on'): ?>
        
        <div class="toggle"><div class="toggle_title"><?php _e('Share this','cora');?></div>

        <div class="share_this_icons toggle_content">
           <a rel="nofollow" target="_blank" title="<?php _e('Share this on facebook','cora');?>" href="http://www.facebook.com/share.php?u=<?php the_permalink();?>"><img src="<?php echo THEME_IMAGES;?>/social_icons/facebook.png"></a>
           
           <a rel="nofollow" target="_blank" title="<?php _e('Share this on twitter','cora');?>" href="http://twitter.com/home?status=<?php the_title();?> - <?php the_permalink();?>"><img src="<?php echo THEME_IMAGES;?>/social_icons/twitter.png"></a>
           
           <a rel="nofollow" target="_blank" title="<?php _e('Bookmark at digg','cora');?>" href="http://digg.com/submit?url=<?php the_permalink();?>&title=<?php the_title();?>"><img src="<?php echo THEME_IMAGES;?>/social_icons/digg.png"></a>
           
           <a rel="nofollow" target="_blank" title="<?php _e('Share this on myspace','cora');?>" href="http://www.myspace.com/Modules/PostTo/Pages/?u=<?php the_permalink();?>"><img src="<?php echo THEME_IMAGES;?>/social_icons/myspace.png"></a>
           
           <a rel="nofollow" target="_blank" title="<?php _e('Bookmark at delicious','cora');?>" href="http://delicious.com/post?url=<?php the_permalink();?>&title=<?php the_title();?>"><img src="<?php echo THEME_IMAGES;?>/social_icons/delicious.png"></a>
           
           <a rel="nofollow" target="_blank" title="<?php _e('Bookmark at google','cora');?>" href="http://www.google.com/bookmarks/mark?op=edit&bkmk=<?php the_permalink();?>&title=<?php the_title();?>"><img src="<?php echo THEME_IMAGES;?>/social_icons/google.png"></a>
           
           <a rel="nofollow" target="_blank" title="<?php _e('Share this on linked in','cora');?>" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink();?>&title=<?php the_title();?>"><img src="<?php echo THEME_IMAGES;?>/social_icons/linkedin.png"></a>
           
           <a rel="nofollow" target="_blank" title="<?php _e('Bookmark at reddit','cora');?>" href="http://reddit.com/submit?url=<?php the_permalink();?>&title=<?php the_title();?>"><img src="<?php echo THEME_IMAGES;?>/social_icons/reddit.png"></a>
           
           <a rel="nofollow" target="_blank" title="<?php _e('Bookmark at stumbleupon','cora');?>" href="http://www.stumbleupon.com/submit?url=<?php the_permalink();?>&title=<?php the_title();?>"><img src="<?php echo THEME_IMAGES;?>/social_icons/stumbleupon.png"></a>
        </div>
        </div>
        
        <?php endif; ?>
          
	  <?php if(get_option('portfolio_enable_comments')=='on') comments_template( '', true ); ?>
      
<?php endwhile; // end of the loop.?>

	  <?php if($layout!='full width'): ?>
          </div><!-- end primary -->

          <div id="sidebar_<?php echo $sidebar;?>"> 
                <div class="sidebar_content_<?php echo $sidebar;?> sidebar" >
            
                <?php get_sidebar(); ?>
             
                </div>
          </div><!-- end sidebar -->
      <?php endif;?>
        
        
        
		<div class="clearboth"></div>
	</div><!-- end main -->
    
</div>

<?php theme_functions('page_bg'); ?>

<?php get_footer(); ?>