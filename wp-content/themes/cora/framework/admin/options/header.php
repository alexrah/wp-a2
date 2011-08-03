<?php

$options[] = array( "name" => "Header",
					"type" => "heading"); 
					

					
$options[] = array( "name" => "Logo",
					"desc" => "upload the logo",
					"id" => "logo_image",
					"std" => "",
					"type" => "upload_min");   
					
$options[] =array(
			"name" => "Logo width",
			"desc" => "the width of the logo in pixels",
			"id" => "logo_width",
			"min" => "1",
			"max" => "400",
			"step" => "1",
			"std" => "100",
			"type" => "range",
			'unit' => 'pixels'
		);
		
$options[] =array(
			"name" => "Logo left position",
			"desc" => "left coordinate of the logo",
			"id" => "logo_left",
			"min" => "1",
			"max" => "400",
			"step" => "1",
			"std" => "0",
			"type" => "range",
			'unit' => 'pixels'
		);
		
$options[] =array(
			"name" => "Logo top position",
			"desc" => "top coordinate of the logo",
			"id" => "logo_top",
			"min" => "-200",
			"max" => "200",
			"step" => "1",
			"std" => "0",
			"type" => "range",
			'unit' => 'pixels'
		);

	
			
					

					
  
					
					
?>