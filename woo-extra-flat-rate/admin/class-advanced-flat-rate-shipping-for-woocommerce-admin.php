<?php

// phpcs:ignore
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com
 * @since      1.0.0
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro
 * @subpackage Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro/admin
 * @author     Multidots <inquiry@multidots.in>
 */
class Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Admin
{
    const  afrsm_shipping_post_type = 'wc_afrsm' ;
    const  afrsm_zone_post_type = 'wc_afrsm_zone' ;
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version     The version of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->afrsm_pro_load_dependencies();
    }
    
    /**
     * List of location specific conditions.
     *
     * @return array $loca_arr
     *
     * @since  3.8
     *
     * @author jb
     */
    public function afrsm_location_specific_action()
    {
        $list_cnd_comm = array(
            'country'     => esc_html__( 'Country', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'state'       => esc_html__( 'State', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'city_in_pro' => esc_html__( 'City ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'postcode'    => esc_html__( 'Postcode', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'zone'        => esc_html__( 'Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
        );
        return apply_filters( 'afrsm_location_specific_ft', $list_cnd_comm );
    }
    
    /**
     * List of Product specific conditions.
     *
     * @return array $loca_arr
     *
     * @since  3.8
     *
     * @author jb
     */
    public function afrsm_product_specific_action()
    {
        $list_cnd_comm = array(
            'product'  => esc_html__( 'Cart contains product', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'category' => esc_html__( 'Cart contains category\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'tag'      => esc_html__( 'Cart contains tag\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
        );
        $list_cnd = array();
        $list_cnd = array(
            'variableproduct_in_pro' => esc_html__( 'Cart contains variable product ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'sku_in_pro'             => esc_html__( 'Cart contains SKU\'s product ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'product_qty_in_pro'     => esc_html__( 'Cart contains product\'s quantity ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
        );
        $loca_arr = array_merge( $list_cnd_comm, $list_cnd );
        return apply_filters( 'afrsm_product_specific_ft', $loca_arr );
    }
    
    /**
     * List of Attribute specific conditions.
     *
     * @return array $loca_arr
     *
     * @since  3.8
     *
     * @author jb
     */
    public function afrsm_attribute_specific_action()
    {
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        $loca_arr = array();
        foreach ( $attribute_taxonomies as $attribute ) {
            $att_label = $attribute->attribute_label;
            $att_name = wc_attribute_taxonomy_name( $attribute->attribute_name );
            $loca_arr[$att_name] = $att_label;
        }
        return apply_filters( 'afrsm_attribute_specific_ft', $loca_arr );
    }
    
    /**
     * List of User specific conditions.
     *
     * @return array $loca_arr
     *
     * @since  3.8
     *
     * @author jb
     */
    public function afrsm_user_specific_action()
    {
        $list_cnd_comm = array(
            'user' => esc_html__( 'User', 'advanced-flat-rate-shipping-for-woocommerce' ),
        );
        $list_cnd = array();
        $list_cnd = array(
            'user_role_in_pro' => esc_html__( 'User Role ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
        );
        $loca_arr = array_merge( $list_cnd_comm, $list_cnd );
        return apply_filters( 'afrsm_user_specific_ft', $loca_arr );
    }
    
    /**
     * List of Cart specific conditions.
     *
     * @return array $loca_arr
     *
     * @since  3.8
     *
     * @author jb
     */
    public function afrsm_cart_specific_action()
    {
        $list_cnd_comm = array(
            'cart_total' => esc_html__( 'Cart Subtotal (Before Discount)', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'quantity'   => esc_html__( 'Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ),
        );
        $list_cnd = array();
        $list_cnd = array(
            'cart_totalafter_in_pro'      => esc_html__( 'Cart Subtotal (After Discount)  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'cart_productspecific_in_pro' => esc_html__( 'Cart Subtotal (Product Specific)  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'weight_in_pro'               => esc_html__( 'Weight  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'coupon_in_pro'               => esc_html__( 'Coupon  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'shipping_class_in_pro'       => esc_html__( 'Shipping Class  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
        );
        $loca_arr = array_merge( $list_cnd_comm, $list_cnd );
        return apply_filters( 'afrsm_cart_specific_ft', $loca_arr );
    }
    
    /**
     * List of Checkout specific conditions.
     *
     * @return array $loca_arr
     *
     * @since  3.8
     *
     * @author jb
     */
    public function afrsm_checkout_specific_action()
    {
        $loca_arr = array();
        $loca_arr = array(
            'payment_method_in_pro' => esc_html__( 'Payment Method  ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
        );
        return apply_filters( 'afrsm_checkout_specific_ft', $loca_arr );
    }
    
    /**
     * List of conditions
     *
     * @return array $final_data
     *
     * @since  3.8
     *
     * @author jb
     */
    public function afrsm_conditions_list_action()
    {
        $final_data = array(
            'Location Specific'  => $this->afrsm_location_specific_action(),
            'Product Specific'   => $this->afrsm_product_specific_action(),
            'Attribute Specific' => $this->afrsm_attribute_specific_action(),
            'User Specific'      => $this->afrsm_user_specific_action(),
            'Cart Specific'      => $this->afrsm_cart_specific_action(),
            'Checkout Specific'  => $this->afrsm_checkout_specific_action(),
        );
        return apply_filters( 'afrsm_conditions_list_ft', $final_data );
    }
    
    /**
     * List of Operator
     *
     * @param string $fees_conditions Check which condition is applying.
     *
     * @return array $final_data
     *
     * @since  3.8
     *
     * @author jb
     */
    public function afrsm_operator_list_action( $fees_conditions )
    {
        $cart_op_arr = array();
        $prd_op_arr = array(
            'is_equal_to' => esc_html__( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'not_in'      => esc_html__( 'Not Equal to ( != )', 'advanced-flat-rate-shipping-for-woocommerce' ),
        );
        if ( 'product' === $fees_conditions || 'category' === $fees_conditions || 'tag' === $fees_conditions || 'variableproduct' === $fees_conditions || 'sku' === $fees_conditions ) {
            $cart_op_arr = array(
                'only_equal_to' => esc_html__( 'Only Equal to ( == )', 'advanced-flat-rate-shipping-for-woocommerce' ),
            );
        }
        if ( 'product_qty' === $fees_conditions || 'cart_total' === $fees_conditions || 'cart_totalafter' === $fees_conditions || 'cart_productspecific' === $fees_conditions || 'quantity' === $fees_conditions || 'weight' === $fees_conditions ) {
            $cart_op_arr = array(
                'less_equal_to'    => esc_html__( 'Less or Equal to ( <= )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'less_then'        => esc_html__( 'Less than ( < )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'greater_equal_to' => esc_html__( 'Greater or Equal to ( >= )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'greater_then'     => esc_html__( 'Greater than ( > )', 'advanced-flat-rate-shipping-for-woocommerce' ),
            );
        }
        $final_data = array_merge( $prd_op_arr, $cart_op_arr );
        return apply_filters( 'afrsm_operator_list_crt_ft', $final_data );
    }
    
    /**
     * List of advanced tab section.
     *
     * @return array $tab_array
     *
     * @since  3.8
     *
     * @author jb
     */
    public function afrsm_advanced_tab_list_action()
    {
        $tab_array = array(
            'tab-8' => esc_html__( 'Cost on Total Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'tab-9' => esc_html__( 'Cost on Total Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
        );
        return apply_filters( 'afrsm_advanced_tab_list_ft', $tab_array );
    }
    
    /**
     * List of apply per qty section.
     *
     * @return array $afrsm_apq_array
     *
     * @since  3.8
     *
     * @author jb
     */
    public function afrsm_apq_type_action()
    {
        $afrsm_apq_array = array(
            'qty_cart_based'    => esc_html__( 'Cart Based', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'qty_product_based' => esc_html__( 'Product Based', 'advanced-flat-rate-shipping-for-woocommerce' ),
        );
        return apply_filters( 'afrsm_apq_action_ft', $afrsm_apq_array );
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @param string $hook display current page name
     *
     * @since    1.0.0
     *
     */
    public function afrsm_pro_enqueue_styles( $hook )
    {
        
        if ( false !== strpos( $hook, 'dotstore-plugins_page_afrsm' ) ) {
            wp_enqueue_style(
                $this->plugin_name . 'select2-min',
                plugin_dir_url( __FILE__ ) . 'css/select2.min.css',
                array(),
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-jquery-ui-css',
                plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-timepicker-min-css',
                plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.min.css',
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . 'font-awesome',
                plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . 'main-style',
                plugin_dir_url( __FILE__ ) . 'css/style.css',
                array(),
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . 'media-css',
                plugin_dir_url( __FILE__ ) . 'css/media.css',
                array(),
                'all'
            );
        }
    
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @param string $hook display current page name
     *
     * @since    1.0.0
     *
     */
    public function afrsm_pro_enqueue_scripts( $hook )
    {
        global  $wp ;
        wp_enqueue_style( 'wp-jquery-ui-dialog' );
        wp_enqueue_script( 'jquery-ui-accordion' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
        
        if ( false !== strpos( $hook, 'dotstore-plugins_page_afrsm' ) ) {
            wp_enqueue_script(
                $this->plugin_name . '-select2-full-min',
                plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js',
                array( 'jquery', 'jquery-ui-datepicker' ),
                $this->version,
                false
            );
            wp_enqueue_script(
                $this->plugin_name . '-tablesorter-js',
                plugin_dir_url( __FILE__ ) . 'js/jquery.tablesorter.js',
                array( 'jquery' ),
                $this->version,
                false
            );
            wp_enqueue_script(
                $this->plugin_name . '-timepicker-js',
                plugin_dir_url( __FILE__ ) . 'js/jquery.timepicker.js',
                array( 'jquery' ),
                $this->version,
                false
            );
            wp_enqueue_script(
                $this->plugin_name . '-help-scout-beacon-js',
                plugin_dir_url( __FILE__ ) . 'js/help-scout-beacon.js',
                array( 'jquery' ),
                $this->version,
                false
            );
            $current_url = home_url( add_query_arg( $wp->query_vars, $wp->request ) );
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/advanced-flat-rate-shipping-for-woocommerce-admin.js',
                array(
                'jquery',
                'jquery-ui-dialog',
                'jquery-ui-accordion',
                'jquery-ui-sortable',
                'select2'
            ),
                $this->version,
                false
            );
            wp_localize_script( $this->plugin_name, 'coditional_vars', array(
                'first_path'                     => admin_url( 'admin.php?page=afrsm-pro-list' ),
                'ajaxurl'                        => admin_url( 'admin-ajax.php' ),
                'ajax_icon'                      => esc_url( plugin_dir_url( __FILE__ ) . '/images/ajax-loader.gif' ),
                'plugin_url'                     => plugin_dir_url( __FILE__ ),
                'dsm_ajax_nonce'                 => wp_create_nonce( 'dsm_nonce' ),
                'country'                        => esc_html__( 'Country', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'state'                          => esc_html__( 'State', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'city'                           => esc_html__( 'City ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'postcode'                       => esc_html__( 'Postcode', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'zone'                           => esc_html__( 'Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'cart_contains_product'          => esc_html__( 'Cart contains product', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'cart_contains_variable_product' => esc_html__( 'Cart contains variable product ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'cart_contains_category_product' => esc_html__( 'Cart contains category\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'cart_contains_tag_product'      => esc_html__( 'Cart contains tag\'s product', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'cart_contains_sku_product'      => esc_html__( 'Cart contains SKU\'s product ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'cart_contains_product_qty'      => esc_html__( 'Cart contains product\'s quantity ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'user'                           => esc_html__( 'User', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'user_role'                      => esc_html__( 'User Role ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'cart_subtotal_before_discount'  => esc_html__( 'Cart Subtotal (Before Discount)', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'cart_subtotal_after_discount'   => esc_html__( 'Cart Subtotal (After Discount) ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'cart_subtotal_productspecific'  => esc_html__( 'Cart Subtotal (Product Specific) ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'quantity'                       => esc_html__( 'Quantity', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'weight'                         => esc_html__( 'Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'coupon'                         => esc_html__( 'Coupon', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'shipping_class'                 => esc_html__( 'Shipping Class', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'weight'                         => esc_html__( 'Weight ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'coupon'                         => esc_html__( 'Coupon ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'shipping_class'                 => esc_html__( 'Shipping Class ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'payment_method'                 => esc_html__( 'Payment Method ( In Pro )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'equal_to'                       => esc_html__( 'Equal to ( = )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'not_equal_to'                   => esc_html__( 'Not Equal to ( != )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'less_or_equal_to'               => esc_html__( 'Less or Equal to ( <= )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'less_than'                      => esc_html__( 'Less then ( < )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'greater_or_equal_to'            => esc_html__( 'greater or Equal to ( >= )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'greater_than'                   => esc_html__( 'greater then ( > )', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'validation_length1'             => esc_html__( 'Please enter 3 or more characters', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'select_category'                => esc_html__( 'Select Category', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'delete'                         => esc_html__( 'Delete', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'validation_length2'             => esc_html__( 'Please enter', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'validation_length3'             => esc_html__( 'or more characters', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'location_specific'              => esc_html__( 'Location Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'product_specific'               => esc_html__( 'Product Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'user_specific'                  => esc_html__( 'User Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'cart_specific'                  => esc_html__( 'Cart Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'checkout_specific'              => esc_html__( 'Checkout Specific', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'success_msg1'                   => esc_html__( 'Shipping method order saved successfully', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'success_msg2'                   => esc_html__( 'Your settings successfully saved.', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'warning_msg1'                   => sprintf( __( '<p><b style="color: red;">Note: </b>If entered price is more than total shipping price than Message looks like: <b>Shipping Method Name: Curreny Symbole like($) -60.00 Price </b> and if shipping minus price is more than total price than it will set Total Price to Zero(0).</p>', 'advanced-flat-rate-shipping-for-woocommerce' ) ),
                'note'                           => esc_html__( 'Note: ', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'click_here'                     => esc_html__( 'Click Here', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'current_url'                    => $current_url,
                'doc_url'                        => "https://docs.thedotstore.com/collection/81-flat-rate-shipping-plugin-for-woocommerce",
                'list_page_url'                  => add_query_arg( array(
                'page' => 'afrsm-start-page',
            ), admin_url( 'admin.php' ) ),
                'product_qty_page_url'           => "https://docs.thedotstore.com/article/104-product-specific-shipping-rule/",
                'cart_weight'                    => esc_html__( 'Cart Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'min_weight'                     => esc_html__( 'Min Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'max_weight'                     => esc_html__( 'Max Weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'cart_subtotal'                  => esc_html__( 'Cart Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'min_subtotal'                   => esc_html__( 'Min Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'max_subtotal'                   => esc_html__( 'Max Subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'min_max_qty_error'              => esc_html__( 'Max qty should greater then min qty', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'min_max_weight_error'           => esc_html__( 'Max weight should greater then min weight', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'min_max_subtotal_error'         => esc_html__( 'Max subtotal should greater then min subtotal', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'success_msg1'                   => esc_html__( 'Shipping method order saved successfully', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'success_msg2'                   => esc_html__( 'Your settings successfully saved.', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'warning_msg1'                   => sprintf( __( '<p><b style="color: red;">Note: </b>If entered price is more than total shipping price than Message looks like: <b>Shipping Method Name: Curreny Symbole like($) -60.00 Price </b> and if shipping minus price is more than total price than it will set Total Price to Zero(0).</p>', 'advanced-flat-rate-shipping-for-woocommerce' ) ),
                'warning_msg2'                   => esc_html__( 'Please disable Advance Pricing Rule if you dont need because you have not created rule there.', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'warning_msg3'                   => esc_html__( 'You need to select product specific option in Shipping Method Rules for product based option', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'warning_msg4'                   => esc_html__( 'If you active Apply Per Quantity option then Advance Pricing Rule will be disable and not working.', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'warning_msg5'                   => esc_html__( 'Please fill some required field in advance pricing rule section', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'amount'                         => esc_html__( 'Amount', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'tooltip_char_limit'             => 100,
            ) );
        }
    
    }
    
    /**
     * Load zone section
     *
     * @since    1.0.0
     */
    private function afrsm_pro_load_dependencies()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/afrsm-pro-shipping-zone-page.php';
    }
    
    /*
     * Shipping method Pro Menu
     *
     * @since 3.0.0
     */
    public function afrsm_pro_dot_store_menu_shipping_method_pro()
    {
        global  $GLOBALS ;
        if ( empty($GLOBALS['admin_page_hooks']['dots_store']) ) {
            add_menu_page(
                'DotStore Plugins',
                __( 'DotStore Plugins', 'advanced-flat-rate-shipping-for-woocommerce' ),
                'null',
                'dots_store',
                array( $this, 'dot_store_menu_page' ),
                AFRSM_PRO_PLUGIN_URL . 'admin/images/menu-icon.png',
                25
            );
        }
        add_submenu_page(
            'dots_store',
            'Advanced Flat Rate Shipping For WooCommerce',
            'Advanced Flat Rate Shipping For WooCommerce',
            'manage_options',
            'afrsm-pro-list',
            array( $this, 'afrsm_pro_fee_list_page' )
        );
        add_submenu_page(
            'dots_store',
            'Premium Version',
            'Premium Version',
            'manage_options',
            'afrsm-premium',
            array( $this, 'premium_version_afrsm_page' )
        );
        // add_submenu_page( 'dots_store', 'Add Shipping Method', 'Add Shipping Method', 'manage_options', 'afrsm-pro-add-shipping', array(
        // 	$this,
        // 	'afrsm_pro_add_new_fee_page',
        // ) );
        add_submenu_page(
            'dots_store',
            'Edit Shipping Method',
            'Edit Shipping Method',
            'manage_options',
            'afrsm-pro-edit-shipping',
            array( $this, 'afrsm_pro_edit_fee_page' )
        );
        add_submenu_page(
            'dots_store',
            'Manage Shipping Zones',
            'Manage Shipping Zones',
            'manage_options',
            'afrsm-wc-shipping-zones',
            array( __CLASS__, 'afrsm_pro_shipping_zone_page' )
        );
        add_submenu_page(
            'dots_store',
            'Import Export Shipping',
            'Import Export Shipping',
            'manage_options',
            'afrsm-pro-import-export',
            array( $this, 'afrsm_pro_import_export_fee' )
        );
        // add_submenu_page( 'dots_store', 'Add-on List', 'Add-on List', 'manage_options', 'advanced-flat-rate-shipping-for-woocommerce-addons', array(
        // 	$this,
        // 	'afrsm_pro_addon_list_page',
        // ) );
        add_submenu_page(
            'dots_store',
            'Getting Started',
            'Getting Started',
            'manage_options',
            'afrsm-pro-get-started',
            array( $this, 'afrsm_pro_get_started_page' )
        );
        add_submenu_page(
            'dots_store',
            'Quick info',
            'Quick info',
            'manage_options',
            'afrsm-pro-information',
            array( $this, 'afrsm_pro_information_page' )
        );
    }
    
    /**
     * Redirect to listing page from dotStore menu page
     *
     * @since    4.1.0
     */
    public function dot_store_menu_page()
    {
        wp_redirect( admin_url( 'admin.php?page=afrsm-pro-list' ) );
        exit;
    }
    
    /**
     * Shipping List Page
     *
     * @since    1.0.0
     */
    public function afrsm_pro_fee_list_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/afrsm-pro-list-page.php';
        $afrsm_rule_lising_obj = new AFRSM_Rule_Listing_Page();
        $afrsm_rule_lising_obj->afrsm_sj_output();
    }
    
    /**
     * Add new shipping method Page
     *
     * @since    1.0.0
     */
    // public function afrsm_pro_add_new_fee_page() {
    // 	require_once( plugin_dir_path( __FILE__ ) . 'partials/afrsm-pro-add-new-page.php' );
    // }
    /**
     * Edit shipping method Page
     *
     * @since    1.0.0
     */
    // public function afrsm_pro_edit_fee_page() {
    // 	require_once( plugin_dir_path( __FILE__ ) . 'partials/afrsm-pro-add-new-page.php' );
    // }
    /**
     * Shipping zone page
     * @uses     AFRSM_Shipping_Zone class
     * @uses     AFRSM_Shipping_Zone::output()
     *
     * @since    1.0.0
     */
    public static function afrsm_pro_shipping_zone_page()
    {
        $shipping_zone_obj = new AFRSM_Shipping_Zone();
        $shipping_zone_obj->afrsm_pro_sz_output();
    }
    
    /**
     * Quick guide page
     *
     * @since    1.0.0
     */
    public function afrsm_pro_get_started_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/afrsm-pro-get-started-page.php';
    }
    
    /**
     * Plugin information page
     *
     * @since    1.0.0
     */
    public function afrsm_pro_information_page()
    {
        require_once plugin_dir_path( __FILE__ ) . 'partials/afrsm-pro-information-page.php';
    }
    
    /**
     * Premium version info page
     *
     */
    public function premium_version_afrsm_page()
    {
        require_once plugin_dir_path( __FILE__ ) . '/partials/afrsm-premium-version-page.php';
    }
    
    /**
     * Import Export Setting page
     *
     */
    public function afrsm_pro_import_export_fee()
    {
        require_once plugin_dir_path( __FILE__ ) . '/partials/afrsm-import-export-setting.php';
    }
    
    /**
     * Addon list page
     *
     */
    // public function afrsm_pro_addon_list_page() {
    // 	require_once( plugin_dir_path( __FILE__ ) . '/partials/afrsm-addon-list.php' );
    // }
    /**
     * Redirect to shipping list page
     *
     * @since    1.0.0
     */
    public function afrsm_pro_redirect_shipping_function()
    {
        $get_section = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_STRING );
        
        if ( isset( $get_section ) && !empty($get_section) && 'advanced_flat_rate_shipping' === $get_section ) {
            wp_safe_redirect( add_query_arg( array(
                'page' => 'afrsm-pro-list',
            ), admin_url( 'admin.php' ) ) );
            exit;
        }
    
    }
    
    /**
     * Redirect to quick start guide after plugin activation
     *
     * @uses     afrsm_pro_register_post_type()
     *
     * @since    1.0.0
     */
    public function afrsm_pro_welcome_shipping_method_screen_do_activation_redirect()
    {
        $this->afrsm_pro_register_post_type();
        // if no activation redirect
        if ( !get_transient( '_welcome_screen_afrsm_pro_mode_activation_redirect_data' ) ) {
            return;
        }
        // Delete the redirect transient
        delete_transient( '_welcome_screen_afrsm_pro_mode_activation_redirect_data' );
        // if activating from network, or bulk
        $activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_STRING );
        if ( is_network_admin() || isset( $activate_multi ) ) {
            return;
        }
        // Redirect to extra cost welcome  page
        wp_safe_redirect( add_query_arg( array(
            'page' => 'afrsm-pro-list',
        ), admin_url( 'admin.php' ) ) );
        exit;
    }
    
    /**
     * Register post type
     *
     * @since    1.0.0
     */
    public function afrsm_pro_register_post_type()
    {
        register_post_type( self::afrsm_shipping_post_type, array(
            'labels' => array(
            'name'          => __( 'Advance Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'singular_name' => __( 'Advance Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' ),
        ),
        ) );
    }
    
    /**
     * Remove submenu from admin screeen
     *
     * @since    1.0.0
     */
    public function afrsm_pro_remove_admin_submenus()
    {
        remove_submenu_page( 'dots_store', 'afrsm-pro-add-shipping' );
        remove_submenu_page( 'dots_store', 'afrsm-pro-edit-shipping' );
        remove_submenu_page( 'dots_store', 'afrsm-wc-shipping-zones' );
        remove_submenu_page( 'dots_store', 'afrsm-pro-import-export' );
        remove_submenu_page( 'dots_store', 'afrsm-premium' );
        remove_submenu_page( 'dots_store', 'afrsm-pro-get-started' );
        remove_submenu_page( 'dots_store', 'afrsm-pro-information' );
    }
    
    /**
     * Match condition based on shipping list
     *
     * @param int          $sm_post_id
     * @param array|object $package
     *
     * @return bool True if $final_condition_flag is 1, false otherwise. if $sm_status is off then also return false.
     * @since    1.0.0
     *
     * @uses     afrsm_pro_get_default_langugae_with_sitpress()
     * @uses     afrsm_pro_get_woo_version_number()
     * @uses     WC_Cart::get_cart()
     * @uses     afrsm_pro_match_country_rules()
     * @uses     afrsm_pro_match_state_rules()
     * @uses     afrsm_pro_match_city_rules()
     * @uses     afrsm_pro_match_postcode_rules()
     * @uses     afrsm_pro_match_zone_rules()
     * @uses     afrsm_pro_match_variable_products_rule__premium_only()
     * @uses     afrsm_pro_match_simple_products_rule()
     * @uses     afrsm_pro_match_category_rule()
     * @uses     afrsm_pro_match_tag_rule()
     * @uses     afrsm_pro_match_sku_rule__premium_only()
     * @uses     afrsm_pro_match_user_rule()
     * @uses     afrsm_pro_match_user_role_rule__premium_only()
     * @uses     afrsm_pro_match_coupon_rule__premium_only()
     * @uses     afrsm_pro_match_cart_subtotal_before_discount_rule()
     * @uses     afrsm_pro_match_cart_subtotal_after_discount_rule__premium_only()
     * @uses	 afrsm_pro_match_cart_subtotal_specific_product_shipping_rule__premium_only()
     * @uses     afrsm_pro_match_cart_total_cart_qty_rule()
     * @uses     afrsm_pro_match_cart_total_weight_rule__premium_only()
     * @uses     afrsm_pro_match_shipping_class_rule__premium_only()
     *
     */
    public function afrsm_pro_condition_match_rules( $sm_post_id, $package = array() )
    {
        if ( empty($sm_post_id) ) {
            return false;
        }
        global  $sitepress ;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        
        if ( !empty($sitepress) ) {
            $sm_post_id = apply_filters(
                'wpml_object_id',
                $sm_post_id,
                'wc_afrsm',
                true,
                $default_lang
            );
        } else {
            $sm_post_id = $sm_post_id;
        }
        
        $wc_curr_version = $this->afrsm_pro_get_woo_version_number();
        $is_passed = array();
        $final_is_passed_general_rule = array();
        $new_is_passed = array();
        $final_condition_flag = array();
        $cart_array = ( !empty($package['contents']) ? $package['contents'] : $this->afrsm_pro_get_cart() );
        $cart_main_product_ids_array = $this->afrsm_pro_get_main_prd_id( $cart_array, $sitepress, $default_lang );
        $cart_product_ids_array = $this->afrsm_pro_get_prd_var_id( $cart_array, $sitepress, $default_lang );
        $sm_status = get_post_status( $sm_post_id );
        $get_condition_array = get_post_meta( $sm_post_id, 'sm_metabox', true );
        $general_rule_match = 'all';
        $sm_select_log_in_user = get_post_meta( $sm_post_id, 'sm_select_log_in_user', true );
        $general_rule_match = 'all';
        if ( isset( $sm_status ) && 'off' === $sm_status ) {
            return false;
        }
        
        if ( !empty($get_condition_array) || '' !== $get_condition_array || null !== $get_condition_array ) {
            $country_array = array();
            $product_array = array();
            $category_array = array();
            $tag_array = array();
            $user_array = array();
            $cart_total_array = array();
            $quantity_array = array();
            $state_array = array();
            $city_array = array();
            $postcode_array = array();
            $zone_array = array();
            foreach ( $get_condition_array as $key => $value ) {
                if ( array_search( 'country', $value, true ) ) {
                    $country_array[$key] = $value;
                }
                if ( array_search( 'state', $value, true ) ) {
                    $state_array[$key] = $value;
                }
                if ( array_search( 'city', $value, true ) ) {
                    $city_array[$key] = $value;
                }
                if ( array_search( 'postcode', $value, true ) ) {
                    $postcode_array[$key] = $value;
                }
                if ( array_search( 'zone', $value, true ) ) {
                    $zone_array[$key] = $value;
                }
                if ( array_search( 'product', $value, true ) ) {
                    $product_array[$key] = $value;
                }
                if ( array_search( 'category', $value, true ) ) {
                    $category_array[$key] = $value;
                }
                if ( array_search( 'tag', $value, true ) ) {
                    $tag_array[$key] = $value;
                }
                if ( array_search( 'user', $value, true ) ) {
                    $user_array[$key] = $value;
                }
                if ( array_search( 'cart_total', $value, true ) ) {
                    $cart_total_array[$key] = $value;
                }
                if ( array_search( 'quantity', $value, true ) ) {
                    $quantity_array[$key] = $value;
                }
                //Check if is country exist
                
                if ( is_array( $country_array ) && isset( $country_array ) && !empty($country_array) && !empty($cart_product_ids_array) ) {
                    $country_passed = $this->afrsm_pro_match_country_rules( $country_array, $general_rule_match );
                    
                    if ( 'yes' === $country_passed ) {
                        $is_passed['has_fee_based_on_country'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_country'] = 'no';
                    }
                
                }
                
                //Check if is product exist
                
                if ( is_array( $product_array ) && isset( $product_array ) && !empty($product_array) && !empty($cart_product_ids_array) ) {
                    $product_passed = $this->afrsm_pro_match_simple_products_rule(
                        $cart_product_ids_array,
                        $product_array,
                        $general_rule_match,
                        $sm_post_id
                    );
                    
                    if ( 'yes' === $product_passed ) {
                        $is_passed['has_fee_based_on_product'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_product'] = 'no';
                    }
                
                }
                
                //Check if is Category exist
                
                if ( is_array( $category_array ) && isset( $category_array ) && !empty($category_array) && !empty($cart_main_product_ids_array) ) {
                    $category_passed = $this->afrsm_pro_match_category_rule( $cart_main_product_ids_array, $category_array, $general_rule_match );
                    
                    if ( 'yes' === $category_passed ) {
                        $is_passed['has_fee_based_on_category'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_category'] = 'no';
                    }
                
                }
                
                //Check if is tag exist
                
                if ( is_array( $tag_array ) && isset( $tag_array ) && !empty($tag_array) && !empty($cart_main_product_ids_array) ) {
                    $tag_passed = $this->afrsm_pro_match_tag_rule( $cart_main_product_ids_array, $tag_array, $general_rule_match );
                    
                    if ( 'yes' === $tag_passed ) {
                        $is_passed['has_fee_based_on_tag'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_tag'] = 'no';
                    }
                
                }
                
                //Check if is user exist
                
                if ( is_array( $user_array ) && isset( $user_array ) && !empty($user_array) && !empty($cart_product_ids_array) ) {
                    $user_passed = $this->afrsm_pro_match_user_rule( $user_array, $general_rule_match );
                    
                    if ( 'yes' === $user_passed ) {
                        $is_passed['has_fee_based_on_user'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_user'] = 'no';
                    }
                
                }
                
                //Check if is Cart Subtotal (Before Discount) exist
                
                if ( is_array( $cart_total_array ) && isset( $cart_total_array ) && !empty($cart_total_array) && !empty($cart_product_ids_array) ) {
                    $cart_total_before_passed = $this->afrsm_pro_match_cart_subtotal_before_discount_rule(
                        $wc_curr_version,
                        $cart_total_array,
                        $general_rule_match,
                        $sm_post_id
                    );
                    
                    if ( 'yes' === $cart_total_before_passed ) {
                        $is_passed['has_fee_based_on_cart_total_before'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_cart_total_before'] = 'no';
                    }
                
                }
                
                //Check if is quantity exist
                
                if ( is_array( $quantity_array ) && isset( $quantity_array ) && !empty($quantity_array) && !empty($cart_product_ids_array) ) {
                    $quantity_passed = $this->afrsm_pro_match_cart_total_cart_qty_rule( $quantity_array, $general_rule_match );
                    
                    if ( 'yes' === $quantity_passed ) {
                        $is_passed['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
                
                //Check if is state exist
                
                if ( is_array( $state_array ) && isset( $state_array ) && !empty($state_array) && !empty($cart_product_ids_array) ) {
                    $state_passed = $this->afrsm_pro_match_state_rules( $state_array, $general_rule_match );
                    
                    if ( 'yes' === $state_passed ) {
                        $is_passed['has_fee_based_on_state'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_state'] = 'no';
                    }
                
                }
                
                //Check if is city exist
                
                if ( is_array( $city_array ) && isset( $city_array ) && !empty($city_array) && !empty($cart_product_ids_array) ) {
                    $city_passed = $this->afrsm_pro_match_city_rules( $city_array, $general_rule_match );
                    
                    if ( 'yes' === $city_passed ) {
                        $is_passed['has_fee_based_on_postcode'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_postcode'] = 'no';
                    }
                
                }
                
                //Check if is postcode exist
                
                if ( is_array( $postcode_array ) && isset( $postcode_array ) && !empty($postcode_array) && !empty($cart_product_ids_array) ) {
                    $postcode_passed = $this->afrsm_pro_match_postcode_rules( $postcode_array, $general_rule_match );
                    
                    if ( 'yes' === $postcode_passed ) {
                        $is_passed['has_fee_based_on_postcode'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_postcode'] = 'no';
                    }
                
                }
                
                //Check if is zone exist
                
                if ( is_array( $zone_array ) && isset( $zone_array ) && !empty($zone_array) && !empty($cart_product_ids_array) ) {
                    $zone_passed = $this->afrsm_pro_match_zone_rules( $zone_array, $package, $general_rule_match );
                    
                    if ( 'yes' === $zone_passed ) {
                        $is_passed['has_fee_based_on_zone'] = 'yes';
                    } else {
                        $is_passed['has_fee_based_on_zone'] = 'no';
                    }
                
                }
            
            }
            
            if ( isset( $is_passed ) && !empty($is_passed) && is_array( $is_passed ) ) {
                $fnispassed = array();
                foreach ( $is_passed as $val ) {
                    if ( '' !== $val ) {
                        $fnispassed[] = $val;
                    }
                }
                
                if ( 'all' === $general_rule_match ) {
                    
                    if ( in_array( 'no', $fnispassed, true ) ) {
                        $final_is_passed_general_rule['passed'] = 'no';
                    } else {
                        $final_is_passed_general_rule['passed'] = 'yes';
                    }
                
                } else {
                    
                    if ( in_array( 'yes', $fnispassed, true ) ) {
                        $final_is_passed_general_rule['passed'] = 'yes';
                    } else {
                        $final_is_passed_general_rule['passed'] = 'no';
                    }
                
                }
            
            }
        
        }
        
        
        if ( empty($final_is_passed_general_rule) || '' === $final_is_passed_general_rule || null === $final_is_passed_general_rule ) {
            $new_is_passed['passed'] = 'no';
        } else {
            
            if ( !empty($final_is_passed_general_rule) && in_array( 'no', $final_is_passed_general_rule, true ) ) {
                $new_is_passed['passed'] = 'no';
            } else {
                
                if ( empty($final_is_passed_general_rule) && in_array( '', $final_is_passed_general_rule, true ) ) {
                    $new_is_passed['passed'] = 'no';
                } else {
                    if ( !empty($final_is_passed_general_rule) && in_array( 'yes', $final_is_passed_general_rule, true ) ) {
                        $new_is_passed['passed'] = 'yes';
                    }
                }
            
            }
        
        }
        
        if ( isset( $new_is_passed ) && !empty($new_is_passed) && is_array( $new_is_passed ) ) {
            
            if ( !in_array( 'no', $new_is_passed, true ) ) {
                $final_condition_flag[] = 'yes';
            } else {
                $final_condition_flag[] = 'no';
            }
        
        }
        
        if ( isset( $sm_select_log_in_user ) && "yes" === $sm_select_log_in_user ) {
            
            if ( !is_user_logged_in() ) {
                return false;
            } else {
                
                if ( empty($final_condition_flag) && $final_condition_flag === '' ) {
                    return false;
                } else {
                    
                    if ( !empty($final_condition_flag) && in_array( 'no', $final_condition_flag, true ) ) {
                        return false;
                    } else {
                        
                        if ( empty($final_condition_flag) && in_array( '', $final_condition_flag, true ) ) {
                            return false;
                        } else {
                            if ( !empty($final_condition_flag) && in_array( 'yes', $final_condition_flag, true ) ) {
                                return true;
                            }
                        }
                    
                    }
                
                }
            
            }
        
        } else {
            
            if ( empty($final_condition_flag) && $final_condition_flag === '' ) {
                return false;
            } else {
                
                if ( !empty($final_condition_flag) && in_array( 'no', $final_condition_flag, true ) ) {
                    return false;
                } else {
                    
                    if ( empty($final_condition_flag) && in_array( '', $final_condition_flag, true ) ) {
                        return false;
                    } else {
                        if ( !empty($final_condition_flag) && in_array( 'yes', $final_condition_flag, true ) ) {
                            return true;
                        }
                    }
                
                }
            
            }
        
        }
    
    }
    
    /**
     * Check product type for front.
     *
     * @param object $_product Get product object.
     *
     * @param array  $value    Cart details.
     *
     * @return boolean $flag.
     *
     * @since  3.8
     *
     * @author jb
     */
    public function afrsm_check_product_type_for_front( $_product, $value )
    {
        $flag = false;
        if ( $_product instanceof WC_Product ) {
            if ( !$_product->is_virtual( 'yes' ) && !$_product->is_type( 'bundle' ) ) {
                $flag = true;
            }
        }
        return apply_filters(
            'afrsm_check_product_type_for_front_ft',
            $flag,
            $_product,
            $value
        );
    }
    
    /**
     * Check product type for admin.
     *
     * @param object $_product Get product object.
     *
     * @return boolean $flag.
     *
     * @since  3.8
     *
     * @author jb
     */
    public function afrsm_check_product_type_for_admin( $_product )
    {
        $flag = false;
        
        if ( $_product instanceof WC_Product ) {
            
            if ( !$_product->is_virtual( 'yes' ) && $_product->is_type( 'variable' ) ) {
                $flag = true;
            } elseif ( !$_product->is_virtual( 'yes' ) && $_product->is_type( 'simple' ) ) {
                $flag = true;
            }
            
            // elseif ( $_product->is_type( 'bundle' ) ) {
            // 	$flag = true;
            // }
        }
        
        return apply_filters( 'afrsm_check_product_type_for_admin_ft', $flag, $_product );
    }
    
    /**
     * Match country rules
     *
     * @param array  $country_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     *
     * @uses     WC_Customer::get_shipping_country()
     *
     * @since    3.4
     */
    public function afrsm_pro_match_country_rules( $country_array, $general_rule_match )
    {
        $selected_country = WC()->customer->get_shipping_country();
        $is_passed = array();
        foreach ( $country_array as $key => $country ) {
            
            if ( 'is_equal_to' === $country['product_fees_conditions_is'] ) {
                if ( !empty($country['product_fees_conditions_values']) ) {
                    
                    if ( in_array( $selected_country, $country['product_fees_conditions_values'], true ) ) {
                        $is_passed[$key]['has_fee_based_on_country'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_country'] = 'no';
                    }
                
                }
                if ( empty($country['product_fees_conditions_values']) ) {
                    $is_passed[$key]['has_fee_based_on_country'] = 'yes';
                }
            }
            
            if ( 'not_in' === $country['product_fees_conditions_is'] ) {
                if ( !empty($country['product_fees_conditions_values']) ) {
                    
                    if ( in_array( $selected_country, $country['product_fees_conditions_values'], true ) || in_array( 'all', $country['product_fees_conditions_values'], true ) ) {
                        $is_passed[$key]['has_fee_based_on_country'] = 'no';
                    } else {
                        $is_passed[$key]['has_fee_based_on_country'] = 'yes';
                    }
                
                }
            }
        }
        /**
         * Filter for matched all passed rules.
         *
         * @since  3.8
         *
         * @author jb
         */
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( apply_filters(
            'afrsm_pro_match_country_rules_ft',
            $is_passed,
            $selected_country,
            $country_array,
            'has_fee_based_on_country',
            $general_rule_match
        ), 'has_fee_based_on_country', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match state rules
     *
     * @param array  $state_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     *
     * @since    3.4
     *
     * @uses     WC_Customer::get_shipping_country()
     * @uses     WC_Customer::get_shipping_state()
     *
     */
    public function afrsm_pro_match_state_rules( $state_array, $general_rule_match )
    {
        $country = WC()->customer->get_shipping_country();
        $state = WC()->customer->get_shipping_state();
        $selected_state = $country . ':' . $state;
        $is_passed = array();
        foreach ( $state_array as $key => $get_state ) {
            if ( 'is_equal_to' === $get_state['product_fees_conditions_is'] ) {
                if ( !empty($get_state['product_fees_conditions_values']) ) {
                    
                    if ( in_array( $selected_state, $get_state['product_fees_conditions_values'], true ) ) {
                        $is_passed[$key]['has_fee_based_on_state'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_state'] = 'no';
                    }
                
                }
            }
            if ( 'not_in' === $get_state['product_fees_conditions_is'] ) {
                if ( !empty($get_state['product_fees_conditions_values']) ) {
                    
                    if ( in_array( $selected_state, $get_state['product_fees_conditions_values'], true ) ) {
                        $is_passed[$key]['has_fee_based_on_state'] = 'no';
                    } else {
                        $is_passed[$key]['has_fee_based_on_state'] = 'yes';
                    }
                
                }
            }
        }
        /**
         * Filter for matched all passed rules.
         *
         * @since  3.8
         *
         * @author jb
         */
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( apply_filters(
            'afrsm_pro_match_state_rules_ft',
            $is_passed,
            $selected_state,
            $state_array,
            'has_fee_based_on_state',
            $general_rule_match
        ), 'has_fee_based_on_state', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match city rules
     *
     * @param array  $city_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @uses     WC_Customer::get_shipping_postcode()
     *
     * @since    3.4
     *
     */
    public function afrsm_pro_match_city_rules( $city_array, $general_rule_match )
    {
        $selected_city = strtolower( WC()->customer->get_shipping_city() );
        $is_passed = array();
        foreach ( $city_array as $key => $citycode ) {
            if ( 'is_equal_to' === $citycode['product_fees_conditions_is'] ) {
                
                if ( !empty($citycode['product_fees_conditions_values']) ) {
                    $citystr = str_replace( PHP_EOL, "<br/>", trim( $citycode['product_fees_conditions_values'] ) );
                    $city_val_array = explode( '<br/>', $citystr );
                    $new_city_array = array();
                    foreach ( $city_val_array as $value ) {
                        $new_city_array[] = trim( $value );
                    }
                    $new_city_array = array_map( 'strtolower', $new_city_array );
                    
                    if ( in_array( $selected_city, $new_city_array, true ) ) {
                        $is_passed[$key]['has_fee_based_on_postcode'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_postcode'] = 'no';
                    }
                
                }
            
            }
            if ( 'not_in' === $citycode['product_fees_conditions_is'] ) {
                
                if ( !empty($citycode['product_fees_conditions_values']) ) {
                    $citystr = str_replace( PHP_EOL, "<br/>", $citycode['product_fees_conditions_values'] );
                    $city_val_array = explode( '<br/>', $citystr );
                    $city_val_array = array_map( 'strtolower', $city_val_array );
                    
                    if ( in_array( $selected_city, $city_val_array, true ) ) {
                        $is_passed[$key]['has_fee_based_on_postcode'] = 'no';
                    } else {
                        $is_passed[$key]['has_fee_based_on_postcode'] = 'yes';
                    }
                
                }
            
            }
        }
        /**
         * Filter for matched all passed rules.
         *
         * @since  3.8
         *
         * @author jb
         */
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( apply_filters(
            'afrsm_pro_match_city_rules_ft',
            $is_passed,
            $selected_city,
            $city_array,
            'has_fee_based_on_postcode',
            $general_rule_match
        ), 'has_fee_based_on_postcode', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match postcode rules
     *
     * @param array  $postcode_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @uses     WC_Customer::get_shipping_postcode()
     *
     * @since    3.4
     *
     */
    public function afrsm_pro_match_postcode_rules( $postcode_array, $general_rule_match )
    {
        $selected_postcode = WC()->customer->get_shipping_postcode();
        $is_passed = array();
        foreach ( $postcode_array as $key => $postcode ) {
            if ( 'is_equal_to' === $postcode['product_fees_conditions_is'] ) {
                
                if ( !empty($postcode['product_fees_conditions_values']) ) {
                    $postcodestr = str_replace( PHP_EOL, "<br/>", trim( $postcode['product_fees_conditions_values'] ) );
                    $postcode_val_array = explode( '<br/>', $postcodestr );
                    $new_postcode_array = array();
                    foreach ( $postcode_val_array as $value ) {
                        $new_postcode_array[] = trim( $value );
                    }
                    
                    if ( in_array( $selected_postcode, $new_postcode_array, true ) ) {
                        $is_passed[$key]['has_fee_based_on_postcode'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_postcode'] = 'no';
                    }
                
                }
            
            }
            if ( 'not_in' === $postcode['product_fees_conditions_is'] ) {
                
                if ( !empty($postcode['product_fees_conditions_values']) ) {
                    $postcodestr = str_replace( PHP_EOL, "<br/>", $postcode['product_fees_conditions_values'] );
                    $postcode_val_array = explode( '<br/>', $postcodestr );
                    
                    if ( in_array( $selected_postcode, $postcode_val_array, true ) ) {
                        $is_passed[$key]['has_fee_based_on_postcode'] = 'no';
                    } else {
                        $is_passed[$key]['has_fee_based_on_postcode'] = 'yes';
                    }
                
                }
            
            }
        }
        /**
         * Filter for matched all passed rules.
         *
         * @since  3.8
         *
         * @author jb
         */
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( apply_filters(
            'afrsm_pro_match_postcode_rules_ft',
            $is_passed,
            $selected_postcode,
            $postcode_array,
            'has_fee_based_on_postcode',
            $general_rule_match
        ), 'has_fee_based_on_postcode', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match zone rules
     *
     * @param array        $zone_array
     * @param array|object $package
     * @param              $general_rule_match
     *
     * @return string $main_is_passed
     * @since    3.4
     *
     * @uses     afrsm_pro_check_zone_available()
     *
     */
    public function afrsm_pro_match_zone_rules( $zone_array, $package, $general_rule_match )
    {
        $is_passed = array();
        foreach ( $zone_array as $key => $zone ) {
            $zone['product_fees_conditions_values'] = array_map( 'intval', $zone['product_fees_conditions_values'] );
            if ( 'is_equal_to' === $zone['product_fees_conditions_is'] ) {
                
                if ( !empty($zone['product_fees_conditions_values']) ) {
                    $get_zonelist = $this->afrsm_pro_check_zone_available( $package, $zone['product_fees_conditions_values'] );
                    
                    if ( in_array( $get_zonelist, $zone['product_fees_conditions_values'], true ) ) {
                        $is_passed[$key]['has_fee_based_on_zone'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_zone'] = 'no';
                    }
                
                }
            
            }
            if ( 'not_in' === $zone['product_fees_conditions_is'] ) {
                
                if ( !empty($zone['product_fees_conditions_values']) ) {
                    $get_zonelist = $this->afrsm_pro_check_zone_available( $package, $zone['product_fees_conditions_values'] );
                    
                    if ( in_array( $get_zonelist, $zone['product_fees_conditions_values'], true ) ) {
                        $is_passed[$key]['has_fee_based_on_zone'] = 'no';
                    } else {
                        $is_passed[$key]['has_fee_based_on_zone'] = 'yes';
                    }
                
                }
            
            }
        }
        /**
         * Filter for matched all passed rules.
         *
         * @since  3.8
         *
         * @author jb
         */
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( apply_filters(
            'afrsm_pro_match_zone_rules_ft',
            $is_passed,
            $zone_array,
            'has_fee_based_on_zone',
            $general_rule_match
        ), 'has_fee_based_on_zone', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match simple products rules
     *
     * @param array  $cart_product_ids_array
     * @param array  $product_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @since    3.4
     *
     * @uses     afrsm_pro_fee_array_column_admin()
     *
     */
    public function afrsm_pro_match_simple_products_rule(
        $cart_product_ids_array,
        $product_array,
        $general_rule_match,
        $sm_post_id
    )
    {
        $is_passed = array();
        $is_passed_free = false;
        $free_shipping_based_on = get_post_meta( $sm_post_id, 'sm_free_shipping_based_on', true );
        $sm_free_shipping_based_on_product = get_post_meta( $sm_post_id, 'sm_free_shipping_based_on_product', true );
        if ( "min_simple_product" === $free_shipping_based_on && "" !== $sm_free_shipping_based_on_product ) {
            foreach ( $sm_free_shipping_based_on_product as $key => $free_shipping_product_id ) {
                settype( $free_shipping_product_id, 'integer' );
                
                if ( in_array( $free_shipping_product_id, $cart_product_ids_array, true ) ) {
                    $is_passed[$key]['has_fee_based_on_product'] = 'yes';
                    $is_passed_free = true;
                    break;
                } else {
                    $is_passed_free = false;
                }
            
            }
        }
        if ( false === $is_passed_free ) {
            foreach ( $product_array as $key => $product ) {
                if ( 'is_equal_to' === $product['product_fees_conditions_is'] ) {
                    if ( !empty($product['product_fees_conditions_values']) ) {
                        foreach ( $product['product_fees_conditions_values'] as $product_id ) {
                            settype( $product_id, 'integer' );
                            
                            if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
                                $is_passed[$key]['has_fee_based_on_product'] = 'yes';
                                break;
                            } else {
                                $is_passed[$key]['has_fee_based_on_product'] = 'no';
                            }
                        
                        }
                    }
                }
                if ( 'not_in' === $product['product_fees_conditions_is'] ) {
                    if ( !empty($product['product_fees_conditions_values']) ) {
                        foreach ( $product['product_fees_conditions_values'] as $product_id ) {
                            settype( $product_id, 'integer' );
                            
                            if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
                                $is_passed[$key]['has_fee_based_on_product'] = 'no';
                                break;
                            } else {
                                $is_passed[$key]['has_fee_based_on_product'] = 'yes';
                            }
                        
                        }
                    }
                }
                if ( 'only_equal_to' === $product['product_fees_conditions_is'] ) {
                    if ( !empty($product['product_fees_conditions_values']) ) {
                        foreach ( $cart_product_ids_array as $product_id ) {
                            settype( $product_id, 'integer' );
                            $product['product_fees_conditions_values'] = array_map( 'intval', $product['product_fees_conditions_values'] );
                            
                            if ( in_array( $product_id, $product['product_fees_conditions_values'], true ) ) {
                                $is_passed[$key]['has_fee_based_on_product'] = 'yes';
                            } else {
                                $is_passed[$key]['has_fee_based_on_product'] = 'no';
                                break;
                            }
                        
                        }
                    }
                }
            }
        }
        /**
         * Filter for matched all passed rules.
         *
         * @since  3.8
         *
         * @author jb
         */
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( apply_filters(
            'afrsm_pro_match_simple_products_rule_ft',
            $is_passed,
            $cart_product_ids_array,
            $product_array,
            'has_fee_based_on_product',
            $general_rule_match
        ), 'has_fee_based_on_product', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match category rules
     *
     * @param array  $cart_product_ids_array
     * @param array  $category_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @since    3.4
     *
     * @uses     afrsm_pro_fee_array_column_admin()
     * @uses     WC_Product class
     * @uses     WC_Product::is_virtual()
     * @uses     wp_get_post_terms()
     * @uses     afrsm_pro_array_flatten()
     *
     */
    public function afrsm_pro_match_category_rule( $cart_product_ids_array, $category_array, $general_rule_match )
    {
        $is_passed = array();
        $cart_category_id_array = array();
        foreach ( $cart_product_ids_array as $product ) {
            $cart_product_category = wp_get_post_terms( $product, 'product_cat', array(
                'fields' => 'ids',
            ) );
            if ( isset( $cart_product_category ) && !empty($cart_product_category) && is_array( $cart_product_category ) ) {
                $cart_category_id_array[] = $cart_product_category;
            }
        }
        $get_cat_all = array_unique( $this->afrsm_pro_array_flatten( $cart_category_id_array ) );
        foreach ( $category_array as $key => $category ) {
            if ( 'is_equal_to' === $category['product_fees_conditions_is'] ) {
                if ( !empty($category['product_fees_conditions_values']) ) {
                    foreach ( $category['product_fees_conditions_values'] as $category_id ) {
                        settype( $category_id, 'integer' );
                        
                        if ( in_array( $category_id, $get_cat_all, true ) ) {
                            $is_passed[$key]['has_fee_based_on_category'] = 'yes';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_category'] = 'no';
                        }
                    
                    }
                }
            }
            if ( 'not_in' === $category['product_fees_conditions_is'] ) {
                if ( !empty($category['product_fees_conditions_values']) ) {
                    foreach ( $category['product_fees_conditions_values'] as $category_id ) {
                        settype( $category_id, 'integer' );
                        
                        if ( in_array( $category_id, $get_cat_all, true ) ) {
                            $is_passed[$key]['has_fee_based_on_category'] = 'no';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_category'] = 'yes';
                        }
                    
                    }
                }
            }
            if ( 'only_equal_to' === $category['product_fees_conditions_is'] ) {
                if ( !empty($category['product_fees_conditions_values']) ) {
                    foreach ( $cart_product_ids_array as $product_id ) {
                        settype( $product_id, 'integer' );
                        $cart_product_category_ids = wp_get_post_terms( $product_id, 'product_cat', array(
                            'fields' => 'ids',
                        ) );
                        $category['product_fees_conditions_values'] = array_map( 'intval', $category['product_fees_conditions_values'] );
                        $common_ids = array_intersect( $cart_product_category_ids, $category['product_fees_conditions_values'] );
                        
                        if ( is_array( $common_ids ) && !empty($common_ids) ) {
                            $is_passed[$key]['has_fee_based_on_category'] = 'yes';
                        } else {
                            $is_passed[$key]['has_fee_based_on_category'] = 'no';
                            break;
                        }
                    
                    }
                }
            }
        }
        /**
         * Filter for matched all passed rules.
         *
         * @since  3.8
         *
         * @author jb
         */
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( apply_filters(
            'afrsm_pro_match_category_rule_ft',
            $is_passed,
            $cart_product_ids_array,
            $category_array,
            'has_fee_based_on_category',
            $general_rule_match
        ), 'has_fee_based_on_category', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match tag rules
     *
     * @param array  $cart_product_ids_array
     * @param array  $tag_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @since    3.4
     *
     * @uses     afrsm_pro_fee_array_column_admin()
     * @uses     WC_Product class
     * @uses     WC_Product::is_virtual()
     * @uses     wp_get_post_terms()
     * @uses     afrsm_pro_array_flatten()
     *
     */
    public function afrsm_pro_match_tag_rule( $cart_product_ids_array, $tag_array, $general_rule_match )
    {
        $tagid = array();
        $is_passed = array();
        foreach ( $cart_product_ids_array as $product ) {
            $cart_product_tag = wp_get_post_terms( $product, 'product_tag', array(
                'fields' => 'ids',
            ) );
            if ( isset( $cart_product_tag ) && !empty($cart_product_tag) && is_array( $cart_product_tag ) ) {
                $tagid[] = $cart_product_tag;
            }
        }
        $get_tag_all = array_unique( $this->afrsm_pro_array_flatten( $tagid ) );
        foreach ( $tag_array as $key => $tag ) {
            if ( 'is_equal_to' === $tag['product_fees_conditions_is'] ) {
                if ( !empty($tag['product_fees_conditions_values']) ) {
                    foreach ( $tag['product_fees_conditions_values'] as $tag_id ) {
                        settype( $tag_id, 'integer' );
                        
                        if ( in_array( $tag_id, $get_tag_all, true ) ) {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'yes';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'no';
                        }
                    
                    }
                }
            }
            if ( 'not_in' === $tag['product_fees_conditions_is'] ) {
                if ( !empty($tag['product_fees_conditions_values']) ) {
                    foreach ( $tag['product_fees_conditions_values'] as $tag_id ) {
                        settype( $tag_id, 'integer' );
                        
                        if ( in_array( $tag_id, $get_tag_all, true ) ) {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'no';
                            break;
                        } else {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'yes';
                        }
                    
                    }
                }
            }
            if ( 'only_equal_to' === $tag['product_fees_conditions_is'] ) {
                if ( !empty($tag['product_fees_conditions_values']) ) {
                    foreach ( $cart_product_ids_array as $product_id ) {
                        settype( $product_id, 'integer' );
                        $cart_product_tag_ids = wp_get_post_terms( $product_id, 'product_tag', array(
                            'fields' => 'ids',
                        ) );
                        $tag['product_fees_conditions_values'] = array_map( 'intval', $tag['product_fees_conditions_values'] );
                        $common_ids = array_intersect( $cart_product_tag_ids, $tag['product_fees_conditions_values'] );
                        
                        if ( is_array( $common_ids ) && !empty($common_ids) ) {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'yes';
                        } else {
                            $is_passed[$key]['has_fee_based_on_tag'] = 'no';
                            break;
                        }
                    
                    }
                }
            }
        }
        /**
         * Filter for matched all passed rules.
         *
         * @since  3.8
         *
         * @author jb
         */
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( apply_filters(
            'afrsm_pro_match_tag_rule_ft',
            $is_passed,
            $cart_product_ids_array,
            $tag_array,
            'has_fee_based_on_tag',
            $general_rule_match
        ), 'has_fee_based_on_tag', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match user rules
     *
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @uses     get_current_user_id()
     * @since    3.4
     *
     * @uses     is_user_logged_in()
     */
    public function afrsm_pro_match_user_rule( $user_array, $general_rule_match )
    {
        if ( !is_user_logged_in() ) {
            return false;
        }
        $current_user_id = get_current_user_id();
        $is_passed = array();
        foreach ( $user_array as $key => $user ) {
            $user['product_fees_conditions_values'] = array_map( 'intval', $user['product_fees_conditions_values'] );
            if ( 'is_equal_to' === $user['product_fees_conditions_is'] ) {
                
                if ( in_array( $current_user_id, $user['product_fees_conditions_values'], true ) ) {
                    $is_passed[$key]['has_fee_based_on_user'] = 'yes';
                } else {
                    $is_passed[$key]['has_fee_based_on_user'] = 'no';
                }
            
            }
            if ( 'not_in' === $user['product_fees_conditions_is'] ) {
                
                if ( in_array( $current_user_id, $user['product_fees_conditions_values'], true ) ) {
                    $is_passed[$key]['has_fee_based_on_user'] = 'no';
                } else {
                    $is_passed[$key]['has_fee_based_on_user'] = 'yes';
                }
            
            }
        }
        /**
         * Filter for matched all passed rules.
         *
         * @since  3.8
         *
         * @author jb
         */
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( apply_filters(
            'afrsm_pro_match_user_rule_ft',
            $is_passed,
            $user_array,
            'has_fee_based_on_user',
            $general_rule_match
        ), 'has_fee_based_on_user', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match rule based on cart subtotal before discount
     *
     * @param string $wc_curr_version
     * @param array  $cart_total_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     *
     * @since    3.4
     *
     * @uses     WC_Cart::get_subtotal()
     *
     */
    public function afrsm_pro_match_cart_subtotal_before_discount_rule(
        $wc_curr_version,
        $cart_total_array,
        $general_rule_match,
        $sm_post_id
    )
    {
        global  $woocommerce, $woocommerce_wpml ;
        
        if ( $wc_curr_version >= 3.0 ) {
            $total = $this->afrsm_pro_get_cart_subtotal();
        } else {
            $total = $woocommerce->cart->subtotal;
        }
        
        
        if ( isset( $woocommerce_wpml ) && !empty($woocommerce_wpml->multi_currency) ) {
            $new_total = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $total );
        } else {
            $new_total = $total;
        }
        
        $is_passed = array();
        $is_allow_free_shipping = get_post_meta( $sm_post_id, 'is_allow_free_shipping', true );
        $free_shipping_based_on = get_post_meta( $sm_post_id, 'sm_free_shipping_based_on', true );
        $free_shipping_costs = get_post_meta( $sm_post_id, 'sm_free_shipping_cost', true );
        //convert price if multi currency WOOCS exist
        $free_shipping_costs = $this->afrsm_woocs_convert_price( $free_shipping_costs );
        
        if ( !empty($is_allow_free_shipping) && "on" === $is_allow_free_shipping && "min_order_amt" === $free_shipping_based_on && "" !== $free_shipping_costs && $new_total > $free_shipping_costs ) {
            foreach ( $cart_total_array as $key => $cart_total ) {
                $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
            }
        } else {
            foreach ( $cart_total_array as $key => $cart_total ) {
                $cart_total['product_fees_conditions_values'] = $this->afrsm_pro_price_based_on_switcher( $cart_total['product_fees_conditions_values'] );
                // convert curranct for Multi Currency for WooCommerce
                $cart_total['product_fees_conditions_values'] = $this->afrsm_woocs_convert_price( $cart_total['product_fees_conditions_values'] );
                // convert currency for WOOCS plugin
                settype( $cart_total['product_fees_conditions_values'], 'float' );
                if ( 'is_equal_to' === $cart_total['product_fees_conditions_is'] ) {
                    if ( !empty($cart_total['product_fees_conditions_values']) ) {
                        
                        if ( $cart_total['product_fees_conditions_values'] === $new_total ) {
                            $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                        } else {
                            $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                        }
                    
                    }
                }
                if ( 'less_equal_to' === $cart_total['product_fees_conditions_is'] ) {
                    if ( !empty($cart_total['product_fees_conditions_values']) ) {
                        
                        if ( $cart_total['product_fees_conditions_values'] >= $new_total ) {
                            $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                        } else {
                            $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                        }
                    
                    }
                }
                if ( 'less_then' === $cart_total['product_fees_conditions_is'] ) {
                    if ( !empty($cart_total['product_fees_conditions_values']) ) {
                        
                        if ( $cart_total['product_fees_conditions_values'] > $new_total ) {
                            $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                        } else {
                            $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                        }
                    
                    }
                }
                if ( 'greater_equal_to' === $cart_total['product_fees_conditions_is'] ) {
                    if ( !empty($cart_total['product_fees_conditions_values']) ) {
                        
                        if ( $cart_total['product_fees_conditions_values'] <= $new_total ) {
                            $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                        } else {
                            $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                        }
                    
                    }
                }
                
                if ( 'greater_then' === $cart_total['product_fees_conditions_is'] ) {
                    $cart_total['product_fees_conditions_values'];
                    if ( !empty($cart_total['product_fees_conditions_values']) ) {
                        
                        if ( $cart_total['product_fees_conditions_values'] < $new_total ) {
                            $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                        } else {
                            $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                        }
                    
                    }
                }
                
                if ( 'not_in' === $cart_total['product_fees_conditions_is'] ) {
                    if ( !empty($cart_total['product_fees_conditions_values']) ) {
                        
                        if ( $new_total === $cart_total['product_fees_conditions_values'] ) {
                            $is_passed[$key]['has_fee_based_on_cart_total'] = 'no';
                        } else {
                            $is_passed[$key]['has_fee_based_on_cart_total'] = 'yes';
                        }
                    
                    }
                }
            }
        }
        
        /**
         * Filter for matched all passed rules.
         *
         * @since  3.8
         *
         * @author jb
         */
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( apply_filters(
            'afrsm_pro_match_cart_subtotal_before_discount_rule_ft',
            $is_passed,
            $wc_curr_version,
            $cart_total_array,
            'has_fee_based_on_cart_total',
            $general_rule_match
        ), 'has_fee_based_on_cart_total', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match rule based on total cart quantity
     *
     * @param array  $cart_array
     * @param array  $quantity_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @since    3.4
     *
     * @uses     WC_Cart::get_cart()
     *
     */
    public function afrsm_pro_match_cart_total_cart_qty_rule( $quantity_array, $general_rule_match )
    {
        $quantity_total = 0;
        $cart_array = $this->afrsm_pro_get_cart();
        foreach ( $cart_array as $woo_cart_item ) {
            $check_virtual = $this->afrsm_check_product_type_for_front( $woo_cart_item['data'], $woo_cart_item );
            if ( true === $check_virtual ) {
                $quantity_total += $woo_cart_item['quantity'];
            }
        }
        $is_passed = array();
        foreach ( $quantity_array as $key => $quantity ) {
            settype( $quantity['product_fees_conditions_values'], 'integer' );
            if ( 'is_equal_to' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'less_equal_to' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] >= $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'less_then' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] > $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'greater_equal_to' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] <= $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'greater_then' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] < $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    }
                
                }
            }
            if ( 'not_in' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'no';
                    } else {
                        $is_passed[$key]['has_fee_based_on_quantity'] = 'yes';
                    }
                
                }
            }
        }
        /**
         * Filter for matched all passed rules.
         *
         * @since  3.8
         *
         * @author jb
         */
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( apply_filters(
            'afrsm_pro_match_cart_total_cart_qty_rule_ft',
            $is_passed,
            $cart_array,
            $quantity_array,
            'has_fee_based_on_quantity',
            $general_rule_match
        ), 'has_fee_based_on_quantity', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Match rule based on product qty
     *
     * @param array  $cart_array
     * @param array  $quantity_array
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @since    3.4
     *
     * @uses     WC_Cart::get_cart()
     *
     */
    public function afrsm_pro_match_product_based_qty_rule( $product_qty, $quantity_array, $general_rule_match )
    {
        $quantity_total = 0;
        if ( 0 < $product_qty ) {
            $quantity_total = $product_qty;
        }
        $is_passed = array();
        foreach ( $quantity_array as $key => $quantity ) {
            settype( $quantity['product_fees_conditions_values'], 'integer' );
            if ( 'is_equal_to' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
                        $is_passed[$key]['has_fee_based_on_product_qty'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_product_qty'] = 'no';
                    }
                
                }
            }
            if ( 'less_equal_to' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] >= $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_product_qty'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_product_qty'] = 'no';
                    }
                
                }
            }
            if ( 'less_then' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] > $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_product_qty'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_product_qty'] = 'no';
                    }
                
                }
            }
            if ( 'greater_equal_to' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] <= $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_product_qty'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_product_qty'] = 'no';
                    }
                
                }
            }
            if ( 'greater_then' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity['product_fees_conditions_values'] < $quantity_total ) {
                        $is_passed[$key]['has_fee_based_on_product_qty'] = 'yes';
                    } else {
                        $is_passed[$key]['has_fee_based_on_product_qty'] = 'no';
                    }
                
                }
            }
            if ( 'not_in' === $quantity['product_fees_conditions_is'] ) {
                if ( !empty($quantity['product_fees_conditions_values']) ) {
                    
                    if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
                        $is_passed[$key]['has_fee_based_on_product_qty'] = 'no';
                    } else {
                        $is_passed[$key]['has_fee_based_on_product_qty'] = 'yes';
                    }
                
                }
            }
        }
        /**
         * Filter for matched all passed rules.
         *
         * @since  3.8
         *
         * @author jb
         */
        $main_is_passed = $this->afrsm_pro_check_all_passed_general_rule( apply_filters(
            'afrsm_pro_match_product_based_qty_rule_ft',
            $is_passed,
            $product_qty,
            $quantity_array,
            'has_fee_based_on_product_qty',
            $general_rule_match
        ), 'has_fee_based_on_product_qty', $general_rule_match );
        return $main_is_passed;
    }
    
    /**
     * Find unique id based on given array
     *
     * @param array  $is_passed
     * @param string $has_fee_based
     * @param string $general_rule_match
     *
     * @return string $main_is_passed
     * @since    3.6
     *
     */
    public function afrsm_pro_check_all_passed_general_rule( $is_passed, $has_fee_based, $general_rule_match )
    {
        $main_is_passed = 'no';
        $flag = array();
        
        if ( !empty($is_passed) ) {
            foreach ( $is_passed as $key => $is_passed_value ) {
                
                if ( 'yes' === $is_passed_value[$has_fee_based] ) {
                    $flag[$key] = true;
                } else {
                    $flag[$key] = false;
                }
            
            }
            
            if ( 'any' === $general_rule_match ) {
                
                if ( in_array( true, $flag, true ) ) {
                    $main_is_passed = 'yes';
                } else {
                    $main_is_passed = 'no';
                }
            
            } else {
                
                if ( in_array( false, $flag, true ) ) {
                    $main_is_passed = 'no';
                } else {
                    $main_is_passed = 'yes';
                }
            
            }
        
        }
        
        /**
         * Filter for matched all passed general rules.
         *
         * @since  3.8
         *
         * @author jb
         */
        return apply_filters(
            'afrsm_pro_check_all_passed_general_rule_ft',
            $main_is_passed,
            $is_passed,
            $has_fee_based,
            $general_rule_match
        );
    }
    
    /**
     * Find unique id based on given array
     *
     * @param array $array
     *
     * @return array $result if $array is empty it will return false otherwise return array as $result
     * @since    1.0.0
     *
     */
    public function afrsm_pro_array_flatten( $array )
    {
        if ( !is_array( $array ) ) {
            return false;
        }
        $result = array();
        foreach ( $array as $key => $value ) {
            
            if ( is_array( $value ) ) {
                $result = array_merge( $result, $this->afrsm_pro_array_flatten( $value ) );
            } else {
                $result[$key] = $value;
            }
        
        }
        return $result;
    }
    
    /**
     * Find a matching zone for a given package.
     *
     * @param array|object $package
     * @param array        $available_zone_id_array
     *
     * @return int $return_zone_id
     * @uses   afrsm_pro_wc_make_numeric_postcode()
     *
     * @since  3.0.0
     *
     */
    public function afrsm_pro_check_zone_available( $package, $available_zone_id_array )
    {
        $return_zone_id = '';
        //Cart page package selection
        $shipping_packages = WC()->cart->get_shipping_packages();
        // Get the WC_Shipping_Zones instance object for the first package
        $shipping_zone = wc_get_shipping_zone( reset( $shipping_packages ) );
        $zone_id = $shipping_zone->get_id();
        // Get the zone ID
        if ( !empty($zone_id) && $zone_id > 0 && in_array( $zone_id, $available_zone_id_array ) ) {
            return $zone_id;
        }
        
        if ( $package ) {
            $country = $package['destination']['country'];
            
            if ( !empty($package['destination']['state']) && '' !== $package['destination']['state'] ) {
                $state = $country . ':' . $package['destination']['state'];
            } else {
                $state = '';
            }
            
            $postcode = strtoupper( $package['destination']['postcode'] );
            $cart_city = $package['destination']['city'];
            $valid_postcodes = array( '*', $postcode );
            // Work out possible valid wildcard postcodes
            $postcode_length = strlen( $postcode );
            $wildcard_postcode = $postcode;
            for ( $i = 0 ;  $i < $postcode_length ;  $i++ ) {
                $wildcard_postcode = substr( $wildcard_postcode, 0, -1 );
                $valid_postcodes[] = $wildcard_postcode . '*';
            }
            foreach ( $available_zone_id_array as $available_zone_id ) {
                $postcode_ranges = new WP_Query( array(
                    'post_type'      => 'wc_afrsm_zone',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'post__in'       => array( $available_zone_id ),
                ) );
                $location_code = array();
                foreach ( $postcode_ranges->posts as $postcode_ranges_value ) {
                    $postcode_ranges_location_code = get_post_meta( $postcode_ranges_value->ID, 'location_code', false );
                    $zone_type = get_post_meta( $postcode_ranges_value->ID, 'zone_type', true );
                    $location_code[$postcode_ranges_value->ID] = $postcode_ranges_location_code;
                    foreach ( $postcode_ranges_location_code as $location_code_sub_val ) {
                        $country_array = array();
                        $state_array = array();
                        foreach ( $location_code_sub_val as $location_country_state => $location_code_postcode_val ) {
                            
                            if ( 'postcodes' === $zone_type ) {
                                
                                if ( false !== strpos( $location_country_state, ':' ) ) {
                                    $location_country_state_explode = explode( ':', $location_country_state );
                                } else {
                                    $state_array = array();
                                }
                                
                                
                                if ( !empty($location_country_state_explode) ) {
                                    if ( array_key_exists( '0', $location_country_state_explode ) ) {
                                        $country_array[] = $location_country_state_explode[0];
                                    }
                                } else {
                                    $country_array[] = $location_country_state;
                                }
                                
                                if ( !empty($location_country_state_explode) ) {
                                    if ( array_key_exists( '1', $location_country_state_explode ) ) {
                                        $state_array[] = $location_country_state;
                                    }
                                }
                                foreach ( $location_code_postcode_val as $location_code_val ) {
                                    if ( false !== strpos( $location_code_val, '=' ) ) {
                                        $location_code_val = str_replace( '=', ' ', $location_code_val );
                                    }
                                    
                                    if ( false !== strpos( $location_code_val, '-' ) ) {
                                        
                                        if ( $postcode_ranges->posts ) {
                                            $encoded_postcode = $this->afrsm_pro_wc_make_numeric_postcode( $postcode );
                                            $encoded_postcode_len = strlen( $encoded_postcode );
                                            $range = array_map( 'trim', explode( '-', $location_code_val ) );
                                            if ( 2 !== sizeof( $range ) ) {
                                                continue;
                                            }
                                            
                                            if ( is_numeric( $range[0] ) && is_numeric( $range[1] ) ) {
                                                $encoded_postcode = $postcode;
                                                $min = $range[0];
                                                $max = $range[1];
                                            } else {
                                                $min = $this->afrsm_pro_wc_make_numeric_postcode( $range[0] );
                                                $max = $this->afrsm_pro_wc_make_numeric_postcode( $range[1] );
                                                $min = str_pad( $min, $encoded_postcode_len, '0' );
                                                $max = str_pad( $max, $encoded_postcode_len, '9' );
                                            }
                                            
                                            if ( $encoded_postcode >= $min && $encoded_postcode <= $max ) {
                                                $return_zone_id = $available_zone_id;
                                            }
                                        }
                                    
                                    } elseif ( false !== strpos( $location_code_val, '*' ) ) {
                                        if ( in_array( $location_code_val, $valid_postcodes, true ) ) {
                                            $return_zone_id = $available_zone_id;
                                        }
                                    } else {
                                        if ( in_array( $country, $country_array, true ) ) {
                                            
                                            if ( !empty($state_array) ) {
                                                if ( in_array( $state, $state_array, true ) ) {
                                                    if ( in_array( $postcode, $location_code_postcode_val, true ) ) {
                                                        $return_zone_id = $available_zone_id;
                                                    }
                                                }
                                            } else {
                                                if ( $postcode === $location_code_val ) {
                                                    $return_zone_id = $available_zone_id;
                                                }
                                            }
                                        
                                        }
                                    }
                                
                                }
                            } elseif ( 'cities' === $zone_type ) {
                                
                                if ( false !== strpos( $location_country_state, ':' ) ) {
                                    $location_country_state_explode = explode( ':', $location_country_state );
                                } else {
                                    $state_array = array();
                                }
                                
                                
                                if ( !empty($location_country_state_explode) ) {
                                    if ( array_key_exists( '0', $location_country_state_explode ) ) {
                                        $country_array[] = $location_country_state_explode[0];
                                    }
                                } else {
                                    $country_array[] = $location_country_state;
                                }
                                
                                if ( !empty($location_country_state_explode) ) {
                                    if ( array_key_exists( '1', $location_country_state_explode ) ) {
                                        $state_array[] = $location_country_state;
                                    }
                                }
                                foreach ( $location_code_postcode_val as $city_val ) {
                                    if ( in_array( $country, $country_array, true ) ) {
                                        
                                        if ( !empty($state_array) ) {
                                            if ( in_array( $state, $state_array, true ) ) {
                                                if ( in_array( $cart_city, $location_code_postcode_val, true ) ) {
                                                    $return_zone_id = $available_zone_id;
                                                }
                                            }
                                        } else {
                                            if ( $cart_city === $city_val ) {
                                                $return_zone_id = $available_zone_id;
                                            }
                                        }
                                    
                                    }
                                }
                            } elseif ( 'countries' === $zone_type ) {
                                if ( !empty($country) && in_array( $country, $location_code_postcode_val, true ) ) {
                                    $return_zone_id = $available_zone_id;
                                }
                            } elseif ( 'states' === $zone_type ) {
                                if ( !empty($state) && in_array( $state, $location_code_postcode_val, true ) ) {
                                    $return_zone_id = $available_zone_id;
                                }
                            }
                        
                        }
                    }
                }
            }
        }
        
        return $return_zone_id;
    }
    
    /**
     * Make numeric postcode function.
     *
     * @param mixed $postcode
     *
     * @return void
     * @since  1.0.0
     *
     * Converts letters to numbers so we can do a simple range check on postcodes.
     *
     * E.g. PE30 becomes 16050300 (P = 16, E = 05, 3 = 03, 0 = 00)
     *
     * @access public
     *
     */
    function afrsm_pro_wc_make_numeric_postcode( $postcode )
    {
        $postcode_length = strlen( $postcode );
        $letters_to_numbers = array_merge( array( 0 ), range( 'A', 'Z' ) );
        $letters_to_numbers = array_flip( $letters_to_numbers );
        $numeric_postcode = '';
        for ( $i = 0 ;  $i < $postcode_length ;  $i++ ) {
            
            if ( is_numeric( $postcode[$i] ) ) {
                $numeric_postcode .= str_pad(
                    $postcode[$i],
                    2,
                    '0',
                    STR_PAD_LEFT
                );
            } elseif ( isset( $letters_to_numbers[$postcode[$i]] ) ) {
                $numeric_postcode .= str_pad(
                    $letters_to_numbers[$postcode[$i]],
                    2,
                    '0',
                    STR_PAD_LEFT
                );
            } else {
                $numeric_postcode .= '00';
            }
        
        }
        return $numeric_postcode;
    }
    
    /**
     * Display array column
     *
     * @param array $input
     * @param int   $columnKey
     * @param int   $indexKey
     *
     * @return array $array It will return array if any error generate then it will return false
     * @since  1.0.0
     *
     */
    public function afrsm_pro_fee_array_column_admin( array $input, $columnKey, $indexKey = null )
    {
        $array = array();
        foreach ( $input as $value ) {
            
            if ( !isset( $value[$columnKey] ) ) {
                wp_die( sprintf( esc_html_x( 'Key %d does not exist in array', esc_attr( $columnKey ), 'advanced-flat-rate-shipping-for-woocommerce' ) ) );
                return false;
            }
            
            
            if ( is_null( $indexKey ) ) {
                $array[] = $value[$columnKey];
            } else {
                
                if ( !isset( $value[$indexKey] ) ) {
                    wp_die( sprintf( esc_html_x( 'Key %d does not exist in array', esc_attr( $indexKey ), 'advanced-flat-rate-shipping-for-woocommerce' ) ) );
                    return false;
                }
                
                
                if ( !is_scalar( $value[$indexKey] ) ) {
                    wp_die( sprintf( esc_html_x( 'Key %d does not contain scalar value', esc_attr( $indexKey ), 'advanced-flat-rate-shipping-for-woocommerce' ) ) );
                    return false;
                }
                
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        
        }
        return $array;
    }
    
    /**
     * Remove WooCommerce currency symbol
     *
     * @param float $price
     *
     * @return float $new_price2
     * @since  1.0.0
     *
     * @uses   get_woocommerce_currency_symbol()
     *
     */
    public function afrsm_pro_remove_currency_symbol( $price )
    {
        $wc_currency_symbol = get_woocommerce_currency_symbol();
        $cleanText = strip_tags( $price );
        $new_price = str_replace( $wc_currency_symbol, '', $cleanText );
        
        if ( "," === wc_get_price_decimal_separator() ) {
            $new_price2 = (double) str_replace( ",", ".", str_replace( ".", "", $new_price ) );
        } else {
            $new_price2 = (double) preg_replace( '/[^.\\d]/', '', $new_price );
        }
        
        return $new_price2;
    }
    
    /*
     * Get WooCommerce version number
     *
     * @since 1.0.0
     *
     * @return string if file is not exists then it will return null
     */
    function afrsm_pro_get_woo_version_number()
    {
        // If get_plugins() isn't available, require it
        if ( !function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        // Create the plugins folder and file variables
        $plugin_folder = get_plugins( '/' . 'woocommerce' );
        $plugin_file = 'woocommerce.php';
        // If the plugin version number is set, return it
        
        if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
            return $plugin_folder[$plugin_file]['Version'];
        } else {
            return null;
        }
    
    }
    
    /**
     * Save shipping order in shipping list section
     *
     * @since 1.0.0
     */
    public function afrsm_pro_sm_sort_order()
    {
        global  $wpdb ;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $post_type = self::afrsm_shipping_post_type;
        $paged = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT );
        $get_smOrderArray = filter_input(
            INPUT_GET,
            'smOrderArray',
            FILTER_SANITIZE_NUMBER_INT,
            FILTER_REQUIRE_ARRAY
        );
        $smOrderArray = ( !empty($get_smOrderArray) ? array_map( 'sanitize_text_field', wp_unslash( $get_smOrderArray ) ) : '' );
        //If order array empty then no need to order
        if ( empty($smOrderArray) ) {
            wp_die();
        }
        //Get all shipping post ids
        $mysql_query = $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type = %s AND post_status IN ('publish', 'draft') ORDER BY menu_order, post_date DESC", $post_type );
        $results = $wpdb->get_results( $mysql_query );
        //Create the list of ID's
        $objects_ids = array();
        foreach ( $results as $result ) {
            $objects_ids[] = (int) $result->ID;
        }
        //Here we switch order
        $objects_per_page = ( get_option( 'afrsm_sm_count_per_page' ) ? get_option( 'afrsm_sm_count_per_page' ) : 30 );
        $edit_start_at = $paged * $objects_per_page - $objects_per_page;
        $index = 0;
        for ( $i = $edit_start_at ;  $i < $edit_start_at + $objects_per_page ;  $i++ ) {
            if ( !isset( $objects_ids[$i] ) ) {
                break;
            }
            $objects_ids[$i] = (int) $smOrderArray[$index];
            $index++;
        }
        //Update the menu_order within database
        foreach ( $objects_ids as $menu_order => $id ) {
            $data = array(
                'menu_order' => $menu_order,
            );
            $wpdb->update( $wpdb->posts, $data, array(
                'ID' => $id,
            ) );
            clean_post_cache( $id );
        }
        //Update for our global variable
        if ( isset( $objects_ids ) && !empty($objects_ids) ) {
            update_option( 'sm_sortable_order_' . $default_lang, $objects_ids );
        }
        wp_die();
    }
    
    /**
     * Save master settings data
     *
     * @since 1.0.0
     */
    public function afrsm_pro_save_master_settings()
    {
        $get_shipping_display_mode = filter_input( INPUT_GET, 'shipping_display_mode', FILTER_SANITIZE_STRING );
        $get_chk_enable_logging = filter_input( INPUT_GET, 'chk_enable_logging', FILTER_SANITIZE_STRING );
        $get_afrsm_force_customer_to_select_sm = filter_input( INPUT_GET, 'afrsm_force_customer_to_select_sm', FILTER_SANITIZE_STRING );
        $get_afrsm_sm_count_per_page = filter_input( INPUT_GET, 'afrsm_sm_count_per_page', FILTER_SANITIZE_NUMBER_INT );
        $shipping_display_mode = ( !empty($get_shipping_display_mode) ? sanitize_text_field( wp_unslash( $get_shipping_display_mode ) ) : '' );
        $afrsm_force_customer_to_select_sm = ( !empty($get_afrsm_force_customer_to_select_sm) ? sanitize_text_field( wp_unslash( $get_afrsm_force_customer_to_select_sm ) ) : '' );
        $afrsm_sm_count_per_page = ( !empty($get_afrsm_sm_count_per_page) && $get_afrsm_sm_count_per_page <= 30 ? intval( wp_unslash( $get_afrsm_sm_count_per_page ) ) : 30 );
        if ( isset( $shipping_display_mode ) && !empty($shipping_display_mode) ) {
            update_option( 'md_woocommerce_shipping_method_format', $shipping_display_mode );
        }
        if ( isset( $get_chk_enable_logging ) && !empty($get_chk_enable_logging) ) {
            update_option( 'chk_enable_logging', $get_chk_enable_logging );
        }
        
        if ( isset( $afrsm_force_customer_to_select_sm ) && !empty($afrsm_force_customer_to_select_sm) ) {
            update_option( 'afrsm_force_customer_to_select_sm', $afrsm_force_customer_to_select_sm );
        } else {
            update_option( 'afrsm_force_customer_to_select_sm', '' );
        }
        
        
        if ( isset( $afrsm_sm_count_per_page ) && !empty($afrsm_sm_count_per_page) ) {
            update_option( 'afrsm_sm_count_per_page', $afrsm_sm_count_per_page );
        } else {
            update_option( 'afrsm_sm_count_per_page', 30 );
        }
        
        wp_die();
    }
    
    /**
     * Display textfield and multiselect dropdown based on country, state, zone and etc
     *
     * @return string $html
     * @since 1.0.0
     *
     * @uses  afrsm_pro_get_country_list()
     * @uses  afrsm_pro_get_states_list()
     * @uses  afrsm_pro_get_zones_list()
     * @uses  afrsm_pro_get_product_list()
     * @uses  afrsm_pro_get_varible_product_list__premium_only()
     * @uses  afrsm_pro_get_category_list()
     * @uses  afrsm_pro_get_tag_list()
     * @uses  afrsm_pro_get_sku_list__premium_only()
     * @uses  afrsm_pro_get_user_list()
     * @uses  afrsm_pro_get_user_role_list__premium_only()
     * @uses  afrsm_pro_get_coupon_list__premium_only()
     * @uses  afrsm_pro_get_advance_flat_rate_class__premium_only()
     * @uses  Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags()
     *
     */
    public function afrsm_pro_product_fees_conditions_values_ajax()
    {
        $get_condition = filter_input( INPUT_GET, 'condition', FILTER_SANITIZE_STRING );
        $get_count = filter_input( INPUT_GET, 'count', FILTER_SANITIZE_NUMBER_INT );
        $condition = ( isset( $get_condition ) ? sanitize_text_field( $get_condition ) : '' );
        $count = ( isset( $get_count ) ? sanitize_text_field( $get_count ) : '' );
        $html = '';
        
        if ( 'country' === $condition ) {
            $html .= wp_json_encode( $this->afrsm_pro_get_country_list( $count, [], true ) );
        } elseif ( 'state' === $condition ) {
            $html .= wp_json_encode( $this->afrsm_pro_get_states_list( $count, [], true ) );
        } elseif ( 'postcode' === $condition ) {
            $html .= 'textarea';
        } elseif ( 'zone' === $condition ) {
            $html .= wp_json_encode( $this->afrsm_pro_get_zones_list( $count, [], true ) );
        } elseif ( 'product' === $condition ) {
            $html .= wp_json_encode( $this->afrsm_pro_get_product_list( $count, [], true ) );
        } elseif ( 'category' === $condition ) {
            $html .= wp_json_encode( $this->afrsm_pro_get_category_list( $count, [], true ) );
        } elseif ( 'tag' === $condition ) {
            $html .= wp_json_encode( $this->afrsm_pro_get_tag_list( $count, [], true ) );
        } elseif ( 'user' === $condition ) {
            $html .= wp_json_encode( $this->afrsm_pro_get_user_list( $count, [], true ) );
        } elseif ( 'cart_total' === $condition ) {
            $html .= 'input';
        } elseif ( 'quantity' === $condition ) {
            $html .= 'input';
        }
        
        /**
         * Filter for dynamic condition field value.
         *
         * @since  3.8
         *
         * @author jb
         */
        echo  wp_kses( apply_filters(
            'afrsm_pro_product_fees_conditions_values_ajax_ft',
            $html,
            $condition,
            $count
        ), Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() ) ;
        wp_die();
        // this is required to terminate immediately and return a proper response
    }
    
    /**
     * Get country list
     *
     * @param string $count
     * @param array  $selected
     *
     * @return string $html
     * @uses   WC_Countries() class
     *
     * @since  1.0.0
     *
     */
    public function afrsm_pro_get_country_list( $count = '', $selected = array(), $json = false )
    {
        $countries_obj = new WC_Countries();
        $getCountries = $countries_obj->__get( 'countries' );
        $html = '<select name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_country" multiple="multiple">';
        if ( !empty($getCountries) ) {
            foreach ( $getCountries as $code => $country ) {
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $code, $selected, true ) ? 'selected=selected' : '' );
                $html .= '<option value="' . esc_attr( $code ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $country ) . '</option>';
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->afrsm_pro_convert_array_to_json( $getCountries );
        }
        return $html;
    }
    
    /**
     * Get the states for a country.
     *
     * @param string $count
     * @param array  $selected
     *
     * @return string $html
     * @since  1.0.0
     *
     * @uses   WC_Countries::get_allowed_countries()
     * @uses   WC_Countries::get_states()
     *
     */
    public function afrsm_pro_get_states_list( $count = '', $selected = array(), $json = false )
    {
        $filter_states = [];
        $countries = WC()->countries->get_allowed_countries();
        $html = '<select name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_state" multiple="multiple">';
        if ( !empty($countries) && is_array( $countries ) ) {
            foreach ( $countries as $key => $val ) {
                $states = WC()->countries->get_states( $key );
                if ( !empty($states) ) {
                    foreach ( $states as $state_key => $state_value ) {
                        $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( esc_attr( $key . ':' . $state_key ), $selected, true ) ? 'selected=selected' : '' );
                        $html .= '<option value="' . esc_attr( $key . ':' . $state_key ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $val . ' -> ' . $state_value ) . '</option>';
                        $filter_states[$key . ':' . $state_key] = $val . ' -> ' . $state_value;
                    }
                }
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->afrsm_pro_convert_array_to_json( $filter_states );
        }
        return $html;
    }
    
    /**
     * Get all zones list
     *
     * @param string $count
     * @param array  $selected
     *
     * @return string $html
     * @uses   afrsm_pro_get_default_langugae_with_sitpress()
     *
     * @since  1.0.0
     *
     */
    public function afrsm_pro_get_zones_list( $count = '', $selected = array(), $json = false )
    {
        global  $sitepress ;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $filter_zone = [];
        // WooCommerce shipping zones
        $delivery_zones = WC_Shipping_Zones::get_zones();
        if ( !empty($delivery_zones) ) {
            foreach ( $delivery_zones as $key => $the_zone ) {
                
                if ( !empty($sitepress) ) {
                    $zone_id = apply_filters(
                        'wpml_object_id',
                        $key,
                        'wc_afrsm_zone',
                        true,
                        $default_lang
                    );
                } else {
                    $zone_id = $key;
                }
                
                $filter_zone[$zone_id] = sanitize_text_field( $the_zone['zone_name'] );
            }
        }
        // Plugin's custom zones
        $get_all_zones = new WP_Query( array(
            'post_type'      => 'wc_afrsm_zone',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        ) );
        $get_zones_data = $get_all_zones->posts;
        if ( isset( $get_zones_data ) && !empty($get_zones_data) ) {
            foreach ( $get_zones_data as $get_all_zone ) {
                
                if ( !empty($sitepress) ) {
                    $new_zone_id = apply_filters(
                        'wpml_object_id',
                        $get_all_zone->ID,
                        'wc_afrsm_zone',
                        true,
                        $default_lang
                    );
                } else {
                    $new_zone_id = $get_all_zone->ID;
                }
                
                $filter_zone[$new_zone_id] = get_the_title( $new_zone_id );
            }
        }
        if ( $json ) {
            return $this->afrsm_pro_convert_array_to_json( $filter_zone );
        }
        $html = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_zone" multiple="multiple">';
        if ( isset( $filter_zone ) && !empty($filter_zone) ) {
            foreach ( $filter_zone as $get_all_zone_id => $get_all_zone ) {
                
                if ( !empty($sitepress) ) {
                    $new_zone_id = apply_filters(
                        'wpml_object_id',
                        $get_all_zone_id,
                        'wc_afrsm_zone',
                        true,
                        $default_lang
                    );
                } else {
                    $new_zone_id = $get_all_zone_id;
                }
                
                $selected = array_map( 'intval', $selected );
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_zone_id, $selected, false ) ? 'selected=selected' : '' );
                $html .= '<option value="' . esc_attr( $new_zone_id ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $get_all_zone ) . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }
    
    /**
     * Get product list when edit method.
     *
     * @param string $count
     * @param array  $selected
     *
     * @return string $html
     * @uses   afrsm_pro_get_default_langugae_with_sitpress()
     *
     * @since  1.0.0
     *
     */
    public function afrsm_pro_get_product_list( $count = '', $selected = array(), $json = false )
    {
        global  $sitepress ;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $get_all_products_count = 900;
        $get_all_products = new WP_Query( array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $get_all_products_count,
            'post__in'       => $selected,
        ) );
        $html = '<select id="product-filter-' . esc_attr( $count ) . '" rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_product product_fees_conditions_values_' . esc_attr( $count ) . '" multiple="multiple">';
        if ( isset( $get_all_products->posts ) && !empty($get_all_products->posts) ) {
            foreach ( $get_all_products->posts as $get_all_product ) {
                
                if ( !empty($sitepress) ) {
                    $new_product_id = apply_filters(
                        'wpml_object_id',
                        $get_all_product->ID,
                        'product',
                        true,
                        $default_lang
                    );
                } else {
                    $new_product_id = $get_all_product->ID;
                }
                
                $selected = array_map( 'intval', $selected );
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_product_id, $selected, true ) ? 'selected=selected' : '' );
                if ( '' !== $selectedVal ) {
                    $html .= '<option value="' . esc_attr( $new_product_id ) . '" ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $new_product_id ) . ' - ' . esc_html( get_the_title( $new_product_id ) ) . '</option>';
                }
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return [];
        }
        return $html;
    }
    
    /**
     * Get variable product list in Advance pricing rules
     *
     * @param string $count
     * @param array  $selected
     *
     * @return string $html
     * @uses   WC_Product::is_type()
     *
     * @since  3.4
     *
     * @uses   afrsm_pro_get_default_langugae_with_sitpress()
     * @uses   wc_get_product()
     */
    public function afrsm_pro_get_product_options( $count = '', $selected = array() )
    {
        global  $sitepress ;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $all_selected_product_ids = array();
        if ( !empty($selected) && is_array( $selected ) ) {
            foreach ( $selected as $product_id ) {
                $_product = wc_get_product( $product_id );
                
                if ( 'product_variation' === $_product->post_type ) {
                    $all_selected_product_ids[] = $_product->get_parent_id();
                    //parent_id;
                } else {
                    $all_selected_product_ids[] = $product_id;
                }
            
            }
        }
        $all_selected_product_count = 900;
        $get_all_products = new WP_Query( array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $all_selected_product_count,
            'post__in'       => $all_selected_product_ids,
        ) );
        $baselang_variation_product_ids = array();
        $defaultlang_simple_product_ids = array();
        $html = '';
        $get_all_products = $get_all_products->posts;
        if ( isset( $get_all_products ) && !empty($get_all_products) ) {
            foreach ( $get_all_products as $get_all_product ) {
                $_product = wc_get_product( $get_all_product->ID );
                $check_virtual = $this->afrsm_check_product_type_for_admin( $_product );
                if ( true === $check_virtual ) {
                    
                    if ( $_product->is_type( 'variable' ) ) {
                        $variations = $_product->get_available_variations();
                        foreach ( $variations as $value ) {
                            
                            if ( !empty($sitepress) ) {
                                $defaultlang_variation_product_id = apply_filters(
                                    'wpml_object_id',
                                    $value['variation_id'],
                                    'product',
                                    true,
                                    $default_lang
                                );
                            } else {
                                $defaultlang_variation_product_id = $value['variation_id'];
                            }
                            
                            $baselang_variation_product_ids[] = $defaultlang_variation_product_id;
                        }
                    } else {
                        
                        if ( !empty($sitepress) ) {
                            $defaultlang_simple_product_id = apply_filters(
                                'wpml_object_id',
                                $get_all_product->ID,
                                'product',
                                true,
                                $default_lang
                            );
                        } else {
                            $defaultlang_simple_product_id = $get_all_product->ID;
                        }
                        
                        $defaultlang_simple_product_ids[] = $defaultlang_simple_product_id;
                    }
                
                }
            }
        }
        $baselang_product_ids = array_merge( $baselang_variation_product_ids, $defaultlang_simple_product_ids );
        if ( isset( $baselang_product_ids ) && !empty($baselang_product_ids) ) {
            foreach ( $baselang_product_ids as $baselang_product_id ) {
                $selected = array_map( 'intval', $selected );
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $baselang_product_id, $selected, true ) ? 'selected=selected' : '' );
                if ( '' !== $selectedVal ) {
                    $html .= '<option value="' . esc_attr( $baselang_product_id ) . '" ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $baselang_product_id ) . ' - ' . esc_html( get_the_title( $baselang_product_id ) ) . '</option>';
                }
            }
        }
        return $html;
    }
    
    /**
     * Get category list in Advance pricing rules
     *
     * @param array $selected
     *
     * @return string $html
     * @since  3.4
     *
     * @uses   afrsm_pro_get_default_langugae_with_sitpress()
     *
     */
    public function afrsm_pro_get_category_options( $selected = array(), $json )
    {
        global  $sitepress ;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $filter_category_list = [];
        $taxonomy = 'product_cat';
        $post_status = 'publish';
        $orderby = 'name';
        $hierarchical = 1;
        $empty = 0;
        $args = array(
            'post_type'      => 'product',
            'post_status'    => $post_status,
            'taxonomy'       => $taxonomy,
            'orderby'        => $orderby,
            'hierarchical'   => $hierarchical,
            'hide_empty'     => $empty,
            'posts_per_page' => -1,
        );
        $get_all_categories = get_categories( $args );
        $html = '';
        if ( isset( $get_all_categories ) && !empty($get_all_categories) ) {
            foreach ( $get_all_categories as $get_all_category ) {
                
                if ( $get_all_category ) {
                    
                    if ( !empty($sitepress) ) {
                        $new_cat_id = apply_filters(
                            'wpml_object_id',
                            $get_all_category->term_id,
                            'product_cat',
                            true,
                            $default_lang
                        );
                    } else {
                        $new_cat_id = $get_all_category->term_id;
                    }
                    
                    $category = get_term_by( 'id', $new_cat_id, 'product_cat' );
                    $parent_category = get_term_by( 'id', $category->parent, 'product_cat' );
                    
                    if ( !empty($selected) ) {
                        $selected = array_map( 'intval', $selected );
                        $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_cat_id, $selected, true ) ? 'selected=selected' : '' );
                        
                        if ( $category->parent > 0 ) {
                            $html .= '<option value=' . $category->term_id . ' ' . $selectedVal . '>' . '' . $parent_category->name . '->' . $category->name . '</option>';
                        } else {
                            $html .= '<option value=' . $category->term_id . ' ' . $selectedVal . '>' . $category->name . '</option>';
                        }
                    
                    } else {
                        
                        if ( $category->parent > 0 ) {
                            $filter_category_list[$category->term_id] = $parent_category->name . '->' . $category->name;
                        } else {
                            $filter_category_list[$category->term_id] = $category->name;
                        }
                    
                    }
                
                }
            
            }
        }
        
        if ( true === $json ) {
            return wp_json_encode( $this->afrsm_pro_convert_array_to_json( $filter_category_list ) );
        } else {
            return $html;
        }
    
    }
    
    /**
     * Get category list in Shipping Method Rules
     *
     * @param string $count
     * @param array  $selected
     *
     * @return string $html
     * @uses   get_term_by()
     *
     * @since  1.0.0
     *
     * @uses   afrsm_pro_get_default_langugae_with_sitpress()
     * @uses   get_categories()
     */
    public function afrsm_pro_get_category_list( $count = '', $selected = array(), $json = false )
    {
        global  $sitepress ;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $filter_categories = [];
        $taxonomy = 'product_cat';
        $post_status = 'publish';
        $orderby = 'name';
        $hierarchical = 1;
        $empty = 0;
        $args = array(
            'post_type'      => 'product',
            'post_status'    => $post_status,
            'taxonomy'       => $taxonomy,
            'orderby'        => $orderby,
            'hierarchical'   => $hierarchical,
            'hide_empty'     => $empty,
            'posts_per_page' => -1,
        );
        $get_all_categories = get_categories( $args );
        $html = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_cat_product" multiple="multiple">';
        if ( isset( $get_all_categories ) && !empty($get_all_categories) ) {
            foreach ( $get_all_categories as $get_all_category ) {
                
                if ( $get_all_category ) {
                    
                    if ( !empty($sitepress) ) {
                        $new_cat_id = apply_filters(
                            'wpml_object_id',
                            $get_all_category->term_id,
                            'product_cat',
                            true,
                            $default_lang
                        );
                    } else {
                        $new_cat_id = $get_all_category->term_id;
                    }
                    
                    $selected = array_map( 'intval', $selected );
                    $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_cat_id, $selected, true ) ? 'selected=selected' : '' );
                    $category = get_term_by( 'id', $new_cat_id, 'product_cat' );
                    $parent_category = get_term_by( 'id', $category->parent, 'product_cat' );
                    
                    if ( $category->parent > 0 ) {
                        $html .= '<option value=' . esc_attr( $category->term_id ) . ' ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $parent_category->name ) . '->' . esc_html( $category->name ) . '</option>';
                        $filter_categories[$category->term_id] = '#' . $parent_category->name . '->' . $category->name;
                    } else {
                        $html .= '<option value=' . esc_attr( $category->term_id ) . ' ' . esc_attr( $selectedVal ) . '>' . esc_html( $category->name ) . '</option>';
                        $filter_categories[$category->term_id] = $category->name;
                    }
                
                }
            
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->afrsm_pro_convert_array_to_json( $filter_categories );
        }
        return $html;
    }
    
    /**
     * Get tag list in Shipping Method Rules
     *
     * @param string $count
     * @param array  $selected
     *
     * @return string $html
     * @since  1.0.0
     *
     * @uses   afrsm_pro_get_default_langugae_with_sitpress()
     * @uses   get_term_by()
     *
     */
    public function afrsm_pro_get_tag_list( $count = '', $selected = array(), $json = false )
    {
        global  $sitepress ;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $filter_tags = [];
        $taxonomy = 'product_tag';
        $orderby = 'name';
        $hierarchical = 1;
        $empty = 0;
        $args = array(
            'post_type'        => 'product',
            'post_status'      => 'publish',
            'taxonomy'         => $taxonomy,
            'orderby'          => $orderby,
            'hierarchical'     => $hierarchical,
            'hide_empty'       => $empty,
            'posts_per_page'   => -1,
            'suppress_filters' => false,
        );
        $get_all_tags = get_categories( $args );
        $html = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_tag_product" multiple="multiple">';
        if ( isset( $get_all_tags ) && !empty($get_all_tags) ) {
            foreach ( $get_all_tags as $get_all_tag ) {
                
                if ( $get_all_tag ) {
                    
                    if ( !empty($sitepress) ) {
                        $new_tag_id = apply_filters(
                            'wpml_object_id',
                            $get_all_tag->term_id,
                            'product_tag',
                            true,
                            $default_lang
                        );
                    } else {
                        $new_tag_id = $get_all_tag->term_id;
                    }
                    
                    $selected = array_map( 'intval', $selected );
                    $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_tag_id, $selected, true ) ? 'selected=selected' : '' );
                    $tag = get_term_by( 'id', $new_tag_id, 'product_tag' );
                    $html .= '<option value="' . esc_attr( $tag->term_id ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $tag->name ) . '</option>';
                    $filter_tags[$tag->term_id] = $tag->name;
                }
            
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->afrsm_pro_convert_array_to_json( $filter_tags );
        }
        return $html;
    }
    
    /**
     * Get user list in Shipping Method Rules
     *
     * @param string $count
     * @param array  $selected
     *
     * @return string $html
     * @since  1.0.0
     *
     */
    public function afrsm_pro_get_user_list( $count = '', $selected = array(), $json = false )
    {
        $filter_users = [];
        $get_all_users = get_users();
        $html = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="afrsm_select product_fees_conditions_values multiselect2 product_fees_conditions_values_user" multiple="multiple">';
        if ( isset( $get_all_users ) && !empty($get_all_users) ) {
            foreach ( $get_all_users as $get_all_user ) {
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $get_all_user->data->ID, $selected, true ) ? 'selected=selected' : '' );
                $html .= '<option value="' . esc_attr( $get_all_user->data->ID ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $get_all_user->data->user_login ) . '</option>';
                $filter_users[$get_all_user->data->ID] = $get_all_user->data->user_login;
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->afrsm_pro_convert_array_to_json( $filter_users );
        }
        return $html;
    }
    
    /**
     * Display product list based product specific option
     *
     * @return string $html
     * @uses   afrsm_pro_get_default_langugae_with_sitpress()
     * @uses   Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags()
     *
     * @since  1.0.0
     *
     */
    public function afrsm_pro_product_fees_conditions_values_product_ajax()
    {
        global  $sitepress ;
        $json = true;
        $filter_product_list = [];
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $request_value = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_STRING );
        $post_value = ( isset( $request_value ) ? sanitize_text_field( $request_value ) : '' );
        $baselang_product_ids = array();
        function afrsm_pro_posts_where( $where, $wp_query )
        {
            global  $wpdb ;
            $search_term = $wp_query->get( 'search_pro_title' );
            
            if ( !empty($search_term) ) {
                $search_term_like = $wpdb->esc_like( $search_term );
                $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
            }
            
            return $where;
        }
        
        $product_fees_conditions_count = 900;
        $product_args = array(
            'post_type'        => 'product',
            'posts_per_page'   => $product_fees_conditions_count,
            'search_pro_title' => $post_value,
            'post_status'      => 'publish',
            'orderby'          => 'title',
            'order'            => 'ASC',
        );
        add_filter(
            'posts_where',
            'afrsm_pro_posts_where',
            10,
            2
        );
        $get_wp_query = new WP_Query( $product_args );
        remove_filter(
            'posts_where',
            'afrsm_pro_posts_where',
            10,
            2
        );
        $get_all_products = $get_wp_query->posts;
        if ( isset( $get_all_products ) && !empty($get_all_products) ) {
            foreach ( $get_all_products as $get_all_product ) {
                $_product = wc_get_product( $get_all_product->ID );
                $check_virtual = $this->afrsm_check_product_type_for_admin( $_product );
                
                if ( true === $check_virtual ) {
                    
                    if ( !empty($sitepress) ) {
                        $defaultlang_product_id = apply_filters(
                            'wpml_object_id',
                            $get_all_product->ID,
                            'product',
                            true,
                            $default_lang
                        );
                    } else {
                        $defaultlang_product_id = $get_all_product->ID;
                    }
                    
                    $baselang_product_ids[] = $defaultlang_product_id;
                }
            
            }
        }
        $html = '';
        if ( isset( $baselang_product_ids ) && !empty($baselang_product_ids) ) {
            foreach ( $baselang_product_ids as $baselang_product_id ) {
                $html .= '<option value="' . esc_attr( $baselang_product_id ) . '">' . '#' . esc_html( $baselang_product_id ) . ' - ' . esc_html( get_the_title( $baselang_product_id ) ) . '</option>';
                $filter_product_list[] = array( $baselang_product_id, get_the_title( $baselang_product_id ) );
            }
        }
        
        if ( $json ) {
            echo  wp_json_encode( $filter_product_list ) ;
            wp_die();
        }
        
        echo  wp_kses( $html, Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro::afrsm_pro_allowed_html_tags() ) ;
        wp_die();
    }
    
    /**
     * Delete multiple shipping method
     *
     * @return string $result
     * @uses   wp_delete_post()
     *
     * @since  1.0.0
     *
     */
    public function afrsm_pro_wc_multiple_delete_shipping_method()
    {
        check_ajax_referer( 'dsm_nonce', 'nonce' );
        $result = 0;
        $get_allVals = filter_input(
            INPUT_GET,
            'allVals',
            FILTER_SANITIZE_NUMBER_INT,
            FILTER_REQUIRE_ARRAY
        );
        $allVals = ( !empty($get_allVals) ? array_map( 'sanitize_text_field', wp_unslash( $get_allVals ) ) : array() );
        if ( !empty($allVals) ) {
            foreach ( $allVals as $val ) {
                wp_delete_post( $val );
                $result = 1;
            }
        }
        echo  (int) $result ;
        wp_die();
    }
    
    /**
     * Count total shipping method
     *
     * @return int $count_method
     * @since    3.5
     *
     */
    public static function afrsm_pro_sm_count_method()
    {
        $shipping_method_args = array(
            'post_type'      => self::afrsm_shipping_post_type,
            'post_status'    => array( 'publish', 'draft' ),
            'posts_per_page' => -1,
            'orderby'        => 'ID',
            'order'          => 'DESC',
        );
        $sm_post_query = new WP_Query( $shipping_method_args );
        $shipping_method_list = $sm_post_query->posts;
        return count( $shipping_method_list );
    }
    
    /**
     * Save shipping method
     *
     * @param array $post
     *
     * @return bool false if post is empty otherwise it will redirect to shipping method list
     * @since  1.0.0
     *
     * @uses   update_post_meta()
     *
     */
    function afrsm_pro_fees_conditions_save( $post )
    {
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        if ( empty($post) ) {
            return false;
        }
        $action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
        $post_type = filter_input( INPUT_POST, 'post_type', FILTER_SANITIZE_STRING );
        $afrsm_pro_conditions_save = filter_input( INPUT_POST, 'afrsm_pro_conditions_save', FILTER_SANITIZE_STRING );
        
        if ( isset( $post_type ) && self::afrsm_shipping_post_type === sanitize_text_field( $post['post_type'] ) && wp_verify_nonce( sanitize_text_field( $afrsm_pro_conditions_save ), 'afrsm_pro_save_action' ) ) {
            $method_id = filter_input( INPUT_POST, 'fee_post_id', FILTER_SANITIZE_NUMBER_INT );
            $fees = filter_input(
                INPUT_POST,
                'fees',
                FILTER_SANITIZE_STRING,
                FILTER_REQUIRE_ARRAY
            );
            $sm_status = filter_input( INPUT_POST, 'sm_status', FILTER_SANITIZE_STRING );
            $fee_settings_product_fee_title = filter_input( INPUT_POST, 'fee_settings_product_fee_title', FILTER_SANITIZE_STRING );
            $get_condition_key = filter_input(
                INPUT_POST,
                'condition_key',
                FILTER_SANITIZE_STRING,
                FILTER_REQUIRE_ARRAY
            );
            $get_sm_product_cost = filter_input( INPUT_POST, 'sm_product_cost', FILTER_SANITIZE_STRING );
            $get_sm_free_shipping_based_on = filter_input( INPUT_POST, 'sm_free_shipping_based_on', FILTER_SANITIZE_STRING );
            $get_is_allow_free_shipping = filter_input( INPUT_POST, 'is_allow_free_shipping', FILTER_SANITIZE_STRING );
            $get_sm_free_shipping_cost = filter_input( INPUT_POST, 'sm_free_shipping_cost', FILTER_SANITIZE_STRING );
            $get_sm_free_shipping_coupan_cost = filter_input( INPUT_POST, 'sm_free_shipping_coupan_cost', FILTER_SANITIZE_STRING );
            $get_sm_free_shipping_label = filter_input( INPUT_POST, 'sm_free_shipping_label', FILTER_SANITIZE_STRING );
            $get_sm_tooltip_type = filter_input( INPUT_POST, 'sm_tooltip_type', FILTER_SANITIZE_STRING );
            $get_sm_tooltip_desc = filter_input( INPUT_POST, 'sm_tooltip_desc', FILTER_SANITIZE_STRING );
            $get_sm_select_log_in_user = filter_input( INPUT_POST, 'sm_select_log_in_user', FILTER_SANITIZE_STRING );
            $get_sm_select_selected_shipping = filter_input( INPUT_POST, 'sm_select_selected_shipping', FILTER_SANITIZE_STRING );
            $get_sm_select_taxable = filter_input( INPUT_POST, 'sm_select_taxable', FILTER_SANITIZE_STRING );
            $get_sm_select_shipping_provider = filter_input( INPUT_POST, 'sm_select_shipping_provider', FILTER_SANITIZE_STRING );
            $get_sm_extra_cost = filter_input(
                INPUT_POST,
                'sm_extra_cost',
                FILTER_SANITIZE_STRING,
                FILTER_REQUIRE_ARRAY
            );
            $get_sm_extra_cost_calculation_type = filter_input( INPUT_POST, 'sm_extra_cost_calculation_type', FILTER_SANITIZE_STRING );
            $sm_product_cost = ( isset( $get_sm_product_cost ) ? sanitize_text_field( $get_sm_product_cost ) : '' );
            $sm_free_shipping_based_on = ( isset( $get_sm_free_shipping_based_on ) ? sanitize_text_field( $get_sm_free_shipping_based_on ) : '' );
            $is_allow_free_shipping = ( isset( $get_is_allow_free_shipping ) ? sanitize_text_field( $get_is_allow_free_shipping ) : '' );
            $sm_free_shipping_cost = ( isset( $get_sm_free_shipping_cost ) ? sanitize_text_field( $get_sm_free_shipping_cost ) : '' );
            $sm_free_shipping_coupan_cost = ( isset( $get_sm_free_shipping_coupan_cost ) ? sanitize_text_field( $get_sm_free_shipping_coupan_cost ) : '' );
            $sm_tooltip_type = ( isset( $get_sm_tooltip_type ) ? sanitize_text_field( $get_sm_tooltip_type ) : '' );
            $sm_tooltip_desc = ( isset( $get_sm_tooltip_desc ) ? sanitize_textarea_field( substr( $get_sm_tooltip_desc, 0, 100 ) ) : '' );
            $sm_select_log_in_user = ( isset( $get_sm_select_log_in_user ) ? sanitize_text_field( $get_sm_select_log_in_user ) : '' );
            $sm_select_selected_shipping = ( isset( $get_sm_select_selected_shipping ) ? sanitize_text_field( $get_sm_select_selected_shipping ) : '' );
            $sm_select_taxable = ( isset( $get_sm_select_taxable ) ? sanitize_text_field( $get_sm_select_taxable ) : '' );
            $sm_select_shipping_provider = ( isset( $get_sm_select_shipping_provider ) ? sanitize_text_field( $get_sm_select_shipping_provider ) : '' );
            $sm_extra_cost = ( isset( $get_sm_extra_cost ) ? array_map( 'sanitize_text_field', $get_sm_extra_cost ) : array() );
            $sm_extra_cost_calculation_type = ( isset( $get_sm_extra_cost_calculation_type ) ? sanitize_text_field( $get_sm_extra_cost_calculation_type ) : '' );
            $get_cost_on_total_cart_weight_status = filter_input( INPUT_POST, 'cost_on_total_cart_weight_status', FILTER_SANITIZE_STRING );
            $get_cost_on_total_cart_subtotal_status = filter_input( INPUT_POST, 'cost_on_total_cart_subtotal_status', FILTER_SANITIZE_STRING );
            $cost_on_total_cart_weight_status = ( isset( $get_cost_on_total_cart_weight_status ) ? sanitize_text_field( $get_cost_on_total_cart_weight_status ) : 'off' );
            $cost_on_total_cart_subtotal_status = ( isset( $get_cost_on_total_cart_subtotal_status ) ? sanitize_text_field( $get_cost_on_total_cart_subtotal_status ) : 'off' );
            $get_ap_rule_status = filter_input( INPUT_POST, 'ap_rule_status', FILTER_SANITIZE_STRING );
            $ap_rule_status = ( isset( $get_ap_rule_status ) ? sanitize_text_field( $get_ap_rule_status ) : "off" );
            $sm_free_shipping_label = 'Free Shipping';
            $shipping_method_count = self::afrsm_pro_sm_count_method();
            settype( $method_id, 'integer' );
            
            if ( isset( $sm_status ) ) {
                $post_status = 'publish';
            } else {
                $post_status = 'draft';
            }
            
            
            if ( '' !== $method_id && 0 !== $method_id ) {
                $fee_post = array(
                    'ID'          => $method_id,
                    'post_title'  => sanitize_text_field( $fee_settings_product_fee_title ),
                    'post_status' => $post_status,
                    'post_type'   => self::afrsm_shipping_post_type,
                );
                $method_id = wp_update_post( $fee_post );
            } else {
                $fee_post = array(
                    'post_title'  => sanitize_text_field( $fee_settings_product_fee_title ),
                    'post_status' => $post_status,
                    'menu_order'  => $shipping_method_count + 1,
                    'post_type'   => self::afrsm_shipping_post_type,
                );
                $method_id = wp_insert_post( $fee_post );
            }
            
            
            if ( '' !== $method_id && 0 !== $method_id ) {
                
                if ( $method_id > 0 ) {
                    $feesArray = array();
                    $conditions_values_array = array();
                    $condition_key = ( isset( $get_condition_key ) ? $get_condition_key : array() );
                    $fees_conditions = $fees['product_fees_conditions_condition'];
                    $conditions_is = $fees['product_fees_conditions_is'];
                    $conditions_values = ( isset( $fees['product_fees_conditions_values'] ) && !empty($fees['product_fees_conditions_values']) ? $fees['product_fees_conditions_values'] : array() );
                    $size = count( $fees_conditions );
                    foreach ( array_keys( $condition_key ) as $key ) {
                        if ( !array_key_exists( $key, $conditions_values ) ) {
                            $conditions_values[$key] = array();
                        }
                    }
                    uksort( $conditions_values, 'strnatcmp' );
                    foreach ( $conditions_values as $v ) {
                        $conditions_values_array[] = $v;
                    }
                    for ( $i = 0 ;  $i < $size ;  $i++ ) {
                        $feesArray[] = array(
                            'product_fees_conditions_condition' => $fees_conditions[$i],
                            'product_fees_conditions_is'        => $conditions_is[$i],
                            'product_fees_conditions_values'    => $conditions_values_array[$i],
                        );
                    }
                    update_post_meta( $method_id, 'sm_product_cost', $sm_product_cost );
                    update_post_meta( $method_id, 'is_allow_free_shipping', $is_allow_free_shipping );
                    update_post_meta( $method_id, 'sm_free_shipping_based_on', $sm_free_shipping_based_on );
                    update_post_meta( $method_id, 'sm_free_shipping_cost', $sm_free_shipping_cost );
                    update_post_meta( $method_id, 'sm_free_shipping_coupan_cost', $sm_free_shipping_coupan_cost );
                    update_post_meta( $method_id, 'sm_free_shipping_label', $sm_free_shipping_label );
                    update_post_meta( $method_id, 'sm_tooltip_type', $sm_tooltip_type );
                    update_post_meta( $method_id, 'sm_tooltip_desc', $sm_tooltip_desc );
                    update_post_meta( $method_id, 'sm_select_log_in_user', $sm_select_log_in_user );
                    update_post_meta( $method_id, 'sm_select_selected_shipping', $sm_select_selected_shipping );
                    update_post_meta( $method_id, 'sm_select_taxable', $sm_select_taxable );
                    update_post_meta( $method_id, 'sm_select_shipping_provider', $sm_select_shipping_provider );
                    update_post_meta( $method_id, 'sm_metabox', $feesArray );
                    update_post_meta( $method_id, 'sm_extra_cost', $sm_extra_cost );
                    update_post_meta( $method_id, 'sm_extra_cost_calculation_type', $sm_extra_cost_calculation_type );
                    $ap_total_cart_weight_arr = array();
                    $ap_total_cart_subtotal_arr = array();
                    //Total cart weight
                    
                    if ( isset( $fees['ap_total_cart_weight_fees_conditions_condition'] ) ) {
                        $fees_total_cart_weight = $fees['ap_total_cart_weight_fees_conditions_condition'];
                        $fees_ap_total_cart_weight_min_weight = $fees['ap_fees_ap_total_cart_weight_min_weight'];
                        $fees_ap_total_cart_weight_max_weight = $fees['ap_fees_ap_total_cart_weight_max_weight'];
                        $fees_ap_price_total_cart_weight = $fees['ap_fees_ap_price_total_cart_weight'];
                        $total_cart_weight_arr = array();
                        foreach ( $fees_total_cart_weight as $fees_total_cart_weight_val ) {
                            $total_cart_weight_arr[] = $fees_total_cart_weight_val;
                        }
                        $size_total_cart_weight_cond = count( $fees_total_cart_weight );
                        if ( !empty($size_total_cart_weight_cond) && $size_total_cart_weight_cond > 0 ) {
                            for ( $total_cart_weight_cnt = 0 ;  $total_cart_weight_cnt < $size_total_cart_weight_cond ;  $total_cart_weight_cnt++ ) {
                                if ( !empty($total_cart_weight_arr) && '' !== $total_cart_weight_arr ) {
                                    foreach ( $total_cart_weight_arr as $total_cart_weight_key => $total_cart_weight_val ) {
                                        if ( $total_cart_weight_key === $total_cart_weight_cnt ) {
                                            $ap_total_cart_weight_arr[] = array(
                                                'ap_fees_total_cart_weight'               => $total_cart_weight_val,
                                                'ap_fees_ap_total_cart_weight_min_weight' => $fees_ap_total_cart_weight_min_weight[$total_cart_weight_cnt],
                                                'ap_fees_ap_total_cart_weight_max_weight' => $fees_ap_total_cart_weight_max_weight[$total_cart_weight_cnt],
                                                'ap_fees_ap_price_total_cart_weight'      => $fees_ap_price_total_cart_weight[$total_cart_weight_cnt],
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    //Cart subtotal
                    
                    if ( isset( $fees['ap_total_cart_subtotal_fees_conditions_condition'] ) ) {
                        $fees_total_cart_subtotal = $fees['ap_total_cart_subtotal_fees_conditions_condition'];
                        $fees_ap_total_cart_subtotal_min_subtotal = $fees['ap_fees_ap_total_cart_subtotal_min_subtotal'];
                        $fees_ap_total_cart_subtotal_max_subtotal = $fees['ap_fees_ap_total_cart_subtotal_max_subtotal'];
                        $fees_ap_price_total_cart_subtotal = $fees['ap_fees_ap_price_total_cart_subtotal'];
                        $total_cart_subtotal_arr = array();
                        foreach ( $fees_total_cart_subtotal as $total_cart_subtotal_key => $total_cart_subtotal_val ) {
                            $total_cart_subtotal_arr[] = $total_cart_subtotal_val;
                        }
                        $size_total_cart_subtotal_cond = count( $fees_total_cart_subtotal );
                        if ( !empty($size_total_cart_subtotal_cond) && $size_total_cart_subtotal_cond > 0 ) {
                            for ( $total_cart_subtotal_cnt = 0 ;  $total_cart_subtotal_cnt < $size_total_cart_subtotal_cond ;  $total_cart_subtotal_cnt++ ) {
                                if ( !empty($total_cart_subtotal_arr) && $total_cart_subtotal_arr !== '' ) {
                                    foreach ( $total_cart_subtotal_arr as $total_cart_subtotal_key => $total_cart_subtotal_val ) {
                                        if ( $total_cart_subtotal_key === $total_cart_subtotal_cnt ) {
                                            $ap_total_cart_subtotal_arr[] = array(
                                                'ap_fees_total_cart_subtotal'                 => $total_cart_subtotal_val,
                                                'ap_fees_ap_total_cart_subtotal_min_subtotal' => $fees_ap_total_cart_subtotal_min_subtotal[$total_cart_subtotal_cnt],
                                                'ap_fees_ap_total_cart_subtotal_max_subtotal' => $fees_ap_total_cart_subtotal_max_subtotal[$total_cart_subtotal_cnt],
                                                'ap_fees_ap_price_total_cart_subtotal'        => $fees_ap_price_total_cart_subtotal[$total_cart_subtotal_cnt],
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    update_post_meta( $method_id, 'cost_on_total_cart_weight_status', $cost_on_total_cart_weight_status );
                    update_post_meta( $method_id, 'cost_on_total_cart_subtotal_status', $cost_on_total_cart_subtotal_status );
                    update_post_meta( $method_id, 'sm_metabox_ap_total_cart_weight', $ap_total_cart_weight_arr );
                    update_post_meta( $method_id, 'sm_metabox_ap_total_cart_subtotal', $ap_total_cart_subtotal_arr );
                    update_post_meta( $method_id, 'ap_rule_status', $ap_rule_status );
                    
                    if ( 'edit' !== $action ) {
                        $getSortOrder = get_option( 'sm_sortable_order_' . $default_lang );
                        
                        if ( !empty($getSortOrder) ) {
                            foreach ( $getSortOrder as $getSortOrder_id ) {
                                settype( $getSortOrder_id, 'integer' );
                            }
                            array_unshift( $getSortOrder, $method_id );
                        }
                        
                        update_option( 'sm_sortable_order_' . $default_lang, $getSortOrder );
                    }
                
                }
            
            } else {
                echo  '<div class="updated error"><p>' . esc_html__( 'Error saving shipping method.', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</p></div>' ;
                return false;
            }
            
            $afrsmnonce = wp_create_nonce( 'afrsmnonce' );
            wp_safe_redirect( add_query_arg( array(
                'page'     => 'afrsm-pro-list',
                '_wpnonce' => esc_attr( $afrsmnonce ),
            ), admin_url( 'admin.php' ) ) );
            exit;
        }
    
    }
    
    /**
     * Review message in footer
     *
     * @return string
     * @since  1.0.0
     *
     */
    public function afrsm_pro_admin_footer_review()
    {
        $url = '';
        $url = esc_url( 'https://wordpress.org/plugins/woo-extra-flat-rate/#reviews' );
        echo  sprintf( wp_kses( __( 'If you like <strong>Advanced Flat Rate Shipping For WooCommerce</strong> plugin, please leave us &#9733;&#9733;&#9733;&#9733;&#9733; ratings on <a href="%1$s" target="_blank">DotStore</a>.', 'advanced-flat-rate-shipping-for-woocommerce' ), array(
            'strong' => array(),
            'a'      => array(
            'href' => array(),
        ),
        ) ), esc_url( $url ) ) ;
    }
    
    /**
     * Clone shipping method
     *
     * @return string true if current_shipping_id is empty then it will give message.
     * @uses   get_post()
     * @uses   wp_get_current_user()
     * @uses   wp_insert_post()
     *
     * @since  3.4
     *
     */
    public function afrsm_pro_clone_shipping_method()
    {
        /* Check for post request */
        $get_current_shipping_id = filter_input( INPUT_GET, 'current_shipping_id', FILTER_SANITIZE_NUMBER_INT );
        $get_post_id = ( isset( $get_current_shipping_id ) ? absint( $get_current_shipping_id ) : '' );
        
        if ( empty($get_post_id) ) {
            echo  sprintf( wp_kses( __( '<strong>No post to duplicate has been supplied!</strong>', 'advanced-flat-rate-shipping-for-woocommerce' ), array(
                'strong' => array(),
            ) ) ) ;
            wp_die();
        }
        
        /* End of if */
        /* Get the original post id */
        
        if ( !empty($get_post_id) || '' !== $get_post_id ) {
            /* Get all the original post data */
            $post = get_post( $get_post_id );
            /* Get current user and make it new post user (duplicate post) */
            $current_user = wp_get_current_user();
            $new_post_author = $current_user->ID;
            /* If post data exists, duplicate the data into new duplicate post */
            
            if ( isset( $post ) && null !== $post ) {
                /* New post data array */
                $args = array(
                    'comment_status' => $post->comment_status,
                    'ping_status'    => $post->ping_status,
                    'post_author'    => $new_post_author,
                    'post_content'   => $post->post_content,
                    'post_excerpt'   => $post->post_excerpt,
                    'post_name'      => $post->post_name,
                    'post_parent'    => $post->post_parent,
                    'post_password'  => $post->post_password,
                    'post_status'    => 'draft',
                    'post_title'     => $post->post_title . '-duplicate',
                    'post_type'      => self::afrsm_shipping_post_type,
                    'to_ping'        => $post->to_ping,
                    'menu_order'     => $post->menu_order,
                );
                /* Duplicate the post by wp_insert_post() function */
                $new_post_id = wp_insert_post( $args );
                /* Duplicate all post meta-data */
                $post_meta_data = get_post_meta( $get_post_id );
                if ( 0 !== count( $post_meta_data ) ) {
                    foreach ( $post_meta_data as $meta_key => $meta_data ) {
                        if ( '_wp_old_slug' === $meta_key ) {
                            continue;
                        }
                        $meta_value = maybe_unserialize( $meta_data[0] );
                        update_post_meta( $new_post_id, $meta_key, $meta_value );
                    }
                }
            }
            
            $afrsmnonce = wp_create_nonce( 'afrsmnonce' );
            $redirect_url = add_query_arg( array(
                'page'     => 'afrsm-pro-edit-shipping',
                'id'       => $new_post_id,
                'action'   => 'edit',
                '_wpnonce' => esc_attr( $afrsmnonce ),
            ), admin_url( 'admin.php' ) );
            echo  wp_json_encode( array( true, $redirect_url ) ) ;
        }
        
        wp_die();
    }
    
    /**
     * Change shipping status from list of shipping method
     *
     * @since  3.4
     *
     * @uses   update_post_meta()
     *
     * if current_shipping_id is empty then it will give message.
     */
    public function afrsm_pro_change_status_from_list_section()
    {
        global  $sitepress ;
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        $active_items = 0;
        /* Check for post request */
        $get_current_shipping_id = filter_input( INPUT_GET, 'current_shipping_id', FILTER_SANITIZE_NUMBER_INT );
        
        if ( !empty($sitepress) ) {
            $get_current_shipping_id = apply_filters(
                'wpml_object_id',
                $get_current_shipping_id,
                'product',
                true,
                $default_lang
            );
        } else {
            $get_current_shipping_id = $get_current_shipping_id;
        }
        
        $get_current_value = filter_input( INPUT_GET, 'current_value', FILTER_SANITIZE_STRING );
        $get_post_id = ( isset( $get_current_shipping_id ) ? absint( $get_current_shipping_id ) : '' );
        
        if ( empty($get_post_id) ) {
            echo  '<strong>' . esc_html__( 'Something went wrong', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</strong>' ;
            wp_die();
        }
        
        $current_value = ( isset( $get_current_value ) ? sanitize_text_field( $get_current_value ) : '' );
        $get_search = filter_input( INPUT_GET, 's', FILTER_SANITIZE_STRING );
        
        if ( 'true' === $current_value ) {
            $post_args = array(
                'ID'          => $get_post_id,
                'post_status' => 'publish',
                'post_type'   => self::afrsm_shipping_post_type,
            );
            $post_update = wp_update_post( $post_args );
            update_post_meta( $get_post_id, 'sm_status', 'on' );
        } else {
            $post_args = array(
                'ID'          => $get_post_id,
                'post_status' => 'draft',
                'post_type'   => self::afrsm_shipping_post_type,
            );
            $post_update = wp_update_post( $post_args );
            update_post_meta( $get_post_id, 'sm_status', 'off' );
        }
        
        
        if ( !class_exists( 'WC_Advanced_Flat_Rate_Shipping_Table' ) ) {
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/list-tables/class-wc-flat-rate-rule-table.php';
            $WC_Advanced_Flat_Rate_Shipping_Table = new WC_Advanced_Flat_Rate_Shipping_Table();
            if ( isset( $get_search ) && !empty($get_search) ) {
                $args['s'] = trim( wp_unslash( $get_search ) );
            }
            $active_items = $WC_Advanced_Flat_Rate_Shipping_Table->afrsm_active_find( $args );
        }
        
        
        if ( !empty($post_update) ) {
            $message = esc_html__( 'Shipping status changed successfully.', 'advanced-flat-rate-shipping-for-woocommerce' );
        } else {
            $message = esc_html__( 'Something went wrong', 'advanced-flat-rate-shipping-for-woocommerce' );
        }
        
        wp_send_json( array(
            'active_count' => $active_items,
            'message'      => $message,
        ) );
    }
    
    /**
     * Change Advance pricing rules status
     *
     * @return string true if current_shipping_id is empty then it will give message.
     *
     * @uses   update_post_meta()
     *
     * @since  3.4
     *
     */
    public function afrsm_pro_change_status_of_advance_pricing_rules()
    {
        $get_current_shipping_id = filter_input( INPUT_GET, 'current_shipping_id', FILTER_SANITIZE_NUMBER_INT );
        $get_current_value = filter_input( INPUT_GET, 'current_value', FILTER_SANITIZE_STRING );
        $get_post_id = ( isset( $get_current_shipping_id ) ? absint( $get_current_shipping_id ) : '' );
        
        if ( empty($get_post_id) ) {
            echo  '<strong>' . esc_html__( 'Something went wrong', 'advanced-flat-rate-shipping-for-woocommerce' ) . '</strong>' ;
            wp_die();
        }
        
        $current_value = ( isset( $get_current_value ) ? sanitize_text_field( $get_current_value ) : '' );
        
        if ( 'true' === $current_value ) {
            update_post_meta( $get_post_id, 'ap_rule_status', 'off' );
            echo  esc_html( 'true' ) ;
        }
        
        wp_die();
    }
    
    /**
     * Get default site language
     *
     * @return string $default_lang
     *
     * @since  3.4
     *
     */
    public function afrsm_pro_get_default_langugae_with_sitpress()
    {
        global  $sitepress ;
        
        if ( !empty($sitepress) ) {
            $default_lang = $sitepress->get_current_language();
        } else {
            $default_lang = $this->afrsm_pro_get_current_site_language();
        }
        
        return $default_lang;
    }
    
    /**
     * Get AFRSM shipping method
     *
     * @param string $args
     *
     * @return string $default_lang
     *
     * @since  3.4
     *
     */
    public static function afrsm_pro_get_shipping_method( $args )
    {
        $sm_args = array(
            'post_type'        => self::afrsm_shipping_post_type,
            'posts_per_page'   => -1,
            'orderby'          => 'menu_order',
            'order'            => 'ASC',
            'suppress_filters' => false,
        );
        if ( 'not_list' === $args ) {
            $sm_args['post_status'] = 'publish';
        }
        $get_all_shipping = new WP_Query( $sm_args );
        $get_all_shipping = $get_all_shipping->get_posts();
        return $get_all_shipping;
    }
    
    /**
     * Convert array to json
     *
     * @param array $arr
     *
     * @return array $filter_data
     * @since 1.0.0
     *
     */
    public function afrsm_pro_convert_array_to_json( $arr )
    {
        $filter_data = [];
        foreach ( $arr as $key => $value ) {
            $option = [];
            $option['name'] = $value;
            $option['attributes']['value'] = $key;
            $filter_data[] = $option;
        }
        return $filter_data;
    }
    
    /**
     * Get product id and variation id from cart
     *
     * @param string $sitepress
     * @param string $default_lang
     *
     * @return array $cart_main_product_ids_array
     * @uses  afrsm_pro_get_cart();
     *
     * @since 1.0.0
     *
     */
    public function afrsm_pro_get_main_prd_id( $cart_array, $sitepress, $default_lang )
    {
        $cart_array = ( !empty($cart_array) ? $cart_array : $this->afrsm_pro_get_cart() );
        $cart_main_product_ids_array = array();
        foreach ( $cart_array as $woo_cart_item ) {
            $_product = wc_get_product( $woo_cart_item['product_id'] );
            $check_virtual = $this->afrsm_check_product_type_for_front( $_product, $woo_cart_item );
            if ( true === $check_virtual ) {
                
                if ( !empty($sitepress) ) {
                    $cart_main_product_ids_array[] = apply_filters(
                        'wpml_object_id',
                        $woo_cart_item['product_id'],
                        'product',
                        true,
                        $default_lang
                    );
                } else {
                    $cart_main_product_ids_array[] = $woo_cart_item['product_id'];
                }
            
            }
        }
        return $cart_main_product_ids_array;
    }
    
    /**
     * Get product id and variation id from cart
     *
     * @param string $sitepress
     * @param string $default_lang
     *
     * @return array $cart_product_ids_array
     * @uses  afrsm_pro_get_cart();
     *
     * @since 1.0.0
     *
     */
    public function afrsm_pro_get_prd_var_id( $cart_array, $sitepress, $default_lang )
    {
        $cart_array = ( !empty($cart_array) ? $cart_array : $this->afrsm_pro_get_cart() );
        $cart_product_ids_array = array();
        foreach ( $cart_array as $woo_cart_item ) {
            $_product = wc_get_product( $woo_cart_item['product_id'] );
            $_product_simp_var_id = 'product_id';
            /**
             * Updated and added code for fetch product from addon.
             *
             * @since  3.8
             *
             * @author jb
             */
            $check_virtual = $this->afrsm_check_product_type_for_front( $_product, $woo_cart_item );
            if ( true === $check_virtual ) {
                
                if ( $_product->is_type( 'variable' ) ) {
                    
                    if ( !empty($sitepress) ) {
                        $cart_product_ids_array[] = apply_filters(
                            'wpml_object_id',
                            $woo_cart_item[$_product_simp_var_id],
                            'product',
                            true,
                            $default_lang
                        );
                    } else {
                        $cart_product_ids_array[] = $woo_cart_item[$_product_simp_var_id];
                    }
                
                } else {
                    
                    if ( !empty($sitepress) ) {
                        $cart_product_ids_array[] = apply_filters(
                            'wpml_object_id',
                            $woo_cart_item['product_id'],
                            'product',
                            true,
                            $default_lang
                        );
                    } else {
                        $cart_product_ids_array[] = $woo_cart_item['product_id'];
                    }
                
                }
            
            }
        }
        return $cart_product_ids_array;
    }
    
    /**
     * Get product id and variation id from cart
     *
     * @return array $cart_array
     * @since 1.0.0
     *
     */
    public function afrsm_pro_get_cart()
    {
        $cart_array = WC()->cart->get_cart();
        return $cart_array;
    }
    
    /**
     * Get current site langugae
     *
     * @return string $default_lang
     * @since 1.0.0
     *
     */
    public function afrsm_pro_get_current_site_language()
    {
        $get_site_language = get_bloginfo( 'language' );
        
        if ( false !== strpos( $get_site_language, '-' ) ) {
            $get_site_language_explode = explode( '-', $get_site_language );
            $default_lang = $get_site_language_explode[0];
        } else {
            $default_lang = $get_site_language;
        }
        
        return $default_lang;
    }
    
    /**
     * Remove section from shipping settings because we have added new menu in woocommece section
     *
     * @param array $sections
     *
     * @return array $sections
     *
     * @since    1.0.0
     */
    public function afrsm_pro_remove_section( $sections )
    {
        unset( $sections['advanced_flat_rate_shipping'], $sections['forceall'] );
        return $sections;
    }
    
    /**
     * Get cart subtotal
     *
     * @return float $subtotal
     *
     * @since    3.6
     */
    public function afrsm_pro_get_cart_subtotal()
    {
        $get_customer = WC()->cart->get_customer();
        $get_customer_vat_exempt = WC()->customer->get_is_vat_exempt();
        $tax_display_cart = WC()->cart->get_tax_price_display_mode();
        //tax_display_cart; //WC()->cart->tax_display_cart;
        $wc_prices_include_tax = wc_prices_include_tax();
        $tax_enable = wc_tax_enabled();
        $cart_subtotal = 0;
        
        if ( true === $tax_enable ) {
            
            if ( true === $wc_prices_include_tax ) {
                
                if ( 'incl' === $tax_display_cart && !($get_customer && $get_customer_vat_exempt) ) {
                    $cart_subtotal += WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax();
                } else {
                    $cart_subtotal += WC()->cart->get_subtotal();
                }
            
            } else {
                
                if ( 'incl' === $tax_display_cart && !($get_customer && $get_customer_vat_exempt) ) {
                    $cart_subtotal += WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax();
                } else {
                    $cart_subtotal += WC()->cart->get_subtotal();
                }
            
            }
        
        } else {
            $cart_subtotal += WC()->cart->get_subtotal();
        }
        
        return $cart_subtotal;
    }
    
    /**
     * Fetch Zone
     *
     * @since    3.6
     */
    public function afrsm_pro_fetch_shipping_zone()
    {
        global  $wpdb ;
        $sz_table_name = "{$wpdb->prefix}wcextraflatrate_shipping_zones";
        $szl_table_name = "{$wpdb->prefix}wcextraflatrate_shipping_zone_locations";
        if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $sz_table_name ) ) === $sz_table_name ) {
            $get_zone_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM %s as tbl1", $sz_table_name ) );
        }
        $i = 0;
        $success_array = array();
        $get_zone_array = array();
        if ( !empty($get_zone_data) ) {
            foreach ( $get_zone_data as $value ) {
                $i++;
                $zone_id = $value->zone_id;
                $zone_enabled = $value->zone_enabled;
                
                if ( '1' === $zone_enabled ) {
                    $post_status = 'publish';
                } else {
                    $post_status = 'draft';
                }
                
                $get_zone_array[$zone_id]['ID'] = $value->zone_id;
                $get_zone_array[$zone_id]['zone_name'] = $value->zone_name;
                $get_zone_array[$zone_id]['zone_enabled'] = $post_status;
                $get_zone_array[$zone_id]['zone_type'] = $value->zone_type;
                $get_zone_array[$zone_id]['zone_order'] = $value->zone_order;
                $locations = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM %s WHERE zone_id = %s;", $szl_table_name, $zone_id ) );
                
                if ( !empty($locations) ) {
                    $locations_list = array();
                    foreach ( $locations as $locations_value ) {
                        $location_type = $locations_value->location_type;
                        $get_zone_array[$i]['location_type'] = $location_type;
                        if ( 'country' === $location_type || 'state' === $location_type ) {
                            $postcode_location_type = $locations_value->location_code;
                        }
                        if ( 'postcode' === $location_type ) {
                            $locations_list[$postcode_location_type][] = $locations_value->location_code;
                        }
                        
                        if ( 'postcode' === $location_type ) {
                            $get_zone_array[$zone_id]['location_code'] = $locations_list;
                        } else {
                            $get_zone_array[$zone_id]['location_code'][] = $postcode_location_type;
                        }
                    
                    }
                }
            
            }
        }
        
        if ( !empty($get_zone_array) ) {
            foreach ( $get_zone_array as $get_zone_val ) {
                $zone_post = array(
                    'post_title'  => $get_zone_val['zone_name'],
                    'post_status' => $get_zone_val['zone_enabled'],
                    'menu_order'  => $get_zone_val['zone_order'] + 1,
                    'post_type'   => 'wc_afrsm_zone',
                );
                $new_zone_id = wp_insert_post( $zone_post );
                
                if ( 'postcodes' === $get_zone_val['zone_type'] ) {
                    update_post_meta( $new_zone_id, 'location_type', 'postcode' );
                } elseif ( 'countries' === $get_zone_val['zone_type'] ) {
                    update_post_meta( $new_zone_id, 'location_type', 'country' );
                } elseif ( 'states' === $get_zone_val['zone_type'] ) {
                    update_post_meta( $new_zone_id, 'location_type', 'state' );
                }
                
                update_post_meta( $new_zone_id, 'zone_type', $get_zone_val['zone_type'] );
                
                if ( 'postcodes' === $get_zone_val['zone_type'] ) {
                    update_post_meta( $new_zone_id, 'location_code', $get_zone_val['location_code'] );
                } else {
                    update_post_meta( $new_zone_id, 'location_code', array( $get_zone_val['location_code'] ) );
                }
            
            }
            $success_array[] = true;
        } else {
            $success_array[] = false;
        }
        
        $redirect_url = add_query_arg( array(
            'page' => 'afrsm-wc-shipping-zones',
        ), admin_url( 'admin.php' ) );
        
        if ( in_array( true, $success_array, true ) ) {
            echo  wp_json_encode( array( true, $redirect_url ) ) ;
        } else {
            echo  wp_json_encode( array( false, $redirect_url ) ) ;
        }
        
        update_option( 'zone_migration', 'done' );
        wp_die();
    }
    
    /**
     * Fetch slug based on id
     *
     * @since    3.6.1
     */
    public function afrsm_pro_fetch_slug( $id_array, $condition )
    {
        $return_array = array();
        if ( !empty($id_array) ) {
            foreach ( $id_array as $key => $ids ) {
                if ( !empty($ids) ) {
                    
                    if ( 'product' === $condition || 'variableproduct' === $condition || 'cpp' === $condition || 'zone' === $condition ) {
                        $get_posts = get_post( $ids );
                        if ( !empty($get_posts) ) {
                            $return_array[] = $get_posts->post_name;
                        }
                    } elseif ( 'category' === $condition || 'cpc' === $condition ) {
                        $term = get_term( $ids, 'product_cat' );
                        if ( !empty($term) ) {
                            $return_array[] = $term->slug;
                        }
                    } elseif ( 'tag' === $condition ) {
                        $tag = get_term( $ids, 'product_tag' );
                        if ( !empty($tag) ) {
                            $return_array[] = $tag->slug;
                        }
                    } elseif ( 'shipping_class' === $condition ) {
                        $shipping_class = get_term( $key, 'product_shipping_class' );
                        if ( !empty($shipping_class) ) {
                            $return_array[$shipping_class->slug] = $ids;
                        }
                    } elseif ( 'cpsc' === $condition ) {
                        $return_array[] = $ids;
                    } elseif ( 'cpp' === $condition ) {
                        $cpp_posts = get_post( $ids );
                        if ( !empty($cpp_posts) ) {
                            $return_array[] = $cpp_posts->post_name;
                        }
                    } else {
                        $return_array[] = $ids;
                    }
                
                }
            }
        }
        return $return_array;
    }
    
    /**
     * Fetch id based on slug
     *
     * @since    3.6.1
     */
    public function afrsm_pro_fetch_id( $slug_array, $condition )
    {
        $return_array = array();
        if ( !empty($slug_array) ) {
            foreach ( $slug_array as $key => $slugs ) {
                if ( !empty($slugs) ) {
                    
                    if ( 'product' === $condition ) {
                        $post = get_page_by_path( $slugs, OBJECT, 'product' );
                        // phpcs:ignore
                        
                        if ( !empty($post) ) {
                            $id = $post->ID;
                            $return_array[] = $id;
                        }
                    
                    } elseif ( 'variableproduct' === $condition ) {
                        $args = array(
                            'post_type' => 'product_variation',
                            'fields'    => 'ids',
                            'name'      => $slugs,
                        );
                        $variable_posts = get_posts( $args );
                        if ( !empty($variable_posts) ) {
                            foreach ( $variable_posts as $val ) {
                                $return_array[] = $val;
                            }
                        }
                    } elseif ( 'category' === $condition || 'cpc' === $condition ) {
                        $term = get_term_by( 'slug', $slugs, 'product_cat' );
                        if ( !empty($term) ) {
                            $return_array[] = $term->term_id;
                        }
                    } elseif ( 'tag' === $condition ) {
                        $term_tag = get_term_by( 'slug', $slugs, 'product_tag' );
                        if ( !empty($term_tag) ) {
                            $return_array[] = $term_tag->term_id;
                        }
                    } elseif ( 'shipping_class' === $condition || 'cpsc' === $condition ) {
                        $shipping_class = get_term_by( 'slug', $slugs, 'product_shipping_class' );
                        if ( !empty($shipping_class) ) {
                            $return_array[$shipping_class->term_id] = $slugs;
                        }
                    } elseif ( 'cpp' === $condition ) {
                        $args = array(
                            'post_type' => array( 'product_variation', 'product' ),
                            'name'      => $slugs,
                        );
                        $variable_posts = get_posts( $args );
                        if ( !empty($variable_posts) ) {
                            foreach ( $variable_posts as $val ) {
                                $return_array[] = $val->ID;
                            }
                        }
                    } elseif ( 'zone' === $condition ) {
                        $post = get_page_by_path( $slugs, OBJECT, 'wc_afrsm_zone' );
                        // phpcs:ignore
                        
                        if ( !empty($post) ) {
                            $id = $post->ID;
                            $return_array[] = $id;
                        }
                    
                    } else {
                        $return_array[] = $slugs;
                    }
                
                }
            }
        }
        return $return_array;
    }
    
    /**
     * Export Shipping Method
     *
     * @since 3.6.1
     *
     */
    public function afrsm_pro_import_export_shipping_method()
    {
        $export_action = filter_input( INPUT_POST, 'afrsm_export_action', FILTER_SANITIZE_STRING );
        $import_action = filter_input( INPUT_POST, 'afrsm_import_action', FILTER_SANITIZE_STRING );
        $default_lang = $this->afrsm_pro_get_default_langugae_with_sitpress();
        
        if ( !empty($export_action) || 'export_settings' === $export_action ) {
            $get_all_fees_args = array(
                'post_type'      => self::afrsm_shipping_post_type,
                'order'          => 'DESC',
                'posts_per_page' => -1,
                'orderby'        => 'ID',
            );
            $get_all_fees_query = new WP_Query( $get_all_fees_args );
            $get_all_fees = $get_all_fees_query->get_posts();
            $get_all_fees_count = $get_all_fees_query->found_posts;
            $get_sort_order = get_option( 'sm_sortable_order_' . $default_lang );
            $sort_order = array();
            if ( isset( $get_sort_order ) && !empty($get_sort_order) ) {
                foreach ( $get_sort_order as $sort ) {
                    $sort_order[$sort] = array();
                }
            }
            foreach ( $get_all_fees as $carrier_id => $carrier ) {
                $carrier_name = $carrier->ID;
                
                if ( array_key_exists( $carrier_name, $sort_order ) ) {
                    $sort_order[$carrier_name][$carrier_id] = $get_all_fees[$carrier_id];
                    unset( $get_all_fees[$carrier_id] );
                }
            
            }
            foreach ( $sort_order as $carriers ) {
                $get_all_fees = array_merge( $get_all_fees, $carriers );
            }
            $fees_data = array();
            $main_data = array();
            
            if ( $get_all_fees_count > 0 ) {
                foreach ( $get_all_fees as $fees ) {
                    $request_post_id = $fees->ID;
                    $sm_status = get_post_status( $request_post_id );
                    $sm_title = __( get_the_title( $request_post_id ), 'advanced-flat-rate-shipping-for-woocommerce' );
                    $sm_cost = get_post_meta( $request_post_id, 'sm_product_cost', true );
                    $sm_free_shipping_based_on = get_post_meta( $request_post_id, 'sm_free_shipping_based_on', true );
                    $is_allow_free_shipping = get_post_meta( $request_post_id, 'is_allow_free_shipping', true );
                    $sm_free_shipping_cost = get_post_meta( $request_post_id, 'sm_free_shipping_cost', true );
                    $sm_free_shipping_cost_before_discount = get_post_meta( $request_post_id, 'sm_free_shipping_cost_before_discount', true );
                    $sm_free_shipping_cost_left_notice = get_post_meta( $request_post_id, 'sm_free_shipping_cost_left_notice', true );
                    $sm_free_shipping_cost_left_notice_msg = get_post_meta( $request_post_id, 'sm_free_shipping_cost_left_notice_msg', true );
                    $sm_free_shipping_coupan_cost = get_post_meta( $request_post_id, 'sm_free_shipping_coupan_cost', true );
                    $sm_free_shipping_label = get_post_meta( $request_post_id, 'sm_free_shipping_label', true );
                    $sm_tooltip_type = get_post_meta( $request_post_id, 'sm_tooltip_type', true );
                    $sm_tooltip_desc = get_post_meta( $request_post_id, 'sm_tooltip_desc', true );
                    $sm_is_taxable = get_post_meta( $request_post_id, 'sm_select_taxable', true );
                    $sm_select_shipping_provider = get_post_meta( $request_post_id, 'sm_select_shipping_provider', true );
                    $sm_metabox = get_post_meta( $request_post_id, 'sm_metabox', true );
                    $sm_extra_cost = get_post_meta( $request_post_id, 'sm_extra_cost', true );
                    $sm_extra_cost_calc_type = get_post_meta( $request_post_id, 'sm_extra_cost_calculation_type', true );
                    $ap_rule_status = get_post_meta( $request_post_id, 'ap_rule_status', true );
                    $fee_settings_unique_shipping_title = get_post_meta( $request_post_id, 'fee_settings_unique_shipping_title', true );
                    $getFeesPerQtyFlag = get_post_meta( $request_post_id, 'sm_fee_chk_qty_price', true );
                    $getFeesPerQty = get_post_meta( $request_post_id, 'sm_fee_per_qty', true );
                    $extraProductCost = get_post_meta( $request_post_id, 'sm_extra_product_cost', true );
                    $sm_estimation_delivery = get_post_meta( $request_post_id, 'sm_estimation_delivery', true );
                    $sm_start_date = get_post_meta( $request_post_id, 'sm_start_date', true );
                    $sm_end_date = get_post_meta( $request_post_id, 'sm_end_date', true );
                    $sm_time_from = get_post_meta( $request_post_id, 'sm_time_from', true );
                    $sm_time_to = get_post_meta( $request_post_id, 'sm_time_to', true );
                    $sm_select_day_of_week = get_post_meta( $request_post_id, 'sm_select_day_of_week', true );
                    $cost_on_product_status = get_post_meta( $request_post_id, 'cost_on_product_status', true );
                    $cost_on_product_weight_status = get_post_meta( $request_post_id, 'cost_on_product_weight_status', true );
                    $cost_on_product_subtotal_status = get_post_meta( $request_post_id, 'cost_on_product_subtotal_status', true );
                    $cost_on_category_status = get_post_meta( $request_post_id, 'cost_on_category_status', true );
                    $cost_on_category_weight_status = get_post_meta( $request_post_id, 'cost_on_category_weight_status', true );
                    $cost_on_category_subtotal_status = get_post_meta( $request_post_id, 'cost_on_category_subtotal_status', true );
                    $cost_on_total_cart_qty_status = get_post_meta( $request_post_id, 'cost_on_total_cart_qty_status', true );
                    $cost_on_total_cart_weight_status = get_post_meta( $request_post_id, 'cost_on_total_cart_weight_status', true );
                    $cost_on_total_cart_subtotal_status = get_post_meta( $request_post_id, 'cost_on_total_cart_subtotal_status', true );
                    $cost_on_shipping_class_subtotal_status = get_post_meta( $request_post_id, 'cost_on_shipping_class_subtotal_status', true );
                    $sm_metabox_ap_product = get_post_meta( $request_post_id, 'sm_metabox_ap_product', true );
                    $sm_metabox_ap_product_subtotal = get_post_meta( $request_post_id, 'sm_metabox_ap_product_subtotal', true );
                    $sm_metabox_ap_product_weight = get_post_meta( $request_post_id, 'sm_metabox_ap_product_weight', true );
                    $sm_metabox_ap_category = get_post_meta( $request_post_id, 'sm_metabox_ap_category', true );
                    $sm_metabox_ap_category_subtotal = get_post_meta( $request_post_id, 'sm_metabox_ap_category_subtotal', true );
                    $sm_metabox_ap_category_weight = get_post_meta( $request_post_id, 'sm_metabox_ap_category_weight', true );
                    $sm_metabox_ap_total_cart_qty = get_post_meta( $request_post_id, 'sm_metabox_ap_total_cart_qty', true );
                    $sm_metabox_ap_total_cart_weight = get_post_meta( $request_post_id, 'sm_metabox_ap_total_cart_weight', true );
                    $sm_metabox_ap_total_cart_subtotal = get_post_meta( $request_post_id, 'sm_metabox_ap_total_cart_subtotal', true );
                    $sm_metabox_ap_shipping_class_subtotal = get_post_meta( $request_post_id, 'sm_metabox_ap_shipping_class_subtotal', true );
                    $cost_rule_match = get_post_meta( $request_post_id, 'cost_rule_match', true );
                    $sm_metabox_customize = array();
                    if ( !empty($sm_metabox) ) {
                        foreach ( $sm_metabox as $key => $val ) {
                            
                            if ( 'product' === $val['product_fees_conditions_condition'] || 'variableproduct' === $val['product_fees_conditions_condition'] || 'category' === $val['product_fees_conditions_condition'] || 'tag' === $val['product_fees_conditions_condition'] || 'zone' === $val['product_fees_conditions_condition'] ) {
                                $product_fees_conditions_values = $this->afrsm_pro_fetch_slug( $val['product_fees_conditions_values'], $val['product_fees_conditions_condition'] );
                                $sm_metabox_customize[$key] = array(
                                    'product_fees_conditions_condition' => $val['product_fees_conditions_condition'],
                                    'product_fees_conditions_is'        => $val['product_fees_conditions_is'],
                                    'product_fees_conditions_values'    => $product_fees_conditions_values,
                                );
                            } else {
                                $sm_metabox_customize[$key] = array(
                                    'product_fees_conditions_condition' => $val['product_fees_conditions_condition'],
                                    'product_fees_conditions_is'        => $val['product_fees_conditions_is'],
                                    'product_fees_conditions_values'    => $val['product_fees_conditions_values'],
                                );
                            }
                        
                        }
                    }
                    
                    if ( !empty($sm_extra_cost) ) {
                        foreach ( $sm_extra_cost as $key => $val ) {
                            $shipping_class = $this->afrsm_pro_fetch_slug( $sm_extra_cost, 'shipping_class' );
                        }
                    } else {
                        $shipping_class = array();
                    }
                    
                    $sm_metabox_ap_product_customize = array();
                    if ( !empty($sm_metabox_ap_product) ) {
                        foreach ( $sm_metabox_ap_product as $key => $val ) {
                            $ap_fees_products_values = $this->afrsm_pro_fetch_slug( $val['ap_fees_products'], 'cpp' );
                            $sm_metabox_ap_product_customize[$key] = array(
                                'ap_fees_products'         => $ap_fees_products_values,
                                'ap_fees_ap_prd_min_qty'   => $val['ap_fees_ap_prd_min_qty'],
                                'ap_fees_ap_prd_max_qty'   => $val['ap_fees_ap_prd_max_qty'],
                                'ap_fees_ap_price_product' => $val['ap_fees_ap_price_product'],
                            );
                        }
                    }
                    $sm_metabox_ap_product_subtotal_customize = array();
                    if ( !empty($sm_metabox_ap_product_subtotal) ) {
                        foreach ( $sm_metabox_ap_product_subtotal as $key => $val ) {
                            $ap_fees_product_subtotal_values = $this->afrsm_pro_fetch_slug( $val['ap_fees_product_subtotal'], 'cpp' );
                            $sm_metabox_ap_product_subtotal_customize[$key] = array(
                                'ap_fees_product_subtotal'                 => $ap_fees_product_subtotal_values,
                                'ap_fees_ap_product_subtotal_min_subtotal' => $val['ap_fees_ap_product_subtotal_min_subtotal'],
                                'ap_fees_ap_product_subtotal_max_subtotal' => $val['ap_fees_ap_product_subtotal_max_subtotal'],
                                'ap_fees_ap_price_product_subtotal'        => $val['ap_fees_ap_price_product_subtotal'],
                            );
                        }
                    }
                    $sm_metabox_ap_product_weight_customize = array();
                    if ( !empty($sm_metabox_ap_product_weight) ) {
                        foreach ( $sm_metabox_ap_product_weight as $key => $val ) {
                            $ap_fees_product_weight_values = $this->afrsm_pro_fetch_slug( $val['ap_fees_product_weight'], 'cpp' );
                            $sm_metabox_ap_product_weight_customize[$key] = array(
                                'ap_fees_product_weight'            => $ap_fees_product_weight_values,
                                'ap_fees_ap_product_weight_min_qty' => $val['ap_fees_ap_product_weight_min_qty'],
                                'ap_fees_ap_product_weight_max_qty' => $val['ap_fees_ap_product_weight_max_qty'],
                                'ap_fees_ap_price_product_weight'   => $val['ap_fees_ap_price_product_weight'],
                            );
                        }
                    }
                    $sm_metabox_ap_category_customize = array();
                    if ( !empty($sm_metabox_ap_category) ) {
                        foreach ( $sm_metabox_ap_category as $key => $val ) {
                            $ap_fees_category_values = $this->afrsm_pro_fetch_slug( $val['ap_fees_categories'], 'cpc' );
                            $sm_metabox_ap_category_customize[$key] = array(
                                'ap_fees_categories'        => $ap_fees_category_values,
                                'ap_fees_ap_cat_min_qty'    => $val['ap_fees_ap_cat_min_qty'],
                                'ap_fees_ap_cat_max_qty'    => $val['ap_fees_ap_cat_max_qty'],
                                'ap_fees_ap_price_category' => $val['ap_fees_ap_price_category'],
                            );
                        }
                    }
                    $sm_metabox_ap_category_subtotal_customize = array();
                    if ( !empty($sm_metabox_ap_category_subtotal) ) {
                        foreach ( $sm_metabox_ap_category_subtotal as $key => $val ) {
                            $ap_fees_category_subtotal_values = $this->afrsm_pro_fetch_slug( $val['ap_fees_category_subtotal'], 'cpc' );
                            $sm_metabox_ap_category_subtotal_customize[$key] = array(
                                'ap_fees_category_subtotal'                 => $ap_fees_category_subtotal_values,
                                'ap_fees_ap_category_subtotal_min_subtotal' => $val['ap_fees_ap_category_subtotal_min_subtotal'],
                                'ap_fees_ap_category_subtotal_max_subtotal' => $val['ap_fees_ap_category_subtotal_max_subtotal'],
                                'ap_fees_ap_price_category_subtotal'        => $val['ap_fees_ap_price_category_subtotal'],
                            );
                        }
                    }
                    $sm_metabox_ap_category_weight_customize = array();
                    if ( !empty($sm_metabox_ap_category_weight) ) {
                        foreach ( $sm_metabox_ap_category_weight as $key => $val ) {
                            $ap_fees_category_weight_values = $this->afrsm_pro_fetch_slug( $val['ap_fees_categories_weight'], 'cpc' );
                            $sm_metabox_ap_category_weight_customize[$key] = array(
                                'ap_fees_categories_weight'          => $ap_fees_category_weight_values,
                                'ap_fees_ap_category_weight_min_qty' => $val['ap_fees_ap_category_weight_min_qty'],
                                'ap_fees_ap_category_weight_max_qty' => $val['ap_fees_ap_category_weight_max_qty'],
                                'ap_fees_ap_price_category_weight'   => $val['ap_fees_ap_price_category_weight'],
                            );
                        }
                    }
                    $sm_metabox_ap_total_cart_qty_customize = array();
                    if ( !empty($sm_metabox_ap_total_cart_qty) ) {
                        foreach ( $sm_metabox_ap_total_cart_qty as $key => $val ) {
                            $ap_fees_total_cart_qty_values = $this->afrsm_pro_fetch_slug( $val['ap_fees_total_cart_qty'], '' );
                            $sm_metabox_ap_total_cart_qty_customize[$key] = array(
                                'ap_fees_total_cart_qty'            => $ap_fees_total_cart_qty_values,
                                'ap_fees_ap_total_cart_qty_min_qty' => $val['ap_fees_ap_total_cart_qty_min_qty'],
                                'ap_fees_ap_total_cart_qty_max_qty' => $val['ap_fees_ap_total_cart_qty_max_qty'],
                                'ap_fees_ap_price_total_cart_qty'   => $val['ap_fees_ap_price_total_cart_qty'],
                            );
                        }
                    }
                    $sm_metabox_ap_total_cart_weight_customize = array();
                    if ( !empty($sm_metabox_ap_total_cart_weight) ) {
                        foreach ( $sm_metabox_ap_total_cart_weight as $key => $val ) {
                            $ap_fees_total_cart_weight_values = $this->afrsm_pro_fetch_slug( $val['ap_fees_total_cart_weight'], '' );
                            $sm_metabox_ap_total_cart_weight_customize[$key] = array(
                                'ap_fees_total_cart_weight'               => $ap_fees_total_cart_weight_values,
                                'ap_fees_ap_total_cart_weight_min_weight' => $val['ap_fees_ap_total_cart_weight_min_weight'],
                                'ap_fees_ap_total_cart_weight_max_weight' => $val['ap_fees_ap_total_cart_weight_max_weight'],
                                'ap_fees_ap_price_total_cart_weight'      => $val['ap_fees_ap_price_total_cart_weight'],
                            );
                        }
                    }
                    $sm_metabox_ap_total_cart_subtotal_customize = array();
                    if ( !empty($sm_metabox_ap_total_cart_subtotal) ) {
                        foreach ( $sm_metabox_ap_total_cart_subtotal as $key => $val ) {
                            $ap_fees_total_cart_subtotal_values = $this->afrsm_pro_fetch_slug( $val['ap_fees_total_cart_subtotal'], '' );
                            $sm_metabox_ap_total_cart_subtotal_customize[$key] = array(
                                'ap_fees_total_cart_subtotal'                 => $ap_fees_total_cart_subtotal_values,
                                'ap_fees_ap_total_cart_subtotal_min_subtotal' => $val['ap_fees_ap_total_cart_subtotal_min_subtotal'],
                                'ap_fees_ap_total_cart_subtotal_max_subtotal' => $val['ap_fees_ap_total_cart_subtotal_max_subtotal'],
                                'ap_fees_ap_price_total_cart_subtotal'        => $val['ap_fees_ap_price_total_cart_subtotal'],
                            );
                        }
                    }
                    $sm_metabox_ap_shipping_class_subtotal_customize = array();
                    if ( !empty($sm_metabox_ap_shipping_class_subtotal) ) {
                        foreach ( $sm_metabox_ap_shipping_class_subtotal as $key => $val ) {
                            $ap_fees_shipping_class_subtotal_values = $this->afrsm_pro_fetch_slug( $val['ap_fees_shipping_class_subtotals'], 'cpsc' );
                            $sm_metabox_ap_shipping_class_subtotal_customize[$key] = array(
                                'ap_fees_shipping_class_subtotals'                => $ap_fees_shipping_class_subtotal_values,
                                'ap_fees_ap_shipping_class_subtotal_min_subtotal' => $val['ap_fees_ap_shipping_class_subtotal_min_subtotal'],
                                'ap_fees_ap_shipping_class_subtotal_max_subtotal' => $val['ap_fees_ap_shipping_class_subtotal_max_subtotal'],
                                'ap_fees_ap_price_shipping_class_subtotal'        => $val['ap_fees_ap_price_shipping_class_subtotal'],
                            );
                        }
                    }
                    $fees_data[$request_post_id] = array(
                        'sm_title'                               => $sm_title,
                        'fee_settings_unique_shipping_title'     => $fee_settings_unique_shipping_title,
                        'sm_cost'                                => $sm_cost,
                        'sm_free_shipping_based_on'              => $sm_free_shipping_based_on,
                        'is_allow_free_shipping'                 => $is_allow_free_shipping,
                        'sm_free_shipping_cost'                  => $sm_free_shipping_cost,
                        'sm_free_shipping_cost_before_discount'  => $sm_free_shipping_cost_before_discount,
                        'sm_free_shipping_cost_left_notice'      => $sm_free_shipping_cost_left_notice,
                        'sm_free_shipping_cost_left_notice_msg'  => $sm_free_shipping_cost_left_notice_msg,
                        'sm_free_shipping_coupan_cost'           => $sm_free_shipping_coupan_cost,
                        'sm_free_shipping_label'                 => $sm_free_shipping_label,
                        'sm_tooltip_type'                        => $sm_tooltip_type,
                        'sm_tooltip_desc'                        => $sm_tooltip_desc,
                        'sm_start_date'                          => $sm_start_date,
                        'sm_end_date'                            => $sm_end_date,
                        'sm_start_time'                          => $sm_time_from,
                        'sm_end_time'                            => $sm_time_to,
                        'sm_select_day_of_week'                  => $sm_select_day_of_week,
                        'sm_estimation_delivery'                 => $sm_estimation_delivery,
                        'sm_select_taxable'                      => $sm_is_taxable,
                        'sm_select_shipping_provider'            => $sm_select_shipping_provider,
                        'status'                                 => $sm_status,
                        'product_fees_metabox'                   => $sm_metabox_customize,
                        'sm_extra_cost'                          => $shipping_class,
                        'sm_extra_cost_calc_type'                => $sm_extra_cost_calc_type,
                        'sm_fee_chk_qty_price'                   => $getFeesPerQtyFlag,
                        'sm_fee_per_qty'                         => $getFeesPerQty,
                        'sm_extra_product_cost'                  => $extraProductCost,
                        'ap_rule_status'                         => $ap_rule_status,
                        'cost_on_product_status'                 => $cost_on_product_status,
                        'cost_on_product_weight_status'          => $cost_on_product_weight_status,
                        'cost_on_product_subtotal_status'        => $cost_on_product_subtotal_status,
                        'cost_on_category_status'                => $cost_on_category_status,
                        'cost_on_category_weight_status'         => $cost_on_category_weight_status,
                        'cost_on_category_subtotal_status'       => $cost_on_category_subtotal_status,
                        'cost_on_total_cart_qty_status'          => $cost_on_total_cart_qty_status,
                        'cost_on_total_cart_weight_status'       => $cost_on_total_cart_weight_status,
                        'cost_on_total_cart_subtotal_status'     => $cost_on_total_cart_subtotal_status,
                        'cost_on_shipping_class_subtotal_status' => $cost_on_shipping_class_subtotal_status,
                        'sm_metabox_ap_product'                  => $sm_metabox_ap_product_customize,
                        'sm_metabox_ap_product_subtotal'         => $sm_metabox_ap_product_subtotal_customize,
                        'sm_metabox_ap_product_weight'           => $sm_metabox_ap_product_weight_customize,
                        'sm_metabox_ap_category'                 => $sm_metabox_ap_category_customize,
                        'sm_metabox_ap_category_subtotal'        => $sm_metabox_ap_category_subtotal_customize,
                        'sm_metabox_ap_category_weight'          => $sm_metabox_ap_category_weight_customize,
                        'sm_metabox_ap_total_cart_qty'           => $sm_metabox_ap_total_cart_qty_customize,
                        'sm_metabox_ap_total_cart_weight'        => $sm_metabox_ap_total_cart_weight_customize,
                        'sm_metabox_ap_total_cart_subtotal'      => $sm_metabox_ap_total_cart_subtotal_customize,
                        'sm_metabox_ap_shipping_class_subtotal'  => $sm_metabox_ap_shipping_class_subtotal_customize,
                        'cost_rule_match'                        => $cost_rule_match,
                    );
                }
                $get_sort_order = get_option( 'sm_sortable_order_' . $default_lang );
                $main_data = array(
                    'fees_data'      => $fees_data,
                    'shipping_order' => $get_sort_order,
                );
            }
            
            $afrsm_export_action_nonce = filter_input( INPUT_POST, 'afrsm_export_action_nonce', FILTER_SANITIZE_STRING );
            if ( !wp_verify_nonce( $afrsm_export_action_nonce, 'afrsm_export_save_action_nonce' ) ) {
                return;
            }
            ignore_user_abort( true );
            nocache_headers();
            header( 'Content-Type: application/json; charset=utf-8' );
            header( 'Content-Disposition: attachment; filename=afrsm-settings-export-' . gmdate( 'm-d-Y' ) . '.json' );
            header( "Expires: 0" );
            echo  wp_json_encode( $main_data ) ;
            exit;
        }
        
        
        if ( !empty($import_action) || 'import_settings' === $import_action ) {
            $afrsm_import_action_nonce = filter_input( INPUT_POST, 'afrsm_import_action_nonce', FILTER_SANITIZE_STRING );
            if ( !wp_verify_nonce( $afrsm_import_action_nonce, 'afrsm_import_action_nonce' ) ) {
                return;
            }
            $file_import_file_args = array(
                'import_file' => array(
                'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                'flags'  => FILTER_FORCE_ARRAY,
            ),
            );
            $attached_import_files__arr = filter_var_array( $_FILES, $file_import_file_args );
            $attached_import_files__arr_explode = explode( '.', $attached_import_files__arr['import_file']['name'] );
            $extension = end( $attached_import_files__arr_explode );
            if ( $extension !== 'json' ) {
                wp_die( esc_html__( 'Please upload a valid .json file', 'advanced-flat-rate-shipping-for-woocommerce' ) );
            }
            $import_file = $attached_import_files__arr['import_file']['tmp_name'];
            if ( empty($import_file) ) {
                wp_die( esc_html__( 'Please upload a file to import', 'advanced-flat-rate-shipping-for-woocommerce' ) );
            }
            WP_Filesystem();
            global  $wp_filesystem ;
            $file_data = $wp_filesystem->get_contents( $import_file );
            
            if ( !empty($file_data) ) {
                $file_data_decode = json_decode( $file_data, true );
                $new_sorting_id = array();
                
                if ( !empty($file_data_decode['fees_data']) ) {
                    foreach ( $file_data_decode['fees_data'] as $fees_val ) {
                        $fee_post = array(
                            'post_title'  => $fees_val['sm_title'],
                            'post_status' => $fees_val['status'],
                            'post_type'   => self::afrsm_shipping_post_type,
                        );
                        $get_post_id = wp_insert_post( $fee_post );
                        if ( '' !== $get_post_id && 0 !== $get_post_id ) {
                            
                            if ( $get_post_id > 0 ) {
                                $new_sorting_id[] = $get_post_id;
                                $sm_metabox_customize = array();
                                if ( !empty($fees_val['product_fees_metabox']) ) {
                                    foreach ( $fees_val['product_fees_metabox'] as $key => $val ) {
                                        
                                        if ( 'product' === $val['product_fees_conditions_condition'] || 'variableproduct' === $val['product_fees_conditions_condition'] || 'category' === $val['product_fees_conditions_condition'] || 'tag' === $val['product_fees_conditions_condition'] || 'zone' === $val['product_fees_conditions_condition'] ) {
                                            $product_fees_conditions_values = $this->afrsm_pro_fetch_id( $val['product_fees_conditions_values'], $val['product_fees_conditions_condition'] );
                                            $sm_metabox_customize[$key] = array(
                                                'product_fees_conditions_condition' => $val['product_fees_conditions_condition'],
                                                'product_fees_conditions_is'        => $val['product_fees_conditions_is'],
                                                'product_fees_conditions_values'    => $product_fees_conditions_values,
                                            );
                                        } else {
                                            $sm_metabox_customize[$key] = array(
                                                'product_fees_conditions_condition' => $val['product_fees_conditions_condition'],
                                                'product_fees_conditions_is'        => $val['product_fees_conditions_is'],
                                                'product_fees_conditions_values'    => $val['product_fees_conditions_values'],
                                            );
                                        }
                                    
                                    }
                                }
                                
                                if ( !empty($fees_val['sm_extra_cost']) ) {
                                    foreach ( $fees_val['sm_extra_cost'] as $key => $val ) {
                                        $shipping_class = $this->afrsm_pro_fetch_id( $fees_val['sm_extra_cost'], 'shipping_class' );
                                    }
                                } else {
                                    $shipping_class = array();
                                }
                                
                                $sm_metabox_product_customize = array();
                                if ( !empty($fees_val['sm_metabox_ap_product']) ) {
                                    foreach ( $fees_val['sm_metabox_ap_product'] as $key => $val ) {
                                        $ap_fees_products_values = $this->afrsm_pro_fetch_id( $val['ap_fees_products'], 'cpp' );
                                        $sm_metabox_product_customize[$key] = array(
                                            'ap_fees_products'         => $ap_fees_products_values,
                                            'ap_fees_ap_prd_min_qty'   => $val['ap_fees_ap_prd_min_qty'],
                                            'ap_fees_ap_prd_max_qty'   => $val['ap_fees_ap_prd_max_qty'],
                                            'ap_fees_ap_price_product' => $val['ap_fees_ap_price_product'],
                                        );
                                    }
                                }
                                $sm_metabox_ap_product_subtotal_customize = array();
                                if ( !empty($fees_val['sm_metabox_ap_product_subtotal']) ) {
                                    foreach ( $fees_val['sm_metabox_ap_product_subtotal'] as $key => $val ) {
                                        $ap_fees_products_subtotal_values = $this->afrsm_pro_fetch_id( $val['ap_fees_product_subtotal'], 'cpp' );
                                        $sm_metabox_ap_product_subtotal_customize[$key] = array(
                                            'ap_fees_product_subtotal'                 => $ap_fees_products_subtotal_values,
                                            'ap_fees_ap_product_subtotal_min_subtotal' => $val['ap_fees_ap_product_subtotal_min_subtotal'],
                                            'ap_fees_ap_product_subtotal_max_subtotal' => $val['ap_fees_ap_product_subtotal_max_subtotal'],
                                            'ap_fees_ap_price_product_subtotal'        => $val['ap_fees_ap_price_product_subtotal'],
                                        );
                                    }
                                }
                                $sm_metabox_ap_product_weight_customize = array();
                                if ( !empty($fees_val['sm_metabox_ap_product_weight']) ) {
                                    foreach ( $fees_val['sm_metabox_ap_product_weight'] as $key => $val ) {
                                        $ap_fees_products_weight_values = $this->afrsm_pro_fetch_id( $val['ap_fees_product_weight'], 'cpp' );
                                        $sm_metabox_ap_product_weight_customize[$key] = array(
                                            'ap_fees_product_weight'            => $ap_fees_products_weight_values,
                                            'ap_fees_ap_product_weight_min_qty' => $val['ap_fees_ap_product_weight_min_qty'],
                                            'ap_fees_ap_product_weight_max_qty' => $val['ap_fees_ap_product_weight_max_qty'],
                                            'ap_fees_ap_price_product_weight'   => $val['ap_fees_ap_price_product_weight'],
                                        );
                                    }
                                }
                                $sm_metabox_ap_category_customize = array();
                                if ( !empty($fees_val['sm_metabox_ap_category']) ) {
                                    foreach ( $fees_val['sm_metabox_ap_category'] as $key => $val ) {
                                        $ap_fees_category_values = $this->afrsm_pro_fetch_id( $val['ap_fees_categories'], 'cpc' );
                                        $sm_metabox_ap_category_customize[$key] = array(
                                            'ap_fees_categories'        => $ap_fees_category_values,
                                            'ap_fees_ap_cat_min_qty'    => $val['ap_fees_ap_cat_min_qty'],
                                            'ap_fees_ap_cat_max_qty'    => $val['ap_fees_ap_cat_max_qty'],
                                            'ap_fees_ap_price_category' => $val['ap_fees_ap_price_category'],
                                        );
                                    }
                                }
                                $sm_metabox_ap_category_subtotal_customize = array();
                                if ( !empty($fees_val['sm_metabox_ap_category_subtotal']) ) {
                                    foreach ( $fees_val['sm_metabox_ap_category_subtotal'] as $key => $val ) {
                                        $ap_fees_ap_category_subtotal_values = $this->afrsm_pro_fetch_id( $val['ap_fees_category_subtotal'], 'cpc' );
                                        $sm_metabox_ap_category_subtotal_customize[$key] = array(
                                            'ap_fees_category_subtotal'                 => $ap_fees_ap_category_subtotal_values,
                                            'ap_fees_ap_category_subtotal_min_subtotal' => $val['ap_fees_ap_category_subtotal_min_subtotal'],
                                            'ap_fees_ap_category_subtotal_max_subtotal' => $val['ap_fees_ap_category_subtotal_max_subtotal'],
                                            'ap_fees_ap_price_category_subtotal'        => $val['ap_fees_ap_price_category_subtotal'],
                                        );
                                    }
                                }
                                $sm_metabox_ap_category_weight_customize = array();
                                if ( !empty($fees_val['sm_metabox_ap_category_weight']) ) {
                                    foreach ( $fees_val['sm_metabox_ap_category_weight'] as $key => $val ) {
                                        $ap_fees_ap_category_weight_values = $this->afrsm_pro_fetch_id( $val['ap_fees_categories_weight'], 'cpc' );
                                        $sm_metabox_ap_category_weight_customize[$key] = array(
                                            'ap_fees_categories_weight'          => $ap_fees_ap_category_weight_values,
                                            'ap_fees_ap_category_weight_min_qty' => $val['ap_fees_ap_category_weight_min_qty'],
                                            'ap_fees_ap_category_weight_max_qty' => $val['ap_fees_ap_category_weight_max_qty'],
                                            'ap_fees_ap_price_category_weight'   => $val['ap_fees_ap_price_category_weight'],
                                        );
                                    }
                                }
                                $sm_metabox_ap_total_cart_qty_customize = array();
                                if ( !empty($fees_val['sm_metabox_ap_total_cart_qty']) ) {
                                    foreach ( $fees_val['sm_metabox_ap_total_cart_qty'] as $key => $val ) {
                                        $ap_fees_ap_total_cart_qty_values = $this->afrsm_pro_fetch_id( $val['ap_fees_total_cart_qty'], '' );
                                        $sm_metabox_ap_total_cart_qty_customize[$key] = array(
                                            'ap_fees_total_cart_qty'            => $ap_fees_ap_total_cart_qty_values,
                                            'ap_fees_ap_total_cart_qty_min_qty' => $val['ap_fees_ap_total_cart_qty_min_qty'],
                                            'ap_fees_ap_total_cart_qty_max_qty' => $val['ap_fees_ap_total_cart_qty_max_qty'],
                                            'ap_fees_ap_price_total_cart_qty'   => $val['ap_fees_ap_price_total_cart_qty'],
                                        );
                                    }
                                }
                                $sm_metabox_ap_total_cart_weight_customize = array();
                                if ( !empty($fees_val['sm_metabox_ap_total_cart_weight']) ) {
                                    foreach ( $fees_val['sm_metabox_ap_total_cart_weight'] as $key => $val ) {
                                        $ap_fees_ap_total_cart_weight_values = $this->afrsm_pro_fetch_id( $val['ap_fees_total_cart_weight'], '' );
                                        $sm_metabox_ap_total_cart_weight_customize[$key] = array(
                                            'ap_fees_total_cart_weight'               => $ap_fees_ap_total_cart_weight_values,
                                            'ap_fees_ap_total_cart_weight_min_weight' => $val['ap_fees_ap_total_cart_weight_min_weight'],
                                            'ap_fees_ap_total_cart_weight_max_weight' => $val['ap_fees_ap_total_cart_weight_max_weight'],
                                            'ap_fees_ap_price_total_cart_weight'      => $val['ap_fees_ap_price_total_cart_weight'],
                                        );
                                    }
                                }
                                $sm_metabox_ap_total_cart_subtotal_customize = array();
                                if ( !empty($fees_val['sm_metabox_ap_total_cart_subtotal']) ) {
                                    foreach ( $fees_val['sm_metabox_ap_total_cart_subtotal'] as $key => $val ) {
                                        $ap_fees_ap_total_cart_subtotal_values = $this->afrsm_pro_fetch_id( $val['ap_fees_total_cart_subtotal'], '' );
                                        $sm_metabox_ap_total_cart_subtotal_customize[$key] = array(
                                            'ap_fees_total_cart_subtotal'                 => $ap_fees_ap_total_cart_subtotal_values,
                                            'ap_fees_ap_total_cart_subtotal_min_subtotal' => $val['ap_fees_ap_total_cart_subtotal_min_subtotal'],
                                            'ap_fees_ap_total_cart_subtotal_max_subtotal' => $val['ap_fees_ap_total_cart_subtotal_max_subtotal'],
                                            'ap_fees_ap_price_total_cart_subtotal'        => $val['ap_fees_ap_price_total_cart_subtotal'],
                                        );
                                    }
                                }
                                $sm_metabox_ap_shipping_class_subtotal_customize = array();
                                if ( !empty($fees_val['sm_metabox_ap_shipping_class_subtotal']) ) {
                                    foreach ( $fees_val['sm_metabox_ap_shipping_class_subtotal'] as $key => $val ) {
                                        $ap_fees_ap_shipping_class_subtotal_values = $this->afrsm_pro_fetch_id( $val['ap_fees_shipping_class_subtotals'], 'cpsc' );
                                        $sm_metabox_ap_shipping_class_subtotal_customize[$key] = array(
                                            'ap_fees_shipping_class_subtotals'                => $ap_fees_ap_shipping_class_subtotal_values,
                                            'ap_fees_ap_shipping_class_subtotal_min_subtotal' => $val['ap_fees_ap_shipping_class_subtotal_min_subtotal'],
                                            'ap_fees_ap_shipping_class_subtotal_max_subtotal' => $val['ap_fees_ap_shipping_class_subtotal_max_subtotal'],
                                            'ap_fees_ap_price_shipping_class_subtotal'        => $val['ap_fees_ap_price_shipping_class_subtotal'],
                                        );
                                    }
                                }
                                update_post_meta( $get_post_id, 'fee_settings_unique_shipping_title', $fees_val['fee_settings_unique_shipping_title'] );
                                update_post_meta( $get_post_id, 'sm_product_cost', $fees_val['sm_cost'] );
                                update_post_meta( $get_post_id, 'sm_free_shipping_based_on', $fees_val['sm_free_shipping_based_on'] );
                                update_post_meta( $get_post_id, 'is_allow_free_shipping', $fees_val['is_allow_free_shipping'] );
                                update_post_meta( $get_post_id, 'sm_free_shipping_cost', $fees_val['sm_free_shipping_cost'] );
                                update_post_meta( $get_post_id, 'sm_free_shipping_cost_before_discount', $fees_val['sm_free_shipping_cost_before_discount'] );
                                update_post_meta( $get_post_id, 'sm_free_shipping_cost_left_notice', $fees_val['sm_free_shipping_cost_left_notice'] );
                                update_post_meta( $get_post_id, 'sm_free_shipping_cost_left_notice_msg', $fees_val['sm_free_shipping_cost_left_notice_msg'] );
                                update_post_meta( $get_post_id, 'sm_free_shipping_coupan_cost', $fees_val['sm_free_shipping_coupan_cost'] );
                                update_post_meta( $get_post_id, 'sm_free_shipping_label', $fees_val['sm_free_shipping_label'] );
                                update_post_meta( $get_post_id, 'sm_tooltip_type', $fees_val['sm_tooltip_type'] );
                                update_post_meta( $get_post_id, 'sm_tooltip_desc', $fees_val['sm_tooltip_desc'] );
                                update_post_meta( $get_post_id, 'sm_start_date', $fees_val['sm_start_date'] );
                                update_post_meta( $get_post_id, 'sm_end_date', $fees_val['sm_end_date'] );
                                update_post_meta( $get_post_id, 'sm_time_from', $fees_val['sm_start_time'] );
                                update_post_meta( $get_post_id, 'sm_time_to', $fees_val['sm_end_time'] );
                                update_post_meta( $get_post_id, 'sm_select_day_of_week', $fees_val['sm_select_day_of_week'] );
                                update_post_meta( $get_post_id, 'sm_estimation_delivery', $fees_val['sm_estimation_delivery'] );
                                update_post_meta( $get_post_id, 'sm_select_taxable', $fees_val['sm_select_taxable'] );
                                update_post_meta( $get_post_id, 'sm_select_shipping_provider', $fees_val['sm_select_shipping_provider'] );
                                update_post_meta( $get_post_id, 'sm_metabox', $sm_metabox_customize );
                                update_post_meta( $get_post_id, 'sm_extra_cost', $shipping_class );
                                update_post_meta( $get_post_id, 'sm_extra_cost_calculation_type', $fees_val['sm_extra_cost_calc_type'] );
                                update_post_meta( $get_post_id, 'sm_fee_chk_qty_price', $fees_val['sm_fee_chk_qty_price'] );
                                update_post_meta( $get_post_id, 'sm_fee_per_qty', $fees_val['sm_fee_per_qty'] );
                                update_post_meta( $get_post_id, 'sm_extra_product_cost', $fees_val['sm_extra_product_cost'] );
                                update_post_meta( $get_post_id, 'ap_rule_status', $fees_val['ap_rule_status'] );
                                update_post_meta( $get_post_id, 'cost_on_product_status', $fees_val['cost_on_product_status'] );
                                update_post_meta( $get_post_id, 'cost_on_product_weight_status', $fees_val['cost_on_product_weight_status'] );
                                update_post_meta( $get_post_id, 'cost_on_product_subtotal_status', $fees_val['cost_on_product_subtotal_status'] );
                                update_post_meta( $get_post_id, 'cost_on_category_status', $fees_val['cost_on_category_status'] );
                                update_post_meta( $get_post_id, 'cost_on_category_weight_status', $fees_val['cost_on_category_weight_status'] );
                                update_post_meta( $get_post_id, 'cost_on_category_subtotal_status', $fees_val['cost_on_category_subtotal_status'] );
                                update_post_meta( $get_post_id, 'cost_on_total_cart_qty_status', $fees_val['cost_on_total_cart_qty_status'] );
                                update_post_meta( $get_post_id, 'cost_on_total_cart_weight_status', $fees_val['cost_on_total_cart_weight_status'] );
                                update_post_meta( $get_post_id, 'cost_on_total_cart_subtotal_status', $fees_val['cost_on_total_cart_subtotal_status'] );
                                update_post_meta( $get_post_id, 'cost_on_shipping_class_subtotal_status', $fees_val['cost_on_shipping_class_subtotal_status'] );
                                update_post_meta( $get_post_id, 'sm_metabox_ap_product', $sm_metabox_product_customize );
                                update_post_meta( $get_post_id, 'sm_metabox_ap_product_subtotal', $sm_metabox_ap_product_subtotal_customize );
                                update_post_meta( $get_post_id, 'sm_metabox_ap_product_weight', $sm_metabox_ap_product_weight_customize );
                                update_post_meta( $get_post_id, 'sm_metabox_ap_category', $sm_metabox_ap_category_customize );
                                update_post_meta( $get_post_id, 'sm_metabox_ap_category_subtotal', $sm_metabox_ap_category_subtotal_customize );
                                update_post_meta( $get_post_id, 'sm_metabox_ap_category_weight', $sm_metabox_ap_category_weight_customize );
                                update_post_meta( $get_post_id, 'sm_metabox_ap_total_cart_qty', $sm_metabox_ap_total_cart_qty_customize );
                                update_post_meta( $get_post_id, 'sm_metabox_ap_total_cart_weight', $sm_metabox_ap_total_cart_weight_customize );
                                update_post_meta( $get_post_id, 'sm_metabox_ap_total_cart_subtotal', $sm_metabox_ap_total_cart_subtotal_customize );
                                update_post_meta( $get_post_id, 'sm_metabox_ap_shipping_class_subtotal', $sm_metabox_ap_shipping_class_subtotal_customize );
                                update_post_meta( $get_post_id, 'cost_rule_match', $fees_val['cost_rule_match'] );
                            }
                        
                        }
                    }
                    update_option( 'sm_sortable_order_' . $default_lang, $new_sorting_id );
                }
            
            }
            
            wp_safe_redirect( add_query_arg( array(
                'page'   => 'afrsm-pro-import-export',
                'status' => 'success',
            ), admin_url( 'admin.php' ) ) );
            exit;
        }
    
    }
    
    /**
     * Export Zone
     *
     * @since 3.6.1
     *
     */
    public function afrsm_pro_import_export_zone()
    {
        $get_all_fees_args = array(
            'post_type'      => self::afrsm_zone_post_type,
            'order'          => 'DESC',
            'posts_per_page' => -1,
            'orderby'        => 'ID',
        );
        $get_all_fees_query = new WP_Query( $get_all_fees_args );
        $get_all_fees = $get_all_fees_query->get_posts();
        $get_all_fees_count = $get_all_fees_query->found_posts;
        $fees_data = array();
        if ( $get_all_fees_count > 0 ) {
            foreach ( $get_all_fees as $fees ) {
                $request_post_id = $fees->ID;
                $sm_status = get_post_status( $request_post_id );
                $sm_title = __( get_the_title( $request_post_id ), 'advanced-flat-rate-shipping-for-woocommerce' );
                $location_type = get_post_meta( $request_post_id, 'location_type', true );
                $zone_type = get_post_meta( $request_post_id, 'zone_type', true );
                $location_code = get_post_meta( $request_post_id, 'location_code', true );
                $fees_data[$request_post_id] = array(
                    'sm_title'      => $sm_title,
                    'status'        => $sm_status,
                    'location_type' => $location_type,
                    'zone_type'     => $zone_type,
                    'location_code' => $location_code,
                );
            }
        }
        $export_action = filter_input( INPUT_POST, 'afrsm_zone_export_action', FILTER_SANITIZE_STRING );
        $import_action = filter_input( INPUT_POST, 'afrsm_zone_import_action', FILTER_SANITIZE_STRING );
        
        if ( !empty($export_action) || 'zone_export_settings' === $export_action ) {
            $afrsm_export_action_nonce = filter_input( INPUT_POST, 'afrsm_zone_export_action_nonce', FILTER_SANITIZE_STRING );
            if ( !wp_verify_nonce( $afrsm_export_action_nonce, 'afrsm_zone_export_save_action_nonce' ) ) {
                return;
            }
            ignore_user_abort( true );
            nocache_headers();
            header( 'Content-Type: application/json; charset=utf-8' );
            header( 'Content-Disposition: attachment; filename=afrsm-zone-export-' . gmdate( 'm-d-Y' ) . '.json' );
            header( "Expires: 0" );
            echo  wp_json_encode( $fees_data ) ;
            exit;
        }
        
        
        if ( !empty($import_action) || 'zone_import_settings' === $import_action ) {
            $afrsm_import_action_nonce = filter_input( INPUT_POST, 'afrsm_zone_import_action_nonce', FILTER_SANITIZE_STRING );
            if ( !wp_verify_nonce( $afrsm_import_action_nonce, 'afrsm_zone_import_action_nonce' ) ) {
                return;
            }
            $file_import_file_args = array(
                'zone_import_file' => array(
                'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                'flags'  => FILTER_FORCE_ARRAY,
            ),
            );
            $attached_import_files__arr = filter_var_array( $_FILES, $file_import_file_args );
            $attached_import_files__arr_explode = explode( '.', $attached_import_files__arr['zone_import_file']['name'] );
            $extension = end( $attached_import_files__arr_explode );
            if ( $extension !== 'json' ) {
                wp_die( esc_html__( 'Please upload a valid .json file', 'advanced-flat-rate-shipping-for-woocommerce' ) );
            }
            $import_file = $attached_import_files__arr['zone_import_file']['tmp_name'];
            if ( empty($import_file) ) {
                wp_die( esc_html__( 'Please upload a file to import', 'advanced-flat-rate-shipping-for-woocommerce' ) );
            }
            WP_Filesystem();
            global  $wp_filesystem ;
            $fees_data = $wp_filesystem->get_contents( $import_file );
            
            if ( !empty($fees_data) ) {
                $fees_data_decode = json_decode( $fees_data, true );
                if ( !empty($fees_data_decode) ) {
                    foreach ( $fees_data_decode as $fees_val ) {
                        
                        if ( !empty($fees_val['sm_title']) ) {
                            $fee_post = array(
                                'post_title'  => $fees_val['sm_title'],
                                'post_status' => $fees_val['status'],
                                'post_type'   => self::afrsm_zone_post_type,
                            );
                            $get_post_id = wp_insert_post( $fee_post );
                            if ( '' !== $get_post_id && 0 !== $get_post_id ) {
                                
                                if ( $get_post_id > 0 ) {
                                    update_post_meta( $get_post_id, 'location_type', $fees_val['location_type'] );
                                    update_post_meta( $get_post_id, 'zone_type', $fees_val['zone_type'] );
                                    update_post_meta( $get_post_id, 'location_code', $fees_val['location_code'] );
                                }
                            
                            }
                        }
                    
                    }
                }
            }
            
            wp_safe_redirect( add_query_arg( array(
                'page'   => 'afrsm-pro-import-export',
                'status' => 'success',
            ), admin_url( 'admin.php' ) ) );
            exit;
        }
    
    }
    
    /**
     * Plugins URL
     *
     * @since    3.6.1
     */
    public function afrsm_pro_plugins_url(
        $id,
        $page,
        $tab,
        $action,
        $nonce
    )
    {
        $query_args = array();
        if ( '' !== $page ) {
            $query_args['page'] = $page;
        }
        if ( '' !== $tab ) {
            $query_args['tab'] = $tab;
        }
        if ( '' !== $action ) {
            $query_args['action'] = $action;
        }
        if ( '' !== $id ) {
            $query_args['id'] = $id;
        }
        if ( '' !== $nonce ) {
            $query_args['_wpnonce'] = wp_create_nonce( 'afrsmnonce' );
        }
        return esc_url( add_query_arg( $query_args, admin_url( 'admin.php' ) ) );
    }
    
    /**
     * Create a menu for plugin.
     *
     * @param string $current current page.
     *
     * @since    3.6.1
     */
    public function afrsm_pro_menus( $current = 'afrsm-pro-list' )
    {
        // $afrsm_action  = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
        // $afrsm_wpnonce = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
        // if ( 'edit' === $afrsm_action && $current === 'afrsm-pro-edit-shipping' ) {
        // 	$shipping_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_STRING );
        // 	$menu_title  = __( 'Edit Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' );
        // 	$menu_url    = $this->afrsm_pro_plugins_url( $shipping_id, 'afrsm-pro-edit-shipping', '', 'edit', $afrsm_wpnonce );
        // 	$menu_slug   = 'afrsm-pro-edit-shipping';
        // } else {
        // 	$menu_title = __( 'Add Shipping Method', 'advanced-flat-rate-shipping-for-woocommerce' );
        // 	$menu_url   = $this->afrsm_pro_plugins_url( '', 'afrsm-pro-add-shipping', '', '', '' );
        // 	$menu_slug  = 'afrsm-pro-add-shipping';
        // }
        $wpfp_menus = array(
            'main_menu' => array(
            'pro_menu'  => array(
            'afrsm-pro-list'          => array(
            'menu_title' => __( 'Manage Shipping Methods', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-list',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-list',
            '',
            '',
            ''
        ),
        ),
            'afrsm-wc-shipping-zones' => array(
            'menu_title' => __( 'Manage Shipping Zones', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-wc-shipping-zones',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-wc-shipping-zones',
            '',
            '',
            ''
        ),
            'sub_menu'   => array(
            'afrsm-wc-shipping-zones' => array(
            'menu_title' => __( 'Add Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-wc-shipping-zones',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-wc-shipping-zones&add_zone',
            '',
            '',
            ''
        ),
        ),
        ),
        ),
            'afrsm-pro-import-export' => array(
            'menu_title' => __( 'Import / Export', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-import-export',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-import-export',
            '',
            '',
            ''
        ),
        ),
            'afrsm-pro-list-addons'   => array(
            'menu_title' => __( 'Add-On list', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-list-addons',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-list-addons',
            '',
            '',
            ''
        ),
        ),
            'afrsm-pro-get-started'   => array(
            'menu_title' => __( 'About Plugin', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-get-started',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-get-started',
            '',
            '',
            ''
        ),
            'sub_menu'   => array(
            'afrsm-pro-get-started' => array(
            'menu_title' => __( 'Getting Started', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-get-started',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-get-started',
            '',
            '',
            ''
        ),
        ),
            'afrsm-pro-information' => array(
            'menu_title' => __( 'Quick info', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-information',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-information',
            '',
            '',
            ''
        ),
        ),
        ),
        ),
            'dotstore'                => array(
            'menu_title' => __( 'Dotstore', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'dotstore',
            'menu_url'   => 'javascript:void(0)',
            'sub_menu'   => array(
            'woocommerce-plugins' => array(
            'menu_title' => __( 'WooCommerce Plugins', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'woocommerce-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/woocommerce-plugins/' ),
        ),
            'wordpress-plugins'   => array(
            'menu_title' => __( 'Wordpress Plugins', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'wordpress-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/wordpress-plugins/' ),
        ),
            'contact-support'     => array(
            'menu_title' => __( 'Contact Support', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'contact-support',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/support/' ),
        ),
        ),
        ),
        ),
            'free_menu' => array(
            'afrsm-pro-list'          => array(
            'menu_title' => __( 'Manage Shipping Methods', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-list',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-list',
            '',
            '',
            ''
        ),
        ),
            'afrsm-wc-shipping-zones' => array(
            'menu_title' => __( 'Manage Shipping Zones', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-wc-shipping-zones',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-wc-shipping-zones',
            '',
            '',
            ''
        ),
            'sub_menu'   => array(
            'afrsm-wc-shipping-zones' => array(
            'menu_title' => __( 'Add Shipping Zone', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-wc-shipping-zones',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-wc-shipping-zones&add_zone',
            '',
            '',
            ''
        ),
        ),
        ),
        ),
            'afrsm-pro-import-export' => array(
            'menu_title' => __( 'Import / Export', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-import-export',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-import-export',
            '',
            '',
            ''
        ),
        ),
            'afrsm-pro-list-addons'   => array(
            'menu_title' => __( 'Add-On list', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-list-addons',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-list-addons',
            '',
            '',
            ''
        ),
        ),
            'afrsm-pro-get-started'   => array(
            'menu_title' => __( 'About Plugin', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-get-started',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-get-started',
            '',
            '',
            ''
        ),
            'sub_menu'   => array(
            'afrsm-pro-get-started' => array(
            'menu_title' => __( 'Getting Started', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-get-started',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-get-started',
            '',
            '',
            ''
        ),
        ),
            'afrsm-pro-information' => array(
            'menu_title' => __( 'Quick info', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-pro-information',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-pro-information',
            '',
            '',
            ''
        ),
        ),
        ),
        ),
            'afrsm-premium'           => array(
            'menu_title' => __( 'Premium Version', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'afrsm-premium',
            'menu_url'   => $this->afrsm_pro_plugins_url(
            '',
            'afrsm-premium',
            '',
            '',
            ''
        ),
        ),
            'dotstore'                => array(
            'menu_title' => __( 'Dotstore', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'dotstore',
            'menu_url'   => 'javascript:void(0)',
            'sub_menu'   => array(
            'woocommerce-plugins' => array(
            'menu_title' => __( 'WooCommerce Plugins', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'woocommerce-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/woocommerce-plugins/' ),
        ),
            'wordpress-plugins'   => array(
            'menu_title' => __( 'Wordpress Plugins', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'wordpress-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/wordpress-plugins/' ),
        ),
            'contact-support'     => array(
            'menu_title' => __( 'Contact Support', 'advanced-flat-rate-shipping-for-woocommerce' ),
            'menu_slug'  => 'contact-support',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/support/' ),
        ),
        ),
        ),
        ),
        ),
        );
        ?>
		<div class="dots-menu-main">
			<nav>
				<ul>
					<?php 
        $main_current = $current;
        $sub_current = $current;
        foreach ( $wpfp_menus['main_menu'] as $main_menu_slug => $main_wpfp_menu ) {
            if ( 'free_menu' === $main_menu_slug || 'common_menu' === $main_menu_slug ) {
                foreach ( $main_wpfp_menu as $menu_slug => $wpfp_menu ) {
                    if ( 'afrsm-pro-information' === $main_current ) {
                        $main_current = 'afrsm-pro-get-started';
                    }
                    $class = ( $menu_slug === $main_current ? 'active' : '' );
                    ?>
									<li>
										<a class="dotstore_plugin <?php 
                    echo  esc_attr( $class ) ;
                    ?>"
										   href="<?php 
                    echo  esc_url( $wpfp_menu['menu_url'] ) ;
                    ?>">
											<?php 
                    esc_html_e( $wpfp_menu['menu_title'], 'advanced-flat-rate-shipping-for-woocommerce' );
                    ?>
										</a>
										<?php 
                    
                    if ( isset( $wpfp_menu['sub_menu'] ) && !empty($wpfp_menu['sub_menu']) ) {
                        ?>
											<ul class="sub-menu">
												<?php 
                        foreach ( $wpfp_menu['sub_menu'] as $sub_menu_slug => $wpfp_sub_menu ) {
                            $sub_class = ( $sub_menu_slug === $sub_current ? 'active' : '' );
                            ?>

													<li>
														<a class="dotstore_plugin <?php 
                            echo  esc_attr( $sub_class ) ;
                            ?>"
														   href="<?php 
                            echo  esc_url( $wpfp_sub_menu['menu_url'] ) ;
                            ?>">
															<?php 
                            esc_html_e( $wpfp_sub_menu['menu_title'], 'advanced-flat-rate-shipping-for-woocommerce' );
                            ?>
														</a>
													</li>
												<?php 
                        }
                        ?>
											</ul>
										<?php 
                    }
                    
                    ?>
									</li>
									<?php 
                }
            }
        }
        ?>
				</ul>
			</nav>
		</div>
		<?php 
    }
    
    /**
     * Filter for price - currency switcher
     *
     * @param float $price Get price which we will convert here.
     *
     * @return float $price Return converted price.
     *
     * @since  3.8
     *
     * @author jb
     */
    public function afrsm_pro_price_based_on_switcher( $price )
    {
        if ( has_filter( 'afrsm_woomc_price' ) ) {
            $price = apply_filters( 'afrsm_woomc_price', $price );
        }
        return $price;
    }
    
    /**
     * Filter for price - currency switcher
     *
     * @param float $price Get price which we will convert here.
     *
     * @return float $price Return converted price.
     *
     * @since  3.8
     *
     * @author jb
     */
    public function afrsm_woomc_price_data( $price )
    {
        if ( function_exists( 'wmc_get_price' ) ) {
            $price = wmc_get_price( $price );
        }
        return $price;
    }
    
    /**
     * Outputs a small piece of javascript for the beacon.
     */
    public function afrsm_output_beacon_js()
    {
        printf(
            '<script type="text/javascript">window.%1$s(\'%2$s\', \'%3$s\')</script>',
            'Beacon',
            'init',
            esc_html( 'afe1c188-3c3b-4c5f-9dbd-87329301c920' )
        );
    }
    
    /**
     * Price change by currancy exchange plugin compatible WOOCS Plugin
     *
     * @param string $price
     *
     * @return string $converted_price
     * @since  3.9.7
     *
     */
    public function afrsm_woocs_convert_price( $price )
    {
        
        if ( is_plugin_active( 'woocommerce-currency-switcher/index.php' ) ) {
            // $converted_price = (new WOOCS())->raw_woocommerce_price($free_shipping_costs); // This conflict with exist cost of shipping.
            $currencies = get_option( 'woocs', array() );
            $cc = get_woocommerce_currency();
            $converted_price = floatval( (double) $price * (double) $currencies[$cc]['rate'] );
        } else {
            $converted_price = $price;
        }
        
        return $converted_price;
    }
    
    /**
     * Action perfome after shipment added to DB from Germanized plugin to append our manual data
     *
     * @param string $data, $data_store
     *
     * @since  4.0
     *
     */
    public function afrsm_shipment_object( $data, $data_store )
    {
        global  $wpdb ;
        $order_id = $data->get_order_id();
        $order = wc_get_order( $order_id );
        // Iterating through order shipping items
        foreach ( $order->get_items( 'shipping' ) as $item_id => $item ) {
            $order_item_name = $item->get_name();
            $fee_post = get_page_by_title( $order_item_name, OBJECT, 'wc_afrsm' );
            // phpcs:ignore
            $fee_id = ( !empty($fee_post) ? $fee_post->ID : 0 );
            $shipment_provider_name = ( get_post_meta( $fee_id, 'sm_select_shipping_provider', true ) ? get_post_meta( $fee_id, 'sm_select_shipping_provider', true ) : '' );
            if ( !empty($shipment_provider_name) ) {
                $shipping_zone_exist = $wpdb->get_var( $wpdb->prepare( "UPDATE {$wpdb->gzd_shipments} \n\t\t\t\t\t\tSET `shipment_shipping_provider` = '%s'\n\t\t\t\t\t\tWHERE shipment_order_id = %d", $shipment_provider_name, intval( $order_id ) ) );
            }
        }
    }
    
    public function afrsm_updated_message( $message, $validation_msg )
    {
        if ( empty($message) ) {
            return false;
        }
        
        if ( 'created' === $message ) {
            $updated_message = esc_html__( "Shipping rule has been created.", 'advanced-flat-rate-shipping-for-woocommerce' );
        } elseif ( 'saved' === $message ) {
            $updated_message = esc_html__( "Shipping rule has been updated.", 'advanced-flat-rate-shipping-for-woocommerce' );
        } elseif ( 'deleted' === $message ) {
            $updated_message = esc_html__( "Shipping rule has been deleted.", 'advanced-flat-rate-shipping-for-woocommerce' );
        } elseif ( 'duplicated' === $message ) {
            $updated_message = esc_html__( "Shipping rule has been duplicated.", 'advanced-flat-rate-shipping-for-woocommerce' );
        } elseif ( 'disabled' === $message ) {
            $updated_message = esc_html__( "Shipping rule has been disabled.", 'advanced-flat-rate-shipping-for-woocommerce' );
        } elseif ( 'enabled' === $message ) {
            $updated_message = esc_html__( "Shipping rule has been enabled.", 'advanced-flat-rate-shipping-for-woocommerce' );
        }
        
        
        if ( 'failed' === $message ) {
            $failed_messsage = esc_html__( "There was an error with saving data.", 'advanced-flat-rate-shipping-for-woocommerce' );
        } elseif ( 'nonce_check' === $message ) {
            $failed_messsage = esc_html__( "There was an error with security check.", 'advanced-flat-rate-shipping-for-woocommerce' );
        }
        
        if ( 'validated' === $message ) {
            $validated_messsage = esc_html( $validation_msg );
        }
        
        if ( !empty($updated_message) ) {
            echo  sprintf( '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) ) ;
            return false;
        }
        
        
        if ( !empty($failed_messsage) ) {
            echo  sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $failed_messsage ) ) ;
            return false;
        }
        
        
        if ( !empty($validated_messsage) ) {
            echo  sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $validated_messsage ) ) ;
            return false;
        }
    
    }

}