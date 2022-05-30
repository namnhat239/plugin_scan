<?php

namespace ADP\BaseVersion\Includes\Core\RuleProcessor;

use ADP\BaseVersion\Includes\Core\Rule\Structures\FreeCartItemChoices;
use ADP\BaseVersion\Includes\Core\RuleProcessor\ProductStock\ProductStockController;
use ADP\BaseVersion\Includes\Enums\GiftChoiceMethodEnum;
use ADP\BaseVersion\Includes\Enums\GiftChoiceTypeEnum;

defined('ABSPATH') or exit;

class FreeCartItemChoicesSuitability
{
    /**
     * @param FreeCartItemChoices $cartItemChoices
     *
     * @return array<int,int>
     */
    protected function getMatchedProductsIds($cartItemChoices)
    {
        $includeIds = array();

        foreach ($cartItemChoices->getChoices() as $choice) {
            if ($choice->getType()->equals(GiftChoiceTypeEnum::PRODUCT())) {
                if ($choice->getMethod()->equals(GiftChoiceMethodEnum::IN_LIST())) {
                    $includeIds = array_merge($includeIds, $choice->getValues());
                }
            }
        }

        return $includeIds;
    }

    /**
     * @param FreeCartItemChoices $cartItemChoices
     * @param ProductStockController $ruleUsedStock
     * @param float $giftedCount
     *
     * @return array
     */
    public function getProductsSuitableToGift(
        $cartItemChoices,
        $ruleUsedStock,
        $giftedCount
    ) {
        $result = array();

        $productIds = $this->getMatchedProductsIds($cartItemChoices);
        $products   = array_values(
            array_filter(
                array_map(array("ADP\BaseVersion\Includes\Cache\CacheHelper", "getWcProduct"), $productIds)
            )
        );

        if ( count($products) === 0 ) {
            return array();
        }

        $giftQty           = $cartItemChoices->getRequiredQty();
        $initialProductQty = count($products);

        while ($giftQty > 0 && count($products) > 0) {
            $currentIndex = $giftedCount % $initialProductQty;

            if ( ! isset($products[$currentIndex])) {
                $giftedCount++;
                continue;
            }

            $currentProduct = $products[$currentIndex];
            $qtyToAdd = $ruleUsedStock->getQtyAvailableForSale($currentProduct->get_id(), 1, $currentProduct->get_parent_id());

            if ( isset($result[md5($currentProduct->get_id())]) ) {
                $result[md5($currentProduct->get_id())][1] += $qtyToAdd;
            } else {
                $result[md5($currentProduct->get_id())] = array($currentProduct->get_id(), $qtyToAdd, false);
            }

            if ($qtyToAdd === (float)0) {
                $result[md5($currentProduct->get_id())][2] = true;
                unset($products[$currentIndex]);
                $giftedCount++;
                continue;
            }

            $giftQty  -= $qtyToAdd;
            $giftedCount++;
        }

        return $result;
    }

    /**
     * @param FreeCartItemChoices $cartItemChoices
     * @param array $queryArgs
     *
     * @return array
     */
    public function getMatchedProductsGlobalQueryArgs($cartItemChoices, $queryArgs)
    {
        $includeIds = array();
        $excludeIds = array();

        foreach ($cartItemChoices->getChoices() as $choice) {
            if ($choice->getType()->equals(GiftChoiceTypeEnum::PRODUCT())) {
                if ($choice->getMethod()->equals(GiftChoiceMethodEnum::IN_LIST())) {
                    $includeIds = array_merge($includeIds, $choice->getValues());
                } elseif ($choice->getMethod()->equals(GiftChoiceMethodEnum::NOT_IN_LIST())) {
                    $excludeIds = array_merge($excludeIds, $choice->getValues());
                }
            }
        }

        $queryArgs['include'] = $includeIds;

        return $queryArgs;
    }

    /**
     * @param FreeCartItemChoices $cartItemChoices
     * @param \WC_Product $product
     *
     * @return bool
     */
    public function isProductMatched($cartItemChoices, $product)
    {
        if (count($cartItemChoices->getChoices()) === 0) {
            return false;
        }

        if ($product instanceof \WC_Product_Grouped) {
            return false;
        }

        $result = true;
        foreach ($cartItemChoices->getChoices() as $choice) {
            $choiceMatch = false;

            if ($choice->getType()->equals(GiftChoiceTypeEnum::PRODUCT())) {
                if ($choice->getMethod()->equals(GiftChoiceMethodEnum::IN_LIST())) {
                    $choiceMatch = in_array($product->get_id(), $choice->getValues(), true);
                }
            }

            $result &= $choiceMatch;
        }

        return $result;
    }
}
