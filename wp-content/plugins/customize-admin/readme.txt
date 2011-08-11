=== Customize Admin ===
Contributors: vanderwijk
Author URI: http://www.vanderwijk.com/
Donate link: http://www.vanderwijk.com/wordpress/support/
Tags: custom, admin, customize, logo
Requires at least: 2.9.2
Tested up to: 3.2
Stable tag: 1.3

With this plugin you can use your own logo on the WordPress login page.

== Description ==

With this plugin you can display your own logo on the WordPress login page. You can also specify the link attached to the logo. By default you are redirected to the homepage of your site.

You can find more information about this plugin and a screencast video which shows the plugin in action on the [plugin homepage](http://www.vanderwijk.com/wordpress/wordpress-customize-admin-plugin/).

== Screenshots ==

1. You can specify the logo image and clickthrough link on the options page.


== Installation ==

1. First you will have to upload the plugin to the `/wp-content/plugins/` folder.
2. Then activate the plugin in the plugin panel.
If you have manage options rights you will see the new Custom Admin Settings menu.
3. Specify a clickthrough url for the logo if required.
4. Specify the url for the custom logo. The path can be relative from the root or include the domain name.
5. If you have not yet uploaded a logo, you can do so via the Upload Image button. Make sure you click 'Insert into Post'. For the best result, use an image of maximum 67px height by 326px width.
6. Click Save Changes.

== Frequently Asked Questions ==

= Why did you make another admin logo plugin?  =

There are already quite a few plugins that offer similar functionality, but the fact that my plugin uses the WordPress Media Library makes it super easy to upload and edit your own logo.

I also am not aware of any other plugins that allow you to specify a clickthrough url for the logo. 

Finally, this plugin is ready to be localized. All you have to do is to use the POT file for translating.

== Changelog ==

= 1.0 =
First release

= 1.1 =
Minor update, moved the Customize Admin Options page to the Settings menu.

= 1.2 =
Code cleanup, inclusion of [option to remove the admin shadow](http://www.vanderwijk.com/updates/remove-wordpress-3-2-admin-shadow-plugin/) which was introduced in WordPress 3.2.

= 1.3 =
Added option to remove generator meta tag from the head section of the html source.


== Upgrade Notice ==

= 1.2 =
Because of a code rewrite and renaming of the options, all SETTINGS will BE LOST when upgrading the plugin. My apologies for this, but fortunately it should be simple to restore the settings via Settings - Customize Admin.

