<?php
/**
 * Taxonomy order view.
 */

use RT\Team\Helpers\Fns;

$taxonomy_objects = Fns::rt_get_all_taxonomy_by_post_type();
?>

<div class="wrap">
	<h2><?php esc_html_e( 'Taxonomy Ordering', 'tlp-team' ); ?></h2>
	<?php if ( ! function_exists( 'get_term_meta' ) ) { ?>
		<div class="update-message notice inline notice-error notice-alt"><p>Please update your WordPress to 4.4.0 or
				latest version to use taxonomy order functionality.</p></div>
	<?php } ?>
	<div class="ttp-taxonomy-wrapper">
		<label>Select Taxonomy</label>
		<select class="tlp-select" id="ttp-taxonomy">
			<option value="">Select one taxonomy</option>
			<?php
			if ( ! empty( $taxonomy_objects ) ) {
				foreach ( $taxonomy_objects as $key => $taxonomy ) {
					echo "<option value='{$key}'>{$taxonomy}</option>";
				}
			}
			?>
		</select>
	</div>
	<div class="ordering-wrapper">
		<div id="term-wrapper">
			<p>No taxonomy selected</p>
		</div>
	</div>
</div>
