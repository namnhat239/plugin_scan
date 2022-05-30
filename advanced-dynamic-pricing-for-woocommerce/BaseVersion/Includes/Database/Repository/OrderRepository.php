<?php

namespace ADP\BaseVersion\Includes\Database\Repository;

use ADP\BaseVersion\Includes\Database\Models\Order;
use ADP\BaseVersion\Includes\Database\Models\Rule;

class OrderRepository implements OrderRepositoryInterface {
    public function addOrderStats($order)
    {
        global $wpdb;

        $table = $wpdb->prefix . Order::TABLE_NAME;
        $data = $order->getData();

        $data = array_merge(array(
            'order_id'         => 0,
            'rule_id'          => 0,
            'amount'           => 0.0,
            'extra'            => 0,
            'shipping'         => 0,
            'is_free_shipping' => 0,
            'gifted_amount'    => 0.0,
            'gifted_qty'       => 0,
            'date'             => current_time('mysql'),
        ), $data);

        $wpdb->replace($table, $data);
    }

    /**
     * @param $orderId
     *
     * @return array<int, array{order: Order, rule: Rule}>
     */
    public function getAppliedRulesForOrder($orderId)
    {
        global $wpdb;

        $table_order_rules = $wpdb->prefix . Order::TABLE_NAME;
        $table_rules       = $wpdb->prefix . Rule::TABLE_NAME;

        $sql = $wpdb->prepare("
            SELECT *
            FROM $table_order_rules LEFT JOIN $table_rules ON $table_order_rules.rule_id = $table_rules.id
            WHERE order_id = %d
            ORDER BY amount DESC
        ", $orderId);

        $rows = $wpdb->get_results($sql, ARRAY_A);

        $orderRules = array_map(function ($orderRule) {
            return [
                'order' => Order::fromArray($orderRule),
                'rule'  => Rule::fromArray($orderRule)
            ];
        }, $rows);

        return $orderRules;
    }

    public function getCountOfRuleUsages($ruleId)
    {
        global $wpdb;

        $tableOrderRules = $wpdb->prefix . Order::TABLE_NAME;

        $sql = $wpdb->prepare("
            SELECT COUNT(*)
            FROM {$tableOrderRules}
            WHERE rule_id = %d
        ", $ruleId);

        $value = $wpdb->get_var($sql);

        return (integer)$value;
    }

    public function getCountOfRuleUsagesPerCustomer($ruleId, $customerId)
    {
        global $wpdb;

        $tableOrderRules = $wpdb->prefix . Order::TABLE_NAME;

        $customerOrdersIds = get_posts(array(
            'fields'      => 'ids',
            'numberposts' => -1,
            'meta_key'    => '_customer_user',
            'meta_value'  => $customerId,
            'post_type'   => wc_get_order_types(),
            'post_status' => array_keys(wc_get_order_statuses()),
        ));
        if (empty($customerOrdersIds)) {
            return 0;
        }

        $value = $wpdb->get_var("SELECT COUNT(*) FROM {$tableOrderRules}
		            WHERE rule_id = $ruleId  AND order_id IN (" . implode(',', $customerOrdersIds) . ")");

        return (integer)$value;
    }
}
