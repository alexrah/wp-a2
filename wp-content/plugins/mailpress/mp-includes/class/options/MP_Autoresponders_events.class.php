<?php
class MP_Autoresponders_events extends MP_Options
{
	var $path = 'autoresponder/events';

	function __construct()
	{
		parent::__construct();
		do_action('MailPress_load_Autoresponders_events');
	}

	public static function get_all()
	{
		return apply_filters('MailPress_autoresponder_events_register', array());
	}
}