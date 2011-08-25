<?php
class MP_Tracking_module_m002 extends MP_Tracking_module_abstract
{
	var $id	= 'm002';
	var $context= 'normal';
	var $file 	= __FILE__;

	function meta_box($mail)
	{
		global $wpdb;
		$tracks = $wpdb->get_results( $wpdb->prepare( "SELECT user_id, MAX(tmstp) as tmstp FROM $wpdb->mp_tracks WHERE mail_id = %d GROUP BY user_id ORDER BY tmstp DESC LIMIT 30;", $mail->id) );

		if ($tracks) 
		{
			echo '<table cellpadding="0" cellspacing="0">';

			foreach($tracks as $track)
			{
				$tracking_url = esc_url(MailPress::url( MailPress_tracking_u, array('id' => $track->user_id) ));
				$action = "<a href='$tracking_url' target='_blank' title='" . __('Guarda risultati tracking', MP_TXTDOM ) . "'>" . MP_Users::get_email($track->user_id) . '</a>';
				echo '<tr><td><abbr title="' . $track->tmstp . '">' . substr($track->tmstp, 0, 10) . '</abbr></td><td>&nbsp;' . $action . '</td></tr>';
			}
			echo '</table>';
		}
	}
}
new MP_Tracking_module_m002(__('Ultimi 30 utenti', MP_TXTDOM));
