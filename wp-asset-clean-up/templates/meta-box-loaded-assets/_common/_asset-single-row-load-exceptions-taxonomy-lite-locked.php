<?php
if (! isset($data)) {
	exit; // no direct access
}

switch ($data['post_type']) {
	case 'product':
		$loadBulkTextViaTax = __('On all WooCommerce "Product" pages if these taxonomies (e.g. Category, Tag) are set', 'wp-asset-clean-up');
		break;
	case 'download':
		$loadBulkTextViaTax = __('On all Easy Digital Downloads "Download" pages if these taxonomies (e.g. Category, Tag) are set', 'wp-asset-clean-up');
		break;
	default:
		$loadBulkTextViaTax = sprintf(__('On All Pages of "<strong>%s</strong>" post type if these taxonomies (e.g. Category, Tag) are set', 'wp-asset-clean-up'), $data['post_type']);
}

// "styles" to "style" & "scripts" to "script"
$assetTypeS = substr($data['row']['asset_type'], 0, -1);
?>
<!-- [wpacu_lite] -->
<li>
    <label for="wpacu_load_it_via_tax_option_<?php echo $assetTypeS; ?>_<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>">
        <input data-handle="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
               data-handle-for="<?php echo $assetTypeS; ?>"
               id="wpacu_load_it_via_tax_option_<?php echo $assetTypeS; ?>_<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
               class="wpacu_load_it_via_tax_checkbox wpacu_load_exception wpacu_load_rule_input wpacu_bulk_load wpacu_lite_locked"
               type="checkbox"
               name="<?php echo WPACU_FORM_ASSETS_POST_KEY; ?>[<?php echo $data['row']['asset_type']; ?>][load_it_post_type_via_tax][<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>][enable]"
               disabled="disabled"
               value="1"/>&nbsp;<span><?php echo $loadBulkTextViaTax; ?>:</span></label>
    <a class="go-pro-link-no-style"
       href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=manage_asset&utm_medium=load_exception_<?php echo $assetTypeS; ?>_post_type_via_tax"><span
                class="wpacu-tooltip wpacu-larger"><?php _e( 'This feature is available in the premium version of the plugin.',
				'wp-asset-clean-up' ); ?><br/> <?php _e( 'Click here to upgrade to Pro',
				'wp-asset-clean-up' ); ?>!</span><img width="20" height="20"
                                                      src="<?php echo esc_url(WPACU_PLUGIN_URL); ?>/assets/icons/icon-lock.svg"
                                                      valign="top" alt=""/></a>
    <a style="text-decoration: none; color: inherit; vertical-align: middle;" target="_blank"
       href="https://www.assetcleanup.com/docs/?p=1415#load_exception"><span
                class="dashicons dashicons-editor-help"></span></a>
</li>
<!-- [/wpacu_lite] -->
