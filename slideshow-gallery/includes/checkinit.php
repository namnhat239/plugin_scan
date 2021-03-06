<?php
	
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!class_exists('GalleryCheckinit')) {
	class GalleryCheckinit {
	
		function __construct() {
			return true;	
		}
		
		function ci_initialize() {							
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
			
			if (!is_plugin_active(plugin_basename($this -> plugin_file))) {			
				return;
			}
			
			add_action('wp_ajax_slideshow_serialkey', array($this, 'ajax_serialkey'));
		
			if (true || !is_admin() || (is_admin() && $this -> ci_serial_valid())) {
				$this -> ci_initialization();
			} else {				
				$this -> add_action('admin_print_styles', 'ci_print_styles', 10, 1);
				$this -> add_action('admin_print_scripts', 'ci_print_scripts', 10, 1);
				$this -> add_action('admin_notices');
				$this -> add_action('init', 'init', 10, 1);
				$this -> add_action('admin_menu', 'admin_menu');
			}
			
			return false;
		}
		
		function ci_initialization() {	
			
			$this -> add_action('after_plugin_row_' . $this -> plugin_name . '/slideshow-gallery.php', 'after_plugin_row', 10, 2);
			$this -> add_action('install_plugins_pre_plugin-information', 'display_changelog', 10, 1);
			
			/*if ($this -> ci_serial_valid()) {				
				$this -> add_action('install_plugins_pre_plugin-information', 'display_changelog', 10, 1);
				$this -> add_action('plugin_row_meta', 'plugin_row_meta', 10, 2);
				$this -> add_filter('transient_update_plugins', 'check_update', 10, 1);
		        $this -> add_filter('site_transient_update_plugins', 'check_update', 10, 1);
		    }*/
			
			$this -> add_filter('default_hidden_columns', 'default_hidden_columns', 10, 2);
			$this -> add_filter('set-screen-option', 'set_screen_option', 10, 3);
			$this -> add_filter('removable_query_args', 'removable_query_args', 10, 1);
			//$this -> add_filter('transient_update_plugins', 'check_update', 10, 1);
			//$this -> add_filter('site_transient_update_plugins', 'check_update', 10, 1);
			
			// SSL stuff
			add_filter('upload_dir', array($this, 'replace_https'));
			add_filter('option_siteurl', array($this, 'replace_https'));
			add_filter('option_home', array($this, 'replace_https'));
			add_filter('option_url', array($this, 'replace_https'));
			add_filter('option_wpurl', array($this, 'replace_https'));
			add_filter('option_stylesheet_url', array($this, 'replace_https'));
			add_filter('option_template_url', array($this, 'replace_https'));
			add_filter('wp_get_attachment_url', array($this, 'replace_https'));
			add_filter('widget_text', array($this, 'replace_https'));
			add_filter('login_url', array($this, 'replace_https'));
			add_filter('language_attributes', array($this, 'replace_https'));
			
			return true;
		}
		
		function ci_get_serial() {
		    //return true;

			if ($serial = $this -> get_option('serialkey')) {
				return $serial;
			}
			
			return false;
		}
		
		function ci_serial_valid() {
		    //return true;

			$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
			$port = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 80;
			$result = false;
			
			$existing = $this -> get_option('existing');
			if (!empty($existing)) return true;
			
			if (preg_match("/^(www\.)(.*)/si", $host, $matches)) {
				$wwwhost = $host;
				$nonwwwhost = preg_replace("/^(www\.)?/si", "", $wwwhost);
			} else {
				$nonwwwhost = $host;
				$wwwhost = "www." . $host;
			}

            if (preg_match('/tribulant.net/i', $nonwwwhost)) {
                return true;
            }

			if ($host == "localhost" || $host == "localhost:" . $port) {
				$result = true;	
			} else {
				if ($serial = $this -> ci_get_serial()) {			
					if ($serial == strtoupper(md5($host . "gallery" . "mymasesoetkoekiesisfokkenlekker"))) {
						$result = true;
					} elseif (strtoupper(md5($wwwhost . "gallery" . "mymasesoetkoekiesisfokkenlekker")) == $serial || 
								strtoupper(md5($nonwwwhost . "gallery" . "mymasesoetkoekiesisfokkenlekker")) == $serial) {
						$result = true;
					}
				}
			}
			
			$result = apply_filters($this -> pre . '_serialkey_validation', $result);
			return $result;
		}
	}
}

?>