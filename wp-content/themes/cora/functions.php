<?php
require_once (TEMPLATEPATH . '/framework/functions.php');

$theme = new Theme();
$theme->init(array(
	'theme_name' => 'Cora', 
	'theme_slug' => 'cora'
));

/*
function get_custom_field_value($szKey, $bPrint = false) {
  global $post;
  $szValue = get_post_meta($post->ID, $szKey, true);
  if ( $bPrint == false ) require_onceeturn $szValue; else echo $szValue;
}
 */
?>
