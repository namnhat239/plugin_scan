<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// add admin options page
function vsel_menu_page() {
    add_options_page( esc_attr__( 'VSEL', 'very-simple-event-list' ), esc_attr__( 'VSEL', 'very-simple-event-list' ), 'manage_options', 'vsel', 'vsel_options_page' );
}
add_action( 'admin_menu', 'vsel_menu_page' );

// add admin settings and such
function vsel_admin_init() {
	// general section
	add_settings_section( 'vsel-general-section', esc_attr__( 'General', 'very-simple-event-list' ), '', 'vsel-general' );

	add_settings_field( 'vsel-field', esc_attr__( 'Uninstall', 'very-simple-event-list' ), 'vsel_field_callback', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-38', esc_attr__( 'Date format', 'very-simple-event-list' ), 'vsel_field_callback_38', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-38', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-92', esc_attr__( 'Time format', 'very-simple-event-list' ), 'vsel_field_callback_92', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-92', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-58', esc_attr__( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_58', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-58', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-87', esc_attr__( 'Time', 'very-simple-event-list' ), 'vsel_field_callback_87', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-87', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-94', esc_attr__( 'Time', 'very-simple-event-list' ), 'vsel_field_callback_94', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-94', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-69', esc_attr__( 'Date separator', 'very-simple-event-list' ), 'vsel_field_callback_69', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-69', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-88', esc_attr__( 'Time separator', 'very-simple-event-list' ), 'vsel_field_callback_88', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-88', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-70', esc_attr__( 'Category separator', 'very-simple-event-list' ), 'vsel_field_callback_70', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-70', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-55', esc_attr__( 'Event category', 'very-simple-event-list' ), 'vsel_field_callback_55', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-55', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-56', esc_attr__( 'Time', 'very-simple-event-list' ), 'vsel_field_callback_56', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-56', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-57', esc_attr__( 'Location', 'very-simple-event-list' ), 'vsel_field_callback_57', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-57', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-61', esc_attr__( 'Order', 'very-simple-event-list' ), 'vsel_field_callback_61', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-61', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-50', esc_attr__( 'Menu', 'very-simple-event-list' ), 'vsel_field_callback_50', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-50', array('sanitize_callback' => 'sanitize_key') );	

	add_settings_field( 'vsel-field-60', esc_attr__( 'Single event', 'very-simple-event-list' ), 'vsel_field_callback_60', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-60', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-39', esc_attr__( 'Single event', 'very-simple-event-list' ), 'vsel_field_callback_39', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-39', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-48', esc_attr__( 'Post type archive', 'very-simple-event-list' ), 'vsel_field_callback_48', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-48', array('sanitize_callback' => 'sanitize_key') );	

	add_settings_field( 'vsel-field-43', esc_attr__( 'Post type archive', 'very-simple-event-list' ), 'vsel_field_callback_43', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-43', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-40', esc_attr__( 'Event category', 'very-simple-event-list' ), 'vsel_field_callback_40', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-40', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-41', esc_attr__( 'Search results', 'very-simple-event-list' ), 'vsel_field_callback_41', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-41', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-46', esc_attr__( 'Single event base', 'very-simple-event-list' ), 'vsel_field_callback_46', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-46', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-47', esc_attr__( 'Event category base', 'very-simple-event-list' ), 'vsel_field_callback_47', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-47', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-49', esc_attr__( 'Feed', 'very-simple-event-list' ), 'vsel_field_callback_49', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-49', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-93', esc_attr__( 'Feed', 'very-simple-event-list' ), 'vsel_field_callback_93', 'vsel-general', 'vsel-general-section' );
	register_setting( 'vsel-general-options', 'vsel-setting-93', array('sanitize_callback' => 'sanitize_text_field') );

	// page section
	add_settings_section( 'vsel-page-section', esc_attr__( 'Page', 'very-simple-event-list' ), '', 'vsel-page' );

	add_settings_field( 'vsel-field-66', esc_attr__( 'Event details', 'very-simple-event-list' ), 'vsel_field_callback_66', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-66', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-35', esc_attr__( 'Event details', 'very-simple-event-list' ), 'vsel_field_callback_35', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-35', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-59', esc_attr__( 'Title', 'very-simple-event-list' ), 'vsel_field_callback_59', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-59', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-9', esc_attr__( 'Title', 'very-simple-event-list' ), 'vsel_field_callback_9', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-9', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-62', esc_attr__( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_62', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-62', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-68', esc_attr__( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_68', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-68', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-15', esc_attr__( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_15', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-15', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-36', esc_attr__( 'Featured image', 'very-simple-event-list' ), 'vsel_field_callback_36', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-36', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-30', esc_attr__( 'Featured image', 'very-simple-event-list' ), 'vsel_field_callback_30', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-30', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-53', esc_attr__( 'Featured image', 'very-simple-event-list' ), 'vsel_field_callback_53', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-53', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-29', esc_attr__( 'Featured image', 'very-simple-event-list' ), 'vsel_field_callback_29', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-29', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-13', esc_attr__( 'Event info', 'very-simple-event-list' ), 'vsel_field_callback_13', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-13', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-44', esc_attr__( 'Event category', 'very-simple-event-list' ), 'vsel_field_callback_44', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-44', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-64', esc_attr__( 'Title', 'very-simple-event-list' ), 'vsel_field_callback_64', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-64', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-8', esc_attr__( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_8', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-8', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-11', esc_attr__( 'Time', 'very-simple-event-list' ), 'vsel_field_callback_11', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-11', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-12', esc_attr__( 'Location', 'very-simple-event-list' ), 'vsel_field_callback_12', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-12', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-33', esc_attr__( 'Event category', 'very-simple-event-list' ), 'vsel_field_callback_33', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-33', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-10', esc_attr__( 'Link to more info', 'very-simple-event-list' ), 'vsel_field_callback_10', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-10', array('sanitize_callback' => 'sanitize_key') );

	if( class_exists('acf') ) {
		add_settings_field( 'vsel-field-51', esc_attr__( 'ACF fields', 'very-simple-event-list' ), 'vsel_field_callback_51', 'vsel-page', 'vsel-page-section' );
		register_setting( 'vsel-page-options', 'vsel-setting-51', array('sanitize_callback' => 'sanitize_key') );
	}

	add_settings_field( 'vsel-field-27', esc_attr__( 'Featured image', 'very-simple-event-list' ), 'vsel_field_callback_27', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-27', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-28', esc_attr__( 'Event info', 'very-simple-event-list' ), 'vsel_field_callback_28', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-28', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-42', esc_attr__( 'Pagination', 'very-simple-event-list' ), 'vsel_field_callback_42', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-42', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-16', esc_attr__( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_16', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-16', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-17', esc_attr__( 'Start date', 'very-simple-event-list' ), 'vsel_field_callback_17', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-17', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-18', esc_attr__( 'End date', 'very-simple-event-list' ), 'vsel_field_callback_18', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-18', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-19', esc_attr__( 'Time', 'very-simple-event-list' ), 'vsel_field_callback_19', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-19', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-89', esc_attr__( 'All-day event', 'very-simple-event-list' ), 'vsel_field_callback_89', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-89', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-20', esc_attr__( 'Location', 'very-simple-event-list' ), 'vsel_field_callback_20', 'vsel-page', 'vsel-page-section' );
	register_setting( 'vsel-page-options', 'vsel-setting-20', array('sanitize_callback' => 'sanitize_text_field') );

	// widget section
	add_settings_section( 'vsel-widget-section', esc_attr__( 'Widget', 'very-simple-event-list' ), 'vsel_widget_section_callback', 'vsel-widget' );

	add_settings_field( 'vsel-field-14', esc_attr__( 'Title', 'very-simple-event-list' ), 'vsel_field_callback_14', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-14', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-63', esc_attr__( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_63', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-63', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-67', esc_attr__( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_67', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-67', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-21', esc_attr__( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_21', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-21', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-37', esc_attr__( 'Featured image', 'very-simple-event-list' ), 'vsel_field_callback_37', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-37', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-32', esc_attr__( 'Featured image', 'very-simple-event-list' ), 'vsel_field_callback_32', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-32', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-54', esc_attr__( 'Featured image', 'very-simple-event-list' ), 'vsel_field_callback_54', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-54', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-31', esc_attr__( 'Featured image', 'very-simple-event-list' ), 'vsel_field_callback_31', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-31', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-1', esc_attr__( 'Event info', 'very-simple-event-list' ), 'vsel_field_callback_1', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-1', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-45', esc_attr__( 'Event category', 'very-simple-event-list' ), 'vsel_field_callback_45', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-45', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-65', esc_attr__( 'Title', 'very-simple-event-list' ), 'vsel_field_callback_65', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-65', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-2', esc_attr__( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_2', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-2', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-3', esc_attr__( 'Time', 'very-simple-event-list' ), 'vsel_field_callback_3', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-3', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-4', esc_attr__( 'Location', 'very-simple-event-list' ), 'vsel_field_callback_4', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-4', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-34', esc_attr__( 'Event category', 'very-simple-event-list' ), 'vsel_field_callback_34', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-34', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-6', esc_attr__( 'Link to more info', 'very-simple-event-list' ), 'vsel_field_callback_6', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-6', array('sanitize_callback' => 'sanitize_key') );

	if( class_exists('acf') ) {
		add_settings_field( 'vsel-field-52', esc_attr__( 'ACF fields', 'very-simple-event-list' ), 'vsel_field_callback_52', 'vsel-widget', 'vsel-widget-section' );
		register_setting( 'vsel-widget-options', 'vsel-setting-52', array('sanitize_callback' => 'sanitize_key') );
	}

	add_settings_field( 'vsel-field-5', esc_attr__( 'Featured image', 'very-simple-event-list' ), 'vsel_field_callback_5', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-5', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-7', esc_attr__( 'Event info', 'very-simple-event-list' ), 'vsel_field_callback_7', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-7', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-22', esc_attr__( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_22', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-22', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-23', esc_attr__( 'Start date', 'very-simple-event-list' ), 'vsel_field_callback_23', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-23', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-24', esc_attr__( 'End date', 'very-simple-event-list' ), 'vsel_field_callback_24', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-24', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-25', esc_attr__( 'Time', 'very-simple-event-list' ), 'vsel_field_callback_25', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-25', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-90', esc_attr__( 'All-day event', 'very-simple-event-list' ), 'vsel_field_callback_90', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-90', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-26', esc_attr__( 'Location', 'very-simple-event-list' ), 'vsel_field_callback_26', 'vsel-widget', 'vsel-widget-section' );
	register_setting( 'vsel-widget-options', 'vsel-setting-26', array('sanitize_callback' => 'sanitize_text_field') );

	// single event section
	add_settings_section( 'vsel-single-section', esc_attr__( 'Single event', 'very-simple-event-list' ), 'vsel_single_section_callback', 'vsel-single' );

	add_settings_field( 'vsel-field-71', esc_attr__( 'Event details', 'very-simple-event-list' ), 'vsel_field_callback_71', 'vsel-single', 'vsel-single-section' );
	register_setting( 'vsel-single-options', 'vsel-setting-71', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-72', esc_attr__( 'Event details', 'very-simple-event-list' ), 'vsel_field_callback_72', 'vsel-single', 'vsel-single-section' );
	register_setting( 'vsel-single-options', 'vsel-setting-72', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-74', esc_attr__( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_74', 'vsel-single', 'vsel-single-section' );
	register_setting( 'vsel-single-options', 'vsel-setting-74', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-75', esc_attr__( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_75', 'vsel-single', 'vsel-single-section' );
	register_setting( 'vsel-single-options', 'vsel-setting-75', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-73', esc_attr__( 'Event category', 'very-simple-event-list' ), 'vsel_field_callback_73', 'vsel-single', 'vsel-single-section' );
	register_setting( 'vsel-single-options', 'vsel-setting-73', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-86', esc_attr__( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_86', 'vsel-single', 'vsel-single-section' );
	register_setting( 'vsel-single-options', 'vsel-setting-86', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-76', esc_attr__( 'Time', 'very-simple-event-list' ), 'vsel_field_callback_76', 'vsel-single', 'vsel-single-section' );
	register_setting( 'vsel-single-options', 'vsel-setting-76', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-77', esc_attr__( 'Location', 'very-simple-event-list' ), 'vsel_field_callback_77', 'vsel-single', 'vsel-single-section' );
	register_setting( 'vsel-single-options', 'vsel-setting-77', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-78', esc_attr__( 'Event category', 'very-simple-event-list' ), 'vsel_field_callback_78', 'vsel-single', 'vsel-single-section' );
	register_setting( 'vsel-single-options', 'vsel-setting-78', array('sanitize_callback' => 'sanitize_key') );

	add_settings_field( 'vsel-field-79', esc_attr__( 'Link to more info', 'very-simple-event-list' ), 'vsel_field_callback_79', 'vsel-single', 'vsel-single-section' );
	register_setting( 'vsel-single-options', 'vsel-setting-79', array('sanitize_callback' => 'sanitize_key') );

	if( class_exists('acf') ) {
		add_settings_field( 'vsel-field-80', esc_attr__( 'ACF fields', 'very-simple-event-list' ), 'vsel_field_callback_80', 'vsel-single', 'vsel-single-section' );
		register_setting( 'vsel-single-options', 'vsel-setting-80', array('sanitize_callback' => 'sanitize_key') );
	}

	add_settings_field( 'vsel-field-81', esc_attr__( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_81', 'vsel-single', 'vsel-single-section' );
	register_setting( 'vsel-single-options', 'vsel-setting-81', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-82', esc_attr__( 'Start date', 'very-simple-event-list' ), 'vsel_field_callback_82', 'vsel-single', 'vsel-single-section' );
	register_setting( 'vsel-single-options', 'vsel-setting-82', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-83', esc_attr__( 'End date', 'very-simple-event-list' ), 'vsel_field_callback_83', 'vsel-single', 'vsel-single-section' );
	register_setting( 'vsel-single-options', 'vsel-setting-83', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-84', esc_attr__( 'Time', 'very-simple-event-list' ), 'vsel_field_callback_84', 'vsel-single', 'vsel-single-section' );
	register_setting( 'vsel-single-options', 'vsel-setting-84', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-91', esc_attr__( 'All-day event', 'very-simple-event-list' ), 'vsel_field_callback_91', 'vsel-single', 'vsel-single-section' );
	register_setting( 'vsel-single-options', 'vsel-setting-91', array('sanitize_callback' => 'sanitize_text_field') );

	add_settings_field( 'vsel-field-85', esc_attr__( 'Location', 'very-simple-event-list' ), 'vsel_field_callback_85', 'vsel-single', 'vsel-single-section' );
	register_setting( 'vsel-single-options', 'vsel-setting-85', array('sanitize_callback' => 'sanitize_text_field') );
}
add_action( 'admin_init', 'vsel_admin_init' );

// section callbacks
function vsel_widget_section_callback() {
	?>
	<p><?php esc_attr_e( 'Event details section and Event info section are full width when using the widget.', 'very-simple-event-list' ); ?></p>
	<?php
}

function vsel_single_section_callback() {
	?>
	<p><?php esc_attr_e( 'Support for the Single event page and Single event template must be active.', 'very-simple-event-list' ); ?></p>
	<p><?php esc_attr_e( 'Title, Event info and Featured image are handled by your theme.', 'very-simple-event-list' ); ?></p>
	<?php
}

// general field callbacks
function vsel_field_callback() {
	$value = get_option( 'vsel-setting' );
	?>
	<input type='hidden' name='vsel-setting' value='no'>
	<label><input type='checkbox' name='vsel-setting' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Do not delete events and settings.', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_38() {
	$placeholder = get_option( 'date_format' );
	$value = get_option( 'vsel-setting-38' );
	?>
	<input type='text' size='40' maxlength='10' name='vsel-setting-38' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<?php
}

function vsel_field_callback_92() {
	$placeholder = get_option( 'time_format' );
	$value = get_option( 'vsel-setting-92' );
	?>
	<input type='text' size='40' maxlength='10' name='vsel-setting-92' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<?php
}

function vsel_field_callback_58() {
	$value = get_option( 'vsel-setting-58' );
	?>
	<input type='hidden' name='vsel-setting-58' value='no'>
	<label><input type='checkbox' name='vsel-setting-58' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'One date instead of start date and end date.', 'very-simple-event-list' ); ?></label>
	<p><i><?php esc_attr_e( 'Resaving an existing event will reset start date and end date.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_87() {
	$value = get_option( 'vsel-setting-87' );
	?>
	<input type='hidden' name='vsel-setting-87' value='no'>
	<label><input type='checkbox' name='vsel-setting-87' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'One time field instead of start time and end time.', 'very-simple-event-list' ); ?></label>
	<p><i><?php esc_attr_e( 'This will disable ordering by time.', 'very-simple-event-list' ); ?></i></p>
	<p><i><?php esc_attr_e( 'Resaving an existing event will reset start time and end time.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_94() {
	$value = get_option( 'vsel-setting-94' );
	?>
	<input type='hidden' name='vsel-setting-94' value='no'>
	<label><input type='checkbox' name='vsel-setting-94' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide equal start time and end time in frontend.', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_69() {
	$placeholder = '-';
	$value = get_option( 'vsel-setting-69' );
	?>
	<input type='text' size='40' maxlength='10' name='vsel-setting-69' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<?php
}

function vsel_field_callback_88() {
	$placeholder = '-';
	$value = get_option( 'vsel-setting-88' );
	?>
	<input type='text' size='40' maxlength='10' name='vsel-setting-88' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<?php
}

function vsel_field_callback_70() {
	$placeholder = '|';
	$value = get_option( 'vsel-setting-70' );
	?>
	<input type='text' size='40' maxlength='10' name='vsel-setting-70' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<?php
}

function vsel_field_callback_55() {
	$value = get_option( 'vsel-setting-55' );
	?>
	<input type='hidden' name='vsel-setting-55' value='no'>
	<label><input type='checkbox' name='vsel-setting-55' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Disable column in dashboard.', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_56() {
	$value = get_option( 'vsel-setting-56' );
	?>
	<input type='hidden' name='vsel-setting-56' value='no'>
	<label><input type='checkbox' name='vsel-setting-56' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Disable column in dashboard.', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_57() {
	$value = get_option( 'vsel-setting-57' );
	?>
	<input type='hidden' name='vsel-setting-57' value='no'>
	<label><input type='checkbox' name='vsel-setting-57' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Disable column in dashboard.', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_61() {
	$value = get_option( 'vsel-setting-61' );
	?>
	<input type='hidden' name='vsel-setting-61' value='no'>
	<label><input type='checkbox' name='vsel-setting-61' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Disable column in dashboard.', 'very-simple-event-list' ); ?></label>
	<p><i><?php esc_attr_e( 'This column shows your custom order for events with the same date.', 'very-simple-event-list' ); ?></i></p>
	<p><i><?php esc_attr_e( 'Custom order is not needed when event has start time and end time.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_50() {
	$value = get_option( 'vsel-setting-50' );
	?>
	<input type='hidden' name='vsel-setting-50' value='no'>
	<label><input type='checkbox' name='vsel-setting-50' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php printf( esc_attr__( 'Disable support for the %s page.', 'very-simple-event-list' ), __( 'Menu', 'very-simple-event-list' ) ); ?></label>
	<p><i><?php esc_attr_e( 'This support is added to make VSEL compatible with page builder plugins.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_60() {
	$value = get_option( 'vsel-setting-60' );
	?>
	<input type='hidden' name='vsel-setting-60' value='no'>
	<label><input type='checkbox' name='vsel-setting-60' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php printf( esc_attr__( 'Disable support for the %s page.', 'very-simple-event-list' ), __( 'Single event', 'very-simple-event-list' ) ); ?></label>
	<?php
}

function vsel_field_callback_39() {
	$value = get_option( 'vsel-setting-39' );
	?>
	<input type='hidden' name='vsel-setting-39' value='no'>
	<label><input type='checkbox' name='vsel-setting-39' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php printf( esc_attr__( 'Disable support for the %s template.', 'very-simple-event-list' ), __( 'Single event', 'very-simple-event-list' ) ); ?></label>
	<?php
}

function vsel_field_callback_48() {
	$value = get_option( 'vsel-setting-48' );
	$link_label = __( 'Permalinks', 'very-simple-event-list' );
	$link_permalinks = '<a href="'.admin_url( 'options-permalink.php' ).'">'.$link_label.'</a>';
	?>
	<input type='hidden' name='vsel-setting-48' value='no'>
	<label><input type='checkbox' name='vsel-setting-48' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php printf( esc_attr__( 'Disable support for the %s page.', 'very-simple-event-list' ), __( 'Post type archive', 'very-simple-event-list' ) ); ?></label>
	<p><strong><i><?php printf( esc_attr__( 'Resave the %s after changing this.', 'very-simple-event-list' ), $link_permalinks ); ?></i></strong></p>
	<p><i><?php printf( esc_attr__( 'The %s page is not generated by VSEL. Events are not ordered by event date.', 'very-simple-event-list' ), __( 'Post type archive', 'very-simple-event-list' ) ); ?></i></p>
	<p><i><?php esc_attr_e( 'This support is added to make VSEL compatible with page builder plugins.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_43() {
	$value = get_option( 'vsel-setting-43' );
	?>
	<input type='hidden' name='vsel-setting-43' value='no'>
	<label><input type='checkbox' name='vsel-setting-43' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php printf( esc_attr__( 'Disable support for the %s template.', 'very-simple-event-list' ), __( 'Post type archive', 'very-simple-event-list' ) ); ?></label>
	<p><i><?php printf( esc_attr__( 'The %s page is not generated by VSEL. Events are not ordered by event date.', 'very-simple-event-list' ), __( 'Post type archive', 'very-simple-event-list' ) ); ?></i></p>
	<p><i><?php esc_attr_e( 'This support is added to make VSEL compatible with page builder plugins.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_40() {
	$value = get_option( 'vsel-setting-40' );
	?>
	<input type='hidden' name='vsel-setting-40' value='no'>
	<label><input type='checkbox' name='vsel-setting-40' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php printf( esc_attr__( 'Disable support for the %s template.', 'very-simple-event-list' ), __( 'Event category', 'very-simple-event-list' ) ); ?></label>
	<p><i><?php printf( esc_attr__( 'The %s page is not generated by VSEL. Events are not ordered by event date.', 'very-simple-event-list' ), __( 'Event category', 'very-simple-event-list' ) ); ?></i></p>
	<p><i><?php esc_attr_e( 'This support is added to make VSEL compatible with page builder plugins.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_41() {
	$value = get_option( 'vsel-setting-41' );
	?>
	<input type='hidden' name='vsel-setting-41' value='no'>
	<label><input type='checkbox' name='vsel-setting-41' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php printf( esc_attr__( 'Disable support for the %s template.', 'very-simple-event-list' ), __( 'Search results', 'very-simple-event-list' ) ); ?></label>
	<p><i><?php printf( esc_attr__( 'The %s page is not generated by VSEL. Events are not ordered by event date.', 'very-simple-event-list' ), __( 'Search results', 'very-simple-event-list' ) ); ?></i></p>
	<p><i><?php esc_attr_e( 'This support is added to make VSEL compatible with page builder plugins.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_46() {
	$placeholder = 'event';
	$value = get_option( 'vsel-setting-46' );
	?>
	<input type='text' size='40' maxlength='25' name='vsel-setting-46' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<?php
	$link_label = __( 'Permalinks', 'very-simple-event-list' );
	$link_permalinks = '<a href="'.admin_url( 'options-permalink.php' ).'">'.$link_label.'</a>';
	?>
	<p><strong><i><?php printf( esc_attr__( 'Resave the %s after changing this.', 'very-simple-event-list' ), $link_permalinks ); ?></i></strong></p>
	<?php
}

function vsel_field_callback_47() {
	$placeholder = 'event_cat';
	$value = get_option( 'vsel-setting-47' );
	?>
	<input type='text' size='40' maxlength='25' name='vsel-setting-47' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<?php
	$link_label = __( 'Permalinks', 'very-simple-event-list' );
	$link_permalinks = '<a href="'.admin_url( 'options-permalink.php' ).'">'.$link_label.'</a>';
	?>
	<p><strong><i><?php printf( esc_attr__( 'Resave the %s after changing this.', 'very-simple-event-list' ), $link_permalinks ); ?></i></strong></p>
	<?php
}

function vsel_field_callback_49() {
	$value = get_option( 'vsel-setting-49' );
	?>
	<input type='hidden' name='vsel-setting-49' value='no'>
	<label><input type='checkbox' name='vsel-setting-49' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Activate iCal feed.', 'very-simple-event-list' ); ?></label>
	<?php
	$link_label = __( 'Permalinks', 'very-simple-event-list' );
	$link_permalinks = '<a href="'.admin_url( 'options-permalink.php' ).'">'.$link_label.'</a>';
	?>
	<p><strong><i><?php printf( esc_attr__( 'Resave the %s after changing this.', 'very-simple-event-list' ), $link_permalinks ); ?></i></strong></p>
	<?php
	$feed_link_1 = '<code>'.get_bloginfo('url').'/feed/vsel-events</code>';
	$feed_link_2 = '<code>'.get_bloginfo('url').'/?feed=vsel-events</code>';
	?>
	<p><i><?php printf( __( 'Subscribe with URL %1$s or %2$s.', 'very-simple-event-list' ), $feed_link_1, $feed_link_2 ); ?></i></p>
	<?php
}

function vsel_field_callback_93() {
	$value = get_option( 'vsel-setting-93' );
	$placeholder = '10';
	?>
	<label><input type='number' size='10' min='5' max='100' maxlength='3' name='vsel-setting-93' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' /> <?php printf( esc_attr__( 'Default value is %s.', 'very-simple-event-list' ), '10' ); ?></label>
	<p><i><?php esc_attr_e( 'Number of events in iCal feed (from future to past).', 'very-simple-event-list' ); ?></i></p>
	<?php
}

// page field callbacks
function vsel_field_callback_66() {
	$value = get_option( 'vsel-setting-66' );
	$placeholder = '36';
	?>
	<label><input type='number' size='10' min='20' max='60' maxlength='3' name='vsel-setting-66' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' /> <?php printf( esc_attr__( 'Default value is %s.', 'very-simple-event-list' ), '36' ); ?></label>
	<p><i><?php esc_attr_e( 'This is the width of the Event details section.', 'very-simple-event-list' ); ?></i></p>
	<p><i><?php esc_attr_e( 'Section is full width when Event info and Featured image are hidden.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_35() {
	$value = get_option( 'vsel-setting-35' );
 	?>
	<select id='vsel-setting-35' name='vsel-setting-35'>
		<option value='left'<?php echo (esc_attr($value) == 'left')?' selected':''; ?>><?php esc_attr_e( 'Align left', 'very-simple-event-list' ); ?></option>
		<option value='right'<?php echo (esc_attr($value) == 'right')?' selected':''; ?>><?php esc_attr_e( 'Align right', 'very-simple-event-list' ); ?></option>
	</select>
	<?php printf( esc_attr__( 'Default value is %s.', 'very-simple-event-list' ), __( 'Align left', 'very-simple-event-list' ) ); ?>
	<p><i><?php esc_attr_e( 'Section is full width when Event info and Featured image are hidden.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_59() {
	$value = get_option( 'vsel-setting-59' );
	?>
	<input type='hidden' name='vsel-setting-59' value='no'>
	<label><input type='checkbox' name='vsel-setting-59' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Display on top (outside Event details section).', 'very-simple-event-list' ); ?></label>
	<p><i><?php esc_attr_e( 'This only affects pages where you have added the shortcode.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_9() {
	$value = get_option( 'vsel-setting-9' );
	?>
	<input type='hidden' name='vsel-setting-9' value='no'>
	<label><input type='checkbox' name='vsel-setting-9' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Link to the event page.', 'very-simple-event-list' ); ?></label>
	<p><i><?php esc_attr_e( 'This only affects pages where you have added the shortcode.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_62() {
	$value = get_option( 'vsel-setting-62' );
	?>
	<input type='hidden' name='vsel-setting-62' value='no'>
	<label><input type='checkbox' name='vsel-setting-62' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Show a date icon instead of a label.', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_68() {
	$value = get_option( 'vsel-setting-68' );
	?>
	<input type='hidden' name='vsel-setting-68' value='no'>
	<label><input type='checkbox' name='vsel-setting-68' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Combine date icon and title.', 'very-simple-event-list' ); ?></label>
	<p><strong><i><?php esc_attr_e( 'Date icon must be active.', 'very-simple-event-list' ); ?></i></strong></p>
	<p><strong><i><?php esc_attr_e( 'Title and date must not be hidden.', 'very-simple-event-list' ); ?></i></strong></p>
	<p><strong><i><?php esc_attr_e( 'Title must not be displayed on top.', 'very-simple-event-list' ); ?></i></strong></p>
	<p><i><?php esc_attr_e( 'This only affects pages where you have added the shortcode.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_15() {
	$value = get_option( 'vsel-setting-15' );
	?>
	<input type='hidden' name='vsel-setting-15' value='no'>
	<label><input type='checkbox' name='vsel-setting-15' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Combine start date and end date in one label.', 'very-simple-event-list' ); ?></label>
	<p><i><?php esc_attr_e( 'This does not affect the date icons.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_36() {
	$value = get_option( 'vsel-setting-36' );
 	?>
	<select id='vsel-setting-36' name='vsel-setting-36'>
		<option value='right'<?php echo (esc_attr($value) == 'right')?' selected':''; ?>><?php esc_attr_e( 'Align right', 'very-simple-event-list' ); ?></option>
		<option value='left'<?php echo (esc_attr($value) == 'left')?' selected':''; ?>><?php esc_attr_e( 'Align left', 'very-simple-event-list' ); ?></option>
	</select>
	<?php printf( esc_attr__( 'Default value is %s.', 'very-simple-event-list' ), __( 'Align right', 'very-simple-event-list' ) ); ?>
	<p><i><?php esc_attr_e( 'This only affects pages where you have added the shortcode.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_30() {
	$value = get_option( 'vsel-setting-30' );
 	?>
	<select id='vsel-setting-30' name='vsel-setting-30'>
		<option value='post-thumbnail'<?php echo (esc_attr($value) == 'post-thumbnail')?' selected':''; ?>><?php esc_attr_e( 'Post thumbnail', 'very-simple-event-list' ); ?></option>
		<option value='full'<?php echo (esc_attr($value) == 'full')?' selected':''; ?>><?php esc_attr_e( 'Full', 'very-simple-event-list' ); ?></option>
		<option value='large'<?php echo (esc_attr($value) == 'large')?' selected':''; ?>><?php esc_attr_e( 'Large', 'very-simple-event-list' ); ?></option>
		<option value='medium'<?php echo (esc_attr($value) == 'medium')?' selected':''; ?>><?php esc_attr_e( 'Medium', 'very-simple-event-list' ); ?></option>
		<option value='small'<?php echo (esc_attr($value) == 'small')?' selected':''; ?>><?php esc_attr_e( 'Small', 'very-simple-event-list' ); ?></option>
	</select>
	<?php printf( esc_attr__( 'Default value is %s.', 'very-simple-event-list' ), __( 'Post thumbnail', 'very-simple-event-list' ) ); ?>
	<p><i><?php esc_attr_e( 'This size is being used as source for the featured image.', 'very-simple-event-list' ); ?></i></p>
	<p><i><?php esc_attr_e( 'The size of the Post thumbnail may vary by theme.', 'very-simple-event-list' ); ?></i></p>
	<p><i><?php esc_attr_e( 'This only affects pages where you have added the shortcode.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_53() {
	$value = get_option( 'vsel-setting-53' );
	$placeholder = '40';
	?>
	<label><input type='number' size='10' min='20' max='100' maxlength='3' name='vsel-setting-53' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' /> <?php printf( esc_attr__( 'Default value is %s.', 'very-simple-event-list' ), '40' ); ?></label>
	<p><i><?php esc_attr_e( 'This is the maximum width of the featured image.', 'very-simple-event-list' ); ?></i></p>
	<p><i><?php esc_attr_e( 'This only affects pages where you have added the shortcode.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_29() {
	$value = get_option( 'vsel-setting-29' );
	?>
	<input type='hidden' name='vsel-setting-29' value='no'>
	<label><input type='checkbox' name='vsel-setting-29' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Link to the event page.', 'very-simple-event-list' ); ?></label>
	<p><i><?php esc_attr_e( 'This only affects pages where you have added the shortcode.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_13() {
	$value = get_option( 'vsel-setting-13' );
	?>
	<input type='hidden' name='vsel-setting-13' value='no'>
	<label><input type='checkbox' name='vsel-setting-13' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Show a summary instead of all event info.', 'very-simple-event-list' ); ?></label>
	<p><i><?php esc_attr_e( 'This only affects pages where you have added the shortcode.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_44() {
	$value = get_option( 'vsel-setting-44' );
	?>
	<input type='hidden' name='vsel-setting-44' value='no'>
	<label><input type='checkbox' name='vsel-setting-44' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Link to the category page.', 'very-simple-event-list' ); ?></label>
	<p><i><?php printf( esc_attr__( 'The %s page is not generated by VSEL. Events are not ordered by event date.', 'very-simple-event-list' ), __( 'Event category', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

function vsel_field_callback_64() {
	$value = get_option( 'vsel-setting-64' );
	?>
	<input type='hidden' name='vsel-setting-64' value='no'>
	<label><input type='checkbox' name='vsel-setting-64' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<p><i><?php esc_attr_e( 'This only affects pages where you have added the shortcode.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_8() {
	$value = get_option( 'vsel-setting-8' );
	?>
	<input type='hidden' name='vsel-setting-8' value='no'>
	<label><input type='checkbox' name='vsel-setting-8' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_11() {
	$value = get_option( 'vsel-setting-11' );
	?>
	<input type='hidden' name='vsel-setting-11' value='no'>
	<label><input type='checkbox' name='vsel-setting-11' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_12() {
	$value = get_option( 'vsel-setting-12' );
	?>
	<input type='hidden' name='vsel-setting-12' value='no'>
	<label><input type='checkbox' name='vsel-setting-12' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_33() {
	$value = get_option( 'vsel-setting-33' );
	?>
	<input type='hidden' name='vsel-setting-33' value='no'>
	<label><input type='checkbox' name='vsel-setting-33' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_10() {
	$value = get_option( 'vsel-setting-10' );
	?>
	<input type='hidden' name='vsel-setting-10' value='no'>
	<label><input type='checkbox' name='vsel-setting-10' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_51() {
	$value = get_option( 'vsel-setting-51' );
	?>
	<input type='hidden' name='vsel-setting-51' value='no'>
	<label><input type='checkbox' name='vsel-setting-51' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_27() {
	$value = get_option( 'vsel-setting-27' );
	?>
	<input type='hidden' name='vsel-setting-27' value='no'>
	<label><input type='checkbox' name='vsel-setting-27' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<p><i><?php esc_attr_e( 'This only affects pages where you have added the shortcode.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_28() {
	$value = get_option( 'vsel-setting-28' );
	?>
	<input type='hidden' name='vsel-setting-28' value='no'>
	<label><input type='checkbox' name='vsel-setting-28' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_42() {
	$value = get_option( 'vsel-setting-42' );
	?>
	<input type='hidden' name='vsel-setting-42' value='no'>
	<label><input type='checkbox' name='vsel-setting-42' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<p><i><?php esc_attr_e( 'This only affects pages where you have added the shortcode.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_16() {
	$placeholder = __( 'Date: %s', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-16' );
	?>
	<input type='text' size='40' name='vsel-setting-16' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php printf( esc_attr__( 'Use %1$s to display the %2$s variable.', 'very-simple-event-list' ), '%s', __( 'Date', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

function vsel_field_callback_17() {
	$placeholder = __( 'Start date: %s', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-17' );
	?>
	<input type='text' size='40' name='vsel-setting-17' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php printf( esc_attr__( 'Use %1$s to display the %2$s variable.', 'very-simple-event-list' ), '%s', __( 'Start date', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

function vsel_field_callback_18() {
	$placeholder = __( 'End date: %s', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-18' );
	?>
	<input type='text' size='40' name='vsel-setting-18' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php printf( esc_attr__( 'Use %1$s to display the %2$s variable.', 'very-simple-event-list' ), '%s', __( 'End date', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

function vsel_field_callback_19() {
	$placeholder = __( 'Time: %s', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-19' );
	?>
	<input type='text' size='40' name='vsel-setting-19' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php printf( esc_attr__( 'Use %1$s to display the %2$s variable.', 'very-simple-event-list' ), '%s', __( 'Time', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

function vsel_field_callback_89() {
	$placeholder = __( 'All-day event', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-89' );
	?>
	<input type='text' size='40' name='vsel-setting-89' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<?php
}

function vsel_field_callback_20() {
	$placeholder = __( 'Location: %s', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-20' );
	?>
	<input type='text' size='40' name='vsel-setting-20' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php printf( esc_attr__( 'Use %1$s to display the %2$s variable.', 'very-simple-event-list' ), '%s', __( 'Location', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

// widget field callbacks
function vsel_field_callback_14() {
	$value = get_option( 'vsel-setting-14' );
	?>
	<input type='hidden' name='vsel-setting-14' value='no'>
	<label><input type='checkbox' name='vsel-setting-14' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Link to the event page.', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_63() {
	$value = get_option( 'vsel-setting-63' );
	?>
	<input type='hidden' name='vsel-setting-63' value='no'>
	<label><input type='checkbox' name='vsel-setting-63' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Show a date icon instead of a label.', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_67() {
	$value = get_option( 'vsel-setting-67' );
	?>
	<input type='hidden' name='vsel-setting-67' value='no'>
	<label><input type='checkbox' name='vsel-setting-67' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Combine date icon and title.', 'very-simple-event-list' ); ?></label>
	<p><strong><i><?php esc_attr_e( 'Date icon must be active.', 'very-simple-event-list' ); ?></i></strong></p>
	<p><strong><i><?php esc_attr_e( 'Title and date must not be hidden.', 'very-simple-event-list' ); ?></i></strong></p>
	<?php
}

function vsel_field_callback_21() {
	$value = get_option( 'vsel-setting-21' );
	?>
	<input type='hidden' name='vsel-setting-21' value='no'>
	<label><input type='checkbox' name='vsel-setting-21' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Combine start date and end date in one label.', 'very-simple-event-list' ); ?></label>
	<p><i><?php esc_attr_e( 'This does not affect the date icons.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_37() {
	$value = get_option( 'vsel-setting-37' );
 	?>
	<select id='vsel-setting-37' name='vsel-setting-37'>
		<option value='right'<?php echo (esc_attr($value) == 'right')?' selected':''; ?>><?php esc_attr_e( 'Align right', 'very-simple-event-list' ); ?></option>
		<option value='left'<?php echo (esc_attr($value) == 'left')?' selected':''; ?>><?php esc_attr_e( 'Align left', 'very-simple-event-list' ); ?></option>
	</select>
	<?php
	printf( esc_attr__( 'Default value is %s.', 'very-simple-event-list' ), __( 'Align right', 'very-simple-event-list' ) );
}

function vsel_field_callback_32() {
	$value = get_option( 'vsel-setting-32' );
 	?>
	<select id='vsel-setting-32' name='vsel-setting-32'>
		<option value='post-thumbnail'<?php echo (esc_attr($value) == 'post-thumbnail')?' selected':''; ?>><?php esc_attr_e( 'Post thumbnail', 'very-simple-event-list' ); ?></option>
		<option value='full'<?php echo (esc_attr($value) == 'full')?' selected':''; ?>><?php esc_attr_e( 'Full', 'very-simple-event-list' ); ?></option>
		<option value='large'<?php echo (esc_attr($value) == 'large')?' selected':''; ?>><?php esc_attr_e( 'Large', 'very-simple-event-list' ); ?></option>
		<option value='medium'<?php echo (esc_attr($value) == 'medium')?' selected':''; ?>><?php esc_attr_e( 'Medium', 'very-simple-event-list' ); ?></option>
		<option value='small'<?php echo (esc_attr($value) == 'small')?' selected':''; ?>><?php esc_attr_e( 'Small', 'very-simple-event-list' ); ?></option>
	</select>
	<?php printf( esc_attr__( 'Default value is %s.', 'very-simple-event-list' ), __( 'Post thumbnail', 'very-simple-event-list' ) ); ?>
	<p><i><?php esc_attr_e( 'This size is being used as source for the featured image.', 'very-simple-event-list' ); ?></i></p>
	<p><i><?php esc_attr_e( 'The size of the Post thumbnail may vary by theme.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_54() {
	$value = get_option( 'vsel-setting-54' );
	$placeholder = '40';
	?>
	<label><input type='number' size='10' min='20' max='100' maxlength='3' name='vsel-setting-54' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' /> <?php printf( esc_attr__( 'Default value is %s.', 'very-simple-event-list' ), '40' ); ?></label>
	<p><i><?php esc_attr_e( 'This is the maximum width of the featured image.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_31() {
	$value = get_option( 'vsel-setting-31' );
	?>
	<input type='hidden' name='vsel-setting-31' value='no'>
	<label><input type='checkbox' name='vsel-setting-31' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Link to the event page.', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_1() {
	$value = get_option( 'vsel-setting-1' );
	?>
	<input type='hidden' name='vsel-setting-1' value='no'>
	<label><input type='checkbox' name='vsel-setting-1' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Show a summary instead of all event info.', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_45() {
	$value = get_option( 'vsel-setting-45' );
	?>
	<input type='hidden' name='vsel-setting-45' value='no'>
	<label><input type='checkbox' name='vsel-setting-45' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Link to the category page.', 'very-simple-event-list' ); ?></label>
	<p><i><?php printf( esc_attr__( 'The %s page is not generated by VSEL. Events are not ordered by event date.', 'very-simple-event-list' ), __( 'Event category', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

function vsel_field_callback_65() {
	$value = get_option( 'vsel-setting-65' );
	?>
	<input type='hidden' name='vsel-setting-2' value='no'>
	<label><input type='checkbox' name='vsel-setting-65' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_2() {
	$value = get_option( 'vsel-setting-2' );
	?>
	<input type='hidden' name='vsel-setting-2' value='no'>
	<label><input type='checkbox' name='vsel-setting-2' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_3() {
	$value = get_option( 'vsel-setting-3' );
	?>
	<input type='hidden' name='vsel-setting-3' value='no'>
	<label><input type='checkbox' name='vsel-setting-3' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_4() {
	$value = get_option( 'vsel-setting-4' );
	?>
	<input type='hidden' name='vsel-setting-4' value='no'>
	<label><input type='checkbox' name='vsel-setting-4' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_34() {
	$value = get_option( 'vsel-setting-34' );
	?>
	<input type='hidden' name='vsel-setting-34' value='no'>
	<label><input type='checkbox' name='vsel-setting-34' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_6() {
	$value = get_option( 'vsel-setting-6' );
	?>
	<input type='hidden' name='vsel-setting-6' value='no'>
	<label><input type='checkbox' name='vsel-setting-6' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_52() {
	$value = get_option( 'vsel-setting-52' );
	?>
	<input type='hidden' name='vsel-setting-52' value='no'>
	<label><input type='checkbox' name='vsel-setting-52' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_5() {
	$value = get_option( 'vsel-setting-5' );
	?>
	<input type='hidden' name='vsel-setting-5' value='no'>
	<label><input type='checkbox' name='vsel-setting-5' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_7() {
	$value = get_option( 'vsel-setting-7' );
	?>
	<input type='hidden' name='vsel-setting-7' value='no'>
	<label><input type='checkbox' name='vsel-setting-7' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_22() {
	$placeholder = __( 'Date: %s', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-22' );
	?>
	<input type='text' size='40' name='vsel-setting-22' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php printf( esc_attr__( 'Use %1$s to display the %2$s variable.', 'very-simple-event-list' ), '%s', __( 'Date', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

function vsel_field_callback_23() {
	$placeholder = __( 'Start date: %s', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-23' );
	?>
	<input type='text' size='40' name='vsel-setting-23' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php printf( esc_attr__( 'Use %1$s to display the %2$s variable.', 'very-simple-event-list' ), '%s', __( 'Start date', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

function vsel_field_callback_24() {
	$placeholder = __( 'End date: %s', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-24' );
	?>
	<input type='text' size='40' name='vsel-setting-24' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php printf( esc_attr__( 'Use %1$s to display the %2$s variable.', 'very-simple-event-list' ), '%s', __( 'End date', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

function vsel_field_callback_25() {
	$placeholder = __( 'Time: %s', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-25' );
	?>
	<input type='text' size='40' name='vsel-setting-25' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php printf( esc_attr__( 'Use %1$s to display the %2$s variable.', 'very-simple-event-list' ), '%s', __( 'Time', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

function vsel_field_callback_90() {
	$placeholder = __( 'All-day event', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-90' );
	?>
	<input type='text' size='40' name='vsel-setting-90' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<?php
}

function vsel_field_callback_26() {
	$placeholder = __( 'Location: %s', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-26' );
	?>
	<input type='text' size='40' name='vsel-setting-26' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php printf( esc_attr__( 'Use %1$s to display the %2$s variable.', 'very-simple-event-list' ), '%s', __( 'Location', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

// single event field callbacks
function vsel_field_callback_71() {
	$value = get_option( 'vsel-setting-71' );
	$placeholder = '36';
	?>
	<label><input type='number' size='10' min='20' max='60' maxlength='3' name='vsel-setting-71' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' /> <?php printf( esc_attr__( 'Default value is %s.', 'very-simple-event-list' ), '36' ); ?></label>
	<p><i><?php esc_attr_e( 'This is the width of the Event details section.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_72() {
	$value = get_option( 'vsel-setting-72' );
 	?>
	<select id='vsel-setting-72' name='vsel-setting-72'>
		<option value='left'<?php echo (esc_attr($value) == 'left')?' selected':''; ?>><?php esc_attr_e( 'Align left', 'very-simple-event-list' ); ?></option>
		<option value='right'<?php echo (esc_attr($value) == 'right')?' selected':''; ?>><?php esc_attr_e( 'Align right', 'very-simple-event-list' ); ?></option>
	</select>
	<?php
	printf( esc_attr__( 'Default value is %s.', 'very-simple-event-list' ), __( 'Align left', 'very-simple-event-list' ) );
}

function vsel_field_callback_74() {
	$value = get_option( 'vsel-setting-74' );
	?>
	<input type='hidden' name='vsel-setting-74' value='no'>
	<label><input type='checkbox' name='vsel-setting-74' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Show a date icon instead of a label.', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_75() {
	$value = get_option( 'vsel-setting-75' );
	?>
	<input type='hidden' name='vsel-setting-75' value='no'>
	<label><input type='checkbox' name='vsel-setting-75' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Combine start date and end date in one label.', 'very-simple-event-list' ); ?></label>
	<p><i><?php esc_attr_e( 'This does not affect the date icons.', 'very-simple-event-list' ); ?></i></p>
	<?php
}

function vsel_field_callback_73() {
	$value = get_option( 'vsel-setting-73' );
	?>
	<input type='hidden' name='vsel-setting-73' value='no'>
	<label><input type='checkbox' name='vsel-setting-73' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Link to the category page.', 'very-simple-event-list' ); ?></label>
	<p><i><?php printf( esc_attr__( 'The %s page is not generated by VSEL. Events are not ordered by event date.', 'very-simple-event-list' ), __( 'Event category', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

function vsel_field_callback_86() {
	$value = get_option( 'vsel-setting-86' );
	?>
	<input type='hidden' name='vsel-setting-86' value='no'>
	<label><input type='checkbox' name='vsel-setting-86' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_76() {
	$value = get_option( 'vsel-setting-76' );
	?>
	<input type='hidden' name='vsel-setting-76' value='no'>
	<label><input type='checkbox' name='vsel-setting-76' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_77() {
	$value = get_option( 'vsel-setting-77' );
	?>
	<input type='hidden' name='vsel-setting-77' value='no'>
	<label><input type='checkbox' name='vsel-setting-77' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_78() {
	$value = get_option( 'vsel-setting-78' );
	?>
	<input type='hidden' name='vsel-setting-78' value='no'>
	<label><input type='checkbox' name='vsel-setting-78' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_79() {
	$value = get_option( 'vsel-setting-79' );
	?>
	<input type='hidden' name='vsel-setting-79' value='no'>
	<label><input type='checkbox' name='vsel-setting-79' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_80() {
	$value = get_option( 'vsel-setting-80' );
	?>
	<input type='hidden' name='vsel-setting-80' value='no'>
	<label><input type='checkbox' name='vsel-setting-80' <?php checked( esc_attr($value), 'yes' ); ?> value='yes'> <?php esc_attr_e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_81() {
	$placeholder = __( 'Date: %s', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-81' );
	?>
	<input type='text' size='40' name='vsel-setting-81' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php printf( esc_attr__( 'Use %1$s to display the %2$s variable.', 'very-simple-event-list' ), '%s', __( 'Date', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

function vsel_field_callback_82() {
	$placeholder = __( 'Start date: %s', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-82' );
	?>
	<input type='text' size='40' name='vsel-setting-82' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php printf( esc_attr__( 'Use %1$s to display the %2$s variable.', 'very-simple-event-list' ), '%s', __( 'Start date', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

function vsel_field_callback_83() {
	$placeholder = __( 'End date: %s', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-83' );
	?>
	<input type='text' size='40' name='vsel-setting-83' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php printf( esc_attr__( 'Use %1$s to display the %2$s variable.', 'very-simple-event-list' ), '%s', __( 'End date', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

function vsel_field_callback_84() {
	$placeholder = __( 'Time: %s', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-84' );
	?>
	<input type='text' size='40' name='vsel-setting-84' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php printf( esc_attr__( 'Use %1$s to display the %2$s variable.', 'very-simple-event-list' ), '%s', __( 'Time', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

function vsel_field_callback_91() {
	$placeholder = __( 'All-day event', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-91' );
	?>
	<input type='text' size='40' name='vsel-setting-91' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<?php
}

function vsel_field_callback_85() {
	$placeholder = __( 'Location: %s', 'very-simple-event-list' );
	$value = get_option( 'vsel-setting-85' );
	?>
	<input type='text' size='40' name='vsel-setting-85' placeholder='<?php echo esc_attr($placeholder); ?>' value='<?php echo esc_attr($value); ?>' />
	<p><i><?php printf( esc_attr__( 'Use %1$s to display the %2$s variable.', 'very-simple-event-list' ), '%s', __( 'Location', 'very-simple-event-list' ) ); ?></i></p>
	<?php
}

// display admin options page
function vsel_options_page() {
?>
<div class="wrap">
	<h1><?php esc_attr_e( 'Very Simple Event List', 'very-simple-event-list' ); ?></h1>
	<?php
	$active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'general_options';
	?>
	<h2 class="nav-tab-wrapper">
		<a href="?page=vsel&tab=general_options" class="nav-tab <?php echo esc_attr($active_tab) == 'general_options' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( 'General', 'very-simple-event-list' ); ?></a>
		<a href="?page=vsel&tab=page_options" class="nav-tab <?php echo esc_attr($active_tab) == 'page_options' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( 'Page', 'very-simple-event-list' ); ?></a>
		<a href="?page=vsel&tab=widget_options" class="nav-tab <?php echo esc_attr($active_tab) == 'widget_options' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( 'Widget', 'very-simple-event-list' ); ?></a>
		<a href="?page=vsel&tab=single_options" class="nav-tab <?php echo esc_attr($active_tab) == 'single_options' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( 'Single event', 'very-simple-event-list' ); ?></a>
	</h2>
	<form action="options.php" method="POST">
		<?php if( $active_tab == 'general_options' ) {
			settings_fields( 'vsel-general-options' );
			do_settings_sections( 'vsel-general' );
		} elseif( $active_tab == 'page_options' ) {
			settings_fields( 'vsel-page-options' );
			do_settings_sections( 'vsel-page' );
		} elseif( $active_tab == 'widget_options' ) {
			settings_fields( 'vsel-widget-options' );
			do_settings_sections( 'vsel-widget' );
		} else {
			settings_fields( 'vsel-single-options' );
			do_settings_sections( 'vsel-single' );
		}
		submit_button(); ?>
	</form>
	<p><?php esc_attr_e( 'More customizations can be made by using (shortcode) attributes.', 'very-simple-event-list' ); ?></p>
	<?php
	$link_label = __( 'click here', 'very-simple-event-list' );
	$link_wp = '<a href="https://wordpress.org/plugins/very-simple-event-list" target="_blank">'.$link_label.'</a>';
	?>
	<p><?php printf( esc_attr__( 'For info, available attributes and support %s.', 'very-simple-event-list' ), $link_wp ); ?></p>
</div>
<?php
}
