<?php
/*
Template Name: confirmed
Subject: [<?php bloginfo('name');?>] <?php printf('%s confirmed', '{{toemail}}'); ?>
*/

$_the_title = 'Congratulazioni !';

$_the_content = "Siete iscritti alla Newsletter di : <a " . $this->classes('button', false) . " href='" . get_option('siteurl') . "'>" . get_option('blogname') . "</a><br />";

include('_mail.php');
