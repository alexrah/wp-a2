<?php
if (class_exists('MailPress_newsletter') && !class_exists('MailPress_newsletter_categories') )
{
/*
Plugin Name: MailPress_newsletter_categories
Plugin URI: http://www.mailpress.org/wiki/index.php?title=Add_ons:Newsletter_categories
Description: This is just an add-on for MailPress to manage newsletters per categories
Author: Andre Renaut
Version: 5.1
Author URI: http://www.mailpress.org
*/

class MailPress_newsletter_categories
{
	function __construct() 
	{
// for plugin
		add_action('MailPress_register_newsletter',	array(__CLASS__, 'register'));
// for wp admin
		if (is_admin())
		{
		// install
			register_activation_hook(  plugin_basename(__FILE__), array(__CLASS__, 'install'));
			register_deactivation_hook(plugin_basename(__FILE__), array(__CLASS__, 'uninstall'));
		// for link on plugin page
			add_filter('plugin_action_links', 		array(__CLASS__, 'plugin_action_links'), 10, 2 );
		}
	}

// for plugin
	public static function register() 
	{
		if ( function_exists( 'create_initial_taxonomies' ) ) create_initial_taxonomies();
		$args = array('hierarchical' => false, 'depth'=>false, 'echo'=>false, 'get'=>'all');
		$categories = get_categories($args);

		$root  = MP_CONTENT_DIR . 'advanced/newsletters';
		$root  = apply_filters('MailPress_advanced_newsletters_root', $root);
		$root .= '/categories';
		$files = array('categories');

   		$dir  = @opendir($root);
		if ($dir) while ( ($file = readdir($dir)) !== false ) if (preg_match('/category-[0-9]*\.xml/', $file)) $files[] = substr($file, 0, -4);
		if ($dir) @closedir($dir);

		$xml = '';
		foreach($files as $file)
		{
			$fullpath = "$root/$file.xml";
			if (!is_file($fullpath)) continue;

	            if ('categories' == $file)
	            {
				foreach ($categories as $category)
				{
					if ($category->category_parent) continue;
					ob_start();
						include($fullpath);
						$xml .= trim(ob_get_contents());
					ob_end_clean();
				}
	            }
	            else
	            {
				ob_start();
					include($fullpath);
					$xml .= trim(ob_get_contents());
				ob_end_clean();
      	      }
		}
		$xml = '<?xml version="1.0" encoding="UTF-8"?><newsletters>' . $xml . '</newsletters>';
		$newsletters = new MP_Xml($xml);
		foreach($newsletters->object->children as $newsletter) MP_Newsletters::register(MailPress_newsletter::convert($newsletter));
	}

////  ADMIN  ////
////  ADMIN  ////
////  ADMIN  ////
////  ADMIN  ////

// install
	public static function install() 
	{
		$now4cron = current_time('timestamp', 'gmt');
		wp_schedule_single_event($now4cron - 1, 'mp_schedule_newsletters', array('args' => array('event' => 'Install newsletter_categories' )));
	}

	public static function uninstall() 
	{
		MailPress_newsletter::unschedule_hook('mp_process_newsletter');

		$now4cron = current_time('timestamp', 'gmt');
		wp_schedule_single_event($now4cron + 1, 'mp_schedule_newsletters', array('args' => array('event' => 'Uninstall newsletter_categories' )));
	}

// for link on plugin page
	public static function plugin_action_links($links, $file)
	{
		return MailPress::plugin_links($links, $file, plugin_basename(__FILE__), 'subscriptions');
	}
}
new MailPress_newsletter_categories();
}