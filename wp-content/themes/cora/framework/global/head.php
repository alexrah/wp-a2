<?php 
/**
 * JavaScripts In Header
 */
function theme_enqueue_scripts() {
	

	if(!is_admin()){
		
		
		
		
		wp_enqueue_script('jquery144', THEME_JS . '/jquery-1.4.4.min.js');
		
	  
		
		wp_enqueue_script('cufon', THEME_JS . '/cufon-yui.js');
		wp_enqueue_script('handfont', THEME_JS . '/fonts/Pacifico.js');
		wp_enqueue_script('font', THEME_JS . '/fonts/'.get_option('main_font').'.js');
		
		wp_enqueue_script('global', THEME_JS . '/global.js');

        /*wp_enqueue_script('jquerycolor', THEME_JS . '/jquery.color.js');
		wp_enqueue_script('preloader', THEME_JS . '/jquery.preloader.js');
		wp_enqueue_script('menu', THEME_JS . '/menu.js');
		wp_enqueue_script('jquery-tools-tabs', THEME_JS .'/jquery.tools.tabs.min.js');
		wp_enqueue_script('jquery.colorbox-min', THEME_JS .'/jquery.colorbox-min.js');
		wp_enqueue_script('jquery.swfobject', THEME_JS .'/jquery.swfobject.1-1-1.min.js');
		wp_enqueue_script( 'jquery-slides', THEME_JS .'/slides.min.jquery.js');
		wp_enqueue_script('jquery-easing', THEME_JS . '/jquery.easing.1.3.js');*/
		
		wp_register_script( 'jquery-gmap', THEME_JS .'/jquery.gmap-1.1.0-min.js');
		wp_register_script( 'jquery-tools-validator', THEME_JS .'/jquery.tools.validator.min.js');
	    wp_register_script( 'jquery-tweet', THEME_JS .'/jquery.tweet.js');
		wp_register_script( 'jquery-quicksand', THEME_JS .'/jquery.quicksand.min.js');
		

		if( (is_front_page()) ){
			theme_functions('sliderHeader');
		}
		


	}
}
add_action('wp_print_scripts', 'theme_enqueue_scripts');


if(get_option('enable_gmap')=='on'){
	function theme_add_gmap_script(){
		echo "\n<script type='text/javascript' src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=".get_option('gmap_api_key')."'></script>\n";
		wp_print_scripts('jquery-gmap');
		
	}
	add_filter('wp_head','theme_add_gmap_script');
}


?>
