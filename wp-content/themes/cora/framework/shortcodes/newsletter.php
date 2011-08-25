<?php
function theme_shortcode_newsletter($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'cat' => '',
		'count' => 3,
	), $atts));

	
	$output='<div class="testmail_list">';
	
	
	$query = array('post_type' => 'post','posts_per_page' => $count, 'orderby'=> 'date', 'post_status' => 'publish');
	if($cat){
		$query['cat'] = $cat;
	}
	$r = new WP_Query($query);

	$i = 1;
	$j = 1;
	
	$col[1]='<div class="testmail_columns" >';
	$col[2]='<div class="testmail_columns" >';
	$col[3]='<div class="testmail_columns" >';
	$col[4]='<div class="testmail_columns" >';
	
	while($r->have_posts()) {
		$r->the_post();
		$terms = get_the_terms(get_the_id(), 'testmail_category');
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
			
			$col[$i] .='<table style="margin-top:15px; background-color:#F0F0F0; -moz-border-radius: 15px; border-radius: 15px;" class="testmail_item testmail_item_'.$j.'">';
			$col[$i] .= '<tr>';  //
			$col[$i] .= '<td>';  //

			$col[$i] .= '<a class="icon_'.$icon.$lightbox.'" href="' . $href . '" title="' . get_the_title() . '"'.$rel.'>';
			
			$col[$i] .= '<img align="left" style="margin-left:10px; margin-right: 5px; margin-top: 10px; margin-bottom: 0px;" src="' . THEME_SCRIPTS . '/timthumb.php?src=' . $image[0] . '&amp;h=152&amp;w=228&amp;zc=1' . '" title="' . get_the_title() . '" alt="' . get_the_title() . '" />';
			
			$col[$i] .= '</a>';
      $col[$i] .= '</td>';
//		  $col[$i] .= '</br >';	// THIS MAKE THE NEWSLETTER BREAK LINE WORKS!!!!!!!!
		}

    
			$col[$i] .= '<td valign="top">';  //
      $col[$i] .= '<div class="testmail_title"><a href="'.get_permalink().'"><h4>' . get_the_title() . '</h4></a></div>';
      $col[$i] .= '<div style="margin-right: 10px;" class="testmail_description"><font face="Arial, Helvetica, sans-serif" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333333;">' . excerpt(15) . '</font></div>';
      $col[$i] .= '<table style="width: 97%;  margin-top: 15px; border: solid 2px #CCCCCC;">';  // start new table row for scheda tecnica
      $col[$i] .= '<tr>';
      $col[$i] .= '<td style="border-bottom: solid 2px #CCCCCC;" align="left">';
      $col[$i] .= '<div><font face="Arial, Helvetica, sans-serif" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333333;">' . get_post_meta(get_the_id(), 'Impianto', true) . '</font></div>';
      $col[$i] .= '</td>';
      $col[$i] .= '<td style="border-bottom: solid 2px #CCCCCC;" align="right">';
      $col[$i] .= '<div><font face="Arial, Helvetica, sans-serif" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333333;">' . get_post_meta(get_the_id(), 'Tipo Impianto', true) . '</font></div>';
      $col[$i] .= '</td>';
      $col[$i] .= '</tr>'; // end 1 sub-line
      $col[$i] .= '<tr>';
      $col[$i] .= '<td align="left">';
      $col[$i] .= '<div><font face="Arial, Helvetica, sans-serif" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333333;">' . get_post_meta(get_the_id(), 'Potenza', true) . '</font></div>';
      $col[$i] .= '</td>';
      $col[$i] .= '<td align="right">';
      $col[$i] .= '<div><font face="Arial, Helvetica, sans-serif" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333333;">' . get_post_meta(get_the_id(), 'N potenza', true) . '</font></div>';
      $col[$i] .= '</td>';
      $col[$i] .= '</tr>'; // end 2 sub-line
      $col[$i] .= '</table>'; // 
      $col[$i] .= '</tr>'; // end master row
      $col[$i] .= '<tr>';  // nuova linea x didascalia e bottone
      $col[$i] .= '<td>';
      $col[$i] .= '<div style="width: 227px; margin-left: 10px; background-color: #CCCCCC;"><font face="Arial, Helvetica, sans-serif" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333333;">' . get_post_meta(get_the_id(), 'Didascalia Immagine', true) . '</font></div>';
      $col[$i] .= '</td>';
      $col[$i] .= '<td>';
      $col[$i] .= '<div align="right" style="margin-right: 10px;">';
			$col[$i] .= '<a class="icon_'.$icon.$lightbox.'" href="' . $href . '" title="' . get_the_title() . '"'.$rel.'>';

			$col[$i] .= '<img src="images/bottone.gif"  />';

			$col[$i] .= '</a>';
      $col[$i] .= '</div>';
      $col[$i] .= '</td>';
      $col[$i] .= '</tr>';
		  $col[$i] .= '</table>';
		
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
add_shortcode('newsletter', 'theme_shortcode_newsletter');


