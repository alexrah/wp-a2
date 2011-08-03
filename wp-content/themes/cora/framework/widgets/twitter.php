<?php
/**
 * Twitter Widget Class
 */
class Theme_Widget_Twitter extends WP_Widget {

	function Theme_Widget_Twitter() {
		$widget_ops = array('classname' => 'widget_twitter', 'description' => 'Displays a list of twitter feeds' );
		$this->WP_Widget('twitter', THEME_SLUG.' - '.'Twitter', $widget_ops);
		
		if ( is_active_widget(false, false, $this->id_base) ){
			add_action( 'wp_print_scripts', array(&$this, 'add_tweet_script') );
		}
		
	}

	function add_tweet_script(){
		wp_enqueue_script('jquery-tweet',THEME_JS.'/jquery.tweet.js');
	}
	
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? 'Recent Tweets': $instance['title'], $instance, $this->id_base);
		$username= $instance['username'];
		$count = (int)$instance['count'];

		if($count < 1){
			$count = 1;
		}
		
		if ( !empty( $username ) ) {
			echo $before_widget;
			if ( $title)
				echo $before_title . $title . $after_title;
				
		$id = rand(1,1000);
		?>
		
		<script type="text/javascript">
				jQuery(document).ready(function($) {
					 $("#twitter_wrap_<?php echo $id;?>").tweet({
						username: "<?php echo $username;?>",
						count: <?php echo $count;?>,
						seconds_ago_text: '<?php _e('about %d seconds ago','cora');?>',
						a_minutes_ago_text: '<?php _e('about a minute ago','cora');?>',
						minutes_ago_text: '<?php _e('about %d minutes ago','cora');?>',
						a_hours_ago_text: '<?php _e('about an hour ago','cora');?>',
						hours_ago_text: '<?php _e('about %d hours ago','cora');?>',
						a_day_ago_text: '<?php _e('about a day ago','cora');?>',
						days_ago_text: '<?php _e('about %d days ago','cora');?>',
						view_text: '<?php _e('view tweet on twitter','cora');?>'
					 });
				});
		</script>
		<div id="twitter_wrap_<?php echo $id;?>"></div>
		<div class="clearboth"></div>
		<?php
			echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['username'] = strip_tags($new_instance['username']);
		$instance['count'] = (int) $new_instance['count'];
		
		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$username = isset($instance['username']) ? esc_attr($instance['username']) : '';
		$count = isset($instance['count']) ? absint($instance['count']) : 3;
		$display = isset( $instance['display'] ) ? $instance['display'] : 'latest';
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('username'); ?>">Username</label>
		<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('count'); ?>">How many tweets to display?</label>
		<input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" size="3" /></p>
		
<?php
	}
}