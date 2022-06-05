<?php

namespace Product_Gallery_Sldier;

class Bootstrap {
	public function __construct() {
		new Product();
		new Options();
		add_action( 'admin_enqueue_scripts', [$this, 'admin_scripts'], 90 );
		if ( time() > strtotime( get_option( 'ciwpgs_installed' ) . ' + 3 Days' ) ) {
			add_action( 'admin_notices', [$this, 'review'], 10 );
		}

		add_action( 'admin_init', [$this, 'wcpg_param_check'], 10 );
		add_action( 'wcpg_admin_top', [$this, 'pro_notice'], 10 );
		add_action( 'csf_options_after', array( $this, 'update_notice_option' ) );

		add_action( 'plugin_action_links_' . CIPG_FILE, [$this, 'wpgs_plugin_row_meta'], 90 );
	}
// notice for option page
	public function update_notice_option() {
		$currentScreen = get_current_screen();
		if ( $currentScreen->id == 'codeixer_page_cix-gallery-settings' ) {
			echo '<a class="cit-admin-pro-notice" href="http://codeixer.com/twist" target="_blank"><p>Missing anything? Discover more powerful features in the premium version now!</p> <span>I\'m interested</span></a>';
		}

	}
	// Output the product image before the single product summary.
	public function wpgs_product_thumbnails() {
		require_once CIPG_PATH . '/inc/product-thumbnails.php';
	}

	// Output the product image before the single product summary.
	public function wpgs_product_image() {
		require_once CIPG_PATH . '/inc/product-image.php';
	}
	/**
	 * Admin scripts/styles
	 *
	 * @return void
	 */
	public function admin_scripts() {
	}

	/**
	 * Leave Review Notice
	 *
	 * @return void
	 */
	public function review() {
		$dismiss_parm    = array( 'wcpg-review-dismiss' => '1' );
		$ciwg_maybelater = array( 'wcpg-later-dismiss' => '1' );

		if ( get_option( 'wcpg_plugin_review' ) || get_transient( 'wpgs-review-later' ) ) {
			return;
		}?>
        <div class="notice ciplugin-review">
        <p><img draggable="false" class="emoji" alt="🎉" src="https://s.w.org/images/core/emoji/11/svg/1f389.svg"><strong style="font-size: 19px; margin-bottom: 5px; display: inline-block;" ><?php echo __( 'Thanks for using Product gallery slider for WooCommerce.', 'wpgs' ); ?></strong><br> <?php _e( 'If you can spare a minute, please help us by leaving a 5 star review on WordPress.org.', 'wpgs' );?></p>
        <p class="dfwc-message-actions">
            <a style="margin-right:5px;" href="https://wordpress.org/support/plugin/woo-product-gallery-slider/reviews/#new-post" target="_blank" class="button button-primary button-primary"><?php _e( 'Happy To Help', 'wpgs' );?></a>
            <a style="margin-right:5px;" href="<?php echo esc_url( add_query_arg( $ciwg_maybelater ) ); ?>" class="button button-alt"><?php _e( 'Maybe later', 'wpgs' );?></a>
            <a href="<?php echo esc_url( add_query_arg( $dismiss_parm ) ); ?>" class="dfwc-button-notice-dismiss button button-link"><?php _e( 'Hide Notification', 'wpgs' );?></a>
        </p>
        </div>
        <?php
}
	public function pro_notice() {?>
        <div class="notice notice-success">
        <h3>Need More options for customize the gallery slider ?</h3>
        <p style="font-size:16px">Get fully customizable image gallery slider for the product page.<br> comes with vertical and horizontal gallery layouts, clicking, sliding, image navigation, fancybox 3 & many more exciting features.</p>
        <a href="https://codeixer.com/twist" target="_blank" class="button button-primary" style="margin-bottom:20px">Know more about the pro version</a>
        </div>
        <?php
}

	/**
	 * simple dismissable logic
	 *
	 * @return void
	 */
	public function wcpg_param_check() {
		if ( isset( $_GET['wcpg-review-dismiss'] ) && $_GET['wcpg-review-dismiss'] == 1 ) {
			update_option( 'wcpg_plugin_review', 1 );
		}
		if ( isset( $_GET['dfwc-banner'] ) && $_GET['dfwc-banner'] == 1 ) {
			update_option( 'dfwc-banner', 1 );
		}
		if ( isset( $_GET['wcpg-later-dismiss'] ) && $_GET['wcpg-later-dismiss'] == 1 ) {
			set_transient( 'wpgs-review-later', 1, 2 * DAY_IN_SECONDS );
		}
	}
}
