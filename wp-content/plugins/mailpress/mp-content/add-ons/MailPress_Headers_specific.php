<?php
if (class_exists('MailPress') && !class_exists('MailPress_Headers_specific'))
{
/*
Plugin Name: MailPress_Headers_specific
Plugin URI: http://swiftmailer.org/docs/headers
Description: This is just an add-on for MailPress to add specific headers in mail (sample).
Author: Andre Renaut
Version: 5.1
Author URI: http://www.mailpress.org
*/

class MailPress_Headers_specific
{
	function __construct()
	{
// prepare mail
		add_filter('MailPress_swift_message_headers',  	array(__CLASS__, 'swift_message_headers'), 8, 2);
	}

// prepare mail
	public static function swift_message_headers($message, $row)
	{
		if ($row->template == 'new_subscriber') return $message;

		$url = MP_Users::get_unsubscribe_url( (isset($row->mp_user_id)) ? MP_Users::get_key_by_email(MP_Users::get_email($row->mp_user_id)) : '{{_confkey}}' );
		$url = apply_filters('MailPress_header_url', $url, $row);

		global $wpdb;
		$headers = array(
					array(	'type' => Swift_Mime_Header::TYPE_TEXT , 
							'name' => 'List-Unsubscribe', 
							'value' => '<mailto:toto@toto.com>'
					),
					array(	'type' => Swift_Mime_Header::TYPE_TEXT , 
							'name' => 'List-Unsubscribe', 
							'value' => "<$url>"
					),
					array(	'type' => Swift_Mime_Header::TYPE_TEXT , 
							'name' => 'List-ID', 
							'value' => "mp_mail_id_{$wpdb->blogid}_{$row->id}"
					),
					array(	'type' => Swift_Mime_Header::TYPE_TEXT , 
							'name' => 'Precedence', 
							'value' => 'bulk'
					),
				);


		$_headers = $message->getHeaders();
		foreach ($headers as $header)
		{
			switch ($header['type'])
			{
				case Swift_Mime_Header::TYPE_TEXT :
					$_headers->addTextHeader($header['name'], $header['value']);
			  	break;
				case Swift_Mime_Header::TYPE_PARAMETERIZED :
					$_headers->addParameterizedHeader($header['name'], $header['value'], $header['parms']);
			  	break;
				case Swift_Mime_Header::TYPE_MAILBOX :
					$_headers->addMailboxHeader($header['name'], $header['value']);
			  	break;
				case Swift_Mime_Header::TYPE_DATE :
					$_headers->addDateHeader($header['name'], $header['value']);
			  	break;
				case Swift_Mime_Header::TYPE_ID :
					$_headers->addIdHeader($header['name'], $header['value']);
			  	break;
				case Swift_Mime_Header::TYPE_PATH :
					$_headers->addPathHeader($header['name'], $header['value']);
			  	break;
			}
		}

		return $message;
	}
}
new MailPress_Headers_specific();
}