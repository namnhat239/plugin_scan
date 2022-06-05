<?php 
$user = wp_get_current_user();
$user_name = $user->display_name;
echo '
<div class="step-one">
	<h1 class="cx-welcome">' . sprintf( __( 'Hello %s! 🎉', 'woolementor' ), esc_html( $user_name ) ) .'</h1>
	<p class="cx-wizard-sub">' . __( 'Thank you for choosing our plugin!', 'woolementor' ) . '</p>
	<p class="cx-wizard-sub">' . __( 'You can easily design your WooCommerce with elementor and make your website beautiful with', 'woolementor' ) . '
		<span class="cx-wizard-sub-span">' . __( 'CoDeisgner.', 'woolementor' ) . '</span>
	</p>
	<p class="cx-wizard-sub">' . __( 'This quick installation wizard will let you do it in three steps and less than 10 seconds!', 'woolementor' ) . '
	</p>
</div>';