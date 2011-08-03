<?php

 /*
Template Name: Blog1
*/ 
 


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
	</div></div>
	<div id='slider_shadow'></div>";
else


switch($slider){
			case 'Static Image':
			    echo '<div id="sliderwrap" style="height:'.get_post_meta($post->ID,'slideshow_height_value',true).'px;"></div>';
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



</div><!-- end headerwrap -->

<?php if ( have_posts() ) while ( have_posts() ) : the_post();   ?>

<?php theme_functions('heading_wrap'); ?>

<?php
$layout = get_post_meta($post->ID, 'layout_value', true);

if($layout=='right sidebar'||$layout=='') { $layout='left'; $sidebar='right'; } 
if($layout=='left sidebar') { $layout='right'; $sidebar='left'; }



?>

<div id="main">

      <?php if($layout!='full width'): ?>
          <div id="primary" style="float:<?php echo $layout?>;">
      <?php endif;?>


			
                        <?php endwhile; ?>
                        
                        			<?php  
						
			get_template_part( 'loop','1' ); ?>
            
            <?php echo pagination(); ?>
            <span id="handwritten2"><span></span><?php _e('more blog posts','cora');?></span>
            
  
        <?php if($layout!='full width'): ?>
          </div><!-- end primary -->

          <div id="sidebar_<?php echo $sidebar;?>"> 
                <div class="sidebar_content_<?php echo $sidebar;?> sidebar" >
            
                <?php get_sidebar(); ?>
             
                </div>

          </div><!-- end sidebar -->
      <?php endif;?>
  

</div><!-- end main -->

<?php theme_functions('page_bg'); ?>

<?php get_footer(); ?>

