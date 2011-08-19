<?php
class MP_AdminPage extends MP_Admin_page
{
	const screen 	= MailPress_page_themes;
	const capability 	= 'MailPress_switch_themes';
	const help_url	= 'http://www.mailpress.org/wiki/index.php?title=Manual:MailPress:Configuring:Themes';
	const file        = __FILE__;

////  Redirect  ////

	public static function redirect() 
	{
		$th = new MP_Themes();

		if ( isset($_GET['action']) ) 
		{
			check_admin_referer('switch-theme_' . $_GET['template']);
			if ('activate' == $_GET['action']) 
			{
				$th->switch_theme($_GET['template'], $_GET['stylesheet']);
				self::mp_redirect(MailPress_themes . '&activated=true');
			}
		}
	}

////  Styles  ////

	public static function print_styles($styles = array()) 
	{
		$styles[] = 'thickbox';
		parent::print_styles($styles);
	}

////  Scripts  ////

	public static function print_scripts($scripts = array()) 
	{
		wp_register_script( self::screen, 	'/' . MP_PATH . 'mp-admin/js/themes.js', array( 'thickbox', 'jquery' ), false, 1);

		$scripts[] = self::screen;
		parent::print_scripts($scripts);
	}

//// List ////

	public static function get_list($start, $num, $url_parms, $void = '') 
	{
		$th = new MP_Themes();

		$themes = $th->themes;

		unset($themes['plaintext']);
		ksort( $themes );

		return array(array_slice( $themes, $start, $num ), count( $themes ), $th);
	}

////  Row  ////

	public static function get_row($theme, $row, $col, $rows)
	{
		$class = array('available-theme');
		if ( $row == 1 ) $class[] = 'top';
		if ( $col == 1 ) $class[] = 'left';
		if ( $row == $rows ) $class[] = 'bottom';
		if ( $col == 3 ) $class[] = 'right';

// url's
		$args = array();
		$args['action'] 		= 'activate';
		$args['template'] 	= $theme['Template'];
		$args['stylesheet'] 	= $theme['Stylesheet'];
		$activate_url = esc_url(self::url( MailPress_themes, $args, 'switch-theme_' . $theme['Template'] ));

		$args['action'] 		= 'theme-preview';
		$args['TB_iframe'] 	= 'true';
		$args['width'] 		= 600;
		$args['height'] 		= 400;
		$preview_url =  esc_url(self::url( MP_Action_url, $args));

// titles's
		$activate_title	= esc_attr( sprintf( __('Activate &#8220;%s&#8221;'), $theme['Title'] ) );
		$preview_title	= esc_attr( sprintf( __('Preview of &#8220;%s&#8221;'), $theme['Title'] ) );
// actions
		$actions = array();

		$activate['link1']	= "<a class='thickbox screenshot' href='$activate_url'>";
		if ( $theme['Screenshot'] ) $activate['link1'] .= "<img src='" . get_option('siteurl') . '/' . $theme['Stylesheet Dir'] . '/' . $theme['Screenshot'] . "' alt='" . esc_attr($theme['Title']) . "' />";
		$activate['link1']     .= '</a>';

		$activate['link2']	= "<a class='thickbox' href='$activate_url'>" . esc_attr($theme['Title']) . '</a>';

		$activate['link3']	= "<a href='$activate_url' title='$activate_title'>" . __('Activate') . '</a>';
		$preview['link3']		= "<a class='thickbox'  href='$preview_url' title='$preview_title'>"  . __('Preview')  . '</a>';

		$activate['link4']	= "<a class='activatelink' href='$activate_url'>$activate_title</a>";
		$preview['link4']		= "<a class='previewlink'  href='$preview_url' >$preview_title </a>";
?>
			<td class="<?php echo join(' ', $class); ?>">
				<?php echo $activate['link1']; ?>
				<h3><?php echo $activate['link2']; ?></h3>
<?php if ( $theme['Description'] ) : ?>
				<p><?php echo $theme['Description']; ?></p>
<?php endif; ?>
<?php if ( $theme['Tags'] ) : ?>
				<p><?php _e('Tags:'); ?> <?php echo join(', ', $theme['Tags']); ?></p>
<?php endif; ?>
				<p class='themeactions'>
					<?php echo $activate['link3']; ?> | 
					<?php echo $preview['link3']; ?>
				</p>
				<div style='display:none;'>
					<?php echo $preview['link4']; ?>
					<?php echo $activate['link4']; ?>
				</div>
			</td>
<?php
	}
}