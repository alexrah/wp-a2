<?php
/*
Template Name: new_subscriber
Subject: [<?php bloginfo('name');?>] <?php printf( __('En attente de : %s', MP_TXTDOM), '{{toemail}}'); ?>
*/

$_the_title = "Validation de votre adresse mail";

$_the_content = "Merci <a " . $this->classes('button', false) . "href='{{subscribe}}'>de confirmer</a> votre addresse mail.";
$_the_content .= '<br />';

unset($this->args->unsubscribe);
include('_mail.php');