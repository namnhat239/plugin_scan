<?php

namespace ADP\BaseVersion\Includes\Core\Rule\Internationalization;

use ADP\BaseVersion\Includes\Enums\GiftChoiceTypeEnum;
use ADP\BaseVersion\Includes\Core\Rule\Rule;
use ADP\BaseVersion\Includes\Core\Rule\Structures\Discount;
use ADP\BaseVersion\Includes\Core\Rule\NoItemRule;
use ADP\BaseVersion\Includes\Core\Rule\SingleItemRule;
use ADP\BaseVersion\Includes\Core\Rule\PackageRule;
use ADP\BaseVersion\Includes\Core\Rule\PackageRule\ProductsAdjustmentSplit;
use ADP\BaseVersion\Includes\Core\Rule\PackageRule\ProductsAdjustmentTotal;
use ADP\BaseVersion\Includes\Core\Rule\SingleItemRule\ProductsAdjustment;

defined('ABSPATH') or exit;

class RuleTranslator
{
    /**
     * @param SingleItemRule|PackageRule|NoItemRule $rule
     * @param float $rate
     *
     * @return NoItemRule|PackageRule|SingleItemRule
     */
    public static function setCurrency($rule, $rate)
    {
        if ($rule->hasProductAdjustment()) {
            $productAdj = $rule->getProductAdjustmentHandler();
            if ($productAdj instanceof ProductsAdjustment or
                $productAdj instanceof ProductsAdjustmentTotal) {
                if ($productAdj->isMaxAvailableAmountExists()) {
                    $productAdj->setMaxAvailableAmount($productAdj->getMaxAvailableAmount() * $rate);
                }
                $discount = $productAdj->getDiscount();
                if ($discount->getType() !== Discount::TYPE_PERCENTAGE) {
                    $discount->setValue($discount->getValue() * $rate);
                }
                $productAdj->setDiscount($discount);
            } elseif ($productAdj instanceof ProductsAdjustmentSplit) {
                $discounts = $productAdj->getDiscounts();
                foreach ($discounts as $discount) {
                    if ($discount->getType() !== Discount::TYPE_PERCENTAGE) {
                        $discount->setValue($discount->getValue() * $rate);
                    }
                }
                $productAdj->setDiscounts($discounts);
            }

            $rule->installProductAdjustmentHandler($productAdj);
        }

        if ($rule->hasProductRangeAdjustment()) {
            $productAdj = $rule->getProductRangeAdjustmentHandler();
            $ranges     = $productAdj->getRanges();
            foreach ($ranges as &$range) {
                $discount = $range->getData();
                if ($discount->getType() !== Discount::TYPE_PERCENTAGE) {
                    $discount->setValue($discount->getValue() * $rate);
                    $range->setData($discount);
                }
            }
            $productAdj->setRanges($ranges);

            $rule->installProductRangeAdjustmentHandler($productAdj);
        }

        $roleDiscounts = array();
        if ($rule->getRoleDiscounts() !== null) {
            foreach ($rule->getRoleDiscounts() as $roleDiscount) {
                $discount = $roleDiscount->getDiscount();
                if ($discount->getType() !== Discount::TYPE_PERCENTAGE) {
                    $discount->setValue($discount->getValue() * $rate);
                }
                $roleDiscount->setDiscount($discount);
                $roleDiscounts[] = $roleDiscount;
            }
            $rule->setRoleDiscounts($roleDiscounts);
        }

        if ($rule->getCartAdjustments()) {
            $cartAdjs = $rule->getCartAdjustments();
            foreach ($cartAdjs as $cartAdjustment) {
                $cartAdjustment->multiplyAmounts($rate);
            }
            $rule->setCartAdjustments($cartAdjs);
        }

        if ($rule->getConditions()) {
            $cartConditions = $rule->getConditions();
            foreach ($cartConditions as $cart_condition) {
                $cart_condition->multiplyAmounts($rate);
            }
            $rule->setConditions($cartConditions);
        }

        if ($rule instanceof SingleItemRule || $rule instanceof PackageRule) {
            $rule->setItemGiftSubtotalDivider($rule->getItemGiftSubtotalDivider() * $rate);
        }

        return $rule;
    }

    /**
     * @param Rule $rule
     * @param string $languageCode
     *
     * @return Rule
     */
    public static function translate($rule, $languageCode)
    {
        $filterTranslator = new FilterTranslator();

        if ($rule instanceof SingleItemRule) {
            $filters = array();
            foreach ($rule->getFilters() as $filter) {
                $filter->setValue(
                    $filterTranslator->translateByType(
                        $filter->getType(),
                        $filter->getValue(),
                        $languageCode
                    )
                );

                $filter->setExcludeProductIds(
                    $filterTranslator->translateProduct(
                        $filter->getExcludeProductIds(),
                        $languageCode
                    )
                );

                $filters[] = $filter;
            }
            $rule->setFilters($filters);
        } elseif ($rule instanceof PackageRule) {
            $packages = array();
            foreach ($rule->getPackages() as $package) {
                $filters = array();
                foreach ($package->getFilters() as $filter) {
                    $filter->setValue(
                        $filterTranslator->translateByType(
                            $filter->getType(),
                            $filter->getValue(),
                            $languageCode
                        )
                    );

                    $filter->setExcludeProductIds(
                        $filterTranslator->translateProduct(
                            $filter->getExcludeProductIds(),
                            $languageCode
                        )
                    );

                    $filters[] = $filter;
                }
                $package->setFilters($filters);
                $packages[] = $package;
            }
            $rule->setPackages($packages);
        }

        if ($rule instanceof SingleItemRule || $rule instanceof PackageRule) {
            if ($rule->hasProductRangeAdjustment()) {
                $productAdj = $rule->getProductRangeAdjustmentHandler();
                $productAdj->setSelectedProductIds(
                    $filterTranslator->translateProduct(
                        $productAdj->getSelectedCategoryIds(),
                        $languageCode
                    )
                );

                $productAdj->setSelectedCategoryIds(
                    $filterTranslator->translateCategory(
                        $productAdj->getSelectedCategoryIds(),
                        $languageCode
                    )
                );

                $rule->installProductRangeAdjustmentHandler($productAdj);
            }

            foreach ($rule->getItemGiftsCollection()->asArray() as $gift) {
                foreach ($gift->getChoices() as $choice) {
                    if ($choice->getType()->equals(GiftChoiceTypeEnum::PRODUCT())) {
                        $choice->setValues($filterTranslator->translateProduct($choice->getValues(), $languageCode));
                    }

                    if ($choice->getType()->equals(GiftChoiceTypeEnum::CATEGORY())) {
                        $choice->setValues($filterTranslator->translateCategory($choice->getValues(), $languageCode));
                    }
                }
            }
        }

        $cartConditions = array();
        foreach ($rule->getConditions() as $cartCondition) {
            $cartCondition->translate($languageCode);
            $cartConditions[] = $cartCondition;
        }
        $rule->setConditions($cartConditions);

        return $rule;
    }
}
