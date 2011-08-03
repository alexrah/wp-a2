<?php


get_header(); 

$layout=get_option('blog_layout');
if($layout=='right sidebar') { $layout='left'; $sidebar='right'; }
else { $layout='right'; $sidebar='left'; }


?>

<?php

			if(is_category()){
				$text = sprintf(__('Category Archive for: %s','cora'),single_cat_title('',false));
			}elseif(is_tag()){
				$text = sprintf(__('Tag Archives for: %s','cora'),single_tag_title('',false));
			}elseif(is_day()){
				$text = sprintf(__('Daily Archive for: %s','cora'),get_the_time('F jS, Y'));
			}elseif(is_month()){
				$text = sprintf(__('Monthly Archive for: %s','cora'),get_the_time('F, Y'));
			}elseif(is_year()){
				$text = sprintf(__('Yearly Archive for: %s','cora'),get_the_time('Y'));
			}
			elseif(isset($_GET['paged']) && !empty($_GET['paged'])) {
				$text = __('Blog Archives','cora');
			}
		
?>

<div id="sliderwrap" style="height:100px;"></div><!-- end sliderwrap -->
</div>

<?php theme_functions('heading_wrap'); ?>


<div id="main">
  <div id="primary" style="float:<?php echo $layout?>;">
  
			<?php get_template_part( 'loop', 'index' ); ?>
            
            
            <?php echo pagination(); ?>
			    
  </div><!-- end primary -->
  
      <div id="sidebar_<?php echo $sidebar?>"> 
        <div class="sidebar_content_<?php echo $sidebar?> sidebar">
    
          <?php get_sidebar(); ?>
     
        </div>
      </div><!-- end sidebar -->
  
</div><!-- end main -->

<?php get_footer(); ?>
