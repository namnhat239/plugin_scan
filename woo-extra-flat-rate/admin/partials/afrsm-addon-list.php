<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
?>
<div class="afrsm-section-left">
    <div class="afrsm-main-table res-cl">
        <h2><?php esc_html_e( 'Add-On list for the', 'advanced-flat-rate-shipping-for-woocommerce' ); ?> <?php echo esc_html(AFRSM_PRO_PLUGIN_NAME); ?></h2>
        <table class="table-outer">
            <tbody>
                <tr>
                    <td>
                        <p class="block addon-name"><?php echo esc_html('WC Vendors Marketplace Addon for Flat Rate Shipping Plugin'); ?></p>
                        <a href="#" class="button"><?php esc_html_e( 'View details', 'advanced-flat-rate-shipping-for-woocommerce'); ?></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<button id="purchase">Buy Button</button>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://checkout.freemius.com/checkout.min.js"></script>
<script>
    var handler = FS.Checkout.configure({
        plugin_id:  '9639',
        plan_id:    '16223',
        public_key: 'pk_47b89ed59d4df53740bd005ee2ee6',
        image:      'https://your-plugin-site.com/logo-100x100.png'
    });
    
    $('#purchase').on('click', function (e) {
        handler.open({
            name     : 'WC Vendors Marketplace Addon for Flat Rate Shipping Plugin',
            licenses : 1,
            // You can consume the response for after purchase logic.
            purchaseCompleted  : function (response) {
                // The logic here will be executed immediately after the purchase confirmation.                                // alert(response.user.email);
            },
            success  : function (response) {
                // The logic here will be executed after the customer closes the checkout, after a successful purchase.                                // alert(response.user.email);
            }
        });
        e.preventDefault();
    });
</script>
<?php require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php' ); ?>