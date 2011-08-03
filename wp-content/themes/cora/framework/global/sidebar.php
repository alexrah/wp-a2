<?php
class sidebar {
	var $sidebar_names = array();
	var $footer_sidebar_count = 0;
	var $footer_sidebar_names = array();
	
	function sidebar(){
		$this->sidebar_names = array(
			'home'=>'Homepage Widget Area',
			'page'=>'Page Widget Area',
			'blog'=>'Blog Widget Area',
			'portfolio' =>'Portfolio Widget Area',
		);
		$this->footer_sidebar_names = array(
			'First Footer Widget Area',
			'Second Footer Widget Area',
			'Third Footer Widget Area',
			'Fourth Footer Widget Area',
		);

	}

	function register_sidebar(){
		foreach ($this->sidebar_names as $name){
			register_sidebar(array(
				'name' => $name,
				'description' => $name,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widgettitle">',
				'after_title' => '</h3>',
			));
		}
		
		//register footer sidebars
		foreach ($this->footer_sidebar_names as $name){
			register_sidebar(array(
				'name' =>  $name,
				'description' => $name,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widgettitle">',
				'after_title' => '</h3>',
			));
		}
		//register custom sidebars
		$custom_sidebars = get_option('sidebars');
		if(!empty($custom_sidebars)){
			$custom_sidebar_names = explode(',',$custom_sidebars);
			foreach ($custom_sidebar_names as $name){
				register_sidebar(array(
					'name' =>  $name,
					'description' => $name,
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<h3 class="widgettitle">',
					'after_title' => '</h3>',
				));
			}
		}
		
	
	}
	
	function get_sidebar($post_id){
	    if(is_front_page()){
			$sidebar = $this->sidebar_names['home'];
		}
		elseif(is_page()){
			$sidebar = $this->sidebar_names['page'];
		}

		if(is_home()){
			$sidebar = $this->sidebar_names['blog'];
		}
		if(is_singular('post')){
			$sidebar = $this->sidebar_names['blog'];
			
		}elseif(is_singular('portfolio')){
			$sidebar = $this->sidebar_names['portfolio'];
		}
		if(is_search() || is_archive() || is_404()){
			$sidebar = $this->sidebar_names['blog'];
		}
		
		if(!empty($post_id)&& !is_front_page() && !is_home()){
			$custom = get_post_meta($post_id, 'custom_sidebar_value', true);
			if(!empty($custom)&&$custom!='off'){
				$sidebar = $custom;
			}
		}
		if(isset($sidebar)){
			dynamic_sidebar($sidebar);
		}
	}
	
	function get_footer_sidebar(){
		dynamic_sidebar($this->footer_sidebar_names[$this->footer_sidebar_count]);
		$this->footer_sidebar_count++;
	}
}
global $_sidebar;
$_sidebar = new sidebar;

add_action('widgets_init', array($_sidebar,'register_sidebar'));

function sidebar($function){
	global $_sidebar;
	$args = array_slice( func_get_args(), 1 );
	return call_user_func_array(array( &$_sidebar, $function ), $args );
}