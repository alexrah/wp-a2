<?php

					
$options[] = array( "name" => "Blog",
					"type" => "heading"); 
				
$options[] = array( "name" => "Blog type",
					"desc" => "which blog type to use: <br> type 1 (full width post images) <br> type 2 (small post images)",
					"id" => "blog_type",
					"type" => "select",
                    "default" => 'type 1',
					"options" => array(
								  "type 1",
								  "type 2",
								  )
					);
					
$options[] = array( "name" => "Blog Sidebar position",
					"desc" => "Sidebar position",
					"id" => "blog_layout",
					"std" => 'right sidebar',
					"type" => "select",
					"options" => array(
								  "left sidebar",
								  "right sidebar"
								  )
					);
					
$options[] =array(
			"name" => "Post Image Height",
			"desc" => "the height of the post thumbnail",
			"id" => "post_image_height",
			"min" => "100",
			"max" => "400",
			"step" => "1",
			"std" => "250",
			"type" => "range",
			'unit' => 'pixels'
		);
		
$options[] =array(
			"name" => "Display Share Icons",
			"desc" => "display social icons on every post",
			"id" => "share_this",
			"std" => "on",
			"type" => "select",
			"options" => array(
			  "on",
			  "off"
			  )
		);
		
$options[] =array(
			"name" => "Display About the Author box",
			"desc" => "display about the author box on every post",
			"id" => "about_author",
			"std" => "on",
			"type" => "select",
			"options" => array(
			  "on",
			  "off"
			  )
		);
		
$options[] =array(
			"name" => "Display Related and Popular Posts",
			"desc" => "display the related and popular posts on every post",
			"id" => "posts_box",
			"std" => "on",
			"type" => "select",
			"options" => array(
			  "on",
			  "off"
			  )
		);
		

					
?>