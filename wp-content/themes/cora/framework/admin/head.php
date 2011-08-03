<?php
//checks to see if is on admin page or a post type and then loads the necesary scripts
if(theme_is_options() || theme_is_post_type()){
	add_action('admin_init', 'admin_add_scripts');
}

function admin_add_scripts() {
	
	wp_enqueue_style( 'colorpicker', get_template_directory_uri().'/framework/admin/css/colorpicker.css');
	wp_enqueue_style( 'admin-style', get_template_directory_uri().'/framework/admin/admin-style.css');
	wp_enqueue_script('jqueryrange',get_template_directory_uri().'/framework/admin/js/jquery.tools.min.js');
	wp_enqueue_script( 'colorpicker3', get_template_directory_uri().'/framework/admin/js/colorpicker.js');
	wp_enqueue_script( 'ajaxupload', get_template_directory_uri().'/framework/admin/js/ajaxupload.js');
	wp_enqueue_script( 'jquery.maskedinput-1.2.2', get_template_directory_uri().'/framework/admin/js/jquery.maskedinput-1.2.2.js');
	wp_enqueue_script( 'shortcode', get_template_directory_uri().'/framework/admin/js/shortcodeGenerator.js');
	wp_enqueue_script( 'jquery.tools.validator', get_template_directory_uri().'/framework/admin/js/jquery.tools.validator.js');
	
	
}

?>