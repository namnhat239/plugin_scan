<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}

// Unload it if the post has a certain "Category", "Tag" or other taxonomy associated with it.

// Only show it if "Unload site-wide" is NOT enabled
// Otherwise, there's no point to use this unload rule based on the chosen taxonomy's value if the asset is unloaded site-wide

// This is a LITE version of this feature
if (! $data['row']['global_unloaded']) {
	?>
    <div class="wpacu_asset_options_wrap wpacu_manage_via_tax_area_wrap">
        <ul class="wpacu_asset_options">
            <?php
            switch ($data['post_type']) {
	            case 'product':
		            $unloadViaTaxText = __('Unload JS on all WooCommerce "Product" pages if these taxonomies (e.g. Category, Tag) are set', 'wp-asset-clean-up');
		            break;
	            case 'download':
		            $unloadViaTaxText = __('Unload JS on all Easy Digital Downloads "Download" pages if these taxonomies (e.g. Category, Tag) are set', 'wp-asset-clean-up');
		            break;
	            default:
		            $unloadViaTaxText = sprintf(__('Unload on All Pages of "<strong>%s</strong>" post type if these taxonomies (category, tag, etc.) are set', 'wp-asset-clean-up'), $data['post_type']);
            }
            ?>
            <li>
                <label for="wpacu_unload_it_via_tax_option_script_<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>">
                    <input data-handle="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
                           data-handle-for="script"
                           id="wpacu_unload_it_via_tax_option_script_<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
                           class="wpacu_unload_it_via_tax_checkbox wpacu_unload_rule_input wpacu_bulk_unload wpacu_lite_locked"
                           type="checkbox"
                           disabled="disabled"
                           name="<?php echo WPACU_FORM_ASSETS_POST_KEY; ?>[scripts][unload_post_type_via_tax][<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>][enable]"
                           value="1"/>&nbsp;<span><?php echo $unloadViaTaxText; ?>:</span></label>
                <a class="go-pro-link-no-style"
                   href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=manage_asset&utm_medium=unload_script_post_type_via_tax"><span
                            class="wpacu-tooltip wpacu-larger"><?php _e( 'This feature is available in the premium version of the plugin.',
				            'wp-asset-clean-up' ); ?><br/> <?php _e( 'Click here to upgrade to Pro',
				            'wp-asset-clean-up' ); ?>!</span><img width="20" height="20"
                                                                  src="<?php echo esc_url(WPACU_PLUGIN_URL); ?>/assets/icons/icon-lock.svg"
                                                                  valign="top" alt=""/></a>
                <a style="text-decoration: none; color: inherit; vertical-align: middle;" target="_blank"
                   href="https://www.assetcleanup.com/docs/?p=1415#unload"><span class="dashicons dashicons-editor-help"></span></a>
            </li>
        </ul>
    </div>
	<?php
}
