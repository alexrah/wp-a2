
			
<?php 

      if(isset($post)) sidebar('get_sidebar',$post->ID);
      else sidebar('get_sidebar',NULL);
	 
?>
