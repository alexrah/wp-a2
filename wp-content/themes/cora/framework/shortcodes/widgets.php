<?php

function theme_shortcode_contactform($atts,$content = null) {
	extract(shortcode_atts(array(
		'email' => get_bloginfo('admin_email'),
	), $atts));
    wp_print_scripts('jquery-tools-validator');

    $content = trim($content);
	if(!empty($content)){
		$success = do_shortcode($content);
	}

	if(empty($success)){
		$success = __('Your message was successfully sent!','cora');
	}
	
	$include_path = THEME_SCRIPTS;
	return <<<HTML
[raw]
<div class="contact_form_wrap">
	<div class="success" style="display:none;"><div class="message_box_wrap">{$success}</div></div> 
	<form class="contact_form" action="{$include_path}/send.php" method="post">
		<p><input type="text" required="required" id="contact_name" name="contact_name" class="text_input" value="" tabindex="5" />
		<label for="contact_name">Name *</label></p>
		<p><input type="email" required="required" id="contact_email" name="contact_email" class="text_input" value="" tabindex="6"  />
		<label for="contact_email">Email *</label></p>
		<p><textarea required="required" name="contact_content" class="textarea" cols="30" rows="5" tabindex="7"></textarea></p>
		<p><button type="submit" class="button white"><span>Submit</span></button></p>
		<input type="hidden" value="{$email}" name="contact_to"/>
	</form>
</div>
[/raw]
HTML;
}
add_shortcode('contactform', 'theme_shortcode_contactform');


function theme_shortcode_twitter($atts) {
	extract(shortcode_atts(array(
		'username' => '',
		'count'=> 1
	), $atts));
	
	wp_print_scripts('jquery-tweet');
	$id = rand(1,1000);
	
	$output='
<div class="twitter_wrap">
<script type="text/javascript">
jQuery(document).ready(function($) {
	jQuery("#twitter_wrap_'.$id.'").tweet({
		username: "'.$username.'",
		count: "'.$count.'",
		seconds_ago_text: "'.__('about %d seconds ago','cora').'",
		a_minutes_ago_text: "'. __('about a minute ago','cora').'",
		minutes_ago_text: "'. __('about %d minutes ago','cora').'",
		a_hours_ago_text: "'. __('about an hour ago','cora').'",
		hours_ago_text: "'. __('about %d hours ago','cora').'",
		a_day_ago_text: "'. __('about a day ago','cora').'",
		days_ago_text: "'. __('about %d days ago','cora').'",
		view_text: "'. __('view tweet on twitter','cora').'"			
	});
});
</script>
	<div id="twitter_wrap_'.$id.'"></div>
	<div class="clearboth"></div>
</div>
	';
	
	return '[raw]'.$output.'[/raw]';
}
add_shortcode('twitter', 'theme_shortcode_twitter');

function theme_shortcode_flickr($atts) {
	extract(shortcode_atts(array(
		'id' => '',
		'count' => 4,
		'display' => 'latest'
	), $atts));
	
	return <<<HTML
[raw]
<div class="flickr_wrap">
	<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count={$count}&amp;display={$display}&amp;size=85,60&amp;layout=x&amp;source=user&amp;user={$id}"></script>
</div>
<div class="clearboth"></div>
[/raw]
HTML;
}
add_shortcode('flickr', 'theme_shortcode_flickr');

function theme_shortcode_contact_details($atts) {
	extract(shortcode_atts(array(
		'color' => '',
		'phone' => '',
		'cellphone' => '',
		'email' => '',
		'address' => '',
		'city' => '',
		'state' => '',
		'zip' => '',
		'name' => '',
	), $atts));
	
	if(!empty($city) && !empty($state)){
		$city = $city.',&nbsp;'.$state;
	}elseif(!empty($state)){
		$city = $state;
	}
	if(!empty($color)){
		$color = ' '.$color;
	}
	
	$output = '<div class="contact_info_wrap">';
	if(!empty($phone)){
		$output .= '<p><span class="icon_text icon_phone'.$color.'">'.$phone.'</span></p>';
	}
	if(!empty($cellphone)){
		$output .= '<p><span class="icon_text icon_cellphone'.$color.'">'.$cellphone.'</span></p>';
	}
	if(!empty($email)){
		$output .= '<p><a href="mailto:'.$email.'" class="icon_text icon_email'.$color.'">'.$email.'</a></p>';
	}
	if(!empty($address)){
		$output .= '<p><span class="icon_text icon_home'.$color.'">'.$address.'</span></p>';
	}
	if(!empty($city)){
        $output .= '<p><span class="icon_text icon_globe'.$color.'">'.$city.' '.$zip.'</span></p>';
	}
	if(!empty($name)){
		$output .= '<p><span class="icon_text icon_user'.$color.'">'.$name.'</span></p>';
	}
	$output .= '</div>';
	return $output;
}
add_shortcode('contact_details', 'theme_shortcode_contact_details');

function theme_shortcode_popular_posts($atts) {
	extract(shortcode_atts(array(
		'count' => '4',
		'thumbnail' => 'true',
		'extra' => 'desc',
		'cat' => '',
		'desc_length' => '80',
	), $atts));
	
	$query = array('showposts' => $count, 'nopaging' => 0, 'orderby'=> 'comment_count', 'post_status' => 'publish', 'caller_get_posts' => 1);
	if($cat){
		$query['cat'] = $cat;
	}
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
	return '[raw]'.$output.'[/raw]';
}
add_shortcode('popular_posts', 'theme_shortcode_popular_posts');

function theme_shortcode_recent_posts($atts) {
	extract(shortcode_atts(array(
		'count' => '4',
		'cat' => '',
	), $atts));
	
	$query = array('showposts' => $count, 'nopaging' => 0, 'post_status' => 'publish', 'caller_get_posts' => 1);
	if($cat){
		$query['cat'] = $cat;
	}
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
	return '[raw]'.$output.'[/raw]';
}
add_shortcode('recent_posts', 'theme_shortcode_recent_posts');

function theme_shortcode_googlemap($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		"width" => false,
		"height" => '400',
		"latitude" => 0,
		"longitude" => 0,
		"zoom" => 1,
		"html" => '',
		"popup" => 'false',
		"maptype" => 'G_NORMAL_MAP',
		"marker" => 'true',
	), $atts));
	
	if($width && is_numeric($width)){
		$width = 'width:'.$width.'px;';
	}else{
		$width = '';
	}
	if($height && is_numeric($height)){
		$height = 'height:'.$height.'px';
	}else{
		$height = '';
	}
	
	$id = rand(100,1000);
	if($marker != 'false'){
		return <<<HTML
[raw]
<div id="google_map_{$id}" class="google_map" style="{$width}{$height}"></div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	jQuery("#google_map_{$id}").gMap({
	    zoom: {$zoom},
	    markers:[{
			latitude: {$latitude},
	    	longitude: {$longitude},
	    	html: "{$html}",
	    	popup: {$popup}
		}],
		maptype: {$maptype},
		controls: false
	});
});
</script>
[/raw]
HTML;
	}else{
return <<<HTML
[raw]
<div id="google_map_{$id}" class="google_map" style="{$width}{$height}"></div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	jQuery("#google_map_{$id}").gMap({
	    zoom: {$zoom},
	    latitude: {$latitude},
	    longitude: {$longitude},
		maptype: {$maptype},
		controls: false
	});
});
</script>
[/raw]
HTML;
	}
}

add_shortcode('gmap','theme_shortcode_googlemap');
