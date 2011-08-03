<?php
 
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];
require_once( $path_to_wp.'/wp-load.php' );




	Header("Content-type: text/css");
	
	$color_scheme=get_option('color_scheme');
	
	$logo_left=get_option('logo_left');
	$logo_top=get_option('logo_top');
	
	$custom_css=get_option('custom_css');
	

	echo <<<CSS
	
#logo{
	left:{$logo_left}px;
	top:{$logo_top}px;
	
}


#nav li span, .subheading span, .blogcol li .more span, .pagination a:hover, #about_the_author .about_heading span, #handwritten1 span, .handwritten3 span, #handwritten2 span, .blogpost .more:hover{
	background-color:{$color_scheme};
	
}

#subfooterwrap{

border-bottom:4px solid {$color_scheme};

}

#crumbs a:hover, #subfooter a:hover, a:hover, .bold_text span, .widget_twitter .tweet_list a, .comment_content .comment_meta .comment_author, .comment_content .comment_meta .comment_author a, #about_the_author .about_heading, #handwritten1, .handwritten3, #handwritten2 {
    color:{$color_scheme};
}

input.text_input:focus,input.text_input:hover,textarea.textarea:hover,textarea.textarea:focus {
	
	-webkit-box-shadow: 0 0px 3px {$color_scheme};
	-moz-box-shadow: 0 0px 3px {$color_scheme};
	box-shadow: 0 0px 3px {$color_scheme};
		
}

{$custom_css}


CSS;
?>