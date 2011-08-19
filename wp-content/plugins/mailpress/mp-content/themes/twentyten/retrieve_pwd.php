<?php
/*
Template Name: retrieve_pwd
*/
$user = $this->args->advanced->user;
$url  = $this->args->advanced->url;

$_the_title = 'Password reset';

$_the_content  = __('Someone has asked to reset the password for the following site and username.') . "\r\n<br />\r\n";
$_the_content .= $url['site'] . "\r\n<br />\r\n";
$_the_content .= sprintf(__('Username: %s'), stripslashes($user->user_login)) . "<br />\r\n<br />\r\n";
$_the_content .= __('To reset your password visit the following address, otherwise just ignore this email and nothing will happen.') . "<br />\r\n<br />\r\n";	

$_the_actions  = "<a " . $this->classes('button', false) . " href='{$url['reset']}'>"	. __('Reset') . "</a>";

include('_mail.php');