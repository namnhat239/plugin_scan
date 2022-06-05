<?php
/**
 * Import/Export view.
 */

use RT\Team\Helpers\Fns;
use RT\Team\Helpers\Options;
?>

<div class="wrap export-import-wrapper">
	<h2><?php esc_html_e( 'TLP Team Export Import', 'tlp-team' ); ?></h2>
	<div id="settings-tabs" class="tlp-tabs rt-tab-container">
		<ul class="tab-nav rt-tab-nav">
			<li><a href="#export-settings">Export</a></li>
			<li><a href="#import-settings">Import</a></li>
		</ul>
		<div id="export-settings" class="rt-tab-content">
			<form id="team-export-form">
				<?php echo Fns::rtFieldGenerator( Options::exportFields() ); ?>
				<div class='tlp-field-holder'>
					<input type="submit" class="button button-primary" value="Export">
				</div>
				<div class="response"></div>
			</form>
		</div>
		<div id="import-settings" class="rt-tab-content">
			<form id="team-import-form" enctype="multipart/form-data">
				<div class='tlp-field-holder'>
					<div class='tlp-label'><label>Select a file to import</label></div>
					<div class='tlp-field'>
						<input type="file" name="import_file">
						<div class="description">Please select a .xslx/.xml/.JSON file</div>
					</div>
				</div>
				<div class='tlp-field-holder'>
					<input type="submit" class="button button-primary" value="Import">
					<div class="total-data-found"></div>
					<div class="completed-import"></div>
				</div>
			</form>
		</div>
	</div>

	<div id="response" class="updated"></div>
</div>
