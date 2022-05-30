<?php

namespace ADP\BaseVersion\Includes\Core\Rule\PackageRule;

use ADP\BaseVersion\Includes\Core\Rule\Enums\ProductAdjustmentSplitDiscount;
use ADP\BaseVersion\Includes\Core\Rule\Structures\Discount;

defined('ABSPATH') or exit;

class ProductsAdjustmentTotal
{
    const AVAILABLE_DISCOUNT_TYPES = array(
        Discount::TYPE_AMOUNT,
        Discount::TYPE_FIXED_VALUE,
        Discount::TYPE_PERCENTAGE,
    );

    /**
     * @var Discount
     */
    protected $discount;

    /**
     * @var float
     */
    protected $maxAvailableAmount;

    /**
     * Coupon or Fee
     *
     * @var bool
     */
    protected $replaceAsCartAdjustment;

    /**
     * @var string
     */
    protected $replaceCartAdjustmentCode;

    /**
     * @var ProductAdjustmentSplitDiscount
     */
    protected $splitDiscount;

    /**
     * @param Discount $discount
     */
    public function __construct($discount)
    {
        if ($discount instanceof Discount && in_array($discount->getType(), self::AVAILABLE_DISCOUNT_TYPES)) {
            $this->discount = $discount;
        }
        $this->replaceAsCartAdjustment   = false;
        $this->replaceCartAdjustmentCode = null;
        $this->splitDiscount             = ProductAdjustmentSplitDiscount::ITEM_COST();
    }

    /**
     * @param Discount $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * @return Discount
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param float $value
     */
    public function setMaxAvailableAmount($value)
    {
        $value = floatval($value);

        $this->maxAvailableAmount = $value;
    }

    /**
     * @param bool $replace
     */
    public function setReplaceAsCartAdjustment($replace)
    {
        $this->replaceAsCartAdjustment = boolval($replace);
    }

    /**
     * @return bool
     */
    public function isMaxAvailableAmountExists()
    {
        return ! is_null($this->maxAvailableAmount);
    }

    /**
     * @return float|null
     */
    public function getMaxAvailableAmount()
    {
        return $this->maxAvailableAmount;
    }

    /**
     * @return bool
     */
    public function isReplaceWithCartAdjustment()
    {
        return $this->replaceCartAdjustmentCode && $this->replaceAsCartAdjustment;
    }

    /**
     * @param string $code
     */
    public function setReplaceCartAdjustmentCode($code)
    {
        $this->replaceCartAdjustmentCode = (string)$code;
    }

    /**
     * @return string
     */
    public function getReplaceCartAdjustmentCode()
    {
        return $this->replaceCartAdjustmentCode;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return ! is_null($this->discount);
    }

    /**
     * @param ProductAdjustmentSplitDiscount $splitDiscount
     */
    public function setSplitDiscount($splitDiscount)
    {
        if ($splitDiscount instanceof ProductAdjustmentSplitDiscount) {
            $this->splitDiscount = $splitDiscount;
        }
    }

    /**
     * @return ProductAdjustmentSplitDiscount
     */
    public function getSplitDiscount()
    {
        return $this->splitDiscount;
    }
}
