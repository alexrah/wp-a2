<?php
function theme_shortcode_blog($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'cat' => '',
		'count' => 8,
	), $atts));

	
	$output='<div class="portfolio_list">';
	
	
	$query = array('post_type' => 'post','posts_per_page' => $count, 'orderby'=> 'date', 'post_status' => 'publish');
	if($cat){
		$query['cat'] = $cat;
	}
	$r = new WP_Query($query);

	$i = 1;
	$j = 1;
	
	$col[1]='<div class="four_columns">';
	$col[2]='<div class="four_columns">';
	$col[3]='<div class="four_columns">';
	$col[4]='<div class="four_columns last">';
	
	while($r->have_posts()) {
		$r->the_post();
		$terms = get_the_terms(get_the_id(), 'portfolio_category');
		$terms_slug = array();
		if (is_array($terms)) {
			foreach($terms as $term) {
				$terms_slug[] = $term->slug;
			}
		}
		
	
		
		
		if (has_post_thumbnail()) {
			$image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_id()), 'full', true);
			
			$type = get_post_meta(get_the_id(), 'type_value', true);
			if($type == 'image'){
				$href =  get_post_meta(get_the_id(), 'image_url_value', true);
				if(empty($href)){
					$href = $image[0];
				}
				$icon = 'zoom';
				$lightbox = ' lightbox';
				$rel = ' rel="'.$group.'"';
			}elseif($type == 'video'){
				$href =  get_post_meta(get_the_id(), 'video_url_value', true);
				if(empty($href)){
					$href = $image[0];
				}
				$icon = 'play';
				$lightbox = ' lightbox';
				$rel = ' rel="'.$group.'"';
			}else{
				$href = get_permalink();
				$icon = 'doc';
				$lightbox = '';
				$rel = '';
			}
			
			$col[$i] .='<div class="portfolio_item portfolio_item_'.$j.'">';
			
			
			$col[$i] .= '<div class="frame">';
			$col[$i] .= '<a class="icon_'.$icon.$lightbox.'" href="' . $href . '" title="' . get_the_title() . '"'.$rel.'>';
			
			$col[$i] .= '<img src="' . THEME_SCRIPTS . '/timthumb.php?src=' . $image[0] . '&amp;h=152&amp;w=228&amp;zc=1' . '" title="' . get_the_title() . '" alt="' . get_the_title() . '" />';
			
			$col[$i] .= '</a>';
			$col[$i] .= '</div>';

		}

			$col[$i] .= '<div class="portfolio_title"><a href="'.get_permalink().'">' . get_the_title() . '</a></div>';
			$col[$i] .= '<div class="portfolio_description">' . get_the_excerpt() . '</div>';
	

		$col[$i] .= '</div>';
		$col[$i] .= '<br />';
		
		$i++;
		if($i==5) {$i=1; $j++;}
	}
	
	$col[1].='</div>';
	$col[2].='</div>';
	$col[3].='</div>';
	$col[4].='</div>';
	
	$output .=$col[1].$col[2].$col[3].$col[4];
	
	
	$output .= '</div>';
	$output .= '<div class="clearboth"></div>';

	wp_reset_query();
	return $output;
}
add_shortcode('blog', 'theme_shortcode_blog');


