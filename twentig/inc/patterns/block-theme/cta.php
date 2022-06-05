<?php
/**
 * Call-To-Action block patterns.
 *
 * @package twentig
 * @phpcs:disable Squiz.Strings.DoubleQuoteUsage.NotRequired
 */

twentig_register_block_pattern(
	'twentig/cta-colored-background',
	array(
		'title'      => __( 'CTA: Colored Background', 'twentig' ),
		'categories' => array( 'cta' ),
		'content'    => '<!-- wp:group {"backgroundColor":"tertiary","align":"full","layout":{"inherit":true}} --><div class="wp-block-group alignfull has-tertiary-background-color has-background"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Write a call to action heading', 'Block pattern content', 'twentig' ) . '</h2><!-- /wp:heading --><!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}},"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link">' . esc_html_x( 'Get started', 'Block pattern content', 'twentig' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->',
	)
);

twentig_register_block_pattern(
	'twentig/cta-colored-background-with-text',
	array(
		'title'      => __( 'CTA: Colored Background with Text', 'twentig' ),
		'categories' => array( 'cta' ),
		'content'    => '<!-- wp:group {"backgroundColor":"tertiary","align":"full","layout":{"inherit":true}} --><div class="wp-block-group alignfull has-tertiary-background-color has-background"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Write a heading', 'Block pattern content', 'twentig' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.5rem","lineHeight":1.4}}} --><p class="has-text-align-center" style="font-size:1.5rem;line-height:1.4">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.</p><!-- /wp:paragraph --><!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}},"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link">' . esc_html_x( 'Get started', 'Block pattern content', 'twentig' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->',
	)
);

twentig_register_block_pattern(
	'twentig/cta-wide-cover',
	array(
		'title'      => __( 'CTA: Wide Cover', 'twentig' ),
		'categories' => array( 'cta' ),
		'content'    => '<!-- wp:group {"align":"full","layout":{"inherit":true}} -->
		<div class="wp-block-group alignfull"><!-- wp:cover {"url":"' . twentig_get_pattern_asset( 'wide.jpg' ) . '","dimRatio":60,"minHeight":500,"align":"wide"} -->
		<div class="wp-block-cover alignwide" style="min-height:500px"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . twentig_get_pattern_asset( 'wide.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"inherit":true}} --><div class="wp-block-group"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Write a heading', 'Block pattern content', 'twentig' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":1.4,"fontSize":"1.5rem"}}} --><p class="has-text-align-center" style="font-size:1.5rem;line-height:1.4">Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore.</p><!-- /wp:paragraph --><!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}},"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button {"style":{"color":{"background":"white","text":"black"}}} --><div class="wp-block-button"><a class="wp-block-button__link has-text-color has-background" style="background-color:white;color:black">' . esc_html_x( 'Get started', 'Block pattern content', 'twentig' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group --></div></div><!-- /wp:cover --></div><!-- /wp:group -->',
	)
);

twentig_register_block_pattern(
	'twentig/cta-cover-with-buttons',
	array(
		'title'      => __( 'CTA: Cover with Buttons', 'twentig' ),
		'categories' => array( 'cta' ),
		'content'    => '<!-- wp:cover {"url":"' . twentig_get_pattern_asset( 'wide.jpg' ) . '","dimRatio":60,"minHeight":500,"align":"full"} --><div class="wp-block-cover alignfull" style="min-height:500px"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . twentig_get_pattern_asset( 'wide.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"inherit":true}} --><div class="wp-block-group"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Write a call to action heading to engage your audience', 'Block pattern content', 'twentig' ) . '</h2><!-- /wp:heading --><!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}},"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button {"style":{"color":{"background":"white","text":"black"}}} --><div class="wp-block-button"><a class="wp-block-button__link has-text-color has-background" style="background-color:white;color:black">' . esc_html_x( 'Get started', 'Block pattern content', 'twentig' ) . '</a></div><!-- /wp:button --><!-- wp:button {"style":{"color":{"text":"white"}},"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-text-color" style="color:white">' . esc_html_x( 'Learn more', 'Block pattern content', 'twentig' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group --></div></div><!-- /wp:cover -->',
	)
);

twentig_register_block_pattern(
	'twentig/cta-heading-on-left',
	array(
		'title'      => __( 'CTA: Heading on Left', 'twentig' ),
		'categories' => array( 'cta' ),
		'content'    => '<!-- wp:group {"align":"full","layout":{"inherit":true}} --><div class="wp-block-group alignfull"><!-- wp:columns {"align":"wide","twGutter":"large","twStack":"md","twVerticalGap":"small"} --><div class="wp-block-columns alignwide tw-gutter-large tw-cols-stack-md tw-row-gap-small"><!-- wp:column --><div class="wp-block-column"><!-- wp:heading --><h2>' . esc_html_x( 'Write a call to action heading', 'Block pattern content', 'twentig' ) . '</h2><!-- /wp:heading --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:paragraph {"style":{"typography":{"fontSize":"1.125em"}}} --><p style="font-size:1.125em">Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore et dolore. Integer enim risus suscipit eu iaculis sed, ullamcorper at metus.</p><!-- /wp:paragraph --><!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}}} --><div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link">' . esc_html_x( 'Get started', 'Block pattern content', 'twentig' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
	)
);

twentig_register_block_pattern(
	'twentig/cta-image-on-right',
	array(
		'title'      => __( 'CTA: Image on Right', 'twentig' ),
		'categories' => array( 'cta' ),
		'content'    => '<!-- wp:group {"align":"full","layout":{"inherit":true}} --><div class="wp-block-group alignfull"><!-- wp:media-text {"mediaPosition":"right","mediaType":"image","twStackedMd":true} --><div class="wp-block-media-text alignwide has-media-on-the-right is-stacked-on-mobile tw-stack-md"><figure class="wp-block-media-text__media"><img src="' . twentig_get_pattern_asset( 'landscape1.jpg' ) . '" alt=""/></figure><div class="wp-block-media-text__content"><!-- wp:heading --><h2>' . esc_html_x( 'Write a heading', 'Block pattern content', 'twentig' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph {"style":{"typography":{"fontSize":"1.125em"}}} --><p style="font-size:1.125em">Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore et dolore.</p><!-- /wp:paragraph --><!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}}} --><div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link">' . esc_html_x( 'Get started', 'Block pattern content', 'twentig' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div></div><!-- /wp:media-text --></div><!-- /wp:group -->',
	)
);

twentig_register_block_pattern(
	'twentig/cta-horizontal-card',
	array(
		'title'      => __( 'CTA: Horizontal Card', 'twentig' ),
		'categories' => array( 'cta' ),
		'content'    => '<!-- wp:group {"align":"full","layout":{"inherit":true}} --><div class="wp-block-group alignfull"><!-- wp:media-text {"mediaType":"image","imageFill":true,"className":"is-style-tw-shadow","twStackedMd":true} --><div class="wp-block-media-text alignwide is-stacked-on-mobile is-image-fill is-style-tw-shadow tw-stack-md"><figure class="wp-block-media-text__media" style="background-image:url(' . twentig_get_pattern_asset( 'landscape1.jpg' ) . ');background-position:50% 50%"><img src="' . twentig_get_pattern_asset( 'landscape1.jpg' ) . '" alt=""/></figure><div class="wp-block-media-text__content"><!-- wp:heading --><h2>' . esc_html_x( 'Write a heading', 'Block pattern content', 'twentig' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph {"style":{"typography":{"fontSize":"1.125em"}}} --><p style="font-size:1.125em">Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore.</p><!-- /wp:paragraph --><!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}}} -->
		<div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link">' . esc_html_x( 'Get started', 'Block pattern content', 'twentig' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div></div><!-- /wp:media-text --></div><!-- /wp:group -->',
	)
);

twentig_register_block_pattern(
	'twentig/cta-2-columns',
	array(
		'title'      => __( 'CTA: 2 Columns', 'twentig' ),
		'categories' => array( 'cta' ),
		'content'    => '<!-- wp:group {"align":"full","layout":{"inherit":true}} --><div class="wp-block-group alignfull"><!-- wp:columns {"align":"wide","twGutter":"large","twStack":"md"} --><div class="wp-block-columns alignwide tw-gutter-large tw-cols-stack-md"><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"textAlign":"center","fontSize":"xx-large"} --><h2 class="has-text-align-center has-xx-large-font-size">' . esc_html_x( 'Write a call to action heading to engage your audience', 'Block pattern content', 'twentig' ) . '</h2><!-- /wp:heading --><!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}},"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link">' . esc_html_x( 'Get started', 'Block pattern content', 'twentig' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --><!-- wp:separator {"className":"tw-mt-7 tw-lg-hidden is-style-wide"} --><hr class="wp-block-separator tw-mt-7 tw-lg-hidden is-style-wide"/><!-- /wp:separator --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"textAlign":"center","fontSize":"xx-large"} --><h2 class="has-text-align-center has-xx-large-font-size">' . esc_html_x( 'Write a call to action heading to engage your audience', 'Block pattern content', 'twentig' ) . '</h2><!-- /wp:heading --><!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}},"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link">' . esc_html_x( 'Contact us', 'Block pattern content', 'twentig' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
	)
);

twentig_register_block_pattern(
	'twentig/cta-2-columns-with-cover',
	array(
		'title'      => __( 'CTA: 2 Columns with Cover', 'twentig' ),
		'categories' => array( 'cta' ),
		'content'    => '<!-- wp:columns {"align":"full","twGutter":"no","twStack":"md","twTextAlign":"center"} --><div class="wp-block-columns alignfull tw-gutter-no tw-cols-stack-md has-text-align-center"><!-- wp:column --><div class="wp-block-column"><!-- wp:cover {"url":"' . twentig_get_pattern_asset( 'landscape1.jpg' ) . '","dimRatio":60,"twStretchedLink":true,"twHover":"opacity"} --><div class="wp-block-cover tw-stretched-link tw-hover-opacity"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . twentig_get_pattern_asset( 'landscape1.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"inherit":true}} --><div class="wp-block-group"><!-- wp:heading {"fontSize":"extra-large"} --><h2 class="has-extra-large-font-size">' . esc_html_x( 'Write a heading', 'Block pattern content', 'twentig' ) . '</h2><!-- /wp:heading --><!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}},"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button {"style":{"color":{"text":"black","background":"white"}}} --><div class="wp-block-button"><a class="wp-block-button__link has-text-color has-background" style="background-color:white;color:black">' . esc_html_x( 'Get started', 'Block pattern content', 'twentig' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group --></div></div><!-- /wp:cover --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:cover {"url":"' . twentig_get_pattern_asset( 'landscape2.jpg' ) . '","dimRatio":60,"twStretchedLink":true,"twHover":"opacity"} --><div class="wp-block-cover tw-stretched-link tw-hover-opacity"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . twentig_get_pattern_asset( 'landscape2.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"inherit":true}} --><div class="wp-block-group"><!-- wp:heading {"fontSize":"extra-large"} --><h2 class="has-extra-large-font-size">' . esc_html_x( 'Write a heading', 'Block pattern content', 'twentig' ) . '</h2><!-- /wp:heading --><!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}},"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button {"style":{"color":{"text":"black","background":"white"}}} --><div class="wp-block-button"><a class="wp-block-button__link has-text-color has-background" style="background-color:white;color:black">' . esc_html_x( 'Contact us', 'Block pattern content', 'twentig' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group --></div></div><!-- /wp:cover --></div><!-- /wp:column --></div><!-- /wp:columns -->',
	)
);
