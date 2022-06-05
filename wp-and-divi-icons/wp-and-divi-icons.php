<?php
/**
 * Plugin Name: WP and Divi Icons
 * Plugin URI:  https://aspengrovestudios.com/
 * Description: Adds 300+ new icons to the WordPress editor and the Divi & Extra framework, helping you build standout WordPress web designs.
 * Version:     1.6.0
 * Author:      Aspen Grove Studios
 * Author URI:  https://aspengrovestudios.com/?utm_source=wp-and-divi-icons-pro&utm_medium=plugin-credit-link&utm_content=plugin-file-author-uri
 * License:     GNU General Public License version 3
 * License URI: https://www.gnu.org/licenses/gpl.html
 * Text Domain: ds-icon-expansion
 * GitLab Plugin URI: https://gitlab.com/aspengrovestudios/wp-and-divi-icons-pro/
 * AGS Info: ids.aspengrove 425765
 */

/*

WP and Divi Icons plugin
Copyright (C) 2022 Aspen Grove Studios

Despite the following, this project is licensed exclusively under
GNU General Public License (GPL) version 3 (no later versions).
This statement modifies the following text.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.

============

For the text of the GNU General Public License version 3, and licensing/copyright
information for third-party code used in this product, see ./license.txt.

=======

Note: Divi is a registered trademark of Elegant Themes, Inc. This product is not
affiliated with nor endorsed by Elegant Themes.

*/

defined( 'ABSPATH' ) or exit;



register_activation_hook( __FILE__, array('AGS_Divi_Icons', 'on_activation') );

class AGS_Divi_Icons {
	// Following constants must be HTML safe
	const PLUGIN_NAME = 'WP and Divi Icons';
	const PLUGIN_SLUG = 'wp-and-divi-icons-free';
	const PLUGIN_AUTHOR = 'Aspen Grove Studios';
	const PLUGIN_AUTHOR_URL = 'https://aspengrovestudios.com/';
	const PLUGIN_VERSION = '1.6.0';
	const PLUGIN_PAGE = 'admin.php?page=ds-icon-expansion';
	const PLUGIN_PRODUCT_URL_FREE = 'https://divi.space/product/wp-and-divi-icons/';
	const PLUGIN_PRODUCT_URL_PRO = 'https://wordpress.org/plugins/wp-and-divi-icons/';
	const PLUGIN_REVIEW_URL_FREE = 'https://divi.space/product/wp-and-divi-icons/';
	

	public static $pluginFile, $pluginDir, $pluginDirUrl;
	public static                          $icon_packs, $icons;
	protected static                       $agsDiviIconsPages, $multiColorIconsColorized;

	public static function init() {

		self::$pluginFile   = __FILE__;
		self::$pluginDir    = dirname( __FILE__ ) . '/';
		self::$pluginDirUrl = plugins_url( '', __FILE__ );
		$isAdmin            = is_admin();

		// Used to determine which icon sets to load and use, don't change order.
		self::$icon_packs = array(
			'single_color' => array(
				'fo'  => array(
					'name'          => __( 'Free Outline', 'ds-icon-expansion' ),
					'quantity'      => 301,
					'value'         => get_option( 'agsdi_fo_icons' ),
					'path'          => self::$pluginDirUrl . '/icon-packs/free-icons/',
					'icon_prefixes' => array( 'agsdi' => '(301)' ),
					'free'          => true,
					'1.5.0'         => 'yes'
				),
				'mc'  => array(
					'name'          => __( 'Multicolor', 'ds-icon-expansion' ),
					'quantity'      => 48,
					'value'         => get_option( 'agsdi_mc_icons' ),
					'path'          => self::$pluginDirUrl . '/icon-packs/ags-multicolor/',
					'icon_prefixes' => array(
						'agsdix-smc' => '(48)'
					),
					'1.5.0'         => 'yes'
				),
				'fa'  => array(
					'name'          => __( 'Font Awesome', 'ds-icon-expansion' ),
					'quantity'      => 1297, // Number of icons in pack
					'value'         => get_option( 'agsdi_fa_icons' ),
					'path'          => self::$pluginDirUrl . '/icon-packs/fontawesome/',
					'icon_prefixes' => array(
						'agsdix-fa'  => '(1297)',
						'agsdix-fab' => __( 'Brands', 'ds-icon-expansion' ),
						'agsdix-fas' => __( 'Solid', 'ds-icon-expansion' ),
						'agsdix-far' => __( 'Line', 'ds-icon-expansion' ),
					),
					'1.5.0'         => 'yes'
				),
				'md'  => array(
					'name'          => __( 'Material Design', 'ds-icon-expansion' ),
					'quantity'      => 933,
					'value'         => get_option( 'agsdi_md_icons' ),
					'path'          => self::$pluginDirUrl . '/icon-packs/material/',
					'icon_prefixes' => array(
						'agsdix-smt' => '(933)',
					),
					'1.5.0'         => 'yes'
				),
				'ui'  => array(
					'name'          => __( 'Universal', 'ds-icon-expansion' ),
					'quantity'      => 100,
					'value'         => get_option( 'agsdi_ui_icons' ),
					'path'          => self::$pluginDirUrl . '/icon-packs/ags-universal/single-color/',
					'icon_prefixes' => array(
						'agsdix-sao' => '(100)'
					),
					'1.5.0'         => 'yes'
				),
				'np'  => array(
					'name'          => __( 'Hand Drawn', 'ds-icon-expansion' ),  // prev: Nonprofit
					'quantity'      => 114,
					'value'         => get_option( 'agsdi_np_icons' ),
					'path'          => self::$pluginDirUrl . '/icon-packs/ags-hand-drawn/single-color/',
					'icon_prefixes' => array(
						'agsdix-snp' => '(114)'
					),
					'1.5.0'         => 'yes'
				),
				'cs'  => array(
					'name'          => __( 'Lineal', 'ds-icon-expansion' ), //(prev: Cleaning Service)
					'quantity'      => 25,
					'value'         => get_option( 'agsdi_cs_icons' ),
					'path'          => self::$pluginDirUrl . '/icon-packs/ags-lineal/single-color/',
					'icon_prefixes' => array(
						'agsdix-scs' => '(25)'
					),
					'1.5.0'         => 'yes'
				),
				'out' => array(
					'name'          => __( 'Outline', 'ds-icon-expansion' ),
					'quantity'      => 50,
					'value'         => get_option( 'agsdi_out_icons' ),
					'path'          => self::$pluginDirUrl . '/icon-packs/ags-outline/single-color/',
					'icon_prefixes' => array(
						'agsdix-sout' => '(50)'
					),
					'1.5.0'         => 'no'
				),
				'ske' => array(
					'name'          => __( 'Sketch', 'ds-icon-expansion' ),
					'quantity'      => 40,
					'value'         => get_option( 'agsdi_ske_icons' ),
					'path'          => self::$pluginDirUrl . '/icon-packs/ags-sketch/single-color/',
					'icon_prefixes' => array(
						'agsdix-sske' => '(40)'
					),
					'1.5.0'         => 'no'
				),

				'ele' => array(
					'name'          => __( 'Elegant', 'ds-icon-expansion' ),
					'quantity'      => 51,
					'value'         => get_option( 'agsdi_ele_icons' ),
					'path'          => self::$pluginDirUrl . '/icon-packs/ags-elegant/single-color/',
					'icon_prefixes' => array(
						'agsdix-sele' => '(51)'
					),
					'1.5.0'         => 'no'
				),
				'fil' => array(
					'name'          => __( 'Filled', 'ds-icon-expansion' ),
					'quantity'      => 54,
					'value'         => get_option( 'agsdi_fil_icons' ),
					'path'          => self::$pluginDirUrl . '/icon-packs/ags-filled/single-color/',
					'icon_prefixes' => array(
						'agsdix-sfil' => '(54)'
					),
					'1.5.0'         => 'no'
				),
				'etl' => array(
					'name'          => __( 'Elegant Themes Line', 'ds-icon-expansion' ),
					'quantity'      => 100,
					'value'         => get_option( 'agsdi_etl_icons' ),
					'path'          => self::$pluginDirUrl . '/icon-packs/elegant-themes-line/single-color/',
					'icon_prefixes' => array(
						'agsdix-set' => '(100)'
					),
					'1.5.0'         => 'no'
				),
				'eth' => array(
					'name'          => __( 'Elegant Themes', 'ds-icon-expansion' ),
					'quantity'      => 360,
					'value'         => get_option( 'agsdi_eth_icons' ),
					'path'          => self::$pluginDirUrl . '/icon-packs/elegant-themes/single-color/',
					'icon_prefixes' => array(
						'agsdix-seth' => '(360)'
					),
					'free'          => true,
					// Icons that will be added to wysiwyg icon picker only
					// (because we don't want to repeat some icons)
					'tinymce_only'  => true,
					'1.5.0'         => 'no'
				),
			),
			'multicolor'   => array(
				'mul_mul' => array(
					'name'     => __( 'Multicolor', 'ds-icon-expansion' ),
					'quantity' => 48,
					'path'     => self::$pluginDirUrl . '/icon-packs/ags-multicolor/multicolor/',
					'preview'  => 'multicolor_multicolor.svg'
				),
				'mul_ele' => array(
					'name'     => __( 'Elegant', 'ds-icon-expansion' ),
					'quantity' => 51,
					'path'     => self::$pluginDirUrl . '/icon-packs/ags-elegant/multicolor/',
					'preview'  => 'multicolor_elegant.svg'
				),
				'mul_fil' => array(
					'name'     => __( 'Filled', 'ds-icon-expansion' ),
					'quantity' => 54,
					'path'     => self::$pluginDirUrl . '/icon-packs/ags-filled/multicolor/',
					'preview'  => 'multicolor_filled.svg'
				),
				'mul_han' => array(
					'name'     => __( 'Hand Drawn', 'ds-icon-expansion' ),
					'quantity' => 80,
					'path'     => self::$pluginDirUrl . '/icon-packs/ags-hand-drawn/multicolor/',
					'preview'  => 'multicolor_hand-drawn.svg'
				),
				'mul_lin' => array(
					'name'     => __( 'Lineal', 'ds-icon-expansion' ),
					'quantity' => 25,
					'path'     => self::$pluginDirUrl . '/icon-packs/ags-lineal/multicolor/',
					'preview'  => 'multicolor_lineal.svg'
				),
				'mul_out' => array(
					'name'     => __( 'Outline', 'ds-icon-expansion' ),
					'quantity' => 50,
					'path'     => self::$pluginDirUrl . '/icon-packs/ags-outline/multicolor/',
					'preview'  => 'multicolor_outline.svg'
				),
				'mul_ske' => array(
					'name'     => __( 'Sketch', 'ds-icon-expansion' ),
					'quantity' => 40,
					'path'     => self::$pluginDirUrl . '/icon-packs/ags-sketch/multicolor/',
					'preview'  => 'multicolor_sketch.svg'
				),
				'mul_tri' => array(
					'name'     => __( 'Tri Color', 'ds-icon-expansion' ),
					'quantity' => 54,
					'path'     => self::$pluginDirUrl . '/icon-packs/ags-tri-color/multicolor/',
					'preview'  => 'multicolor_tri-color.svg'
				),
				'mul_uni' => array(
					'name'     => __( 'Universal', 'ds-icon-expansion' ),
					'quantity' => 100,
					'path'     => self::$pluginDirUrl . '/icon-packs/ags-universal/multicolor/',
					'preview'  => 'multicolor_universal.svg'
				),
			)
		);

		// Don't change order
		self::$icons['single_color'] = array(
			
			// 'Free Outline'
			'fo' => array(
				'agsdi-aspengrovestudios','agsdi-wpgears','agsdi-message','agsdi-location','agsdi-message-2','agsdi-message-3','agsdi-mail','agsdi-gear','agsdi-zoom','agsdi-zoom-in','agsdi-zoom-out','agsdi-time','agsdi-wallet','agsdi-world','agsdi-bulb','agsdi-bulb-flash','agsdi-bulb-options','agsdi-calendar','agsdi-chat','agsdi-music','agsdi-video','agsdi-security-camera','agsdi-sound','agsdi-music-play','agsdi-video-play','agsdi-microphone','agsdi-cd','agsdi-coffee','agsdi-gift','agsdi-printer','agsdi-hand-watch','agsdi-alarm','agsdi-alarm-2','agsdi-calendar-check','agsdi-code','agsdi-learn','agsdi-globe','agsdi-warning','agsdi-cancel','agsdi-question','agsdi-error','agsdi-check-circle','agsdi-arrow-left-circle','agsdi-arrow-right-circle','agsdi-arrow-up-circle','agsdi-arrow-down-circle','agsdi-refresh','agsdi-share','agsdi-tag','agsdi-bookmark','agsdi-bookmark-star','agsdi-briefcase','agsdi-calculator','agsdi-id-card','agsdi-credit-card','agsdi-shop','agsdi-tshirt','agsdi-handbag','agsdi-clothing-handbag','agsdi-analysis','agsdi-chat-gear','agsdi-certificate','agsdi-medal','agsdi-ribbon','agsdi-star','agsdi-bullhorn','agsdi-target','agsdi-pie-chart','agsdi-bar-chart','agsdi-bar-chart-2','agsdi-bar-chart-3','agsdi-bar-chart-4','agsdi-bar-chart-5','agsdi-income','agsdi-piggy-bank','agsdi-bitcoin','agsdi-bitcoin-circle','agsdi-bitcoin-mining','agsdi-mining','agsdi-dollar','agsdi-dollar-circle','agsdi-dollar-bill','agsdi-binders','agsdi-house','agsdi-padlock','agsdi-padlock-open','agsdi-house-padlock','agsdi-cloud-padlock','agsdi-key','agsdi-keys','agsdi-eye','agsdi-eye-closed','agsdi-champagne','agsdi-rocket','agsdi-rocket-2','agsdi-rocket-3','agsdi-flag','agsdi-flag-2','agsdi-flag-3','agsdi-drop','agsdi-sun','agsdi-sun-cloud','agsdi-thermometer','agsdi-celsius','agsdi-sun-2','agsdi-cloud','agsdi-upload','agsdi-cloud-computing','agsdi-cloud-download','agsdi-cloud-check','agsdi-cursor','agsdi-mobile','agsdi-monitor','agsdi-browser','agsdi-laptop','agsdi-hamburger-menu','agsdi-hamburger-menu-circle','agsdi-download','agsdi-image','agsdi-file','agsdi-file-error','agsdi-file-add','agsdi-file-check','agsdi-file-download','agsdi-file-question','agsdi-file-cursor','agsdi-file-padlock','agsdi-file-heart','agsdi-file-jpg','agsdi-file-png','agsdi-file-pdf','agsdi-file-zip','agsdi-file-ai','agsdi-file-ps','agsdi-delete','agsdi-notebook','agsdi-notebook-2','agsdi-documents','agsdi-brochure','agsdi-clip','agsdi-align-center','agsdi-align-left','agsdi-align-justify','agsdi-align-right','agsdi-portrait','agsdi-landscape','agsdi-portrait-2','agsdi-wedding','agsdi-billboard','agsdi-flash','agsdi-crop','agsdi-message-heart','agsdi-adjust-square-vert','agsdi-adjust-circle-vert','agsdi-camera','agsdi-grid','agsdi-grid-copy','agsdi-layers','agsdi-ruler','agsdi-eyedropper','agsdi-aperture','agsdi-macro','agsdi-pin','agsdi-contrast','agsdi-battery-level-empty','agsdi-battery-level1','agsdi-battery-level2','agsdi-battery-level3','agsdi-usb-stick','agsdi-sd-card','agsdi-stethoscope','agsdi-vaccine','agsdi-hospital','agsdi-pills','agsdi-heart','agsdi-heartbeat','agsdi-hearts','agsdi-heart-leaf','agsdi-heart-leaf-2','agsdi-coffee-2','agsdi-hands','agsdi-book','agsdi-food-heart','agsdi-soup-heart','agsdi-food','agsdi-soup','agsdi-pencil','agsdi-people','agsdi-money-bag','agsdi-world-heart','agsdi-doctor','agsdi-person','agsdi-water-cycle','agsdi-sign','agsdi-hand-leaf','agsdi-gift-heart','agsdi-sleep','agsdi-hand-heart','agsdi-calendar-heart','agsdi-book-heart','agsdi-list','agsdi-leaves','agsdi-bread','agsdi-bread-heart','agsdi-animal-hands','agsdi-animal-heart','agsdi-dog','agsdi-cat','agsdi-bird','agsdi-dog-2','agsdi-cat-2','agsdi-transporter','agsdi-adjust-square-horiz','agsdi-adjust-circle-horiz','agsdi-square','agsdi-circle','agsdi-triangle','agsdi-pentagon','agsdi-hexagon','agsdi-heptagon','agsdi-refresh-2','agsdi-pause','agsdi-play','agsdi-fast-forward','agsdi-rewind','agsdi-previous','agsdi-next','agsdi-stop','agsdi-arrow-left','agsdi-arrow-right','agsdi-arrow-up','agsdi-arrow-down','agsdi-face-sad','agsdi-face-happy','agsdi-face-neutral','agsdi-messenger','agsdi-facebook','agsdi-facebook-like','agsdi-twitter','agsdi-google-plus','agsdi-linkedin','agsdi-pinterest','agsdi-tumblr','agsdi-instagram','agsdi-skype','agsdi-flickr','agsdi-myspace','agsdi-dribble','agsdi-vimeo','agsdi-500px','agsdi-behance','agsdi-bitbucket','agsdi-deviantart','agsdi-github','agsdi-github-2','agsdi-medium','agsdi-medium-2','agsdi-meetup','agsdi-meetup-2','agsdi-slack','agsdi-slack-2','agsdi-snapchat','agsdi-twitch','agsdi-rss','agsdi-rss-2','agsdi-paypal','agsdi-stripe','agsdi-youtube','agsdi-facebook-2','agsdi-twitter-2','agsdi-linkedin-2','agsdi-tumblr-2','agsdi-myspace-2','agsdi-slack-3','agsdi-github-3','agsdi-vimeo-2','agsdi-behance-2','agsdi-apple','agsdi-quora','agsdi-trello','agsdi-amazon','agsdi-reddit','agsdi-windows','agsdi-wordpress','agsdi-patreon','agsdi-patreon-2','agsdi-soundcloud','agsdi-spotify','agsdi-google-hangout','agsdi-dropbox','agsdi-tinder','agsdi-whatsapp','agsdi-adobe-cc','agsdi-android','agsdi-html5','agsdi-google-drive','agsdi-pinterest-2','agsdi-gmail','agsdi-google-wallet','agsdi-google-sheets','agsdi-twitch-2'
			),
			
			// Elegant Themes
			'eth' => array(
				'agsdix-seth-arrow_up','agsdix-seth-arrow_down','agsdix-seth-arrow_left','agsdix-seth-arrow_right','agsdix-seth-arrow_left-up','agsdix-seth-arrow_right-up','agsdix-seth-arrow_right-down','agsdix-seth-arrow_left-down','agsdix-seth-arrow-up-down','agsdix-seth-arrow_up-down_alt','agsdix-seth-arrow_left-right_alt','agsdix-seth-arrow_left-right','agsdix-seth-arrow_expand_alt2','agsdix-seth-arrow_expand_alt','agsdix-seth-arrow_condense','agsdix-seth-arrow_expand','agsdix-seth-arrow_move','agsdix-seth-arrow_carrot-up','agsdix-seth-arrow_carrot-down','agsdix-seth-arrow_carrot-left','agsdix-seth-arrow_carrot-right','agsdix-seth-arrow_carrot-2up','agsdix-seth-arrow_carrot-2down','agsdix-seth-arrow_carrot-2left','agsdix-seth-arrow_carrot-2right','agsdix-seth-arrow_carrot-up_alt2','agsdix-seth-arrow_carrot-down_alt2','agsdix-seth-arrow_carrot-left_alt2','agsdix-seth-arrow_carrot-right_alt2','agsdix-seth-arrow_carrot-2up_alt2','agsdix-seth-arrow_carrot-2down_alt2','agsdix-seth-arrow_carrot-2left_alt2','agsdix-seth-arrow_carrot-2right_alt2','agsdix-seth-arrow_triangle-up','agsdix-seth-arrow_triangle-down','agsdix-seth-arrow_triangle-left','agsdix-seth-arrow_triangle-right','agsdix-seth-arrow_triangle-up_alt2','agsdix-seth-arrow_triangle-down_alt2','agsdix-seth-arrow_triangle-left_alt2','agsdix-seth-arrow_triangle-right_alt2','agsdix-seth-arrow_back','agsdix-seth-icon_minus-06','agsdix-seth-icon_plus','agsdix-seth-icon_close','agsdix-seth-icon_check','agsdix-seth-icon_minus_alt2','agsdix-seth-icon_plus_alt2','agsdix-seth-icon_close_alt2','agsdix-seth-icon_check_alt2','agsdix-seth-icon_zoom-out_alt','agsdix-seth-icon_zoom-in_alt','agsdix-seth-icon_search','agsdix-seth-icon_box-empty','agsdix-seth-icon_box-selected','agsdix-seth-icon_minus-box','agsdix-seth-icon_plus-box','agsdix-seth-icon_box-checked','agsdix-seth-icon_circle-empty','agsdix-seth-icon_circle-slelected','agsdix-seth-icon_stop_alt2','agsdix-seth-icon_stop','agsdix-seth-icon_pause_alt2','agsdix-seth-icon_pause','agsdix-seth-icon_menu','agsdix-seth-icon_menu-square_alt2','agsdix-seth-icon_menu-circle_alt2','agsdix-seth-icon_ul','agsdix-seth-icon_ol','agsdix-seth-icon_adjust-horiz','agsdix-seth-icon_adjust-vert','agsdix-seth-icon_document_alt','agsdix-seth-icon_documents_alt','agsdix-seth-icon_pencil','agsdix-seth-icon_pencil-edit_alt','agsdix-seth-icon_pencil-edit','agsdix-seth-icon_folder-alt','agsdix-seth-icon_folder-open_alt','agsdix-seth-icon_folder-add_alt','agsdix-seth-icon_info_alt','agsdix-seth-icon_error-oct_alt','agsdix-seth-icon_error-circle_alt','agsdix-seth-icon_error-triangle_alt','agsdix-seth-icon_question_alt2','agsdix-seth-icon_question','agsdix-seth-icon_comment_alt','agsdix-seth-icon_chat_alt','agsdix-seth-icon_vol-mute_alt','agsdix-seth-icon_volume-low_alt','agsdix-seth-icon_volume-high_alt','agsdix-seth-icon_quotations','agsdix-seth-icon_quotations_alt2','agsdix-seth-icon_clock_alt','agsdix-seth-icon_lock_alt','agsdix-seth-icon_lock-open_alt','agsdix-seth-icon_key_alt','agsdix-seth-icon_cloud_alt','agsdix-seth-icon_cloud-upload_alt','agsdix-seth-icon_cloud-download_alt','agsdix-seth-icon_image','agsdix-seth-icon_images','agsdix-seth-icon_lightbulb_alt','agsdix-seth-icon_gift_alt','agsdix-seth-icon_house_alt','agsdix-seth-icon_genius','agsdix-seth-icon_mobile','agsdix-seth-icon_tablet','agsdix-seth-icon_laptop','agsdix-seth-icon_desktop','agsdix-seth-icon_camera_alt','agsdix-seth-icon_mail_alt','agsdix-seth-icon_cone_alt','agsdix-seth-icon_ribbon_alt','agsdix-seth-icon_bag_alt','agsdix-seth-icon_creditcard','agsdix-seth-icon_cart_alt','agsdix-seth-icon_paperclip','agsdix-seth-icon_tag_alt','agsdix-seth-icon_tags_alt','agsdix-seth-icon_trash_alt','agsdix-seth-icon_cursor_alt','agsdix-seth-icon_mic_alt','agsdix-seth-icon_compass_alt','agsdix-seth-icon_pin_alt','agsdix-seth-icon_pushpin_alt','agsdix-seth-icon_map_alt','agsdix-seth-icon_drawer_alt','agsdix-seth-icon_toolbox_alt','agsdix-seth-icon_book_alt','agsdix-seth-icon_calendar','agsdix-seth-icon_film','agsdix-seth-icon_table','agsdix-seth-icon_contacts_alt','agsdix-seth-icon_headphones','agsdix-seth-icon_lifesaver','agsdix-seth-icon_piechart','agsdix-seth-icon_refresh','agsdix-seth-icon_link_alt','agsdix-seth-icon_link','agsdix-seth-icon_loading','agsdix-seth-icon_blocked','agsdix-seth-icon_archive_alt','agsdix-seth-icon_heart_alt','agsdix-seth-icon_star_alt','agsdix-seth-icon_star-half_alt','agsdix-seth-icon_star','agsdix-seth-icon_star-half','agsdix-seth-icon_tools','agsdix-seth-icon_tool','agsdix-seth-icon_cog','agsdix-seth-icon_cogs','agsdix-seth-arrow_up_alt','agsdix-seth-arrow_down_alt','agsdix-seth-arrow_left_alt','agsdix-seth-arrow_right_alt','agsdix-seth-arrow_left-up_alt','agsdix-seth-arrow_right-up_alt','agsdix-seth-arrow_right-down_alt','agsdix-seth-arrow_left-down_alt','agsdix-seth-arrow_condense_alt','agsdix-seth-arrow_expand_alt3','agsdix-seth-arrow_carrot_up_alt','agsdix-seth-arrow_carrot-down_alt','agsdix-seth-arrow_carrot-left_alt','agsdix-seth-arrow_carrot-right_alt','agsdix-seth-arrow_carrot-2up_alt','agsdix-seth-arrow_carrot-2dwnn_alt','agsdix-seth-arrow_carrot-2left_alt','agsdix-seth-arrow_carrot-2right_alt','agsdix-seth-arrow_triangle-up_alt','agsdix-seth-arrow_triangle-down_alt','agsdix-seth-arrow_triangle-left_alt','agsdix-seth-arrow_triangle-right_alt','agsdix-seth-icon_minus_alt','agsdix-seth-icon_plus_alt','agsdix-seth-icon_close_alt','agsdix-seth-icon_check_alt','agsdix-seth-icon_zoom-out','agsdix-seth-icon_zoom-in','agsdix-seth-icon_stop_alt','agsdix-seth-icon_menu-square_alt','agsdix-seth-icon_menu-circle_alt','agsdix-seth-icon_document','agsdix-seth-icon_documents','agsdix-seth-icon_pencil_alt','agsdix-seth-icon_folder','agsdix-seth-icon_folder-open','agsdix-seth-icon_folder-add','agsdix-seth-icon_folder_upload','agsdix-seth-icon_folder_download','agsdix-seth-icon_info','agsdix-seth-icon_error-circle','agsdix-seth-icon_error-oct','agsdix-seth-icon_error-triangle','agsdix-seth-icon_question_alt','agsdix-seth-icon_comment','agsdix-seth-icon_chat','agsdix-seth-icon_vol-mute','agsdix-seth-icon_volume-low','agsdix-seth-icon_volume-high','agsdix-seth-icon_quotations_alt','agsdix-seth-icon_clock','agsdix-seth-icon_lock','agsdix-seth-icon_lock-open','agsdix-seth-icon_key','agsdix-seth-icon_cloud','agsdix-seth-icon_cloud-upload','agsdix-seth-icon_cloud-download','agsdix-seth-icon_lightbulb','agsdix-seth-icon_gift','agsdix-seth-icon_house','agsdix-seth-icon_camera','agsdix-seth-icon_mail','agsdix-seth-icon_cone','agsdix-seth-icon_ribbon','agsdix-seth-icon_bag','agsdix-seth-icon_cart','agsdix-seth-icon_tag','agsdix-seth-icon_tags','agsdix-seth-icon_trash','agsdix-seth-icon_cursor','agsdix-seth-icon_mic','agsdix-seth-icon_compass','agsdix-seth-icon_pin','agsdix-seth-icon_pushpin','agsdix-seth-icon_map','agsdix-seth-icon_drawer','agsdix-seth-icon_toolbox','agsdix-seth-icon_book','agsdix-seth-icon_contacts','agsdix-seth-icon_archive','agsdix-seth-icon_heart','agsdix-seth-icon_profile','agsdix-seth-icon_group','agsdix-seth-icon_grid-2x2','agsdix-seth-icon_grid-3x3','agsdix-seth-icon_music','agsdix-seth-icon_pause_alt','agsdix-seth-icon_phone','agsdix-seth-icon_upload','agsdix-seth-icon_download','agsdix-seth-social_facebook','agsdix-seth-social_twitter','agsdix-seth-social_pinterest','agsdix-seth-social_googleplus','agsdix-seth-social_tumblr','agsdix-seth-social_tumbleupon','agsdix-seth-social_wordpress','agsdix-seth-social_instagram','agsdix-seth-social_dribbble','agsdix-seth-social_vimeo','agsdix-seth-social_linkedin','agsdix-seth-social_rss','agsdix-seth-social_deviantart','agsdix-seth-social_share','agsdix-seth-social_myspace','agsdix-seth-social_skype','agsdix-seth-social_youtube','agsdix-seth-social_picassa','agsdix-seth-social_googledrive','agsdix-seth-social_flickr','agsdix-seth-social_blogger','agsdix-seth-social_spotify','agsdix-seth-social_delicious','agsdix-seth-social_facebook_circle','agsdix-seth-social_twitter_circle','agsdix-seth-social_pinterest_circle','agsdix-seth-social_googleplus_circle','agsdix-seth-social_tumblr_circle','agsdix-seth-social_stumbleupon_circle','agsdix-seth-social_wordpress_circle','agsdix-seth-social_instagram_circle','agsdix-seth-social_dribbble_circle','agsdix-seth-social_vimeo_circle','agsdix-seth-social_linkedin_circle','agsdix-seth-social_rss_circle','agsdix-seth-social_deviantart_circle','agsdix-seth-social_share_circle','agsdix-seth-social_myspace_circle','agsdix-seth-social_skype_circle','agsdix-seth-social_youtube_circle','agsdix-seth-social_picassa_circle','agsdix-seth-social_googledrive_alt2','agsdix-seth-social_flickr_circle','agsdix-seth-social_blogger_circle','agsdix-seth-social_spotify_circle','agsdix-seth-social_delicious_circle','agsdix-seth-social_facebook_square','agsdix-seth-social_twitter_square','agsdix-seth-social_pinterest_square','agsdix-seth-social_googleplus_square','agsdix-seth-social_tumblr_square','agsdix-seth-social_stumbleupon_square','agsdix-seth-social_wordpress_square','agsdix-seth-social_instagram_square','agsdix-seth-social_dribbble_square','agsdix-seth-social_vimeo_square','agsdix-seth-social_linkedin_square','agsdix-seth-social_rss_square','agsdix-seth-social_deviantart_square','agsdix-seth-social_share_square','agsdix-seth-social_myspace_square','agsdix-seth-social_skype_square','agsdix-seth-social_youtube_square','agsdix-seth-social_picassa_square','agsdix-seth-social_googledrive_square','agsdix-seth-social_flickr_square','agsdix-seth-social_blogger_square','agsdix-seth-social_spotify_square','agsdix-seth-social_delicious_square','agsdix-seth-icon_printer','agsdix-seth-icon_calulator','agsdix-seth-icon_building','agsdix-seth-icon_floppy','agsdix-seth-icon_drive','agsdix-seth-icon_search-2','agsdix-seth-icon_id','agsdix-seth-icon_id-2','agsdix-seth-icon_puzzle','agsdix-seth-icon_like','agsdix-seth-icon_dislike','agsdix-seth-icon_mug','agsdix-seth-icon_currency','agsdix-seth-icon_wallet','agsdix-seth-icon_pens','agsdix-seth-icon_easel','agsdix-seth-icon_flowchart','agsdix-seth-icon_datareport','agsdix-seth-icon_briefcase','agsdix-seth-icon_shield','agsdix-seth-icon_percent','agsdix-seth-icon_globe','agsdix-seth-icon_globe-2','agsdix-seth-icon_target','agsdix-seth-icon_hourglass','agsdix-seth-icon_balance','agsdix-seth-icon_rook','agsdix-seth-icon_printer-alt','agsdix-seth-icon_calculator_alt','agsdix-seth-icon_building_alt','agsdix-seth-icon_floppy_alt','agsdix-seth-icon_drive_alt','agsdix-seth-icon_search_alt','agsdix-seth-icon_id_alt','agsdix-seth-icon_id-2_alt','agsdix-seth-icon_puzzle_alt','agsdix-seth-icon_like_alt','agsdix-seth-icon_dislike_alt','agsdix-seth-icon_mug_alt','agsdix-seth-icon_currency_alt','agsdix-seth-icon_wallet_alt','agsdix-seth-icon_pens_alt','agsdix-seth-icon_easel_alt','agsdix-seth-icon_flowchart_alt','agsdix-seth-icon_datareport_alt','agsdix-seth-icon_briefcase_alt','agsdix-seth-icon_shield_alt','agsdix-seth-icon_percent_alt','agsdix-seth-icon_globe_alt','agsdix-seth-icon_clipboard',
			)
		);

		
		if ( $isAdmin ) {
			include( self::$pluginDir . 'admin/notices/admin-notices.php' );
		}
		include( self::$pluginDir . 'ags-divi-icons-pages/AGS_Divi_Icons_Pages.php' );
		if ( class_exists( 'AGS_Divi_Icons_Pages' ) ) {
			self::$agsDiviIconsPages = new AGS_Divi_Icons_Pages();
		}
		$actionClassName = defined( 'AGS_DIVI_ICONS_PRO' ) ? 'AGS_Divi_Icons' : 'AGS_Divi_Icons_Pro';

		add_action( 'admin_menu', array( 'AGS_Divi_Icons', 'adminMenu' ), 11 );
		add_action( 'load-plugins.php', array( 'AGS_Divi_Icons', 'onLoadPluginsPhp' ) );
		add_action( 'admin_enqueue_scripts', array( 'AGS_Divi_Icons', 'adminScripts' ) );
		add_action( 'wp_ajax_agsdi_get_icons', array( 'AGS_Divi_Icons', 'getOrderedIconsAjax' ) );

		// Load translations
		load_plugin_textdomain( 'ds-icon-expansion', false, self::$pluginDir . 'languages' );

		if ( $isAdmin ) {
			$settings = get_option( 'agsdi-icon-expansion' );
		}
		

			

			

			add_action( 'et_fb_framework_loaded', array( 'AGS_Divi_Icons', 'adminScripts' ) );
			add_filter( 'et_pb_font_icon_symbols', array( 'AGS_Divi_Icons', 'addIcons' ) );
			add_filter( 'mce_external_plugins', array( 'AGS_Divi_Icons', 'mcePlugins' ) );
			add_filter( 'mce_buttons', array( 'AGS_Divi_Icons', 'mceButtons' ) );
			add_filter( 'mce_css', array( 'AGS_Divi_Icons', 'mceStyles' ) );
			add_filter( 'et_fb_get_asset_helpers', array( __CLASS__, 'setIconFilteringCategories' ), 11 );

			if ( self::loadFontAwesomeInSelectedPages() ) {
				wp_enqueue_style( 'ags-divi-icons', self::$pluginDirUrl . '/css/icons.css', array(), self::PLUGIN_VERSION );
				wp_enqueue_script( 'ags-divi-icons', self::$pluginDirUrl . '/js/icons.js', array( 'jquery' ), self::PLUGIN_VERSION );

				

				foreach ( self::$icon_packs['single_color'] as $prefix => $pack ) {
						if ( $pack['value'] == 'yes' && $prefix !== 'fa' ) {
							wp_enqueue_style( 'ags-divi-icons-' . $prefix . '-icons', $pack['path'] . 'agsdi-icons.css', null, self::PLUGIN_VERSION );
							wp_enqueue_script( 'ags-divi-icons-' . $prefix, $pack['path'] . 'agsdi-icons.js', null, self::PLUGIN_VERSION );
						}
				}

			}

			$ags_divi_icons_config = array(
				'pluginDirUrl' => self::$pluginDirUrl
			);

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only GET variable checks
			if ( ! empty( $_GET['et_fb'] ) || ( is_admin() && isset( $_GET['page'] ) && $_GET['page'] == 'et_theme_builder' ) ) { // in frontend builder or theme builder
				wp_enqueue_script( 'ags-divi-icons-editor', self::$pluginDirUrl . '/js/tinymce-plugin.js', array(
					'jquery',
					'react-tiny-mce',
					'wp-i18n'
				), self::PLUGIN_VERSION );                                                                                        // redid second dependency 2019-10-11, good
				$ags_divi_icons_config['mceStyles'] = self::mceStyles( '' );
			}

			if ( self::loadFontAwesomeInSelectedPages() ) {
				
				wp_localize_script( 'ags-divi-icons', 'ags_divi_icons_config', $ags_divi_icons_config );
			}
			
			require_once(__DIR__.'/blocks/blocks.php');

			

		$option_plugin_version = 'AGS_DIVI_ICONS_PRO' ? 'agsdi_version' : 'agsdi_free_version';
		if ( get_option( $option_plugin_version ) != self::PLUGIN_VERSION ) {

			

			// Fix: free version had pro options enabled
			if ( version_compare( get_option( 'agsdi_free_version', 0 ), '1.5.0', '<' ) ) {
				delete_option('agsdi_fa_icons' );
				delete_option('agsdi_mc_icons' );
				delete_option('agsdi_md_icons' );
				delete_option('agsdi_ui_icons' );
				delete_option('agsdi_np_icons' );
				delete_option('agsdi_cs_icons' );
			}

			

			

			update_option( $option_plugin_version, self::PLUGIN_VERSION );
		}

	}

	public static function on_activation() {

		add_option( 'ds-icon-expansion_first_activate', time(), false );

		if ( get_option( 'AGS_DIVI_ICONS_PRO' ? 'agsdi_version' : 'agsdi_free_version' ) === false ) { // this is the first ever activation

			add_option( 'agsdi_fo_icons', 'yes' );
			add_option( 'agsdi_eth_icons', 'yes' );


			

			add_option( 'AGS_DIVI_ICONS_PRO' ? 'agsdi_version' : 'agsdi_free_version', self::PLUGIN_VERSION );
		}

	}

	public static function loadFontAwesomeInSelectedPages() {
		if ( is_admin() ) {
			if ( self::$agsDiviIconsPages->isAllowedPages() ||
			     self::$agsDiviIconsPages->IsDiviBuilderAllowedPages() ||
			     self::$agsDiviIconsPages->isDiviLayout() ) {
				return true;
			}

			return false;
		}

		// Currently loading on all non-admin pages
		return true;

		/*
		if( self::$agsDiviIconsPages->isFrontendPostsOrPages()){
			return true;
		}

	    return false;
		*/
	}

	public static function adminMenu() {
		add_options_page( self::PLUGIN_NAME, self::PLUGIN_NAME, 'install_plugins', 'admin.php?page=ds-icon-expansion' );
		add_submenu_page( 'admin.php', self::PLUGIN_NAME, self::PLUGIN_NAME,
			'install_plugins', 'ds-icon-expansion', array( 'AGS_Divi_Icons', 'adminPage' ) );
		add_submenu_page( 'et_divi_options', self::PLUGIN_NAME, self::PLUGIN_NAME,
			'install_plugins', 'ds-icon-expansion', array( 'AGS_Divi_Icons', 'adminPage' ) );
		add_submenu_page( 'et_extra_options', self::PLUGIN_NAME, self::PLUGIN_NAME,
			'install_plugins', 'ds-icon-expansion', array( 'AGS_Divi_Icons', 'adminPage' ) );
	}

	public static function adminPage() {
		include( self::$pluginDir . 'admin/admin.php' );
	}

	// Add settings link on plugin page
	public static function pluginActionLinks( $links ) {
		
		
		$custom_links = esc_html__( 'Instructions', 'ds-icon-expansion' );
		

		array_unshift( $links, '<a href="admin.php?page=ds-icon-expansion">' . $custom_links . '</a>' );

		return $links;
	}

	public static function onLoadPluginsPhp() {
		$plugin = plugin_basename( __FILE__ );
		add_filter( 'plugin_action_links_' . $plugin, array( 'AGS_Divi_Icons', 'pluginActionLinks' ) );
	}

	public static function addIcons( $existingIcons ) {
		$icons = self::getOrderedIcons();

		return array_merge( $existingIcons, $icons );
	}

	public static function getOrderedIconsAjax() {
		wp_send_json_success( array_merge( self::getOrderedIcons(), self::getTinyMCEIcons() ) );
	}

	public static function getOrderedIcons() {
		$icons = self::getIcons();
		

		if ( isset( $proIcons ) ) {
			return array_merge( $icons, $proIcons );
		} else {
			return $icons;
		}
	}

	public static function getIcons() {

		$isDisabled = empty( self::$icon_packs['single_color']['fo']['value'] ) || self::$icon_packs['single_color']['fo']['value'] !== 'yes';

		if ( $isDisabled && ! empty( get_option( 'agsdi-legacy-sets-loading' ) ) ) {
			return array();
		}

		$icons = self::$icons['single_color']['fo'];


		return $isDisabled
			? array_fill( 0, count( $icons ), 'agsdix-null' )
			: $icons;

	}

	

	public static function getTinyMCEIcons() {
		$icons = array();
		foreach ( self::$icon_packs['single_color'] as $prefix => $pack ) {
			$tinymce = ! empty ( $pack['tinymce_only'] ) && $pack['tinymce_only'];
			if ( $pack['value'] === 'yes' ) {
				$icons = self::$icons['single_color'][ $prefix ];
			}
		}

		return $icons;
	}


	


	public static function adminScripts() {
		wp_enqueue_style( 'admin-ags-divi-icons-multicolor-icons', self::$pluginDirUrl . '/icon-packs/ags-multicolor/agsdi-icons.css', array(), self::PLUGIN_VERSION );
		wp_enqueue_style( 'ags-divi-icons-admin', self::$pluginDirUrl . '/css/admin.min.css', array(), self::PLUGIN_VERSION );
		wp_enqueue_script( 'ags-divi-icons-admin', self::$pluginDirUrl . '/js/admin' . ( defined( 'ET_BUILDER_PRODUCT_VERSION' ) && version_compare( ET_BUILDER_PRODUCT_VERSION, '4.13', '<' ) ? '-old' : '' ) . '.js', array(
			'jquery',
			'wp-i18n'
		), self::PLUGIN_VERSION );
		wp_set_script_translations( 'ags-divi-icons-admin', 'ds-icon-expansion', dirname( __FILE__ ) . '/languages' );

		//wp_enqueue_script('ags-divi-icons-tinymce', self::$pluginDirUrl.'/js/tinymce-plugin.js', array('tinymce'), self::PLUGIN_VERSION);
		wp_localize_script( 'ags-divi-icons-admin', 'ags_divi_icons_tinymce_config', array(
			
			'styleInheritMessage' => esc_html__( 'If you leave the color and/or size settings blank, the icon will derive its color and size from the surrounding text\'s color and size (based on the styling of the icon\'s parent element). This is not reflected in the icon preview.', 'ds-icon-expansion' )
		) );
		wp_localize_script( 'ags-divi-icons-admin', 'ags_divi_icons_credit_promos', self::getCreditPromos( 'icon-picker' ) );

		global $pagenow;
		if ( isset( $pagenow ) && $pagenow == 'admin.php' ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			// ags,free-product-addons
			
			
			wp_enqueue_style( 'ags-wadip-addons-admin', self::$pluginDirUrl . '/admin/addons/css/admin-ags.css', array(), self::PLUGIN_VERSION );
			
		}


		// RankMath SEO plugin compatibility
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- just checking if et_fb is set
		if ( class_exists( 'RankMath\\Divi\\Divi' ) && ! empty( $_GET['et_fb'] ) ) {
			add_filter( 'script_loader_tag', function ( $tag ) {
				return strpos( $tag, 'i18n' ) ? str_replace( 'et_fb_ignore_iframe', '', $tag ) : $tag;
			} );
		}
	}

	public static function mcePlugins( $plugins ) {
		$plugins['agsdi_icons'] = self::$pluginDirUrl . '/js/tinymce-plugin.js';

		return $plugins;
	}

	public static function mceButtons( $toolbarButtons ) {
		$toolbarButtons[] = 'agsdi_icons';

		return $toolbarButtons;
	}

	public static function mceStyles( $styles ) {

		$styles .= ( empty( $styles ) ? '' : ',' ) . self::$pluginDirUrl . '/css/icons.css';

		foreach ( self::$icon_packs['single_color'] as $pack ) {
			if ( $pack['value'] === 'yes' ) {
				$styles .= ',' . $pack['path'] . 'agsdi-icons.css';
			}
		}
		

		
		$styles .= ',' . self::$pluginDirUrl . '/icon-packs/free-icons/agsdi-icons.css';

		

		return $styles;
	}

	public static function getCreditPromos( $context, $all = false ) {
		/*
		$creditPromos array format:
		First level of the array is requirements (promo only shown if true)
		Second level of the array is exclusions (promo only shown if false)
		Third level of the array is promos themselves

		Requirements/exclusions have the following format:
		*  - no requirement/exclusion
		p: - require active plugin / exclude if plugin installed
		t: - require active parent theme (case-insensitive) / exclude if theme installed (case-sensitive, does not check if theme is parent or child)
		c: - require active child theme (case-insensitive) / exclude if theme installed (case-sensitive, does not check if theme is parent or child)

		Promos may be specifed as single promo or array of promos
		*/
		$contextSafe = esc_attr( $context );
		$utmVars     = 'utm_source=' . self::PLUGIN_SLUG . '&amp;utm_medium=plugin-ad&amp;utm_content=' . $contextSafe . '&amp;utm_campaign=';

		$creditPromos = array(
			'*'             => array(
				'*'         => array(
					sprintf( esc_html__( '%sSubscribe%s to Aspen Grove Studios emails for the latest news, updates, special offers, and more!', 'ds-icon-expansion' ), '<a href="https://aspengrovestudios.com/?' . $utmVars . 'subscribe-general#main-footer" target="_blank">', '</a>' ),
				),
				'p:testify' =>
					sprintf( esc_html__( 'Create an engaging testimonial section for your website with %s! ', 'ds-icon-expansion' ), '<a href="https://divi.space/product/testify/?' . $utmVars . 'testify" target="_blank">Testify</a>' )
			),
			't:Divi'        => array(
				'*'                 => array(
					sprintf( esc_html__( '%sSign up%s for emails from %sDivi Space%s to receive news, updates, special offers, and more!', 'ds-icon-expansion' ), '<a href="https://divi.space/?' . $utmVars . 'subscribe-general#main-footer" target="_blank">', '</a>', '<strong>', '</strong>' ),
					sprintf( esc_html__( 'Get child themes, must-have Divi plugins & exclusive content with the %sDivi Space membership%s!', 'ds-icon-expansion' ), '<a href="https://divi.space/product/annual-membership/?' . $utmVars . 'annual-membership" target="_blank">', '</a>' ),
				),
				'p:divi-switch'     => sprintf( esc_html__( 'Take your Divi website to new heights with %s, the Swiss Army Knife for Divi!', 'ds-icon-expansion' ), '<a href="https://divi.space/product/divi-switch/?' . $utmVars . 'divi-switch" target="_blank">Divi Switch</a>' ),
				'p:ds-divi-extras'  => sprintf( esc_html__( 'Get blog modules from the Extra theme in the Divi Builder with %s!', 'ds-icon-expansion' ), '<a href="https://divi.space/product/divi-extras/?' . $utmVars . 'divi-extras" target="_blank">Divi Extras</a>' ),
				'c:diviecommerce'   => sprintf( esc_html__( 'Create an impactful online presence for your online store with the %sdivi ecommerce child theme%s!', 'ds-icon-expansion' ), '<a href="https://divi.space/product/divi-ecommerce/?' . $utmVars . 'divi-ecommerce" target="_blank">', '</a>' ),
				'c:divibusinesspro' => sprintf( esc_html__( 'Showcase your business in a memorable & engaging way with the %sDivi Business Pro child theme%s!', 'ds-icon-expansion' ), '<a href="https://divi.space/product/divi-business-pro/?' . $utmVars . 'divi-business-pro" target="_blank">', '</a>' ),
			),
			'p:woocommerce' => array(
				'p:hm-product-sales-report-pro' => sprintf( esc_html__( 'Need a powerful sales reporting tool for WooCommerce? Check out %s!', 'ds-icon-expansion' ), '<a href="https://aspengrovestudios.com/product/product-sales-report-pro-for-woocommerce/?' . $utmVars . 'product-sales-report-pro" target="_blank">Product Sales Report Pro</a>' ),
			),
			'p:bbpress'     => array(
				'p:image-upload-for-bbpress-pro' => sprintf( esc_html__( 'Let your forum users upload images into their posts with %s!', 'ds-icon-expansion' ), '<a href="https://aspengrovestudios.com/product/image-upload-for-bbpress-pro/?' . $utmVars . 'image-upload-for-bbpress-pro" target="_blank">Image Upload for bbPress Pro</a>' ),
			)
		);

		$myCreditPromos = array();
		if ( $all ) {
			$otherPromos = array();
		}

		foreach ( $creditPromos as $require => $requirePromos ) {
			unset( $isOtherPromos );
			if ( $require != '*' ) {
				switch ( $require[0] ) {
					case 'p':
						if ( ! is_plugin_active( substr( $require, 2 ) ) ) {
							if ( $all ) {
								$isOtherPromos = true;
							} else {
								continue 2;
							}
						}
						break;
					case 't':
						if ( ! isset( $parentTheme ) ) {
							$parentTheme = get_template();
						}
						if ( strcasecmp( $parentTheme, substr( $require, 2 ) ) ) {
							if ( $all ) {
								$isOtherPromos = true;
							} else {
								continue 2;
							}
						}
						break;
					case 'c':
						if ( ! isset( $childTheme ) ) {
							$childTheme = get_stylesheet();
						}
						if ( strcasecmp( $childTheme, substr( $require, 2 ) ) ) {
							if ( $all ) {
								$isOtherPromos = true;
							} else {
								continue 2;
							}
						}
						break;
					default:
						if ( $all ) {
							$isOtherPromos = true;
						} else {
							continue 2;
						}
				}
			}

			foreach ( $requirePromos as $exclude => $promos ) {
				if ( empty( $isOtherPromos ) ) {
					unset( $isExcluded );
					if ( $exclude != '*' ) {
						switch ( $exclude[0] ) {
							case 'p':
								if ( is_dir( self::$pluginDir . '../' . substr( $exclude, 2 ) ) ) {
									if ( $all ) {
										$isExcluded = true;
									} else {
										continue 2;
									}
								}
								break;
							case 't':
							case 'c':
								if ( ! isset( $themes ) ) {
									$themes = search_theme_directories();
								}
								if ( isset( $themes[ substr( $exclude, 2 ) ] ) ) {
									if ( $all ) {
										$isExcluded = true;
									} else {
										continue 2;
									}
								}
								break;
							default:
								if ( $all ) {
									$isExcluded = true;
								} else {
									continue 2;
								}
						}
					}
				}

				if ( empty( $isOtherPromos ) && empty( $isExcluded ) ) {
					if ( is_array( $promos ) ) {
						$myCreditPromos = array_merge( $myCreditPromos, $promos );
					} else {
						$myCreditPromos[] = $promos;
					}
				} else {
					if ( is_array( $promos ) ) {
						$otherPromos = array_merge( $otherPromos, $promos );
					} else {
						$otherPromos[] = $promos;
					}
				}


			}
		}

		return $all ? array_merge( $myCreditPromos, $otherPromos ) : $myCreditPromos;
	}

	public static function onPluginFirstActivate() {
		if ( class_exists( 'AGS_Divi_Icons_Pro' ) ) {
			AGS_Divi_Icons_Pro::onPluginFirstActivate();
		}
	}

	public static function setIconFilteringCategories( $helpers ) {

		// "searchFilterIconItems":{"show_only":{"solid":"Solid Icons","line":"Line Icons","divi":"Divi Icons","fa":"Font Awesome"}}
		$helpers = [ $helpers ];

		

		// Add our filtering categories for single color icons
		foreach ( self::$icon_packs['single_color'] as $prefix => $pack ) {
			if ( ! empty( $pack['value'] ) && $pack['value'] === 'yes' && ! isset ( $pack['tinymce_only'] ) ) {
				if ( $prefix === 'fo' ) {
					$helpers[] = 'ETBuilderBackend.searchFilterIconItems.show_only.agsdi=' . json_encode( esc_html( $pack['name'] ) );
				} elseif ( ! empty ( $pack['icon_prefixes'] ) ) {
					foreach ( $pack['icon_prefixes'] as $icon_prefix => $subname ) {
						$helpers[] = 'ETBuilderBackend.searchFilterIconItems.show_only[\'' . str_replace( '"', "", json_encode( esc_attr( $icon_prefix ) ) ) . '\']=' . json_encode( esc_html( $pack['name'] . ' ' . $subname ) );
					}
				}
			}

		}

		return implode( ';', $helpers );
	}

}



if ( class_exists( 'AGS_Divi_Icons_Pro' ) ) {
	add_action( 'init', array( 'AGS_Divi_Icons_Pro', 'init' ) );

	// Temporary measure to assist with backwards compatibility
	if ( get_option( 'agsdi-legacy-sets-loading', null ) === null ) {
		$needsLegacy = false;
		if ( get_option( 'aspengrove_icons_colors_slots', null ) === null && get_option( 'agsdi_fa_icons', null ) !== null ) {

			// First of all, fix screwed up options via mapping
			update_option( 'agsdi_md_icons', get_option( 'agsdi_ui_icons', 'no' ) );
			update_option( 'agsdi_ui_icons', get_option( 'agsdi_fo_icons', 'no' ) );
			update_option( 'agsdi_fo_icons', 'yes' );

			$options = [
				'agsdi_fo_icons',
				'agsdi_mc_icons',
				'agsdi_fa_icons',
				'agsdi_md_icons',
				'agsdi_ui_icons',
				'agsdi_np_icons',
				'agsdi_cs_icons'
			];

			$hadDisabled = false;

			foreach ( $options as $option ) {
				$optionValue = get_option( $option, '' );
				if ( $optionValue !== 'yes' ) {
					$hadDisabled = true;
				} else if ( $hadDisabled ) { // option is yes and a previous set was disabled
					$needsLegacy = true;
					break;
				}
			}

		}

		update_option( 'agsdi-legacy-sets-loading', $needsLegacy ? 1 : 0 );

	}
} else {
	add_action( 'init', array( 'AGS_Divi_Icons', 'init' ) );
}

if ( ! function_exists( 'et_pb_get_extended_font_icon_symbols' ) ) {
	function et_pb_get_extended_font_icon_symbols() {
		$icons = array_map( function ( $icon ) {
			$isWadiIcon = substr( $icon, 0, 5 ) == 'agsdi';

			if ( ! $isWadiIcon ) {
				$iconCategories = [ 'divi' ];
			} else if ( $icon[5] == 'x' ) {
				if ( substr( $icon, 0, 9 ) == 'agsdix-fa' ) {
					$iconCategories = [ 'agsdix-fa', 'agsdix-fa' . $icon[9] ];
				} else {
					$iconCategories = [ 'agsdix-' . strstr( substr( $icon, 7 ), '-', true ) ];
					if ( $iconCategories[0] == 'agsdix-smt1' || $iconCategories[0] == 'agsdix-smt2' ) {
						$iconCategories[0] = 'agsdix-smt';
					}
				}
			} else { // WADI free icon
				$iconCategories = [ 'agsdi' ];
			}

			return [
				'search_terms' => $isWadiIcon ? str_replace( '-', ' ', substr( $icon, strpos( $icon, '-' ) + 1 ) ) : '',
				'unicode'      => html_entity_decode( $icon ),
				'name'         => '',
				'styles'       => $iconCategories,
				'is_divi_icon' => ! $isWadiIcon,
				'font_weight'  => 400
			];
		}, et_pb_get_font_icon_symbols() );

		

		// Add back icon packs from Divi, besides ETModules
		$wpFilesystem = et_()->WPFS();
		if ( defined('ET_BUILDER_DIR') && $wpFilesystem->exists(ET_BUILDER_DIR.'feature/icon-manager/full_icons_list.json') ) {
			$diviIcons = json_decode( $wpFilesystem->get_contents( ET_BUILDER_DIR.'feature/icon-manager/full_icons_list.json' ), true );

			if (is_array($diviIcons)) {
				foreach ($diviIcons as $icon) {
					if (empty($icon['is_divi_icon'])) {
						$icons[] = $icon;
					}
				}
			}

		}

		


		return $icons;
	}
}

if ( ! function_exists( 'et_pb_get_extended_font_icon_value' ) ) {
	function et_pb_get_extended_font_icon_value( $icon, $decoded = false ) {
		$iconValue = strstr( $icon, '||', true );
		if ( $iconValue === false ) {
			$iconValue = $icon;
		}
		$processedIcon = et_pb_process_font_icon( $iconValue );

		return $decoded ? htmlspecialchars_decode( $processedIcon ) : $processedIcon;
	}
}

///*
//Following code is copied from the Divi theme by Elegant Themes (v3.10): includes/builder/functions.php and modified.
//Licensed under the GNU General Public License version 3 (see license.txt file in plugin root for license text)
//*/
//if ( ! function_exists( 'et_pb_get_font_icon_list' ) ) :
//function et_pb_get_font_icon_list() {
//	$output = is_customize_preview() ? et_pb_get_font_icon_list_items() : '<%= window.et_builder.font_icon_list_template() %>';
//
//	$output = sprintf( '<ul class="et_font_icon">%1$s</ul>', $output );
//
//	// Following lines were added
//	$output = '<input type="search" placeholder="' . esc_html__( 'Search icons...', 'ds-icon-expansion' ) . '" class="agsdi-picker-search-divi" oninput="agsdi_search(this);">'
//	          . $output
//	          . '<span class="agsdi-picker-credit">'
//	          . ( defined( 'AGS_DIVI_ICONS_PRO' ) ?
//			sprintf( esc_html__( 'With additional icons from %s by %s ', 'ds-icon-expansion' ),'<strong>' . AGS_Divi_Icons::PLUGIN_NAME . '</strong>', '<a href="' . AGS_Divi_Icons::PLUGIN_AUTHOR_URL . '?utm_source=' . AGS_Divi_Icons::PLUGIN_SLUG . '&amp;utm_medium=plugin-credit-link&amp;utm_content=divi-builder" target="_blank">' . AGS_Divi_Icons::PLUGIN_AUTHOR . '</a>' ) : sprintf( esc_html__( 'With WP and Divi Icons by %s', 'ds-icon-expansion' ), '<a href="' . AGS_Divi_Icons::PLUGIN_AUTHOR_URL . '?utm_source=' . AGS_Divi_Icons::PLUGIN_SLUG . '&amp;utm_medium=plugin-credit-link&amp;utm_content=divi-builder" target="_blank">' . AGS_Divi_Icons::PLUGIN_AUTHOR . '</a><span class="agsdi-picker-credit-promo"></span>' ) )
//	          . '</span>';
//
//	return $output;
//}
//endif;
///* End code copied from the Divi theme by Elegant Themes */
