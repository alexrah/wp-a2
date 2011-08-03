<?php
/**
 * Popular_Posts Widget Class
 */
class Theme_Widget_Portfolio extends WP_Widget {

	function Theme_Widget_Portfolio() {
		$widget_ops = array('classname' => 'widget_portfolio', 'description' => "Displays thumbnails of your portfolio items" );
		$this->WP_Widget('portfolio', THEME_SLUG.' - '.'Portfolio', $widget_ops);

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {

		
		$cache = wp_cache_get('theme_widget_portfolio', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? 'Portfolio' : $instance['title'], $instance, $this->id_base);
		if ( !$number = (int) $instance['number'] )
			$number = 10;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 15 )
			$number = 15;

        if($instance['cat']) $cat=implode(',', $instance['cat']);	


		
		query_posts(array('post_type' => 'portfolio', 'posts_per_page' => $number, 'taxonomy' => 'portfolio_category', 'term' => $cat));
		

		if (have_posts()) :
?>

		<?php echo $before_widget; ?>
        
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<ul class="portfolio_widget">
        
<?php  while (have_posts()) : the_post(); ?>
			<li>

             <div class="thumbnail">
				<a href="<?php echo get_permalink() ?>" title="<?php the_title();?>">


<?php if (has_post_thumbnail() ): ?>

           
    <?php    $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
        
             $image_src = THEME_SCRIPTS.'/timthumb.php?src='.$image_src_array[0].'&amp;h=60&amp;w=85&amp;zc=1';  ?>


                    <img src="<?php echo $image_src;?>" alt="<?php the_title(); ?>"/>
<?php else:?>
					<img src="<?php echo THEME_IMAGES;?>/widget_post_thumbnail.jpg" title="<?php the_title();?>" alt="<?php the_title();?>"/>
                    
                    
<?php endif;//end has_post_thumbnail ?>
				</a>

               
             </div>



			</li>
<?php endwhile; ?>
		</ul>
        <div class="clearboth"></div>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_query();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('theme_widget_portfolio', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];

		$instance['cat'] = $new_instance['cat'];
		
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_posts', 'widget');
	}

	function form( $instance ) {
		
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$cat = isset($instance['cat']) ? $instance['cat'] : array();
		
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 5;


		$categories = get_categories('taxonomy=portfolio_category&orderby=name&hide_empty=0');

?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>">Number of portfolio items to show</label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

			<label for="<?php echo $this->get_field_id('cat'); ?>">Categories</label>
			<select style="height:5.5em" name="<?php echo $this->get_field_name('cat'); ?>[]" id="<?php echo $this->get_field_id('cat'); ?>" class="widefat" multiple="multiple">
				<?php foreach($categories as $category):?>
				<option value="<?php echo $category->name;?>"<?php echo in_array($category->term_id, $cat)? ' selected="selected"':'';?>><?php echo $category->name;?></option>
				<?php endforeach;?>
			</select>
		</p>
<?php
	}
}

