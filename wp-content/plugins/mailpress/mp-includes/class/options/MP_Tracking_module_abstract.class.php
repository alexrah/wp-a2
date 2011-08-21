<?php
abstract class MP_Tracking_module_abstract
{
	function __construct($title)
	{
		$this->title = $title;
		$this->type  = basename(dirname($this->file));

		add_filter('MailPress_tracking_modules_register', 	array(&$this, 'register'), 8, 2);
		add_action('MailPress_tracking_add_meta_box', 		array(&$this, 'add_meta_box'), 8, 1);
	}

	function register($modules, $type)
	{
		if ($type != $this->type) return $modules;

		$modules[$this->id]['title'] = $this->title;
		if (isset($this->parms)) $modules[$this->id]['parms'] = $this->parms;
		return $modules;
	}

	function add_meta_box($screen)
	{
		add_meta_box('tracking' . $this->id . 'div', $this->title, array(&$this, 'meta_box'), $screen, $this->context, '');
	}
}