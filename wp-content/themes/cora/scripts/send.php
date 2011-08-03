<?php
require_once( '../../../../wp-load.php' );

$sitename = get_bloginfo('name');
$siteurl =  get_bloginfo('siteurl');

$to = isset($_POST['to'])?trim($_POST['to']):'';
$name = isset($_POST['name'])?trim($_POST['name']):'';
$email = isset($_POST['email'])?trim($_POST['email']):'';
$content = isset($_POST['content'])?trim($_POST['content']):'';


$error = false;
if($to === '' || $email === '' || $content === '' || $name === ''){
	$error = true;
}
if(!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $email)){
	$error = true;
}
if(!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $to)){
	$error = true;
}

if($error == false){
	$subject = "$sitename's message from $name";
	$body = "Site: $sitename ($siteurl) \n\nName: $name \n\nEmail: $email \n\nMessages: $content";
	$headers = "From: $sitename <$to>\r\nReply-To: $email\r\n";
	
	wp_mail($to, $subject, $body, $headers);
}