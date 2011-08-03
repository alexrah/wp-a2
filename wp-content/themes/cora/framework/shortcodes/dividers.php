<?php
function theme_shortcode_divider() {
	return '<div class="divider"></div>';
}
add_shortcode('divider', 'theme_shortcode_divider');

function theme_shortcode_divider_line() {
	return '<div class="divider_line"></div>';
}
add_shortcode('divider_line', 'theme_shortcode_divider_line');


function theme_shortcode_divider_dashed() {
	return '<div class="divider_dashed"></div>';
}
add_shortcode('divider_dashed', 'theme_shortcode_divider_dashed');

function theme_shortcode_divider_top() {
	return '<div class="divider top"><a href="#">'.__('Top','cora').'</a></div>';
}
add_shortcode('divider_with_top_link', 'theme_shortcode_divider_top');

function theme_shortcode_padding() {
	return '<div class="divider_padding"></div>';
}
add_shortcode('padding', 'theme_shortcode_padding');



function theme_shortcode_clearboth() {
   return '<div class="clearboth"></div>';
}
add_shortcode('clearboth', 'theme_shortcode_clearboth');