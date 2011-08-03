<?php

$options[] = array(
		"name" => "Sidebar",
		"type" => "heading"
	);
    

$options[] =		array(
			"name" => "Generate Sidebar",
			"desc" => "Enter the name of the sidebar you'd like to create.<br>It must start with a letter, followed by letters, numbers, spaces, or underscores. Click the create button, then click the Save All Changes button at the bottom of the page",
			"id" => "sidebars",
			"function" => "theme_add_sidebar_option",
			"default" => "",
			"type" => "custom_sidebar"
		);



?>