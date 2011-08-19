<?php
class MP_Newsletters_schedulers extends MP_Options
{
	const bt = 130;

	var $path = 'newsletter/schedulers';

	public static function get_all()
	{
		return apply_filters('MailPress_newsletter_schedulers_register', array());
	}

	public static function schedule($event)
	{
		MailPress::no_abort_limit();
		$results = array();
		$error = false;

		$schedulers = self::get_all();

		global $mp_registered_newsletters;
		$nls = MP_Newsletters::get_active();

		$trace = self::header_report($event);

		foreach ($nls as $k => $v)
		{
			$newsletter = $mp_registered_newsletters[$k];
			if ( isset($newsletter['scheduler']['id']) && isset($schedulers[$newsletter['scheduler']['id']]) )
			{
				if (!$results[] = apply_filters('MailPress_newsletter_scheduler_' . $newsletter['scheduler']['id'] . '_schedule', $newsletter)) array_pop($results);
			}
			else
			{
				if ( !isset($newsletter['scheduler']['id']) )
				{
					if (!$error) self::sep_report($trace);
					self::message_report( $newsletter, 'no scheduler in newsletter (see xml file) ', $trace, true);
					$error = true;
				}
				if ( !isset($schedulers[$newsletter['scheduler']['id']]) )
				{
					if (!$error) self::sep_report($trace);
					self::message_report($newsletter, 'scheduler unknown ', $trace, true);
					$error = true;
				}
			}
		}

		if (!empty($results))
		{
			self::sep_report($trace);
			$results = array_reverse($results);
			uasort($results, create_function('$a, $b', 'return strcmp($a["timestamp"], $b["timestamp"]);'));
			foreach($results as $result) $trace->log($result['log']);
		}
		self::footer_report($trace);
	}

	public static function header_report($event)
	{
		$trace = new MP_Log('sched_proc', MP_ABSPATH, 'MP_Newsletter', false, 'newsletter');

		self::sep_report($trace);
		$bm = "Scheduling Newsletters    event : $event ";
		$trace->log('!' . str_repeat( ' ', 5) . $bm . str_repeat( ' ', self::bt - 5 - strlen($bm)) . '!');
		self::sep_report($trace);
		$bm = ' Newsletter id                  ! scheduler  ! processor  ! timestamp  ! time';
		$trace->log('!' . $bm . str_repeat( ' ', self::bt - strlen($bm)) . '!');

		return $trace;
	}

	public static function schedule_report($newsletter, $timestamp, $id)
	{
		$bm = ' ';
		$bm .= $newsletter['id']. str_repeat( ' ', 30 - strlen($newsletter['id'])) 	. ' ! ';
		$bm .= $id 			. str_repeat( ' ', 10 - strlen($id)) 			. ' ! ';
		$bm .= $newsletter['processor']['id'] 	. str_repeat( ' ', 10 - strlen($newsletter['processor']['id'])) 	. ' ! ';
		$bm .= $timestamp		. str_repeat( ' ', 10 - strlen($timestamp)) 		. ' ! ';
		$bm .= date_i18n( 'l jS \of F Y H:i', strtotime(get_date_from_gmt(gmdate('Y-m-d H:i:s', $timestamp))));

		return array('timestamp' => $timestamp, 'log' => '!' . $bm . str_repeat( ' ', self::bt - strlen($bm)) . '!');
	}

	public static function message_report($newsletter, $text, $trace, $error = false)
	{
		if ($error)
			$bm = ($newsletter) ? ' ' . $newsletter['id'] . str_repeat( ' ', 30 - strlen($newsletter['id'])) . ' ! ' : ' ';
		else
			$bm = ($newsletter) ? ' ' . $newsletter['id'] . str_repeat( ' ', 30 - strlen($newsletter['id'])) . ' ! ' : str_repeat( ' ', 31 ) . ' ! ';

		$bm .= $text;
		$trace->log('!' . $bm . str_repeat( ' ', self::bt - strlen($bm)) . '!');
	}

	public static function sep_report($trace)
	{
		$trace->log('!' . str_repeat( '-', self::bt) . '!');
	}

	public static function footer_report($trace)
	{
		self::sep_report($trace);
		$trace->end(true);
	}
}