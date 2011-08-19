<?php
/*
Template Name: reset_pwd
*/
$user = $this->args->advanced->user;

$_the_title = 'Your new password';

$_the_content  = sprintf(__('Username: %s'), stripslashes($user->user_login) );
$_the_content .= "<br />\r\n";
$_the_content .= sprintf(__('Password: %s'), $user->new_pass) ;
$_the_content .= "<br />\r\n<br />\r\n";

$_the_actions  = "<a " . $this->classes('button', false) . " href='" . wp_login_url() . "'>"	. __('Log In') . "</a><br />\r\n";

include('_mail.php');