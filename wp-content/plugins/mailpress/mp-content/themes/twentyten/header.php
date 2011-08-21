<?php $post_id = (isset($this->args->newsletter['params']['post_id'])) ? $this->args->newsletter['params']['post_id'] : false; ?>
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
<?php if (isset($this->args->viewhtml)) { ?>
				<div <?php $this->classes('mail_link'); ?>>
					Email not displaying correctly ? <a href='{{viewhtml}}' <?php $this->classes('mail_link_a'); ?>>View it in your browser</a>
				</div>
<?php } ?>
				<div <?php $this->classes('header'); ?>>
					<div <?php $this->classes('nopmb'); ?>>
						<div <?php $this->classes('nopmb w100'); ?>>
							<table cellspacing='0' cellpadding='0' <?php $this->classes('nopmb'); ?>>
								<tr>
									<td <?php $this->classes('nopmb'); ?>>
										<h1 <?php $this->classes('site-title'); ?>>
											<span <?php $this->classes('nopmb'); ?>>
												<a <?php $this->classes('site-title_a'); ?> href="<?php bloginfo( 'url' ) ?>/" title="<?php bloginfo( 'name' ) ?>" rel="home">
<?php bloginfo( 'name' ); ?>
												</a>
											</span>
										</h1>
									</td>
									<td <?php $this->classes('nopmb'); ?>>
										<div <?php $this->classes('site-description'); ?>>
<?php bloginfo( 'description' ); ?>
										</div>
									</td>
								</tr>
							</table>
							<img src="<?php MP_theme_html::header_image('images/header-1.jpg', $post_id); ?>" width="<?php echo MP_theme_html::HEADER_IMAGE_WIDTH; ?>" height="<?php echo MP_theme_html::HEADER_IMAGE_HEIGHT; ?>" alt="" <?php $this->classes('nopmb header_img'); ?> />
						</div>
					</div>
				</div>
				<div <?php $this->classes('main'); ?>>
					<div <?php $this->classes('nopmb w100'); ?>>
						<div <?php $this->classes('content'); ?>>
<!-- end header -->