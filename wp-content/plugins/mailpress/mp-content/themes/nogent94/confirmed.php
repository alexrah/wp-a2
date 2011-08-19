<?php
/*
Template Name: confirmed
Subject: [<?php bloginfo('name');?>] <?php printf('Abonnement de %s', '{{toemail}}'); ?>
*/

$_the_title = 'F&eacute;licitations !';

$_the_content = "Vous &ecirc;tes maintenant abonn&eacute; &agrave; : <a " . $this->classes('button', false) . " href='" . get_option('siteurl') . "'>" . get_option('blogname') . "</a><br />";

include('_mail.php');