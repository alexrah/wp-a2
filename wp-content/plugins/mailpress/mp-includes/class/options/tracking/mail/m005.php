<?php
class MP_Tracking_module_m005 extends MP_Tracking_module_abstract
{
	var $id	= 'm005';
	var $context= 'normal';
	var $file 	= __FILE__;

	function meta_box($mail)
	{
		global $wpdb;

		$tracks = $wpdb->get_results( $wpdb->prepare( "SELECT context, count(*) as count FROM $wpdb->mp_tracks WHERE mail_id = %d GROUP BY context ORDER BY context;", $mail->id) );
		$total = 0;
		if ($tracks)
		{
			foreach($tracks as $track)
			{
				$context[$track->context] = $track->count;
				$total += $track->count;
			}
			foreach($context as $k => $v)
			{
				echo '<b>' . $k . '</b> : &nbsp;' . sprintf("%01.2f %%",100 * $v/$total ) . '&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			echo '<br />';
		}
		echo '<br />';
		$tracks = $wpdb->get_results( $wpdb->prepare( "SELECT agent, count(*) as count FROM $wpdb->mp_tracks WHERE mail_id = %d GROUP BY agent ORDER BY agent;", $mail->id) );

		if ($tracks)
		{
			$total = 0;
			foreach($tracks as $track)
			{
				$agent[$track->agent] = $track->count;
				$total += $track->count;
			}
			foreach($agent as $k => $v)
			{
				$key = MailPress_tracking::get_os($k) . '</td><td>' . MailPress_tracking::get_browser($k) ;
				if (isset($agents[$key])) 	$agents[$key] += $v;
				else 					$agents[$key]  = $v;
			}
			arsort($agents);
			echo '<table>';
			foreach($agents as $k => $v)
			{
				echo '<tr><td>' . $k . '</td><td>' . sprintf("%01.2f %%",100 * $v/$total ) . '</td></tr>';
			}
			echo '</table>';
		}
	}
}
new MP_Tracking_module_m005(__('System info', MP_TXTDOM));