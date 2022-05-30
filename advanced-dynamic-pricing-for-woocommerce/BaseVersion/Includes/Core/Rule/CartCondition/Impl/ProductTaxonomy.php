<?php

namespace ADP\BaseVersion\Includes\Core\Rule\CartCondition\Impl;

use ADP\BaseVersion\Includes\Core\Rule\CartCondition\ConditionsLoader;
use WP_Taxonomy;

defined('ABSPATH') or exit;

class ProductTaxonomy extends AbstractConditionCartItems
{
    /**
     * @var WP_Taxonomy
     */
    protected $taxonomy;

    /**
     * @var string
     */
    protected $filterType;

    public static function getType()
    {
        return 'custom_taxonomy';
    }

    public static function getLabel()
    {
        return "";
    }

    /**
     * @return string
     */
    public function getTaxonomyLabel()
    {
        return $this->taxonomy->label . " (qty)";
    }

    /**
     * @param WP_Taxonomy $taxonomy
     */
    public function setTaxonomy(WP_Taxonomy $taxonomy)
    {
        $this->taxonomy   = $taxonomy;
        $this->filterType = $taxonomy->name;
    }

    public static function getTemplatePath()
    {
        return WC_ADP_PLUGIN_VIEWS_PATH . 'conditions/products/product-taxonomy.php';
    }

    public static function getGroup()
    {
        return ConditionsLoader::GROUP_CART_ITEMS;
    }
}
