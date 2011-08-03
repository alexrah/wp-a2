<?php
function theme_shortcode_table($atts, $content = null, $code) {

	return '<div'.($id?'id="'.$id.'"':'').' class="table_style">' . do_shortcode(trim($content)) . '</div>';
}
add_shortcode('table','theme_shortcode_table');