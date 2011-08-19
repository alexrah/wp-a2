<?php
/*
Template Name: new_subscriber
Subject: [<?php bloginfo('name');?>] <?php printf( __('Waiting for : %s', MP_TXTDOM), '{{toemail}}'); ?>
*/

$_the_title = "Email validation";

$_the_content = "Please <a " . $this->classes('button', false) . "href='{{subscribe}}'>confirm</a> your email address.";
$_the_content .= '<br />';

unset($this->args->unsubscribe);
include('_mail.php');