<?php
/*
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_action('jetpack_activate_module',  'ualpJetpackActivateModule', 10, 2);

/**
 * Get all jetpack modules
 */
if (!function_exists('ualp_get_jetpack_modules')){

    function ualp_get_jetpack_modules() {

        // Check that Jetpack has the needed methods.
        if (! method_exists('Jetpack', 'get_available_modules') || ! method_exists('Jetpack', 'get_module')) {
            return false;
        }
        $available_modules = Jetpack::get_available_modules();
        $available_modules_with_info = array();

        foreach ($available_modules as $module_slug) {
            $module = Jetpack::get_module($module_slug);
            if (!$module) {
                continue;
            }
            $available_modules_with_info[$module_slug] = $module;
        }
        return $available_modules_with_info;
    }

}

/**
 * Get single jetpack modules
 */
if (!function_exists('ualp_get_jetpack_module')){

    function ualp_get_jetpack_module($slug = null) {
        if (empty($slug)) {
            return false;
        }
        $modules = ualp_get_jetpack_modules();
        return isset($modules[$slug]) ? $modules[$slug] : false;
    }

}

/**
 * Store Jetpack Activate Module
 */
if (!function_exists('ualpJetpackActivateModule')){

    function ualpJetpackActivateModule($module_slug = null, $success = null) {
        if (true !== $success) {
            return;
        }
        $module = ualp_get_jetpack_module($module_slug);
        if ($module) {
            $obj_type = 'Activate Jetpack Module';
            $action = $module_slug;
            $post_id = '';
            $post_title = 'Activate Jetpack Module: ' .$module['name'];
			ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
        }
    }

}

add_action('jetpack_deactivate_module', 'ualpJetpackDeactivateModule', 10, 2);

/**
 * Store Jetpack Deactivate Module
 */
if (!function_exists('ualpJetpackDeactivateModule')){

    function ualpJetpackDeactivateModule($module_slug = null, $success = null) {
        if (true !== $success) {
            return;
        }
        $module = ualp_get_jetpack_module($module_slug);
        if ($module) {
            $obj_type = 'Deactivate Jetpack Module';
            $action = $module_slug;
            $post_id = '';
            $post_title = '';
            $hook = 'jetpack_deactivate_module';
            $post_title = 'Deactivate Jetpack Module: ' .$module['name'];
            ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
        }
    }

}