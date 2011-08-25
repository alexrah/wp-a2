<?php
class MP_Dashboard__right_now extends MP_Dashboard_widget_abstract
{
	var $id = 'mp__right_now';

	function widget()
	{
		global $wpdb, $wp_locale;

		$countm = $wpdb->get_var("SELECT sum(scount) FROM $wpdb->mp_stats WHERE stype='t';");
		$counts = $wpdb->get_var("SELECT count(*)    FROM $wpdb->mp_users WHERE status='active';");
		if (!$countm) $countm = 0;
		if (!$counts) $counts = 0;

		$plugin_data = get_plugin_data( MP_ABSPATH . 'MailPress.php' );
		$plugin_version = $plugin_data['Version'];

		$th = new MP_Themes();
		$themes = $th->themes; 
		$ct = $th->current_theme_info(); 
?>
<div id="dashboard_right_now">
<div class="inside">
	<div class="table table_content">
		<table>
			<tr class='first'>
				<td class="first b b-posts">
<?php 	if (current_user_can('MailPress_edit_mails')) : ?>
					<a href="<?php echo MailPress_mails; ?>"><?php echo $countm; ?></a>
<?php 	else : ?>
					<?php echo $countm; ?>
<?php 	endif; ?>
				</td>
				<td class="t posts"><?php echo( _n( __('Mail sent', MP_TXTDOM), __('Email spedite', MP_TXTDOM), $countm )); ?></td>
				<td class="b b-comments">
<?php 	if (current_user_can('MailPress_edit_users')) : ?>
					<a href="<?php echo MailPress_users; ?>"><?php echo $counts; ?></a>
<?php 	else : ?>
					<?php echo $counts; ?>
<?php 	endif; ?>
				</td>
				<td class="last t approved"><?php echo(_n( __('Active subscriber', MP_TXTDOM), __('Utenti attivi', MP_TXTDOM), $counts )); ?></td>
			</tr>
		</table>
	</div>
	<div class="versions">
			</div>

</div>
</div>
<?php
	}
}
new MP_Dashboard__right_now(__( "Newsletter - Sommario", MP_TXTDOM ));
