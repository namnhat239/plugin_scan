<?php

namespace OXI_TABS_PLUGINS\Modules;

/**
 * Description of Visual_Composer
 *
 * @author biplo
 */
class Visual_Composer {

    public function __construct() {
        add_action('vc_before_init', [$this, 'VC_extension']);
        add_shortcode('ctu_ultimate_oxi_VC', [$this, 'VC_Shortcode']);
    }

    public function VC_Shortcode($atts) {
        extract(shortcode_atts(array(
            'id' => ''
                        ), $atts));
        $styleid = $atts['id'];
        ob_start();
        echo \OXI_TABS_PLUGINS\Classes\Bootstrap::instance()->shortcode_render($styleid, 'user');
        return ob_get_clean();
    }

    public function VC_extension() {
        vc_map(array(
            "name" => __("Content Tabs"),
            "base" => "ctu_ultimate_oxi_VC",
            "category" => __("Content"),
            "params" => array(
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "heading" => __("ID"),
                    "param_name" => "id",
                    "description" => __("Input your Tabs ID in input box")
                ),
            )
        ));
    }

}
