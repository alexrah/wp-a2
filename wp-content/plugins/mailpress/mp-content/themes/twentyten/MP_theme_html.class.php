<?php
class MP_theme_html
{
	const HEADER_IMAGE_WIDTH = 700;
	const HEADER_IMAGE_HEIGHT = 147;

	public static function header_image($default, $post_id = false)
	{
		if (!is_numeric($post_id)) $post_id = false;
		switch (true)
		{
			case ( function_exists('has_post_thumbnail') && function_exists('get_post_thumbnail_id') && function_exists('wp_get_attachment_image_src') && $post_id && has_post_thumbnail( $post_id ) && ($image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'post-thumbnail')) && ($image[1] >= MP_theme_html::HEADER_IMAGE_WIDTH) ) :
				echo $image[0];
			break;
			case ( function_exists('header_image') ) :
				header_image();
			break;
			default:
				echo $default;
			break;
		}
	}
//
	public static function comments_popup_link_attributes($attrs = '')
	{
		return $attrs . ' style="color:#888;" ';
	}

	public static function the_category($thelist, $separator, $parents)
	{
		return str_replace('a href=', 'a style="color:#888;" href=', $thelist );
	}

	public static function term_links_post_tag($term_links)
	{
		foreach($term_links as $k => $v)
			$term_links[$k] = str_replace('a href=', 'a style="color:#888;" href=', $v );
		return $term_links;
	}
//
	public static function who_is($ip)
	{
		$x  = MP_Ip::get_all($ip);

		if (!$x['geo']['lat'] && !$x['geo']['lng']) return array('src' => false, 'addr' => false);

		$width  = 300;
		$height = 300;
		$src  = 'http://maps.google.com/staticmap?';
		$src .= 'center=' . $x['geo']['lat'] . ',' . $x['geo']['lng'];
		$src .= '&zoom=4';
		$src .= "&size=$width" . 'x' . $height;
		$src .= '&maptype=roadmap'; 
		$src .= '&markers=' . $x['geo']['lat'] . ',' . $x['geo']['lng'];
		$src .= '&sensor=false';

		$addr = MP_Ip::get_address($x['geo']['lat'], $x['geo']['lng']);

		return array('src' => $src, 'addr' => $addr[0]);
	}
}