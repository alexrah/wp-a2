<?php
if (class_exists('MailPress') && !class_exists('MailPress_connection_php_mail') )
{
/*
Plugin Name: MailPress_connection_php_mail 
Plugin URI: http://www.mailpress.org/wiki/index.php?title=Add_ons:Phpmail
Description: This is just an add-on for MailPress to use native php mail connection.
Author: Andre Renaut
Version: 5.1
Author URI: http://www.mailpress.org
*/

class MailPress_connection_php_mail
{
	const option_name = 'MailPress_connection_phpmail';

	function __construct()
	{
// for connection type
		add_filter('MailPress_Swift_Connection_type', 		array(__CLASS__, 'Swift_Connection_type'), 8, 1);

// for connection 
		add_filter('MailPress_Swift_Connection_PHP_MAIL', 	array(__CLASS__, 'connect'), 8, 1);

// for wp admin
		if (is_admin())
		{
		// for link on plugin page
			add_filter('plugin_action_links', 			array(__CLASS__, 'plugin_action_links'), 10, 2 );
		}

	}

////  Connection type & settings  ////

	public static function Swift_Connection_type($x)
	{
		return 'PHP_MAIL';
	}

////  Connection  ////

	public static function connect($x)
	{
//		if ( ini_get('safe_mode') ) return Swift_MailTransport::newInstance();

		$php_mail_settings = get_option(self::option_name);
		$addparm = $php_mail_settings['addparm'];

		$conn = (empty($addparm)) ? Swift_MailTransport::newInstance() : Swift_MailTransport::newInstance($addparm);

		return $conn;
	}

////  ADMIN  ////
////  ADMIN  ////
////  ADMIN  ////
////  ADMIN  ////

// for link on plugin page
	public static function plugin_action_links($links, $file)
	{
		return MailPress::plugin_links($links, $file, plugin_basename(__FILE__), 'connection_php_mail');
	}
}
new MailPress_connection_php_mail();
}