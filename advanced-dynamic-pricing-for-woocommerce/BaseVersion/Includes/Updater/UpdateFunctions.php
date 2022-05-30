<?php

namespace ADP\BaseVersion\Includes\Updater;

use ADP\BaseVersion\Includes\Context;
use ADP\BaseVersion\Includes\Core\RuleProcessor\OptionsConverter;
use ADP\BaseVersion\Includes\Database\Models\Rule;
use ADP\BaseVersion\Includes\Database\Repository\RuleRepository;
use ADP\BaseVersion\Includes\Enums\RuleTypeEnum;

defined('ABSPATH') or exit;

class UpdateFunctions
{
    public static function call_update_function($function)
    {
        if (method_exists(__CLASS__, $function)) {
            self::$function();
        }
    }

    public static function migrateTo_2_2_3()
    {
        global $wpdb;

        $table = $wpdb->prefix . Rule::TABLE_NAME;
        $sql   = "SELECT id, conditions FROM $table";
        $rows  = $wpdb->get_results($sql);

        $rows = array_map(function ($item) {
            $result = array(
                'id'         => $item->id,
                'conditions' => unserialize($item->conditions),
            );

            return $result;
        }, $rows);

        foreach ($rows as &$row) {
            $prev_row = $row;
            foreach ($row['conditions'] as &$condition) {
                if ('amount_' === substr($condition['type'], 0,
                        strlen('amount_')) && 3 === count($condition['options'])) {
                    array_unshift($condition['options'], 'in_list');
                }
            }
            if ($prev_row != $row) {
                $row['conditions'] = serialize($row['conditions']);
                $result            = $wpdb->update($table, array('conditions' => $row['conditions']),
                    array('id' => $row['id']));
            }
        }
    }

    public static function migrateTo_3_0_0()
    {
        global $wpdb;

        $table = $wpdb->prefix . Rule::TABLE_NAME;
        $sql   = "SELECT id, conditions, limits, cart_adjustments FROM $table";
        $rows  = $wpdb->get_results($sql);

        $rows = array_map(function ($item) {
            $result = array(
                'id'               => $item->id,
                'conditions'       => unserialize($item->conditions),
                'limits'           => unserialize($item->limits),
                'cart_adjustments' => unserialize($item->cart_adjustments),
            );

            return $result;
        }, $rows);

        foreach ($rows as &$row) {
            $prev_row = $row;
            foreach ($row['conditions'] as &$data) {
                $data = OptionsConverter::convertCondition($data);
            }
            foreach ($row['cart_adjustments'] as &$data) {
                $data = OptionsConverter::convertCartAdj($data);
            }
            foreach ($row['limits'] as &$data) {
                $data = OptionsConverter::convertLimit($data);
            }
            if ($prev_row != $row) {
                $row['conditions']       = serialize($row['conditions']);
                $row['cart_adjustments'] = serialize($row['cart_adjustments']);
                $row['limits']           = serialize($row['limits']);
                $result                  = $wpdb->update($table, array(
                    'conditions'       => $row['conditions'],
                    'cart_adjustments' => $row['cart_adjustments'],
                    'limits'           => $row['limits'],
                ),
                    array('id' => $row['id']));
            }
        }
    }

    public static function migrateOptionsTo_3_1_0()
    {
        $context                     = new Context();
        $settings                    = $context->getSettings();
        $disableExternalCouponsValue = $settings->getOption('disable_external_coupons');

        if ($disableExternalCouponsValue === "dont_disable") {
            if ( ! $settings->getOption('apply_external_coupons_only_to_unmodified_products')) {
                $settings->set("external_coupons_behavior", "apply");
            } else {
                $settings->set("external_coupons_behavior", "apply_to_unmodified_only");
            }
        } elseif ($disableExternalCouponsValue === "if_any_rule_applied") {
            $settings->set("external_coupons_behavior", "disable_if_any_rule_applied");
        } elseif ($disableExternalCouponsValue === "if_any_of_cart_items_updated") {
            $settings->set("external_coupons_behavior", "disable_if_any_of_cart_items_updated");
        }

        $context->getSettings()->save();
    }

    public static function migrateFreeProductsTo_3_1_0()
    {
        global $wpdb;

        $table = $wpdb->prefix . Rule::TABLE_NAME;
        $sql   = "SELECT id, get_products FROM $table";
        $rows  = $wpdb->get_results($sql);

        $rows = array_filter(array_map(function ($item) {
            $result = array(
                'id'           => $item->id,
                'get_products' => unserialize($item->get_products),
            );

            if (empty($result['get_products'])) {
                return false;
            }

            return $result;
        }, $rows));

        foreach ($rows as &$row) {
            $values = isset($row['get_products']['value']) ? $row['get_products']['value'] : array();
            foreach ($values as &$value) {
                $giftMode             = isset($value['gift_mode']) ? $value['gift_mode'] : "giftable_products";
                $useProductFromFilter = isset($value['use_product_from_filter']) ? $value['use_product_from_filter'] === 'on' : false;
                if ($useProductFromFilter) {
                    $giftMode = "use_product_from_filter";
                }

                $value['gift_mode'] = $giftMode;
                unset($value['use_product_from_filter']);
            }
            $row['get_products']['value'] = $values;

            $result = $wpdb->update($table, array('get_products' => serialize($row['get_products'])),
                array('id' => $row['id']));
        }
    }

    public static function migrate_options_to_3_2_1()
    {
        $context  = new Context();
        $settings = $context->getSettings();

        $replaceVariationPriceOption = $settings->getOption('replace_price_with_min_variation_price');
        if ($replaceVariationPriceOption) {
            $replaceVariationPriceCategoryOption = $settings->tryGetOption('replace_price_with_min_variation_price_category');

            if ($replaceVariationPriceCategoryOption && ! $replaceVariationPriceCategoryOption->isValueInstalled()) {
                $replaceVariationPriceCategoryOption->set(true);
            }
        }

        if ($replaceVariationPriceTemplateOption = $settings->getOption('replace_price_with_min_variation_price_template')) {
            $replaceVariationPriceCategoryTemplateOption = $settings->tryGetOption('replace_price_with_min_variation_price_category_template');

            if ($replaceVariationPriceCategoryTemplateOption && ! $replaceVariationPriceCategoryTemplateOption->isValueInstalled()) {
                $replaceVariationPriceCategoryTemplateOption->set($replaceVariationPriceTemplateOption);
            }
        }

        $replaceLwestBulkPriceOption = $settings->getOption('replace_price_with_min_bulk_price');
        if ($replaceLwestBulkPriceOption) {
            $replaceLwestBulkPriceCategoryOption = $settings->tryGetOption('replace_price_with_min_bulk_price_category');

            if ($replaceLwestBulkPriceCategoryOption && ! $replaceLwestBulkPriceCategoryOption->isValueInstalled()) {
                $replaceLwestBulkPriceCategoryOption->set(true);
            }
        }

        if ($replaceLwestBulkPriceTemplateOption = $settings->getOption('replace_price_with_min_bulk_price_template')) {
            $replaceLwestBulkPriceCategoryTemplateOption = $settings->tryGetOption('replace_price_with_min_bulk_price_category_template');

            if ($replaceLwestBulkPriceCategoryTemplateOption && ! $replaceLwestBulkPriceCategoryTemplateOption->isValueInstalled()) {
                $replaceLwestBulkPriceCategoryTemplateOption->set($replaceLwestBulkPriceTemplateOption);
            }
        }

        $context->getSettings()->save();
    }

    public static function migrateFreeProductsTo_3_2_6()
    {
        global $wpdb;

        $table = $wpdb->prefix . Rule::TABLE_NAME;
        $sql   = "SELECT id, get_products FROM $table";
        $rows  = $wpdb->get_results($sql);

        $rows = array_filter(array_map(function ($item) {
            $result = array(
                'id'           => $item->id,
                'get_products' => unserialize($item->get_products),
            );

            if (empty($result['get_products'])) {
                return false;
            }

            return $result;
        }, $rows));

        foreach ($rows as &$row) {
            $values = isset($row['get_products']['value']) ? $row['get_products']['value'] : array();
            foreach ($values as &$value) {
                $giftMode = isset($value['gift_mode']) ? $value['gift_mode'] : "giftable_products";
                if ($giftMode === 'giftable_products_in_random') {
                    $giftMode = 'giftable_products_in_rotation';
                }

                $value['gift_mode'] = $giftMode;
            }
            $row['get_products']['value'] = $values;

            $result = $wpdb->update($table, array('get_products' => serialize($row['get_products'])),
                array('id' => $row['id']));
        }
    }

    public static function migrateConditionsTo_4_0_0() {
        global $wpdb;

        $table = $wpdb->prefix . Rule::TABLE_NAME;
        $sql   = "SELECT id, conditions FROM $table";
        $rows  = $wpdb->get_results($sql);

        $rows = array_filter(array_map(function ($item) {
            $result = array(
                'id'           => $item->id,
                'conditions' => unserialize($item->conditions),
            );

            if (empty($result['conditions'])) {
                return false;
            }

            return $result;
        }, $rows));

        foreach ($rows as &$row) {
            $prev_row = $row;
            foreach ($row['conditions'] as $index => $data) {
                try {
                    $row['conditions'][$index] = OptionsConverter::convertCondition($data);
                } catch (\Exception $e) {
                    unset($row['conditions'][$index]);
                }
            }
            if ($prev_row != $row) {
                $row['conditions']       = serialize($row['conditions']);
                $wpdb->update($table, array(
                    'conditions'       => $row['conditions'],
                ), array('id' => $row['id']));
            }
        }
    }

    public static function migrateRuleTypeTo_4_0_0() {
        global $wpdb;

        $table = $wpdb->prefix . Rule::TABLE_NAME;
        $sql   = "SELECT id, exclusive FROM $table";
        $rows  = $wpdb->get_results($sql, ARRAY_A);

        foreach ($rows as $row) {
            $rule_type = $row['exclusive'] ? RuleTypeEnum::EXCLUSIVE()->getValue() : RuleTypeEnum::COMMON()->getValue();
            $wpdb->update($table, array(
                'rule_type'       => $rule_type,
            ), array('id' => $row['id']));
        }
    }

    public static function migrateOptionsTo_3_3_1()
    {
        $context = new Context();
        $settings = $context->getSettings();

        $externalCouponsBehavior = $settings->getOption('external_coupons_behavior');
        $settings->set('external_product_coupons_behavior', $externalCouponsBehavior);
        $settings->set('external_cart_coupons_behavior', $externalCouponsBehavior);

        $settings->save();
    }

    public static function migrateOptionsTo_4_0_0()
    {
        $context = new Context();
        $settings = $context->getSettings();

        $allowToMergeCartItems = $settings->getOption('allow_to_merge_cart_items');
        $settings->set('split_same_product_if_diff_costs', ! $allowToMergeCartItems);

        $settings->save();
    }

    public static function migrateSplitDiscountByTo_4_0_0()
    {
        global $wpdb;

        $table = $wpdb->prefix . Rule::TABLE_NAME;
        $sql = "SELECT id, product_adjustments, bulk_adjustments FROM $table";
        $rows = $wpdb->get_results($sql);

        $rows = array_filter(array_map(function ($item) {
            $result = array(
                'id' => $item->id,
                'product_adjustments' => unserialize($item->product_adjustments),
                'bulk_adjustments' => unserialize($item->bulk_adjustments),
            );

            if (empty($result['product_adjustments'])) {
                return false;
            }

            return $result;
        }, $rows));

        foreach ($rows as $row) {
            $changes = array();

            $prodAdjSplitDiscountBy = $row['product_adjustments']['split_discount_by'] ?? false;
            if ($prodAdjSplitDiscountBy instanceof \__PHP_Incomplete_Class) {
                $objArray = (array)$prodAdjSplitDiscountBy;
                foreach ($objArray as $k => $v) {
                    if ($k == '__PHP_Incomplete_Class_Name') {
                        continue;
                    }
                    $parts = explode(chr(0), $k);
                    $key = $parts[0] == $k ? $k : $parts[2];
                    if ($key == 'value') {
                        $row['product_adjustments']['split_discount_by'] = $v;
                        $changes['product_adjustments'] = serialize($row['product_adjustments']);
                        break;
                    }
                }
            }

            $bulkAdjSplitDiscountBy = $row['bulk_adjustments']['split_discount_by'] ?? false;
            if ($bulkAdjSplitDiscountBy instanceof \__PHP_Incomplete_Class) {
                $objArray = (array)$bulkAdjSplitDiscountBy;
                foreach ($objArray as $k => $v) {
                    if ($k == '__PHP_Incomplete_Class_Name') {
                        continue;
                    }
                    $parts = explode(chr(0), $k);
                    $key = $parts[0] == $k ? $k : $parts[2];
                    if ($key == 'value') {
                        $row['bulk_adjustments']['split_discount_by'] = $v;
                        $changes['bulk_adjustments'] = serialize($row['bulk_adjustments']);
                        break;
                    }
                }
            }

            if (!empty($changes)) {
                $wpdb->update($table, $changes, array('id' => $row['id']));
            }
        }
    }

    public static function migrateSummaryTo_4_1_0()
    {
        $ruleRepository = new RuleRepository();

        foreach ($ruleRepository->getRules() as $rule) {
            $ruleRepository->storeRule($rule);
        }
    }

}

