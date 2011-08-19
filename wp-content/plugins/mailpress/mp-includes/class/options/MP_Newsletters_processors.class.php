<?php
class MP_Newsletters_processors extends MP_Options
{
	const bt = 130;

	var $path = 'newsletter/processors';

	public static function get_all()
	{
		return apply_filters('MailPress_newsletter_processors_register', array());
	}

	public static function process($newsletter)
	{
		MailPress::no_abort_limit();

		$processors = self::get_all();

		$nls = MP_Newsletters::get_active();

		$trace = self::header_report($newsletter);
		self::sep_report($trace);

		if ( isset($newsletter['processor']['id']) && isset($processors[$newsletter['processor']['id']]) && isset($nls[$newsletter['id']]) )
		{
			do_action('MailPress_newsletter_processor_' . $newsletter['processor']['id'] . '_process', $newsletter, $trace);
		}
		else
		{
			$bm = ' ' . $newsletter['id'] 	. str_repeat( ' ', 30 - strlen($newsletter['id'])) 	. ' ! ';
			if ( !isset($newsletter['processor']['id']) )
				self::message_report($newsletter, 'no processor in newsletter (see xml file) ', $trace, true);
			elseif ( !isset($processors[$newsletter['processor']['id']]) )
				self::message_report($newsletter, 'unknown processor : ' . $newsletter['processor']['id'], $trace, true);
			elseif ( !isset($nls[$newsletter['id']]) )
				self::message_report($newsletter, 'newsletter not active : ' . $newsletter['id'], $trace, true);
		}
		self::footer_report($trace);
		unset($trace, $newsletter);
	}

	public static function send($newsletter, $trace = false, $report = true )
	{
		if (!isset($newsletter['query_posts']))
		{
			if ($trace) self::message_report(false, '>> empty query_posts : end of process <<', $trace);
			return;
		}

		self::message_report(($report) ? $newsletter : false, 'query_posts : ' . json_encode($newsletter['query_posts']), $trace);

		$rc = MP_Newsletters::send($newsletter, true, false, $trace);

		if ($trace)
		{
			$bm = "($rc) ";
			switch ( true )
			{
				case ( 0 === $rc ) :
					$bm .= 'no recipients';
				break;
				case (!$rc) :
					$bm .= 'a problem occured (further details in appropriate Mail log)';
				break;
				case ( 'npst' == $rc ) :
					$bm .= 'no posts for this newsletter';
				break;
				case ( 'noqp' == $rc ) :
					$bm .= 'newsletter[\'query_posts\'] not set (error in code ?)';
				break;
				default :
					$bm = "** Process successful ** (recipients : $rc)";
				break;
			}
			self::message_report(false, $bm, $trace);
		}
	}

	public static function header_report($newsletter)
	{
		$trace = new MP_Log('sched_proc', MP_ABSPATH, 'MP_Newsletter', false, 'newsletter');

		self::sep_report($trace);
		$bm = 'Processing Newsletter    ' . '  processor : ' . $newsletter['processor']['id'] . ' ' ;
		$trace->log('!' . str_repeat( ' ', 5) . $bm . str_repeat( ' ', self::bt - 5 - strlen($bm)) . '!');
		self::sep_report($trace);
		$bm = ' Newsletter id                  ! ';
		$trace->log('!' . $bm . str_repeat( ' ', self::bt - strlen($bm)) . '!');

		return $trace;
	}

	public static function message_report($newsletter, $text, $trace, $error = false) { MP_Newsletters_schedulers::message_report($newsletter, $text, $trace, $error); }
	public static function sep_report($trace) { MP_Newsletters_schedulers::sep_report($trace); }
	public static function footer_report($trace) { MP_Newsletters_schedulers::footer_report($trace); }
}