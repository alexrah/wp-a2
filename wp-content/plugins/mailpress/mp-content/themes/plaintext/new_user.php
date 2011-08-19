<?php
/*
Template Name: new_user
*/
$user = $this->args->advanced->user;

if (isset($this->args->advanced->admin))
{
	$_the_title = "New User";

	$_the_content  = sprintf(__('Username: %s'), stripslashes($user->user_login) );
	$_the_content .= "\r\n";
	$_the_content .= sprintf(__('E-mail: %s'),   stripslashes($user->user_email) );
	$_the_content .= "\r\n\r\n";
}
else
{
	$_the_title = "Welcome !";

	$_the_content  = sprintf(__('Username: %s'), stripslashes($user->user_login) );
	$_the_content .= "\r\n";
	$_the_content .= sprintf(__('Password: %s'), $user->plaintext_pass ) ;
	$_the_content .= "\r\n\r\n";

	$_the_actions 	= __('Log in') . ' [' . wp_login_url() . "]\r\n";
}

include('_mail.php');