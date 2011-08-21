<?php
/*
Template Name: confirmed
*/

$_the_title 	= 'Félicitations !';

$_the_content 	= sprintf('Vous êtes maintenant abonné à %1$s [%2$s] ', get_option('blogname'), get_option('siteurl'));

include('_mail.php');