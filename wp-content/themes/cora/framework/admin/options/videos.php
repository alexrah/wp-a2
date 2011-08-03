<?php

$options[] = array( "name" => "Videos",
					"type" => "heading"); 
					
$options[] = array( "name" => "<div style='color:#21759B; background:#efefef; font-size:19px;'>Video sizes</div>",
					);
					
$options[] = array(
			"name" => "Width",
			"id" => "video_width",
			"min" => "1",
			"max" => "960",
			"step" => "1",
			"unit" => 'px',
			"std" => "600",
			"type" => "range"
		);
		
$options[] = array(
			"name" => "Height",
			"id" => "video_height",
			"min" => "1",
			"max" => "600",
			"step" => "1",
			"unit" => 'px',
			"std" => "350",
			"type" => "range"
		);
					
				
?>