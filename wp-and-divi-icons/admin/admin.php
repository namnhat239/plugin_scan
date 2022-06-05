<?php

$icon_options = AGS_Divi_Icons::$icon_packs['single_color'];

function ags_wadip_none_check($icon_options) {
	foreach ( $icon_options as $prefix => $pack ) {
		if ( ! empty($_POST["agsdi_{$prefix}_icons"]) ) {
			return false;
		}
	}

    

	return true;
}


function ags_wadip_return_label($prefix, $name, $enabled, $value = false, $type = 'input' ) {

	$output = sprintf(
		'<label>
            <input type="checkbox" class="agsdi_icons_%7$s" name="agsdi_%1$s_icons" value="%2$s" %3$s %4$s>
            <span class="agsdi-icon-set-name">%5$s</span>
            %6$s
        </label>',
		esc_attr($prefix),
		esc_attr($value),
		$value === 'yes' || $type === 'preview' && $enabled && $value === 'yes' ? 'checked' : '',
		$type === 'preview' && $enabled || ! $enabled ? "disabled" : '',
		esc_html($name),
		! $enabled ? '<span class="ds_icon_expansion_pro">PRO</span>' : '',
		esc_attr($type)
	);

	return $output;

}


	?>
    <div id="ds_icon_expansion-settings-container">
    <div id="ds_icon_expansion-settings">

        <div id="ds_icon_expansion-settings-header">
            <div class="ds_icon_expansion-settings-logo">
                <h1>
					<?php echo esc_html__('WP and Divi Icons', 'ds-icon-epxansion'); ?>
                    
                </h1>
            </div>
            <div id="ds_icon_expansion-settings-header-links">
                <a id="ds_icon_expansion-settings-header-link-support"
                   href="https://support.aspengrovestudios.com/article/418-wp-and-divi-icons"
                   target="_blank"><?php esc_html_e('Support', 'ds-icon-expansion'); ?></a>
            </div>
        </div>

        <ul id="ds_icon_expansion-settings-tabs">
            <li><a href="#icon-sets"><?php esc_html_e('Performance', 'ds-icon-expansion'); ?></a></li>
            
            <li class="ds_icon_expansion-settings-disabled"><a><?php esc_html_e('Multi-Color Icons', 'ds-icon-expansion'); ?><span class="ds_icon_expansion_pro">PRO</span></a></li>
            
            <li class="ds_icon_expansion-settings-active"><a href="#instructions"><?php esc_html_e('About & Instructions', 'ds-icon-expansion'); ?></a></li>
            
            <li><a href="#addons"><?php esc_html_e('Addons', 'ds-icon-expansionr') ?></a></li>
        </ul>

        <div id="ds_icon_expansion-settings-tabs-content">

			<?php
			// Handle the "Performance" tab form processing (select icon sets to load and use)

			if ( ! empty($_POST['wadip_nonce']) && wp_verify_nonce(sanitize_key($_POST['wadip_nonce']), 'wadip-save') && ! empty($_POST['agsdi-icon-sets']) ) {

				update_option('agsdi-legacy-sets-loading', empty($_POST['agsdi-legacy-sets-loading']) ? 0 : 1);

				// Icon sets form was posted (agsdi-icon-sets hidden form field is present)
				// Don't update stored settings in case user unchecked every option
				$none_checked           = ags_wadip_none_check($icon_options);
				$iconSetValidationError = false;

				if ( ! $none_checked ) {

					// Update icons sets to use based on checked status
					foreach ( $icon_options as $prefix => $pack ) {
						if ( ! empty($_POST["agsdi_{$prefix}_icons"]) ) {
							update_option("agsdi_{$prefix}_icons", 'yes', 'yes');
							$icon_options[ $prefix ]['value'] = "yes";
						} else {
							update_option("agsdi_{$prefix}_icons", 'no', 'yes');
							$icon_options[ $prefix ]['value'] = "no";
						}
					}

					if ( ! empty($_POST["agsdi_mc_packs"]) ) {
						update_option("agsdi_mc_packs", 'yes', 'yes');
						$multicolor_icons = 'yes';

					} else {
						update_option("agsdi_mc_packs", 'no', 'yes');
						$multicolor_icons = 'no';
					}


				} else {
					$iconSetValidationError = true;
				}
			} else if ( ! empty($_POST['agsdi-icon-sets']) ) {
				$iconSetValidationError = true;
			}
			// Show available icons sets. Allow user to select sets to use on site.
			// Unselected sets will not load and will not be available when building. ?>
            <div id="ds_icon_expansion-settings-icon-sets">
                <form id="agsdi-icon-sets" method="post">

                    


                    
                    
                    <div class="ds_icon_expansion-settings-box">
                        <h3><?php esc_html_e('Icon Packs', 'ds-icon-expansion'); ?></h3>
                        <p><?php printf(
				                esc_html__('Upgrade to %sWP and Divi Icons Pro%s to unlock additional icons plus any new icon packs we create.', 'ds-icon-expansion'),
				                '<a href="https://aspengrovestudios.com/product/wp-and-divi-icons/?utm_source=wadi&utm_medium=link&utm_campaign=wp-plugin-upgrade-link" target="_blank">',
				                '</a>'
			                )
			                ?></p>
                        <p><?php echo sprintf(
				                esc_html__('You can uncheck icon sets that you do not wish to load. This can help improve page builder performance by not including icons sets you do not use on your site.', 'ds-icon-expansion'),
				                esc_html(AGS_DIVI_ICONS::PLUGIN_NAME),
				                '<span data-icon="agsdix-sao-design" class="agsdi-icon"></span>'
			                ); ?>
                        </p>
                    </div>

                    <h4><?php echo(esc_html__('Single Color', 'ds-icon-expansion')); ?></h4>
                    <div class="agsdi-load-icon-sets-wrapper">
		                <?php
		                foreach ( $icon_options as $prefix => $pack ) {
			                $status = ! empty ( $pack['free'] );
			                echo ags_wadip_return_label($prefix,  $pack['name'], $status, $pack ['value']);
		                } ?>
                    </div>

                    <h4><?php echo(esc_html__('Multicolor', 'ds-icon-expansion')); ?></h4>
                    <div class="agsdi-load-icon-sets-wrapper">
		                <?php
		                foreach ( AGS_Divi_Icons::$icon_packs['multicolor'] as $key => $pack ) {
			                echo ags_wadip_return_label($key,  $pack['name'], false, false, 'preview');
		                }
		                ?>
                    </div>
                    


                    <input type="hidden" name="agsdi-icon-sets" id="agsdi-icon-sets" value="yes">
					<?php wp_nonce_field('wadip-save', 'wadip_nonce'); ?>

                    <button class="ds_icon_button-primary" ><?php echo(esc_html__('Save', 'ds-icon-expansion')); ?></button>

                </form>
            </div>
            

            <div id="ds_icon_expansion-settings-instructions" class="ds_icon_expansion-settings-active">
                <div class="ds_icon_expansion-settings-box">
                    <h3><?php echo(esc_html__('Instructions', 'ds-icon-expansion')); ?></h3>
					<?php
					
					$number_of_icons = '660+';
					
					
					?>
                    <p><?php echo sprintf(
							esc_html__('Easily insert one of the %s icons provided by %s when using the WordPress visual editor to create and edit posts, pages, and other content! Simply click on the %s icon in the editor\'s toolbar to open the icon insertion window.', 'ds-icon-expansion'),
							esc_html($number_of_icons),
							esc_html__(AGS_Divi_Icons::PLUGIN_NAME, 'ds-icon-expansion'),
							'<span data-icon="agsdix-sao-design" class="agsdi-icon"></span>'
						); ?>
                    </p>
                    <p><?php echo sprintf(
							esc_html__('If you use the %sDivi or Extra theme%s or the %sDivi Builder%s, you can also use the %s icons provided by %s anywhere that the Divi Builder allows you to specify an icon, such as in Buttons, Blurbs, and much more! Works in both the Divi Builder and the Visual Builder.', 'ds-icon-expansion'),
							'<a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=32248_0_1_10" target="_blank">',
							'</a>',
							'<a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=32248_0_1_10" target="_blank">',
							'</a>',
							esc_html($number_of_icons),
							esc_html(AGS_Divi_Icons::PLUGIN_NAME)
						); ?>
                    </p>
                </div>

                
                <div class="ds_icon_expansion-settings-box has-border-top">
                    <h3><?php echo(esc_html__('Check out these products too!', 'ds-icon-expansion')); ?></h3>
                    <ul id="ds_icon_expansion-settings-products">
						<?php
						foreach ( AGS_Divi_Icons::getCreditPromos('admin-page', true) as $promo ) {
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML; escaping happens in the getCreditPromos() function
							echo('<li>' . $promo . '</li>');
						}
						?>
                    </ul>
                </div>
                

            </div>
            

            <div id="ds_icon_expansion-settings-addons">
				<?php
				define('AGS_WADIP_ADDONS_URL', 'https://divi.space/wp-content/uploads/product-addons/wadip.json');
				require_once(plugin_dir_path(__FILE__) . '/addons/addons.php');
				AGS_WADIP_Addons::outputList();
				?>
            </div>

        </div>

    </div>

    <p><em><?php esc_html_e('Divi is a registered trademark of Elegant Themes, Inc. This product is not affiliated with nor endorsed by Elegant Themes. Links to the Elegant Themes website on this page are affiliate links.', 'ds-icon-expansion'); ?></em></p>

    <script>
        var ags_testify_tabs_navigate = function () {
            jQuery('#ds_icon_expansion-settings-tabs-content > div, #ds_icon_expansion-settings-tabs > li').removeClass('ds_icon_expansion-settings-active');
            jQuery('#ds_icon_expansion-settings-' + location.hash.substr(1)).addClass('ds_icon_expansion-settings-active');
            jQuery('#ds_icon_expansion-settings-tabs > li:has(a[href="' + location.hash + '"])').addClass('ds_icon_expansion-settings-active');
        };

        if (location.hash) {
            ags_testify_tabs_navigate();
        }

        jQuery(window).on('hashchange', ags_testify_tabs_navigate);
    </script>

	<?php
	
	