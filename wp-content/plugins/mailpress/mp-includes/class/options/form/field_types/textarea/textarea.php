<?php
class MP_Forms_field_type_textarea extends MP_Forms_field_type_abstract
{
	var $id			= 'textarea';
	var $order 			= 20;
	var $file			= __FILE__;
	var $field_not_input 	= true;

	function submitted($field)
	{
		$value	= trim($_POST[$this->prefix][$field->form_id][$field->id]);

		$required 	= (isset($field->settings['controls']['required']) && $field->settings['controls']['required']);
		$empty 	= empty($value);

		if ($required && $empty)
		{
			$field->submitted['on_error'] = 1;
			return $field;
		}
		$field->submitted['value'] = $value;
		$field->submitted['text']  = apply_filters('the_content', stripslashes($value));
		return $field;
	}

	function attributes_filter($no_reset)
	{
		$this->field->settings['attributes']['tag_content'] = base64_decode($this->field->settings['attributes']['tag_content']);

		if (!$no_reset) return;

		$this->field->settings['attributes']['tag_content'] = trim(stripslashes($_POST[$this->prefix][$this->field->form_id][$this->field->id]));
		$this->attributes_filter_css();
	}
}
new MP_Forms_field_type_textarea(__('Multi-line Input', MP_TXTDOM));