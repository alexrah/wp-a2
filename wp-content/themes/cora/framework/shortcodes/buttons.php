<?php
function theme_shortcode_button($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'size' => 'small',
		'link' => '',
		'linktarget' => '',
		'bgcolor' => '',
		'textcolor' => '',
		'full' => "false",
		'align' => false,
	), $atts));

	$full = ($full==="false")?'':' full';
	$link = $link?' href="'.$link.'"':'';
	$linktarget = $linktarget?' target="'.$linktarget.'"':'';
	$bgcolor = $bgcolor?' background-color:'.$bgcolor.';':'';
	$textcolor = $textcolor?' color:'.$textcolor.';':'';
	if($align != 'center'){
		$aligncss = ' align'.$align;
	}else{
		$aligncss = '';
	}
	$content = '<a'.$link.$linktarget.' style="'.$bgcolor.$textcolor.'" class="button '.$size.$full.$aligncss.'">' . trim($content) . '</a>';
	if($align === 'center'){
		return '<p class="center">'.$content.'</p>';
	}else{
		return $content;
	}
}
add_shortcode('button','theme_shortcode_button');