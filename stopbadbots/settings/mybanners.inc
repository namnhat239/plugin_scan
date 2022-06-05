<?php
/**
 * @author William Sergio Minossi
 * @copyright 2018
 */
$termina                  = get_transient( 'termina' );
$stopbadbots_checkversion = trim( $stopbadbots_checkversion );
echo '<div id="containerright">';


		$allowed_atts = array(
			'align'      => array(),
			'class'      => array(),
			'type'       => array(),
			'id'         => array(),
			'dir'        => array(),
			'lang'       => array(),
			'style'      => array(),
			'xml:lang'   => array(),
			'src'        => array(),
			'alt'        => array(),
			'href'       => array(),
			'rel'        => array(),
			'rev'        => array(),
			'target'     => array(),
			'novalidate' => array(),
			'type'       => array(),
			'value'      => array(),
			'name'       => array(),
			'tabindex'   => array(),
			'action'     => array(),
			'method'     => array(),
			'for'        => array(),
			'width'      => array(),
			'height'     => array(),
			'data'       => array(),
			'title'      => array(),

			'checked'    => array(),
			'selected'   => array(),
		);

		$my_allowed['form']   = $allowed_atts;
		$my_allowed['select'] = $allowed_atts;
		// select options
		$my_allowed['option']   = $allowed_atts;
		$my_allowed['style']    = $allowed_atts;
		$my_allowed['label']    = $allowed_atts;
		$my_allowed['input']    = $allowed_atts;
		$my_allowed['textarea'] = $allowed_atts;

		// more...future...
		$my_allowed['form']     = $allowed_atts;
		$my_allowed['label']    = $allowed_atts;
		$my_allowed['input']    = $allowed_atts;
		$my_allowed['textarea'] = $allowed_atts;
		$my_allowed['iframe']   = $allowed_atts;
		$my_allowed['script']   = $allowed_atts;
		$my_allowed['style']    = $allowed_atts;
		$my_allowed['strong']   = $allowed_atts;
		$my_allowed['small']    = $allowed_atts;
		$my_allowed['table']    = $allowed_atts;
		$my_allowed['span']     = $allowed_atts;
		$my_allowed['abbr']     = $allowed_atts;
		$my_allowed['code']     = $allowed_atts;
		$my_allowed['pre']      = $allowed_atts;
		$my_allowed['div']      = $allowed_atts;
		$my_allowed['img']      = $allowed_atts;
		$my_allowed['h1']       = $allowed_atts;
		$my_allowed['h2']       = $allowed_atts;
		$my_allowed['h3']       = $allowed_atts;
		$my_allowed['h4']       = $allowed_atts;
		$my_allowed['h5']       = $allowed_atts;
		$my_allowed['h6']       = $allowed_atts;
		$my_allowed['ol']       = $allowed_atts;
		$my_allowed['ul']       = $allowed_atts;
		$my_allowed['li']       = $allowed_atts;
		$my_allowed['em']       = $allowed_atts;
		$my_allowed['hr']       = $allowed_atts;
		$my_allowed['br']       = $allowed_atts;
		$my_allowed['tr']       = $allowed_atts;
		$my_allowed['td']       = $allowed_atts;
		$my_allowed['p']        = $allowed_atts;
		$my_allowed['a']        = $allowed_atts;
		$my_allowed['b']        = $allowed_atts;
		$my_allowed['i']        = $allowed_atts;

		if ( ! $termina ) {

			ob_start();
			// Debug
			// $stopbadbots_checkversion = '123';
			if ( ! empty( $stopbadbots_checkversion ) ) {
				$myarray = array(
					'checkversion' => $stopbadbots_checkversion,
				);
			} else {
				$myarray = array();
			}
			$url      = 'https://billminozzi.com/API/bill-api.php';
			$response = wp_remote_post(
				$url,
				array(
					'method'      => 'POST',
					'timeout'     => 5,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(),
					'body'        => $myarray,
					'cookies'     => array(),
				)
			);
			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				// echo "Something went wrong: $error_message";
				set_transient( 'termina', DAY_IN_SECONDS, DAY_IN_SECONDS );
				ob_end_clean();
				return;
			}
			$r = trim( $response['body'] );
			ob_end_clean();
			$r = json_decode( $r, true );

			if ( $r == null or count( $r ) < 5 ) {
				set_transient( 'termina', time(), DAY_IN_SECONDS );
				$title = '';
				$image = '';
				return;
			} else {
				$type = $r['type'];
				if ( $type == 'news' ) {
					$message = wp_kses( $r['message'], $my_allowed );
				} else {
					$code = sanitize_text_field( $r['code'] );
				}

				$title   = sanitize_text_field( $r['title'] );
				$termina = sanitize_text_field( $r['termina'] );
				$image   = sanitize_text_field( $r['image'] );

				set_transient( 'termina', $termina, DAY_IN_SECONDS );


				set_transient( 'title', $title, DAY_IN_SECONDS );
				$x = set_transient( 'type', $type, DAY_IN_SECONDS );
				set_transient( 'image', $image, DAY_IN_SECONDS );
				if ( $type == 'news' ) {
					set_transient( 'message', $message, DAY_IN_SECONDS );
				} else {
					set_transient( 'code', $code, DAY_IN_SECONDS );
				}
			}
		} else {
			// termina existe
			$type = get_transient( 'type' );
			if ( $type == 'news' ) {
				$message = get_transient( 'message' );
			} else {
				$code = get_transient( 'code' );
			}
			$title   = get_transient( 'title' );
			$termina = get_transient( 'termina' );
			$image   = get_transient( 'image' );
		}
		// Debug
		// $termina = time() + DAY_IN_SECONDS;

		// var_dump($type);
		// die();


		if ( empty( $stopbadbots_checkversion ) or trim( $type ) == 'news' ) {
			// free always or news
			if ( ( strtotime( $termina ) > time() ) and ! empty( $title ) and ! empty( $image ) ) {
				// show block...
				echo '<ul>';
				echo '<h2>' . esc_attr( $title ) . '</h2>';
				echo '<img src="' . esc_url( STOPBADBOTSIMAGES ) . '/' . esc_attr( $image ) . '" width="250" />';
				if ( $type == 'news' ) {
					echo '<br>';
					// echo '<BIG>' . esc_attr($message) . '</BIG>';
					echo '<BIG>' . wp_kses( $message, $my_allowed ) . '</BIG>';
				} else {
					echo '<center><BIG>CODE: ' . esc_attr( $code ) . '</BIG></center>';
				}
				echo '</ul>';
			} // if termina..
		}
		if ( empty( $stopbadbots_checkversion ) ) {
			// Only Free
			echo '<ul>';
			$x = rand( 1, 3 );
			if ( $x == 1 ) {
				$url = STOPBADBOTSURL . 'assets/videos/security11.mp4';
			}
			if ( $x == 2 ) {
				$url = STOPBADBOTSURL . 'assets/videos/security12.mp4';
			}
			if ( $x == 3 ) {
				$url = STOPBADBOTSURL . 'assets/videos/security13.mp4';
			}
			?>
	<video id="bill-banner-2" style="margin:0px 0px -15px -12px; padding:0px;" width="400" height="230" muted>
		<source src="<?php echo esc_url( $url ); ?>" type="video/mp4">
	</video>

    <li><?php esc_attr_e("Go Premium and receive automatically weekly updates","stopbadbots"); ?></li>
    <li><?php esc_attr_e("Limit Bots Visits","stopbadbots"); ?></li>
    <li><?php esc_attr_e("Firewall Protection","stopbadbots"); ?></li>
    <li><?php esc_attr_e("Dedicated Premium Support","stopbadbots"); ?></li>
    <li><?php esc_attr_e("More...","stopbadbots"); ?></li>
	<br />
	<a href="https://stopbadbots.com/premium/" class="button button-medium button-primary"><?php esc_attr_e( 'Learn More', 'stopbadbots' ); ?></a>
			<?php
			echo '</ul>';
		}
		// Always...
		echo '<ul>';
		$x = rand( 1, 3 );
		if ( $x < 2 ) {

            echo '<h2>'.esc_attr__("Like This Plugin?","stopbadbots").'</h2>';
            echo esc_attr__("If you like this product, please write a few words about it. It will help other people find this useful plugin more quickly.","stopbadbots").'<br><b>'.esc_attr_e("Thank you!","stopbadbots").'</b>';
 



			?>
	<br /><br />
	<a href="http://stopbadbots.com/share/" class="button button-medium button-primary"><?php esc_attr_e( 'Rate or Share', 'stopbadbots' ); ?></a>
			<?php
		} else {
		    //	echo '<h2>Please help us keep the plugin live & up-to-date</h2>';
			   echo '<h2>'.esc_attr__("Please help us keep the plugin live & up-to-date","stopbadbots").'</h2>';
 
			esc_attr_e( 'If you use & enjoy Stop Bad Bots Plugin, please rate it on WordPress.org. It only takes a second and helps us keep the plugin live and maintained. Thank you!', 'stopbadbots' );
			?>
	<br /><br />
	<a href="https://wordpress.org/support/plugin/stopbadbots/reviews/#new-post" class="button button-medium button-primary"><?php esc_attr_e( 'Rate', 'stopbadbots' ); ?></a>
			<?php
		}
		echo '</ul>';
		?>
