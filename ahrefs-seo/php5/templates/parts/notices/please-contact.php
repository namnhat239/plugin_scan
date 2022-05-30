<?php

namespace ahrefs\AhrefsSeo;

$locals   = Ahrefs_Seo_View::get_template_variables();
$messages = isset( $locals['messages'] ) ? $locals['messages'] : Ahrefs_Seo_Errors::get_current_messages();
if ( ! empty( $messages ) ) {
	$unique = [];
	foreach ( $messages as $item ) {
		$key = md5( $item['message'] ); // unique messages, any source.
		if ( ! isset( $unique[ $key ] ) ) {
			$unique[ $key ]          = $item;
			$unique[ $key ]['count'] = 1;
		} else {
			$unique[ $key ]['count']++;
		}
	}
	?>
	<div class="notice notice-error is-dismissible" id="ahrefs_api_messsages">
		<div id="ahrefs-messages">
			<?php
			if ( count( $unique ) ) {
				?>
				<span class="message-expanded-title">
				<?php
				printf(
					/* translators: %s: text "contact Ahrefs support" with link */
					esc_html__( 'Oops, seems like there was an error. Please %s to get it resolved.', 'ahrefs-seo' ),
					sprintf( '<a href="%s">%s</a>', esc_attr( Ahrefs_Seo::get_support_url( true ) ), esc_html__( 'contact Ahrefs support', 'ahrefs-seo' ) )
				);
				?>
			</span>
				<a href="#" class="message-expanded-link">
				<?php
				esc_html_e( '(Show more details)', 'ahrefs-seo' );
				?>
		</a>
				<div class="message-expanded-text">
					<?php
					foreach ( $unique as $key => $item ) {
						$title = Ahrefs_Seo_Errors::get_title_for_source( $item['source'] );
						?>
						<p id="<?php echo esc_attr( "message-id-{$key}" ); ?>" data-count="<?php echo esc_attr( "{$item['count']}" ); ?>" class="ahrefs-message">
							<b>
							<?php
							echo esc_html( $title );
							?>
			</b>:
							<?php
							echo esc_html( $item['message'] );
							?>
							<span class="ahrefs-messages-count
							<?php
							echo esc_attr( 1 === $item['count'] ? ' hidden' : '' ); ?>">
						<?php
						echo esc_html( "{$item['count']}" );
						?>
			</span>
						</p>
						<?php
						if ( 'compatibility' === $item['source'] ) {
							?>
							<a href="https://help.ahrefs.com/en/articles/4858501-why-is-my-wordpress-plugin-incompatible-with-the-ahrefs-seo-wordpress-plugin" target="_blank">
							<?php
							esc_html_e( 'Why’s this happening?', 'ahrefs-seo' );
							?>
				</a>
							<?php
						}
					}
					?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
	<?php
}
