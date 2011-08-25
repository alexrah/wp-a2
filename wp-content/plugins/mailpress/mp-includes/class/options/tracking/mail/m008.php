<?php
class MP_Tracking_module_m008 extends MP_Tracking_module_abstract
{
	var $id	= 'm008';
	var $context= 'normal';
	var $file 	= __FILE__;

	function meta_box($mail)
	{
		global $wpdb;
		$tracks = $wpdb->get_results( $wpdb->prepare( "SELECT track, count(*) as count FROM $wpdb->mp_tracks WHERE mail_id = %d AND track <> '_MailPress_mail_opened' GROUP BY 1 ORDER BY 2 DESC, 1 DESC LIMIT 10;", $mail->id) );
		if ($tracks) foreach($tracks as $track)
			echo "({$track->count}) "  . MailPress_tracking::translate_track($track->track, $mail->id, 50) . " <br />";
	}
}
new MP_Tracking_module_m008(__('Pagine piu&#39; cliccate', MP_TXTDOM));
