<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

// ----------------------------------------------
// create the tag manager main code database
// ----------------------------------------------    

global $wpdb;
$charset_collate = $wpdb->get_charset_collate();
require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); // Require to use dbDelta
include('settings.php'); // Load the files to get the databse info

if( $wpdb->get_var("SHOW TABLES LIKE '{$yydev_tags_table_name}' ") != $yydev_tags_table_name ) {
    // The table we want to create doesn't exists
   
    $sql = "CREATE TABLE " . $yydev_tags_table_name . "( 
    id INTEGER(11) UNSIGNED AUTO_INCREMENT,
    page_id INTEGER (11),
    tag_type TEXT,
    tag_code TEXT,
    PRIMARY KEY (id) 
    ) $charset_collate;";
    
    dbDelta($sql); 
   
}  // if( $wpdb->get_var("SHOW TABLES LIKE '{$yydev_tags_table_name}' ") != $yydev_tags_table_name ) {

// ----------------------------------------------
// checking if the data existing on the db and 
// if not we will create it with initial settings
// ----------------------------------------------      

if( !get_option($wp_options_name) ) {

    // ----------------------------------------------
    // getting all the values and clear data
    // ----------------------------------------------    

    $exclude_users = 'none';
    $exclude_option = 'none';
    $exclude_ids = '';

    $google_analytics_id = '';
    $yandex_metrika_id = '';

    $wp_body_open = 0;
    $add_plugin_to_settings = 0;

    // ----------------------------------------------
    // fall back saving data for older versions than 2.1 for this plugin
    // ----------------------------------------------  

    // loading a string into wp_option to load the plugin from the manu page menu
    if( get_option('yydev_tag_mangage_wp_body_open') ) {
        $wp_body_open = intval( get_option('yydev_tag_mangage_wp_body_open') );
    } // if( get_option('yydev_tag_mangage_wp_body_open') ) {

    // loading a string into wp_option to load the plugin from the manu page menu
    if( get_option('yydev_tagmanager_main_menu') ) {
        $add_plugin_to_settings = intval( get_option('yydev_tagmanager_main_menu') );
    } // if( get_option('yydev_tagmanager_main_menu') ) {

    // ----------------------------------------------
    // insert the data into an array
    // ----------------------------------------------  

    $plugin_data_array = array(

        'exclude_users' => $exclude_users,
        'exclude_option' => $exclude_option,
        'exclude_ids' => $exclude_ids,

        'google_analytics_id' => $google_analytics_id,
        'yandex_metrika_id' => $yandex_metrika_id,

        'wp_body_open' => $wp_body_open,
        'add_plugin_to_settings' => $add_plugin_to_settings,

    ); // $creating_data_array = array(

    // ----------------------------------------------
    // creating a value with all the array data
    // ----------------------------------------------  
    
    $array_key_name = "";
    $array_item_value = "";
    
    foreach($plugin_data_array as $key=>$item) {
        $array_key_name .= "####" . $key;
    	$array_item_value .= "####" . $item;
    } // foreach($medical_form_array as $key=>$item) {

    // ----------------------------------------------
    // inserting all the data to datbase
    // ----------------------------------------------  

    $plugin_data = $array_key_name . "***" . $array_item_value;
    $plugin_data = sanitize_text_field($plugin_data);

    // update optuon on the database into wp_options
    update_option($wp_options_name, $plugin_data);    

} // if( !get_option($wp_options_name) ) {