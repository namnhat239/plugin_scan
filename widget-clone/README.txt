=== Widget Clone ===
Contributors: 93digital
Tags: widget clone, widget duplicator, duplicate widget plugin
Requires at least: 4.3
Tested up to: 5.8
Stable tag: trunk
License: GPL v3
License URI: http://www.gnu.org/licenses/gpl-3.0.en.html

Easily duplicate or clone a widget with all of its settings in just one click.

== Description ==

**This plugin is no longer be supported from v1.1.4.**

93digital’s widget duplicator plugin adds a ‘Clone’ link at the bottom of every widget. Simply click ‘Clone’ to make a copy of the widget that you can then drag and drop to another sidebar.

**PLEASE NOTE:**
With the release of WordPress 5.8, the Widget area has had an overhaul and now uses it's own version of the Gutenberg editor. This sadly renders this plugin redundant as it is only built to work with the older widget area, plus the new area comes with widget duplication functionality as standard.

To keep using the old widget area and this plugin, please add `remove_theme_support( 'widgets-block-editor' )` to the active theme's functions.php file. You should only do this if you know what you are doing!

== Installation ==

1. Upload the widget-clone folder to the /wp-content/plugins/ directory
2. Activate the Widget Clone plugin through the 'Plugins' menu in WordPress
3. Navigate to Appearance - Widgets on the menu, open a widget and click the ‘Clone’ link to make a copy of the widget.

== Frequently Asked Questions ==

How do I clone a widget?

Navigate to Appearance - Widgets on the menu, open a widget and click the ‘Clone’ link to make a copy of the widget.

== Screenshots ==

1. The ‘Clone’ button can be found at the bottom of each widget in between ‘Delete’ and ‘Close’.

== Changelog ==

= 1.1.4 =
* Tested with WordPress v5.8, using the old widget area (see plugin description) to test. This plugin does not work with the new Gutenberg widget area.

= 1.1.3 =
* Tested with WordPress v5.6

= 1.1.2 =
* Tested with WordPress v5.4.1

= 1.1.1 =
* Tested with WordPress v5.3.2

= 1.1 =
* No data

= 1.0 =
* First release
