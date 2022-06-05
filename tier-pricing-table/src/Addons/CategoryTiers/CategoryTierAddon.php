<?php

namespace TierPricingTable\Addons\CategoryTiers;

use  TierPricingTable\Addons\AbstractAddon ;
use  TierPricingTable\PriceManager ;
use  WC_Product_Variation ;
use  WP_Term ;
class CategoryTierAddon extends AbstractAddon
{
    const  SKIP_FOR_PRODUCT_META_KEY = '_skip_category_tier_rules' ;
    const  SETTING_ENABLE_KEY = 'enable_category_addon' ;
    public function getName()
    {
        return __( 'Category tier pricing add-on', 'tier-pricing-table' );
    }
    
    public function isActive()
    {
        $active = $this->settings->get( self::SETTING_ENABLE_KEY, 'yes' ) === 'yes';
        return apply_filters( 'tier_pricing_table/addons/category_tier_pricing_active', $active, $this );
    }
    
    public function run()
    {
        // Saving
        add_action(
            'edit_term',
            array( $this, 'saveTermFields' ),
            10,
            1
        );
        add_action(
            'create_product_cat',
            array( $this, 'saveTermFields' ),
            10,
            1
        );
        add_action( 'product_cat_edit_form_fields', array( $this, 'renderEditFields' ), 99 );
        add_action( 'product_cat_add_form_fields', array( $this, 'renderAddFields' ), 99 );
        add_action( 'tier_pricing_table/admin/pricing_tab_begin', array( $this, 'renderProductCheckbox' ) );
        add_action( 'woocommerce_process_product_meta', array( $this, 'saveTieredPricingTab' ) );
    }
    
    public function renderProductCheckbox( $productId )
    {
        woocommerce_wp_checkbox( array(
            'id'            => self::SKIP_FOR_PRODUCT_META_KEY,
            'wrapper_class' => 'show_if_simple show_if_variable',
            'type'          => 'number',
            'checked'       => $this->isSkipForProduct( $productId, 'edit' ),
            'label'         => __( 'Skip category rules', 'tier-pricing-table' ),
            'description'   => __( 'Don\'t take into account tiered pricing rules from categories. ', 'tier-pricing-table' ),
        ) );
    }
    
    /**
     * Save tiered pricing tab data
     *
     * @param int $productId
     */
    public function saveTieredPricingTab( $productId )
    {
        $nonce = ( isset( $_POST['_simple_product_tier_nonce'] ) ? sanitize_key( $_POST['_simple_product_tier_nonce'] ) : false );
        
        if ( wp_verify_nonce( $nonce, 'save_simple_product_tier_price_data' ) ) {
            $skip = ( isset( $_POST[self::SKIP_FOR_PRODUCT_META_KEY] ) ? 'yes' : 'no' );
            update_post_meta( $productId, self::SKIP_FOR_PRODUCT_META_KEY, $skip );
        }
    
    }
    
    /**
     * Check if need skip category rules for specific product
     *
     * @param int $productId
     * @param string $context
     *
     * @return bool|mixed|void
     */
    public function isSkipForProduct( $productId, $context = 'view' )
    {
        $product = wc_get_product( $productId );
        
        if ( $product ) {
            if ( $product instanceof WC_Product_Variation ) {
                $productId = $product->get_parent_id();
            }
            $skip = 'yes' === get_post_meta( $productId, self::SKIP_FOR_PRODUCT_META_KEY, true );
            if ( 'edit' != $context ) {
                return apply_filters(
                    'tier_pricing_table/addons/category_tier_pricing_skip_category',
                    $skip,
                    $productId,
                    $product
                );
            }
            return $skip;
        }
        
        return false;
    }
    
    /**
     * Save metadata to custom attributes terms
     *
     * @param int $term_id
     */
    public function saveTermFields( $term_id )
    {
        $data = $_REQUEST;
        $prefix = 'category';
        $percentageAmounts = ( isset( $data['tiered_price_percent_quantity_' . $prefix] ) ? (array) $data['tiered_price_percent_quantity_' . $prefix] : array() );
        $percentagePrices = ( !empty($data['tiered_price_percent_discount_' . $prefix]) ? (array) $data['tiered_price_percent_discount_' . $prefix] : array() );
    }
    
    /**
     * Render fields on category edit page
     *
     * @param WP_Term $category
     */
    public function renderEditFields( WP_Term $category )
    {
        
        if ( tpt_fs()->is_premium() ) {
            $rules = $this->getForTerm( $category->term_id );
            $this->fileManager->includeTemplate( 'addons/category-tiers/edit.php', [
                'rules' => $rules,
            ] );
        } else {
            $this->fileManager->includeTemplate( 'addons/category-tiers/edit-free.php' );
        }
    
    }
    
    /**
     * Render fields on category adding page
     */
    public function renderAddFields()
    {
        
        if ( tpt_fs()->is_premium() ) {
            $this->fileManager->includeTemplate( 'addons/category-tiers/add.php', [
                'rules' => [],
            ] );
        } else {
            $this->fileManager->includeTemplate( 'addons/category-tiers/add-free.php' );
        }
    
    }
    
    /**
     * @param int $term_id
     *
     * @return array
     */
    public function getForTerm( $term_id )
    {
        $rules = get_term_meta( $term_id, '_percentage_price_rules', true );
        $rules = ( !empty($rules) ? $rules : array() );
        ksort( $rules );
        return $rules;
    }

}