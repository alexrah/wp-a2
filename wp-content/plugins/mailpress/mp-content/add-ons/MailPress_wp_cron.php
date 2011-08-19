<?php
if (class_exists('MailPress') && !class_exists('MailPress_wp_cron'))
{
/*
Plugin Name: MailPress_wp_cron
Plugin URI: http://www.mailpress.org/wiki/index.php?title=Add_ons:Wp_cron
Description: This is just an add-on for MailPress to manage wp_crons (based on wp-cron)
Author: Andre Renaut
Version: 5.1
Author URI: http://www.mailpress.org
*/

// 3.

/** for admin plugin pages */
define ('MailPress_page_wp_cron', 	'mailpress_wp_cron');

/** for admin plugin urls */
$mp_file = 'tools.php';
define ('MailPress_wp_cron', $mp_file . '?page=' . MailPress_page_wp_cron);

class MailPress_wp_cron
{
	function __construct()
	{
// for wp admin
		if (is_admin())
		{
		// for role & capabilities
			add_filter('MailPress_capabilities', 		array('MailPress_wp_cron', 'capabilities'), 1, 1);
		// for load admin page
			add_filter('MailPress_load_admin_page', 		array('MailPress_wp_cron', 'load_admin_page'), 10, 1);
		}
	}

////  Admin  ////

// for role & capabilities
	public static function capabilities($capabilities)
	{
		$capabilities['MailPress_manage_wp_cron'] = array(	'name'	=> __('Wp_cron', MP_TXTDOM),
											'group'	=> 'admin',
											'menu'	=> 99,

											'parent'	=> 'tools.php',
											'page_title'=> __('MailPress wp_cron', MP_TXTDOM),
											'menu_title'=> __('Wp_cron', MP_TXTDOM),
											'page'	=> MailPress_page_wp_cron,
											'func'	=> array('MP_AdminPage', 'body')
									);
		return $capabilities;
	}

// for load admin page
	public static function load_admin_page($hub)
	{
		$hub[MailPress_page_wp_cron] = 'wp_cron';
		return $hub;
	}
}
new MailPress_wp_cron();
}