<?php
/**
 * Bootstrap file for 
		1. setting some constants ( mostly path and dir ),
 		2. loading optional mailpress-config.php file in 'mailpress' folder or in its parent directory if any,
		3. setting some admin page and url constants,
		4. loading text domain file for gettext,
		5. set debug stuff if WP_DEBUG is true,
		6. class auto loader,
		7. mp_general, mp_subscriptions,
		8. loading pluggable functions

 * If the mailpress-config.php file is not found then default constant values apply.

**/

// 1.

/** text domain for gettext. */
define ('MP_TXTDOM', 'MailPress');

/** Absolute path to the MailPress directory. */
define ('MP_ABSPATH', 	dirname(__FILE__) . '/');

/** Folder name of MailPress plugin. */
define ('MP_FOLDER', 	basename( MP_ABSPATH ));

/** Relative path to the MailPress directory. */
define ('MP_PATH', 	PLUGINDIR . '/' . MP_FOLDER . '/' );

/** Paypal button */
define ('MP_Paypal', '<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick" /><input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----" /><input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" style="border:none;" name="submit" alt="PayPal - The safer, easier way to pay online!" /><img alt="" style="border:none;" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" /></form>' );

// 2.

/** Loading optional mailpress-config.php file in current directory or parent directory */
$mp_config = 'mailpress-config.php';
foreach (array(MP_ABSPATH . $mp_config, dirname(MP_ABSPATH) . '/' . $mp_config) as $mp_file)
{
	if ( !file_exists( $mp_file ) ) continue;
	require_once( $mp_file );
	break;
}

/** Folder name of MailPress 'mp-content'. */
defined('MP_CONTENT_FOLDER') 	or define ('MP_CONTENT_FOLDER', 	'mp-content');

/** Absolute path to the MailPress 'mp-content' folder. */
defined('MP_CONTENT_DIR')	or define ('MP_CONTENT_DIR', 		MP_ABSPATH . MP_CONTENT_FOLDER . '/');

/** Relative path to the MailPress 'mp-content' folder. */
defined('MP_PATH_CONTENT')	or define ('MP_PATH_CONTENT', 	MP_PATH    . MP_CONTENT_FOLDER . '/');

// 3.

/** for admin plugin pages */
define ('MailPress_page_mails', 	'mailpress_mails');
define ('MailPress_page_write', 	'mailpress_write');
define ('MailPress_page_edit', 	MailPress_page_mails . '&file=write');
define ('MailPress_page_revision', 	MailPress_page_mails . '&file=revision');
define ('MailPress_page_themes', 	'mailpress_themes');
define ('MailPress_page_settings', 	'mailpress_settings');
define ('MailPress_page_users', 	'mailpress_users');
define ('MailPress_page_user', 	MailPress_page_users . '&file=uzer');
define ('MailPress_page_addons', 	'mailpress_addons');

/** for admin plugin urls */
$mp_file = 'admin.php';
define ('MailPress_mails', 		$mp_file . '?page=' 	. MailPress_page_mails);
define ('MailPress_write', 		$mp_file . '?page=' 	. MailPress_page_write);
define ('MailPress_edit', 		$mp_file . '?page=' 	. MailPress_page_edit);
define ('MailPress_revision', 	$mp_file . '?page=' 	. MailPress_page_revision);
define ('MailPress_themes', 		$mp_file . '?page=' 	. MailPress_page_themes);
define ('MailPress_settings', 	'options-general.php' . '?page=' 	. MailPress_page_settings);
define ('MailPress_users', 		$mp_file . '?page=' 	. MailPress_page_users);
define ('MailPress_user', 		$mp_file . '?page=' 	. MailPress_page_user);
define ('MailPress_addons', 		'plugins.php' . '?page=' 	. MailPress_page_addons);

/** for ajax & actions */
define ('MP_Action_url', get_option('siteurl') . '/' . MP_PATH . 'mp-includes/action.php');
define ('MP_Action_home', get_option('home') . '/' . MP_PATH . 'mp-includes/action.php');

/** for contextual help */
define ('MP_Help_url', 'http://www.mailpress.org/wiki/');

// 4.

/** for gettext */
load_plugin_textdomain(MP_TXTDOM, false, MP_FOLDER . '/' . MP_CONTENT_FOLDER . '/' . 'languages');

// 5.

if ( defined('WP_DEBUG') && WP_DEBUG && !defined('MP_DEBUG_LOG') ) 
	define('MP_DEBUG_LOG', true);

// 6.

require_once(MP_ABSPATH . 'mp-includes/class/MP_autoload.class.php');
MP_autoload::registerAutoload();

// 7.

global $mp_general, $mp_subscriptions;
$mp_general  	= get_option('MailPress_general');
$mp_subscriptions = get_option('MailPress_subscriptions');

// 8. 

/** loading pluggable functions **/
//global $wp_version; 
if (isset($mp_general['wp_mail']))
	include (MP_ABSPATH . 'mp-includes/wp_pluggable.php');