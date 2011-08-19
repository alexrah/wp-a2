<?php
class MP_Themes
{
	const option_current_theme = 'MailPress_current_theme';
	const option_stylesheet    = 'MailPress_stylesheet';
	const option_template      = 'MailPress_template';

	function __construct() 
	{
		$this->themes  			= $this->get_themes();
		$this->current_theme 		= $this->get_current_theme();
		$this->path_current_theme 	= ABSPATH . $this->themes[$this->current_theme] ['Template Dir'];
	}

	function get_page_templates_from($t) 
	{
		$themes = $this->themes;
		$theme = $this->get_theme_by_template($t);
		$templates = $theme['Template Files'];	
		$page_templates = array ();

		if ( is_array( $templates ) ) {
			foreach ( $templates as $template ) {
				$template_data = implode( '', file( ABSPATH . $template ));

				preg_match( '|Subject:(.*)$|mi', $template_data, $subject );
				preg_match( '|Template Name:(.*)$|mi', $template_data, $name );
				preg_match( '|Description:(.*)$|mi', $template_data, $description );

				$name = (isset($name[1])) ? $name[1] : '';
				$description = (isset($description[1])) ? $description[1] : '';
				$subject = (isset($subject[1])) ? $subject[1] : '';
	
				if ( !empty( $name ) ) {
					$page_templates[trim( $name )][] = basename( $template );
					if ('' != $subject) $page_templates[trim( $name )][] = $subject;
				}
			}
		}

		return $page_templates;
	}

	function get_page_plaintext_templates_from($t) 
	{
		$themes = $this->themes;
		$theme = $this->get_theme_by_template($t);
		$templates = $theme['Plaintext Template Files'];	
		$page_templates = array ();

		if ( is_array( $templates ) ) {
			foreach ( $templates as $template ) {
				$template_data = implode( '', file( ABSPATH . $template ));

				preg_match( '|Subject:(.*)$|mi', $template_data, $subject );
				preg_match( '|Template Name:(.*)$|mi', $template_data, $name );
				preg_match( '|Description:(.*)$|mi', $template_data, $description );

				$name = (isset($name[1])) ? $name[1] : '';
				$description = (isset($description[1])) ? $description[1] : '';
				$subject = (isset($subject[1])) ? $subject[1] : '';
	
				if ( !empty( $name ) ) {
					$page_templates[trim( $name )][] = basename( $template );
					if ('' != $subject) $page_templates[trim( $name )][] = $subject;
				}
			}
		}

		return $page_templates;
	}

	function get_theme_by_template($template) 
	{
		foreach ($this->themes as $theme) if ( $theme['Template'] == $template) return $theme;
		return NULL;
	}

	function current_theme_info() 
	{
		$themes = $this->themes;
		$current_theme = $this->current_theme;
		$ct = new stdClass();
		$ct->name = $current_theme;
		$ct->title = $themes[$current_theme]['Title'];
		$ct->version = $themes[$current_theme]['Version'];
		$ct->parent_theme = $themes[$current_theme]['Parent Theme'];
		$ct->template_dir = $themes[$current_theme]['Template Dir'];
		$ct->stylesheet_dir = $themes[$current_theme]['Stylesheet Dir'];
		$ct->template = $themes[$current_theme]['Template'];
		$ct->stylesheet = $themes[$current_theme]['Stylesheet'];
		$ct->screenshot = $themes[$current_theme]['Screenshot'];
		$ct->description = $themes[$current_theme]['Description'];
		$ct->author = $themes[$current_theme]['Author'];
		$ct->tags = $themes[$current_theme]['Tags'];
		return $ct;
	}

	function get_broken_themes() 
	{
		global $mp_broken_themes;
		return $mp_broken_themes;
	}

	function get_page_templates() 
	{
		$themes = $this->themes;
		$theme = $this->current_theme;
		$templates = $themes[$theme]['Template Files'];
		$page_templates = array ();

		if ( is_array( $templates ) ) {
			foreach ( $templates as $template ) {
				$template_data = implode( '', file( ABSPATH . $template ));

				preg_match( '|Subject:(.*)$|mi', $template_data, $subject );
				preg_match( '|Template Name:(.*)$|mi', $template_data, $name );
				preg_match( '|Description:(.*)$|mi', $template_data, $description );

				$name = $name[1];
				$description = $description[1];
				$subject = $subject[1];

				if ( !empty( $name ) ) {
					$page_templates[trim( $name )][] = basename( $template );
					if ('' != $subject) $page_templates[trim( $name )][] = $subject;
				}
			}
		}

		return $page_templates;
	}

/*
 * Theme/template/stylesheet functions.
 */


	function get_stylesheet() 
	{
		return apply_filters('MailPress_stylesheet', get_option(self::option_stylesheet));
	}

	function get_stylesheet_directory() 
	{
		$stylesheet = $this->get_stylesheet();
		$stylesheet_dir = $this->get_theme_root() . "/$stylesheet";
		return apply_filters('MailPress_stylesheet_directory', $stylesheet_dir, $stylesheet);
	}

	function get_stylesheet_directory_uri() 
	{
		$stylesheet = $this->get_stylesheet();
		$stylesheet_dir_uri = $this->get_theme_root_uri() . "/$stylesheet";
		return apply_filters('MailPress_stylesheet_directory_uri', $stylesheet_dir_uri, $stylesheet);
	}

	function get_stylesheet_uri() 
	{
		$stylesheet_dir_uri = $this->get_stylesheet_directory_uri();
		$stylesheet_uri = $stylesheet_dir_uri . "/style.css";
		return apply_filters('MailPress_stylesheet_uri', $stylesheet_uri, $stylesheet_dir_uri);
	}

	function get_locale_stylesheet_uri() 
	{
		global $wp_locale;
		$stylesheet_dir_uri = $this->get_stylesheet_directory_uri();
		$dir = $this->get_stylesheet_directory();
		$locale = get_locale();
		if ( file_exists("$dir/$locale.css") )
			$stylesheet_uri = "$stylesheet_dir_uri/$locale.css";
		elseif ( !empty($wp_locale->text_direction) && file_exists("$dir/{$wp_locale->text_direction}.css") )
			$stylesheet_uri = "$stylesheet_dir_uri/{$wp_locale->text_direction}.css";
		else
			$stylesheet_uri = '';
		return apply_filters('MailPress_locale_stylesheet_uri', $stylesheet_uri, $stylesheet_dir_uri);
	}

	function get_template() 
	{
		return apply_filters('MailPress_template', get_option(self::option_template));
	}

	function get_template_directory() 
	{
		$template = $this->get_template();
		$template_dir = $this->get_theme_root() . "/$template";
		return apply_filters('MailPress_template_directory', $template_dir, $template);
	}

	function get_template_directory_uri() 
	{
		$template = $this->get_template();
		$template_dir_uri = $this->get_theme_root_uri() . "/$template";
		return apply_filters('MailPress_template_directory_uri', $template_dir_uri, $template);
	}

	function get_theme_data( $theme_file ) 
	{
		$themes_allowed_tags = array(
			'a' => array(
				'href' => array(),'title' => array()
				),
			'abbr' => array(
				'title' => array()
				),
			'acronym' => array(
				'title' => array()
				),
			'code' => array(),
			'em' => array(),
			'strong' => array()
		);

		$theme_data = implode( '', file( $theme_file ) );
		$theme_data = str_replace ( '\r', '\n', $theme_data );
		preg_match( '|Theme Name:(.*)$|mi', $theme_data, $theme_name );
		preg_match( '|Theme URI:(.*)$|mi', $theme_data, $theme_uri );
		preg_match( '|Description:(.*)$|mi', $theme_data, $description );

		if ( preg_match( '|Author URI:(.*)$|mi', $theme_data, $author_uri ) )
			$author_uri = esc_url( trim( $author_uri[1]) );
		else
			$author_uti = '';

		if ( preg_match( '|Template:(.*)$|mi', $theme_data, $template ) )
			$template = wp_kses( trim( $template[1] ), $themes_allowed_tags );
		else
			$template = '';

		if ( preg_match( '|Version:(.*)|i', $theme_data, $version ) )
			$version = wp_kses( trim( $version[1] ), $themes_allowed_tags );
		else
			$version = '';

		if ( preg_match('|Status:(.*)|i', $theme_data, $status) )
			$status = wp_kses( trim( $status[1] ), $themes_allowed_tags );
		else
			$status = 'publish';

		if ( preg_match('|Tags:(.*)|i', $theme_data, $tags) )
			$tags = array_map( 'trim', explode( ',', wp_kses( trim( $tags[1] ), array() ) ) );
		else
			$tags = array();

		$name = $theme = wp_kses( trim( $theme_name[1] ), $themes_allowed_tags );
		$theme_uri = (isset($theme_uri[1])) ? esc_url( trim( $theme_uri[1] ) ) : '';
		$description = (isset($description[1])) ? wptexturize( wp_kses( trim( $description[1] ), $themes_allowed_tags ) ) : '';

		if ( preg_match( '|Author:(.*)$|mi', $theme_data, $author_name ) ) {
			if ( empty( $author_uri ) ) {
				$author = wp_kses( trim( $author_name[1] ), $themes_allowed_tags );
			} else {
				$author = sprintf( '<a href="%1$s" title="%2$s">%3$s</a>', $author_uri, __( 'Visit author homepage' ), wp_kses( trim( $author_name[1] ), $themes_allowed_tags ) );
			}
		} else {
			$author = __('Anonymous');
		}

		return array( 'Name' => $name, 'Title' => $theme, 'URI' => $theme_uri, 'Description' => $description, 'Author' => $author, 'Version' => $version, 'Template' => $template, 'Status' => $status, 'Tags' => $tags );
	}

	function get_themes() 
	{
		global $mp_themes, $mp_broken_themes;

		$themes = array();
		$mp_broken_themes = array();
		$theme_loc = $theme_root = $this->get_theme_root();
		if ( '/' != ABSPATH ) // don't want to replace all forward slashes, see Trac #4541
			$theme_loc = str_replace(ABSPATH, '', $theme_root);

// Files in /themes directory and one subdir down
		$themes_dir = @ opendir($theme_root);
		if ( !$themes_dir )
			return false;

		while ( ($theme_dir = readdir($themes_dir)) !== false ) {
			if ( is_dir($theme_root . '/' . $theme_dir) && is_readable($theme_root . '/' . $theme_dir) ) {
				if ( $theme_dir{0} == '.' || $theme_dir == '..' || $theme_dir == 'CVS' )
					continue;
				$stylish_dir = @ opendir($theme_root . '/' . $theme_dir);
				$found_stylesheet = false;
				while ( ($theme_file = readdir($stylish_dir)) !== false ) {
					if ( $theme_file == 'style.css' ) {
						$theme_files[] = $theme_dir . '/' . $theme_file;
						$found_stylesheet = true;
						break;
					}
				}
				@closedir($stylish_dir);
				if ( !$found_stylesheet ) { // look for themes in that dir
					$subdir = "$theme_root/$theme_dir";
					$subdir_name = $theme_dir;
					$theme_subdir = @ opendir( $subdir );
					while ( ($theme_dir = readdir($theme_subdir)) !== false ) {
						if ( is_dir( $subdir . '/' . $theme_dir) && is_readable($subdir . '/' . $theme_dir) ) {
							if ( $theme_dir{0} == '.' || $theme_dir == '..' || $theme_dir == 'CVS' )
								continue;
							$stylish_dir = @ opendir($subdir . '/' . $theme_dir);
							$found_stylesheet = false;
							while ( ($theme_file = readdir($stylish_dir)) !== false ) {
								if ( $theme_file == 'style.css' ) {
									$theme_files[] = $subdir_name . '/' . $theme_dir . '/' . $theme_file;
									$found_stylesheet = true;
									break;
								}
							}
							@closedir($stylish_dir);
						}
					}
					@closedir($theme_subdir);
					$mp_broken_themes[$theme_dir] = array('Name' => $theme_dir, 'Title' => $theme_dir, 'Description' => __('Stylesheet is missing.'), 'Folder' => basename($subdir));
				}
			}
		}
		if ( is_dir( $theme_dir ) )
			@closedir( $theme_dir );

		if ( !$themes_dir || !$theme_files )
			return $themes;

		sort($theme_files);

		foreach ( (array) $theme_files as $theme_file ) {
			if ( !is_readable("$theme_root/$theme_file") ) {
				$mp_broken_themes[$theme_file] = array('Name' => $theme_file, 'Title' => $theme_file, 'Description' => __('File not readable.'), 'Folder' => basename($theme_root));
				continue;
			}

			$theme_data = $this->get_theme_data("$theme_root/$theme_file");

			$name        = $theme_data['Name'];
			$title       = $theme_data['Title'];
			$description = wptexturize($theme_data['Description']);
			$version     = $theme_data['Version'];
			$author      = $theme_data['Author'];
			$template    = $theme_data['Template'];
			$stylesheet  = dirname($theme_file);

			$screenshot = false;
			foreach ( MailPress::ext_image() as $ext ) {
				if (file_exists("$theme_root/$stylesheet/screenshot.$ext")) {
					$screenshot = "screenshot.$ext";
					break;
				}
			}

			if ( empty($name) ) {
				$name = dirname($theme_file);
				$title = $name;
			}

			if ( empty($template) ) {
				if ( file_exists(dirname("$theme_root/$theme_file/index.php")) )
					$template = dirname($theme_file);
				else
					continue;
			}

			$template = trim($template);

			if ( !file_exists("$theme_root/$template/index.php") ) {
				$parent_dir = dirname(dirname($theme_file));
				if ( file_exists("$theme_root/$parent_dir/$template/index.php") ) {
					$template = "$parent_dir/$template";
				} else {
					$mp_broken_themes[$name] = array('Name' => $name, 'Title' => $title, 'Description' => __('Template is missing.'), 'Folder' => basename($template));
					continue;
				}
			}

			$stylesheet_files = array();
			$stylesheet_dir = @ dir("$theme_root/$stylesheet");
			if ( $stylesheet_dir ) {
				while ( ($file = $stylesheet_dir->read()) !== false ) {
					if ( !preg_match('|^\.+$|', $file) && preg_match('|\.css$|', $file) )
						$stylesheet_files[] = "$theme_loc/$stylesheet/$file";
				}
			}

			$template_files = array();
			$template_dir = @ dir("$theme_root/$template");
			if ( $template_dir ) {
				while(($file = $template_dir->read()) !== false) {
					if ( !preg_match('|^\.+$|', $file) && preg_match('|\.php$|', $file) )
						$template_files[] = "$theme_loc/$template/$file";
				}
			}

			$plaintext_template_files = array();
			$plaintext_template_dir = is_dir("$theme_root/$template/plaintext") ? dir("$theme_root/$template/plaintext") : false;
			if ( $plaintext_template_dir ) {
				while(($file = $plaintext_template_dir->read()) !== false) {
					if ( !preg_match('|^\.+$|', $file) && preg_match('|\.php$|', $file) )
						$plaintext_template_files[] = "$theme_loc/$template/plaintext/$file";
				}
			}

			$template_dir 		= dirname($template_files[0]);
			$plaintext_template_dir = (isset($plaintext_template_files[0])) ? dirname($plaintext_template_files[0]) : '';
			$stylesheet_dir 		= dirname($stylesheet_files[0]);

			if ( empty($template_dir) )
				$template_dir = '/';
			if ( empty($plaintext_template_dir) )
				$plaintext_template_dir = '/';
			if ( empty($stylesheet_dir) )
				$stylesheet_dir = '/';

// Check for theme name collision.  This occurs if a theme is copied to
// a new theme directory and the theme header is not updated.  Whichever
// theme is first keeps the name.  Subsequent themes get a suffix applied.
// The Twentyten theme always trump their pretenders.
			if ( isset($themes[$name]) ) {
				if ( ('MailPress Twenty Ten' == $name) && ('twentyten' == $stylesheet) ) 
				{
// If another theme has claimed to be one of our default themes, move
// them aside.
					$suffix = $themes[$name]['Stylesheet'];
					$new_name = "$name/$suffix";
					$themes[$new_name] = $themes[$name];
					$themes[$new_name]['Name'] = $new_name;
				} else {
					$name = "$name/$stylesheet";
				}
			}

			$themes[$name] = array('Name' => $name, 'Title' => $title, 'Description' => $description, 'Author' => $author, 'Version' => $version, 'Template' => $template, 'Stylesheet' => $stylesheet, 'Template Files' => $template_files, 'Plaintext Template Files' => $plaintext_template_files, 'Stylesheet Files' => $stylesheet_files, 'Template Dir' => $template_dir, 'Plaintext Template Dir' => $plaintext_template_dir, 'Stylesheet Dir' => $stylesheet_dir, 'Status' => $theme_data['Status'], 'Screenshot' => $screenshot, 'Tags' => $theme_data['Tags']);
		}

// Resolve theme dependencies.
		$theme_names = array_keys($themes);

		foreach ( (array) $theme_names as $theme_name ) {
			$themes[$theme_name]['Parent Theme'] = '';
			if ( $themes[$theme_name]['Stylesheet'] != $themes[$theme_name]['Template'] ) {
				foreach ( (array) $theme_names as $parent_theme_name ) {
					if ( ($themes[$parent_theme_name]['Stylesheet'] == $themes[$parent_theme_name]['Template']) && ($themes[$parent_theme_name]['Template'] == $themes[$theme_name]['Template']) ) {
						$themes[$theme_name]['Parent Theme'] = $themes[$parent_theme_name]['Name'];
						break;
					}
				}
			}
		}
	
		$mp_themes = $themes;

		return $themes;
	}

	function get_theme($theme) 
	{
		$themes = $this->themes;
	
		if ( array_key_exists($theme, $themes) )	
			return $themes[$theme];

		return NULL;
	}

	function get_current_theme() 
	{
		if ( $theme = get_option(self::option_current_theme) )
			return $theme;

		$themes = $this->themes;
		$theme_names = array_keys($themes);
		$current_template = get_option(self::option_template);
		$current_stylesheet = get_option(self::option_stylesheet);
		$current_theme = 'MailPress Twenty Ten';

		if ( $themes ) {
			foreach ( (array) $theme_names as $theme_name ) {
				if ( $themes[$theme_name]['Stylesheet'] == $current_stylesheet &&
						$themes[$theme_name]['Template'] == $current_template ) {
					$current_theme = $themes[$theme_name]['Name'];
					break;
				}
			}
		}

		update_option(self::option_current_theme, $current_theme);

		return $current_theme;
	}

	function get_theme_root() 
	{
		return apply_filters('MailPress_theme_root', ABSPATH . MP_PATH_CONTENT . 'themes');
	}

	function get_theme_root_uri() 
	{
		return apply_filters('MailPress_theme_root_uri', get_option('siteurl') . '/' . MP_PATH_CONTENT . 'themes', get_option('siteurl'));
	}

	function switch_theme($template, $stylesheet) 
	{
		update_option(self::option_template, $template);
		update_option(self::option_stylesheet, $stylesheet);
		delete_option(self::option_current_theme);
		$theme = $this->current_theme;
		do_action('MailPress_switch_theme', $theme);
	}

	function validate_current_theme() 
	{
// Don't validate during an install/upgrade.
//	if ( defined('WP_INSTALLING') )
//		return true;

		if ( $this->get_template() != 'twentyten' && !file_exists($this->get_template_directory() . '/index.php') ) {
			$this->switch_theme('twentyten', 'twentyten');
			return false;
		}

		if ( $this->get_stylesheet() != 'twentyten' && !file_exists($this->get_template_directory() . '/style.css') ) {
			$this->switch_theme('twentyten', 'twentyten');
			return false;
		}

		return true;
	}
}