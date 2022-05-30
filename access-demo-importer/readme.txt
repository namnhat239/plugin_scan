=== Access Demo Importer ===
Contributors: Access Keys
Tags: import, content, demo, theme options, customizer options, widgets, redux options
Donate link: http://accesspressthemes.com/donation/
Requires at least: 4.7.0
Tested up to: 5.9
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Stable tag: 1.0.9
Version: 1.0.9



== Description ==
This plugin will import demo contents for the active theme which are configured with this plugin. By default the plugin will fetch the configuration files stored on our server.
If you want to configure your own theme with the plugin, you just need to point to your config.json file and pass it to the filter 
<strong>adi_config_location</strong> 

Example: 

If your URL to config.json is 'https://example.com/theme-demos/config.json'

You will have to pass this to the filter 'adi_config_location' like

<pre>
add_filter('adi_config_location', 'my_url_config' );
function my_url_config(){
	$my_url = 'https://example.com/theme-demos/config.json';
	return $my_url;
}
</pre>


[Support](https://accesspressthemes.com/support)


== Installation ==
1. Upload `access-demo-importer` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. After activating plugin go to Appearance >> Install Demos
4. Enjoy !!!

= Available Languages =
* English

= Features =
* <strong>Import all the contents of theme's demo.</strong>
* <strong>Import all widgets data.</strong>
* <strong>Import all menu, customizer settings and other themes settings.</strong>
* <strong>Import Revolution slider.</strong>
* <strong>Import posts, pages, images, categories, custom post.</strong>

= Some Useful Links =
* <strong>Support Forum Link</strong>: http://accesspressthemes.com/support/
* <strong>Website Link</strong>: http://accesspressthemes.com/
* <strong>Youtube channel link</strong>: https://www.youtube.com/watch?v=TjZNcVG3fDE
* <strong>Facebook link</strong>: https://www.facebook.com/AccessPressThemes

== Frequently Asked Questions ==
= What does this plugin do? =
This plugin provides the ability to import the demo contents, widgets, theme option settings, slider files and customizer settings in your site.


== Changelog ==
= 1.0.9 =
* Added capability checks to the plugin functions.

= 1.0.8 =
* Fixed CSRF security issues.

= 1.0.7 =
* Added appropriate capability checks in plugin functions where required

= 1.0.6 =
* Fixed issues regarding fetching revolution slider files from accesspressthemes.com and importng them by making a zip file locally

= 1.0.5 =
* Checked compatibility with WP version 5.8
* Fixed offline installer issue
* Fixed issue regarding calling files remotely from external site
* Fixed issues regarding getting files using CURL
* Fixed minor bugs

= 1.0.4 =
* Fixed customizer importer issue


= 1.0.3 =
* Fixed importer issue with latest version of elementor

= 1.0.2 =
* Minor bugs fixed
* CSS improvements

= 1.0.1 =
* Minor bugs fixed

= 1.0.0 =
* Plugin submitted to http://wordpress.org for review and approval

== Upgrade Notice ==
There is an update available for the Access Demo Importer plugin. Please update to recieve new updates and bug fixes.