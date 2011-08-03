<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<?php load_theme_textdomain( 'cora', TEMPLATEPATH . '/languages' );
?>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />



<link rel="shortcut icon" href="<?php echo get_option('custom_favicon'); ?>" />

<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS2 Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />



<!--[if IE 7 ]>
<link href="<?php echo THEME_CSS;?>/ie7.css" media="screen" rel="stylesheet" type="text/css">
<![endif]-->
<!--[if lte IE 9]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<link rel="stylesheet" href="<?php echo THEME_CSS;?>/ie8.css" type="text/css" media="screen" />
<![endif]-->

<?php wp_head(); ?>

<script>
  $(document).ready(function($){
	if(!$.browser.msie)  $(".frame").preloader();	
  });
</script>



<title><?php theme_functions('title'); ?></title>


</head>

<body>

	   <div id="menuwrap">
       
       <div id="menu">

  
		<div id="logo">
			<a href="<?php echo home_url( '/' ); ?>"><img alt="<?php bloginfo('name'); ?>" src="<?php echo THEME_SCRIPTS.'/timthumb.php?src='.get_option('logo_image').'&amp;w='.get_option('logo_width').'&amp;zc=1'; ?>" /></a>   
		</div>
     

    
     <?php wp_nav_menu( array( 'sort_column' => 'menu_order', 'container' => '', menu_id => 'nav' ) ); ?>
     
  </div><!-- end menu --> 
  
 </div><!-- end menuwrap --> 
 
<div id="headerwrap">
