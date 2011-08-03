<?php

$options[] = array( "name" => "Footer",
					"type" => "heading"); 
				

	$url =  get_stylesheet_directory_uri() . '/framework/admin/images/footer_layout/';
$options[] = array( "name" => "Footer Layout",
					"desc" => "select the footer layout, then you can go to the Widgets area and add widgets",
					"id" => "footer_layout",
					"std" => "5",
					"type" => "images",
					"options" => array(
						'1' => $url . '1.jpg',
						'2' => $url . '2.jpg',
						'3' => $url . '3.jpg',
						'4' => $url . '4.jpg',
						'5' => $url . '5.jpg',
						'6' => $url . '6.jpg'
						));
						
$options[] = array( "name" => "Display SubFooter",
					"desc" => "select off if you don't want to display the subfooter",
					"id" => "subfooter",
					"std" => "on",
					"type" => "select",
					"options" => array(
						'on',
						'off'
						));
						
$options[] = array( "name" => "Subfooter Copyright text",
					"desc" => "the text that will be displayed in subfooter",
					"id" => "subfooter_text",
					"std" => "Copyright 2011",
					"type" => "text");
		


  
					
					
?>