<?php

namespace ADP\BaseVersion\Includes\Compatibility;

use ADP\BaseVersion\Includes\Context;
use ADP\BaseVersion\Includes\WC\WcCartItemFacade;

defined('ABSPATH') or exit;

/**
 * TODO force the option 'initial_price_context' value to 'view'
 *
 * Plugin Name: WooCommerce Product Bundles
 * Author: SomewhereWarm
 *
 * @see https://woocommerce.com/products/product-bundles/
 */
class SomewhereWarmBundlesCmp
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @param null $deprecated
     */
    public function __construct($deprecated = null)
    {
        $this->context = adp_context();
    }

    public function withContext(Context $context)
    {
        $this->context = $context;
    }

    public function addFilters()
    {
        // type cast for "identical" comparison in "update_cart_action" method
        add_filter('woocommerce_stock_amount_cart_item', function ($qty) {
            return (float)$qty;
        }, 10, 2);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return class_exists("WC_Bundles") || class_exists("WC_Product_Bundle");
    }

    /**
     * @param WcCartItemFacade $facade
     *
     * @return bool
     */
    public function isBundled(WcCartItemFacade $facade)
    {
        return function_exists('wc_pb_maybe_is_bundled_cart_item') && wc_pb_maybe_is_bundled_cart_item($facade->getData());
    }

    /**
     * @return bool
     */
    public function IsBundleProduct($product) {
        return $product instanceof \WC_Product_Bundle;
    }
}
