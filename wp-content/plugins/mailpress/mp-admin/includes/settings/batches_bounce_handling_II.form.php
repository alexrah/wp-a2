<?php

$xevery = array (	30 	=> sprintf(__('%1$s seconds', MP_TXTDOM), '30'), 
			45 	=> sprintf(__('%1$s seconds', MP_TXTDOM), '45'), 
			60 	=> sprintf(__('%1$s minute' , MP_TXTDOM) , ''), 
			120 	=> sprintf(__('%1$s minutes', MP_TXTDOM), '2'), 
			300 	=> sprintf(__('%1$s minutes', MP_TXTDOM), '5'), 
			900 	=> sprintf(__('%1$s minutes', MP_TXTDOM), '15'), 
			1800 	=> sprintf(__('%1$s minutes', MP_TXTDOM), '30'), 
			3600 	=> sprintf(__('%1$s hour', 	MP_TXTDOM), '') ); 

$xmailboxstatus = array(	0	=>	__('no changes', MP_TXTDOM),
					1	=>	__('mark as read', MP_TXTDOM),
					2	=>	__('delete', MP_TXTDOM) );

if (!isset($bounce_handling_II)) $bounce_handling_II = get_option(MailPress_bounce_handling_II::option_name);
?>
<tr valign='top'>
	<th style='padding:0;'><strong><?php _e('Handling Bounces', MP_TXTDOM); ?> II</strong></th>
	<td></td>
</tr>
<tr valign='top'>
	<th scope='row'><?php _e('Max bounces per user', MP_TXTDOM); ?></th>
	<td class='field'>
		<select name='bounce_handling_II[max_bounces]'  style='width:4em;'>
<?php MP_AdminPage::select_number(0, 5, ( (isset($bounce_handling_II['max_bounces'])) ? $bounce_handling_II['max_bounces'] : 1 ) );?>
		</select>
	</td>
</tr>
<tr valign='top'>
	<th scope='row'><?php _e('Bounce in mailbox', MP_TXTDOM); ?></th>
	<td class='field'>
		<select name='bounce_handling_II[mailbox_status]'>
<?php MP_AdminPage::select_option($xmailboxstatus, ( (isset($bounce_handling_II['mailbox_status'])) ? $bounce_handling_II['mailbox_status'] : 2 ) );?>
		</select>
	</td>
</tr>
<tr valign='top'>
	<th scope='row'><?php _e('Submit batch with', MP_TXTDOM); ?></th>
	<td>
		<table class='general'>
			<tr>
				<td class='pr10'>
					<label for='bounce_handling_wp_cron'>
						<input value='wpcron' name='bounce_handling_II[batch_mode]' id='bounce_handling_wp_cron' class='submit_batch_bounce_II tog' type='radio' <?php checked('wpcron', $bounce_handling_II['batch_mode']); ?> />
						&nbsp;&nbsp;
						<?php _e('WP_Cron', MP_TXTDOM); ?>
					</label>
				</td>
				<td class='bounce_II_wpcron pr10 toggl3_II<?php if ('wpcron' != $bounce_handling_II['batch_mode']) echo ' hide'; ?>' style='padding-left:10px;vertical-align:bottom;'>
					<?php _e('Every', MP_TXTDOM); ?>
					&nbsp;&nbsp;
					<select name='bounce_handling_II[every]' id='every_bounce' >
<?php MP_AdminPage::select_option($xevery, $bounce_handling_II['every']);?>
					</select>
				</td>
			</tr>
			<tr>
				<td class='pr10'>
					<label for='bounce_handling_other'>
						<input value='other' name='bounce_handling_II[batch_mode]' id='bounce_handling_other' class='submit_batch_bounce_II tog' type='radio' <?php checked('other', $bounce_handling_II['batch_mode']); ?> />
						&nbsp;&nbsp;
						<?php _e('Other', MP_TXTDOM); ?>
					</label>
				</td>
				<td class='bounce_II_other pr10 toggl3_II<?php if ('other' != $bounce_handling_II['batch_mode']) echo ' hide'; ?>'>
					<?php printf(__('see sample in "%1$s"', MP_TXTDOM), MP_CONTENT_DIR . 'xtras/mp_bounce_handling_II'); ?>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr valign='top' style='line-height:10px;padding:0;'><td style='line-height:10px;padding:0;'>&nbsp;</td></tr>
<tr valign='top' class='mp_sep' style='line-height:2px;padding:0;'><td style='line-height:2px;padding:0;'></td></tr>
<tr><th></th><td colspan='4'></td></tr>