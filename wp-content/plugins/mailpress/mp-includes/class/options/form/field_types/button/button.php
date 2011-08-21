<?php
class MP_Forms_field_type_button extends MP_Forms_field_type_abstract
{
	var $id 	= 'button';
	var $order	= 100;
	var $file	= __FILE__;

	function submitted($field)
	{
		if (!isset($_POST[$this->prefix][$field->form_id][$field->id]))
		{
			$field->submitted['value'] = false;
			$field->submitted['text']  = __('not selected', MP_TXTDOM);
			return $field;
		}
		return parent::submitted($field);
	}
}
new MP_Forms_field_type_button(__('Button', MP_TXTDOM));