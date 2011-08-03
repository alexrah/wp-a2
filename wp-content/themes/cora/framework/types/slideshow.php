<?php

add_action('init', 'slideshow_register');

function slideshow_register(){
	
		$args = array(
		'labels' => array(
		  'name' => 'Slider Images', 
		  'singular_name' => 'Slider Item',
		  'add_new' => 'Add New',
		  'add_new_item' => 'Add New Slider Item',
		  'edit_item' => 'Edit Slider Item',
		  'new_item' => 'New Slider Item',
		  'view_item' => 'View Slider Item', 
		  'search_items' => 'Search Slider Items', 
		  'not_found' =>  'No slider item found', 
		  'not_found_in_trash' => 'No slider items found in Trash', 
		  'parent_item_colon' => ''
		),
		'singular_label' => 'slideshow',
		'public' => true,
		'exclude_from_search' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => false,
		'query_var' => false,
		'supports' => array('title', 'thumbnail' , 'page-attributes','custom-fields')
		);
		
		
		register_post_type( 'slideshow' , $args );
		

	register_taxonomy('slideshow_set','slideshow',array(
		'hierarchical' => true,
		'labels' => array(
			'name' => 'Slideshow Sets', 
			'singular_name' => 'Slideshow Set',
			'search_items' =>  'Search Set', 
			'popular_items' => 'Popular Set', 
			'all_items' => 'All Sets', 
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => 'Edit Slideshow Set', 
			'update_item' => 'Update Slideshow Set', 
			'add_new_item' => 'Add New Slideshow Set', 
			'new_item_name' => 'New Slideshow Set Name', 
			'separate_items_with_commas' => 'Separate Slideshow set with commas',
			'add_or_remove_items' => 'Add or remove Slideshow Set',
			'choose_from_most_used' => 'Choose from the most used Slideshow Sets', 
		),
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => false,
		'public' => true,

	));
		

}

add_filter('manage_posts_columns', 'slider_columns');
function slider_columns($defaults) {
    $defaults['Thumbnail'] = 'Thumbnail';
    return $defaults;
}

add_action('manage_posts_custom_column', 'slider_custom_column', 10, 2);

function slider_custom_column($column_name, $id) {
    if( $column_name == 'Thumbnail' ) {
		
        the_post_thumbnail( array(100,100));
        
    }
	

	
}



?>