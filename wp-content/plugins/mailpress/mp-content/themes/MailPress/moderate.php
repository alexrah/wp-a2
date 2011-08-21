<?php
/*
Template Name: moderate
*/
$comment = $this->args->advanced->comment;
$url     = $this->args->advanced->url;

$_the_title = (isset($url['approve']))
						? sprintf( __('A new comment on the post "%s" is waiting for your approval'), '{{the_title}}' )
						: sprintf( __('New comment on your post "%s"'), '{{the_title}}' );

$_the_content .= "<br />\n";
$_the_content .= sprintf( __('Author : %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment->author_domain);
$_the_content .= "<br />\n"; 
$_the_content .= sprintf( __('E-mail : %s'), $comment->comment_author_email);
$_the_content .= "<br />\n"; 
$_the_content .= sprintf( __('URL    : %s'), $comment->comment_author_url);
$_the_content .= "<br />\n";
$_the_content .= __('Comment: ');
$_the_content .= "<br />\n"; 
$_the_content .= $comment->comment_content;
$_the_content .= "<br />\n";
$_the_content .= sprintf( __('Whois  : %s'), sprintf("<a " . $this->classes('button', false) . " href='http://ws.arin.net/cgi-bin/whois.pl?queryinput=%s'>" . __('Whois') . "</a>", $comment->comment_author_IP ));
$_the_content .= "<br />\n";
$_the_content .= "<br />\n";

$_the_actions = (isset($url['approve']))  
						  ? "<a " . $this->classes('button', false) . " href='{$url['approve']}'>"			. __('Approve')  . "</a>"
	 					  : "<a " . $this->classes('button', false) . " href='{$url['comments']}#comments'>"	. __('View all') . "</a>";
$_the_actions .= ( EMPTY_TRASH_DAYS ) ? "&nbsp;<a " . $this->classes('button', false) . " href='{$url['trash']}'>"		. __('Trash')    . "</a>"
						  : "&nbsp;<a " . $this->classes('button', false) . " href='{$url['delete']}'>"		. __('Delete')   . "</a>";
$_the_actions .=                        "&nbsp;<a " . $this->classes('button', false) . " href='{$url['spam']}'>"			. __('Spam')     . "</a>";

include('_mail.php');