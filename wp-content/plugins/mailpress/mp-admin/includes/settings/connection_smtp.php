<?php
$mp_general['tab'] = 'connection_smtp';

$connection_smtp	= stripslashes_deep($_POST['connection_smtp']);

if ('custom' == $connection_smtp['port']) $connection_smtp ['port'] = $connection_smtp['customport'];
unset($connection_smtp['customport']);

switch (true)
{
	case ( empty($connection_smtp['server'] ) ) :
		$serverclass = true;
		$message = __('field should not be empty', MP_TXTDOM); $no_error = false;
	break;
	case ( empty($connection_smtp['username'] ) ) :
		$usernameclass = true;
		$message = __('field should not be empty', MP_TXTDOM); $no_error = false;
	break;
	case ( (isset($connection_smtp['smtp-auth']) && ('@PopB4Smtp' == $connection_smtp['smtp-auth'])) && (empty($connection_smtp['pophost'])) ) : 
		$pophostclass = true;
		$message = __('field should not be empty', MP_TXTDOM); $no_error = false;
	break;
	default :
		update_option('MailPress_smtp_config', $connection_smtp);
		update_option(MailPress::option_name_general, $mp_general);
		$message = __('SMTP settings saved, Test it !!', MP_TXTDOM);
	break;
}