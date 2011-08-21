<?php
/*
Template Name: comments
*/
$comment = $this->args->advanced->comment;
$post    = $this->args->advanced->post;

$_the_title = 'Comment # ' . $comment->comment_ID . ' in "{{the_title}}"';

$_the_actions = "<a " . $this->classes('button', false) . " href='{$post->guid}#comment-{$comment->comment_ID}'>" . __('Reply') . "</a>";

include('_mail.php');