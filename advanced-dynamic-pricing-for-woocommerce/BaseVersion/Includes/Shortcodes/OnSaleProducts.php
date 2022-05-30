<?php

namespace ADP\BaseVersion\Includes\Shortcodes;

use ADP\BaseVersion\Includes\Cache\CacheHelper;
use ADP\BaseVersion\Includes\Context;
use ADP\BaseVersion\Includes\Core\Rule\Rule;
use ADP\BaseVersion\Includes\Core\Rule\SingleItemRule;
use ADP\BaseVersion\Includes\Database\Database;
use ADP\BaseVersion\Includes\Database\Repository\RuleRepository;
use ADP\BaseVersion\Includes\Database\RuleStorage;
use ADP\BaseVersion\Includes\Enums\RuleTypeEnum;
use ADP\Factory;

defined('ABSPATH') or exit;

class OnSaleProducts extends Products
{
    const NAME = 'adp_products_on_sale';
    const STORAGE_KEY = 'wdp_products_onsale';

    protected function set_adp_products_on_sale_query_args(&$queryArgs)
    {
        if( $this->attributes["show_wc_onsale_products"] )
            $queryArgs['post__in'] = array_unique(array_merge(array(0), static::getCachedProductsIds(), wc_get_product_ids_on_sale()));
        else
            $queryArgs['post__in'] = array_merge(array(0), static::getCachedProductsIds());
    }

    /**
     * @param null $deprecated
     *
     * @return array
     */
    public static function getProductsIds($from = null, $count = null, $deprecated = null)
    {
        global $wpdb;

        $context         = adp_context();
        $rulesCollection = CacheHelper::loadActiveRules($context);
        $rulesArray      = $context->getOption('rules_apply_mode') !== "none" ? $rulesCollection->getRules() : array();

        /** @var RuleStorage $storage */
        $storage                   = Factory::get("Database_RuleStorage");
        $ruleRepository            = new RuleRepository();
        $rows                      = $ruleRepository->getRules(
            array(
                'active_only' => true,
                'rule_types'  => array(RuleTypeEnum::PERSISTENT()->getValue())
            )
        );
        $persistentRulesCollection = $storage->buildPersistentRules($rows);
        $persistentRulesArray      = $context->getOption('rules_apply_mode') !== "none" ? $persistentRulesCollection->getRules() : array();

        $rulesArray = array_merge($rulesArray, $persistentRulesArray);

        /** @var $sqlGenerator SqlGenerator */
        $sqlGenerator = Factory::get("Shortcodes_SqlGenerator");

        foreach ($rulesArray as $rule) {
            if (self::isSimpleRule($rule)) {
                $sqlGenerator->applyRuleToQuery($rule);
            }
        }

        if ($sqlGenerator->isEmpty()) {
            return array();
        }

        $sql_joins    = $sqlGenerator->getJoin();
        $sql_where    = $sqlGenerator->getWhere();
        $excludeWhere = $sqlGenerator->getExcludeWhere();

        $sql = "SELECT post.ID as id, post.post_parent as parent_id FROM `$wpdb->posts` AS post
			" . implode(" ", $sql_joins) . "
			WHERE post.post_type IN ( 'product', 'product_variation' )
				AND post.post_status = 'publish'
			" . ($sql_where ? " AND " : "") . implode(" OR ", array_map(function ($v) {
                return "(" . $v . ")";
            }, $sql_where)) . ($excludeWhere ? " AND " : "") . implode(" AND ", array_map(function ($v) {
                return "(" . $v . ")";
            }, $excludeWhere)) . "
			GROUP BY post.ID";
        if (isset($from) && isset($count)) {
            $sql .= " LIMIT $from, $count";
        }

        $bogoProducts = $wpdb->get_results($sql);

        $productIdsBogo = wp_parse_id_list(array_merge(wp_list_pluck($bogoProducts, 'id'),
            array_diff(wp_list_pluck($bogoProducts, 'parent_id'), array(0))));

        return $productIdsBogo;
    }

    /**
     * @param Rule $rule
     *
     * @return bool
     */
    protected static function isSimpleRule($rule)
    {
        return
            $rule instanceof SingleItemRule &&
            $rule->getProductAdjustmentHandler() &&
            ! $rule->getProductRangeAdjustmentHandler() &&
            ! $rule->getRoleDiscounts() &&
            count($rule->getGifts()) === 0 &&
            count($rule->getItemGiftsCollection()->asArray()) === 0 &&
            adp_functions()->isRuleMatchedCart($rule) &&
            count($rule->getLimits()) === 0;
    }

    /**
     * Parse attributes.
     *
     * @since  3.2.0
     * @param  array $attributes Shortcode attributes.
     * @return array
     */
    protected function parse_attributes( $attributes ) {
        $parsed_attributes = parent::parse_attributes( $attributes );
        //parse own attrubutes
        $parsed_attributes['show_wc_onsale_products'] = false;
        if ( isset($attributes['show_wc_onsale_products']) )
            $parsed_attributes['show_wc_onsale_products'] = wc_string_to_bool($attributes['show_wc_onsale_products']);
        return $parsed_attributes;
    }

}
