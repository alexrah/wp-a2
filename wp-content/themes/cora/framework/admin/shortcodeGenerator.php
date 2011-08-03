<?php

global $options_shortcode;



$options_shortcode = array();

$options_shortcode = array(	
     array(
		"name" => "Columns",
		"value" => "columns",
		"options" => array(
			array(
				"name" => "Type",
				"id" => "type",
				"options" => array(
					"one_half",
					"one_half_last",
					"one_third",
					"one_third_last", 
					"two_third",
					"two_third_last", 
					"one_fourth",
					"one_fourth_last", 
					"three_fourth",
					"three_fourth_last", 
					"one_fifth", 
					"one_fifth_last", 
					"two_fifth", 
					"two_fifth_last", 
					"three_fifth", 
					"three_fifth_last", 		
					"four_fifth",
					"four_fifth_last",
					"one_sixth",
					"one_sixth_last", 
					"five_sixth",
					"five_sixth_last",
				),
				"type" => "select",
			),
			array(
				"name" => "Content",
				"id" => "content",
				"default" => "",
				"type" => "textarea"
			),
		),
	),
    
	array(
		"name" => "Layouts",
		"value" => "layouts",
		"sub" => true,
		"options" => array(
			array(
				"name" => "Two Column Layout",
				"value" => "one_half_layout",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'One_half'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_half_last'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "Three Column Layout",
				"value" => "one_third_layout",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'One_third'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_third'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_third_last'),
						"id" => "3",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "Four Column Layout",
				"value" => "one_fourth_layout",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'One_fourth'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_fourth'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_fourth'),
						"id" => "3",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_fourth_last'),
						"id" => "4",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "Five Column Layout",
				"value" => "one_fifth_layout",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'One_fifth'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_fifth'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_fifth'),
						"id" => "3",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_fifth'),
						"id" => "4",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_fifth_last'),
						"id" => "5",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "Six Column Layout",
				"value" => "one_sixth_layout",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'One_sixth'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_sixth'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_sixth'),
						"id" => "3",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_sixth'),
						"id" => "4",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_sixth'),
						"id" => "5",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_sixth_last'),
						"id" => "6",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "One Third - Two Third",
				"value" => "one_third_two_third",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'One_third'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'Two_third_last'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "Two Third - One Third",
				"value" => "two_third_one_third",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'Two_third'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_third_last'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "One Fourth - Three Fourth",
				"value" => "one_fourth_three_fourth",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'One_fourth'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'Three_fourth_last'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "Three Fourth - One Fourth",
				"value" => "three_fourth_one_fourth",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'Three_fourth'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_fourth_last'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "One Fourth - One Fourth - One Half",
				"value" => "one_fourth_one_fourth_one_half",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'One_fourth'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_fourth'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_half_last'),
						"id" => "3",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "One Fourth - One Half - One Fourth",
				"value" => "one_fourth_one_half_one_fourth",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'One_fourth'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_half'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_fourth_last'),
						"id" => "3",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "One Half - One Fourth - One Fourth",
				"value" => "one_half_one_fourth_one_fourth",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'One_half'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_fourth'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_fourth_last'),
						"id" => "3",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "Four Fifth - One Fifth",
				"value" => "four_fifth_one_fifth",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'Four_fifth'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_fifth_last'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "One Fifth - Four Fifth",
				"value" => "one_fifth_four_fifth",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'One_fifth'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'Four_Fifth_last'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "Two Fifth - Three Fifth",
				"value" => "two_fifth_three_fifth",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'Two_fifth'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'Three_Fifth_last'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "Three Fifth - Two Fifth",
				"value" => "three_fifth_two_fifth",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'Three_fifth'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'Two_Fifth_last'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "One Sixth - Five Sixth",
				"value" => "one_sixth_five_sixth",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'One_sixth'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'Five_sixth_last'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "Five Sixth - One Sixth",
				"value" => "five_sixth_one_sixth",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'Five_sixth'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_sixth_last'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "One Sixth - One Sixth - One Sixth - One Half",
				"value" => "one_sixth_one_sixth_one_sixth_one_half",
				"options" => array (
					array(
						"name" => sprintf("%s Content",'One_sixth'),
						"id" => "1",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_sixth'),
						"id" => "2",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_sixth'),
						"id" => "3",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" => sprintf("%s Content",'One_half_last'),
						"id" => "4",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
		),
	),
	
	array(
		"name" => "Typography",
		"value" => "typography",
		"sub" => true,
		"options" => array(
			array(
				"name" => sprintf("Drop Cap %s",1),
				"value" => "dropcap1",
				"options" => array (
					array(
						"name" => "Text",
						"id" => "text",
						"default" => "",
						"type" => "text",
					),
					array(
						"name" => "Color (optional)",
						"id" => "color",
						"default" => "",
						"prompt" => "Choose one..",
						"options" => array(
							"black",
							"gray",
							"red" ,
							"yellow",
							"blue" ,
							"pink",
							"green" ,
							"orange" ,
							"magenta" ,
						),
						"type" => "select",
					),
				)
			),
			array(
				"name" => sprintf("Drop Cap %s",2),
				"value" => "dropcap2",
				"options" => array (
					array(
						"name" => "Text",
						"id" => "text",
						"default" => "",
						"type" => "text",
					),
					array(
						"name" => "Color (optional)",
						"id" => "color",
						"default" => "",
						"prompt" => "Choose one..",
						"options" => array(
							"black" ,
							"gray",
							"red",
							"yellow" ,
							"blue" ,
							"pink",
							"green" ,
							"orange" ,
							"magenta",
						),
						"type" => "select",
					),
				)
			),
			array(
				"name" => sprintf("Drop Cap %s",3),
				"value" => "dropcap3",
				"options" => array (
					array(
						"name" => "Text",
						"id" => "text",
						"default" => "",
						"type" => "text",
					),
					array(
						"name" => "Color (optional)",
						"id" => "color",
						"default" => "",
						"prompt" => "Choose one..",
						"options" => array(
							"black" ,
							"gray",
							"red" ,
							"yellow" ,
							"blue" ,
							"pink" ,
							"green",
							"orange" ,
							"magenta" ,
						),
						"type" => "select",
					),
				)
			),
			array(
				"name" => "Block Quotes",
				"value" => "blockquote",
				"options" => array (
					array(
						"name" => "Align (optional)",
						"id" => "align",
						"default" => '',
						"prompt" => "Choose one..",
						"options" => array(
							"left" ,
							"right",
							"center"
						),
						"type" => "select",
					),
					array(
						"name" => "Cite (optional)",
						"id" => "cite",
						"default" => "",
						"type" => "text",
					),
					array(
						"name" => "Content",
						"id" => "content",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "Bold Text",
				"value" => "bold_text",
				"options" => array (
					array(
						"name" => "Content",
						"id" => "content",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "Pre & Code",
				"value" => "pre_code",
				"options" => array (
					array(
						"name" => "Type",
						"id" => "type",
						"default" => 'code',
						"options" => array(
							"pre" ,
							"code" ,
						),
						"type" => "select",
					),
					array(
						"name" => "Content",
						"id" => "content",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "Styled List",
				"value" => "styledlist",
				"options" => array (
					array(
						"name" => "Style",
						"id" => "style",
						"default" => 'list1',
						"options" => array(
							"list1",
							"list2",
							"list3",
							"list4",
							"list5",
							"list6",
							"list7",
							"list8",
							"list9",
							"list10",
						),
						"type" => "select",
					),
					array(
						"name" => "Color (optional)",
						"id" => "color",
						"default" => "",
						"prompt" => "Choose one..",
						"options" => array(
							"black",
							"gray",
							"red",
							"yellow",
							"blue",
							"pink",
							"green",
							"orange",
							"magenta",
						),
						"type" => "select",
					),
					array(
						"name" => "Content",
						"id" => "content",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "Highlight",
				"value" => "highlight",
				"options" => array (
					array(
						"name" => "Color (optional)",
						"id" => "color",
						"default" => '',
						"prompt" => "Choose one..",
						"options" => array(
							"black",
							"gray",
							"red",
							"yellow",
							"blue",
							"pink",
							"green",
							"orange",
							"magenta",
						),
						"type" => "select",
					),
					array(
						"name" => "Content",
						"id" => "content",
						"default" => "",
						"type" => "textarea"
					),
				)
				
			)
		),
	),
	
	array(
		"name" => "Buttons",
		"value" => "buttons",
		"options" => array(
			array(
				"name" => "Size",
				"id" => "size",
				"default" => 'medium',
				"options" => array(
					"small" ,
					"medium",
					"large",
				),
				"type" => "select",
			),
			array(
				"name" => "Align (optional)",
				"id" => "align",
				"default" => '',
				"prompt" => "Choose one..",
				"options" => array(
				    "no",
					"left",
					"right",
					"center",
				),
				"type" => "select",
			),
			array (
				"name" => "Full Width",
				"id" => "full",
				"default" => false,
				"type" => "select",
				"options" => array(
				   "off",
				   "on")
			),
			array(
				"name" => "Link (optional)",
				"id" => "link",
				"default" => "",
				"type" => "text",
			),
			array(
				"name" => "Link Target (optional)",
				"id" => "linkTarget",
				"default" => '',
				"prompt" => "Choose one..",
				"options" => array(
				    "no",
					"_blank",
					"_self" 
				),
				"type" => "select",
			),
			array(
				"name" => "Background Color (optional)",
				"id" => "bgColor",
				"default" => "",
				"type" => "color"
			),
			array(
				"name" => "Text Color (optional)",
				"id" => "textColor",
				"default" => "",
				"type" => "color"
			),
			array(
				"name" => "Text",
				"id" => "text",
				"default" => "",
				"type" => "text",
			),
		),
	),
	
	array(
		"name" => "Styled Boxes",
		"value" => "styledboxes",
		"sub" => true,
		"options" => array(
			array(
				"name" => "Message Boxes",
				"value" => "messageboxes",
				"options" => array (
					array(
						"name" => "Type",
						"id" => "type",
						"default" => 'info',
						"options" => array(
							"info" ,
							"success" ,
							"error" ,
							"notice" ,
						),
						"type" => "select",
					),
					array(
						"name" => "Content",
						"id" => "content",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			array(
				"name" => "Framed Box",
				"value" => "framed_box",
				"options" => array (
					array(
						"name" => "Content",
						"id" => "content",
						"default" => "",
						"type" => "textarea"
					),
					array (
						"name" => "Width (optional)",
						"id" => "width",
						"default" => '0',
						"min" => 0,
						"max" => 960,
						"step" => "1",
						"type" => "range"
					),
					array (
						"name" => "Height (optional)",
						"id" => "height",
						"default" => '0',
						"min" => 0,
						"max" => 960,
						"step" => "1",
						"type" => "range"
					),
					array(
						"name" => "Background Color (optional)",
						"id" => "bgColor",
						"default" => "",
						"type" => "color"
					),
					array(
						"name" => "Text Color (optional)",
						"id" => "textColor",
						"default" => "",
						"type" => "color"
					),
					array (
						"name" => "Rounded",
						"id" => "rounded",
						"default" => false,
						"options" => array(
							"no" ,
							"yes" 
						),
						"type" => "select",
					),
				)
			),
			array(
				"name" => "Note Box",
				"value" => "note_box",
				"options" => array (
					array(
						"name" => "Title (optional)",
						"id" => "title",
						"default" => "",
						"type" => "text"
					),
					array(
						"name" => "Content",
						"id" => "content",
						"default" => "",
						"type" => "textarea"
					),
					array(
						"name" =>  "Align (optional)",
						"id" => "align",
						"default" => '',
						"prompt" => "Choose one..",
						"options" => array(
							"left" => 'left',
							"right" => 'right',
							"center" => 'center',
						),
						"type" => "select",
					),
					array (
						"name" => "Width (optional)",
						"id" => "width",
						"default" => '0',
						"min" => 0,
						"max" => 960,
						"step" => "1",
						"type" => "range"
					),
				)
			),
		),
	),
	
	array(
		"name" => "Tables",
		"value" => "tables",
		"options" => array(
			array(
				"name" => "Content",
				"id" => "content",
				"default" => "",
				"type" => "textarea"
			),
		),
	),
	
	
	array(
		"name" => "Tabs",
		"value" => "tabs",
		"options" => array(
			array(
				"name" => "Number of tabs",
				"id" => "number",
				"min" => "1",
				"max" => "8",
				"step" => "1",
				"default" => "2",
				"type" => "range"
			),
			array(
				"name" => sprintf("Tab %d Title",1),
				"id" => "title_1",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => sprintf("Tab %d Content",1),
				"id" => "content_1",
				"default" => "",
				"type" => "textarea"
			),
			array(
				"name" =>  sprintf("Tab %d Title",2),
				"id" => "title_2",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => sprintf("Tab %d Content",2),
				"id" => "content_2",
				"default" => "",
				"type" => "textarea"
			),
			array(
				"name" =>  sprintf("Tab %d Title",3),
				"id" => "title_3",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => sprintf("Tab %d Content",3),
				"id" => "content_3",
				"default" => "",
				"type" => "textarea"
			),
			array(
				"name" =>  sprintf("Tab %d Title",4),
				"id" => "title_4",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => sprintf("Tab %d Content",4),
				"id" => "content_4",
				"default" => "",
				"type" => "textarea"
			),
			array(
				"name" =>  sprintf("Tab %d Title",5),
				"id" => "title_5",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => sprintf("Tab %d Content",5),
				"id" => "content_5",
				"default" => "",
				"type" => "textarea"
			),
			array(
				"name" => sprintf("Tab %d Title",6),
				"id" => "title_6",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => sprintf("Tab %d Content",6),
				"id" => "content_6",
				"default" => "",
				"type" => "textarea"
			),
			array(
				"name" =>  sprintf("Tab %d Title",7),
				"id" => "title_7",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => sprintf("Tab %d Content",7),
				"id" => "content_7",
				"default" => "",
				"type" => "textarea"
			),
			array(
				"name" =>  sprintf("Tab %d Title",8),
				"id" => "title_8",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => sprintf("Tab %d Content",8),
				"id" => "content_8",
				"default" => "",
				"type" => "textarea"
			),
		),
	),
	
	array(
		"name" => "Accordion",
		"value" => "accordion",
		"options" => array(
			array(
				"name" => "Number of pans",
				"id" => "number",
				"min" => "1",
				"max" => "8",
				"step" => "1",
				"default" => "2",
				"type" => "range"
			),
			array(
				"name" => sprintf("Accordion %d Title",1),
				"id" => "title_1",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => sprintf("Accordion %d Content",1),
				"id" => "content_1",
				"default" => "",
				"type" => "textarea"
			),
			array(
				"name" => sprintf("Accordion %d Title",2),
				"id" => "title_2",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => sprintf("Accordion %d Content",2),
				"id" => "content_2",
				"default" => "",
				"type" => "textarea"
			),
			array(
				"name" => sprintf("Accordion %d Title",3),
				"id" => "title_3",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => sprintf("Accordion %d Content",3),
				"id" => "content_3",
				"default" => "",
				"type" => "textarea"
			),
			array(
				"name" => sprintf("Accordion %d Title",4),
				"id" => "title_4",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => sprintf("Accordion %d Content",4),
				"id" => "content_4",
				"default" => "",
				"type" => "textarea"
			),
			array(
				"name" => sprintf("Accordion %d Title",5),
				"id" => "title_5",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => sprintf("Accordion %d Content",5),
				"id" => "content_5",
				"default" => "",
				"type" => "textarea"
			),
			array(
				"name" => sprintf("Accordion %d Title",6),
				"id" => "title_6",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => sprintf("Accordion %d Content",6),
				"id" => "content_6",
				"default" => "",
				"type" => "textarea"
			),
			array(
				"name" => sprintf("Accordion %d Title",7),
				"id" => "title_7",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => sprintf("Accordion %d Content",7),
				"id" => "content_7",
				"default" => "",
				"type" => "textarea"
			),
			array(
				"name" => sprintf("Accordion %d Title",8),
				"id" => "title_8",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => sprintf("Accordion %d Content",8),
				"id" => "content_8",
				"default" => "",
				"type" => "textarea"
			),
		),
	),
	
	array(
		"name" => "Toggle",
		"value" => "toggle",
		"options" => array(
			array(
				"name" => "Title",
				"id" => "title",
				"default" => "",
				"type" => "text"
			),
			array(
				"name" => "Content",
				"id" => "content",
				"default" => "",
				"type" => "textarea"
			),
		),
	),
	
	array(
		"name" => "Dividers",
		"value" => "divider",
		"options" => array(
			array(
				"name" => "Type",
				"id" => "type",
				"options" => array(
				    "divider" ,
					"divider_line" ,
					"divider_dashed" ,
					"divider_with_top_link" ,
					"padding",
					"clearboth",
				),
				"type" => "select",
			),
		),
	),
	
	array(
		"name" => "Images",
		"value" => "images",
		"options" => array(
			
					array(
						"name" => "Image Source Url",
						"id" => "src",
						"size" => 30,
						"default" => "",
						"type" => "text",
					),
					array(
						"name" => "Image Title (optional)",
						"id" => "title",
						"size" => 30,
						"default" => "",
						"type" => "text",
					),
					array(
						"name" => "Align (optional)",
						"id" => "align",
						"default" => '',
						"options" => array(
							"left" ,
							"right" ,
							"center",
						),
						"type" => "select",
					),
					array(
						"name" => "Icon (optional)",
						"id" => "icon",
						"default" => '',
						"options" => array(
						    "no" ,
							"zoom" ,
							"play",
							"doc",
						),
						"type" => "select",
					),
					array(
						"name" => "Caption (optional)",
						"id" => "caption",
						"type" => "text",
					),
					array (
						"name" => "Lightbox",
						"id" => "lightbox",
						"default" => false,
						"options" => array(
						  "no",
						  "yes"
						),
						"type" => "select"
					),
					array (
						"name" => "Lightbox group (optional)",
						"id" => "group",
						"default" => '',
						"type" => "text"
					),
					array(
						"name" => "Size (optional)",
						"id" => "size",
						"default" => '',
						"options" => array(
						    "Choose one",
							"small",
							"medium",
							"large",
						),
						"type" => "select",
					),
					array (
						"name" => "Width (optional)",
						"id" => "width",
						"default" => 0,
						"min" => 0,
						"max" => 960,
						"step" => "1",
						"type" => "range"
					),
					array (
						"name" => "Height (optional)",
						"id" => "height",
						"default" => 0,
						"min" => 0,
						"max" => 960,
						"step" => "1",
						"type" => "range"
					),
					array(
						"name" => "Link (optional)",
						"size" => 30,
						"id" => "link",
						"default" => "",
						"type" => "text",
					),
				
			
		),
	),
	
	array(
		"name" => "Video",
		"value" => "video",
		"sub" => true,
		"options" => array(
			array(
				"name" => "Flash",
				"value" => "flash",
				"options" => array(
					array(
						"name" => "Src",
						"desc" => 'Specifies the location (URL) of the movie to be loaded',
						"id" => "src",
						"size" => 30,
						"default" => "",
						"type" => "text",
					),
					array (
						"name" => "Width (optional)",
						"id" => "width",
						"default" => 0,
						"min" => 0,
						"max" => 960,
						"step" => "1",
						"type" => "range"
					),
					array (
						"name" => "Height (optional)",
						"id" => "height",
						"default" => 0,
						"min" => 0,
						"max" => 960,
						"step" => "1",
						"type" => "range"
					),
				),
			),
			array(
				"name" => "YouTube",
				"value" => "youtube",
				"options" => array(
					array(
						"name" => "Clip_id",
						"desc" => "the id from the clip's URL after v= (e.g. http://www.youtube.com/watch?v=<span style='color:red'>2DclLrdaxQd</span>)",
						"id" => "clip_id",
						"size" => 30,
						"default" => "",
						"type" => "text",
					),
					array (
						"name" => "Width (optional)",
						"id" => "width",
						"default" => 0,
						"min" => 0,
						"max" => 960,
						"step" => "1",
						"type" => "range"
					),
					array (
						"name" => "Height (optional)",
						"id" => "height",
						"default" => 0,
						"min" => 0,
						"max" => 960,
						"step" => "1",
						"type" => "range"
					),
				),
			),
			array(
				"name" => "Vimeo",
				"value" => "vimeo",
				"options" => array(
					array(
						"name" => "Clip_id",
						"desc" => "the number from the clip's URL (e.g. http://vimeo.com/<span style='color:red'>123456</span>)",
						"id" => "clip_id",
						"size" => 30,
						"default" => "",
						"type" => "text",
					),
					array (
						"name" => "Width (optional)",
						"id" => "width",
						"default" => 0,
						"min" => 0,
						"max" => 960,
						"step" => "1",
						"type" => "range"
					),
					array (
						"name" => "Height (optional)",
						"id" => "height",
						"default" => 0,
						"min" => 0,
						"max" => 960,
						"step" => "1",
						"type" => "range"
					),
				),
			),
			array(
				"name" => "Dailymotion",
				"value" => "dailymotion",
				"options" => array(
					array(
						"name" => "Clip_id",
						"desc" => "the id from the clip's URL (e.g. http://www.dailymotion.com/video/<span style='color:red'>xf3fk2</span>_didacticiel-quicklist_tech)",
						"id" => "clip_id",
						"size" => 30,
						"default" => "",
						"type" => "text",
					),
					array (
						"name" => "Width (optional)",
						"id" => "width",
						"default" => 0,
						"min" => 0,
						"max" => 960,
						"step" => "1",
						"type" => "range"
					),
					array (
						"name" => "Height (optional)",
						"id" => "height",
						"default" => 0,
						"min" => 0,
						"max" => 960,
						"step" => "1",
						"type" => "range"
					),
				),
			),
		),
	),
	
	array(
		"name" => "Widget",
		"value" => "widget",
		"sub" => true,
		"options" => array(
		     array(
				  "name" => "Google Map",
				  "value" => "gmap",
				  "options" => array(
					  array (
						  "name" => "Width (optional) ",
						  "id" => "width",
						  "default" => 0,
						  "min" => 0,
						  "max" => 960,
						  "step" => "1",
						  "type" => "range"
					  ),
					  array (
						  "name" => "Height",
						  "id" => "height",
						  "default" => '400',
						  "min" => 0,
						  "max" => 960,
						  "step" => "1",
						  "type" => "range"
					  ),
					  array(
						  "name" => "Latitude",
						  "id" => "latitude",
						  "size" => 30,
						  "default" => "",
						  "type" => "text",
					  ),
					  array(
						  "name" => "Longitude",
						  "id" => "longitude",
						  "size" => 30,
						  "default" => "",
						  "type" => "text",
					  ),
					  array (
						  "name" => "Zoom",
						  "id" => "zoom",
						  "default" => '14',
						  "min" => 1,
						  "max" => 19,
						  "step" => "1",
						  "type" => "range"
					  ),
					  array (
						  "name" => "Marker",
						  "id" => "marker",
						  "default" => true,
						  "options" => array(
						    "on",
							"off"
						  ),
						  "type" => "select"
					  ),
					  array (
						  "name" => "Popup Marker",
						  "id" => "popup",
						  "default" => false,
						  "options" => array(
						    "off",
							"on"
						  ),
						  "type" => "select"
					  ),
					  array (
						  "name" => "Html",
						  "id" => "html",
						  "size" => 30,
						  "default" => "",
						  "type" => "text",
					  ),
					  array(
						  "name" => "Maptype (optional)",
						  "id" => "maptype",
						  "options" => array(
							  "G_NORMAL_MAP" ,
							  "G_SATELLITE_MAP",
							  "G_HYBRID_MAP",
							  "G_DEFAULT_MAP_TYPES",
							  "G_PHYSICAL_MAP",
						  ),
						  "type" => "select",
					  ),
		           ),
				   
	           ),
			   
			   array(
				"name" => "Contact Details",
				"value" => "contact_details",
				"options" => array (
					array(
						"name" => "Color (optional)",
						"id" => "color",
						"default" => "",
						"options" => array(
						    "gray",
							"black",
							"red",
							"yellow",
							"blue",
							"pink",
							"green",
							"orange",
							"magenta",
						),
						"type" => "select",
					),
					array(
						"name" => "Phone",
						"id" => "phone",
						"default" => "",
						"size" => 30,
						"type" => "text"
					),
					array(
						"name" => "Cell Phone",
						"id" => "cellphone",
						"default" => "",
						"size" => 30,
						"type" => "text"
					),
					array(
						"name" => "Email",
						"id" => "email",
						"default" => "",
						"type" => "text"
					),
					array(
						"name" => "Address",
						"id" => "address",
						"default" => "",
						"size" => 30,
						"type" => "text"
					),
					array(
						"name" => "City",
						"id" => "city",
						"default" => "",
						"size" => 30,
						"type" => "text"
					),
					array(
						"name" => "State",
						"id" => "state",
						"default" => "",
						"size" => 30,
						"type" => "text"
					),
					array(
						"name" => "Zip",
						"id" => "zip",
						"default" => "",
						"size" => 30,
						"type" => "text"
					),
					array(
						"name" => "Name",
						"id" => "name",
						"default" => "",
						"size" => 30,
						"type" => "text"
					),
				),				
			),
			
			
			array(
				"name" => "Contact Form",
				"value" => "contactform",
				"options" => array (
					array(
						"name" => "email",
						"id" => "email",
						"default" => "",
						"type" => "text"
					),
					array(
						"name" => "Success Text",
						"id" => "content",
						"default" => "",
						"type" => "textarea"
					),
				)
			),
			
			array(
				"name" => "Twitter",
				"value" => "twitter",
				"options" => array (
					array(
						"name" => "Username",
						"id" => "username",
						"default" => "",
						"type" => "text"
					),
					array(
						"name" => "Count",
						"id" => "count",
						"default" => "",
						"type" => "text"
					)
				)
			),
			
			array(
				"name" => "Flickr",
				"value" => "flickr",
				"options" => array (
					array(
						"name" => "Flickr id (<a href='http://idgettr.com/' target='_blank'>idGettr</a>)",
						"id" => "id",
						"default" => "",
						"type" => "text"
					),
					array(
						"name" => "Count",
						"desc" => "",
						"id" => "count",
						"default" => '4',
						"min" => 0,
						"max" => 20,
						"step" => "1",
						"type" => "range"
					),
					array(
						"name" => "Display",
						"id" => "display",
						"options" => array(
							"latest",
							"random",
						),
						"type" => "select",
					),
				)
			),
			
			array(
				"name" => "Popular Posts",
				"value" => "popular_posts",
				"options" => array (
					array(
						"name" => "Count",
						"desc" => "",
						"id" => "count",
						"default" => '4',
						"min" => 0,
						"max" => 20,
						"step" => "1",
						"type" => "range"
					),
					array(
						"name" => "Category (optional)",
						"id" => "cat",
						"options" => $of_categories,
						"type" => "multiselect",
					),
				)
			),
			
			array(
				"name" => "Recent Posts",
				"value" => "recent_posts",
				"options" => array (
					array(
						"name" => "Count",
						"desc" => "",
						"id" => "count",
						"std" => '4',
						"min" => 0,
						"max" => 20,
						"step" => "1",
						"type" => "range"
					),
					array(
						"name" => "Category (optional)",
						"id" => "cat",
						"type" => "multiselect",
					),
				)
			),
		
		),
		
	),
	
	
	array(
		"name" => "Portfolio",
		"value" => "portfolio",
		"options" => array(
			array(
				"name" => "Columns",
				"id" => "column",
				"default" => '4',
				"options" => array(
					"2 columns",
					"3 columns",
					"4 columns",
				),
				"type" => "select",
			),
			array(
				"name" => "No Paging",
				"id" => "nopaging",
				"desc" => "If the option is on, it will disable pagination, displaying all portfolio items",
				"options" => array(
				  "off",
				  "on"
				),
				"type" => "select"
			),
			array(
				"name" => "Items number per page",
				"id" => "max",
				"std" => '8',
				"min" => 0,
				"max" => 50,
				"step" => "1",
				"type" => "range"
			),
			array(
				"name" => "Sortable",
				"id" => "sortable",
				"desc" => "If the option is on, it will disable pagination, displaying all items using filters.",
				"default" => false,
				"options" => array(
				  "off",
				  "on"
				),
				"type" => "select"
			),
			array(
				"name" => "jQuery",
				"id" => "jquery",
				"default" => false,
				"options" => array(
				  "off",
				  "on"
				),
				"type" => "select"
			),
			array(
				"name" => "Category",
				"id" => "cat",
				"default" => array(),
				"target" => '',
				"type" => "multiselect_portfolio",
			),
		),
		
		
		
		
		
	),
	
	
	array(
		"name" => "Blog",
		"value" => "blog",
		"options" => array(
			array(
				"name" => "number of posts",
				"id" => "count",
				"std" => '8',
				"min" => 0,
				"max" => 50,
				"step" => "1",
				"type" => "range"
			),
			array(
				"name" => "Category",
				"id" => "cat",
				"type" => "multiselect",
			),
		),
		
		
		
		
		
	),
	
	
	
);				


					
					
function create_js_shortcode(){
	global $options_shortcode;
	
    ?>
    
    <script type="text/javascript" language="javascript">
		jQuery(document).ready(function(){
			
			
			//Color Picker
			<?php 
			
			foreach($options_shortcode as $shortcode){
	
	          if(!isset($shortcode['sub']))
				foreach($shortcode['options'] as $option)  

			       if($option['type'] == 'color'):
					$option_id = $option['id']='sc_'.$shortcode['value'].'_'.$option['id'];
					$color = get_option($option_id);
					

				?>
    
				 jQuery('#<?php echo $option_id; ?>_picker').ColorPicker({
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#<?php echo $option_id; ?>_picker').children('div').css('backgroundColor', '#' + hex);
						jQuery('#<?php echo $option_id; ?>_picker').next('input').attr('value','#' + hex);
						
					}
				  });
				  
			  <?php endif; 
			  
			  if(isset($shortcode['sub']))
			     foreach($shortcode['options'] as $sub_shortcode)  
			            foreach($sub_shortcode['options'] as $option)
						
						////////
							if($option['type'] == 'color'):
							$option_id='sc_'.$shortcode['value'].'_'.$sub_shortcode['value'].'_'.$option['id'];
							$color = get_option($option_id);
							
							
							?>
							
							jQuery('#<?php echo $option_id; ?>_picker').ColorPicker({
							onShow: function (colpkr) {
							jQuery(colpkr).fadeIn(500);
							return false;
							},
							onHide: function (colpkr) {
							jQuery(colpkr).fadeOut(500);
							return false;
							},
							onChange: function (hsb, hex, rgb) {
							jQuery('#<?php echo $option_id; ?>_picker').children('div').css('backgroundColor', '#' + hex);
							jQuery('#<?php echo $option_id; ?>_picker').next('input').attr('value','#' + hex);
							
							}
							});
							
							<?php endif; 
						
						/////
			  
			  
			  }

			  
			  
			  ?>
			  
		 
		});
		
		</script>
    
    <?php
    
    
	
}




function meta_options_shortcode($options_shortcode) {
	
	create_js_shortcode();
	
	global $options_shortcode,$post,$page;
	
	//////////////
	

	
	
	echo '<div class="shortcode_selector"><table class="theme-options-table" cellspacing="0"><tbody><tr><th scope="row" style="text-align:right"><h4><label for="shortcode_selector">Shortcode</label></h4></th><td><select name="sc_selector">';
	echo '<option value="">Choose one...</option>';
	
	foreach($options_shortcode as $shortcode) {
			echo '<option value="'.$shortcode['value'].'">'.$shortcode['name'].'</option>';
	}
	
	echo '</select></td></tr></tbody></table></div>';
	
	foreach($options_shortcode as $shortcode) {
			echo '<div id="shortcode_'.$shortcode['value'].'" class="shortcode_wrap">';
			if(isset($shortcode['sub'])){
				echo '<div class="shortcode_sub_selector"><table cellspacing="0" class="theme-options-table"><tbody><th scope="row"><h4><label for="shortcode_selector">Type</label></h4></th><td><select name="sc_'.$shortcode['value'].'_selector">';
				echo '<option value="">Choose one...</option>';
				foreach($shortcode['options'] as $sub_shortcode) {
					echo '<option value="'.$sub_shortcode['value'].'">'.$sub_shortcode['name'].'</option>';
				}
				echo '</select></td></tr></tbody></table></div>';
				foreach($shortcode['options'] as $sub_shortcode) {
					echo '<div id="sub_shortcode_'.$sub_shortcode['value'].'" class="sub_shortcode_wrap"><table cellspacing="0" class="theme-options-table"><tbody>';
					foreach($sub_shortcode['options'] as $option){
						
					echo '<tr>';
					echo '<th scope="row"><h4>'.$option['name'].'</h4></th>';
					
					echo '<td>';
					$option['id']='sc_'.$shortcode['value'].'_'.$sub_shortcode['value'].'_'.$option['id'];
					
					 
					renderHTML($option);
					echo '</td>';
					echo '</tr>';
                      
					}
					echo '</tbody></table></div>';
				}
			}else{
				echo '<table cellspacing="0" class="theme-options-table"><tbody>';
				foreach($shortcode['options'] as $option){
					
					echo '<tr>';
					echo '<th scope="row"><h4>'.$option['name'].'</h4></th>';
					
					echo '<td>';
					$option['id']='sc_'.$shortcode['value'].'_'.$option['id'];
					
					 
					renderHTML($option);
					echo '</td>';
					echo '</tr>';
					
					
				}
				echo '</tbody></table>';
			}
			
			echo '</div>';
		}
		echo '<p style="text-indent:240px; margin-top:20px;"><input type="button" id="shortcode_send" class="button" value="Send Shortcode to Editor &raquo;"/></p>';

	     echo '<script>';	  
		 echo '$(":range").rangeinput();';
		 echo '</script>';
		

        
   
}

function create_meta_box_shortcode() {
	global $theme_name;
	if ( function_exists('add_meta_box') ) {
		
	add_meta_box( 'sg', 'Shortcode Generator', 'meta_options_shortcode', 'post', 'normal', 'high' );
	add_meta_box( 'sg', 'Shortcode Generator', 'meta_options_shortcode', 'page', 'normal', 'high' );
	add_meta_box( 'sg', 'Shortcode Generator', 'meta_options_shortcode', 'portfolio', 'normal', 'high' );
	}
}





function renderHTML($opt){
	
	    $output='';
	
		switch($opt['type']):
		case 'select':
		
		   

			$output .= '<select class="of-input" name="'. $opt['id'] .'" id="'. $opt['id'] .'">';
		
			$select_value = get_option($opt['id']);
			 
			foreach ($opt['options'] as $option) {
				
				$selected = '';
				
				 if($select_value != '') {
					 if ( $select_value == $option) { $selected = ' selected="selected"';} 
			     } else {
					 if ( isset($value['std']) )
						 if ($opt['std'] == $option) { $selected = ' selected="selected"'; }
				 }
				  
				 $output .= '<option'. $selected .'>';
				 $output .= $option;
				 $output .= '</option>';
			 
			 } 
			 $output .= '</select>';

			
		break;
		case 'select2':

			$output .= '<select class="of-input" name="'. $value['id'] .'" id="'. $value['id'] .'">';
		
			$select_value = get_option($value['id']);
			 
			foreach ($value['options'] as $option => $name) {
				
				$selected = '';
				
				 if($select_value != '') {
					 if ( $select_value == $option) { $selected = ' selected="selected"';} 
			     } else {
					 if ( isset($value['std']) )
						 if ($value['std'] == $option) { $selected = ' selected="selected"'; }
				 }
				  
				 $output .= '<option'. $selected .' value="'.$option.'">';
				 $output .= $name;
				 $output .= '</option>';
			 
			 } 
			 $output .= '</select>';

			
		break;
		case 'text':
			$val = $opt['std'];
			$std = get_option($opt['id']);
			if ( $std != "") { $val = $std; }
			$output .= '<input class="of-input" name="'. $opt['id'] .'" id="'. $opt['id'] .'" type="'. $opt['type'] .'" value="'. $meta_box_value .'" />';
			
		break;
		case 'textarea':
			
			$cols = '8';
			$ta_value = '';
			
			if(isset($opt['std'])) {
				
				$ta_value = $opt['std']; 
				
				if(isset($opt['options'])){
					$ta_options = $opt['options'];
					if(isset($ta_options['cols'])){
					$cols = $ta_options['cols'];
					} else { $cols = '8'; }
				}
				
			}
				$std = get_option($opt['id']);
				if( $std != "") { $ta_value = stripslashes( $std ); }
				$output .= '<textarea style="width:400px; height:100px;" class="of-input" name="'. $opt['id'] .'" id="'. $opt['id'] .'" cols="'. $cols .'" rows="8">'.$meta_box_value.'</textarea>';
			
			
		break;
		case "color":

			$output .= '<div id="' . $opt['id'] . '_picker" class="colorSelector"><div></div></div>';
			$output .= '<input class="of-color" name="'. $opt['id'] .'" id="'. $opt['id'] .'" type="text" />';
		break;   
		case "range":
			
			$output .= '<div class="range-input-wrap">';
			
			
			$output .= '<input style="display:inline; width:40px;" type="range" name="' . $opt['id'] . '" value="';
			
				$val = $opt['std'];
				$std = get_option($opt['id']);
				if ( $std != "") { $val = $std; }
			
			
			$output .= $val;
			
			if (isset($opt['min'])) {
				$output .= '" min="' . $opt['min'];
			}
			
			if (isset($opt['max'])) {
				$output .= '" max="' . $opt['max'];
			}
			if (isset($opt['step'])) {
				$output .= '" step="' . $opt['step'];
			}
			$output .= '" />';
			
			if (isset($opt['unit'])) {
				$output .= '<span>' . $opt['unit'] . '</span>';
			}
			$output .= '</div>';
			

			
			
	    break;
		case 'multiselect':
		   //Access the WordPress Categories via an Array
		   $of_categories_obj = get_categories('hide_empty=0');

		
		
		   $output .='<select style="height:5.5em;width:300px;" name="'.$opt['id'].'" id="'.$opt['id'].'" class="widefat" multiple="multiple">';
		   foreach ($of_categories_obj as $of_cat):
				 $output .= '<option'. $selected .' value="'.$of_cat->cat_ID.'">'.$of_cat->cat_name.'</option>';
		   endforeach;
		   $output .='</select>';
		break;
		
		case 'multiselect_portfolio':
		
		 $args = array(
		 "hide_empty" => 0,
         "taxonomy" => "portfolio_category");                    
		   //Access the WordPress Categories via an Array
		   $of_categories_obj = get_categories($args);
		   

		   $output .='<select style="height:5.5em;width:300px;" name="'.$opt['id'].'" id="'.$opt['id'].'" class="widefat" multiple="multiple">';
		   foreach ($of_categories_obj as $of_cat):
				 $output .= '<option'. $selected .' value="'.$of_cat->cat_name.'">'.$of_cat->cat_name.'</option>';
		   endforeach;
		   $output .='</select>';
		break;
	endswitch;
	
	echo $output;
}


add_action('admin_menu', 'create_meta_box_shortcode');




?>