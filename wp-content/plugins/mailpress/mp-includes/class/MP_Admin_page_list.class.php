<?php
abstract class MP_Admin_page_list extends MP_Admin_page
{

//// Screen Options ////

	public static function screen_meta() 
	{
		add_filter('manage_' . MP_AdminPage::screen . '_columns', array('MP_AdminPage', 'get_columns'));

		parent::screen_meta();
	}

//// Columns ////

	public static function columns_list($id = true)
	{
		$columns = MP_AdminPage::get_columns();
		$hidden  = MP_AdminPage::get_hidden_columns();
		foreach ( $columns as $key => $display_name ) 
		{
			$thid  = ( $id ) ? " id='$key'" : '';
			$class = ( 'cb' === $key ) ? " class='check-column'" : " class='manage-column column-$key'";
			$style = ( in_array($key, $hidden) ) ? " style='display:none;'" : '';

			echo "<th scope='col'$thid$class$style>$display_name</th>";
		} 
	}

	public static function get_hidden_columns()
	{
		return get_hidden_columns(MP_AdminPage::screen);
	}

//// List ////

	public static function get_search_clause($s, $sc = array())
	{
		$replaces = array("\\" => "\\\\\\\\", "_" => "\_", "%" => "\%", "'" => "\'",);

		foreach($replaces as $k => $v) $s = str_replace($k, $v, $s);

		foreach($sc as $k => $v) $sc[$k] = "$v LIKE '%$s%'";

		return ' AND (' . join(' OR ', $sc) . ') '; 
	}

	public static function get_list($start, $num, $query, $cache_name)
	{
		global $wpdb;

		$start = abs( (int) $start );
		$num = (int) $num;

		$rows = $wpdb->get_results( "$query LIMIT $start, $num" );

		self::update_cache($rows, $cache_name);

		$total = $wpdb->get_var( "SELECT FOUND_ROWS()" );

		return array($rows, $total);
	}

	public static function get_bulk_actions($bulk_actions = array(), $name = 'action')
	{
		$bulk_actions = apply_filters('MailPress_bulk_actions_' . MP_AdminPage::screen, $bulk_actions);
		if (count($bulk_actions) <=1 ) return;
?>
				<select name='<?php echo $name; ?>'>
<?php
		foreach($bulk_actions as $k => $v) :
?>
					<option <?php echo (!empty($k)) ? "value='bulk-$k'": "selected='selected' value='-1'"; ?>><?php echo $v; ?></option>
<?php
		endforeach;
?>
				</select>
				<input type="submit" name="do<?php echo $name; ?>" id="do<?php echo $name; ?>" value="<?php esc_attr_e('Apply'); ?>" class="button-secondary apply" />
<?php
	}

//// Row ////

	public static function get_actions($actions, $class = 'row-actions')
	{
		foreach ( $actions as $k => $v ) $actions[$k] = "<span class='$k'>$v";
		return "<div class='$class'>" . join( ' | </span>', $actions ) . '</span></div>';
	}

	public static function human_time_diff($m_time)
	{
		$time   = strtotime( get_gmt_from_date( $m_time ) );
		$time_diff = current_time('timestamp', true) - $time;

		if ( $time_diff <= 0 )			return __('now', MP_TXTDOM);
		elseif ( $time_diff < 24*60*60 )	return sprintf( __('%s ago'), human_time_diff( $time ) );
		else						return mysql2date(__('Y/m/d'), $m_time);
	}

////  ////

	public static function update_cache($xs, $y) 
	{
		foreach ( (array) $xs as $x ) wp_cache_add($x->id, $x, $y);
	}
}