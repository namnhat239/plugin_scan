=== 301 Redirects & 404 Error Log ===
Contributors: WebFactory
Tags: 301 redirect, redirects, redirect url, redirect rules, 404 error log, 404 error, seo, 404, 404 redirect
Requires at least: 4.0
Tested up to: 5.8
Stable tag: 1.01
Requires PHP: 5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create & manage 301 redirects. Easily test redirects. Includes 404 error log.

== Description ==
A perfect plugin for **creating a new site from an old site** or changing the domain name, and managing all of the redirects and broken URLs.

Find the link to 301 Redirects in the main Settings menu.
404 error log can be found on the same page, and on the admin dashboard.

301 Redirects plugin creates a new table in the WP database called 'WP_PREFIX_ts_redirects' that stores all of your redirect rules. On plugin deactivation the table is not deleted. When you delete the plugin then it is.

**Why is the 404 error log limited to the last 50 errors?**
By default, the 404 error log is limited to the last (chronologically) fifty 404 errors. Since the 404 log doesn't use a custom database table for storage but rather an array saved in WP options, 50 is a safe number that ensures the 404 log works on all sites, that it doesn't take up too much space in the database and that it doesn't slow down the site.
The code imposes no limits on the log size and you can easily overwrite the default limit by using the *301_redirects_max_404_logs* filter or by using the following code snippet to raise the limit to 200:

`add_filter('301_redirects_max_404_logs', function($log_max) { return 200; }, 10, 1);`

== Screenshots ==
1. Just add title, section, old link & new link to redirect URLs.

== Changelog ==

= v1.01 =
* 2021/04/26
* added Delete all redirect rules button
* fixed privilege error for 404 Error dashboard widget

= v1.0 =
* 2021/02/25
* added 404 error log
* added 404 error log dashboard widget
* bug fixes
* security fixes

= v0.5 =
* 2020/09/30
* 10,000 installations; 62,660 downloads
* minor bug fixes
* added promo for WP 301 Redirects PRO

= v0.4 =
* 2019/06/20
* 10,000 installations; 40,940 downloads
* bug fixes

= v0.1 =
* 2015/04/06
* first release
