<?php
/*
Plugin Name: WPC Smart Quick View for WooCommerce
Plugin URI: https://wpclever.net/
Description: WPC Smart Quick View allows users to get a quick look of products without opening the product page.
Version: 2.9.0
Author: WPClever
Author URI: https://wpclever.net
Text Domain: woo-smart-quick-view
Domain Path: /languages/
Requires at least: 4.0
Tested up to: 6.0
WC requires at least: 3.0
WC tested up to: 6.5
*/

defined( 'ABSPATH' ) || exit;

! defined( 'WOOSQ_VERSION' ) && define( 'WOOSQ_VERSION', '2.9.0' );
! defined( 'WOOSQ_URI' ) && define( 'WOOSQ_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WOOSQ_REVIEWS' ) && define( 'WOOSQ_REVIEWS', 'https://wordpress.org/support/plugin/woo-smart-quick-view/reviews/?filter=5' );
! defined( 'WOOSQ_CHANGELOG' ) && define( 'WOOSQ_CHANGELOG', 'https://wordpress.org/plugins/woo-smart-quick-view/#developers' );
! defined( 'WOOSQ_DISCUSSION' ) && define( 'WOOSQ_DISCUSSION', 'https://wordpress.org/support/plugin/woo-smart-quick-view' );
! defined( 'WPC_URI' ) && define( 'WPC_URI', WOOSQ_URI );

include 'includes/wpc-dashboard.php';
include 'includes/wpc-menu.php';
include 'includes/wpc-kit.php';
include 'includes/wpc-notice.php';

if ( ! function_exists( 'woosq_init' ) ) {
	add_action( 'plugins_loaded', 'woosq_init', 11 );

	function woosq_init() {
		// load text-domain
		load_plugin_textdomain( 'woo-smart-quick-view', false, basename( __DIR__ ) . '/languages/' );

		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'woosq_notice_wc' );

			return;
		}

		if ( ! class_exists( 'WPCleverWoosq' ) ) {
			class WPCleverWoosq {
				protected static $summary = array();
				protected static $summary_default = array();
				protected static $localization = array();

				function __construct() {
					self::$summary = array(
						'title'       => esc_html__( 'Title', 'woo-smart-quick-view' ),
						'rating'      => esc_html__( 'Rating', 'woo-smart-quick-view' ),
						'price'       => esc_html__( 'Price', 'woo-smart-quick-view' ),
						'excerpt'     => esc_html__( 'Short description', 'woo-smart-quick-view' ),
						'add_to_cart' => esc_html__( 'Add to cart', 'woo-smart-quick-view' ),
						'meta'        => esc_html__( 'Meta', 'woo-smart-quick-view' ),
						'description' => esc_html__( 'Description', 'woo-smart-quick-view' ),
					);

					self::$summary_default = array(
						'title',
						'rating',
						'price',
						'excerpt',
						'add_to_cart',
						'meta'
					);

					add_action( 'init', [ $this, 'init' ] );

					// menu
					add_action( 'admin_menu', [ $this, 'admin_menu' ] );

					// admin enqueue scripts
					add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

					// enqueue scripts
					add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

					// footer
					add_action( 'wp_footer', [ $this, 'footer' ] );

					// ajax
					add_action( 'wp_ajax_woosq_quickview', [ $this, 'quickview' ] );
					add_action( 'wp_ajax_nopriv_woosq_quickview', [ $this, 'quickview' ] );

					// link
					add_filter( 'plugin_action_links', [ $this, 'action_links' ], 10, 2 );
					add_filter( 'plugin_row_meta', [ $this, 'row_meta' ], 10, 2 );

					// add image to variation
					add_filter( 'woocommerce_available_variation', [ $this, 'available_variation' ], 10, 3 );

					// summary
					add_action( 'woosq_product_summary', [ $this, 'before_title' ], 4 );
					add_action( 'woosq_product_summary', 'woocommerce_template_single_title', 5 );
					add_action( 'woosq_product_summary', [ $this, 'after_title' ], 6 );

					add_action( 'woosq_product_summary', [ $this, 'before_rating' ], 9 );
					add_action( 'woosq_product_summary', 'woocommerce_template_single_rating', 10 );
					add_action( 'woosq_product_summary', [ $this, 'after_rating' ], 11 );

					add_action( 'woosq_product_summary', [ $this, 'before_price' ], 14 );
					add_action( 'woosq_product_summary', 'woocommerce_template_single_price', 15 );
					add_action( 'woosq_product_summary', [ $this, 'after_price' ], 16 );

					add_action( 'woosq_product_summary', [ $this, 'before_excerpt' ], 19 );
					add_action( 'woosq_product_summary', 'woocommerce_template_single_excerpt', 20 );
					add_action( 'woosq_product_summary', [ $this, 'after_excerpt' ], 21 );

					add_action( 'woosq_product_summary', [ $this, 'add_to_cart' ], 25 );

					add_action( 'woosq_product_summary', [ $this, 'before_meta' ], 29 );
					add_action( 'woosq_product_summary', 'woocommerce_template_single_meta', 30 );
					add_action( 'woosq_product_summary', [ $this, 'after_meta' ], 31 );

					// add to cart redirect
					add_filter( 'woocommerce_add_to_cart_redirect', [ $this, 'add_to_cart_redirect' ], 10, 1 );

					// multiple cats
					add_filter( 'wp_dropdown_cats', [ $this, 'dropdown_cats_multiple' ], 10, 2 );
				}

				function init() {
					// localization
					self::$localization = (array) get_option( 'woosq_localization' );

					// image size
					add_image_size( 'woosq', 460, 460, true );

					// shortcode
					add_shortcode( 'woosq', [ $this, 'shortcode' ] );

					// position
					$position = apply_filters( 'woosq_button_position', get_option( 'woosq_button_position', apply_filters( 'woosq_button_position_default', 'after_add_to_cart' ) ) );

					if ( ! empty( $position ) ) {
						switch ( $position ) {
							case 'before_title':
								add_action( 'woocommerce_shop_loop_item_title', [ $this, 'add_button' ], 9 );
								break;
							case 'after_title':
								add_action( 'woocommerce_shop_loop_item_title', [ $this, 'add_button' ], 11 );
								break;
							case 'after_rating':
								add_action( 'woocommerce_after_shop_loop_item_title', [ $this, 'add_button' ], 6 );
								break;
							case 'after_price':
								add_action( 'woocommerce_after_shop_loop_item_title', [ $this, 'add_button' ], 11 );
								break;
							case 'before_add_to_cart':
								add_action( 'woocommerce_after_shop_loop_item', [ $this, 'add_button' ], 9 );
								break;
							case 'after_add_to_cart':
								add_action( 'woocommerce_after_shop_loop_item', [ $this, 'add_button' ], 11 );
								break;
							default:
								add_action( 'woosq_button_position_' . $position, [ $this, 'add_button' ] );
						}
					}
				}

				function available_variation( $data, $variable, $variation ) {
					if ( $image_id = $variation->get_image_id() ) {
						$image_sz = apply_filters( 'woosq_image_size', 'default' );

						if ( $image_sz === 'default' ) {
							$image_size = get_option( 'woosq_image_size', 'woosq' );
						} else {
							$image_size = $image_sz;
						}

						$image_src               = wp_get_attachment_image_src( $image_id, $image_size );
						$data['woosq_image_id']  = $image_id;
						$data['woosq_image_src'] = $image_src[0];
						$data['woosq_image']     = wp_get_attachment_image( $image_id, $image_size );
					}

					return $data;
				}

				function add_to_cart( $product ) {
					do_action( 'woosq_before_add_to_cart', $product );

					if ( get_option( 'woosq_add_to_cart_button', 'single' ) === 'archive' ) {
						woocommerce_template_loop_add_to_cart();
					} else {
						if ( $product->is_type( 'variation' ) ) {
							$variation_id = $product->get_id();
							$product_id   = $product->get_parent_id();
							?>
                            <form class="cart"
                                  action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
                                  method="post" enctype='multipart/form-data'>
								<?php woocommerce_single_variation_add_to_cart_button(); ?>
                            </form>
                            <script type="text/javascript">
                              (function($) {
                                $('#woosq-popup input[name="add-to-cart"]').val(<?php echo $product_id; ?>);
                                $('#woosq-popup input[name="product_id"]').val(<?php echo $product_id; ?>);
                                $('#woosq-popup input[name="variation_id"]').val(<?php echo $variation_id; ?>);
                              })(jQuery);
                            </script>
							<?php
						} else {
							woocommerce_template_single_add_to_cart();
						}
					}

					do_action( 'woosq_after_add_to_cart', $product );
				}

				function add_to_cart_redirect( $url ) {
					if ( isset( $_POST['woosq-redirect'] ) && ! empty( $_POST['woosq-redirect'] ) ) {
						return esc_url( $_POST['woosq-redirect'] );
					}

					return $url;
				}

				function quickview() {
					global $post, $product;
					$product_id = absint( $_GET['product_id'] );
					$product    = wc_get_product( $product_id );

					if ( $product ) {
						$post = get_post( $product_id );
						setup_postdata( $post );
						$thumb_ids = array();

						if ( get_option( 'woosq_content_image', 'all' ) === 'product_image' ) {
							if ( $product->get_image_id() ) {
								$thumb_ids[] = $product->get_image_id();
							}
						} else {
							$thumb_ids = $product->get_gallery_image_ids();

							if ( $product->get_image_id() && ( get_option( 'woosq_content_image', 'all' ) === 'all' ) ) {
								array_unshift( $thumb_ids, $product->get_image_id() );
							}
						}

						$thumb_ids = apply_filters( 'woosq_thumbnails', $thumb_ids, $product );
						?>
                        <div id="woosq-popup" class="mfp-with-anim">
                            <div class="woocommerce single-product">
                                <div id="product-<?php echo $product_id; ?>" <?php wc_product_class( '', $product ); ?>>
                                    <div class="thumbnails thumbnails-ori">
										<?php
										do_action( 'woosq_before_thumbnails', $product );

										echo '<div class="images">';

										$image_sz = apply_filters( 'woosq_image_size', 'default' );

										if ( $image_sz === 'default' ) {
											$image_size = get_option( 'woosq_image_size', 'woosq' );
										} else {
											$image_size = $image_sz;
										}

										if ( ! empty( $thumb_ids ) ) {
											foreach ( $thumb_ids as $thumb_id ) {
												echo '<div class="thumbnail">' . wp_get_attachment_image( $thumb_id, $image_size ) . '</div>';
											}
										} else {
											echo '<div class="thumbnail">' . wc_placeholder_img( $image_size ) . '</div>';
										}

										echo '</div>';

										do_action( 'woosq_after_thumbnails', $product );
										?>
                                    </div>
                                    <div class="summary entry-summary">
										<?php do_action( 'woosq_before_summary', $product ); ?>

                                        <div class="summary-content">
											<?php do_action( 'woosq_product_summary', $product ); ?>
                                        </div>

										<?php do_action( 'woosq_after_summary', $product ); ?>
                                    </div>
                                </div>
                            </div><!-- /woocommerce single-product -->
                        </div>
						<?php
						wp_reset_postdata();
					}

					die();
				}

				function add_button() {
					echo do_shortcode( '[woosq]' );
				}

				function shortcode( $attrs ) {
					$output = '';

					$attrs = shortcode_atts( array(
						'id'      => null,
						'text'    => null,
						'type'    => get_option( 'woosq_button_type', 'button' ),
						'effect'  => get_option( 'woosq_effect', 'mfp-3d-unfold' ),
						'context' => 'default',
					), $attrs, 'woosq' );

					if ( ! $attrs['id'] ) {
						global $product;
						$attrs['id'] = $product->get_id();
					}

					if ( $attrs['id'] ) {
						// check cats
						$selected_cats = get_option( '_woosq_cats', array() );

						if ( ! empty( $selected_cats ) && ( $selected_cats[0] !== '0' ) ) {
							if ( ! has_term( $selected_cats, 'product_cat', $attrs['id'] ) ) {
								return '';
							}
						}

						// button text
						if ( ! empty( $attrs['text'] ) ) {
							$button_text = $attrs['text'];
						} else {
							$button_text = self::localization( 'button', esc_html__( 'Quick view', 'woo-smart-quick-view' ) );
						}

						// button class
						$button_class = apply_filters( 'woosq_button_class', trim( 'woosq-btn woosq-btn-' . esc_attr( $attrs['id'] ) . ' ' . get_option( 'woosq_button_class' ) ), $attrs );

						if ( $attrs['type'] === 'link' ) {
							$output = '<a href="' . esc_url( '?quick-view=' . $attrs['id'] ) . '" class="' . esc_attr( $button_class ) . '" data-id="' . esc_attr( $attrs['id'] ) . '" data-effect="' . esc_attr( $attrs['effect'] ) . '" data-context="' . esc_attr( $attrs['context'] ) . '">' . esc_html( $button_text ) . '</a>';
						} else {
							$output = '<button class="' . esc_attr( $button_class ) . '" data-id="' . esc_attr( $attrs['id'] ) . '" data-effect="' . esc_attr( $attrs['effect'] ) . '" data-context="' . esc_attr( $attrs['context'] ) . '">' . esc_html( $button_text ) . '</button>';
						}
					}

					return apply_filters( 'woosq_button_html', $output, $attrs['id'] );
				}

				function before_title( $product ) {
					do_action( 'woosq_before_title', $product );
				}

				function after_title( $product ) {
					do_action( 'woosq_after_title', $product );
				}

				function before_rating( $product ) {
					do_action( 'woosq_before_rating', $product );
				}

				function after_rating( $product ) {
					do_action( 'woosq_after_rating', $product );
				}

				function before_price( $product ) {
					do_action( 'woosq_before_price', $product );
				}

				function after_price( $product ) {
					do_action( 'woosq_after_price', $product );
				}

				function before_excerpt( $product ) {
					do_action( 'woosq_before_excerpt', $product );
				}

				function after_excerpt( $product ) {
					do_action( 'woosq_after_excerpt', $product );
				}

				function before_meta( $product ) {
					do_action( 'woosq_before_meta', $product );
				}

				function after_meta( $product ) {
					do_action( 'woosq_after_meta', $product );
				}

				function admin_menu() {
					add_submenu_page( 'wpclever', esc_html__( 'WPC Smart Quick View', 'woo-smart-quick-view' ), esc_html__( 'Smart Quick View', 'woo-smart-quick-view' ), 'manage_options', 'wpclever-woosq', array(
						&$this,
						'admin_menu_content'
					) );
				}

				function admin_menu_content() {
					$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'settings';
					?>
                    <div class="wpclever_settings_page wrap">
                        <h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Smart Quick View', 'woo-smart-quick-view' ) . ' ' . WOOSQ_VERSION; ?></h1>
                        <div class="wpclever_settings_page_desc about-text">
                            <p>
								<?php printf( esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'woo-smart-quick-view' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
                                <br/>
                                <a href="<?php echo esc_url( WOOSQ_REVIEWS ); ?>"
                                   target="_blank"><?php esc_html_e( 'Reviews', 'woo-smart-quick-view' ); ?></a> | <a
                                        href="<?php echo esc_url( WOOSQ_CHANGELOG ); ?>"
                                        target="_blank"><?php esc_html_e( 'Changelog', 'woo-smart-quick-view' ); ?></a>
                                | <a href="<?php echo esc_url( WOOSQ_DISCUSSION ); ?>"
                                     target="_blank"><?php esc_html_e( 'Discussion', 'woo-smart-quick-view' ); ?></a>
                            </p>
                        </div>
                        <div class="wpclever_settings_page_nav">
                            <h2 class="nav-tab-wrapper">
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woosq&tab=settings' ); ?>"
                                   class="<?php echo esc_attr( $active_tab === 'settings' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Settings', 'woo-smart-quick-view' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woosq&tab=localization' ); ?>"
                                   class="<?php echo esc_attr( $active_tab === 'localization' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Localization', 'woo-smart-quick-view' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woosq&tab=premium' ); ?>"
                                   class="<?php echo esc_attr( $active_tab === 'premium' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>"
                                   style="color: #c9356e;">
									<?php esc_html_e( 'Premium Version', 'woo-smart-quick-view' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-kit' ); ?>" class="nav-tab">
									<?php esc_html_e( 'Essential Kit', 'woo-smart-quick-view' ); ?>
                                </a>
                            </h2>
                        </div>
                        <div class="wpclever_settings_page_content">
							<?php if ( $active_tab === 'settings' ) { ?>
                                <form method="post" action="options.php">
									<?php wp_nonce_field( 'update-options' ); ?>
                                    <table class="form-table">
                                        <tr class="heading">
                                            <th colspan="2">
												<?php esc_html_e( 'General', 'woo-smart-quick-view' ); ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Type', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <select name="woosq_button_type">
                                                    <option value="button" <?php echo esc_attr( get_option( 'woosq_button_type', 'button' ) === 'button' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Button', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                    <option value="link" <?php echo esc_attr( get_option( 'woosq_button_type', 'button' ) === 'link' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Link', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Extra class (optional)', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <input type="text" name="woosq_button_class"
                                                       value="<?php echo get_option( 'woosq_button_class', '' ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Add extra class for action button/link, split by one space.', 'woo-smart-quick-view' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Position', 'woo-smart-quick-view' ); ?></th>
                                            <td>
												<?php
												$position  = apply_filters( 'woosq_button_position', 'default' );
												$positions = apply_filters( 'woosq_button_positions', array(
													'before_title'       => esc_html__( 'Above title', 'woo-smart-quick-view' ),
													'after_title'        => esc_html__( 'Under title', 'woo-smart-quick-view' ),
													'after_rating'       => esc_html__( 'Under rating', 'woo-smart-quick-view' ),
													'after_price'        => esc_html__( 'Under price', 'woo-smart-quick-view' ),
													'before_add_to_cart' => esc_html__( 'Above add to cart', 'woo-smart-quick-view' ),
													'after_add_to_cart'  => esc_html__( 'Under add to cart', 'woo-smart-quick-view' ),
													'0'                  => esc_html__( 'None (hide it)', 'woo-smart-quick-view' ),
												) );
												?>
                                                <select name="woosq_button_position" <?php echo esc_attr( $position !== 'default' ? 'disabled' : '' ); ?>>
													<?php
													if ( $position === 'default' ) {
														$position = get_option( 'woosq_button_position', apply_filters( 'woosq_button_position_default', 'after_add_to_cart' ) );
													}

													foreach ( $positions as $k => $p ) {
														echo '<option value="' . esc_attr( $k ) . '" ' . ( ( $k === $position ) || ( empty( $position ) && empty( $k ) ) ? 'selected' : '' ) . '>' . esc_html( $p ) . '</option>';
													}
													?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Shortcode', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <span class="description"><?php printf( esc_html__( 'You can add the button by manually, please use the shortcode %s, eg. %s for the product with ID is 99.', 'woo-smart-quick-view' ), '<code>[woosq id="{product id}"]</code>', '<code>[woosq id="99"]</code>' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Popup effect', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <select name="woosq_effect">
                                                    <option value="mfp-fade" <?php echo esc_attr( get_option( 'woosq_effect', 'mfp-3d-unfold' ) === 'mfp-fade' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Fade', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                    <option value="mfp-zoom-in" <?php echo esc_attr( get_option( 'woosq_effect', 'mfp-3d-unfold' ) === 'mfp-zoom-in' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Zoom in', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                    <option value="mfp-zoom-out" <?php echo esc_attr( get_option( 'woosq_effect', 'mfp-3d-unfold' ) === 'mfp-zoom-out' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Zoom out', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                    <option value="mfp-newspaper" <?php echo esc_attr( get_option( 'woosq_effect', 'mfp-3d-unfold' ) === 'mfp-newspaper' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Newspaper', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                    <option value="mfp-move-horizontal" <?php echo esc_attr( get_option( 'woosq_effect', 'mfp-3d-unfold' ) === 'mfp-move-horizontal' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Move horizontal', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                    <option value="mfp-move-from-top" <?php echo esc_attr( get_option( 'woosq_effect', 'mfp-3d-unfold' ) === 'mfp-move-from-top' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Move from top', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                    <option value="mfp-3d-unfold" <?php echo esc_attr( get_option( 'woosq_effect', 'mfp-3d-unfold' ) === 'mfp-3d-unfold' ? 'selected' : '' ); ?>>
														<?php esc_html_e( '3d unfold', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                    <option value="mfp-slide-bottom" <?php echo esc_attr( get_option( 'woosq_effect', 'mfp-3d-unfold' ) === 'mfp-slide-bottom' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Slide bottom', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Use perfect-scrollbar', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <select name="woosq_perfect_scrollbar">
                                                    <option value="yes" <?php echo esc_attr( get_option( 'woosq_perfect_scrollbar', 'yes' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                    <option value="no" <?php echo esc_attr( get_option( 'woosq_perfect_scrollbar', 'yes' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description"><?php printf( esc_html__( 'Read more about %s', 'woo-smart-quick-view' ), '<a href="https://github.com/mdbootstrap/perfect-scrollbar" target="_blank">perfect-scrollbar</a>' ); ?>.</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Categories', 'woo-smart-quick-view' ); ?></th>
                                            <td>
												<?php
												$selected_cats = get_option( '_woosq_cats' );

												if ( empty( $selected_cats ) ) {
													$selected_cats = array( 0 );
												}

												// named _woosq_cats for multiple selected
												wc_product_dropdown_categories(
													array(
														'name'             => '_woosq_cats',
														'hide_empty'       => 0,
														'value_field'      => 'id',
														'multiple'         => true,
														'show_option_all'  => esc_html__( 'All categories', 'woo-smart-quick-view' ),
														'show_option_none' => '',
														'selected'         => implode( ',', $selected_cats )
													) );
												?>
                                                <span class="description"><?php esc_html_e( 'Only show the Quick View button for products in selected categories.', 'woo-smart-quick-view' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th>
												<?php esc_html_e( 'Content', 'woo-smart-quick-view' ); ?>
                                            </th>
                                            <td>
                                                <span style="color: #c9356e">Below settings are available on Premium Version only, click <a
                                                            href="https://wpclever.net/downloads/smart-quick-view?utm_source=pro&utm_medium=woosq&utm_campaign=wporg"
                                                            target="_blank">here</a> to buy, just $29!</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Images', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <select name="woosq_content_image">
                                                    <option value="all" <?php echo esc_attr( get_option( 'woosq_content_image', 'all' ) === 'all' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Product image & Product gallery images', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                    <option value="product_image" <?php echo esc_attr( get_option( 'woosq_content_image', 'all' ) === 'product_image' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Product image', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                    <option value="product_gallery" <?php echo esc_attr( get_option( 'woosq_content_image', 'all' ) === 'product_gallery' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Product gallery images', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Image size', 'woo-smart-quick-view' ); ?></th>
                                            <td>
												<?php
												$image_sz = apply_filters( 'woosq_image_size', 'default' );

												if ( $image_sz === 'default' ) {
													$image_size = get_option( 'woosq_image_size', 'woosq' );
												} else {
													$image_size = $image_sz;
												}

												$image_sizes         = $this->get_image_sizes();
												$image_sizes['full'] = array(
													'width'  => '',
													'height' => '',
													'crop'   => false
												);

												if ( ! empty( $image_sizes ) ) {
													echo '<select name="woosq_image_size" ' . ( $image_sz !== 'default' ? 'disabled' : '' ) . '>';

													foreach ( $image_sizes as $image_size_name => $image_size_data ) {
														echo '<option value="' . esc_attr( $image_size_name ) . '" ' . ( $image_size_name === $image_size ? 'selected' : '' ) . '>' . esc_attr( $image_size_name ) . ( ! empty( $image_size_data['width'] ) ? ' ' . $image_size_data['width'] . '&times;' . $image_size_data['height'] : '' ) . ( $image_size_data['crop'] ? ' (cropped)' : '' ) . '</option>';
													}

													echo '</select>';
												}
												?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Lightbox for images', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <select name="woosq_content_image_lightbox">
                                                    <option value="no" <?php echo esc_attr( get_option( 'woosq_content_image_lightbox', 'no' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                    <option value="yes" <?php echo esc_attr( get_option( 'woosq_content_image_lightbox', 'no' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Product summary', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <span class="description"><?php esc_html_e( 'Drag and drop to re-arrange these fields.', 'woo-smart-quick-view' ); ?></span>
                                                <ul class="woosq-summary">
													<?php
													$saved_summary = $merge_summary = array();
													$summary       = get_option( 'woosq_summary', self::$summary_default );

													foreach ( $summary as $s ) {
														$saved_summary[ $s ] = self::$summary[ $s ];
													}

													$merge_summary = array_merge( $saved_summary, self::$summary );

													foreach ( $merge_summary as $k => $s ) {
														echo '<li><input type="checkbox" name="woosq_summary[]" value="' . esc_attr( $k ) . '" ' . ( is_array( $summary ) && in_array( $k, $summary, true ) ? 'checked' : '' ) . '/><span class="label">' . $s . '</span></li>';
													}
													?>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Add to cart button', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <select name="woosq_add_to_cart_button">
                                                    <option value="archive" <?php echo esc_attr( get_option( 'woosq_add_to_cart_button', 'single' ) === 'archive' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Like archive page', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                    <option value="single" <?php echo esc_attr( get_option( 'woosq_add_to_cart_button', 'single' ) === 'single' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Like single page', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Choose the functionally for the add to cart button.', 'woo-smart-quick-view' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Related products', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <select name="woosq_related_products">
                                                    <option value="yes" <?php echo esc_attr( get_option( 'woosq_related_products', 'yes' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                    <option value="no" <?php echo esc_attr( get_option( 'woosq_related_products', 'yes' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Show related products.', 'woo-smart-quick-view' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'View details button', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <select name="woosq_content_view_details_button">
                                                    <option value="no" <?php echo esc_attr( get_option( 'woosq_content_view_details_button', 'no' ) === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                    <option value="yes" <?php echo esc_attr( get_option( 'woosq_content_view_details_button', 'no' ) === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes', 'woo-smart-quick-view' ); ?>
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="submit">
                                            <th colspan="2">
                                                <input type="submit" name="submit" class="button button-primary"
                                                       value="<?php esc_html_e( 'Update Options', 'woo-smart-quick-view' ); ?>"/>
                                                <input type="hidden" name="action" value="update"/>
                                                <input type="hidden" name="page_options"
                                                       value="woosq_button_type,woosq_button_class,woosq_button_position,woosq_effect,woosq_perfect_scrollbar,_woosq_cats,woosq_content_image,woosq_image_size,woosq_content_image_lightbox,woosq_summary,woosq_related_products,woosq_add_to_cart_button,woosq_content_view_details_button"/>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } elseif ( $active_tab === 'localization' ) { ?>
                                <form method="post" action="options.php">
									<?php wp_nonce_field( 'update-options' ); ?>
                                    <table class="form-table">
                                        <tr class="heading">
                                            <th scope="row"><?php esc_html_e( 'Localization', 'woo-smart-quick-view' ); ?></th>
                                            <td>
												<?php esc_html_e( 'Leave blank to use the default text and its equivalent translation in multiple languages.', 'woo-smart-quick-view' ); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Button text', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text"
                                                       name="woosq_localization[button]"
                                                       value="<?php echo esc_attr( self::localization( 'button' ) ); ?>"
                                                       placeholder="<?php esc_attr_e( 'Quick view', 'woo-smart-quick-view' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Close', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text"
                                                       name="woosq_localization[close]"
                                                       value="<?php echo esc_attr( self::localization( 'close' ) ); ?>"
                                                       placeholder="<?php esc_attr_e( 'Close (Esc)', 'woo-smart-quick-view' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Next', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text"
                                                       name="woosq_localization[next]"
                                                       value="<?php echo esc_attr( self::localization( 'next' ) ); ?>"
                                                       placeholder="<?php esc_attr_e( 'Next (Right arrow key)', 'woo-smart-quick-view' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Previous', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text"
                                                       name="woosq_localization[prev]"
                                                       value="<?php echo esc_attr( self::localization( 'prev' ) ); ?>"
                                                       placeholder="<?php esc_attr_e( 'Previous (Left arrow key)', 'woo-smart-quick-view' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Related products', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text"
                                                       name="woosq_localization[related_products]"
                                                       value="<?php echo esc_attr( self::localization( 'related_products' ) ); ?>"
                                                       placeholder="<?php esc_attr_e( 'You may also like&hellip;', 'woo-smart-quick-view' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'View details text', 'woo-smart-quick-view' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text"
                                                       name="woosq_localization[view_details]"
                                                       value="<?php echo esc_attr( self::localization( 'view_details' ) ); ?>"
                                                       placeholder="<?php esc_attr_e( 'View product details', 'woo-smart-quick-view' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr class="submit">
                                            <th colspan="2">
                                                <input type="submit" name="submit" class="button button-primary"
                                                       value="<?php esc_attr_e( 'Update Options', 'woo-smart-quick-view' ); ?>"/>
                                                <input type="hidden" name="action" value="update"/>
                                                <input type="hidden" name="page_options" value="woosq_localization"/>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } elseif ( $active_tab === 'premium' ) { ?>
                                <div class="wpclever_settings_page_content_text">
                                    <p>Get the Premium Version just $29! <a
                                                href="https://wpclever.net/downloads/smart-quick-view?utm_source=pro&utm_medium=woosq&utm_campaign=wporg"
                                                target="_blank">https://wpclever.net/downloads/smart-quick-view</a>
                                    </p>
                                    <p><strong>Extra features for Premium Version:</strong></p>
                                    <ul style="margin-bottom: 0">
                                        <li>- Add lightbox for images.</li>
                                        <li>- Show/hide the part of content in the popup.</li>
                                        <li>- Add "View Product Details" button.</li>
                                        <li>- Get the lifetime update & premium support.</li>
                                    </ul>
                                </div>
							<?php } ?>
                        </div>
                    </div>
					<?php
				}

				function admin_enqueue_scripts( $hook ) {
					wp_enqueue_style( 'woosq-backend', WOOSQ_URI . 'assets/css/backend.css', array(), WOOSQ_VERSION );

					if ( strpos( $hook, 'woosq' ) ) {
						wp_enqueue_script( 'woosq-backend', WOOSQ_URI . 'assets/js/backend.js', array(
							'jquery',
							'jquery-ui-sortable'
						), WOOSQ_VERSION, true );
					}
				}

				function enqueue_scripts() {
					wp_enqueue_script( 'wc-add-to-cart-variation' );

					// slick
					wp_enqueue_style( 'slick', WOOSQ_URI . 'assets/libs/slick/slick.css' );
					wp_enqueue_script( 'slick', WOOSQ_URI . 'assets/libs/slick/slick.min.js', array( 'jquery' ), WOOSQ_VERSION, true );

					// perfect srollbar
					if ( get_option( 'woosq_perfect_scrollbar', 'yes' ) === 'yes' ) {
						wp_enqueue_style( 'perfect-scrollbar', WOOSQ_URI . 'assets/libs/perfect-scrollbar/css/perfect-scrollbar.min.css' );
						wp_enqueue_style( 'perfect-scrollbar-wpc', WOOSQ_URI . 'assets/libs/perfect-scrollbar/css/custom-theme.css' );
						wp_enqueue_script( 'perfect-scrollbar', WOOSQ_URI . 'assets/libs/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js', array( 'jquery' ), WOOSQ_VERSION, true );
					}

					// magnific
					wp_enqueue_style( 'magnific-popup', WOOSQ_URI . 'assets/libs/magnific-popup/magnific-popup.css' );
					wp_enqueue_script( 'magnific-popup', WOOSQ_URI . 'assets/libs/magnific-popup/jquery.magnific-popup.min.js', array( 'jquery' ), WOOSQ_VERSION, true );

					// feather icons
					wp_enqueue_style( 'woosq-feather', WOOSQ_URI . 'assets/libs/feather/feather.css' );

					// main style & js
					wp_enqueue_style( 'woosq-frontend', WOOSQ_URI . 'assets/css/frontend.css', array(), WOOSQ_VERSION );
					wp_enqueue_script( 'woosq-frontend', WOOSQ_URI . 'assets/js/frontend.js', array(
						'jquery',
						'wc-add-to-cart-variation'
					), WOOSQ_VERSION, true );
					wp_localize_script( 'woosq-frontend', 'woosq_vars', array(
							'ajax_url'      => admin_url( 'admin-ajax.php' ),
							'effect'        => get_option( 'woosq_effect', 'mfp-3d-unfold' ),
							'scrollbar'     => get_option( 'woosq_perfect_scrollbar', 'yes' ),
							'hashchange'    => apply_filters( 'woosq_hashchange', 'no' ),
							'cart_redirect' => get_option( 'woocommerce_cart_redirect_after_add' ),
							'cart_url'      => apply_filters( 'woocommerce_add_to_cart_redirect', wc_get_cart_url(), null ),
							'close'         => self::localization( 'close', esc_html__( 'Close (Esc)', 'woo-smart-quick-view' ) ),
							'next'          => self::localization( 'next', esc_html__( 'Next (Right arrow key)', 'woo-smart-quick-view' ) ),
							'prev'          => self::localization( 'prev', esc_html__( 'Previous (Left arrow key)', 'woo-smart-quick-view' ) ),
							'is_rtl'        => is_rtl()
						)
					);
				}

				function footer() {
					if ( isset( $_REQUEST['quick-view'] ) ) {
						?>
                        <script type="text/javascript">
                          jQuery(document).ready(function() {
                            setTimeout(function() {
                              woosq_open(<?php echo absint( sanitize_key( $_REQUEST['quick-view'] ) ); ?>);
                            }, 1000);
                          });
                        </script>
						<?php
					}
				}

				function action_links( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$settings             = '<a href="' . admin_url( 'admin.php?page=wpclever-woosq&tab=settings' ) . '">' . esc_html__( 'Settings', 'woo-smart-quick-view' ) . '</a>';
						$links['wpc-premium'] = '<a href="' . admin_url( 'admin.php?page=wpclever-woosq&tab=premium' ) . '">' . esc_html__( 'Premium Version', 'woo-smart-quick-view' ) . '</a>';
						array_unshift( $links, $settings );
					}

					return (array) $links;
				}

				function row_meta( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$row_meta = array(
							'support' => '<a href="' . esc_url( WOOSQ_DISCUSSION ) . '" target="_blank">' . esc_html__( 'Community support', 'woo-smart-quick-view' ) . '</a>',
						);

						return array_merge( $links, $row_meta );
					}

					return (array) $links;
				}

				function get_image_sizes() {
					global $_wp_additional_image_sizes;
					$sizes = array();

					foreach ( get_intermediate_image_sizes() as $_size ) {
						if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
							$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
							$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
							$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
						} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
							$sizes[ $_size ] = array(
								'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
								'height' => $_wp_additional_image_sizes[ $_size ]['height'],
								'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
							);
						}
					}

					return $sizes;
				}

				function dropdown_cats_multiple( $output, $r ) {
					if ( isset( $r['multiple'] ) && $r['multiple'] ) {
						$output = preg_replace( '/^<select/i', '<select multiple', $output );
						$output = str_replace( "name='{$r['name']}'", "name='{$r['name']}[]'", $output );

						foreach ( array_map( 'trim', explode( ',', $r['selected'] ) ) as $value ) {
							$output = str_replace( "value=\"{$value}\"", "value=\"{$value}\" selected", $output );
						}
					}

					return $output;
				}

				function localization( $key = '', $default = '' ) {
					$str = '';

					if ( ! empty( $key ) && ! empty( self::$localization[ $key ] ) ) {
						$str = self::$localization[ $key ];
					} elseif ( ! empty( $default ) ) {
						$str = $default;
					}

					return apply_filters( 'woosq_localization_' . $key, $str );
				}
			}

			new WPCleverWoosq();
		}
	}
}

if ( ! function_exists( 'woosq_notice_wc' ) ) {
	function woosq_notice_wc() {
		?>
        <div class="error">
            <p><strong>WPC Smart Quick View</strong> requires WooCommerce version 3.0 or greater.</p>
        </div>
		<?php
	}
}
