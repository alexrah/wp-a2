<?php
class themefunctions {
	function title(){

		wp_title( '|', true, 'right' );

		bloginfo( 'name' );
		$site_description = get_bloginfo( 'description', 'display' );
		
		if ( $site_description && is_front_page() )
			echo " | $site_description";


		
	}
	
	function sidebar($post_id = NULL){
		sidebar('get_sidebar',$post_id);
	}
	

	function page_bg(){
		
		global $post;
		
		if(has_post_thumbnail()){
		
		$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
		
		$image_height = get_post_meta($post->ID,'slideshow_height_value',true);
		if($image_height=='') $image_height=400; 

		    $res_img = my_image_resize('',$image_src_array[0],1200,$image_height,true);
           
			echo '<script>

			$(document).ready(function(){
			
				    var imageObj = new Image();
					
                    $(imageObj).attr("src","'.$res_img['url'].'").load(function(){  
					
					    $("#static_image").css({"visibility":"hidden","opacity":"0"});
					  	$("#static_image").css({
				             "background-image" : "url('.$res_img['url'].')"
							 
			            });
						$("#static_image").css({"visibility":"visible"});

						$("#static_image").animate({"opacity":"1"},500);
						  
				    });
			
			});
			</script>';
			
		}
        
    
	}
	
	function heading_wrap(){
		
		if(get_option('header_search')!='off'):
		
			if(is_archive()){
				
				$subheading= '<span id="handwritten1"><span></span>';
				
				if(is_category()){
						$heading = __('Category Archive for:','cora');
						$subheading.= sprintf('%s',single_cat_title('',false));
				}elseif(is_tag()){
					
						$heading = __('Tag Archives for:','cora');
						$subheading.= sprintf('%s',single_tag_title('',false));
					
				}elseif(is_day()){
					
						$heading = __('Daily Archive for:','cora');
						$subheading.= sprintf('%s',get_the_time('F jS, Y'));
						
				}elseif(is_month()){
					
						$heading = __('Monthly Archive for:','cora');
						$subheading.= sprintf('%s',get_the_time('F, Y'));
					
				}elseif(is_year()){
					
						$heading = __('Yearly Archive for:','cora');
						$subheading.= sprintf('%s',get_the_time('Y'));
						
				}elseif(isset($_GET['paged']) && !empty($_GET['paged'])) {
					
						$heading = __('Blog Archives','cora');
						$subheading.=  __('Blog Archives','cora');
						
				}elseif(is_author()){
					if(get_query_var('author_name')){
						$curauth = get_user_by('slug', get_query_var('author_name'));
					} else {
						$curauth = get_userdata(get_query_var('author'));
					}
					
					$heading = __('Author Archive for:','cora');
					$subheading.= sprintf('%s',$curauth->nickname);
					
				}
				
				$subheading.= '</span>';
				
				
			}elseif(is_singular('post')){
				
				$heading = get_the_title();
				$subheading= '<span id="handwritten1"><span></span>';
				$subheading.= get_the_date();
				$subheading.= '&nbsp;&nbsp;-&nbsp;&nbsp;'.get_comments_number().' comments';
				$subheading.= '</span>';
			
			}elseif (is_404()) {
				
				$heading = __('404 - Not Found','cora');
				$subheading= '<span id="handwritten1"><span></span>';
				$subheading.= __("Looks like the page you're looking for isn't here anymore. ",'cora');
				$subheading.= '</span>';
				
			}elseif (is_search()) {
				
				$heading = __('Search results for:','cora');
				$subheading= '<span id="handwritten1"><span></span>';
				$subheading.= sprintf('%s',stripslashes( strip_tags( get_search_query() ) ));
				$subheading.= '</span>';
				
			}else{
				
				global $post;
				
				$heading = get_the_title();
				$subheading= '<span id="handwritten1"><span></span>';
				$subheading.= get_post_meta($post->ID, 'subheading_value', true);
				
				if(get_post_meta($post->ID, 'subheading_value', true)=='') $subheading= '';
				else{
				   $subheading= '<span id="handwritten1"><span></span>';
				   $subheading.= get_post_meta($post->ID, 'subheading_value', true);
				   $subheading.= '</span>';	
				}
				
			}
				
				
			
		
			
			?>
			<div id="headingwrap">
			   <div id="heading">
					<h1><?php echo $heading; ?></h1>
					<?php echo $subheading; ?>
					
					<form method="get" id="searchform" action="<?php echo home_url(); ?>">
	
		<input type="text" class="text_input" value="<?php _e('Search..', 'cora');?>" name="s" id="s" onfocus="if(this.value == '<?php _e('Search..', 'cora');?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Search..', 'cora');?>';}" />
		<button type="submit" class="button"><?php _e('Search', 'cora');?></button>
					</form>
					
					
			   </div>
			</div>
        
        <?php endif;?>
		
        <?php if(get_option('display_crumbs')!='off'): ?>
		<div id="crumbs_wrap">
		<?php breadcrumbs(); ?>
		</div>
        <?php endif;
	}

	
	function about_the_author(){
		?>
        
        
        <div id="about_the_author">
            <div class="about_heading"><span></span><?php _e('About the author','cora');?></div>
                <div class="author_content">
                <div class="gravatar"><?php echo get_avatar( get_the_author_meta('email'), '60' ); ?></div>
                <div class="author_info">
                    <div class="author_name"><?php the_author_posts_link(); ?></div>
                    <p class="author_desc"><?php the_author_meta('description'); ?></p>
                </div>
                <div class="clearboth"></div>
            </div>
        </div>
        
        
        <?php
		
	}
	
	function post_box(){
		?>
		<div class="one_half">
		   <h3 class="bold_title"><?php _e('Recent Posts','cora');?></h3>
           <?php
		      $query = array('showposts' => 4, 'nopaging' => 0, 'post_status' => 'publish', 'caller_get_posts' => 1);
			  $r = new WP_Query($query);
    
			  if ($r->have_posts()){
		  
				  $output ='<ul class="recent_posts">';
				  while ($r->have_posts()) : $r->the_post(); 
					  $output .= '<li>';
		  
					  $output .= '<div class="thumbnail">';
					  $output .= '<a href="'.get_permalink().'" title="'.get_the_title().'">';
					  
					  $output .= '<div class="inner_frame">';
		  
					  if(has_post_thumbnail()): 
		  
						$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
						$image_src = THEME_SCRIPTS.'/timthumb.php?src='.$image_src_array[0].'&amp;h=60&amp;w=100&amp;zc=1'; 
		  
						$output .= '<img src="'.$image_src.'" alt="'.get_the_title().'"/>';
						
					  else:
						$output .= '<img src="'.THEME_IMAGES.'/widget_post_thumbnail.jpg" title="'.get_the_title().'" alt="'.get_the_title().'"/>';
					  endif;
					  
					  $output .= '</div></a>';
					  $output .= '<div class="caption">'.get_comments_number('0','1','%').'</div>';
					  $output .= '<div class="thumbnail_shadow"></div>';
					  $output .= '</div>';
		  
					  $output .= '<div class="post_extra_info">';
					  $output .= '<a href="'.get_permalink().'" rel="bookmark" title="'.get_the_title().'">'.get_the_title().'</a>';
		  
						  $output .= '<time datetime="'.get_the_time('Y-m-d').'">'.get_the_date().'</time>';
		  
						  $output .= '</div>';
						  $output .= '<div class="clearboth"></div>';
					  $output .= '</li>';
					  
				  endwhile; 
				  $output .= '</ul>';
		  
		  
			  } 
			  wp_reset_query();
			  echo $output;?>
           
           
           
           
           
           
		</div>
		
		<div class="one_half last">
		   <h3 class="bold_title"><?php _e('Popular posts','cora');?></h3>
             <?php
           
            $query = array('showposts' => 4, 'nopaging' => 0, 'orderby'=> 'comment_count', 'post_status' => 'publish', 'caller_get_posts' => 1);
            $r = new WP_Query($query);
        
            if ($r->have_posts()){
                $output ='<ul class="recent_posts">';
                while ($r->have_posts()) : $r->the_post(); 
                    $output .= '<li>';
        
                    $output .= '<div class="thumbnail">';
                    $output .= '<a href="'.get_permalink().'" title="'.get_the_title().'">';
                    
                    
                    
                    $output .= '<div class="inner_frame">';
                    
        
                    if(has_post_thumbnail()): 
        
                      $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
                      $image_src = THEME_SCRIPTS.'/timthumb.php?src='.$image_src_array[0].'&amp;h=60&amp;w=100&amp;zc=1'; 
        
                      $output .= '<img src="'.$image_src.'" alt="'.get_the_title().'"/>';
                      
                    else:
                      $output .= '<img src="'.THEME_IMAGES.'/widget_post_thumbnail.jpg" title="'.get_the_title().'" alt="'.get_the_title().'"/>';
                    endif;
                    
                    $output .= '</div></a>';
                    $output .= '<div class="caption">'.get_comments_number('0','1','%').'</div>';
					$output .= '<div class="thumbnail_shadow"></div>';
                    $output .= '</div>';
        
                    $output .= '<div class="post_extra_info">';
                    $output .= '<a href="'.get_permalink().'" rel="bookmark" title="'.get_the_title().'">'.get_the_title().'</a>';
        
                        $output .= '<time datetime="'.get_the_time('Y-m-d').'">'.get_the_date().'</time>';
        
                        $output .= '</div>';
                        $output .= '<div class="clearboth"></div>';
                    $output .= '</li>';
                    
                endwhile; 
                $output .= '</ul>';
            } 
            wp_reset_query();
            echo $output;
           
           ?>
           
		</div>
        <div class="clearboth"></div>
        
        <?php
		
	}
	
	function slider(){
		
		$type = get_option('slider_type');
		switch($type){
			case 'Nivo Slider':
				$this->slider_nivo();
				break;
			case 'PieceMaker 2 (3d Flash Image Rotator)':
				$this->slider_piecemaker();
				break;
			case 'Kwicks Accordion Slider':
				$this->slider_kwicks();
				break;	
		    case 'Full Width Slider':
				$this->slider_full();
				break;	
            case 'Blox Slider':
				$this->slider_blox();
				break;	
		}
		
	}
		
	function sliderHeader() {
		
		
		$type = get_option('slider_type');
		switch($type){
			case 'Nivo Slider':
				$this->sliderHeader_nivo();
				break;
			case 'PieceMaker 2 (3d Flash Image Rotator)':
				$this->sliderHeader_piecemaker();
				break;
			case 'Kwicks Accordion Slider':
				$this->sliderHeader_kwicks();
				break;
			case 'Full Width Slider':
				$this->sliderHeader_full();
				break;
		    case 'Blox Slider':
				$this->sliderHeader_blox();
				break;	
				
		
		}
		
	}
	function sliderHeader_nivo() {
		wp_enqueue_script('jquery-nivo', THEME_JS . '/jquery.nivo.slider.pack.js');
	}
	function sliderHeader_piecemaker() {	
		wp_enqueue_script('swfobject', THEME_JS . '/swfobject.js');
		
	}
	function sliderHeader_kwicks() {  
		wp_enqueue_script('jquery-easing', THEME_JS . '/jquery.easing.1.3.js');
		wp_enqueue_script('jquery-kwicks', THEME_JS . '/kwicks.js');
	}
	
	function sliderHeader_full() {  
		wp_enqueue_script('full.width.slider', THEME_JS . '/full.width.slider.js');
	}
	
	function sliderHeader_blox() {  
		wp_enqueue_script('jqueryblox', THEME_JS . '/grid_slider.js');
	}
	
	function sliderHeader_blox2() {  
		wp_enqueue_script('jqueryblox2', THEME_JS . '/grid_slider2.js');
	}
	
	function slideShow_getImages($set=''){


		global $post;

		
		if($set=='') $set=get_post_meta($post->ID, 'slideshow_set_value', true) ;
	
		$loop = new WP_Query( array( 'post_type' => 'slideshow', 'showposts'=>'-1', 'orderby'=>'menu_order', 'order'=>'ASC', 'slideshow_set' => $set ) );
		$images = array();
		while ( $loop->have_posts() ) : $loop->the_post();
		
			$link = get_post_meta(get_the_ID(), 'link_value', true);
			$image_id = get_post_thumbnail_id();
			$image_url = wp_get_attachment_image_src($image_id,$size, true);
			$images[] = array(
				'title' => get_the_title(),
				'desc'  => get_post_meta(get_the_ID(), 'description_value', true),
				'src' => $image_url[0],
				'link' => $link,
				'bgcolor'  => get_post_meta(get_the_ID(), 'bg_color_value', true)
 			);
		endwhile;
		

 wp_reset_postdata();

		
		return $images;
		

	}
	
	function slider_nivo(){
		
		global $post;
		
		$height=get_post_meta($post->ID, 'slideshow_height_value', true);
		if($height=='') $height = get_option('slider_height');
		
		$captions=get_post_meta($post->ID, 'slideshow_captions_value', true);
		if($captions=='off') $captions=0;
		else $captions=0.8;
		
		$images = $this->slideShow_getImages();
		$bg_color = get_option('bg_color_transitions');
		$i=0;
		
		
		echo '<div id="slider_bg"></div>';
		echo '<div id="slider" style="height:'.$height.'px">';
		
		foreach($images as $image) {
			if($image['link'] != ''){
				echo "\n".'<a href="'.$image['link'].'"><img src="' . THEME_SCRIPTS.'/timthumb.php?src='.$image['src'].'&amp;h='.$height.'&amp;w=960&amp;zc=1' . '" title="#html'.$i.'" /></a>';
			}else{
				echo "\n".'<img src="' . THEME_SCRIPTS.'/timthumb.php?src='.$image['src'].'&amp;h='.$height.'&amp;w=960&amp;zc=1' . '" title="#html'.$i.'" />';
			}
			$i++;
		}
		

	echo '</div>';
	echo '<div id="slider_shadow"></div>';

	    $i=0;
		if($captions!=0 ) 
		    foreach($images as $image) {
				echo '<div id="html'.$i.'" class="nivo-html-caption">
				<div class="nivo-title">'.$image['title'].'</div> '.$image['desc'].'
				</div>';
				$i++;
		    }

echo <<<HTML

</div>

HTML;
		
	 echo "<script>";
		  
		echo " var bgcolors=new Array();";
   		$i=1;
		if($bg_color=='on') $bg_color="$('#headerwrap').animate({ backgroundColor: bgcolors[$('.nivo-controlNav a.active').html()] }, 750);";
		else $bg_color="";
		if($bg_color) foreach($images as $image) {
			if($image['bgcolor']=="")  $image['bgcolor']="#999999";
			echo " bgcolors[".$i."] = '" . $image['bgcolor'] . "'; \n";
			$i++;
		}
		
   echo " $(window).load(function() {";
  
      echo "  $('#slider').nivoSlider({
		effect:'".get_option('nivo_effect')."',
		slices:".get_option('nivo_slices').",
		animSpeed:".get_option('nivo_animSpeed').",
		pauseTime:".get_option('nivo_pauseTime').",
		directionNav:".get_option('nivo_directionNav').",
		directionNavHide:".get_option('nivo_directionNavHide').",
		keyboardNav:".get_option('nivo_keyboardNav').",
		pauseOnHover:".get_option('nivo_pauseOnHover').",
		manualAdvance:".get_option('nivo_manualAdvance').", 
		captionOpacity:".$captions.",
		
		customChange: function(){
			   Cufon.replace('.nivo-caption .nivo-title',{fontFamily:'Nevis'});
            },
		
	    afterChange: function(){    
		 
		var bg = bgcolors[$('.nivo-controlNav a.active').html()];";


        if(get_option('bg_color_transitions')=='on') echo "
		$('#slider_bg').css({backgroundColor:  bg}).animate({height: '100%'}, 800, function() {
                                                             $('#headerwrap').css({ backgroundColor: bg });
															 $('#slider_bg','#headerwrap').css({height:0}); 
                                                                     });  ";
																	 
		echo "
		
		
		},
		afterLoad: function(){";
			
		if(get_option('bg_color_transitions')=='on') echo "
		
		var bg = bgcolors[$('.nivo-controlNav a.active').html()];
		
		$('#slider_bg').css({backgroundColor:  bg}).animate({height: '100%'}, 800, 'easeOutQuint', function() {
                                                             $('#headerwrap').css({ backgroundColor: bg });
															 $('#slider_bg','#headerwrap').css({height:0}); 
                                                                     });  ";
		
		
		echo "Cufon.replace('.nivo-title',{fontFamily:'Nevis'});
		
			}

   });
   
    
        $('.nivo-controlNav').animate({'left': '0px'}, 0);
		$('.nivo-controlNav').css({'display': 'none'});
		
		var a_count = $('.nivo-controlNav a').size();
		a_count = parseInt(a_count);

		a_count = a_count * 17;
		var width = a_count;
		var x_pos = ((960 / 2) - (width  / 2)) ;
		
		$('.nivo-controlNav').css({'display': 'block'});
		$('.nivo-controlNav').animate({'left': x_pos}, 0);
   
    });
    </script>";

	}
	
	function slider_kwicks() {
		
		global $post;
		
		$height=get_post_meta($post->ID, 'slideshow_height_value', true);
		if($height=='') $height = get_option('slider_height');
		
		$captions=get_post_meta($post->ID, 'slideshow_captions_value', true);
		
        $images = $this->slideShow_getImages();
		$number = get_option('kwicks_number');
		$bg_color=get_option('bg_color_transitions');
		$number = count($images);
		echo <<<HTML


			
HTML;
		echo '<ul id="kwicks" style="height:'.$height.'px" class="kwicks-number-'.$number.'">';
		
		$i=1;
		foreach($images as $image) {
			if($image['link'] != ''){
				$link = $image['link'];
			}else{
				$link = '#';
			}
			echo "\n<li style='height:".$height."px' ><div class='kwicks_hidden'>".$i."</div>";
		
			echo '<a href="'.$link.'"><img src="' . THEME_SCRIPTS.'/timthumb.php?src='.$image['src'].'&amp;h='.$height.'&amp;w='. get_option('kwicks_max') .'&amp;zc=1' . '" alt="" /></a>';
			
			
			if(get_option('kwicks_caption_opacity')!='0' && $captions=='on'){
				
			echo '<div class="kwick_title">
			   <span style="color:white;">' . $image['title'] . '</span>';
			   if($image['desc']!='') echo '<p>' . $image['desc'] . '</p></div>';
			}


			echo "</li>";
			$i++;
		}
echo <<<HTML
</ul>
<div id="slider_shadow"></div>
</div>

HTML;

		echo "<script type=\"text/javascript\">

		   var bgcolors=new Array();";
   		$i=1;
		foreach($images as $image) {
			if($image['bgcolor']=="")  $image['bgcolor']="#999999";
			echo " bgcolors[".$i."] = '" . $image['bgcolor'] . "'; \n";
			$i++;
		}

	echo "	
	 $(document).ready(function() {  
	 
	 		   $('#kwicks li a img').each(function(){
		            
					
				    var imageObj = new Image();
					var img=$(this);
                    $(imageObj).attr('src',$(this).attr('src')).load(function(){  
					    
					      img.fadeIn(400);
					
					});
					
	           });
	 
	 
	 
          $('#kwicks').kwicks({  
               max : ".get_option('kwicks_max').", 
     		   duration: ".get_option('kwicks_duration').",
			   easing: '".get_option('kwicks_easing')."',
			   spacing:0	    
          });
		  
		  
		  $('.kwick_title,','#kwicks li').fadeTo('fast', ".get_option('kwicks_caption_opacity').");
		  
		  
		  $('#kwicks li').hover(function() {";
		  
		  if($bg_color=='on')
			echo "$('#headerwrap').stop().animate({ backgroundColor: bgcolors[$(this).children('.kwicks_hidden').html()] }, 1000);";
			
			echo "
			
			
			$('.kwick_title,','#kwicks li').css({display:'none'});
			
 		}, function() {
 			$(this).children('.kwick_title').children('p').css({display:'none'});
		});
		
	    
		$('#kwicks').hover(function() {
 			

 		}, function() {";
			
			if($bg_color=='on')
 			echo "$('#headerwrap').stop().animate({ backgroundColor: '#cfcfcf' }, 1000);";
			
			echo "$('.kwick_title,','#kwicks li').fadeTo('fast', ".get_option('kwicks_caption_opacity').");
		});

      });  
      </script>";
	 		
	 }
	
	function slider_full(){
		
global $post;
		
$image_height=get_post_meta($post->ID, 'slideshow_height_value', true);
if($image_height=='') $image_height = get_option('slider_height');

$captions=get_post_meta($post->ID, 'slideshow_captions_value', true);

$full_image_width=get_option('full_image_width');
     
echo '



<div id="full_slider" style="height:'.$image_height.'px;">

    
	<!-- jQuery handles to place the header background images -->
	<div id="headerimgs">
	    
		<div id="headerimg1" class="headerimg" style="height:'.$image_height.'px;"></div>
		<div id="headerimg2" class="headerimg" style="height:'.$image_height.'px;"></div>
		
	</div>
	<!-- Top navigation on top of the images -->

	<!-- Slideshow controls -->
	<div id="headernav-outer">
		<div id="headernav">
			<div id="back" class="btn"></div>
			<div id="next" class="btn"></div>
		</div>
	</div>
	
';	

if($captions=='on')  echo '
	
	<!-- jQuery handles for the text displayed on top of the images -->
	<div id="headertxt">
		<p class="caption">
			<h2 id="firstline"></h2>
		</p>
		<p class="pictured">
            <span id="pictureduri"></span>
		</p>
	</div>
	
';
	

echo '

	
	<div id="full_slider_bg" style="height:'.$image_height.'px;"></div>
	
</div>

</div><!-- end headerwrap --> 
	
';

$images = $this->slideShow_getImages();


echo '<script>';



echo '
var slideshowSpeed = '.get_option('full_width_animation').';

var photos = [ ';
$i=1;
foreach($images as $image) {
	
	$res_img = my_image_resize('',$image['src'],$full_image_width,$image_height,true);

	echo '
    {	"title" : "'.$image['title'].'",
		"image" : "'.$res_img['url'].'",
		"firstline" : "'.$image['title'].'",
		"secondline" : "'.$image['desc'].'" 
		}';
		
		
		if($i!=count($images)) echo ',';
	
	$i++;
}
echo '];


</script>';
     
     }
	 
	function slider_blox(){
		
	   global $post;
		
	   $captions=get_post_meta($post->ID, 'slideshow_captions_value', true);
	   
	   $height=get_post_meta($post->ID, 'slideshow_height_value', true);
	   if($height=='') $height = get_option('slider_height');
	   $height=floor($height/3);

	   
	   $images = $this->slideShow_getImages();

	   ?>
       
       
          <div id="nav_left"></div>
          <div id="nav_right"></div>
 
       
       <div id="grid_container" style="height:<?php echo $height*3+8; ?>px">
       
       <ul id="grid_slider" style="height:<?php echo $height*3+8; ?>px">
       
       <?php
	   
	   $begin=true;
	   $col=1;
	   $row=1;
	   $i=1;
	   
	   foreach($images as $image) {
		   
		   if($begin==true) {
			   echo  '<li><ul class="sub_grid">';
			   $begin=false;
		   }
		   
		   $i++;
		   
		   if($i-1==count($images)) {
			   
			   if($col==1&&$row==1)  $h=$height*3+4; 
			   elseif($col==1&&$row==2) $h=$height;
			   elseif($col==2&&$row==1) $h=$height*3+4; 
			   elseif($col==2&&$row==2) $h=$height*2+2; 
			   elseif($col==2&&$row==3)  $h=$height;
			   elseif($col==3&&$row==1) $h=$height*3+4;   
			   elseif($col==3&&$row==2) $h=$height*2+2; 
			   
		   }
		   else{
			   if($col==1&&$row==1) { $h=$height;  $row++; }
			   elseif($col==1&&$row==2) { $h=$height; $row++; }
			   elseif($col==1&&$row==3) { $h=$height; $row++; $col=2; $row=1; $begin=true;}
			   elseif($col==2&&$row==1) { $h=$height*2+2; $row++; }
			   elseif($col==2&&$row==2) { $h=$height; $col=3; $row=1; $begin=true; }
			   elseif($col==3&&$row==1) { $h=$height; $row++; }
			   elseif($col==3&&$row==2) { $h=$height*2+2;  $col=1; $row=1; $begin=true; }
			   
		   }
		   
		   echo '<li style="height:'.$h.'px">';
		   
		   
		   
		   if($image['link']=='')  echo '<a class="lightbox" href="'.$image['src'].'" rel="grid_group" title="'.$image['title'].'">';
		   elseif( substr_count($image['link'], 'vimeo')==1 ) echo '<a class="lightbox" href="'.$image['link'].'" rel="grid_group" title="'.$image['title'].'">';
		   
		   else  echo '<a href="'.$image['link'].'" rel="grid_group" title="'.$image['title'].'">';
		   
		   echo '<div class="caption">';
				   
				    if($captions=='on') echo '
                      <div class="desc">
                         <p class="caption_title">'.$image['title'].'</p>
                         <p>'.$image['desc'].'</p>
                      </div>';
					  
			echo '		  
                   </div>
                   <img src="'.THEME_SCRIPTS.'/timthumb.php?src='.$image['src'].'&amp;h='.($h+30).'&amp;w=330&amp;zc=1" alt="'.$image['title'].'"/>
                   </a>
               </li>';
			   
		   if($begin==true) echo '</ul></li>';
		   
		   
		   
	   }
	   
	   ?>
        
          </ul>
          

       </div>
       
       <?php
	    	
	}
	
	
	function slider_blox2(){
		
	   global $post;
		
	   $captions=get_post_meta($post->ID, 'slideshow_captions_value', true);
	   
	   $height=get_post_meta($post->ID, 'slideshow_height_value', true);
	   if($height=='') $height = get_option('slider_height');

	   $h=floor($height/3);

	   
	   $images = $this->slideShow_getImages();
	   
	   

	   ?>
       
       
          <div id="nav_left"></div>
          <div id="nav_right"></div>
 
       
       <div id="grid_container2" style="height:<?php echo $height+5;?>px">
       
       <ul id="grid_slider2" style="height:<?php echo $height+5; ?>px">
       
       <?php
	   
	  
	   
	   
	   $row=1;
	   $x=0;
	   $y=0;
	   $end=false;
	   
	   foreach($images as $image) {
		   

		   
			   if($row==1) { $row++; $y=0; $class='top_grid';}
			   elseif($row==2) { $row++; $y=$h+2; $class='middle_grid';}
			   elseif($row==3) { $row=1; $y=2*$h+4; $class='bottom_grid'; $end=true; }

			   
		   
		   
		   echo '<li style="height:'.$h.'px; top:'.$y.'px; left:'.$x.'px;" class="'.$class.'">';
		   
		   
		   
		   if($image['link']=='')  echo '<a class="lightbox" href="'.$image['src'].'" rel="grid_group" title="'.$image['title'].'">';
		   elseif( substr_count($image['link'], 'vimeo')==1 ) echo '<a class="lightbox" href="'.$image['link'].'" rel="grid_group" title="'.$image['title'].'">';
		   
		   else  echo '<a href="'.$image['link'].'" rel="grid_group" title="'.$image['title'].'">';
		   
		   echo '<div class="caption">';
				   
				    if($captions=='on') echo '
                      <div class="desc">
                         <p class="caption_title">'.$image['title'].'</p>
                         <p>'.$image['desc'].'</p>
                      </div>';
					  
			echo '		  
                   </div>
                   <img src="'.THEME_SCRIPTS.'/timthumb.php?src='.$image['src'].'&amp;h='.($h+60).'&amp;w=360&amp;zc=1" alt="'.$image['title'].'"/>
                   </a>
               </li>';
			   

		   if($end==true) { $x+=300+2; $end=false; }
		   
		   
	   }
	   
	   ?>
        
          </ul>
          

       </div>
       
       <?php
	    	
	}
	
	function slider_piecemaker() {
		
		$uri = THEME_URI;
		
		global $post;
		
		$piecemaker_height = get_option('slider_height')+100;
		
		$piecemaker_set=get_post_meta($post->ID, 'slideshow_set_value', true);

		$noflash = __('You need to <a href="http://www.adobe.com/products/flashplayer/" target="_blank">Upgrade your Flash Player</a> to version 10 or newer.','cora');
		
		$output = <<<HTML

    <div style="width:1060px; height:{$piecemaker_height}px; margin:0px auto; margin-top:100px;">
      <div id="piecemaker">
           <p style="text-align:center;">{$noflash}</p>

       </div>
     </div>
	 


</div><!-- end headerwrap -->

   <script type="text/javascript">


      var flashvars = {};
      flashvars.cssSource = "{$uri}/piecemaker/piecemaker.css";
      flashvars.xmlSource = "{$uri}/piecemaker/piecemakerXML.php?pc_post={$post->ID}";
		
      var params = {};
      params.play = "true";
      params.menu = "false";
      params.scale = "showall";
      params.wmode = "transparent";
      params.allowfullscreen = "true";
      params.allowscriptaccess = "always";
      params.allownetworking = "all";
	  
      swfobject.embedSWF("{$uri}/piecemaker/piecemaker.swf", 'piecemaker', '100%', '100%', '10', null, flashvars,    
      params, null);


</script>


HTML;
		echo $output;
	}
  //end piecemaker
}




function theme_functions($function){
	global $_themefunctions;
	$_themefunctions = new themefunctions;
	$args = array_slice( func_get_args(), 1 );
	return call_user_func_array(array( &$_themefunctions, $function ), $args );
}
