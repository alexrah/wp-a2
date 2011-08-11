<?php
/*
 Plugin Name: Customize Admin
 Plugin URI: http://www.vanderwijk.com/wordpress/customize-admin/
 Description: This plugin allows you to customize the branding of the WordPress admin interface.
 Version: 1.3
 Author: Johan van der Wijk
 Author URI: http://www.vanderwijk.com

 Release notes: 1.3 Added the option to remove the generator meta tag from the html source

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Title attribute for the logo on the login screen
function ca_logo_title($message) {
	if (get_option('ca_logo_url') != '')
		printf(__("Go to %s"), get_option('ca_logo_url'));
	else
		printf(__("Return to %s"), get_bloginfo('name'));
}

// URL for the logo on the login screen
function ca_logo_url($url) {
	if (get_option('ca_logo_url') != '')
		return get_option('ca_logo_url');
	else
		return get_bloginfo('siteurl');
}

// CSS for custom logo on the login screen
function ca_logo_file() {
	if (get_option('ca_logo_file') != '')
		echo '<style>h1 a { background-image:url("' . get_option('ca_logo_file') . '")!important; }</style>';
	else
		$stylesheet_uri = get_bloginfo('siteurl') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/customize-admin.css';
		echo '<link rel="stylesheet" type="text/css" href="' . $stylesheet_uri . '" />';
}

// Remove the shadow from the left menu
function ca_add_styles() {
	if (get_option('ca_remove_shadow') != '')
		echo '<style type="text/css">#adminmenushadow, #adminmenuback { background-image: none; }</style>';
}

// Remove the shadow from the left menu
function ca_remove_generator() {
	if (get_option('ca_remove_generator') != '')
		remove_action('wp_head', 'wp_generator');
}

add_filter('login_headertitle', 'ca_logo_title');
add_filter('login_headerurl', 'ca_logo_url');
add_action('login_head', 'ca_logo_file');
add_action('admin_head', 'ca_add_styles');
add_action('init', 'ca_remove_generator');

require_once('customize-admin-options.php');

?>