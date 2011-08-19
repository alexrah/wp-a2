<?php
/*
Template Name: new_user
*/
$user = $this->args->advanced->user;

if (isset($this->args->advanced->admin))
{
	$_the_title = "New User";

	$_the_content  = sprintf(__('Username: %s'), stripslashes($user->user_login) );
	$_the_content .= "<br />\r\n";
	$_the_content .= sprintf(__('E-mail: %s'),   stripslashes($user->user_email) );
	$_the_content .= "<br />\r\n<br />\r\n";
}
else
{
	$_the_title = "Welcome !";

	$_the_content  = sprintf(__('Username: %s'), stripslashes($user->user_login) );
	$_the_content .= "<br />\r\n";
	$_the_content .= sprintf(__('Password: %s'), $user->plaintext_pass ) ;
	$_the_content .= "<br />\r\n<br />\r\n";

	$_the_actions  = "<a " . $this->classes('button', false) . " href='" . wp_login_url() . "'>" . __('Log in') . "</a><br />\r\n";
}


include('_mail.php');