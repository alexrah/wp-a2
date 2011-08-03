<?php
/**
 * Related_Posts Widget Class
 *
 * @since 2.8.0
 */
class Theme_Widget_Related_Posts extends WP_Widget {

	function Theme_Widget_Related_Posts() {
		$widget_ops = array('classname' => 'widget_related_posts', 'description' => "Displays the related posts");
		$this->WP_Widget('related_posts', THEME_SLUG.' - '.'Related Posts', $widget_ops);
		$this->alt_option_name = 'widget_related_posts';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {

		$cache = wp_cache_get('theme_widget_related_posts', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? 'Related Posts': $instance['title'], $instance, $this->id_base);
		if ( !$number = (int) $instance['number'] )
			$number = 10;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 15 )
			$number = 15;
		
		
		global $post;
		
		$tags = wp_get_post_tags($post->ID);
		$tagIDs = array();
		if ($tags) {
			$tagcount = count($tags);
			for ($i = 0; $i < $tagcount; $i++) {
				$tagIDs[$i] = $tags[$i]->term_id;
			}

			$query = array('tag__in' => $tagIDs,'post__not_in' => array($post->ID),'showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'caller_get_posts' => 1);
			
			if(!empty($instance['cat'])){
				$query['cat'] = implode(',', $instance['cat']);
			}
			$r = new WP_Query($query);
				if ($r->have_posts()) :		
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<ul class="recent_posts">
<?php  while ($r->have_posts()) : $r->the_post(); ?>
			<li>
            
            
  <div class="thumbnail">
				<a href="<?php echo get_permalink() ?>" title="<?php the_title();?>">
                <div class="inner_frame">

<?php if (has_post_thumbnail() ): ?>

           
    <?php    $image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
        
             $image_src = THEME_SCRIPTS.'/timthumb.php?src='.$image_src_array[0].'&amp;h=60&amp;w=100&amp;zc=1';  ?>


                    <img src="<?php echo $image_src;?>" alt="<?php the_title(); ?>"/>
<?php else:?>
					<img src="<?php echo THEME_IMAGES;?>/widget_post_thumbnail.jpg" title="<?php the_title();?>" alt="<?php the_title();?>"/>
                    
                    
<?php endif;//end has_post_thumbnail ?>
				</div></a>
                
                <div class="caption"><?php comments_number('0','1','%');?></div>
                
                <div class="thumbnail_shadow"></div>
               
             </div>




				<div class="post_extra_info">
					<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a>

					<time datetime="<?php the_time('Y-m-d') ?>"><?php echo get_the_date(); ?></time>

				</div>
				<div class="clearboth"></div>
			</li>
<?php endwhile; ?>
		</ul>
		<?php echo $after_widget; ?>
<?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_query();

			endif;

			$cache[$args['widget_id']] = ob_get_flush();
			wp_cache_set('theme_widget_related_posts', $cache, 'widget');
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];

		$instance['cat'] = $new_instance['cat'];

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_related_entries']) )
			delete_option('widget_related_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_related_posts', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
	
		$cat = isset($instance['cat']) ? $instance['cat'] : array();

		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 5;


		$categories = get_categories('orderby=name&hide_empty=0');
?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>">Number of posts to show</label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

			<label for="<?php echo $this->get_field_id('cat'); ?>">Categories</label>
			<select style="height:5.5em" name="<?php echo $this->get_field_name('cat'); ?>[]" id="<?php echo $this->get_field_id('cat'); ?>" class="widefat" multiple="multiple">
				<?php foreach($categories as $category):?>
				<option value="<?php echo $category->term_id;?>"<?php echo in_array($category->term_id, $cat)? ' selected="selected"':'';?>><?php echo $category->name;?></option>
				<?php endforeach;?>
			</select>
		</p>
<?php
	}
}