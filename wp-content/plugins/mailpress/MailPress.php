<?php
/*
Plugin Name: MailPress
Plugin URI: http://www.mailpress.org
Description: The WordPress mailing platform. <b>(do not use automatic upgrade!)</b>
Author: Andre Renaut
Author URI: http://www.mailpress.org
Requires at least: 3.1
Tested up to: 3.2
Version: 5.1
*/

require_once('mp-load.php');

class MailPress extends MP_abstract
{
	const option_name_general = 'MailPress_general';
	const option_name_test    = 'MailPress_test';
	const option_name_logs    = 'MailPress_logs';

	const option_name_subscriptions = 'MailPress_subscriptions';

	function __construct() 
	{
		require_once (ABSPATH . 'wp-admin/includes/plugin.php');
		$plugin_data = get_plugin_data( MP_ABSPATH . 'MailPress.php' );
		define ('MP_Version', 	$plugin_data['Version']);

		if (defined('MP_DEBUG_LOG')) { global $mp_debug_log; $mp_debug_log = new MP_Log('debug', MP_ABSPATH, 'MailPress', false, 'general'); }

// for mysql
		global $wpdb;
		$wpdb->mp_mails     = $wpdb->prefix . 'mailpress_mails';
		$wpdb->mp_mailmeta  = $wpdb->prefix . 'mailpress_mailmeta';
		$wpdb->mp_users     = $wpdb->prefix . 'mailpress_users';
		$wpdb->mp_usermeta  = $wpdb->prefix . 'mailpress_usermeta';
		$wpdb->mp_stats     = $wpdb->prefix . 'mailpress_stats';

// for add-ons
		add_action('plugins_loaded', 	array(__CLASS__, 'plugins_loaded'));
// for widget
		add_action('widgets_init', 	array(__CLASS__, 'widgets_init'));
// for shutdown
		add_action('shutdown', 		array(__CLASS__, 'shutdown'), 999);
// for shortcode
		add_shortcode('mailpress', 	array(__CLASS__, 'shortcode'));
// for scheduled draft
		add_action('mp_process_send_draft',	array(__CLASS__, 'process'));

// for wp admin
		if (is_admin())
		{
		// for install
			register_activation_hook(plugin_basename(__FILE__), 	array(__CLASS__, 'install'));
		// for plugin update
			add_action( 'in_plugin_update_message-' . MP_FOLDER . '/' . __FILE__,  	array(__CLASS__, 'in_plugin_update_message') );
		// for link on plugin page
			add_filter('plugin_action_links', 				array(__CLASS__, 'plugin_action_links'), 10, 2 );
		// for favorite action
			add_filter('favorite_actions', 				array(__CLASS__, 'favorite_actions'), 8, 1);
		// for menu
			add_action('admin_menu', 					array(__CLASS__, 'menu'), 8, 1);

		// load admin page
			add_action('init', 						array(__CLASS__, 'load_admin_page'));
		}

		do_action('MailPress_init');
	}

////	Add-ons   ////

	public static function plugins_loaded() {	MP_Addons::load_all(); }

////  Widget ////

	public static function widgets_init()   {	register_widget('MP_Widget'); }

////	Shutdown   ////

	public static function shutdown() 
	{
		if (defined('MP_DEBUG_LOG')) { global $mp_debug_log; $mp_debug_log->end(true); }
	}

////  Shortcode  ////

	public static function shortcode($options=false)
	{
		$options['widget_id'] = 'sc';

		ob_start();
			self::form($options);
			$x = ob_get_contents();
		ob_end_clean();
		return $x; 
	}

////  SCHEDULED DRAFT  ////

	public static function process($args)
	{
		return MP_Mails::send_draft($args);
	}

////  Stats ////

	public static function update_stats($stype, $slib, $scount) 
	{
		global $wpdb;
		$sdate   = date('Y-m-d');
		$results = $wpdb->query( $wpdb->prepare("UPDATE $wpdb->mp_stats SET scount=scount+$scount WHERE sdate = %s AND stype = %s AND slib = %s;", $sdate, $stype, $slib) );
		if (!$results)	$wpdb->insert($wpdb->mp_stats, compact('sdate', 'stype', 'slib', 'scount'));
	}

////	user	////

	public static function get_wp_user_id() 
	{
		$user = wp_get_current_user();
		return $user->ID;
	}

	public static function get_wp_user_email() 
	{
		switch (true)
		{
			case (isset($_POST['email'])) :
				return $_POST['email'];
			break;
			default :
				$u = self::get_wp_user_id();
				if ($u)
				{
					$user = get_userdata($u);
					return $user->user_email;
				}
				else
				{
					if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) return $_COOKIE['comment_author_email_' . COOKIEHASH];
				}
			break;
		}
		return '';
	}

//// stuff ////
	public static function no_abort_limit()
	{
		if (function_exists('ignore_user_abort')) 	ignore_user_abort(1);
		if (function_exists('set_time_limit')) 		if( !in_array(ini_get('safe_mode'),array('1', 'On')) ) set_time_limit(0);
	}

	public static function lock($lock, $timeout = 60)
	{
/* not working, retrieves always true 
		global $wpdb;
		$x = $wpdb->query("SELECT GET_LOCK('$lock', 1);");
		return (!empty($x));
*/
		return apply_filters('MailPress_lock', true, $lock, $timeout);
	}

////  ADMIN  ////
////  ADMIN  ////
////  ADMIN  ////
////  ADMIN  ////

////  Install  ////

	public static function install() 
	{
		global $wp_version; 

		$min_ver_php = '5.2.0';
		$min_ver_wp  = '3.1';
		$m = array();

		if (version_compare(PHP_VERSION, $min_ver_php, '<')) 	$m[] = sprintf(__('Your %1$s version is \'%2$s\', at least version \'%3$s\' required.', MP_TXTDOM), __('PHP'), PHP_VERSION, $min_ver_php );
		if (version_compare($wp_version, $min_ver_wp , '<'))	$m[] = sprintf(__('Your %1$s version is \'%2$s\', at least version \'%3$s\' required.', MP_TXTDOM), __('WordPress'), $wp_version , $min_ver_wp );
		if (!is_writable(MP_ABSPATH . 'tmp'))			$m[] = sprintf(__('The directory \'%1$s\' is not writable.', MP_TXTDOM), MP_ABSPATH . 'tmp');
		if (!extension_loaded('simplexml'))				$m[] = __("Default php extension 'simplexml' not loaded.", MP_TXTDOM);

		if (!empty($m))
		{
			$err  = sprintf(__('<b>Sorry, but you can\'t run this plugin : %1$s. </b>', MP_TXTDOM), $_GET['plugin']);
			$err .= '<ol><li>' . implode('</li><li>', $m) . '</li></ol>';

			if (isset($_GET['plugin'])) deactivate_plugins($_GET['plugin']);	
			trigger_error($err, E_USER_ERROR);
			return false;
		}
		include (MP_ABSPATH . 'mp-admin/includes/install/mailpress.php');
	}

	public static function in_plugin_update_message()
	{
?>
		<p style="color:red;margin:3px 0 0 0;border-top:1px solid #ddd;padding-top:3px">
			<?php printf(__( 'IMPORTANT: <a href="%$1s">Read this before attempting to update MailPress</a>', MP_TXTDOM), 'http://www.mailpress.org/wiki/index.php?title=Manual:MailPress:Upgrading'); ?>
		</p>
<?php
	}

////  Settings  ////

	public static function plugin_action_links($links, $file)
	{
		if (plugin_basename(__FILE__) != $file) return $links;

		$addons_link = "<a href='" . MailPress_addons . "' title='" . __('Manage MailPress add-ons', MP_TXTDOM) . "'>" . __('Add-ons', MP_TXTDOM) . '</a>';
		array_unshift ($links, $addons_link);

		return self::plugin_links($links, $file, plugin_basename(__FILE__), '0');
	}

////  Favorite action  ////

	public static function favorite_actions($actions) 
	{
		$actions[MailPress_write] = array(__('New Mail', MP_TXTDOM), 'MailPress_edit_mails');
		return $actions;
	}

////  Roles and capabilities  ////

	public static function roles_and_capabilities()
	{
		$role = get_role('administrator');
		foreach (self::capabilities() as $capability => $v) $role->add_cap($capability);
		do_action('MailPress_roles_and_capabilities');
	}

	public static function capabilities()
	{
		include (MP_ABSPATH . 'mp-admin/includes/capabilities/capabilities.php');
		return apply_filters('MailPress_capabilities', $capabilities);
	}

	public static function capability_groups()
	{
		return apply_filters('MailPress_capability_groups', array('admin' => __('Admin', MP_TXTDOM), 'mails' => __('Mails', MP_TXTDOM), 'users' => __('Users', MP_TXTDOM)));
	}

////	Menu	////

	public static function menu() 
	{
		$menus = array();

		foreach (self::capabilities() as $capability => $datas)
		{
			if (isset($datas['menu']) && $datas['menu'] && current_user_can($capability))
			{
				$datas['capability'] 	= $capability;
				$menus[]			= $datas;
			}
		}
		$count = count($menus);
		if (0 == $count) return;

		uasort($menus, create_function('$a, $b', 'return strcmp($a["menu"], $b["menu"]);'));

		$first = true;
		foreach ($menus as $menu)
		{
			if (!$menu['parent'])
			{
				if ($first)
				{
					$toplevel = $menu['page'];
					add_menu_page('', __('Mails', MP_TXTDOM), $menu['capability'], $menu['page'], $menu['func'], 'div');
				}
				$first = false;
			}

			$parent = ($menu['parent']) ? $menu['parent'] : $toplevel;
			add_submenu_page( $parent, $menu['page_title'], $menu['menu_title'], $menu['capability'], $menu['page'], $menu['func']);

			if ($menu['page'] == MailPress_page_mails)
			{
				add_submenu_page($toplevel, __('Add New Mail', MP_TXTDOM), '&nbsp;' . __('Add New'), 'MailPress_edit_mails', MailPress_page_write, array('MP_AdminPage', 'body'));
			}
		}
	}

////  Load admin page  ////

	public static function get_page()
	{
		if (!isset($_GET['page'])) return false;
		$page = $_GET['page'];
		if (isset($_GET['file'])) $page .= '&file=' . $_GET['file'];
		return $page;
	}

	public static function load_admin_page()
	{
		global $mp_general;

// for roles & capabilities
		self::roles_and_capabilities();

// for dashboard
		if ( isset($mp_general['dashboard']) && current_user_can('MailPress_edit_dashboard') )
			add_filter('wp_dashboard_setup', 	array(__CLASS__, 'wp_dashboard_setup'));

// for global css
		$pathcss		= MP_ABSPATH . 'mp-admin/css/colors_' . get_user_option('admin_color') . '.css';
		$css_url		= '/' . MP_PATH . 'mp-admin/css/colors_' . get_user_option('admin_color') . '.css';
		$css_url_default 	= '/' . MP_PATH . 'mp-admin/css/colors_fresh.css';
		$css_url		= (is_file($pathcss)) ? $css_url : $css_url_default;
		wp_register_style ( 'MailPress_colors', 	$css_url);
		wp_enqueue_style  ( 'MailPress_colors' );

// for specific mailpress page
		$admin_page = self::get_page();
		if (!$admin_page) return;

		$hub = array (	MailPress_page_mails 	=> 'mails', 
					MailPress_page_write 	=> 'write', 
					MailPress_page_edit 	=> 'write', 
					MailPress_page_revision => 'revision', 
					MailPress_page_themes 	=> 'themes', 
					MailPress_page_settings => 'settings', 
					MailPress_page_users 	=> 'users', 
					MailPress_page_user 	=> 'user',
					MailPress_page_addons 	=> 'addons'
		);

		$hub = apply_filters('MailPress_load_admin_page', $hub);

		if (isset($hub[$admin_page])) require_once(MP_ABSPATH . 'mp-admin/' . $hub[$admin_page] . '.php');

		if (class_exists('MP_AdminPage')) new MP_AdminPage();
	}

////	Dashboard	////

	public static function wp_dashboard_setup() { new MP_Dashboard_widgets(); }

////	Subscription form	////

	public static function form($options = array())
	{
		static $_widget_id = 0;

		$options['widget_id'] = (isset($options['widget_id'])) ?  $options['widget_id'] . '_' . $_widget_id : 'mf_' . $_widget_id;

		MP_Widget::widget_form($options);

		$_widget_id++;
	}

////	THE MAIL

	public static function mail($args)
	{
		$x = new MP_Mail();
		return $x->send($args);
	}
}
new MailPress();