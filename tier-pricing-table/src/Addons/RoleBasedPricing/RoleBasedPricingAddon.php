<?php

namespace TierPricingTable\Addons\RoleBasedPricing;

use  TierPricingTable\Addons\AbstractAddon ;
use  WC_Product ;
class RoleBasedPricingAddon extends AbstractAddon
{
    const  GET_ROLE_ROW_HTML__ACTION = 'tpt_get_role_row_html' ;
    const  SETTING_ENABLE_KEY = 'enable_role_based_pricing_addon' ;
    /**
     * Get addon name
     *
     * @return string
     */
    public function getName()
    {
        return __( 'Role based tiered pricing', 'tier-pricing-table' );
    }
    
    /**
     * Whether addon is active or not
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->settings->get( self::SETTING_ENABLE_KEY, 'yes' ) === 'yes';
    }
    
    /**
     * Run
     */
    public function run()
    {
        // Get row ajax
        add_action( 'wp_ajax_' . self::GET_ROLE_ROW_HTML__ACTION, array( $this, 'getRoleRowHtml' ) );
        // Simple product
        add_action( 'tier_pricing_table/admin/pricing_tab_end', array( $this, 'renderProductPage' ) );
        // Variable product
        add_action(
            'woocommerce_variation_options_pricing',
            array( $this, 'renderPriceRulesVariation' ),
            11,
            3
        );
    }
    
    protected function getRolePrice( WC_Product $product, $specific = false )
    {
        $userRoles = $this->getCurrentUserRoles();
        if ( !empty($userRoles) ) {
            foreach ( $userRoles as $role ) {
                $roleSalePrice = RoleBasedPriceManager::getProductSaleRolePrice( $product->get_id(), $role );
                $roleRegularPrice = RoleBasedPriceManager::getProductRegularRolePrice( $product->get_id(), $role );
                
                if ( $specific ) {
                    
                    if ( 'sale' === $specific && $roleSalePrice ) {
                        return $roleSalePrice;
                    } else {
                        if ( 'regular' === $specific && $roleRegularPrice ) {
                            return $roleRegularPrice;
                        }
                    }
                
                } else {
                    
                    if ( $roleSalePrice ) {
                        return $roleSalePrice;
                    } else {
                        if ( $roleRegularPrice ) {
                            return $roleRegularPrice;
                        }
                    }
                
                }
            
            }
        }
        return null;
    }
    
    protected function getCurrentUserRoles()
    {
        $roles = array();
        $user = wp_get_current_user();
        if ( $user ) {
            $roles = (array) $user->roles;
        }
        return apply_filters( 'tier_pricing_table/role_based_rules/current_user_roles', $roles, get_current_user_id() );
    }
    
    /**
     * AJAX Handler
     */
    public function getRoleRowHtml()
    {
        $nonce = ( isset( $_GET['nonce'] ) ? sanitize_text_field( $_GET['nonce'] ) : false );
        
        if ( wp_verify_nonce( $nonce, self::GET_ROLE_ROW_HTML__ACTION ) ) {
            $role = ( isset( $_GET['role'] ) ? sanitize_text_field( $_GET['role'] ) : false );
            $product_id = ( isset( $_GET['product_id'] ) ? intval( $_GET['product_id'] ) : 0 );
            $loop = ( isset( $_GET['loop'] ) ? intval( $_GET['loop'] ) : 0 );
            $role = get_role( $role );
            $product = wc_get_product( $product_id );
            
            if ( $role && $product ) {
                $type = ( $product->is_type( 'variation' ) ? 'variation' : 'simple' );
                wp_send_json( array(
                    'success'       => true,
                    'role_row_html' => $this->fileManager->renderTemplate( "addons/role-based-pricing/{$type}/role.php", array(
                    'role'                   => $role->name,
                    'loop'                   => $loop,
                    'fileManager'            => $this->fileManager,
                    'minimum_amount'         => RoleBasedPriceManager::getProductQtyMin( $product_id, $role->name, 'edit' ),
                    'price_rules_fixed'      => RoleBasedPriceManager::getFixedPriceRules( $product_id, $role->name, 'edit' ),
                    'price_rules_percentage' => RoleBasedPriceManager::getPercentagePriceRules( $product_id, $role->name, 'edit' ),
                    'regular_price'          => RoleBasedPriceManager::getProductRegularRolePrice( $product_id, $role->name, 'edit' ),
                    'sale_price'             => RoleBasedPriceManager::getProductSaleRolePrice( $product_id, $role->name, 'edit' ),
                    'type'                   => RoleBasedPriceManager::getPricingType(
                    $product_id,
                    $role->name,
                    'fixed',
                    'edit'
                ),
                ) ),
                ) );
            }
            
            wp_send_json( array(
                'success'       => false,
                'error_message' => __( 'Invalid role', 'tier-pricing-table' ),
            ) );
        }
        
        wp_send_json( array(
            'success'       => false,
            'error_message' => __( 'Invalid nonce', 'tier-pricing-table' ),
        ) );
    }
    
    /**
     * Render product page role-based template
     */
    public function renderProductPage()
    {
        global  $post ;
        $this->fileManager->includeTemplate( 'addons/role-based-pricing/simple/role-based-block.php', array(
            'fileManager' => $this->fileManager,
            'product_id'  => $post->ID,
        ) );
    }
    
    /**
     * Render variation role-based template
     *
     * @param $loop
     * @param $variation_data
     * @param $variation
     */
    public function renderPriceRulesVariation( $loop, $variation_data, $variation )
    {
        $this->fileManager->includeTemplate( 'addons/role-based-pricing/variation/role-based-block.php', array(
            'fileManager' => $this->fileManager,
            'product_id'  => $variation->ID,
            'loop'        => $loop,
        ) );
    }

}