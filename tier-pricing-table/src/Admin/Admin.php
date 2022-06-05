<?php

namespace TierPricingTable\Admin;

use  TierPricingTable\Admin\ProductManagers\ProductManager ;
use  TierPricingTable\Core\FileManager ;
use  TierPricingTable\Settings\Settings ;
use  TierPricingTable\TierPricingTablePlugin ;
use  TierPricingTable\Freemius ;
use  TierPricingTable\Admin\ProductManagers\SimpleProductManager ;
use  TierPricingTable\Admin\ProductManagers\VariationProductManager ;
use  TierPricingTable\Admin\Export\Woocommerce as WooCommerceExport ;
use  TierPricingTable\Admin\Import\Woocommerce as WooCommerceImport ;
use  TierPricingTable\Admin\Import\WPAllImport ;
/**
 * Class Admin
 *
 * @package TierPricingTable\Admin
 */
class Admin
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
     * Array of Managers
     *
     * @var array
     */
    private  $managers ;
    /**
     * /**
     * Admin constructor.
     *
     * Register menu items and handlers
     *
     * @param FileManager $fileManager
     * @param Settings $settings
     */
    public function __construct( FileManager $fileManager, Settings $settings )
    {
        $this->fileManager = $fileManager;
        $this->settings = $settings;
        $this->initManagers();
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueueAssets' ] );
        if ( get_transient( 'tier_pricing_table_activated' ) ) {
            add_action( 'admin_notices', [ $this, 'showActivationMessage' ] );
        }
        
        if ( !tpt_fs()->is_premium() ) {
            $template = 'upgrade-alert.php';
            add_action( 'woocommerce_settings_' . Settings::SETTINGS_PAGE, function () use( $template ) {
                $this->fileManager->includeTemplate( 'admin/alerts/' . $template, [
                    'upgradeUrl'   => tpt_fs_activation_url(),
                    'contactUsUrl' => admin_url( 'admin.php?page=tired-pricing-table-contact-us' ),
                ] );
                $this->fileManager->includeTemplate( 'admin/only-in-premium-script.php' );
            } );
        }
    
    }
    
    /**
     * Init Managers
     */
    public function initManagers()
    {
        $this->managers = array(
            ProductManager::class          => new ProductManager( $this->fileManager, $this->settings ),
            VariationProductManager::class => new VariationProductManager( $this->fileManager, $this->settings ),
            WooCommerceExport::class       => new WooCommerceExport(),
            WooCommerceImport::class       => new WooCommerceImport(),
            WPAllImport::class             => new WPAllImport(),
        );
    }
    
    /**
     * Show message about activation plugin and advise next step
     */
    public function showActivationMessage()
    {
        $link = $this->settings->getLink();
        $this->fileManager->includeTemplate( 'admin/alerts/activation-alert.php', [
            'link' => $link,
        ] );
        delete_transient( 'tiered_pricing_table_activated' );
    }
    
    /**
     * Register assets on product create/update page
     *
     * @param $page
     */
    public function enqueueAssets( $page )
    {
        global  $post, $page ;
        
        if ( isset( $_GET['tab'] ) && $_GET['tab'] == Settings::SETTINGS_PAGE || ($page == 'post.php' || ($page = 'post-new.php')) && $post && $post->post_type == 'product' ) {
            wp_enqueue_script(
                'tier-pricing-table-admin-js',
                $this->fileManager->locateAsset( 'admin/main.js' ),
                [ 'jquery' ],
                TierPricingTablePlugin::VERSION
            );
            wp_enqueue_style(
                'tier-pricing-table-admin-css',
                $this->fileManager->locateAsset( 'admin/style.css' ),
                array(),
                TierPricingTablePlugin::VERSION
            );
        }
    
    }

}