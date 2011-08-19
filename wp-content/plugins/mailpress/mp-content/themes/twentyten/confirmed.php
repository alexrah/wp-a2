<?php
/*
Template Name: confirmed
Subject: [<?php bloginfo('name');?>] <?php printf('%s confirmed', '{{toemail}}'); ?>
*/

$_the_title = 'Congratulations !';

$_the_content = "You are now a subscriber of : <a " . $this->classes('button', false) . " href='" . get_option('siteurl') . "'>" . get_option('blogname') . "</a><br />";

include('_mail.php');