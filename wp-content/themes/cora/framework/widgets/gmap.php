<?php
/**
 * Twitter Widget Class
 */
class Theme_Widget_Gmap extends WP_Widget {

	function Theme_Widget_Gmap() {
		$widget_ops = array('classname' => 'widget_gmap', 'description' => 'Displays a google map.');
		$this->WP_Widget('gmap', THEME_SLUG.' - '.'Gmap', $widget_ops);
		

	}
	
	
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$address = $instance['address'];
		$latitude = !empty($instance['latitude'])?$instance['latitude']:0;
		$longitude = !empty($instance['longitude'])?$instance['longitude']:0;
		$zoom = (int)$instance['zoom'];
		$html = $instance['html'];
		$popup = $instance['popup'];
		$height = (int)$instance['height'];
		
		if($zoom < 1){
			$zoom = 1;
		}

		echo $before_widget;
		if ( $title)
			echo $before_title . $title . $after_title;
				
		$id = rand(100,3000);
		?>
		
		<div id="gmap_widget_<?php echo $id;?>" class="google_map" style="height:<?php echo $height;?>px"></div>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			jQuery("#gmap_widget_<?php echo $id;?>").gMap({
			    zoom: <?php echo $zoom;?>,
			    markers:[{
					address: "<?php echo $address;?>",
					latitude: <?php echo $latitude;?>,
			    	longitude: <?php echo $longitude;?>,
			    	html: "<?php echo $html;?>",
			    	popup: <?php echo $popup;?>
				}],
				controls: false,
				maptype: G_NORMAL_MAP
			});
		});
		</script>

		<div class="clearboth"></div>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);		
		$instance['address'] = strip_tags($new_instance['address']);
		$instance['latitude'] = strip_tags($new_instance['latitude']);
		$instance['longitude'] = strip_tags($new_instance['longitude']);
		$instance['zoom'] = (int) $new_instance['zoom'];
		$instance['html'] = strip_tags($new_instance['html']);
		$instance['popup'] = !empty($new_instance['popup']) ? 1 : 0;
		$instance['height'] = (int) $new_instance['height'];
		
		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$address = isset($instance['address']) ? esc_attr($instance['address']) : '';
		$latitude = isset($instance['latitude']) ? esc_attr($instance['latitude']) : '';
		$longitude = isset($instance['longitude']) ? esc_attr($instance['longitude']) : '';
		$zoom = isset($instance['zoom']) ? absint($instance['zoom']) : 14;
		$html = isset($instance['html']) ? esc_attr($instance['html']) : '';
		$popup = isset( $instance['popup'] ) ? (bool) $instance['popup'] : false;
		$height = isset($instance['height']) ? absint($instance['height']) : 250;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('address'); ?>">Adress (optional)</label>
		<input class="widefat" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>" type="text" value="<?php echo $address; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('latitude'); ?>">Latitude</label>
		<input class="widefat" id="<?php echo $this->get_field_id('latitude'); ?>" name="<?php echo $this->get_field_name('latitude'); ?>" type="text" value="<?php echo $latitude; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('longitude'); ?>">Longitude</label>
		<input class="widefat" id="<?php echo $this->get_field_id('longitude'); ?>" name="<?php echo $this->get_field_name('longitude'); ?>" type="text" value="<?php echo $longitude; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('zoom'); ?>">Zoom value from 1 to 19</label>
		<input id="<?php echo $this->get_field_id('zoom'); ?>" name="<?php echo $this->get_field_name('zoom'); ?>" type="text" value="<?php echo $zoom; ?>" size="3" /></p>
		
		<p><label for="<?php echo $this->get_field_id('html'); ?>">Content for the marker</label>
		<input class="widefat" id="<?php echo $this->get_field_id('html'); ?>" name="<?php echo $this->get_field_name('html'); ?>" type="text" value="<?php echo $html; ?>" /></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('popup'); ?>" name="<?php echo $this->get_field_name('popup'); ?>"<?php checked( $popup ); ?> />
		<label for="<?php echo $this->get_field_id('popup'); ?>">Auto popup the information?</label></p>
		
		<p><label for="<?php echo $this->get_field_id('height'); ?>">Height</label>
		<input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" /></p>
		
<?php
	}
}