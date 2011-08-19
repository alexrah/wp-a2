<?php
/*
Template Name: retrieve_pwd
*/
$user = $this->args->advanced->user;
$url  = $this->args->advanced->url;

$_the_title = 'Password reset';

$_the_content  = __('Someone has asked to reset the password for the following site and username.') . "\n\n";
$_the_content .= $url['site'] . "\n\n";
$_the_content .= sprintf(__('Username: %s'), stripslashes($user->user_login)) . "\n\n";
$_the_content .= __('To reset your password visit the following address, otherwise just ignore this email and nothing will happen.') . "\n\n";	

$_the_actions 	= __('Reset') . " [{$url['reset']}]\r\n";

include('_mail.php');