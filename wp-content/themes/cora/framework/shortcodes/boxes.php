<?php

function theme_shortcode_message($atts, $content = null, $code) {

	return '<div class="' . $code  . '"><div class="message_box_wrap">' . do_shortcode($content) . '</div></div>';
}

add_shortcode('info','theme_shortcode_message');
add_shortcode('success','theme_shortcode_message');
add_shortcode('error','theme_shortcode_message');
add_shortcode('notice','theme_shortcode_message');


function theme_shortcode_framed_box($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'width' => '',
		'height' => '',
		'bgcolor' => '',
		'textcolor' => '',
		'rounded' => 'false',
	), $atts));
	
	$width = $width?'width:'.$width.'px;':'';
	$height = $height?'height:'.$height.'px;':'';

	if(!empty($width)){
		$style = ' style="'.$width.'"';
	}else{
		$style = '';
	}

	$bgcolor = $bgcolor?'background-color:'.$bgcolor.';':'';
	$textcolor = $textcolor?'color:'.$textcolor:'';
	$rounded = ($rounded == 'true')?' rounded':'';
	if( !empty($height) || !empty($bgcolor) || !empty($textcolor)){
		$content_style = ' style="'.$height.$bgcolor.$textcolor.'"';
	}else{
		$content_style = '';
	}
	
	return '<div class="' . $code .$rounded. '"'.$style.'><div class="framed_box_wrap"'.$content_style.'>' . do_shortcode($content) . '</div></div>';
}
add_shortcode('framed_box','theme_shortcode_framed_box');

function theme_shortcode_note($atts, $content = null) {
	extract(shortcode_atts(array(
		'align' => false,
		'title' => '',
		'width' => false,
	), $atts));
	$align = $align?' align'.$align:'';
	$width = $width?' style="width:'.(int)$width.'px"':'';
	if(!empty($title)){
		$title = '<h4 class="note_title">'.$title.'</h4>';
	}
	return '<div class="note' . $align . '"'.$width.'>'.$title.'<div class="note_content">' . do_shortcode($content) . '</div></div>';
}
add_shortcode('note','theme_shortcode_note');