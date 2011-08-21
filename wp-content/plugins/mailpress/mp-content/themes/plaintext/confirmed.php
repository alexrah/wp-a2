<?php
/*
Template Name: confirmed
Subject: [<?php bloginfo('name');?>] <?php printf('%s confirmed', '{{toemail}}'); ?>
*/

$_the_title = 'Congratulations !';

$_the_content = 'You are now a subscriber of ' . get_option('blogname') . ' [' . get_option('siteurl') . ']';

include('_mail.php');