<?php

function theme_shortcode_image($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'size' => 'medium',
		'link' => '#',
		'icon' => false,
		'caption' => false,
		'lightbox' => 'false',
		'title' => '',
		'align' => false,
		'group' => '',
		'width' => false,
		'height' => false,
	), $atts));
	if(!$width&&!$height){
		$width = get_option($size.'_width');
		$height = get_option($size.'_height');
		if(!$width){
			$width = '150';
		}
		if(!$height){
			$height = '150';
		}
	}
	$src = trim($content);
	$no_link = '';
	if($lightbox == 'true'){
		if($link == '#'){
			$link = $src;
		}
	}else{
		if($link == '#'){
			$no_link = ' image_no_link';
		}
	}
	
	$content = '<img alt="'.$title.'" src="'.THEME_SCRIPTS.'/timthumb.php?src='.$src.'&amp;h='. $height .'&amp;w='. $width .'&amp;zc=1" />';
		
	return '<div class="image_container'.($align?' align'.$align:'').'"><div class="frame" style="width:'.$width.'px;height:'.$height.'px;"><a'.($group?' rel="'.$group.'"':'').' class="image_size_'.$size.$no_link.($icon?' icon_'.$icon:'').($lightbox =='true'?' lightbox':'').'" title="'.$title.'" href="'.$link.'">' . $content . '</a>'.($caption?'<div class="caption">'.$caption.'</div>':'').'</div></div>';
}

add_shortcode('image', 'theme_shortcode_image');

