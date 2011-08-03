<form method="get" id="searchform" action="<?php echo home_url(); ?>">

	<input type="text" class="text_input" value="<?php _e('Search..', 'cora');?>" name="s" id="s" onfocus="if(this.value == '<?php _e('Search..', 'cora');?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Search..', 'cora');?>';}" />
	<button type="submit" class="button"><span><?php _e('Search', 'cora');?></span></button>
</form>