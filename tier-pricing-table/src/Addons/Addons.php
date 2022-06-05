<?php

namespace TierPricingTable\Addons;

use  TierPricingTable\Addons\CategoryTiers\CategoryTierAddon ;
use  TierPricingTable\Addons\ManualOrders\ManualOrdersAddon ;
use  TierPricingTable\Addons\RoleBasedPricing\RoleBasedPricingAddon ;
use  TierPricingTable\Core\FileManager ;
use  TierPricingTable\Addons\MinQuantity\MinQuantity ;
use  TierPricingTable\Settings\Settings ;
class Addons
{
    /**
     * FileManager
     *
     * @var FileManager
     */
    private  $fileManager ;
    /**
     * Settings
     *
     * @var Settings
     */
    private  $settings ;
    /**
     * Addons constructor.
     *
     * @param FileManager $fileManger
     * @param Settings $settings
     */
    public function __construct( FileManager $fileManger, Settings $settings )
    {
        $this->fileManager = $fileManger;
        $this->settings = $settings;
        add_action( 'init', array( $this, 'init' ) );
    }
    
    public function init()
    {
        $addons = array(
            'CategoryTiers'    => new CategoryTierAddon( $this->fileManager, $this->settings ),
            'ManualOrders'     => new ManualOrdersAddon( $this->fileManager, $this->settings ),
            'RoleBasedPricing' => new RoleBasedPricingAddon( $this->fileManager, $this->settings ),
        );
        $addons = apply_filters( 'tier_pricing_table/addons/list', $addons );
        foreach ( $addons as $key => $addon ) {
            if ( $addon->isActive() ) {
                $addon->run();
            }
        }
    }

}