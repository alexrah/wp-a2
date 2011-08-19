=== MailPress add-ons ===

* First thing first :

MP add-on page and management is not as sophisticated as WP plugin management.
So, if you try to activate an add-on with php syntax errors, chances are that wordpress is going to fail. 
If so, rename the add-on file.


* How to code your own add-on ?

Well, all add-ons can be built like WordPress plugins.

1. for activation/deactivation hooks, use this syntax :
	register_activation_hook(plugin_basename(__FILE__), 	array(__CLASS__, 'install'));

2. if your add-on is using the wp hook 'plugins_loaded', duplicate it with 'MailPress_add-ons_loaded' hook.
(sample in MailPress_newsletter.php).

3. Develop and test your add-on as a standard wordpress plugin.

4. Every bug fixed? 
	* deactivate the plugin.
	* MOVE your plugin to your mp-content/add-ons folder.
	* activate the add-on.


Enjoy !
