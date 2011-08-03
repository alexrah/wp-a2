<?php

$options[] = array( "name" => "General",
					"type" => "heading");
					
$options[] = array( "name" => "Color Scheme",
					"desc" => "this is the predominant color",
					"id" => "color_scheme",
					"std" => "#0477d9",
					"type" => "color");
					
					
$options[] = array( "name" => "Custom Favicon",
					"desc" => "Upload a 16px x 16px Png/Gif image that will represent your website's favicon.",
					"id" => "custom_favicon",
					"std" => "",
					"type" => "upload");  
				
$options[] =	array(
			"name" => "Enable Google Map",
			"desc" => "If you want to use the google map shortcode, select on. It will load the require scripts for integrating google map support.",
			"id" => "enable_gmap",
			"std" => 'off',
			"type" => "select",
			"options" => array(
			  "on",
			  "off",
			  )
		);
$options[] =	array(
			"name" => "Google Maps API Key",
			"desc" => "Paste here your Google Maps API Key. If you don't have one, please sign up for a <a href='http://code.google.com/intl/en-US/apis/maps/signup.html'>Google Maps API key</a>.",
			"id" => "gmap_api_key",
			"default" => "",
			"type" => "textarea"
		);		
		
$options[] = array( "name" => "Tracking Code",
					"desc" => "Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.",
					"id" => "google_analytics",
					"std" => "",
					"type" => "textarea");  
					
$options[] = array( "name" => "Display heading/search bar area",
					"desc" => "if you don't want to display the heading and the search bar select off",
					"id" => "header_search",
					"std" => "on",
					"options" => array(
					          "on",
							  "off"
					),
					"type" => "select");  
					
$options[] = array( "name" => "Display breadcrumbs",
					"desc" => "if you don't want to display breadcrumbs select off",
					"id" => "display_crumbs",
					"std" => "on",
					"options" => array(
					          "on",
							  "off"
					),
					"type" => "select");  
					
$options[] = array( "name" => "Custom CSS",
					"desc" => "if you want custom styling type the code in here",
					"id" => "custom_css",
					"std" => "",
					"type" => "textarea"); 
					
$options[] = array( "name" => "Main Font",
					"desc" => "select the main font of the theme",
					"id" => "main_font",
					"std" => "Nevis",
					"options" => array(
					          "Nevis",
							  "Museo",
							  "PT_Sans",
							  "Cabin"
					),
					"type" => "select");  


	
			
					

					
  
					
					
?>