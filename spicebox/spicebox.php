<?php
/*
Plugin Name: SpiceBox
Plugin URI:
Description: Enhances Spicethemes with extra functionality.
Version: 0.2.2
Author: Spicethemes
Author URI: https://github.com
Text Domain: spicebox
*/
define( 'SPICEB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SPICEB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

function spiceb_activate() {
	$theme = wp_get_theme(); // gets the current theme
	if ( 'SpicePress' == $theme->name ){
		require_once('inc/spicepress/features/feature-slider-section.php');
		require_once('inc/spicepress/features/feature-service-section.php');
		require_once('inc/spicepress/features/feature-portfolio-section.php');
		require_once('inc/spicepress/features/feature-testimonial-section.php');
		require_once('inc/spicepress/sections/spicepress-slider-section.php');
		require_once('inc/spicepress/sections/spicepress-features-section.php');
		require_once('inc/spicepress/sections/spicepress-portfolio-section.php');
		require_once('inc/spicepress/sections/spicepress-testimonail-section.php');
		require_once('inc/spicepress/customizer.php');
		
	}

}
add_action( 'init', 'spiceb_activate' );


$theme = wp_get_theme();
if ( 'SpicePress' == $theme->name ){
		
	
register_activation_hook( __FILE__, 'spiceb_install_function');
function spiceb_install_function()
{
    $item_details_page = get_option('item_details_page'); 
    if(!$item_details_page){
        //post status and options
        $post = array(
              'comment_status' => 'closed',
              'ping_status' =>  'closed' ,
              'post_author' => 1,
              'post_date' => date('Y-m-d H:i:s'),
              'post_name' => 'Home',
              'post_status' => 'publish' ,
              'post_title' => 'Home',
              'post_type' => 'page',
        );  
        //insert page and save the id
        $newvalue = wp_insert_post( $post, false );
        if ( $newvalue && ! is_wp_error( $newvalue ) ){
            update_post_meta( $newvalue, '_wp_page_template', 'template-business.php' );
            
            // Use a static front page
            $page = get_page_by_title('Home');
            update_option( 'show_on_front', 'page' );
            update_option( 'page_on_front', $page->ID );
            
        }
        //save the id in the database
        update_option( 'item_details_page', $newvalue );
    }
}

}
?>