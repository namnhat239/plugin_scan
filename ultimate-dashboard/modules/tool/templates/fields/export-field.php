<?php
/**
 * Export field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	?>

	<p><?php _e( 'Select the settings you would like to export and use the button below to generate & export a .json file.', 'ultimate-dashboard' ); ?></p>
	<br>
	<p>
		<label>
			<input type="checkbox" name="udb_export_modules[]" class="udb-module-checkbox" value="modules_manager" checked />
			<?php _e( 'Module Manager', 'ultimate-dashboard' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="udb_export_modules[]" class="udb-module-checkbox" value="settings" checked />
			<?php _e( 'Settings', 'ultimate-dashboard' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="udb_export_modules[]" class="udb-module-checkbox" value="widgets" checked />
			<?php _e( 'Dashboard Widgets', 'ultimate-dashboard' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="udb_export_modules[]" class="udb-module-checkbox" value="branding" checked />
			<?php _e( 'White Label Settings', 'ultimate-dashboard' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="udb_export_modules[]" class="udb-module-checkbox" value="login_customizer" checked />
			<?php _e( 'Login Customizer Settings', 'ultimate-dashboard' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="udb_export_modules[]" class="udb-module-checkbox" value="login_redirect" checked />
			<?php _e( 'Login Redirect Settings', 'ultimate-dashboard' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="udb_export_modules[]" class="udb-module-checkbox" value="admin_pages" checked />
			<?php _e( 'Admin Pages', 'ultimate-dashboard' ); ?>
		</label>
	</p>

	<?php do_action( 'udb_export_fields' ); ?>

	<br>
	<p>
		<a href="#" class="udb-select-all-modules">Select All</a>
	</p>

	<?php

};
