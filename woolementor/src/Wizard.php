<?php
/**
 * All Wizard related functions
 */
namespace Codexpert\Woolementor;
use Codexpert\Plugin\Base;
use Codexpert\Plugin\Setup;
require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
require_once( ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php' );
require_once( ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php' );
/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Wizard
 * @author Codexpert <hi@codexpert.io>
 */
class Wizard extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->version	= $this->plugin['Version'];
		$this->action( 'admin_print_styles', 'enqueue_styles' );
	}

	public function action_links( $links ) {
		$this->admin_url = admin_url( 'admin.php' );

		$new_links = [
			'wizard'	=> sprintf( '<a href="%1$s">%2$s</a>', add_query_arg( [ 'page' => "{$this->slug}_setup" ], $this->admin_url ), __( 'Setup Wizard', 'cx-plugin' ) )
		];
		
		return array_merge( $new_links, $links );
	}

	public function enqueue_styles() {

		wp_enqueue_style( $this->slug, plugins_url( "/assets/css/wizard.css", WOOLEMENTOR ), '', $this->version, 'all' );
		wp_enqueue_style( 'setting', plugins_url( "/assets/css/widgets-settings.css", WOOLEMENTOR ), '', $this->version, 'all' );
		wp_enqueue_style( 'font-awesome-free', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css' );

	}

	public function render() {
		update_option( "{$this->slug}_setup_done", 1 );

		// force setup once
		if( get_option( "{$this->slug}_setup_done" ) != 1 ) {
			update_option( "{$this->slug}_setup_done", 1 );
			wp_safe_redirect( add_query_arg( [ 'page' => "{$this->slug}_setup" ], admin_url( 'admin.php' ) ) );
			exit;
		}

		$this->plugin['steps'] = [
			'welcome'	=> [
				'label'			=> __( 'Welcome', 'woolementor' ),
				'template'		=> WOOLEMENTOR_DIR . '/views/wizard/welcome.php',
				'prev_text'		=> __( 'Skip for now', 'woolementor' ),
				'prev_url'		=> add_query_arg( [ 'page' => 'woolementor' ], admin_url( 'admin.php' ) ),
				'next_text'		=> __( 'Get Started..', 'woolementor' ),
				'next_url'		=> add_query_arg( [ 'page' => 'woolementor_setup', 'step' => 'save-settings' ], admin_url( 'admin.php' ) ),
			],
			'save-settings'	=> [
				'label'			=> __( 'Save Settings', 'woolementor' ),
				'template'		=> WOOLEMENTOR_DIR . '/views/wizard/save-settings.php',
				'action'		=> [ $this, 'save_settings' ],
				'prev_text'		=> __( 'Previous', 'woolementor' ),
				'prev_url'		=> add_query_arg( [ 'page' => 'woolementor_setup', 'step' => 'welcome' ], admin_url( 'admin.php' ) ),
				'next_text'		=> __( 'Next', 'woolementor' ),
				'next_url'		=> add_query_arg( [ 'page' => 'woolementor_setup', 'step' => 'complete' ], admin_url( 'admin.php' ) ),
			],
			'complete'	=> [
				'label'			=> __( 'Complete', 'woolementor' ),
				'template'		=> WOOLEMENTOR_DIR . '/views/wizard/complete.php',
				'action'		=> [ $this, 'install_plugin' ],
				'redirect'		=> add_query_arg( [ 'page' => 'woolementor' ], admin_url( 'admin.php' ) )
			],
		];

		new Setup( $this->plugin );
	}

	public function save_settings() {

		//save to db

	} 
	public function install_plugin() {

		$skin     = new \WP_Ajax_Upgrader_Skin();
		$upgrader = new \Plugin_Upgrader( $skin );

		if ( isset( $_POST['image-sizes'] ) ) {
			$upgrader->install( 'https://downloads.wordpress.org/plugin/image-sizes.latest-stable.zip' );
			update_option( 'image-sizes_setup_done', 1 );
			activate_plugin( 'image-sizes/image-sizes.php' );
		}

		if ( isset( $_POST['wc-affiliate'] ) ) {
			$upgrader->install( 'https://downloads.wordpress.org/plugin/wc-affiliate.latest-stable.zip' );
			update_option( 'wc-affiliate_setup', 1 );
			activate_plugin( 'wc-affiliate/wc-affiliate.php' );
		}

		if ( isset( $_POST['restrict-elementor-widgets'] ) ) {
			$upgrader->install( 'https://downloads.wordpress.org/plugin/restrict-elementor-widgets.latest-stable.zip' );
			activate_plugin( 'restrict-elementor-widgets/restrict-elementor-widgets.php' );
		}
	
	}

}
