<?php
namespace codexpert\Woolementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

class Product_Upsell extends Widget_Base {

	public $id;

	public function __construct( $data = [], $args = null ) {
	    parent::__construct( $data, $args );

	    $this->id 		= woolementor_get_widget_id( __CLASS__ );
	    $this->widget 	= woolementor_get_widget( $this->id );
	    
		// Are we in debug mode?
		$min = defined( 'WOOLEMENTOR_DEBUG' ) && WOOLEMENTOR_DEBUG ? '' : '.min';

		wp_register_style( "woolementor-{$this->id}", plugins_url( "assets/css/style{$min}.css", __FILE__ ), [], '1.1' );
	}

	public function get_script_depends() {
		return [ "woolementor-{$this->id}" ];
	}

	public function get_style_depends() {
		return [ "woolementor-{$this->id}" ];
	}

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return $this->widget['title'];
	}

	public function get_icon() {
		return $this->widget['icon'];
	}

	public function get_categories() {
		return $this->widget['categories'];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_upsell_content',
			[
				'label' => __( 'Upsells', 'woolementor' ),
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'woolementor' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'woolementor-products-columns%s-',
				'default' => 4,
				'min' => 1,
				'max' => 12,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' 	=> __( 'Order By', 'woolementor' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'date',
				'options' 	=> [
					'date' 	=> __( 'Date', 'woolementor' ),
					'title' => __( 'Title', 'woolementor' ),
					'price' => __( 'Price', 'woolementor' ),
					'popularity' 	=> __( 'Popularity', 'woolementor' ),
					'rating' 		=> __( 'Rating', 'woolementor' ),
					'rand' 			=> __( 'Random', 'woolementor' ),
					'menu_order' 	=> __( 'Menu Order', 'woolementor' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' 	=> __( 'Order', 'woolementor' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'desc',
				'options' 	=> [
					'asc' 	=> __( 'ASC', 'woolementor' ),
					'desc' 	=> __( 'DESC', 'woolementor' ),
				],
			]
		);

		$this->end_controls_section();

		parent::register_controls();

		$this->start_injection( [
			'at' => 'before',
			'of' => 'section_design_box',
		] );

		$this->start_controls_section(
			'section_heading_style',
			[
				'label' => __( 'Heading', 'woolementor' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_heading',
			[
				'label' 		=> __( 'Heading', 'woolementor' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_off' 	=> __( 'Hide', 'woolementor' ),
				'label_on' 		=> __( 'Show', 'woolementor' ),
				'default' 		=> 'yes',
				'return_value' 	=> 'yes',
				'prefix_class' 	=> 'show-heading-',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' 		=> __( 'Color', 'woolementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'global' 		=> [
					'default' 	=> Global_Colors::COLOR_PRIMARY,
				],
				'selectors' 	=> [
					'{{WRAPPER}}.woolementor-wc-products .products > h2' => 'color: {{VALUE}}',
				],
				'condition' 	=> [
					'show_heading!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'heading_typography',
				'global' 		=> [
					'default' 	=> Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' 		=> '{{WRAPPER}}.woolementor-wc-products .products > h2',
				'condition' 	=> [
					'show_heading!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'heading_text_align',
			[
				'label' => __( 'Text Align', 'woolementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'woolementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'woolementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'woolementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}.woolementor-wc-products .products > h2' => 'text-align: {{VALUE}}',
				],
				'condition' => [
					'show_heading!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'heading_spacing',
			[
				'label' => __( 'Spacing', 'woolementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}}.woolementor-wc-products .products > h2' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_heading!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->end_injection();
	}

	protected function render() {
		$settings 	= $this->get_settings_for_display();
		$limit 		= '-1';
		$columns 	= 4;
		$orderby 	= 'rand';
		$order 		= 'desc';

		if ( ! empty( $settings['columns'] ) ) {
			$columns = $settings['columns'];
		}

		if ( ! empty( $settings['orderby'] ) ) {
			$orderby = $settings['orderby'];
		}

		if ( ! empty( $settings['order'] ) ) {
			$order = $settings['order'];
		}

		woocommerce_upsell_display( $limit, $columns, $orderby, $order );
	}
}

