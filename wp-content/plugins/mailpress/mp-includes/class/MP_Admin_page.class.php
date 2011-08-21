<?php
abstract class MP_Admin_page extends MP_abstract
{
	function __construct()
	{
		if ( !current_user_can(MP_AdminPage::capability) ) 
			wp_die(__('You do not have sufficient permissions to access this page.'));

		add_action('admin_init',      		array('MP_AdminPage', 'redirect'));
		add_action('admin_init',      		array('MP_AdminPage', 'title'));

		add_action('admin_head',      		array('MP_AdminPage', 'screen_meta'));
		add_filter('screen_meta_screen', 		array('MP_AdminPage', 'screen_meta_screen'));
		add_filter('current_screen', 			array('MP_AdminPage', 'current_screen'), 8, 1);
		add_filter('contextual_help', 		array('MP_AdminPage', 'contextual_help'), 8, 1);

		add_action('admin_print_styles', 		array('MP_AdminPage', 'print_styles'));
		add_action('admin_print_scripts' , 		array('MP_AdminPage', 'print_header_scripts'));
		add_action('admin_print_footer_scripts' , array('MP_AdminPage', 'print_footer_scripts'));

		add_action('wp_print_scripts', 		array('MP_AdminPage', 'deregister_scripts'), 100);
		add_action('wp_print_footer_scripts', 	array('MP_AdminPage', 'deregister_scripts'), 100);
	}

////  Redirect  ////

	public static function redirect() {}

////  Title  ////

	public static function title() {}

//// Screen Options ////

	public static function screen_meta() 
	{
		$help	= sprintf(__('<a href="%1$s" target="_blank">Documentation</a>', MP_TXTDOM), (MP_AdminPage::help_url) ? MP_AdminPage::help_url : MP_Help_url );
		$help	.= '<br />' . sprintf(__('<a href="%1$s" target="_blank">Support Forum</a>', MP_TXTDOM), 'http://groups.google.com/group/mailpress');
		add_contextual_help(MP_AdminPage::screen, $help);
	}

	public static function screen_meta_screen()
	{
		return MP_AdminPage::screen;
	}

	public static function current_screen($current_screen)
	{
		$current_screen->id = MP_AdminPage::screen;
		$current_screen->post_type = '';
		return $current_screen;
	}

	public static function contextual_help($x) 
	{
		global $_wp_contextual_help, $title;

		if ( !isset($_wp_contextual_help) )	$_wp_contextual_help = array();

		if ( !isset($_wp_contextual_help[MP_AdminPage::screen]) ) return $x;

		$contextual_help = '';
		$contextual_help .= "<div style='float:right;padding:10px 15px 0;'>" . MP_Paypal . "</div>";
		$contextual_help .= ( !empty($title) ) ? '<h5>' . sprintf(__('Get help with "%s"'), $title) . '</h5>' : '<h5>' . __('Get help with this page') . '</h5>';

		$contextual_help .= '<div class="metabox-prefs">';
		$contextual_help .= $_wp_contextual_help[MP_AdminPage::screen];
		$contextual_help .= "</div>\n";
		return $contextual_help;
	}

//// Styles ////

	public static function print_styles($styles = array()) 
	{
		$styles = apply_filters('MailPress_styles', $styles, MP_AdminPage::screen);
		if (is_array($styles)) foreach ($styles as $style) wp_enqueue_style($style);
	}

//// Scripts ////

	public static function print_header_scripts() { MP_AdminPage::print_scripts(array(), false); }
	public static function print_footer_scripts() { MP_AdminPage::print_scripts(array(), true); }

	public static function print_scripts($scripts = array()) 
	{
		$scripts = apply_filters('MailPress_scripts', $scripts, MP_AdminPage::screen);
		if (is_array($scripts)) foreach ($scripts as $script)	wp_enqueue_script($script);
	}

	public static function deregister_scripts()
	{
		$root = MP_CONTENT_DIR . 'advanced/scripts';
		$root = apply_filters('MailPress_advanced_scripts_root', $root);
		$file	= "$root/deregister.xml";

		$y = '';

		if (file_exists($file))
		{
			$x = file_get_contents($file);
			if ($xml = simplexml_load_string($x))
			{
				foreach ($xml->script as $script)
				{
					wp_deregister_script((string) $script);
					$y .= (!empty($y)) ? ", $script" : $script;
				}
			}
			echo "\n<!-- MailPress_deregister_scripts : $y -->\n";
		}
	}

////  Body  ////

	public static function body() { include(MP_ABSPATH . 'mp-admin/includes/' . basename(MP_AdminPage::file)); }

//// Html ////

	public static function get_url_parms($parms = array('mode', 'status', 's', 'apage', 'author', 'startwith'))
	{
		$url_parms = array();
		foreach ($parms as $parm) if (isset($_REQUEST[$parm]))
		{
			if (isset($_REQUEST[$parm]))
			{
				$url_parms[$parm] = trim(stripslashes($_REQUEST[$parm]));
				switch ($parm)
				{
					case 'startwith' :
						if (-1 == $url_parms[$parm]) 	unset($url_parms[$parm]);
					break;
					case 'apage' :
						if (1 >= $url_parms[$parm]) 	unset($url_parms[$parm]);
					case 's' :
					case 'author' :
					case 'mailinglist' :
					case 'newsletter' :
						if (empty($url_parms[$parm])) unset($url_parms[$parm]);
					break;
				}
			}
		}
		return $url_parms;
	}

	public static function post_url_parms($url_parms, $parms = array('mode', 'status', 's', 'apage', 'author'))
	{
		foreach ($parms as $key)
			if (isset($url_parms[$key]))
				echo "<input type='hidden' name='$key' value=\"" . $url_parms[$key] . "\" />\n";
	}

	public static function message($s, $b = true)
	{
		if ( $b ) 	echo "<div id='message' class='updated fade'><p>$s</p></div>";
	 	else 		echo "<div id='message' class='error'><p>$s</p></div>";
	}
}