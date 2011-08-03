<?php

function register_portfolio_post_type(){
	register_post_type('portfolio', array(
		'labels' => array(
			'name' => 'Portfolio Items',
			'singular_name' => 'Portfolio',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Portfolio',
			'edit_item' => 'Edit Portfolio',
			'new_item' => 'New Portfolio',
			'view_item' => 'View Portfolio',
			'search_items' => 'Search Portfolios',
			'not_found' =>  'No portfolios found',
			'not_found_in_trash' => 'No portfolios found in Trash', 
			'parent_item_colon' => '',
		),
		'singular_label' => 'portfolio',
		'public' => true,
		'exclude_from_search' => false,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array( 'with_front' => false ),
		'query_var' => false,
		'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'comments','custom-fields')
	));

	//register taxonomy for portfolio
	register_taxonomy('portfolio_category','portfolio',array(
		'hierarchical' => true,
		'labels' => array(
			'name' => 'Portfolio Categories', 
			'singular_name' => 'Portfolio Category',
			'search_items' =>  'Search Categories', 
			'popular_items' => 'Popular Categories', 
			'all_items' => 'All Categories', 
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => 'Edit Portfolio Category', 
			'update_item' => 'Update Portfolio Category', 
			'add_new_item' => 'Add New Portfolio Category', 
			'new_item_name' => 'New Portfolio Category Name', 
			'separate_items_with_commas' => 'Separate Portfolio category with commas',
			'add_or_remove_items' => 'Add or remove portfolio category',
			'choose_from_most_used' => 'Choose from the most used portfolio category', 
		),
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => false,
	));
}
add_action('init','register_portfolio_post_type');

function portfolio_context_fixer() {
	if ( get_query_var( 'post_type' ) == 'portfolio' ) {
		global $wp_query;
		$wp_query->is_home = false;
	}
	if ( get_query_var( 'taxonomy' ) == 'portfolio_category' ) {
		global $wp_query;
		$wp_query->is_404 = true;
		$wp_query->is_tax = false;
		$wp_query->is_archive = false;
	}
}
add_action( 'template_redirect', 'portfolio_context_fixer' );