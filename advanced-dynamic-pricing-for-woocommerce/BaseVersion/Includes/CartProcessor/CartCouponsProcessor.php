<?php

namespace ADP\BaseVersion\Includes\CartProcessor;

use ADP\BaseVersion\Includes\Core\Cart\Cart;
use ADP\BaseVersion\Includes\Core\Cart\CartContext;
use ADP\BaseVersion\Includes\Core\Cart\CouponInterface;
use ADP\BaseVersion\Includes\Core\Cart\CouponCart;
use ADP\BaseVersion\Includes\Core\Cart\Coupon;
use ADP\BaseVersion\Includes\Context;
use ADP\BaseVersion\Includes\Core\Cart\CouponsAdjustment;
use ADP\BaseVersion\Includes\Core\Cart\DisableAllWcCouponsCart;
use ADP\BaseVersion\Includes\Core\Cart\DisableWcCouponsCart;
use ADP\BaseVersion\Includes\Core\Cart\WcCouponCart;
use ADP\BaseVersion\Includes\WC\PriceFunctions;
use ADP\BaseVersion\Includes\WC\WcCartItemFacade;
use ADP\BaseVersion\Includes\WC\WcCouponFacade;
use ADP\BaseVersion\Includes\WC\WcCustomerSessionFacade;
use WC_Cart;
use WC_Coupon;

defined('ABSPATH') or exit;

class CartCouponsProcessor
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var array<string, array<int, CouponInterface>>
     */
    protected $groupedCoupons;

    /**
     * @var array<string, CouponInterface>
     */
    protected $singleCoupons;

    /**
     * @var array<string, CouponInterface>
     */
    protected $wcSingleCoupons;

    /**
     * @var array<string, CouponsAdjustment>
     */
    protected $disabledWcCoupons;

    /**
     * @var bool
     */
    protected $disableAllWcCoupons;

    /**
     * @var array<int, WcCouponFacade>
     */
    protected $readyCouponData;

    /**
     * @var CartContext
     */
    protected $cartContext;

    public function __construct($deprecated = null)
    {
        $this->context = adp_context();
        $this->purge();
    }

    public function withContext(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param Cart $cart
     */
    public function refreshCoupons($cart)
    {
        $context = $cart->getContext();
        $this->purge();

        foreach ($cart->getCoupons() as $coupon) {
            $coupon = clone $coupon;

            if (empty($coupon->getCode())) {
                continue;
            }

            if ($coupon instanceof CouponCart && $coupon->getValue()) {
                if ($coupon->isType($coupon::TYPE_FIXED_VALUE)) {
                    if ($context->isCombineMultipleDiscounts()) {
                        $coupon->setCode($context->getOption('default_discount_name'));
                    }
                    $this->addGroupCoupon($coupon);
                } elseif ($coupon->isType($coupon::TYPE_PERCENTAGE)) {
                    $this->addSingleCoupon($coupon);
                }
            } elseif ($coupon instanceof Coupon && $coupon->getValue()) {
                if ($context->isCombineMultipleDiscounts()) {
                    $coupon->setCode($context->getOption('default_discount_name'));
                    $this->addGroupCoupon($coupon);
                } else {
                    $this->addGroupCoupon($coupon);
                }
            } elseif ($coupon instanceof WcCouponCart) {
                $this->wcSingleCoupons[$coupon->getCode()] = $coupon;
            }
        }

        // remove postfix for single %% discount
        if (count($this->singleCoupons) == 1) {
            $coupon = reset($this->singleCoupons);
            $coupon->setCode(str_replace(' #1', '', $coupon->getCode()));
            $this->singleCoupons = array($coupon->getCode() => $coupon);
        }

        foreach ($cart->getCouponsAdjustments() as $coupon) {
            if ($coupon instanceof DisableWcCouponsCart) {
                $this->disabledWcCoupons[] = $coupon->getCode();
            } elseif ( $coupon instanceof DisableAllWcCouponsCart ) {
                $this->disableAllWcCoupons = true;
            }
        }

        $this->cartContext = $cart->getContext();
    }

    /**
     * @param WC_Cart $wcCart
     */
    public function applyCoupons(&$wcCart)
    {
        $couponCodesToApply = array_merge(
            array_keys($this->groupedCoupons),
            array_keys($this->singleCoupons)
        );

        $appliedCoupons = $wcCart->applied_coupons;
        $discounts = new \WC_Discounts( $wcCart );

        foreach (array_keys($this->wcSingleCoupons) as $couponCode) {
            $coupon = new WC_Coupon($couponCode);

            if ($discounts->is_coupon_valid($coupon) !== true) {
                continue;
            }

            /**
             * @see WC_Cart::apply_coupon
             */
            if ($coupon->get_individual_use() && count($appliedCoupons) > 0) {
                continue;
            }

            /**
             * Check to see if an individual use coupon is set.
             * @see WC_Cart::apply_coupon
             */
            $allowWithIndividual = true;
            foreach ($appliedCoupons as $code) {
                $coupon = new WC_Coupon($code);

                if ($coupon->get_individual_use() && false === apply_filters(
                        'woocommerce_apply_with_individual_use_coupon',
                        false,
                        $coupon,
                        $coupon,
                        $appliedCoupons
                    )) {
                    $allowWithIndividual = false;
                    break;
                }
            }
            if ( ! $allowWithIndividual) {
                continue;
            }


            if ( ! in_array($couponCode, $appliedCoupons, true)) {
                $appliedCoupons[] = $couponCode;
            }
        }

        $appliedCoupons = array_filter($appliedCoupons, function ($couponCode) {
            return ! in_array($couponCode, $this->disabledWcCoupons, true);
        });

        if ( $this->disableAllWcCoupons === true ) {
            $appliedCoupons = [];
        }

        foreach ($couponCodesToApply as $couponCode) {
            if ( ! in_array($couponCode, $appliedCoupons, true)) {
                $appliedCoupons[] = $couponCode;
            }
        }

        $wcCart->applied_coupons = array_unique($appliedCoupons);
        $this->prepareCouponsData($wcCart);
    }

    /**
     * @param WC_Cart $wcCart
     */
    public function sanitize(&$wcCart)
    {
        $appliedCoupons = $wcCart->applied_coupons;
        $adpCoupons     = (new WcCustomerSessionFacade(WC()->session))->getAdpCoupons();

        foreach ($appliedCoupons as $index => $couponCode) {
            if (in_array($couponCode, $adpCoupons, true)) {
                unset($appliedCoupons[$index]);
            }
        }

        $wcCart->applied_coupons = array_values($appliedCoupons);
        $this->purge();
    }

    public function setFilterToInstallCouponsData()
    {
        add_filter('woocommerce_get_shop_coupon_data', array($this, 'getCouponData'), 10, 3);
    }

    public function unsetFilterToInstallCouponsData()
    {
        remove_filter('woocommerce_get_shop_coupon_data', array($this, 'getCouponData'), 10);
    }

    public function setFiltersToSupportPercentLimitCoupon()
    {
        add_filter('woocommerce_coupon_discount_types', array($this, 'addPercentLimitCouponDiscountType'), 10, 1);
        add_filter('woocommerce_product_coupon_types', array($this, 'addPercentLimitCouponProductType'), 10, 1);
        add_filter('woocommerce_coupon_get_discount_amount', array($this, 'getPercentLimitCouponDiscountAmount'), 10,
            5);
        add_filter('woocommerce_coupon_custom_discounts_array', array($this, 'processPercentLimitCoupon'), 10, 2);
    }

    public function unsetFiltersToSupportPercentLimitCoupon()
    {
        remove_filter('woocommerce_coupon_discount_types', array($this, 'addPercentLimitCouponDiscountType'), 10);
        remove_filter('woocommerce_product_coupon_types', array($this, 'addPercentLimitCouponProductType'), 10);
        remove_filter('woocommerce_coupon_get_discount_amount', array($this, 'getPercentLimitCouponDiscountAmount'),
            10);
    }

    public function setFiltersToSupportExactItemApplicationOfReplacementCoupon()
    {
        if ( ! $this->context->isAllowExactApplicationOfReplacementCoupon()) {
            return;
        }

        add_filter('woocommerce_coupon_get_discount_amount',
            array($this, 'getExactItemApplicationCouponDiscountAmount'), 10, 5);
        add_filter('woocommerce_cart_coupon_types', array($this, 'addExactItemApplicationCouponCartType'), 10, 1);
        add_filter('woocommerce_coupon_discount_types', array($this, 'addExactItemApplicationCouponDiscountType'), 10,
            1);
    }

    public function unsetFiltersToSupportExactItemApplicationOfReplacementCoupon()
    {
        remove_filter('woocommerce_coupon_get_discount_amount',
            array($this, 'getExactItemApplicationCouponDiscountAmount'), 10);
        remove_filter('woocommerce_cart_coupon_types', array($this, 'addExactItemApplicationCouponCartType'), 10);
        remove_filter('woocommerce_coupon_discount_types', array($this, 'addExactItemApplicationCouponDiscountType'),
            10);
    }

    public function setFilterToSuppressDisabledWcCoupons()
    {
        add_filter('woocommerce_coupon_is_valid', array($this, 'hookDisabledCouponsIsValidForCart'), 10, 3);
    }

    public function unsetFilterToSuppressDisabledWcCoupons()
    {
        remove_filter('woocommerce_coupon_is_valid', array($this, 'hookDisabledCouponsIsValidForCart'), 10);
    }

    /**
     * This filter allows custom coupon objects to be created on the fly.
     *
     * @param false $couponData
     * @param mixed $couponCode Coupon code
     * @param WC_Coupon $wcCoupon
     *
     * @return array|mixed
     */
    public function getCouponData($couponData, $couponCode, $wcCoupon)
    {
        if (isset($this->readyCouponData[$couponCode])) {
            $readyCouponFacade = $this->readyCouponData[$couponCode];
            $parts             = $readyCouponFacade->getParts();

            $wcCouponFacade = new WcCouponFacade($this->context, $wcCoupon);
            $wcCouponFacade->setParts($parts);
            $wcCouponFacade->updateCoupon();

            /**
             * Return $couponData as is, so it does not become virtual.
             * But push parts anyway.
             */
            if (count($parts) === 1 && $parts[0] instanceof WcCouponCart) {
                return $couponData;
            }

            $couponData = array(
                'discount_type' => $readyCouponFacade->coupon->get_discount_type('edit'),
                'amount'        => $readyCouponFacade->coupon->get_amount('edit'),
            );

            //support max discount for percentage coupon
            if ($coupon = reset($parts)) {
                /** @var CouponCart $coupon */
                if ($couponData['discount_type'] === WcCouponFacade::TYPE_PERCENT && $coupon->isMaxDiscountDefined()) {
                    $couponData['discount_type'] = WcCouponFacade::TYPE_CUSTOM_PERCENT_WITH_LIMIT;
                }
            }
        }

        return $couponData;
    }

    /**
     * @param array<string, string> $discountTypes
     *
     * @return array<string, string>
     */
    public function addPercentLimitCouponDiscountType($discountTypes)
    {
        $discountTypes[WcCouponFacade::TYPE_CUSTOM_PERCENT_WITH_LIMIT] = __('WDP Coupon',
            'advanced-dynamic-pricing-for-woocommerce');

        return $discountTypes;
    }

    /**
     * @param array<string, string> $discountTypes
     *
     * @return array<string, string>
     */
    public function addPercentLimitCouponProductType($discountTypes)
    {
        $discountTypes[] = WcCouponFacade::TYPE_CUSTOM_PERCENT_WITH_LIMIT;

        return $discountTypes;
    }

    /**
     * @param array<string, string> $discountTypes
     *
     * @return array<string, string>
     */
    public function addExactItemApplicationCouponDiscountType($discountTypes)
    {
        $discountTypes[WcCouponFacade::TYPE_ADP_FIXED_CART_ITEM] = __('WDP Coupon (exact item application)',
            'advanced-dynamic-pricing-for-woocommerce');

        return $discountTypes;
    }

    /**
     * @param array<int, string> $discountTypes
     *
     * @return array<int, string>
     */
    public function addExactItemApplicationCouponCartType($discountTypes)
    {
        $discountTypes[] = WcCouponFacade::TYPE_ADP_FIXED_CART_ITEM;

        return $discountTypes;
    }

    /**
     * @param float $discountAmount
     * @param float $discountingAmount
     * @param array $cartItem
     * @param bool $single
     * @param WC_Coupon $coupon
     *
     * @return float
     */
    public function getPercentLimitCouponDiscountAmount(
        $discountAmount,
        $discountingAmount,
        $cartItem,
        $single,
        $coupon
    ) {
        if ($coupon->get_discount_type() === WcCouponFacade::TYPE_CUSTOM_PERCENT_WITH_LIMIT) {
            $discountAmount = (float)$coupon->get_amount() * ($discountingAmount / 100);
        }

        return $discountAmount;
    }

    /**
     * @param array<int, float> $couponDiscounts
     * @param \WC_Coupon $coupon
     *
     * @return array<int, float>
     */
    public function processPercentLimitCoupon($couponDiscounts, $coupon)
    {
        if ($coupon->get_discount_type() === WcCouponFacade::TYPE_CUSTOM_PERCENT_WITH_LIMIT) {
            $coupon_code    = $coupon->get_code();
            $wdpCoupon      = $this->singleCoupons[$coupon_code];
            $discountAmount = array_sum($couponDiscounts);

            $maxDiscount = $wdpCoupon->getMaxDiscount() * pow(10, wc_get_price_decimals());
            if ($discountAmount > $maxDiscount) {
                $itemDiscount = round((float)$maxDiscount / count($couponDiscounts));
                $k            = 0;
                foreach ($couponDiscounts as $key => $discount) {
                    if ($k >= count($couponDiscounts) - 1) {
                        $couponDiscounts[$key] = $maxDiscount - $itemDiscount * $k;
                        break;
                    }
                    $couponDiscounts[$key] = $itemDiscount;
                    $k++;
                }
            }
        }

        return $couponDiscounts;
    }

    /**
     * @param WC_Cart $wcCart
     */
    public function updateTotals($wcCart)
    {
        $this->cartContext->getSession()->insertCouponsData(
            $this->groupedCoupons,
            $this->singleCoupons,
            $this->wcSingleCoupons
        );
    }

    /**
     * @param float $discountAmount
     * @param float $discountingAmount
     * @param array $cartItem
     * @param bool $single
     * @param WC_Coupon $wcCoupon
     *
     * @return float
     */
    public function getExactItemApplicationCouponDiscountAmount(
        $discountAmount,
        $discountingAmount,
        $cartItem,
        $single,
        $wcCoupon
    ) {
        $facade       = new WcCartItemFacade($this->context, $cartItem);
        $couponFacade = new WcCouponFacade($this->context, $wcCoupon);

        /** @var Coupon[] $coupons */
        $coupons = array_filter($couponFacade->getParts(), function ($coupon) {
            return $coupon instanceof Coupon;
        });

        if (count($coupons) === 0) {
            return $discountAmount;
        }

        $newAmount = floatval(0);
        foreach ($coupons as $coupon) {
            if ($coupon->getAffectedCartItemKey() === $facade->getKey() || $coupon->getAffectedCartItemKey() === $facade->getOriginalKey()) {
                $newAmount += $coupon->getValue();
            }
        }

        if ($newAmount > 0) {
            $priceFunctions = new PriceFunctions($this->context);
            $args           = array('price' => $newAmount);
            if ($this->context->getIsPricesIncludeTax()) {
                $newAmount = $priceFunctions->getPriceIncludingTax($facade->getProduct(), $args);
            } else {
                $newAmount = $priceFunctions->getPriceExcludingTax($facade->getProduct(), $args);
            }

            $newAmount = round($newAmount, wc_get_rounding_precision());
        }

        return $newAmount;
    }


    /**
     * @param bool          $valid
     * @param \WC_Coupon    $coupon
     * @param \WC_Discounts $wcDiscounts
     *
     * @return bool
     */
    public function hookDisabledCouponsIsValidForCart($valid, $coupon, $wcDiscounts)
    {
        $customAdpCoupons = (new WcCustomerSessionFacade(WC()->session))->getCustomAdpCoupons();

        if ($this->disableAllWcCoupons === true && ! in_array($coupon->get_code(), $customAdpCoupons, true)) {
            throw new \Exception(
                __('Sorry, this coupon is not applicable to cart.', 'advanced-dynamic-pricing-for-woocommerce')
            );
        }

        if (in_array($coupon->get_code(), $this->disabledWcCoupons, true)) {
            throw new \Exception(
                __('Sorry, this coupon is not applicable to cart.', 'advanced-dynamic-pricing-for-woocommerce')
            );
        }

        return $valid;
    }

    /**
     * @param WC_Cart $wcCart
     */
    protected function prepareCouponsData($wcCart)
    {
        foreach ($this->groupedCoupons as $couponCode => $coupons) {
            $coupon_type = WcCouponFacade::TYPE_FIXED_CART;
            $amount      = floatval(0);

            $appliedCoupons = array();
            foreach ($coupons as $coupon) {
                if ($coupon instanceof CouponCart) {
                    if ($coupon->isType($coupon::TYPE_FIXED_VALUE)) {
                        $amount           += $coupon->getValue();
                        $appliedCoupons[] = $coupon;
                    }
                } elseif ($coupon instanceof Coupon) {
                    $amount += $coupon->getValue() * $coupon->getAffectedCartItemQty();
                    if ($this->context->isAllowExactApplicationOfReplacementCoupon()) {
                        $coupon_type = WcCouponFacade::TYPE_ADP_FIXED_CART_ITEM;
                    }
                    $appliedCoupons[] = $coupon;
                }
            }

            if ($amount > 0) {
                $this->addReadyCouponData($couponCode, $coupon_type, $amount, $appliedCoupons);
            }
        }

        foreach ($this->singleCoupons as $coupon) {
            $coupon_type = WcCouponFacade::TYPE_FIXED_CART;

            if ($coupon instanceof CouponCart) {
                if ($coupon->isType($coupon::TYPE_PERCENTAGE)) {
                    $coupon_type = WcCouponFacade::TYPE_PERCENT;
                }
            } elseif ($coupon instanceof Coupon) {
                $coupon_type = WcCouponFacade::TYPE_FIXED_PRODUCT;
            }

            $this->addReadyCouponData($coupon->getCode(), $coupon_type, $coupon->getValue(), array($coupon));
        }

        foreach ($this->wcSingleCoupons as $coupon) {
            $code = $coupon->getCode();

            if (isset($this->readyCouponData[$code])) {
                continue;
            }

            $couponFacade = new WcCouponFacade($this->context, new \WC_Coupon($code));
            $couponFacade->setParts([$coupon]);

            $this->readyCouponData[$code] = $couponFacade;
        }
    }

    /**
     * @param string $code
     * @param string $type
     * @param float $amount
     * @param array<int, CouponInterface> $parts
     */
    protected function addReadyCouponData($code, $type, $amount, $parts)
    {
        if (isset($this->readyCouponData[$code])) {
            return;
        }

        $couponFacade = new WcCouponFacade($this->context, new \WC_Coupon());
        $couponFacade->coupon->set_virtual(true);
        $couponFacade->coupon->set_code($code);
        $couponFacade->coupon->set_discount_type($type);
        $couponFacade->coupon->set_amount($amount);
        $couponFacade->setParts($parts);

        $this->readyCouponData[$code] = $couponFacade;
    }

    /**
     * @param CouponInterface $coupon
     */
    protected function addGroupCoupon($coupon)
    {
        if ( ! isset($this->groupedCoupons[$coupon->getCode()])) {
            $this->groupedCoupons[$coupon->getCode()] = array();
        }

        $this->groupedCoupons[$coupon->getCode()][] = $coupon;
    }

    /**
     * @param CouponInterface $coupon
     */
    protected function addSingleCoupon($coupon)
    {
        if ( ! isset($this->singleCoupons[$coupon->getCode()])) {
            $this->singleCoupons[$coupon->getCode()] = $coupon;

            return;
        }

        // add "#1" to the end of the coupon label once
        $firstCoupon = $this->singleCoupons[$coupon->getCode()];
        if (strpos($firstCoupon->getLabel(), "#1") === false) {
            $firstCoupon->setLabel(sprintf("%s #%s", $coupon->getCode(), 1));
        }

        $count = 1;
        do {
            $couponCode  = sprintf("%s_%s", $coupon->getCode(), $count);
            $couponLabel = sprintf("%s #%s", $coupon->getCode(), $count + 1);
            $count++;
        } while (isset($this->singleCoupons[$couponCode]));

        $coupon->setCode($couponCode);
        $coupon->setLabel($couponLabel);
        $this->singleCoupons[$coupon->getCode()] = $coupon;
    }

    protected function purge()
    {
        $this->groupedCoupons      = array();
        $this->singleCoupons       = array();
        $this->readyCouponData     = array();
        $this->wcSingleCoupons     = array();
        $this->disabledWcCoupons   = array();
        $this->disableAllWcCoupons = false;
    }
}
