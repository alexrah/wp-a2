<?php

$options[] = array( "name" => "Portfolio",
					"type" => "heading"); 
					
$options[] = array( "name" => "<div style='color:#21759B; background:#efefef; font-size:19px;'>Thumbnail Height</div>",
					);
					
$options[] = array(
			"name" => "One column",
			"id" => "one_column_height",
			"min" => "1",
			"max" => "600",
			"step" => "1",
			"unit" => 'px',
			"std" => "350",
			"type" => "range"
		);
		
$options[] = array(
			"name" => "Two columns",
			"id" => "two_column_height",
			"min" => "1",
			"max" => "600",
			"step" => "1",
			"unit" => 'px',
			"std" => "200",
			"type" => "range"
		);
		
$options[] = array(
			"name" => "Three columns",
			"id" => "three_column_height",
			"min" => "1",
			"max" => "600",
			"step" => "1",
			"unit" => 'px',
			"std" => "150",
			"type" => "range"
		);
		
$options[] = array(
			"name" => "Four columns",
			"id" => "four_column_height",
			"min" => "1",
			"max" => "600",
			"step" => "1",
			"unit" => 'px',
			"std" => "120",
			"type" => "range"
		);

$options[] = array( "name" => "<div style='color:#21759B; background:#efefef; font-size:19px;'>General Settings</div>",
					);
					
$options[] = array(
			"name" => "Display title",
			"id" => "portfolio_title",
			"std" => "on",
			"options" => array(
			  "on",
			  "off"
			),
			"type" => "select");	
			
$options[] = array(
			"name" => "Display description",
			"id" => "portfolio_description",
			"std" => "on",
			"options" => array(
			  "on",
			  "off"
			),
			"type" => "select");	
				
$options[] = array(
			"name" => "Display more button",
			"id" => "portfolio_more",
			"std" => "on",
			"options" => array(
			  "on",
			  "off"
			),
			"type" => "select");	
			
$options[] = array(
			"name" => "Read more text",
			"id" => "portfolio_read_more",
			"std" => "Read More &raquo;",
			"type" => "text");
					
$options[] = array( "name" => "<div style='color:#21759B; background:#efefef; font-size:19px;'>Portfolio item Page Settings</div>",
					);
					
$options[] = array(
			"name" => "Portfolio Layout",
			"id" => "portfolio_layout",
			"std" => "right sidebar",
			"options" => array(
			  "right sidebar",
			  "left sidebar",
			  "full width"
			),
			"type" => "select");
			
$options[] = array(
			"name" => "Display Featured Image",
			"id" => "portfolio_featured_image",
			"std" => "on",
			"options" => array(
			  "on",
			  "off",
			),
			"type" => "select");

$options[] = array(
			"name" => "Featured Image Height",
			"id" => "portfolio_featured_image_height",
			"min" => "1",
			"max" => "600",
			"step" => "1",
			"unit" => 'px',
			"std" => "250",
			"type" => "range"
		);
		
$options[] = array(
			"name" => "Enable Comments",
			"id" => "portfolio_enable_comments",
			"std" => "off",
			"options" => array(
			  "off",
			  "on",
			),
			"type" => "select");
					

					
?>