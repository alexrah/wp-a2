<?php
abstract class MP_abstract
{

////	email	////

	public static function is_email($email)
	{
		if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", strtolower($email))) return true;
		return false;
	}

//// image ////

	public static function is_image($file)
	{
		return (in_array(substr(strtolower(strrchr(strtolower($file), '.')), 1), self::ext_image()));
	}

	public static function ext_image()
	{
		return array('jpg', 'jpeg', 'png', 'gif', 'tif', 'bmp');
	}

//// url ////

	public static function url($url, $url_parms = array(), $wpnonce = false)
	{
		$url = add_query_arg(array_map ( 'urlencode', $url_parms), $url);
		return ($wpnonce) ? wp_nonce_url( $url, $wpnonce ) : $url;
	}

//// plugin/add-on ////

	public static function plugin_links($links, $file, $basename, $tab)
	{
		if ($basename != $file) return $links;

		$settings_link = "<a href='" . MailPress_settings . "#fragment-$tab'>" . __('Settings') . '</a>';
		array_unshift ($links, $settings_link);
		return $links;
	}

//// class loaders ////

	public static function require_class($classname, $path = false)
	{
		/* deprecated */
		if (!$path) return;
		if (class_exists('MP_' . $classname)) return;
		$file = "{$path}MP_{$classname}.class.php";
		if (is_file($file)) require_once($file);
	}

//// form ////

	public static function select_option($list, $selected, $echo = true)
	{
		$x = '';
		foreach( $list as $value => $label )
		{
			$_selected = (!is_array($selected)) ? $selected : ( (in_array($value, $selected)) ? $value : null );
			$x .= "<option " . self::selected( (string) $value, (string) $_selected, false, false ) . " value=\"$value\">$label</option>";
		}
		if (!$echo) return "\n$x\n";
		echo "\n$x\n";
	}

	public static function select_number($start, $max, $selected, $tick = 1, $echo = true)
	{
		$x = '';
		while ($start <= $max)
		{
			if (intval ($start/$tick) == $start/$tick ) 
				$x .= "<option " . self::selected( (string) $start, (string) $selected, false, false ) . " value='$start'>$start</option>";
			$start++;
		}
		if (!$echo) return "\n$x\n";
		echo "\n$x\n";
	}

	public static function selected( $selected, $current = true, $echo = true) 
	{
		return self::__checked_selected_helper( $selected, $current, $echo, 'selected' );
	}

	public static function __checked_selected_helper( $helper, $current, $echo, $type) 
	{
		$result = ( $helper == $current) ? " $type='$type'" : '';
		if ($echo) echo $result;
		return $result;
	}

//// functions ////

	public static function mp_redirect($r)
	{
		if (defined('MP_DEBUG_LOG') && !defined('MP_DEBUG_LOG_STOP')) { global $mp_debug_log; if (isset($mp_debug_log)) $mp_debug_log->log(" mp_redirect : >> $r << "); $mp_debug_log->end(true); define ('MP_DEBUG_LOG_STOP', true);}
		wp_redirect($r);
		self::mp_die();
	}

	public static function mp_die($r = true)
	{
		if (defined('MP_DEBUG_LOG') && !defined('MP_DEBUG_LOG_STOP')) { global $mp_debug_log; if (isset($mp_debug_log)) $mp_debug_log->log(" mp_die : >> $r << "); $mp_debug_log->end(true); define ('MP_DEBUG_LOG_STOP', true);}
		die($r);
	}

	public static function print_scripts_l10n_val($val0, $before = "")
	{
		if (is_array($val0))
		{
			$eol = "\t\t";
			$text =  "{\n\t$before";
			foreach($val0 as $var => $val)
			{
				$text .=  "$eol$var: " . self::print_scripts_l10n_val($val, "\t" . $before );
				$eol = ", \n$before\t\t\t";
			}
			$text .= "\n\t\t$before}";
		}
		else
		{
			$quot = (stripos($val0, '"') === false) ? '"' : "'";
			$text = "$quot$val0$quot";
		}
		return $text;
	}
}