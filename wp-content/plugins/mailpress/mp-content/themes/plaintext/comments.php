<?php
/*
Template Name: comments
*/
$comment = $this->args->advanced->comment;
$post    = $this->args->advanced->post;

$_the_title = 'Comment # ' . $comment->comment_ID . ' in "{{the_title}}"';

$_the_actions 	= __('Reply') . " [{$post->guid}#comment-{$comment->comment_ID}]";

include('_mail.php');