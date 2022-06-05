<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}
?>
<div class="wpacu_asset_options_wrap"
     style="padding: 8px 10px 6px !important;">
    <ul class="wpacu_asset_options">
        <li class="wpacu_unload_this_page">
            <label class="wpacu_switch wpacu_disabled wpacu-manage-hardcoded-assets-requires-pro-popup">
                <input data-handle="<?php echo esc_attr($data['row']['obj']->handle); ?>"
                       data-handle-for="script"
                       class="input-unload-on-this-page wpacu-not-locked wpacu_unload_rule_input wpacu_unload_rule_for_script"
                       id="script_<?php echo esc_attr($data['row']['obj']->handle); ?>"
					   disabled="disabled"
                       name="<?php echo WPACU_PLUGIN_ID; ?>[scripts][]"
                       type="checkbox"
                       value="<?php echo esc_attr($data['row']['obj']->handle); ?>" />
                <span class="wpacu_slider wpacu_round"></span>
            </label>
            <label class="wpacu_slider_text wpacu-manage-hardcoded-assets-requires-pro-popup" for="script_<?php echo esc_attr($data['row']['obj']->handle); ?>">
				<?php echo esc_html($data['page_unload_text']); ?>
            </label>
        </li>
    </ul>
</div>