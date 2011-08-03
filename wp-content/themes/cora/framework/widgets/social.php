<?php
/**
 * Social Icon Widget Class
 */
class Theme_Widget_Social extends WP_Widget {

	var $sites = array(
	'addthis','aim','apple','bebo','behance','blogger','birghtkite','buzz','cargo','delicious','deisgm_bump','designfloat','designmoo','deviantart','digg','dopplr','dribble','email','ember','evernote','facebook','flickr','friendfeed','github','google','google_buzz','google_talk','googlemaps','lastfm','linkedin','livejournal','mixx','mobileme','msn','myspace','netvibes','newsvine','orkut','pandora','paypal','picasa','posterous','qik','reddit','rss','sharethis','skype','slashdot','squidoo','stumbleupon','technorati','tumblr','twitter','viddler','vimeo','virb','windows','wordpress','xing','yahoo','yelp','youtube'
	);

	
	
	function Theme_Widget_Social() {
		$widget_ops = array('classname' => 'widget_social', 'description' => 'Displays social icons' );
		$this->WP_Widget('social', THEME_SLUG.' - '.'Social Icon', $widget_ops);
		
		if ('widgets.php' == basename($_SERVER['PHP_SELF'])) {
			add_action( 'admin_print_scripts', array(&$this, 'add_admin_script') );
		}
	}
	
	function add_admin_script(){
		wp_enqueue_script( 'social-icon-widget', THEME_ADMIN_ASSETS_URI . '/js/social-icon-widget.js');
	}
	

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$alt = isset($instance['alt'])?$instance['alt']:'';
		$animation = isset($instance['animation'])?$instance['animation']:'fade';
		$package = $instance['package'];
		$custom_count = $instance['custom_count'];
		$output = '';
		if( !empty($instance['enable_sites']) ){
			foreach($instance['enable_sites'] as $site){
				
				$path = str_replace('{:name}',strtolower($site),'social_icons/{:name}.png');
				
				
				
				$link = isset($instance[strtolower($site)])?$instance[strtolower($site)]:'#';
				if(file_exists(THEME_DIR . '/images/'.$path)){

					$output .= '<a href="'.$link.'" rel="nofollow" target="_blank"><img src="'.THEME_IMAGES.'/'.$path.'" alt="'.$alt.' '.$site.'" title="'.$alt.' '.$site.'"/></a>';
				}
			}
		}
		if( $custom_count > 0){
			for($i=1; $i<= $custom_count; $i++){
				$name = isset($instance['custom_'.$i.'_name'])?$instance['custom_'.$i.'_name']:'';
				$icon = isset($instance['custom_'.$i.'_icon'])?$instance['custom_'.$i.'_icon']:'';
				$link = isset($instance['custom_'.$i.'_url'])?$instance['custom_'.$i.'_url']:'#';
				if(!empty($icon)){
					$output .= '<a href="'.$link.'" rel="nofollow" target="_blank"><img src="'.$icon.'" alt="'.$alt.' '.$name.'" title="'.$alt.' '.$name.'"/></a>';
				}
			}
		}
		
		
		if ( !empty( $output ) ) {
			echo $before_widget;
			if ( $title)
				echo $before_title . $title . $after_title;
		?>

		<?php echo $output; ?>

		<?php
			echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['alt'] = strip_tags($new_instance['alt']);

		$instance['enable_sites'] = $new_instance['enable_sites'];
		$instance['custom_count'] = (int) $new_instance['custom_count'];

		if(!empty($instance['enable_sites'])){
			foreach($instance['enable_sites'] as $site){
				$instance[strtolower($site)] = isset($new_instance[strtolower($site)])?strip_tags($new_instance[strtolower($site)]):'';
			}
		}
		for($i=1;$i<=$instance['custom_count'];$i++){
			$instance['custom_'.$i.'_name'] = strip_tags($new_instance['custom_'.$i.'_name']);
			$instance['custom_'.$i.'_url'] = strip_tags($new_instance['custom_'.$i.'_url']);
			$instance['custom_'.$i.'_icon'] = strip_tags($new_instance['custom_'.$i.'_icon']);
		}
		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$alt = isset($instance['alt']) ? esc_attr($instance['alt']) : 'Follow Us on';
		
		$enable_sites = isset($instance['enable_sites']) ? $instance['enable_sites'] : array();
		foreach($this->sites as $site){
			$$site = isset($instance[strtolower($site)]) ? esc_attr($instance[strtolower($site)]) : '';
		}

		$custom_count = isset($instance['custom_count']) ? absint($instance['custom_count']) : 0;
		for($i=1;$i<=10;$i++){
			$custom_name = 'custom_'.$i.'_name';
			$$custom_name = isset($instance[$custom_name]) ? $instance[$custom_name] : '';
			$custom_url = 'custom_'.$i.'_url';
			$$custom_url = isset($instance[$custom_url]) ? $instance[$custom_url] : '';
			$custom_icon = 'custom_'.$i.'_icon';
			$$custom_icon = isset($instance[$custom_icon]) ? $instance[$custom_icon] : '';
		}
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title</label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('alt'); ?>">Icon Alternative title</label> <input class="widefat" id="<?php echo $this->get_field_id('alt'); ?>" name="<?php echo $this->get_field_name('alt'); ?>" type="text" value="<?php echo $alt; ?>" /></p>
		

		<p>
			<label for="<?php echo $this->get_field_id('enable_sites'); ?>">Enable Social Icon:</label>
			<select name="<?php echo $this->get_field_name('enable_sites'); ?>[]" style="height:10em" id="<?php echo $this->get_field_id('enable_sites'); ?>" class="social_icon_select_sites widefat" multiple="multiple">
				<?php foreach($this->sites as $site):?>
				<option value="<?php echo strtolower($site);?>"<?php echo in_array(strtolower($site), $enable_sites)? 'selected="selected"':'';?>><?php echo $site;?></option>
				<?php endforeach;?>
			</select>
		</p>
		
		<p>
			<em>Note: Please input FULL URL <br/>(e.g. <code>http://www.sample.com</code></em>
		</p>
		<div class="social_icon_wrap">
		<?php foreach($this->sites as $site):?>
		<p class="social_icon_<?php echo strtolower($site);?>" <?php if(!in_array(strtolower($site), $enable_sites)):?>style="display:none"<?php endif;?>>
			<label for="<?php echo $this->get_field_id( strtolower($site) ); ?>"><?php echo $site.' '.'URL:'?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( strtolower($site) ); ?>" name="<?php echo $this->get_field_name( strtolower($site) ); ?>" type="text" value="<?php echo $$site; ?>" />
		</p>
		<?php endforeach;?>
		</div>

		<p><label for="<?php echo $this->get_field_id('custom_count'); ?>">Other custom icons you would like to add</label>
		<input id="<?php echo $this->get_field_id('custom_count'); ?>" class="social_icon_custom_count" name="<?php echo $this->get_field_name('custom_count'); ?>" type="text" value="<?php echo $custom_count; ?>" size="3" /></p>

		<div class="social_custom_icon_wrap">
		<?php for($i=1;$i<=10;$i++): $custom_name='custom_'.$i.'_name';$custom_url='custom_'.$i.'_url'; $custom_icon='custom_'.$i.'_icon'; ?>
			<div class="social_icon_custom_<?php echo $i;?>" <?php if($i>$custom_count):?>style="display:none"<?php endif;?>>
				<p><label for="<?php echo $this->get_field_id( $custom_name ); ?>"><?php printf('Custom %s Name:',$i);?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( $custom_name ); ?>" name="<?php echo $this->get_field_name( $custom_name ); ?>" type="text" value="<?php echo $$custom_name; ?>" /></p>
				<p><label for="<?php echo $this->get_field_id( $custom_url ); ?>"><?php printf('Custom %s URL:',$i);?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( $custom_url ); ?>" name="<?php echo $this->get_field_name( $custom_url ); ?>" type="text" value="<?php echo $$custom_url; ?>" /></p>
				<p><label for="<?php echo $this->get_field_id( $custom_icon ); ?>"><?php printf('Custom %s Icon:',$i);?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( $custom_icon ); ?>" name="<?php echo $this->get_field_name( $custom_icon ); ?>" type="text" value="<?php echo $$custom_icon; ?>" /></p>
			</div>

		<?php endfor;?>
		</div>


		
<?php
	}
}