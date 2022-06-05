<?php
/**
 * All Widgets facing functions
 */
namespace Codexpert\Woolementor;
use Codexpert\Plugin\Base;
use \Elementor\Plugin as Elementor_Plugin;
use \Elementor\Controls_Manager;
use \Elementor\Scheme_Typography;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Box_Shadow;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Widgets
 * @author Nazmul Ahsan <n.mukto@gmail.com>
 */
class Widgets extends Base {

	public $plugin;

	/**
	 * Constructor function
	 *
	 * @since 1.0
	 */
	public function __construct( $plugin ) {
		$this->plugin   = $plugin;
        $this->slug     = $this->plugin['TextDomain'];
        $this->name     = $this->plugin['Name'];
        $this->version  = $this->plugin['Version'];
		$this->widgets 	= woolementor_widgets();
		$this->active_widgets = wcd_active_widgets();
		$this->active_controls = $this->active_widgets;
		$this->assets 	= WOOLEMENTOR_ASSETS;
	}

	public function editor_enqueue_styles() {

		// Are we in debug mode?
		$min = defined( 'WOOLEMENTOR_DEBUG' ) && WOOLEMENTOR_DEBUG ? '' : '.min';

		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( "{$this->slug}-editor", "{$this->assets}/css/editor{$min}.css", '', $this->version, 'all' );

		/**
		 * Using for cross domain copy-paste
		 * @source https://cdn.jsdelivr.net/npm/xdlocalstorage@2.0.5/dist/scripts/xdLocalStorage.min.js
		 */
		wp_enqueue_script( "{$this->slug}-xdLocalStorage", "{$this->assets}/third-party/xdLocalStorage/xdLocalStorage.min.js", [], $this->version, true );
		wp_enqueue_script( "{$this->slug}-xd-copy-paste", "{$this->assets}/js/xd-copy-paste.js", [], $this->version, true );

		wp_enqueue_script( "{$this->slug}-editor", "{$this->assets}/js/editor.js", [], $this->version, true );
	}

	/**
	 * Registers categories for widgets
	 *
	 * @since 1.0
	 */
	public function register_category( $elements_manager ) {
		foreach ( wcd_widget_categories() as $id => $data ) {
			$elements_manager->add_category(
				$id,
				[
					'title'	=> $data['title'],
					'icon'	=> $data['icon'],
				]
			);
		}
	}

	/**
	 * Registers THE widgets
	 *
	 * @since 1.0
	 */
	public function register_widgets() {

		foreach( $this->active_widgets as $active_widget ) {
			$should_register = apply_filters( 'wcd_register_widget', true, $active_widget );
			if(
				wcd_is_pro_feature( $active_widget ) &&
				defined( 'WOOLEMENTOR_PRO_DIR' ) && $should_register &&
				wcd_is_pro_activated() &&
				file_exists( $file = WOOLEMENTOR_PRO_DIR . "/widgets/{$active_widget}/{$active_widget}.php" )
			) {
				require_once( $file );

				$class = str_replace( ' ', '_', ucwords( str_replace( array( '-', '.php' ), array( ' ', '' ), $active_widget ) ) );
				
				$widget = "Codexpert\\Woolementor_Pro\\{$class}";

				if( class_exists( $widget ) ) {
					Elementor_Plugin::instance()->widgets_manager->register_widget_type( new $widget() );
				}
			}
			elseif( $should_register && file_exists( $file = WOOLEMENTOR_DIR . "/widgets/{$active_widget}/{$active_widget}.php" ) ) {
				require_once( $file );

				$class = str_replace( ' ', '_', ucwords( str_replace( array( '-', '.php' ), array( ' ', '' ), $active_widget ) ) );
				
				$widget = "Codexpert\\Woolementor\\{$class}";

				if( class_exists( $widget ) ) {
					Elementor_Plugin::instance()->widgets_manager->register_widget_type( new $widget() );
				}
			}
		}
	}

	/**
	 * Registers additional controls for widgets
	 *
	 * @since 1.0
	 */
	public function register_controls() {
		
		include_once( WOOLEMENTOR_DIR . '/controls/gradient-text.php' );
        $gradient_text = __NAMESPACE__ . '\Controls\Group_Control_Gradient_Text';

        Elementor_Plugin::instance()->controls_manager->add_group_control( $gradient_text::get_type(), new $gradient_text() );
	}

	/**
	 * Enables Woolementor's place in the default content
	 *
	 * @since 1.0
	 *
	 * @TODO: use a better hook to add this
	 */
	public function the_content( $content ) {
		$content_start = apply_filters( 'woolementor-content_start', '' );
		$content_close = apply_filters( 'woolementor-content_close', '' );

		return $content_start . $content . $content_close;
	}

	public function recently_viewed( $options ) {
		// $is_enable = Helper::get_option( 'woolementor_tools', 'recently_viewed_products' );
		// if ( empty( $is_enable ) ) return $options;

		$options[ 'recently-viewed' ] = __( 'Recently Viewed', 'woolementor' );
		return $options;
	}

	public function recently_sold( $options ) {
		// $is_enable = Helper::get_option( 'woolementor_tools', 'recently_sold_products' );
		// if ( empty( $is_enable ) ) return $options;

		$options[ 'recently-sold' ] = __( 'Recently Sold', 'woolementor' );
		return $options;
	}

	public function set_filter_query( $wp_query ) {

		if ( !isset( $wp_query->query ) || !isset( $wp_query->query['post_type'] ) || $wp_query->query['post_type'] != 'product' ) return;
			
		if ( !isset( $_GET['filter'] ) || empty( $_GET['filter'] ) ) return;

		if( !empty( $_GET['filter']['taxonomies'] ) ) {
			$taxonomies = [];
			foreach ( $_GET['filter']['taxonomies'] as $key => $term ) {
		        $taxonomies[] = array(
		          'taxonomy' => sanitize_text_field( $key ),
		          'field'    => 'slug',
		          'terms'    => array_map( 'sanitize_text_field', $term )
		        );
			}

			$wp_query->set( 'tax_query', $taxonomies );
		}

		if ( isset( $_GET['filter']['max_price'] ) && $_GET['filter']['max_price'] != '' && isset( $_GET['filter']['min_price'] ) && $_GET['filter']['min_price'] != '' ) {
			$max_price = codesigner_sanitize_number( $_GET['filter']['max_price'] );
			$min_price = codesigner_sanitize_number( $_GET['filter']['min_price'] );

	       	$meta_query[] = array(
		          'key' 	=> '_price',
	              'value' 	=> [ $min_price, $max_price ],
	              'compare' => 'BETWEEN',
	              'type' 	=> 'NUMERIC'
	        );
	        $default_metaq = $wp_query->meta_query ? $wp_query->meta_query : [];
			$wp_query->set( 'meta_query', array_merge( $default_metaq, $meta_query ) );
		}

		if ( isset( $_GET['filter']['orderby'] ) ) {					
			$orderby = sanitize_text_field( $_GET['filter']['orderby'] );
			$args['orderby']	= $orderby;
			$wp_query->set( 'orderby', $orderby );

		    if( in_array( $orderby, [ '_price', 'total_sales', '_wc_average_rating' ] ) ) {
		    	$args['meta_key']	= $orderby;
		    	$args['orderby'] 	= 'meta_value_num';
				$wp_query->set( 'meta_key', $orderby );
				$wp_query->set( 'orderby', 'meta_value_num' );
		    }
		}
		if( isset( $_GET['filter']['order'] ) ){
	        $order	= sanitize_text_field( $_GET['filter']['order'] );
			$wp_query->set( 'order', $order );
	    }
	    if( isset( $_GET['filter']['q'] ) ){
	        $q		= sanitize_text_field( $_GET['filter']['q'] );
			$wp_query->set( 's', $q );
	    }
	}

	public function shop_query_controls( $element ) {
		
		/**
		 * Product Source
		 */
		$element->start_controls_section(
		    'product_source_control',
		    [
		        'label' => __( 'Product Source', 'woolementor-pro' ),
		        'tab'   => Controls_Manager::TAB_CONTENT,
		    ]
		);


		$element->add_control(
		    'product_source',
		    [
		        'label'     => __( 'Source Type', 'woolementor-pro' ),
		        'type'      => Controls_Manager::SELECT,
		        'default'   => 'shop',
		        'options'   => wcd_product_source_type(),
		        'label_block' => true
		    ]
		);

		/**
		 * Query non shop types
		 */
		$element->start_controls_tabs(
		    'non_shop_souce_section',
		    [
		        'separator' => 'before',
		        'condition' => [
		            'product_source!' => 'shop'
		        ],
		    ]
		);

		$element->start_controls_tab(
		    'non_shop_souce_tab',
		    [
		        'label'     => __( '', 'woolementor-pro' ),
		    ]
		);

		$element->add_control(
		    'content_source',
		    [
		        'label' => __( 'Content Source', 'woolementor-pro' ),
		        'type' => Controls_Manager::SELECT,
		        'options' => [
		            'current_product'   => __( 'Current Product', 'woolementor-pro' ),
		            'different_product' => __( 'Custom', 'woolementor-pro' ),
		        ],
		        'default' => 'current_product' ,
		        'label_block' => true,
		        'conditions'  => [
		            'relation' => 'or',
		            'terms' => [
		                [
		                    'name' => 'product_source',
		                    'operator' => '==',
		                    'value' => 'related-products',
		                ],
		                [
		                    'name' => 'product_source',
		                    'operator' => '==',
		                    'value' => 'cross-sells',
		                ],
		                [
		                    'name' => 'product_source',
		                    'operator' => '==',
		                    'value' => 'upsells',
		                ],
		            ] 
		        ],
		    ]
		);

		$element->add_control(
		    'main_product_id',
		    [
		        'label'     => __( 'Product ID', 'woolementor-pro' ),
		        'type'      => Controls_Manager::NUMBER,
		        'default'   => get_post_type( get_the_ID() ) == 'product' ? get_the_ID() : '',
		        'description'  => __( 'Input the base product ID', 'woolementor-pro' ),
		        'separator' => 'after',
		        'conditions'  => [
		            'terms' => [
		                [
		                    'name' => 'content_source',
		                    'operator' => '==',
		                    'value' => 'different_product',
		                ],
		                [
		                	'relation' => 'or',
		                	'terms' => [
		                		[
		                		    'name' => 'product_source',
		                		    'operator' => '==',
		                		    'value' => 'cross-sells',
		                		],
		                		[
		                		    'name' => 'product_source',
		                		    'operator' => '==',
		                		    'value' => 'upsells',
		                		],
		                		[
		                		    'name' => 'product_source',
		                		    'operator' => '==',
		                		    'value' => 'related-products',
		                		],
		                	]
		                ]
		            ] 
		        ],
		    ]
		);

		$element->add_control(
		    'product_limit',
		    [
		        'label'     => __( 'Products Limit', 'woolementor-pro' ),
		        'type'      => Controls_Manager::NUMBER,
		        'default'   => 3,
		        'min'   	=> 0,
		        'description'  => __( 'Number of products to show', 'woolementor-pro' ),
		    ]
		);

		$element->add_control(
		    'ns_exclude_products',
		    [
		        'label'     => __( 'Exclude Products', 'woolementor-pro' ),
		        'type'      => Controls_Manager::TEXT,
		        'separator' => 'before',
		        'description'  => __( "Comma separated ID's of products that should be excluded", 'woolementor-pro' ),
		        'conditions'     => [
		            'relation' => 'or',
		            'terms' => [
		                [
		                    'name' => 'product_source',
		                    'operator' => '==',
		                    'value' => 'related-products',
		                ],
		                [
		                    'name' => 'product_source',
		                    'operator' => '==',
		                    'value' => 'cart-cross-sells',
		                ],
		                [
		                    'name' => 'product_source',
		                    'operator' => '==',
		                    'value' => 'cart-upsells',
		                ],
		                [
		                    'name' => 'product_source',
		                    'operator' => '==',
		                    'value' => 'cart-related-products',
		                ],
		            ]
		        ],
		    ]
		);

		$element->end_controls_tab();
		$element->end_controls_tabs();

		/**
		 * Query controls
		 */
		$element->add_control(
		    'custom_query',
		    [
		        'label'         => __( 'Custom Query', 'woolementor-pro' ),
		        'type'          => Controls_Manager::SWITCHER,
		        'label_on'      => __( 'Yes', 'woolementor-pro' ),
		        'label_off'     => __( 'No', 'woolementor-pro' ),
		        'return_value'  => 'yes',
		        'default'       => 'no',
		        'condition' =>[
		            'product_source' => 'shop'
		        ]
		    ]
		);

		$element->add_control(
		    'number',
		    [
		        'label'     => __( 'Products per page', 'woolementor-pro' ),
		        'type'      => Controls_Manager::NUMBER,
		        'default'   => 6,
		        'condition' =>[
		            'product_source' => 'shop'
		        ]
		    ]
		);

		$element->add_control(
		    'order',
		    [
		        'label'         => __( 'Order', 'woolementor-pro' ),
		        'type'          => Controls_Manager::SELECT,
		        'default'       => 'ASC',
		        'options'       => [
		            'ASC'       => __( 'ASC', 'woolementor-pro' ),
		            'DESC'      => __( 'DESC', 'woolementor-pro' ),
		        ],
		        'condition' =>[
		            'product_source' => 'shop'
		        ]
		    ]
		);

		$element->add_control(
		    'orderby',
		    [
		        'label'         => __( 'Order By', 'woolementor-pro' ),
		        'type'          => Controls_Manager::SELECT,
		        'default'       => 'ID',
		        'options'       => wcd_order_options(),
		        'condition' =>[
		            'product_source' => 'shop'
		        ]
		    ]
		);

		$element->start_controls_tabs(
		    'custom_query_section_separator',
		    [
		        'separator' => 'before',
		        'condition' => [
		            'custom_query' => 'yes',
		            'product_source' => 'shop'
		        ],
		    ]
		);

		$element->start_controls_tab(
		    'custom_query_section_normal',
		    [
		        'label'     => __( 'Custom Query', 'woolementor-pro' ),
		    ]
		);

		/**
		 * qury by authhor
		 * 
		 * @author Jakaria Istauk <jakariamd35@gmail.com>
		 * 
		 * @since 3.0
		 */
		$element->add_control(
		    'author',
		    [
		        'label'     => __( 'Author ID', 'woolementor-pro' ),
		        'type'      => Controls_Manager::NUMBER,
		    ]
		);

		$element->add_control(
		    'categories',
		    [
		        'label'     => __( 'Include Category', 'woolementor-pro' ),
		        'type'      => Controls_Manager::SELECT2,
		        'options'   => wcd_get_terms(),
		        'multiple'  => true,
		        'label_block' => true,
		    ]
		);

		$element->add_control(
		    'exclude_categories',
		    [
		        'label'     => __( 'Exclude Categories', 'woolementor-pro' ),
		        'type'      => Controls_Manager::SELECT2,
		        'options'   => wcd_get_terms(),
		        'multiple'  => true,
		        'label_block' => true,
		    ]
		);

		$element->add_control(
		    'include_products',
		    [
		        'label'         => __( 'Include Products', 'woolementor-pro' ),
		        'type'          => Controls_Manager::TEXT,
		        'label_block'   => 'block',
		        'description'   => __( 'Separate product ID\'s with comma delimiter', 'woolementor-pro' ),
		    ]
		);

		$element->add_control(
		    'exclude_products',
		    [
		        'label'         => __( 'Exclude Products', 'woolementor-pro' ),
		        'type'          => Controls_Manager::TEXT,
		        'label_block'   => 'block',
		        'description'   => __( 'Separate product ID\'s with comma delimiter', 'woolementor-pro' ),
		    ]
		);

		$element->add_control(
		    'sale_products_show_hide',
		    [
		        'label'         => __( "'On Sale' Products Only", 'woolementor-pro' ),
		        'type'          => Controls_Manager::SWITCHER,
		        'label_on'      => __( 'Yes', 'woolementor-pro' ),
		        'label_off'     => __( 'No', 'woolementor-pro' ),
		        'return_value'  => 'yes',
		        'default'       => 'no',
		    ]
		);

		$element->add_control(
		    'out_of_stock',
		    [
		        'label'         => __( "Hide Stock out products", 'woolementor-pro' ),
		        'type'          => Controls_Manager::SWITCHER,
		        'label_on'      => __( 'Yes', 'woolementor-pro' ),
		        'label_off'     => __( 'No', 'woolementor-pro' ),
		        'return_value'  => 'yes',
		        'default'       => '',
		    ]
		);

		$element->add_control(
		    'offset',
		    [
		        'label'         => __( 'Offset', 'woolementor-pro' ),
		        'type'          => Controls_Manager::NUMBER,
		    ]
		);

		$element->end_controls_tab();
		$element->end_controls_tabs();
		$element->end_controls_section();
	}
}