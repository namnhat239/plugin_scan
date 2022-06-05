<?php
//Igual no deberías poder abrirme
defined( 'ABSPATH' ) || exit;

/**
 * Añade los campos en Panel de Administración - Pedidos.
 */
class APG_Campo_NIF_en_Admin_Pedidos {
	//Inicializa las acciones de Pedido
	public function __construct() {
		add_filter( 'woocommerce_order_formatted_billing_address', [ $this, 'apg_nif_anade_campo_nif_direccion_facturacion' ], 1, 2 );
		add_filter( 'woocommerce_order_formatted_shipping_address', [ $this, 'apg_nif_anade_campo_nif_direccion_envio' ], 1, 2 );
		add_filter( 'woocommerce_admin_billing_fields', [ $this, 'apg_nif_anade_campo_nif_editar_direccion_pedido' ] );
		add_filter( 'woocommerce_admin_shipping_fields', [ $this, 'apg_nif_anade_campo_nif_editar_direccion_pedido' ] );
		if ( version_compare( WC_VERSION, '2.7', '<' ) ) { 
			add_filter( 'woocommerce_found_customer_details', [ $this, 'apg_nif_ajax' ] );
      	} else { 
        	add_filter( 'woocommerce_ajax_get_customer_details', [ $this, 'apg_dame_nif_ajax' ], 10, 2 ); 
      	} 
		add_action( 'woocommerce_admin_order_data_after_billing_address', [ $this, 'apg_nif_carga_hoja_de_estilo_editar_direccion_pedido' ] );
	}
	
	//Nueva función para hacer compatible el código con WooCommerce 2.1
	public function apg_nif_dame_campo_personalizado( $campo, $pedido ) {
		$valor = get_post_meta( $pedido, $campo, false );
		if ( isset( $valor[0] ) ) {
			return $valor[0];
		}
		
		return NULL;
	}

	//Añade el NIF y el teléfono a la dirección de facturación y envío
	public function apg_nif_anade_campo_nif_direccion_facturacion( $campos, $pedido ) {
		if ( is_array( $campos ) ) {
			$numero_de_pedido	= is_callable( [ $pedido, 'get_id' ] ) ? $pedido->get_id() : $pedido->id;
			$campos['nif']		= $this->apg_nif_dame_campo_personalizado( '_billing_nif', $numero_de_pedido );
			$campos['phone']	= $this->apg_nif_dame_campo_personalizado( '_billing_phone', $numero_de_pedido );
			$campos['email']	= $this->apg_nif_dame_campo_personalizado( '_billing_email', $numero_de_pedido );
		}
		 
		return $campos;
	}
	 
	public function apg_nif_anade_campo_nif_direccion_envio( $campos, $pedido ) {
		if ( is_array( $campos ) ) {
			$numero_de_pedido	= is_callable( [ $pedido, 'get_id' ] ) ? $pedido->get_id() : $pedido->id;
			$campos['nif']		= $this->apg_nif_dame_campo_personalizado( '_shipping_nif', $numero_de_pedido );
			$campos['phone']	= $this->apg_nif_dame_campo_personalizado( '_shipping_phone', $numero_de_pedido );
			$campos['email']	= $this->apg_nif_dame_campo_personalizado( '_shipping_email', $numero_de_pedido );
		}
		 
		return $campos;
	}

	//Añade el campo NIF a Detalles del pedido
	public function apg_nif_anade_campo_nif_editar_direccion_pedido( $campos ) {
		global $apg_nif_settings;

        $campos['nif']		= [ 
			'label'	=> __( ( isset( $apg_nif_settings[ 'etiqueta' ] ) ? esc_attr( $apg_nif_settings[ 'etiqueta' ] ) : 'NIF/CIF/NIE' ), 'wc-apg-nifcifnie-field' ),
			'show'	=> false
		];
		$campos['phone']	= [ 
			'label'	=> __( 'Telephone', 'woocommerce' ),
			'show'	=> false
		];
		$campos['email']	= [ 
			'label'	=> __( 'Email Address', 'woocommerce' ),
			'show'	=> false
		];

		//Ordena los campos
		$orden_de_campos = [
			"first_name", 
			"last_name", 
			"company", 
			"nif", 
			"email",
			"phone",
			"address_1", 
			"address_2", 
			"postcode", 
			"city",
			"state",
			"country", 
		];
		
		foreach( $orden_de_campos as $campo ) {
			$campos_ordenados[$campo] = $campos[$campo];
		}

		return $campos_ordenados;
	}

	//Carga el campo NIF en los pedidos creados manualmente
	public function apg_nif_ajax( $datos_cliente ) {
		$cliente	= ( int ) trim( stripslashes( $_POST[ 'user_id' ] ) );
		$formulario	= esc_attr( trim( stripslashes( $_POST[ 'type_to_load' ] ) ) );

		$datos_cliente[$formulario . '_nif'] = get_user_meta( $cliente, $formulario . '_nif', true );

		return $datos_cliente;
	}
	public function apg_dame_nif_ajax( $datos_cliente, $cliente ) { 
		$datos_cliente['billing']['nif']	= $cliente->get_meta( 'billing_nif' );
		$datos_cliente['shipping']['nif']	= $cliente->get_meta( 'shipping_nif' );
 
		return $datos_cliente; 
	} 

	//Carga hoja de estilo personalizada a Detalles del pedido
	public function apg_nif_carga_hoja_de_estilo_editar_direccion_pedido( $pedido ) {
		echo '</pre>
	<style type="text/css"><!-- #order_data .order_data_column ._billing_company_field, #order_data .order_data_column ._shipping_company_field, #order_data .order_data_column ._billing_phone_field { float: left; margin: 9px 0 0; padding: 0; width: 48%; } #order_data .order_data_column ._billing_nif_field, #order_data .order_data_column ._shipping_nif_field { float: right; margin: 9px 0 0; padding: 0; width: 48%; } --></style>
	<pre>';
	}
}
new APG_Campo_NIF_en_Admin_Pedidos();
