<?php
/*
Template Name: reset_pwd
*/
$user = $this->args->advanced->user;

$_the_title = 'Your new password';

$_the_content  = sprintf(__('Username: %s'), stripslashes($user->user_login) );
$_the_content .= "\r\n";
$_the_content .= sprintf(__('Password: %s'), $user->new_pass) ;
$_the_content .= "\r\n\r\n";

$_the_actions 	= __('Log in') . ' [' . wp_login_url() . "]\r\n";

include('_mail.php');