<?php
abstract class MP_autoload
{
	public static function autoload($class)
	{
		//Don't interfere with other autoloaders
		if (0 !== strpos($class, 'MP_')) return false;

		$path = MP_ABSPATH . "mp-includes/class/{$class}.class.php";
		if (!file_exists($path))
		{
			$path = MP_ABSPATH . "mp-includes/class/options/{$class}.class.php";
			if (!file_exists($path)) return false;
		}

		require_once $path;
	}
  
	public static function registerAutoload()
	{
		spl_autoload_register(array('MP_autoload', 'autoload'));
	}
}