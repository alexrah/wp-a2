<?php
class MP_Tracking_modules extends MP_Options
{
	var $path = 'tracking/';

	function __construct($type, $settings = false)
	{
		if ($settings === false)
		{
			$settings = get_option(MailPress_tracking::option_name);
			if (!is_array($settings)) $settings = array();
			$this->includes = $settings;
		}

		$this->path .= $type;
		parent::__construct();
	}

	function get_all($type)
	{
		return apply_filters('MailPress_tracking_modules_register', array(), $type);
	}
}