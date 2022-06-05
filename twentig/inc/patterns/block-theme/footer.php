<?php
/**
 * Footer block patterns.
 *
 * @package twentig
 */

twentig_register_block_pattern(
	'twentig/footer-inline-copyright-and-social-links',
	array(
		'title'         => __( 'Footer Inline: Copyright and Social Links', 'twentig' ),
		'categories'    => array( 'footer' ),
		'blockTypes'    => array( 'core/template-part/footer' ),
		'content'       => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"45px","bottom":"45px"}},"color":{"background":"#f5f5f5"}},"textColor":"black","layout":{"inherit":true}} --><div class="wp-block-group has-black-color has-text-color has-background" style="background-color:#f5f5f5;padding-top:45px;padding-bottom:45px"><!-- wp:group {"align":"wide","layout":{"type":"flex","allowOrientation":false,"justifyContent":"space-between"}} --><div class="wp-block-group alignwide"><!-- wp:paragraph {"style":{"typography":{"fontSize":"0.9375em"}}} --><p style="font-size:0.9375em">' . esc_html_x( '© 2022 Site Title. Made with Twentig.', 'Block pattern content', 'twentig' ) . '</p><!-- /wp:paragraph --><!-- wp:social-links {"iconColor":"black","iconColorValue":"#000000","size":"has-small-icon-size","className":"is-style-logos-only","layout":{"type":"flex"},"style":{"spacing":{"blockGap":"20px"}},"twHover":"opacity"} --><ul class="wp-block-social-links has-small-icon-size has-icon-color is-style-logos-only tw-hover-opacity"><!-- wp:social-link {"url":"#","service":"twitter"} /--><!-- wp:social-link {"url":"#","service":"instagram"} /--><!-- wp:social-link {"url":"#","service":"facebook"} /--></ul><!-- /wp:social-links --></div><!-- /wp:group --></div><!-- /wp:group -->',
	)
);

twentig_register_block_pattern(
	'twentig/footer-inline-2-rows',
	array(
		'title'         => __( 'Footer Inline: 2 Rows', 'twentig' ),
		'categories'    => array( 'footer' ),
		'blockTypes'    => array( 'core/template-part/footer' ),
		'content'       => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"45px","bottom":"45px"}},"color":{"background":"#111111"}},"textColor":"white","layout":{"inherit":false}} --><div class="wp-block-group has-white-color has-text-color has-background" style="background-color:#111111;padding-top:45px;padding-bottom:45px"><!-- wp:group {"layout":{"type":"flex","allowOrientation":false,"justifyContent":"space-between"}} --><div class="wp-block-group"><!-- wp:site-title {"level":2,"isLink":false,"style":{"typography":{"lineHeight":"1.1"}},"fontSize":"large"} /--><!-- wp:social-links {"iconColor":"white","iconColorValue":"#ffffff","size":"has-small-icon-size","className":"is-style-logos-only","layout":{"type":"flex"},"style":{"spacing":{"blockGap":"20px"}},"twHover":"opacity"} --><ul class="wp-block-social-links has-small-icon-size has-icon-color is-style-logos-only tw-hover-opacity"><!-- wp:social-link {"url":"#","service":"twitter"} /--><!-- wp:social-link {"url":"#","service":"instagram"} /--><!-- wp:social-link {"url":"#","service":"facebook"} /--></ul><!-- /wp:social-links --></div><!-- /wp:group --><!-- wp:separator {"customColor":"#383838","className":"is-style-wide"} --><hr class="wp-block-separator has-text-color has-background is-style-wide" style="background-color:#383838;color:#383838"/><!-- /wp:separator --><!-- wp:group {"layout":{"type":"flex","allowOrientation":false,"justifyContent":"space-between"}} --><div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontSize":"0.875em"}}} --><p style="font-size:0.875em">' . esc_html_x( '© 2022 Site Title. Made with Twentig.', 'Block pattern content', 'twentig' ) . '</p><!-- /wp:paragraph --><!-- wp:navigation {"overlayMenu":"never","style":{"typography":{"fontSize":"0.875em"}},"twGap":"medium"} --><!-- wp:navigation-link {"isTopLevelLink":true} /--><!-- wp:navigation-link {"isTopLevelLink":true} /--><!-- wp:navigation-link {"isTopLevelLink":true} /--><!-- /wp:navigation --></div><!-- /wp:group --></div><!-- /wp:group -->',
	)
);

twentig_register_block_pattern(
	'twentig/footer-stack-navigation',
	array(
		'title'         => __( 'Footer Stack: Navigation', 'twentig' ),
		'categories'    => array( 'footer' ),
		'blockTypes'    => array( 'core/template-part/footer' ),
		'content'       => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"65px","bottom":"65px"}},"elements":{"link":{"color":{"text":"var:preset|color|black"}}},"color":{"background":"#f5f5f5"}},"textColor":"black","layout":{"inherit":true}} --><div class="wp-block-group has-black-color has-text-color has-background has-link-color" style="background-color:#f5f5f5;padding-top:65px;padding-bottom:65px"><!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","justifyContent":"center"}} --><!-- wp:navigation-link {"isTopLevelLink":true} /--><!-- wp:navigation-link {"isTopLevelLink":true} /--><!-- wp:navigation-link {"isTopLevelLink":true} /--><!-- /wp:navigation --><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"0.875em"}}} --><p class="has-text-align-center" style="font-size:0.875em">' . esc_html_x( '© 2022 Site Title. Made with Twentig.', 'Block pattern content', 'twentig' ) . '</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
	)
);

twentig_register_block_pattern(
	'twentig/footer-2-columns-text-and-navigation',
	array(
		'title'         => __( 'Footer 2 Columns: Text and Navigation', 'twentig' ),
		'categories'    => array( 'footer' ),
		'blockTypes'    => array( 'core/template-part/footer' ),
		'content'       => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"65px","bottom":"45px"},"blockGap":"60px"},"color":{"background":"#f5f5f5"}},"textColor":"black","layout":{"inherit":true}} --><div class="wp-block-group alignfull has-black-color has-text-color has-background" style="background-color:#f5f5f5;padding-top:65px;padding-bottom:45px"><!-- wp:columns {"align":"wide"} --><div class="wp-block-columns alignwide"><!-- wp:column --><div class="wp-block-column"><!-- wp:site-title {"isLink":false,"style":{"typography":{"lineHeight":"1.1"}},"fontSize":"large"} /--><!-- wp:paragraph {"style":{"typography":{"fontSize":"0.9375em"}}} --><p style="font-size:0.9375em">16 Thompson Street<br>San Francisco, CA 94102</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"width":"200px"} --><div class="wp-block-column" style="flex-basis:200px"><!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical"},"style":{"typography":{"fontSize":"0.9375em"}},"twGap":"small"} --><!-- wp:navigation-link {"isTopLevelLink":true} /--><!-- wp:navigation-link {"isTopLevelLink":true} /--><!-- wp:navigation-link {"isTopLevelLink":true} /--><!-- /wp:navigation --></div><!-- /wp:column --></div><!-- /wp:columns --><!-- wp:group {"align":"wide","layout":{"type":"flex","allowOrientation":false}} --><div class="wp-block-group alignwide"><!-- wp:paragraph {"style":{"typography":{"fontSize":"0.875em"}}} --><p style="font-size:0.875em">' . esc_html_x( '© 2022 Site Title. Made with Twentig.', 'Block pattern content', 'twentig' ) . '</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:group -->',
	)
);
