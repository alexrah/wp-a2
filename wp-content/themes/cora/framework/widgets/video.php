<?php
/**
 * Video Widget Class
 */
class Theme_Widget_Video extends WP_Widget {

	function Theme_Widget_Video() {
		$widget_ops = array('classname' => 'widget_video', 'description' => 'Displays a vimeo video' );
		$this->WP_Widget('Video', THEME_SLUG.' - '.'Video', $widget_ops);
		
		
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? 'Video': $instance['title'], $instance, $this->id_base);
		$clip_id= $instance['clip_id'];
		$height = (int)$instance['height'];

		if(!$height){
			$height=300;
		}
		
		$width = intval($height * 16 / 9);
		
		if ( !empty( $clip_id ) ) {
			echo $before_widget;
			if ( $title)
				echo $before_title . $title . $after_title;
				

        if (!empty($clip_id) && is_numeric($clip_id)){
		echo "<div class='video_frame'><iframe src='http://player.vimeo.com/video/".$clip_id."?title=0&amp;byline=0&amp;portrait=0' width='".$width."' height='".$height."' frameborder='0'></iframe></div>";
	}
		
			echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['clip_id'] = strip_tags($new_instance['clip_id']);
		$instance['height'] = (int) $new_instance['height'];
		
		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$clip_id = isset($instance['clip_id']) ? esc_attr($instance['clip_id']) : '';
		$height = isset($instance['height']) ? esc_attr($instance['height']) : 100;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('clip_id'); ?>">Clip Id</label>
		<input class="widefat" id="<?php echo $this->get_field_id('clip_id'); ?>" name="<?php echo $this->get_field_name('clip_id'); ?>" type="text" value="<?php echo $clip_id; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('height'); ?>">Height of the video</label>
		<input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" /></p>
		
<?php
	}
}