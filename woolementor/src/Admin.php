<?php
/**
 * All admin facing functions
 */
namespace Codexpert\Woolementor;
use Codexpert\Plugin\Base;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Admin
 * @author Codexpert <hi@codexpert.io>
 */
class Admin extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->server	= $this->plugin['server'];
		$this->version	= $this->plugin['Version'];
		$this->assets 	= WOOLEMENTOR_ASSETS;
	}

	/**
	 * Installer. Runs once when the plugin in activated.
	 *
	 * @since 1.0
	 */
	public function install() {

		if( ! get_option( 'woolementor_version' ) ){
			update_option( 'woolementor_version', $this->version );
		}
		
		if( ! get_option( 'woolementor_install_time' ) ){
			update_option( 'woolementor_install_time', time() );
		}
	}

	/**
	 * Internationalization
	 */
	public function i18n() {
		load_plugin_textdomain( 'woolementor', false, WOOLEMENTOR_DIR . '/languages/' );
	}

	/**
	 * Setup the instance
	 *
	 * @since 1.0
	 */
	public function setup() {
		add_image_size( 'woolementor-thumb', 400, 400, true );
	}

	/**
	 * Add some script to head
	 *
	 * @since 1.0
	 */
	public function head() {}

	public function enqueue_scripts( $screen ) {

		// Are we in debug mode?
		$min = defined( 'WOOLEMENTOR_DEBUG' ) && WOOLEMENTOR_DEBUG ? '' : '.min';
		
		// vendors
		if( $screen == 'toplevel_page_woolementor' ) {
			wp_enqueue_style( 'gogole-fonts', 'https://fonts.googleapis.com/css2?family=Nunito&family=Poppins&display=swap', '', $this->version, 'all' );
			
			// enqueue stylesheet
			wp_register_style( "{$this->slug}-email-designer", "{$this->assets}/css/email-designer{$min}.css", '', $this->version, 'all' );
			wp_enqueue_style( "{$this->slug}-pro-features", "{$this->assets}/css/pro-features{$min}.css", '', $this->version, 'all' );
			wp_enqueue_style( "{$this->slug}-widgets-settings", "{$this->assets}/css/widgets-settings{$min}.css", '', $this->version, 'all' );

			// enqueue JavaScript
			wp_enqueue_script( "{$this->slug}-widgets-settings", "{$this->assets}/js/widgets-settings{$min}.js", array( 'jquery' ), $this->version, true );
		}
		
		wp_register_style( 'woolementor-library', WOOLEMENTOR_ASSETS . '/css/library.css', [ 'dashicons' ], time() );
		wp_enqueue_style( $this->slug, "{$this->assets}/css/admin{$min}.css", '', $this->version, 'all' );

		wp_enqueue_script( $this->slug, "{$this->assets}/js/admin{$min}.js", array( 'jquery' ), $this->version, true );

		$localized = array(
			'ajaxurl'	=> admin_url( 'admin-ajax.php' ),
            'nonce' 	=> wp_create_nonce( 'woolementor' ),
		);
		wp_localize_script( $this->slug, 'WOOLEMENTOR', apply_filters( "{$this->slug}-localized", $localized ) );
	}

	public function action_links( $links ) {

		$new_links = [
			'settings'	=> sprintf( '<a href="%1$s">' . __( 'Settings', 'woolementor' ) . '</a>', add_query_arg( 'page', $this->slug, admin_url( 'admin.php' ) ) )
		];
		
		return array_merge( $new_links, $links );
	}

	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		
		if ( $this->plugin['basename'] === $plugin_file && ! wcd_is_pro() ) {
			$plugin_meta['help'] = '<a href="https://codexpert.io/codesigner/?utm_campaign=plugins-page" target="_blank" class="wl-plugins-upgrade">' . __( 'More Features', 'woolementor' ) . '</a>';
		}

		return $plugin_meta;
	}

	public function footer_text( $text ) {
		if( get_current_screen()->parent_base != $this->slug ) return $text;

		return sprintf( __( 'If you like <strong>%1$s</strong>, please <a href="%2$s" target="_blank">leave us a %3$s rating</a> on WordPress.org! It\'d motivate and inspire us to make the plugin even better!', 'woolementor' ), $this->name, "https://wordpress.org/support/plugin/{$this->slug}/reviews/?filter=5#new-post", '⭐⭐⭐⭐⭐' );
	}

	public function settings_page_redirect(){
    	if( get_option( 'woolementor-activated' ) != 1 ) {
			update_option( 'woolementor-activated', 1 );
    		wp_safe_redirect( admin_url( 'admin.php?page=woolementor' ) );
			exit();
    	}
	}

	public function admin_notices() {
		
		if( ! current_user_can( 'manage_options' ) || wcd_is_pro() ) return;

		$notice_key = "_{$this->slug}_notices-dismissed";
		/**
		 * Promotional banners
		 */
		$banners = [

			// Regular promotion. Shows on 1st to 7th of every month.
			/*
			date( 'F' ) . '-deal'	=> [
				'name'	=> __( 'CoDesigner', 'woolementor' ),
				'url'	=> 'https://codexpert.io/codesigner/',
				'type'	=> 'text',
				'text'	=> sprintf(
					__( 'Use promo code <strong>"%s"</strong> and get 20%% off <strong>CoDesigner Pro</strong>. Offer ends in %d days.. <button class="button button-primary">Grab The Deal Now</button>', 'woolementor' ),
					'Super20',
					( strtotime( date( '2022-m-17 23:59:59' ) ) - time() ) / DAY_IN_SECONDS
				),
				'from'	=> strtotime( date( '2022-m-01 23:59:59' ) ),
				'to'	=> strtotime( date( '2022-m-07 23:59:59' ) ),
			],
			*/
			
			// WordPress birthday
			'wpbday2022'	=> [
				'name'	=> __( 'CoDesigner', 'woolementor' ),
				'url'	=> 'https://codexpert.io/codesigner/',
				'type'	=> 'text',
				'text'	=> sprintf(
					__( '🥳 Happy <strong>19th Birthday</strong> to WordPress! Use promo code <strong>"%s"</strong> and get a flat 35%% off <strong>CoDesigner Pro</strong>. Offer ends in %d days.. <button class="button button-primary">Grab The Deal Now</button>', 'woolementor' ),
					'BDAY19',
					min( ( strtotime( date( '2022-06-15 23:59:59' ) ) - time() ) / DAY_IN_SECONDS, 9 )
				),
				'from'	=> strtotime( date( '2022-05-25 23:59:59' ) ),
				'to'	=> strtotime( date( '2022-06-15 23:59:59' ) ),
			],
		];

		if( isset( $_GET['is-dismiss'] ) && array_key_exists( $_GET['is-dismiss'], $banners ) ) {
			$dismissed = get_option( $notice_key ) ? : [];
			$dismissed[] = sanitize_text_field( $_GET['is-dismiss'] );
			update_option( $notice_key, array_unique( $dismissed ) );
		}

		$dismissed = get_option( $notice_key ) ? : [];
		$active_banners = array_values( array_diff( array_keys( $banners ), $dismissed ) );

		$rand_index = rand( 0, count( $active_banners ) - 1 );
		$rand_img = false;
		if( isset( $active_banners[ $rand_index ] ) ) {
			$rand_img = $active_banners[ $rand_index ];
		}

		if( ! wcd_is_pro() && $rand_img ) {
			$query_args = [ 'is-dismiss' => $rand_img ];

			if( count( $_GET ) > 0 ) {
				$query_args = array_map( 'sanitize_text_field', $_GET ) + $query_args;
			}

			if( isset( $banners[ $rand_img ]['from'] ) && $banners[ $rand_img ]['from'] > time() ) return;
			if( isset( $banners[ $rand_img ]['to'] ) && $banners[ $rand_img ]['to'] < time() ) return;

			?>
			<div class="notice notice-success cx-notice cx-shadow is-dismissible cx-promo cx-promo-<?php echo $banners[ $rand_img ]['type']; ?>">

				<?php if( 'image' == $banners[ $rand_img ]['type'] ) : ?>
				<a href="<?php echo add_query_arg( [ 'utm_campaign' => $rand_img ], $banners[ $rand_img ]['url'] ); ?>" target="_blank">
					<img id="<?php echo "promo-{$rand_img}"?>" src="<?php echo $banners[ $rand_img ]['image']; ?>">
				</a>
				<?php endif; ?>

				<?php if( 'text' == $banners[ $rand_img ]['type'] ) : ?>
				<a href="<?php echo add_query_arg( [ 'utm_campaign' => $rand_img ], $banners[ $rand_img ]['url'] ); ?>" target="_blank">
					<?php echo $banners[ $rand_img ]['text']; ?>
				</a>
				<?php endif; ?>

				<a href="<?php echo add_query_arg( $query_args, '' ); ?>" class="notice-dismiss"><span class="screen-reader-text"></span></a>
			</div>
			<?php
		}
	}
}