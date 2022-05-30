<?php

namespace ADP\BaseVersion\Includes\Core\Rule\CartCondition\Impl;

use ADP\BaseVersion\Includes\Core\Rule\CartCondition\ConditionsLoader;

defined('ABSPATH') or exit;

class ProductCustomFields extends AbstractConditionCartItems
{
    protected $filterType = 'product_custom_fields';

    public static function getType()
    {
        return 'product_custom_fields';
    }

    public static function getLabel()
    {
        return __('Product custom fields (qty)', 'advanced-dynamic-pricing-for-woocommerce');
    }

    public static function getTemplatePath()
    {
        return WC_ADP_PLUGIN_VIEWS_PATH . 'conditions/products/product-custom-fields.php';
    }

    public static function getGroup()
    {
        return ConditionsLoader::GROUP_CART_ITEMS;
    }
}