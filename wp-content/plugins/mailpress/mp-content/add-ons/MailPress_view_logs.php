<?php
if ( (class_exists('MailPress')) && !class_exists('MailPress_view_logs') && (is_admin()) )
{
/*
Plugin Name: MailPress_view_logs
Plugin URI: http://www.mailpress.org/wiki/index.php?title=Add_ons:View_logs
Description: This is just an add-on for MailPress to view logs
Author: Andre Renaut
Version: 5.1
Author URI: http://www.mailpress.org
*/

// 3.

/** for admin plugin pages */
define ('MailPress_page_view_logs', 	'mailpress_viewlogs');
define ('MailPress_page_view_log', 		MailPress_page_view_logs . '&file=view_log');

/** for admin plugin urls */
$mp_file = 'admin.php';
define ('MailPress_view_logs', 	$mp_file . '?page=' . MailPress_page_view_logs);
define ('MailPress_view_log', 	$mp_file . '?page=' . MailPress_page_view_log);

class MailPress_view_logs
{
	function __construct()
	{
	// for role & capabilities
		add_filter('MailPress_capabilities',  		array(__CLASS__, 'capabilities'), 1, 1);
	// for load admin page
		add_filter('MailPress_load_admin_page', 		array(__CLASS__, 'load_admin_page'), 10, 1);
	// for autorefresh
		add_filter('MailPress_autorefresh_files_js',	array(__CLASS__, 'autorefresh_js'), 8, 1);
	}

////  Admin  ////

// for role & capabilities
	public static function capabilities($capabilities)
	{
		$capabilities['MailPress_view_logs'] = array(	'name'	=> __('Logs', MP_TXTDOM),
										'group'	=> 'admin',
										'menu'	=> 99,

										'parent'	=> false,
										'page_title'=> __('MailPress Logs', MP_TXTDOM),
										'menu_title'=> __('Logs', MP_TXTDOM),
										'page'	=> MailPress_page_view_logs,
										'func'	=> array('MP_AdminPage', 'body')
									);
		return $capabilities;
	}

// for load admin page
	public static function load_admin_page($hub)
	{
		$hub[MailPress_page_view_logs] = 'view_logs';
		$hub[MailPress_page_view_log]  = 'view_log';
		return $hub;
	}

// for autorefresh
	public static function autorefresh_js($scripts)
	{
		return MP_AutoRefresh_js::register_scripts($scripts, true);
	}
}
new MailPress_view_logs();
}