<?php
/*
Template Name: new_subscriber
Subject: [<?php bloginfo('name');?>] <?php printf( __('Waiting for : %s', MP_TXTDOM), '{{toemail}}'); ?>
*/

$_the_title .='<div align="center">Verifica Email</div>';

$_the_content .= '<br />';
$_the_content ="Vi preghiamo di <a " . $this->classes('button', false) . "href='{{subscribe}}'>confermare</a> il Vostro indirizzo email.";
$_the_content .= '<br />';

unset($this->args->unsubscribe);
include('_mail.php');
