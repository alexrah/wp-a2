<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<title><?php bloginfo( 'name' ) ?> > <?php $this->the_subject('mail subject'); ?> > {{toemail}}</title>
	</head>
	<body>
<?php $this->get_stylesheet(); ?>
		<div <?php $this->classes('body'); ?>>
			<div <?php $this->classes('wrapper'); ?>>
				<div>
<?php if (isset($this->args->viewhtml)) { ?>
					<div style='text-align:center;padding-top:10px;'>
						<small>
							<a href='{{viewhtml}}' style='color:#999;'>Si ce mail ne s'affiche pas correctement ouvrir ce lien</a>
							<br />
						</small>
					</div>
<?php } ?>
					<div>
						<img src='Nogent94.gif' style='border:none;margin:20px 0;padding:0' alt='' />
						<img src='degrade.jpg' style='width:100%;height:25px;border:none;padding:5px 0;' alt='' height='25px' />
						<span style='float:left;padding:0;margin:0;'><small><b><a href='<?php echo get_bloginfo('siteurl'); ?>' style='color:#D76716;text-align:left;text-decoration:none;outline-style:none;'><?php echo get_bloginfo('siteurl'); ?></a></b></small></span>
						<span style='float:right;color:#590000'><small><b><?php echo mysql2date('l j F Y', current_time('mysql')); ?></b></small></span>
					</div>
					<div style='clear:both;'></div>
				</div>
				<div <?php $this->classes('main'); ?>>
					<div <?php $this->classes('nopmb w100'); ?>>
						<div <?php $this->classes('content'); ?>>
<!-- end header -->