<?php
/*
Template Name: manual
*/

$this->get_header();
include('_loom.php');

$this->args->newsletter = true;		// to tweak $this->the_content in manually newsletter with query_posts
include('_loop.php');
$this->args->newsletter = false;

$this->get_footer();