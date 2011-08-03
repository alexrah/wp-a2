<?php
function theme_shortcode_portfolio($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'column' => "4 columns",
		'cat' => '',
		'max' => 8,
		'nopaging' => 'false',
		'sortable' => 'false',
		'jquery' => 'false',
	), $atts));
	switch($column){
		case "1 column":
			$column_class = 'one_column';
			$size = array(550, (int)get_option('one_column_height'));
			break;
		case "2 columns":
			$column_class = 'two_columns';
			$size = array(458, (int)get_option('two_column_height'));
			break;
		case "3 columns":
			$column_class = 'three_columns';
			$size = array(300, (int)get_option('three_column_height'));
			break;
		case "4 columns":
		default:
			$column_class = 'four_columns';
			$size = array(225, (int)get_option('four_column_height'));
	}
	$group = 'portfolio_'.rand(1,1000); 
	if ($sortable != 'false') {

		wp_print_scripts('jquery-quicksand');
		
		$output = '<div class="portfolios sortable">';
		$output .= '<div class="sortable_categories">';
		$output .= '<span>'.__('Show:','cora').'</span>';
		$output .= '<a data-value="all" class="button" href="#">'.__('All','cora').'</a>';
		$terms = array();
		if ($cat != '') {
			foreach(explode(',', $cat) as $term_slug) {
				$terms[] = get_term_by('slug', $term_slug, 'portfolio_category');
			}
		} else {
			$terms = get_terms('portfolio_category', 'hide_empty=1');
		}
		foreach($terms as $term) {
			$output .= '<a data-value="' . $term->slug . '" class="button" href="#">' . $term->name . '</a>';
		}
		
		$output .= '</div>';
		$nopaging = 'true';
	} 
	else{
		$output = '<div class="portfolios">';
	}
	$output .= '<ul class="portfolio_' . $column_class . '">';
	
	if ($nopaging == 'false') {
		
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		query_posts(array('post_type' => 'portfolio', 'posts_per_page' => $max, 'taxonomy' => 'portfolio_category', 'term' => $cat, 'paged' => $paged));
	} else {
		query_posts(array('post_type' => 'portfolio', 'taxonomy' => 'portfolio_category', 'showposts' => - 1, 'term' => $cat));

	}
	$i = 1;
	while(have_posts()) {
		the_post();
		$terms = get_the_terms(get_the_id(), 'portfolio_category');
		$terms_slug = array();
		if (is_array($terms)) {
			foreach($terms as $term) {
				$terms_slug[] = $term->slug;
			}
		}
		
		
		if (($i % $column) == 0 && $column != 1) {
			$output .= '<li data-type="' . implode(',', $terms_slug) . '" data-id="'.get_the_id().'" class="last">';
		} else {
			$output .= '<li data-type="' . implode(',', $terms_slug) . '" data-id="'.get_the_id().'" >';
		}
		$i++;
		
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
            $output .="<div class='image_container alignleft'>";
			$output .= '<div class="frame" style="height:'.$size[1].'px; width:'.($size[0]-6).'px">';
			$output .= '<a class="icon_'.$icon.$lightbox.'" href="' . $href . '" title="' . get_the_title() . '"'.$rel.'>';
			
			$output .= '<img src="' . THEME_SCRIPTS . '/timthumb.php?src=' . $image[0] . '&amp;h=' . $size[1] . '&amp;w=' . ($size[0]-6) . '&amp;zc=1' . '" title="' . get_the_title() . '" alt="' . get_the_title() . '" />';
			$output .= '</a>';
			$output .= '</div>';
			$output .= '</div>';
		}
		$more = get_post_meta(get_the_id(), 'read_more_value', true);
		$output .= '<div class="portfolio_details">';
		if(get_option('portfolio_title')=='on' || $column == '1 column'){
			$output .= '<div class="portfolio_title">' . get_the_title() . '</div>';
		}
		if(get_option('portfolio_description')=='on' || $column == '1 column'){
			$output .= '<div class="portfolio_desc">' . get_the_excerpt() . '';
		}
		if((get_option('portfolio_more')=='on' || $column == '1 column') && $more=='on'){
			$output .= '<div class="portfolio_more_button"><a class="more" href="'.get_permalink().'">'.get_option('portfolio_read_more').'</a></div>';
		}
		$output .= '</div></div>';
		$output .= '</li>';
	}
	$output .= '</ul>';
	if ($nopaging == 'false') {
		ob_start();

$output .= 	pagination();

$output .= '<span id="handwritten2"><span></span>more projects</span>';
		
		$output .= ob_get_clean();
	}
	$output .= '<div class="clearboth"></div></div>';
	wp_reset_query();
	return $output;
}
add_shortcode('portfolio', 'theme_shortcode_portfolio');



/*
 * Thank to Bob Sherron.
 * http://stackoverflow.com/questions/1155565/query-multiple-custom-taxonomy-terms-in-wordpress-2-8/2060777#2060777
 */
function multi_tax_terms($where) {
    global $wp_query;
    if (isset($wp_query->query_vars['term']) && (strpos($wp_query->query_vars['term'], ',') !== false && strpos($where, "AND 0") !== false) ) {
        // it's failing because taxonomies can't handle multiple terms
        //first, get the terms
        $term_arr = explode(",", $wp_query->query_vars['term']);
        foreach($term_arr as $term_item) {
            $terms[] = get_terms($wp_query->query_vars['taxonomy'], array('slug' => $term_item));
        }

        //next, get the id of posts with that term in that tax
        foreach ( $terms as $term ) {
            $term_ids[] = $term[0]->term_id;
        }

        $post_ids = get_objects_in_term($term_ids, $wp_query->query_vars['taxonomy']);

        if ( !is_wp_error($post_ids) && count($post_ids) ) {
            // build the new query
            $new_where = " AND wp_posts.ID IN (" . implode(', ', $post_ids) . ") ";
            // re-add any other query vars via concatenation on the $new_where string below here

            // now, sub out the bad where with the good
            $where = str_replace("AND 0", $new_where, $where);
        } else {
            // give up
        }
    }
    return $where;
}

add_filter("posts_where", "multi_tax_terms");