<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-style-single-row.php
*/
if (! isset($data, $tagType)) {
	exit; // no direct access
}
?>
<div class="wpacu-handle-notes">
	<p><small>No notes have been added about this hardcoded <?php echo esc_html($tagType); ?> tag (e.g. why you unloaded it or decided to keep it loaded) &#10230; <a data-handle="<?php echo esc_attr($data['row']['obj']->handle); ?>" href="#" class="wpacu-manage-hardcoded-assets-requires-pro-popup wpacu-add-handle-note wpacu-for-style"><span style="color: #ccc; font-size: 20px;" class="wpacu-manage-hardcoded-assets-requires-pro-popup dashicons dashicons-lock"></span> <label for="wpacu_handle_note_<?php echo esc_attr($data['row']['obj']->handle); ?>">Add Note</label></a></small></p>
</div>