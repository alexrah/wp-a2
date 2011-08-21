<?php
/*
Template Name: new_subscriber
*/

$_the_title 	= sprintf('Subscription to %1$s', get_option('blogname'));

$_the_content 	= sprintf('Confirm your subscription by activating the following link : %1$s ', '{{subscribe}}');

unset($this->args->unsubscribe);
include('_mail.php');