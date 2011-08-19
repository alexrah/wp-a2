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
				<table <?php $this->classes('nopmb htable htr'); ?> cellspacing='0' cellpadding='0'>	
					<tr>
						<td <?php $this->classes('nopmb txtleft'); ?>>
							<img src='MailPresslogo.png' <?php $this->classes('logo'); ?> align='' alt=''/>
						</td>
						<td style='width:50px;'></td>
						<td <?php $this->classes('nopmb'); ?>></td>
					</tr>
				</table>
				<table <?php $this->classes('htdate'); ?> cellspacing='0' cellpadding='0' height='70px'>
					<tr>
						<td <?php $this->classes('hdate'); ?>>
							<?php echo mysql2date('F j, Y', current_time('mysql')); ?>
						</td>
					</tr>
				</table>
				<div  <?php $this->classes('main'); ?>>
					<div  <?php $this->classes('content'); ?>>
<!-- end header -->
