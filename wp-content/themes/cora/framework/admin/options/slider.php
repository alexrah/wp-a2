<?php

$options[] = array( "name" => "Slider",
					"type" => "heading"); 
				

					
$options[] = array(
			"name" => "Images Height",
			"desc" => "the height of the slider images.",
			"id" => "slider_height",
			"min" => "100",
			"max" => "400",
			"step" => "10",
			"unit" => 'px',
			"std" => "350",
			"type" => "range"
		);
		
$options[] =array(
			"name" => "Background Color Transitions",
			"desc" => "select on if you want background color transitions",
			"id" => "bg_color_transitions",
			"type" => "select",
			"std" => "on",
			"options" => array(
								  "on",
								  "off",
								  )
		);
					
$options[] = array( "name" => "<div style='color:#21759B; background:#efefef; font-size:19px;'>Nivo Slider Settings</div>",
					);
					
		
$options[] = array(
			"name" => "Transition Effect",
			"desc" => "the effect to use on the slideshow.",
			"id" => "nivo_effect",
			"std" => 'random',
			"options" => array(
				'random',
				'sliceDown',
				'sliceDownLeft',
				'sliceUp',
				'sliceUpLeft',
				'sliceUpDown',
				'sliceUpDownLeft',
				'fold',
				'fade'
			),
			"type" => "select",
		);
		
$options[] =array(
			"name" => "Number of Segments",
			"desc" => "the number of segments in which the image will be sliced.",
			"id" => "nivo_slices",
			"min" => "1",
			"max" => "40",
			"step" => "1",
			"std" => "10",
			"type" => "range",
			'unit' => 'segments'
		);
		
$options[] =array(
			"name" => "Animation Speed",
			"desc" => "the duration of the animation",
			"id" => "nivo_animSpeed",
			"min" => "200",
			"max" => "3000",
			"step" => "100",
			'unit' => 'miliseconds',
			"std" => "500",
			"type" => "range"
		);
		
$options[] =	array(
			"name" => "Autoplay Duration",
			"desc" => "the delay which each slide will have to wait to be played",
			"id" => "nivo_pauseTime",
			"min" => "1000",
			"max" => "20000",
			"step" => "500",
			"unit" => 'miliseconds',
			"std" => "3000",
			"type" => "range"
		);
		
$options[] =array(
			"name" => "Next & Prev Buttons",
			"desc" => "If you want to show Next & Prev Buttons on the slider, turn on the button.",
			"id" => "nivo_directionNav",
			"type" => "select",
			"std" => "true",
			"options" => array(
								  "true",
								  "false",
								  )
		);
		
	$options[] =	array(
			"name" => "Hide Next & Prev Buttons",
			"desc" => "If you want hide Next & Prev Buttons until hovering the slider, turn on the button.",
			"id" => "nivo_directionNavHide",
			"type" => "select",
			"std" => "true",
			"options" => array(
								  "true",
								  "false",
								  )
		);

		$options[] =array(
			"name" => "Keyboard Navigation",
			"desc" => "If you want Keyboard Navigation using left & right arrows, turn on the button.",
			"id" => "nivo_keyboardNav",
			"type" => "select",
			"std" => "true",
			"options" => array(
								  "true",
								  "false",
								  )
		);
		
		$options[] =array(
			"name" => "Pause On Hover",
			"desc" => "If you want to stop animation while hovering, turn on the button.",
			"id" => "nivo_pauseOnHover",
			"type" => "select",
			"std" => "true",
			"options" => array(
								  "true",
								  "false",
								  )
		);
		$options[] =array(
			"name" => "Manual Transitions",
			"desc" => "If you want to force manual transitions, turn on the button.",
			"id" => "nivo_manualAdvance",
			"type" => "select",
			"std" => "false",
			"options" => array(
								  "true",
								  "false",
								  )
		);

	
		
$options[] = array( "name" => "<span style='color:#21759B; background:#efefef; font-size:19px;'>Kwicks Slider Settings</span>",
					);
				
		
	$options[] = array(
			"name" => "Slider Number",
			"desc" => "Define the number of slides.",
			"id" => "kwicks_number",
			"min" => "2",
			"max" => "8",
			"step" => "1",
			"std" => "4",
			"type" => "range"
		);
	$options[] =	array(
			"name" => "Max width",
			"desc" => "Define the width of a fully expanded slider image.",
			"id" => "kwicks_max",
			"min" => "240",
			"max" => "960",
			"step" => "10",
			'unit' => 'px',
			"std" => "660",
			"type" => "range"
		);
	$options[] =	array(
			"name" => "Animation Speed",
			"desc" => "Define the duration of the animation.",
			"id" => "kwicks_duration",
			"min" => "0",
			"max" => "3000",
			"step" => "100",
			'unit' => 'milliseconds',
			"std" => "700",
			"type" => "range"
		);
		$options[] =array(
			"name" => "Easing Animations",
			"desc" => "Select which easing effect to use.",
			"id" => "kwicks_easing",
			"std" => 'linear',
			"options" => array(
				'linear',
				'swing',
				'easeInQuad',
				'easeOutQuad',
				'easeInOutQuad',
				'easeInCubic',
				'easeOutCubic',
				'easeInOutCubic',
				'easeInQuart',
				'easeOutQuart',
				'easeInOutQuart',
				'easeInQuint',
				'easeOutQuint',
				'easeInOutQuint',
				'easeInSine',
				'easeOutSine',
				'easeInOutSine',
				'easeInExpo',
				'easeOutExpo',
				'easeInOutExpo',
				'easeInCirc',
				'easeOutCirc',
				'easeInOutCirc',
				'easeInElastic',
				'easeOutElastic',
				'easeInOutElastic',
				'easeInBack',
				'easeOutBack',
				'easeInOutBack',
				'easeInBounce',
				'easeOutBounce',
				'easeInOutBounce'
			),
			"type" => "select",
		);

	
	$options[] =	array(
			"name" => "Caption Opacity",
			"desc" => "Define the Opacity of the caption. If you don't want captions set this to 0",
			"id" => "kwicks_caption_opacity",
			"min" => "0",
			"max" => "1",
			"step" => "0.1",
			"std" => "0.8",
			"type" => "range"
		);
	
$options[] = array( "name" => "<div style='color:#21759B; background:#efefef; font-size:19px;'>Piecemaker 2 Settings</div>",
					);		
					
$options[] =	array(
			"name" => "Animation Speed",
			"desc" => "the duration of the animation",
			"id" => "piecemaker_animation",
			"min" => "1",
			"max" => "8",
			"step" => "0.1",
			"unit" => 'seconds',
			"std" => "1",
			"type" => "range"
		);
		
$options[] =	array(
			"name" => "Autoplay Duration",
			"desc" => "the delay which each slide will have to wait to be played, set to 0 if you dont want autoplay",
			"id" => "piecemaker_autoplay",
			"min" => "0",
			"max" => "10",
			"step" => "1",
			"unit" => 'seconds',
			"std" => "2",
			"type" => "range"
		);
		
$options[] =	array(
			"name" => "Delay",
			"desc" => "the time between the animation of two consecutive pieces",
			"id" => "piecemaker_delay",
			"min" => "0",
			"max" => "1",
			"step" => "0.1",
			"unit" => 'seconds',
			"std" => "0.1",
			"type" => "range"
		);
		
$options[] = array( "name" => "<div style='color:#21759B; background:#efefef; font-size:19px;'>Full Width Slider Settings</div>",
					);		
					
$options[] =	array(
			"name" => "Autoplay Duration",
			"desc" => "the delay which each slide will have to wait to be played, set to 0 if you dont want autoplay",
			"id" => "full_width_animation",
			"min" => "1000",
			"max" => "8000",
			"step" => "100",
			"unit" => 'milliseconds',
			"std" => "6000",
			"type" => "range"
		);
		
$options[] =	array(
			"name" => "Images width",
			"desc" => "select the width of the images",
			"id" => "full_image_width",
			"min" => "960",
			"max" => "1920",
			"step" => "1",
			"unit" => 'px',
			"std" => "1600",
			"type" => "range"
		);
		
		

					
?>