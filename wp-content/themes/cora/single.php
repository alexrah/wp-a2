<?php
$layout = get_post_meta($post->ID, 'layout_value', true);
if(empty($layout) || $layout == 'default'){
	$layout=get_option('blog_layout');
}
if($layout=='right sidebar') { $layout='left'; $sidebar='right'; } 
if($layout=='left sidebar') { $layout='right'; $sidebar='left'; }




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

</div><!-- end headerwrap -->

<?php if ( have_posts() ) while ( have_posts() ) : the_post();   ?>
      
      <?php theme_functions('heading_wrap'); ?>
     
     
      <div id="main">
      <?php if($layout!='full width'): ?>
          <div id="primary" style="float:<?php echo $layout?>;">
      <?php endif;?>
      
          <h2 style="text-align: center;"><?php echo get_the_title() ?></h2>  
        <div id="blog_content"><?php the_content(); ?></div>
<!-- FROM HERE START CUSTOM FIELD TEMPLATE --> 
        <h3>La Struttura</h3>
          <h5 style="text-align: justify;"><p class="one_half"><?php echo get_post_meta($post->ID, 'La Struttura', true); ?></p></h5>      
              <div class="one_half last"> <!-- SCHEDA TECNICA INIZIO -->  
<table border="0" width="250px" style=" margin-left: 40px;" >
  <tbody>
    <tr>
      <td colspan="2" style="background-color: #CFCFCF;">
<h4>SCHEDA TECNICA</h4>
      </td>
    </tr>
    <tr>
      <td>
        <h4><?php echo get_post_meta($post->ID, 'Impianto', true); ?></h4>
      </td>
      <td>
        <h4 style="text-align: right;"><?php echo get_post_meta($post->ID, 'Tipo Impianto', true); ?></h4>
      </td>
    </tr>
    <tr>
      <td>
        <h4><?php echo get_post_meta($post->ID, 'Unita esterne', true); ?></h4>
      </td>
      <td>
        <h4 style="text-align: right;"><?php echo get_post_meta($post->ID, 'N esterne', true); ?></h4>
      </td>
    </tr>
    <tr>
      <td>
        <h4><?php echo get_post_meta($post->ID, 'Unita interne', true); ?></h4>
      </td>
      <td>
        <h4 style="text-align: right;"><?php echo get_post_meta($post->ID, 'N interne', true); ?></h4>
      </td>
    </tr>
    <tr>
      <td>
          <?php $fields = get_post_meta($post->ID, 'Potenza', false); ?>
            <?php foreach($fields as $field) {
                    echo '<h4>'.$field.'</h4>'; 
            } ?>
      </td>
      <td>
        <h4 style="text-align: right;"><?php echo get_post_meta($post->ID, 'N potenza', true); ?></h4>
      </td>
    </tr>
    <tr>
      <td>
        <h4><?php echo get_post_meta($post->ID, 'Canalizzazioni', true); ?></h4>
      </td>
      <td>
        <h4 style="text-align: right;"><?php echo get_post_meta($post->ID, 'tipo canalizzazioni', true); ?></h4>
      </td>
    </tr>
    <tr>
      <td>
        <h4><?php echo get_post_meta($post->ID, 'Diffusori', true); ?></h4>
      </td>
      <td>
        <h4 style="text-align: right;"><?php echo get_post_meta($post->ID, 'tipo diffusori', true); ?></h4>
      </td>
    </tr>
    <tr>
      <td>
        <h4><?php echo get_post_meta($post->ID, 'Altro 1', true); ?></h4>
      </td>
      <td>
        <h4 style="text-align: right;"><?php echo get_post_meta($post->ID, 'tipo altro 1', true); ?></h4>
      </td>
    </tr>
    <tr>
      <td>
        <h4><?php echo get_post_meta($post->ID, 'Altro 2', true); ?></h4>
      </td>
      <td>
        <h4 style="text-align: right;"><?php echo get_post_meta($post->ID, 'tipo altro 2', true); ?></h4>
      </td>
    </tr>

  </tbody>
</table>
              </div>
<h3>intervento Felline</h3>
<blockquote>
<h5><?php echo get_post_meta($post->ID, 'Intervento Felline 1', true); ?></h5>
<h5><?php echo get_post_meta($post->ID, 'Intervento Felline 2', true); ?></h5>
<h5><?php echo get_post_meta($post->ID, 'Intervento Felline 3', true); ?></h5>
</blockquote>
<!-- FROM HERE START THE POST WIDGET AREA --> 
             <div class="divider_line"></div>
            <div class="divider"></div>
          <div id="post_widget>">
            <div class="one_half" id="post_widget_left" ><?php dynamic_sidebar('Post Widget Right'); ?></div>  
            <div class="one_half last" id="post_widget_right" ><?php dynamic_sidebar('Post widget Left'); ?></div>
          </div> <!--end of post_widget -->
        <div class="clearboth"></div>

        
        <p><?php edit_post_link(__( 'Edit this Post', 'cora' ),'',''); ?></p>
        
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
        
        <?php if(get_option('about_author')=='on') theme_functions('about_the_author'); ?>
        
        <?php if(get_option('posts_box')=='on') theme_functions('post_box'); ?>
        
        <?php comments_template( '', true ); ?>
        
        <?php endwhile; // end of the loop. ?>
         
      <?php if($layout!='full width'): ?>
          </div><!-- end primary -->
<!-- ADD NEW RESIDENZIALE SIDEBAR HOOKER  -->
          <div id="sidebar_<?php echo $sidebar;?>"> 
                <div class="sidebar_content_<?php echo $sidebar;?> sidebar" >
            
                <?php get_sidebar(); ?>
             
                </div>

          </div><!-- end sidebar -->
      <?php endif;?>
        
      <div class="clearboth"></div>
        
      </div><!-- end main -->

<?php theme_functions('page_bg'); ?>	 

<?php get_footer(); ?>
