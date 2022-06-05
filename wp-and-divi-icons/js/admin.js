/*
WP and Divi Icons by Divi Space, an Aspen Grove Studios company
Licensed under the GNU General Public License v3 (see ../license.txt)

This plugin includes code based on parts of the Divi theme and/or the
Divi Builder by Elegant Themes, licensed GPLv2, used under GPLv3  (see ../license.txt).
*/



jQuery(document).ready(function($) {
	function getPromo() {
		return ags_divi_icons_credit_promos[Math.floor(Math.random() * ags_divi_icons_credit_promos.length)];
	}
	setInterval(function() {
		 $('.agsdi-picker-credit-promo:empty:visible').fadeOut(300, function() {
			var $promo = $(this).html(getPromo());
			$promo.siblings('.agsdi-picker-credit-separator').remove();
			$('<span>')
				.addClass('agsdi-picker-credit-separator')
				.hide()
				.html(' &bull; ')
				.insertBefore($promo)
				.add($promo)
				.fadeIn(300);
		});
	}, 1000);
	setInterval(function() {
		 $('.agsdi-picker-credit-promo:parent:visible').fadeOut(300, function() {
			var $promo = $(this);
			var oldPromoContentText = $promo.text();
			do {
				var newPromoContent = getPromo();
				var newPromoContentText = $('<div>').html(newPromoContent).text();
			} while (newPromoContentText === oldPromoContentText);
			$promo.html(newPromoContent).fadeIn(300);
		});
	}, 6600);

	$('#agsdi-colors-add').click(function() {
		var $button = $(this);
		var $colorPreview = $('<div>').addClass('agsdi-color-preview');
		var $scheme = $('<div>')
						.attr('data-colors-id', 'new')
						.append($colorPreview)
						.append($('<input>').attr({type: 'text', name: 'agsdi_colors[new][]'}).val('#6C75E2'))
						.append($('<input>').attr({type: 'text', name: 'agsdi_colors[new][]'}).val('#FEB480'))
						.append($('<input>').attr({type: 'text', name: 'agsdi_colors[new][]'}).val('#E6E7E8'))
						.append($('<button>').attr({type: 'button'}).addClass('agsdi-colors-remove button-secondary').text(wp.i18n.__('Remove', 'ds-icon-expansion')))
						.insertBefore($button.parent());

		createColorPreview($colorPreview);
		$scheme
			.find('input[type=\'text\']')
			.wpColorPicker({
				change:onColorsColorChange
			});
		onColorsColorChange($scheme);
		$button.hide();
		$('#agsdi-color-schemes-none').addClass('hidden');
	});

	$('#agsdi-color-schemes > div').each(function() {
		var $scheme = $(this);
		createColorPreview($scheme.find('.agsdi-color-preview'));
		$scheme
			.find('input[type=\'text\']')
			.wpColorPicker({
				change:onColorsColorChange
			});
		onColorsColorChange($scheme);
	});

	$('#agsdi-color-schemes').on('click', '.agsdi-colors-remove', function() {
		var $colorScheme = $(this).closest('[data-colors-id]');
		var wasLastColorScheme = !$colorScheme.siblings('[data-colors-id]').length;
		$colorScheme.remove();
		if (wasLastColorScheme) {
			$('#agsdi-color-schemes-none').removeClass('hidden');
		}
		if ($colorScheme.attr('data-colors-id') === 'new') {
			$('#agsdi-colors-add').show();
		}
	});


	function createColorPreview(container) {
		for (var i = 0; i < agsdi_color_preview_icons.length; ++i) {
			$('<div>').attr('data-icon', agsdi_color_preview_icons[i]).appendTo(container);
		}
	}

	function onColorsColorChange($scheme) {
		var $scheme = $scheme.length ? $scheme : $(this).closest('[data-colors-id]');
		var $inputs = $scheme.find('input[type=\'text\']');
		var colorsId = $scheme.attr('data-colors-id');
		var $style = $('#agsdi-color-preview-style-' + colorsId);
		if (!$style.length) {
			$style = $('<style>').attr('id', '#agsdi-color-preview-style-' + colorsId).appendTo('head:first');
		}
		$style.html('#agsdi-color-schemes > [data-colors-id=\'' + colorsId + '\'] .agsdi-color-preview div:before{background-image:url(\'' + ajaxurl + '?action=agsdi_colorize_preview&colors[]=' + encodeURIComponent($inputs.eq(0).wpColorPicker('color')) + '&colors[]=' + encodeURIComponent($inputs.eq(1).wpColorPicker('color')) + '&colors[]=' + encodeURIComponent($inputs.eq(2).wpColorPicker('color')) + '\'); }');
	}
	
	var fbApp = $(document.body).hasClass('divi_page_et_theme_builder') ? $(document.body) : $('#et-fb-app');

	if (fbApp.length) {
		var MO = window.MutationObserver ? window.MutationObserver : window.WebkitMutationObserver;
		if (MO) {

			(new MO(function(events) {
				for (var i = 0; i < events.length; ++i) {
					var event = events[i];

					if (event.addedNodes && event.addedNodes.length) {
						$(event.addedNodes).find('#et-fb-multicolor_icon_select').addBack('#et-fb-multicolor_icon_select').each(function() {
							var iconStyle = $('.et-fb-option--WadipETBuilderControlMultipleButtons .et-fb-multiple-buttons-toggle-internal__active:first').closest('[data-option_value]').data('option_value');
							window.agsdi_multicolor.enable(iconStyle.substring(4));
						});

					}

					if (event.removedNodes && event.removedNodes.length) {
						$(event.removedNodes).find('#et-fb-multicolor_icon_select').addBack('#et-fb-multicolor_icon_select').each(function() {
							window.agsdi_multicolor.disable();
						});
					}
				}
			})).observe(fbApp[0], {childList: true, subtree: true});

		}

		fbApp.on('click', '#et-fb-icon_picker li svg', function(ev) {
			ev.stopPropagation();
			$(ev.target).parent().click();
		});

	}

	$('.agsdi-notice').on('click', '.notice-dismiss', function() {
		var $notice = $(this).closest('.agsdi-notice');
		$.post(ajaxurl, {action: 'agsdi_hide_notice', notice: $notice.attr('id').substring(13)});
		$notice.hide();
	});


});

window.agsdi_multicolor = {
	icons: {
	uni: [
		{
			id: 'universal-gen',
			prefix: 'mcip-uni-gen-',
			label: 'Universal General',
			icons: ['clock',  'map', 'arrow_left', 'community', 'arrow_right', 'content', 'meal', 'minus', 'backup', 'speedometer', 'music', 'star', 'bell', 'new', 'disabled', 'stats',  'sun', 'branding', 'targeting', 'energy', 'time', 'plus', 'building', 'user', 'voting', 'puzzle', 'warehouse', 'camera', 'hashtag', 'question','plane',  'car', 'idea', 'rain', 'wind', 'cloudy', 'location', 'sea', 'chat', 'info', 'range', 'x', 'check', 'social_media' ]
		},
		{
			id: 'universal-off',
			prefix: 'mcip-uni-off-',
			label: 'Universal Office',
			icons: [ 'coffee', 'email', 'phone', 'briefcase', 'calendar', 'assistant', 'calculator']
		},
		{
			id: 'universal-eco',
			prefix: 'mcip-uni-eco-',
			label: 'Universal Ecommerce',
			icons: ['add_to_cart', 'shipping', 'shopping_list', 'bank_account', 'online_purchase', 'sale', 'refund', 'credit_card', 'buy', 'filter', 'price_tag', 'currency', 'money', 'stationary_shop', 'office', 'basket_empty', 'donation', 'guarantee', 'promotion', 'wallet', 'price_comparison', 'seo',]
		},
		{
			id: 'universal-web',
			prefix: 'mcip-uni-web-',
			label: 'Universal Web',
			icons: ['laptop', 'link', 'marketing', 'advertisement', 'analytics', 'search',  'design',  'devices', 'browser', 'hamburger_menu']
		},
		{
			id: 'universal-bra',
			prefix: 'mcip-uni-bra-',
			label: 'Universal Brands',
			icons: ['twitter', 'divi', 'facebook', 'instagram', 'youtube','wordpress']
		},
		{
			id: 'universal-edu',
			prefix: 'mcip-uni-edu-',
			label: 'Universal Education',
			icons: ['abacus', 'chemistry', 'school', 'motherboard', 'diploma', 'billboard', 'blackboard', 'student', 'books', 'openbook', 'exam']
		}
	],
	lin: [
		{
			id: 'lineal-cle',
			prefix: 'mcip-lin-cle-',
			label: 'Lineal Cleaning',
			icons: ['apron', 'contact', 'house', 'tap', 'bottle', 'detergent', 'localization', 'towel', 'broom', 'dish', 'mop', 'washing_machine', 'brush', 'dust', 'phone', 'washing', 'bucket', 'email', 'plunger', 'clean_windows', 'garbage', 'recycle_bin', 'cleaning_products', 'glove', 'sponge',]
		}
	],
	out: [
		{
			id: 'outline-gen',
			prefix: 'mcip-out-gen-',
			label: 'Outline General',
			icons: ['calendar', 'flower', 'chat', 'gear', 'check', 'heart', 'honey', 'post', 'user', 'location', 'lock',  'drop']
		},
		{
			id: 'outline-web',
			prefix: 'mcip-out-web-',
			label: 'Outline Web',
			icons: ['phone', 'tablet', 'cursor', 'loupe', 'shield', 'web', 'mail']
		},
		{
			id: 'outline-eco',
			prefix: 'mcip-out-eco-',
			label: 'Outline Ecommerce',
			icons: ['basket', 'money', 'credit_card', 'delivery', 'shipment', 'support']
		},
		{
			id: 'outline-hea',
			prefix: 'mcip-out-hea-',
			label: 'Outline Health',
			icons: ['dna', 'virus', 'medical_bag','flask', 'stethoscope', 'microscope','syringe', 'syrup', 'pill', 'medical_cross','thermometer', 'herbs', 'pipette', 'tooth', 'cross', 'pulse', 'wheelchair', 'vessel', 'vial', 'diagnosis', 'scissors', 'dressing', 'eye',]
		},
		{
			id: 'outline-spo',
			prefix: 'mcip-out-spo-',
			label: 'Outline Sport',
			icons: ['run', 'weights',]
		},
	],
	mul: [
		{
			id: 'multicolor-gen',
			prefix: 'mcip-mul-gen-',
			label: 'Multicolor General',
			icons: [  'new',  'notepad', 'settings', 'email',  'share',  'phone', 'localization',  'calendar',  'question',  'report', 'time',  'map',  'currency', 'search', ]
		},
		{
			id: 'multicolor-eco',
			prefix: 'mcip-mul-eco-',
			label: 'Multicolor Ecommerce',
			icons: ['online_purchase', 'bank_account', 'basket_full', 'empty_basket', 'shopping_list', 'shipping',  'price_tag', 'guarantee', 'storehouse',  'refund', 'stationary_shop', 'credit_card', 'sale', 'wallet', 'donation',]
		},
		{
			id: 'multicolor-web',
			prefix: 'mcip-mul-web-',
			label: 'Multicolor Web',
			icons: ['design_tool', 'backup', 'browser', 'font', 'pipette', 'cache', 'cloud', 'form', 'image', 'tablet', 'code', 'archive', 'server', 'responsive', 'upload', 'data_base', 'website', 'mouse', 'mobile', ]
		}
	],
	han: [
		{
			id: 'hand-drawn-gen',
			prefix: 'mcip-han-gen-',
			label: 'Hand Drawn General',
			icons: [ 'minus_circle', 'minus_with_accent', 'money_accent', 'money_with_heart', 'ask', 'download', 'music', 'ask_2', 'percentage_with_accent', 'pig_money', 'plant',  'plus_with_accent',  'present', 'bulb', 'sign', 'bulb_leaf_with_accent',  'sun', 'card', 'sun_with_heart', 'carrier', 'team', 'team_all', 'cat_3', 'doctor' ]
		},
		{
			id: 'hand-drawn-off',
			prefix: 'mcip-han-off-',
			label: 'Hand Drawn Office',
			icons: [ 'location', 'location_2', 'mail', 'list_with_accent', 'pencil', 'book_with_accent', 'calendar', 'time', 'coffee',  'contact',]
		},
		{
			id: 'hand-drawn-ani',
			prefix: 'mcip-han-ani-',
			label: 'Hand Drawn Animals',
			icons: ['dog', 'dog_face', 'cat', 'hand_with_heart', 'hands_with_paw', 'heart_animal_right_with_accent', 'heart_animal_with_accent', 'bird_with_accent', ]
		},
		{
			id: 'hand-drawn-non',
			prefix: 'mcip-han-non-',
			label: 'Hand Drawn Nonprofit',
			icons: [ 'hand_leaf', 'hands','house_with_accent', 'leaf_heart', 'drop_with_accent', 'bowl', 'bowl_2', 'bowl_3',  'sleep', 'bread',  'world_with_heart', ]
		},
		{
			id: 'hand-drawn-bra',
			prefix: 'mcip-han-bra-',
			label: 'Hand Drawn Brands',
			icons: ['instagram', 'facebook', 'twitter', 'youtube']
		},
		{
			id: 'hand-drawn-num',
			prefix: 'mcip-han-num-',
			label: 'Hand Drawn Numbers',
			icons: [ 'number_one', '0_accent', '1_accent', '2_accent', '3_accent', '4_accent', '5_accent', '6_accent', '7_accent', '8_accent', '9_accent', '10_accent', '0_circle_accent', '1_circle_accent', '2_circle_accent', '3_circle_accent', '4_circle_accent', '5_circle_accent', '6_circle_accent', '7_circle_accent', '8_circle_accent', '9_circle_accent', '10_circle_accent']
		}
	],
	fil: [
		{
			id: 'filled-fil-gen',
			prefix: 'mcip-fil-gen-',
			label: 'Filled',
			icons: ['mail', 'settings', 'shield', 'calendar', 'gear', 'umbrella', 'check', 'user', 'lock', 'post', ]
		},
		{
			id: 'filled-eco',
			prefix: 'mcip-fil-eco-',
			label: 'Filled Ecommerce',
			icons: ['location', 'basket', 'shipment', 'credit_card', 'delivery', 'phone', 'support', 'money', 'setup', 'chat' ]
		},
		{
			id: 'filled-web',
			prefix: 'mcip-fil-web-',
			label: 'Filled Website',
			icons: ['web', 'cursor', 'desktop',]
		},
		{
			id: 'filled-pho',
			prefix: 'mcip-fil-pho-',
			label: 'Filled Photography',
			icons: ['digital_camera', 'day', 'movie_camera','energy','cable', 'eye', 'camera_lens','pause',  'plate', 'night', 'pendrive',  'portrait', 'webcam','battery', 'reel', 'camera', 'frame', 'film_roll', 'trash', 'photostock', 'lamp', 'pictures', 'lens', 'contrast', 'light', 'play','zoom_in','record', 'zoom_out', 'retro_camera', 'memory_card',]
		}
	],
	ske: [
		{
			id: 'sketch-gen',
			prefix: 'mcip-ske-gen-',
			label: 'Sketch General',
			icons: [ 'appointment', 'calendar', 'localization', 'search',  'contact', 'conversation', 'newsletter',  'note', 'stop', 'email', 'phone', 'group_of_people', 'popcorn']
		},
		{
			id: 'sketch-pod',
			prefix: 'mcip-ske-pod-',
			label: 'Sketch Podcast',
			icons: ['listening', 'application', 'music_program', 'sound_turned_on','headphones','radio','instrument', 'clapperboard', 'record', 'sound_wave', 'dictaphone', 'assembly', 'laptop_with_speakers', 'rewind', 'book_woth_notes', 'list_of_files', 'rewind_backward',  'rewind_forward', 'video_editing', 'equalizer', 'play', 'cloud_with_files', 'microphone', 'sound_assembly', 'computer_speaker', 'music_disc', 'sound_turned_off', ]
		}
	],
	tri: [
		{
			id: 'tri-color-gen',
			prefix: 'mcip-tri-gen-',
			label: 'Tri-Color General',
			icons: [ 'check', 'gear', 'settings', 'lock', 'mail', 'support', 'calendar', 'user', 'chat', 'delete', 'phone' ]
		},
		{
			id: 'tri-color-eco',
			prefix: 'mcip-tri-eco-',
			label: 'Tri-Color eCommerce',
			icons: [ 'basket', 'delivery', 'location','shipment',  'credit_card', 'buil-in_badges_and_ratings', 'dynamic_product_sorting', 'money', ]
		},
		{
			id: 'tri-color-web',
			prefix: 'mcip-tri-web-',
			label: 'Tri-Color Website',
			icons: ['backend_and_api_development', 'delete_document', 'post', 'web', 'cloud_protection', 'programist', 'website_speed', 'website_speed_test', 'worldclass_support',  'processor', 'cursor', 'shield', 'cancel',  'website_devolpment', 'bugs_test', 'database_settings',  'settings_list', 'web_map', 'update', 'blockchain_integration', 'easy_style_controls', 'content_download', 'divi_integated','roadmap_and_strategy', 'mobile_app_development', 'proxy', 'database_update', 'elements_and_display_order', 'multiple_navigation_options', 'scalability', 'server_protection', 'user_centric_experiences', 'folders_update', 'programming_diploma', 'download',  ]
		}
	],
	ele: [
		{
			id: 'elegant-gen',
			prefix: 'mcip-ele-gen-',
			label: 'Elegant General',
			icons: [ 'cursor', 'invitation', 'mail', 'calendar', 'user',  'music', 'web', 'gear', 'phone', 'chat', 'check', 'post',  ]
		},
		{
			id: 'elegant-eco',
			prefix: 'mcip-ele-eco-',
			label: 'Elegant eCommerce',
			icons: ['shield', 'basket', 'shipment','location', 'support','delivery', 'money', 'credit',]
		},
		{
			id: 'elegant-wed',
			prefix: 'mcip-ele-wed-',
			label: 'Elegant Wedding',
			icons: ['rose', 'balloons', 'heart_balloons', 'cylinder', 'list', 'candles', 'bell', 'decoration', 'bride', 'lock', 'toast', 'gift', 'fireworks', 'cake', 'wedding_suit',  'wedding_dress', 'gender', 'wedding_rings', 'church', 'groom', 'ring', 'pigeon', 'wedding_guests', 'camera', 'dish','engagement_ring', 'trumpet', 'candle', 'gate', 'necklace', 'wedding', ]
		}
	],
},
	originalIcons: null,
	originalFilterItems: null,
	_isEnabled: false,
	iconStyle: null,
	isEnabled: function() {
		return this._isEnabled;
	},
	enable: function(iconStyle) {
		this._isEnabled = true;
		
		var $appFrame = jQuery('#et-fb-app-frame');
		if (!$appFrame.length) {
			$appFrame = jQuery('#et-bfb-app-frame');
		}
		this.originalIcons = $appFrame[0].contentWindow.ETBuilderBackend.fontIconsExtended;
		this.originalFilterItems = $appFrame[0].contentWindow.ETBuilderBackend.searchFilterIconItems;
		
		this.populateIcons(iconStyle);
	},
	disable: function() {
		this._isEnabled = false;
		
		var $appFrame = jQuery('#et-fb-app-frame');
		if (!$appFrame.length) {
			$appFrame = jQuery('#et-bfb-app-frame');
		}
		$appFrame[0].contentWindow.ETBuilderBackend.fontIconsExtended = this.originalIcons;
		$appFrame[0].contentWindow.ETBuilderBackend.searchFilterIconItems = this.originalFilterItems;

		this.resetFilters(function() {
			jQuery('#et-fb-icon_picker li svg').remove();
		});

	},
	setIconStyle: function(iconStyle) {
		
		if (iconStyle != this.iconStyle) {
			this.populateIcons(iconStyle);
		}
		
	},
	populateIcons: function(iconStyle) {
		this.iconStyle = iconStyle;
		
		var $appFrame = jQuery('#et-fb-app-frame'), icons = [], iconSetFilters = {};
		
		if (!$appFrame.length) {
			$appFrame = jQuery('#et-bfb-app-frame');
		}
		
		$appFrame[0].contentWindow.ETBuilderBackend.fontIconsExtended = icons.concat.apply(icons,
			( iconStyle ? [ this.icons[iconStyle] ] : Object.values(this.icons) ).map(function(iconSets) {
				var currentIcons = [];
				
				return currentIcons.concat.apply(currentIcons, iconSets.map(function(iconSet) {
					iconSetFilters[iconSet.id] = iconSet.label;

					return iconSet.icons.map(function(icon) {

						return {
							decoded_unicode: 'agsdix-' + iconSet.prefix.slice(0, -4) + icon,
							font_weight: 400,
							is_divi_icon: false,
							name: icon,
							search_terms: icon,
							styles: [iconSet.id],
							unicode: 'agsdix-' + iconSet.prefix.slice(0, -4) + icon
						};
					});
				}));
			})
		);
		
		$appFrame[0].contentWindow.ETBuilderBackend.searchFilterIconItems = {
			show_only: iconSetFilters
		};
		
		wadi_config.observerEnabled = false;
		
		this.resetFilters();
		
		setTimeout(function() {
			agsdi_render_icons( jQuery('#et-fb-icon_picker') );
			wadi_config.observerEnabled = true;
		}, 1);
		
	},
	
	resetFilters: function(cb) {
		var $filter = jQuery('.et-fb-font-icon-filter:first');
		var noExistingFilters = !$filter.find('.et-fb-icon--exit').closest('button').click().length;

		if (noExistingFilters) { // Force refresh
		
			function handleFilterDropdown($filterDropdown) {
				$filterDropdown.find('li[data-value]:first').click(); // click the first item in the filter dropdown
				$filter.find('button:last').click(); // remove the filter
				
				if (cb) {
					cb();
				}
			}
		
			var $filterDropdown = jQuery('#et-fb-icon-options-filter');

			if ($filterDropdown.length) {
				handleFilterDropdown($filterDropdown);
			} else {
				var MO = window.MutationObserver ? window.MutationObserver : window.WebkitMutationObserver;
				if (MO) {

					var dropdownObserver = new MO(function(events) {
						for (var i = 0; i < events.length; ++i) {
							var event = events[i];
							for (var j = 0; j < event.addedNodes.length; ++j) {
								
								$filterDropdown = jQuery(event.addedNodes[j]).find('#et-fb-icon-options-filter');
								
								if ( $filterDropdown.length ) {
									dropdownObserver.disconnect();
									handleFilterDropdown( $filterDropdown );
								}
							}
						}
					});
					
					dropdownObserver.observe(document.body, {childList: true, subtree: true});
				}
				
				$filter.find('button:last').click(); // open the filter dropdown
			}
			
			
		} else if (cb) {
			cb();
		}
	},

};

var agsdi_search_id = 0, agsdi_search_timeout;
function agsdi_search(searchField) {
	if (agsdi_search_timeout) {
		clearTimeout(agsdi_search_timeout);
	}

	agsdi_search_timeout = setTimeout(function() {
		var $ = jQuery;
		var $searchField = $(searchField);
		var $searchTarget = $searchField.siblings('ul,.agsdi-icons:first');

		if ($searchTarget.children(':not([data-agsdi-keywords])').length) {
			$searchTarget.children().each(function() {
				var $icon = $(this);
				var iconId = $icon.attr('data-icon-pre');
				if (!iconId) {
					iconId = $icon.attr('data-icon');
				}
				if (iconId.substr(0, 6) === 'agsdi-') {
					var keywords = iconId.substr(6);
				} else if (iconId.substr(0, 9) === 'agsdix-fa') {
					var keywords = iconId.substr(14);
				} else if (iconId.substr(0, 7) === 'agsdix-') {
					var keywords = iconId.substr(iconId.indexOf('-', 7) + 1);
				} else {
					var keywords = '';
				}

				if (keywords) {
					keywords = keywords.split('-').join(' ');
				}

				if (agsdi_icon_aliases[iconId]) {
					keywords = (keywords ? keywords + ' ' : '') + agsdi_icon_aliases[iconId];
				}

				$icon.attr('data-agsdi-keywords', keywords);
			});
		}

		var $searchCss = $('#agsdi-search-css');
		if (!$searchCss.length) {
			$searchCss = $('<style>').attr('id', 'agsdi-search-css').appendTo('head:first');
		}

		var query = $searchField.val().trim();
		if (query) {
			var searchId = ++agsdi_search_id;
			$searchTarget.addClass('agsdi-search').attr('data-agsdi-search-id', searchId);
			var queryCss = query.split(' ');
			for (var i = 0; i < queryCss.length; ++i) {
				queryCss[i] = '.agsdi-search[data-agsdi-search-id=\'' + searchId + '\']>:not([data-agsdi-keywords*=\'' + queryCss[i] + '\'])';
			}
			$searchCss.html(queryCss.join() + '{display:none!important;}');
		} else {
			$searchTarget.removeClass('agsdi-search').attr('data-agsdi-search-id', null);
			$searchCss.html('');
		}

		$searchTarget.trigger('agsdi:search');
	}, 500);
}

(function($) {
	$(window).on('et_builder_api_ready', function(ev, ETBuilderApi) {

		/*
		The WadipETBuilderControlMultipleButtons class contains code from the submodule-builder repository,
		Copyright Elegant Themes. Licensed under the GNU General Public License version 3. See ../license.txt
		for license text.
		*/
		class WadipETBuilderControlMultipleButtons extends React.Component {
			static slug = 'WadipETBuilderControlMultipleButtons';

			constructor() {
				super();

				this.state = {
					activeOption: [],
				};

				this._onChange = this._onChange.bind(this);
				this.switchButton = this.switchButton.bind(this);
				this.getProcessedValue = this.getProcessedValue.bind(this);
			}
			
			shouldComponentUpdate(newProps, newState) {
				return this.state.activeOption.join('|') !== newState.activeOption.join('|');
			}

			componentDidMount() {
				this.setState({
					activeOption: this.getProcessedValue(),
				});
			}

			componentDidUpdate() {
				this.setState({
					activeOption: this.getProcessedValue(),
				});
			}

			getProcessedValue() {
				const value = this.props.value || this.props.default;

				if (typeof value === 'undefined' || '' === value) {
					return [];
				}

				// Support toggleable, multi selection, on tablet and phone. Convert 'none' value as empty
				// array because it has been modified on mobile.
				if (this.props.emptyMobileNone && 'none' === value) {
					return [];
				}

				return value.split('|');
			}

			switchButton(event) {
				event.preventDefault();

				const $clickedButton = jQuery(event.target).closest('.et-fb-multiple-buttons-toggle');
				const clickedButtonVal = $clickedButton.data('option_value');
				let newProcessedValue = this.state.activeOption;

				// support milti selection
				if (this.props.toggleable && includes(this.state.activeOption, clickedButtonVal)) {
					newProcessedValue = this.props.multi_selection ? without(newProcessedValue, clickedButtonVal).join('|') : this.props.default;
				} else {
					// support milti selection
					if (this.props.multi_selection) {
						newProcessedValue.push(clickedButtonVal);
					}
					newProcessedValue = this.props.multi_selection ? newProcessedValue.join('|') : clickedButtonVal;
				}

				// Support toggleable, multi selection, on tablet and phone. Set empty value as 'none' to ensure
				// this value can be used as a flag to tell VB if current option has been modified on mobile.
				if (this.props.emptyMobileNone && '' === newProcessedValue) {
					newProcessedValue = 'none';
				}

				this._onChange(newProcessedValue);
			}

			_onChange(newValue) {
				const {name, _onChange} = this.props;

				let activeOption = newValue;

				// Support toggleable, multi selection, on tablet and phone. Convert 'none' value as empty
				// string because it has been modified on mobile.
				if (this.props.emptyMobileNone && 'none' === newValue) {
					activeOption = '';
				}

				this.setState({
					activeOption: activeOption.split('|'),
				});

				_onChange(name, newValue);
			}

			render() {
				const thisClass = this;
				const optionsSet = thisClass.props.fieldDefinition.options;

				const buttonsOutput = Object.entries(optionsSet).map(function (entry) {

					const optionData = entry[1];
					const optionValue = entry[0];
					const isActiveButton = thisClass.state.activeOption.indexOf(optionValue) !== -1;

					const buttonClasses = 'et-fb-multiple-buttons-toggle-internal' + (isActiveButton ? ' et-fb-multiple-buttons-toggle-internal__active' : '');

					return React.createElement(
						'li',
						{
							className: "et-fb-multiple-buttons-toggle",
							'data-tooltip': optionData.title,
							'data-option_value': optionValue,
							onClick: thisClass.switchButton,
							key: optionValue
						},
						React.createElement(
							'span',
							{
								className: buttonClasses,
								dangerouslySetInnerHTML: {
									__html: optionData.iconSvg
								}
							}
						)
					);

				});

				return React.createElement(
					'div',
					{
						className: "et-fb-multiple-buttons-outer"
					},
					React.createElement(
						'ul',
						{
							className: "et-fb-multiple-buttons-container"
						},
						buttonsOutput
					)
				);
			}
		}
		
		
		ETBuilderApi.registerModalFields(
			[WadipETBuilderControlMultipleButtons]
		);

		var fbBody = $('#et-fb-app').closest('body'), reloadingPickerState = null;

		if (fbBody.length) {

			// js/icons.js
			$('.et-pb-icon:not([data-icon]),.et-pb-icon:not([data-icon])').each(function() {
				var iconId = jQuery(this).text();
				if (iconId.substr(0,6) === 'agsdi-' || iconId.substr(0,7) === 'agsdix-') {
					jQuery(this).attr('data-icon', iconId).html('').addClass('agsdi-loaded');
				} else {
					jQuery(this).addClass('agsdi-loaded');
				}
			});

			/*
			var iconPages, iconsSearchQuery, currentIconsPage, $shadowIconsList = $('<ul>');

			window.agsdi_allIcons = ETBuilderBackend.fontIcons.slice(0);
			window.agsdi_displayIcons = [];

			function calculateIconPages() {
				iconPages = [0];
				var pageCount = 0;
				for ( var i = 0; i < window.agsdi_allIcons.length; ++i ) {


					if (iconsSearchQuery && window.agsdi_allIcons[i] !== 'agsdix-null') {
						if (window.agsdi_allIcons[i].substr(0, 6) === 'agsdi-') {
							var keywords = window.agsdi_allIcons[i].substr(6);
						} else if (window.agsdi_allIcons[i].substr(0, 9) === 'agsdix-fa') {
							var keywords = window.agsdi_allIcons[i].substr(14);
						} else if (window.agsdi_allIcons[i].substr(0, 7) === 'agsdix-') {
							var keywords = window.agsdi_allIcons[i].substr(window.agsdi_allIcons[i].indexOf('-', 7) + 1);
						} else {
							var keywords = '';
						}

						if (keywords) {
							keywords = keywords.split('-').join(' ');
						}

						if (agsdi_icon_aliases[window.agsdi_allIcons[i]]) {
							keywords = (keywords ? keywords + ' ' : '') + agsdi_icon_aliases[window.agsdi_allIcons[i]];
						}

						if (keywords.indexOf(iconsSearchQuery) === -1) {
							window.agsdi_displayIcons[i] = 'agsdix-null';
						} else {
							window.agsdi_displayIcons[i] = window.agsdi_allIcons[i];
						}
					} else {
						window.agsdi_displayIcons[i] = window.agsdi_allIcons[i];
					}


					if (window.agsdi_displayIcons[i] === 'agsdix-null') {
						continue;
					}


					if (pageCount === 48) {
						iconPages.push(i);
						pageCount = 0;
					}
					++pageCount;
				}
			}
			calculateIconPages();


			function iconsMap(cb) {
				var result = [];

				for ( var i in window.ETBuilderBackend.fontIcons  ) {
					if (i !== 'map') {
						var icon = cb(ETBuilderBackend.fontIcons[i], i);
						icon.props.onClick = (function(index, clickCb) {
							return function() {
								clickCb({
									target: $shadowIconsList.children().eq(index)
								});
							}
						})(i, icon.props.onClick);
						result.push( icon );
					}
				}

				return result;
			}

			function setIconsPage(page) {
				currentIconsPage = page;

				window.ETBuilderBackend.fontIcons = {};

				var iconsCount = 0;

				for ( var i = iconPages[currentIconsPage]; i < window.agsdi_displayIcons.length && iconsCount < 48; ++i ) {

					if (window.agsdi_displayIcons[i] === 'agsdix-null') {
						continue;
					}

					window.ETBuilderBackend.fontIcons[i] = window.agsdi_displayIcons[i];

					++iconsCount;
				}


				for ( var shadowLength = $shadowIconsList.children().length; shadowLength <= i; ++shadowLength ) {
					$shadowIconsList.append($('<li>'));
				}

				window.ETBuilderBackend.fontIcons.map = iconsMap;

			}

			setIconsPage(0);
			*/

			var MO = window.MutationObserver ? window.MutationObserver : window.WebkitMutationObserver;
			if (MO) {

				(new MO(function(events) {
					for (var i = 0; i < events.length; ++i) {
						var event = events[i];

						if (event.target && (event.type !== 'characterData' || event.target.parentElement)) {
							var $element = $(event.type === 'characterData' ? event.target.parentElement : event.target);
								if ( $element.hasClass('et-pb-icon') && $element.closest('.et_pb_main_blurb_image, .et_pb_module.et_pb_icon').length ) {
									if ($element.hasClass('agsdi-updating')) {
										$element.removeClass('agsdi-updating');
									} else {
										var iconContent = $element.html();

										if (iconContent.substr(0, 2) === '%%') {
											iconContent = window.agsdi_allIcons[iconContent.substr(2, iconContent.length - 4)];
											iconContentWasIndex = true;
										} else {
											iconContentWasIndex = false;
										}

										if (iconContent.substr(0, 6) === 'agsdi-' || iconContent.substr(0, 7) === 'agsdix-') {
											$element.attr('data-icon', iconContent )
												.addClass('agsdi-updating')
												.html('');
										} else if (iconContentWasIndex) {
											$element.html( ET_Builder.API.Utils.processFontIcon(iconContent) );
										} else {
											/*
											var dataIconValue = $element.attr('data-icon');
											if ( dataIconValue && (dataIconValue.substr(0, 6) === 'agsdi-' || dataIconValue.substr(0, 7) === 'agsdix-') && dataIconValue.substr(0, 11) !== 'agsdix-smc-' && dataIconValue.substring(0, 12) !== 'agsdix-mcip-') {
												$element.attr('data-icon', null);
											}
											*/
										}
										$element.addClass('agsdi-loaded');
									}

							} else if (event.addedNodes && event.addedNodes.length) {

								for (var j = 0; j < event.addedNodes.length; ++j) {

									$(event.addedNodes[j]).find('.et-pb-icon').each(function() {
										var $iconChild = $(this);
										if ($iconChild.closest('.et_pb_module.et_pb_blurb .et_pb_main_blurb_image, .et_pb_module.et_pb_icon').length) {

											var iconContent = $iconChild.html();


											if (iconContent.substr(0, 2) === '%%') {
												iconContent = window.agsdi_allIcons[iconContent.substr(2, iconContent.length - 4)];
												iconContentWasIndex = true;
											} else {
												iconContentWasIndex = false;
											}

											if (iconContent.substr(0, 6) === 'agsdi-' || iconContent.substr(0, 7) === 'agsdix-') {
												$iconChild.attr('data-icon', iconContent )
													.addClass('agsdi-updating')
													.html('');
											} else if (iconContentWasIndex) {
												$iconChild.html( ET_Builder.API.Utils.processFontIcon(iconContent) );
											}

											$iconChild.addClass('agsdi-loaded');
										}
									});

									$element.find('[data-icon]').addBack('[data-icon]').each(function() {
										var $child = $(this);
										var iconContent = $child.attr('data-icon');
										if (iconContent.substr(0, 2) === '%%') {
											$child.attr( 'data-icon', ET_Builder.API.Utils.processFontIcon(window.agsdi_allIcons[iconContent.substr(2, iconContent.length - 4)]) );
										}
									});
								}
							}
						}
					}
				})).observe(fbBody[0], {characterData: true, childList: true, subtree: true});
			}

			/*

			var p$ = window.parent.jQuery, fbAncestorBody = p$('body.et-fb, body.et-bfb, body.et-tb');

			if (fbAncestorBody.length) {

				console.log('has ancestor body')

				var MO3 = window.parent.MutationObserver ? window.parent.MutationObserver : window.parent.WebkitMutationObserver;
				if (MO3) {
					(new MO3(function(events) {

						for (var i = 0; i < events.length; ++i) {
							var event = events[i];

							if (event.addedNodes && event.addedNodes.length) {

								for (var j = 0; j < event.addedNodes.length; ++j) {

									var $_iconPicker = p$(event.addedNodes[j]).find('.et-fb-font-icon-list');


									if ($_iconPicker.length && !$_iconPicker.siblings('.agsdi-picker-pagination').length) {

										(function($iconPicker) {

											if (reloadingPickerState === null) {
												if ( currentIconsPage !== 0 ) {
													pageNavigate(0);
												}
											} else {
												setTimeout((function(scrollPos, afterScrollPos) {
													return function() {
														$iconPicker.closest('.et-fb-tabs__panel').scrollTop(scrollPos);
														afterScrollPos();
													};
												})(reloadingPickerState.scrollPos, reloadingPickerState.afterScrollPos), 100);
												reloadingPickerState = null;
											}

											var searchTimeout;
											function searchIcons(ev) {
												if (searchTimeout) {
													clearTimeout(searchTimeout);
												}

												searchTimeout = setTimeout(function() {
													var $searchField = $(ev.target);
													iconsSearchQuery = $searchField.val().trim();
													calculateIconPages();
													pageNavigate(0);
												}, 500);
											}

											p$('<input>')
												.attr({
													type: 'search',
													placeholder: wp.i18n.__('Search icons...', 'ds-icon-expansion'),
												})
												.val(iconsSearchQuery)
												.addClass('agsdi-picker-search-divi-fb et-fb-settings-option-input')
												.on('input', searchIcons)
												.insertBefore($iconPicker);

											function onPaginationClick(ev) {
												var page = $(ev.target).attr('data-agsdi-page');

												switch ( page ) {
													case 'prev':
														pageNavigate(currentIconsPage - 1);
														break;
													case 'next':
														pageNavigate(currentIconsPage + 1);
														break;
													default:
														pageNavigate(parseInt(page));
												}

												return false;
											}

											function pageNavigate(pageNum) {

												reloadingPickerState = {
													page: pageNum
												};

												var $toggle = $iconPicker.closest('.et-fb-form__toggle');
												var $panel = $toggle.closest('.et-fb-tabs__panel');
												reloadingPickerState.scrollPos = $panel.scrollTop();
												reloadingPickerState.afterScrollPos = function() { $panel.fadeIn(); };
												$panel.fadeOut(300, function() {
													var $toggleTitle = $toggle.children('.et-fb-form__toggle-title:first').click();
													setIconsPage(reloadingPickerState.page);
													$toggleTitle.click();
												});







												return false;
											}

											var $pagination = p$('<div>').addClass('agsdi-picker-pagination');

											function addPageNumber(num) {

												p$('<a>')
													.attr({
														href: '#',
														title: 'Page ' + (num + 1)
													})
													.text(num + 1)
													.addClass('agsdi-picker-pagination-number ' + (num === currentIconsPage ? 'agsdi-picker-pagination-current' : '')).attr('data-agsdi-page', num).click(onPaginationClick).appendTo($pagination);
											}

											var maxPage = iconPages.length - 1;
											var pageNumbersDisplay = [];
											for ( var k = Math.max(currentIconsPage - 5, 0); k <= Math.min(currentIconsPage + 5, maxPage); ++k ) {
												pageNumbersDisplay.push(k);
											}

											if (currentIconsPage > 0) {
												p$('<a>')
													.attr('href', '#')
													.text('Previous Page')
													.attr('data-agsdi-page', 'prev')
													.addClass('agsdi-picker-pagination-prev')
													.click(onPaginationClick)
													.appendTo($pagination);
											}

											if (pageNumbersDisplay[0] !== 0) {
												addPageNumber(0);
												p$('<span>').addClass('agsdi-picker-pagination-ellipsis').html('&hellip;').appendTo($pagination);
											}


											for ( var k = 0; k < pageNumbersDisplay.length; ++k ) {
												addPageNumber(pageNumbersDisplay[k]);
											}

											if (pageNumbersDisplay[pageNumbersDisplay.length - 1] !== maxPage) {
												p$('<span>').addClass('agsdi-picker-pagination-ellipsis').html('&hellip;').appendTo($pagination);
												addPageNumber(maxPage);
											}


											if (currentIconsPage < maxPage) {
												p$('<a>')
													.attr('href', '#')
													.text('Next Page')
													.attr('data-agsdi-page', 'next')
													.addClass('agsdi-picker-pagination-next')
													.click(onPaginationClick)
													.appendTo($pagination);
											}

											$pagination.insertAfter($iconPicker);

											$iconPicker.on('click', 'li', function() {
												$(this).addClass('active').siblings('.active').removeClass('active');
											});

											//!free exclude start
											$pagination.after(
												// Credit HTML copied from ds-icon-expansion-pack.php
												'<span class="agsdi-picker-credit">With free icons by <a href="https://divi.space/?utm_source=ds-icon-expansion&amp;utm_medium=plugin-credit-link&amp;utm_content=divi-visual-builder" target="_blank">Divi Space</a><span class="agsdi-picker-credit-promo"></span></span>'
											);
											//!free exclude end
										})($_iconPicker);
									}

								}
							}
						}
					})).observe(fbAncestorBody[0], {childList: true, subtree: true});
				}
			}
			*/

		}

	});
		
		$(window).on('et_builder_api_ready', function() {
			window.et_gb.wp.hooks.addFilter(
				'et.builder.store.action.dispatched',
				'ags-wadip',
				function(action) {
					if (action.actionType === 'MODULE_EDIT_SETTINGS_CHANGE') {
						if (window.parent.agsdi_multicolor.isEnabled() && action.setting === 'icon_pack_select') {
							window.parent.agsdi_multicolor.setIconStyle(action.newValue.substring(4));
						}
					}
					return action;
					
				}
			);
		});
		
		$(window).on('et_fb_module_did_mount_et_pb_blurb et_fb_module_did_update_et_pb_blurb et_fb_module_did_mount_et_pb_icon et_fb_module_did_update_et_pb_icon', function(ev, module) {

			if ( ( module.type === 'et_pb_icon' || module.attrs.use_icon === 'on') && module.attrs.multicolor_icon_enabled === 'on' )  {
				
				
				var $module = jQuery('.et-module-' + module._key + ':first'),
					$style = $module.find('.wadip-multicolor-style:first');
				if (!$style.length) {
					$style = jQuery('<style>').appendTo($module);
				}

				$style.text(
					(module.attrs.icon_color ? '.et-module-' + module._key + ' .wadip-primary-color{fill:' + module.attrs.icon_color + '}' : '')
					+ (module.attrs.secondary_icon_color ? '.et-module-' + module._key + ' .wadip-secondary-color{fill:' + module.attrs.secondary_icon_color + '}' : '')
					+ (module.attrs.tertiary_icon_color ? '.et-module-' + module._key + ' .wadip-tertiary-color{fill:' + module.attrs.tertiary_icon_color + '}' : '')
				);
			}
		});
})(jQuery);

// admin.php
var ags_testify_tabs_navigate = function () {
	jQuery('#ds_icon_expansion-settings-tabs-content > div, #ds_icon_expansion-settings-tabs > li').removeClass('ds_icon_expansion-settings-active');
	jQuery('#ds_icon_expansion-settings-' + location.hash.substr(1)).addClass('ds_icon_expansion-settings-active');
	jQuery('#ds_icon_expansion-settings-tabs > li:has(a[href="' + location.hash + '"])').addClass('ds_icon_expansion-settings-active');
};

if (location.hash) {
	ags_testify_tabs_navigate();
}

jQuery(document).ready(function($) {

	$('#agsdi_mc_packs').bind('click', function() {
		$('.agsdi-load-icon-sets-wrapper-multicolor .agsdi_icons_input').prop('checked', $( this)[0].checked);
	});
});

jQuery(window).on('hashchange', ags_testify_tabs_navigate);

// This must be the last line - do not modify (automatically updated)
// Includes data copied from the Divi theme (core/admin/fonts/modules.svg)
var agsdi_icon_aliases = {"agsdi-message":"paper airplane","!":"arrow up","\"":"arrow down","#":"arrow left","$":"arrow right","%":"arrow left up","&":"arrow right up","'":"arrow right down","(":"arrow left down",")":"arrow up down","*":"arrow up down alt","+":"arrow left right alt",",":"arrow left right","-":"arrow expand alt2",".":"arrow expand alt","\/":"arrow condense","1":"arrow move","2":"arrow carrot up","3":"arrow carrot down","4":"arrow carrot left","5":"arrow carrot right","6":"arrow carrot 2up","7":"arrow carrot 2down","8":"arrow carrot 2left","9":"arrow carrot 2right",":":"arrow carrot up alt2",";":"arrow carrot down alt2","<":"arrow carrot left alt2","=":"arrow carrot right alt2",">":"arrow carrot 2up alt2","?":"arrow carrot 2down alt2","@":"arrow carrot 2left alt2","A":"arrow carrot 2right alt2","B":"arrow triangle up","C":"arrow triangle down","D":"arrow triangle left","E":"arrow triangle right","F":"arrow triangle up alt2","G":"arrow triangle down alt2","H":"arrow triangle left alt2","I":"arrow triangle right alt2","J":"arrow back","K":"minus 06","L":"plus","M":"close","N":"check","O":"minus alt2","P":"plus alt2","Q":"close alt2","R":"check alt2","S":"zoom out alt","T":"zoom in alt","U":"search","V":"box empty","W":"box selected","X":"minus box","Y":"plus box","Z":"box checked","[":"circle empty","\\":"circle slelected","]":"stop alt2","^":"stop","_":"pause alt2","`":"pause","a":"menu","b":"menu square alt2","c":"menu circle alt2","d":"ul","e":"ol","f":"adjust horiz","g":"adjust vert","h":"document alt","i":"documents alt","j":"pencil","k":"pencil edit alt","l":"pencil edit","m":"folder alt","n":"folder open alt","o":"folder add alt","p":"info alt","q":"error oct alt","r":"error circle alt","s":"error triangle alt","t":"question alt2","u":"question","v":"comment alt","w":"chat alt","x":"vol mute alt","y":"volume low alt","z":"volume high alt","{":"quotations","|":"quotations alt2","}":"clock alt","~":"lock alt","\ue000":"lock open alt","\ue001":"key alt","\ue002":"cloud alt","\ue003":"cloud upload alt","\ue004":"cloud download alt","\ue005":"image","\ue006":"images","\ue007":"lightbulb alt","\ue008":"gift alt","\ue009":"house alt","\ue00a":"genius","\ue00b":"mobile","\ue00c":"tablet","\ue00d":"laptop","\ue00e":"desktop","\ue00f":"camera alt","\ue010":"mail alt","\ue011":"cone alt","\ue012":"ribbon alt","\ue013":"bag alt","\ue014":"creditcard","\ue015":"cart alt","\ue016":"paperclip","\ue017":"tag alt","\ue018":"tags alt","\ue019":"trash alt","\ue01a":"cursor alt","\ue01b":"mic alt","\ue01c":"compass alt","\ue01d":"pin alt","\ue01e":"pushpin alt","\ue01f":"map alt","\ue020":"drawer alt","\ue021":"toolbox alt","\ue022":"book alt","\ue023":"calendar","\ue024":"film","\ue025":"table","\ue026":"contacts alt","\ue027":"headphones","\ue028":"lifesaver","\ue029":"piechart","\ue02a":"refresh","\ue02b":"link alt","\ue02c":"link","\ue02d":"loading","\ue02e":"blocked","\ue02f":"archive alt","\ue030":"heart alt","\ue031":"star alt","\ue032":"star half alt","\ue033":"star","\ue034":"star half","\ue035":"tools","\ue036":"tool","\ue037":"cog","\ue038":"cogs","\ue039":"arrow up alt","\ue03a":"arrow down alt","\ue03b":"arrow left alt","\ue03c":"arrow right alt","\ue03d":"arrow left up alt","\ue03e":"arrow right up alt","\ue03f":"arrow right down alt","\ue040":"arrow left down alt","\ue041":"arrow condense alt","\ue042":"arrow expand alt3","\ue043":"arrow carrot up alt","\ue044":"arrow carrot down alt","\ue045":"arrow carrot left alt","\ue046":"arrow carrot right alt","\ue047":"arrow carrot 2up alt","\ue048":"arrow carrot 2dwnn alt","\ue049":"arrow carrot 2left alt","\ue04a":"arrow carrot 2right alt","\ue04b":"arrow triangle up alt","\ue04c":"arrow triangle down alt","\ue04d":"arrow triangle left alt","\ue04e":"arrow triangle right alt","\ue04f":"minus alt","\ue050":"plus alt","\ue051":"close alt","\ue052":"check alt","\ue053":"zoom out","\ue054":"zoom in","\ue055":"stop alt","\ue056":"menu square alt","\ue057":"menu circle alt","\ue058":"document","\ue059":"documents","\ue05a":"pencil alt","\ue05b":"folder","\ue05c":"folder open","\ue05d":"folder add","\ue05e":"folder upload","\ue05f":"folder download","\ue060":"info","\ue061":"error circle","\ue062":"error oct","\ue063":"error triangle","\ue064":"question alt","\ue065":"comment","\ue066":"chat","\ue067":"vol mute","\ue068":"volume low","\ue069":"volume high","\ue06a":"quotations alt","\ue06b":"clock","\ue06c":"lock","\ue06d":"lock open","\ue06e":"key","\ue06f":"cloud","\ue070":"cloud upload","\ue071":"cloud download","\ue072":"lightbulb","\ue073":"gift","\ue074":"house","\ue075":"camera","\ue076":"mail","\ue077":"cone","\ue078":"ribbon","\ue079":"bag","\ue07a":"cart","\ue07b":"tag","\ue07c":"tags","\ue07d":"trash","\ue07e":"cursor","\ue07f":"mic","\ue080":"compass","\ue081":"pin","\ue082":"pushpin","\ue083":"map","\ue084":"drawer","\ue085":"toolbox","\ue086":"book","\ue087":"contacts","\ue088":"archive","\ue089":"heart","\ue08a":"profile","\ue08b":"group","\ue08c":"grid 2x2","\ue08d":"grid 3x3","\ue08e":"music","\ue08f":"pause alt","\ue090":"phone","\ue091":"upload","\ue092":"download","\ue093":"social facebook","\ue094":"social twitter","\ue095":"social pinterest","\ue096":"social googleplus","\ue097":"social tumblr","\ue098":"social tumbleupon","\ue099":"social wordpress","\ue09a":"social instagram","\ue09b":"social dribbble","\ue09c":"social vimeo","\ue09d":"social linkedin","\ue09e":"social rss","\ue09f":"social deviantart","\ue0a0":"social share","\ue0a1":"social myspace","\ue0a2":"social skype","\ue0a3":"social youtube","\ue0a4":"social picassa","\ue0a5":"social googledrive","\ue0a6":"social flickr","\ue0a7":"social blogger","\ue0a8":"social spotify","\ue0a9":"social delicious","\ue0aa":"social facebook circle","\ue0ab":"social twitter circle","\ue0ac":"social pinterest circle","\ue0ad":"social googleplus circle","\ue0ae":"social tumblr circle","\ue0af":"social stumbleupon circle","\ue0b0":"social wordpress circle","\ue0b1":"social instagram circle","\ue0b2":"social dribbble circle","\ue0b3":"social vimeo circle","\ue0b4":"social linkedin circle","\ue0b5":"social rss circle","\ue0b6":"social deviantart circle","\ue0b7":"social share circle","\ue0b8":"social myspace circle","\ue0b9":"social skype circle","\ue0ba":"social youtube circle","\ue0bb":"social picassa circle","\ue0bc":"social googledrive alt2","\ue0bd":"social flickr circle","\ue0be":"social blogger circle","\ue0bf":"social spotify circle","\ue0c0":"social delicious circle","\ue0c1":"social facebook square","\ue0c2":"social twitter square","\ue0c3":"social pinterest square","\ue0c4":"social googleplus square","\ue0c5":"social tumblr square","\ue0c6":"social stumbleupon square","\ue0c7":"social wordpress square","\ue0c8":"social instagram square","\ue0c9":"social dribbble square","\ue0ca":"social vimeo square","\ue0cb":"social linkedin square","\ue0cc":"social rss square","\ue0cd":"social deviantart square","\ue0ce":"social share square","\ue0cf":"social myspace square","\ue0d0":"social skype square","\ue0d1":"social youtube square","\ue0d2":"social picassa square","\ue0d3":"social googledrive square","\ue0d4":"social flickr square","\ue0d5":"social blogger square","\ue0d6":"social spotify square","\ue0d7":"social delicious square","\ue0d8":"wallet alt","\ue0d9":"shield alt","\ue0da":"percent alt","\ue0db":"pens alt","\ue0dc":"mug alt","\ue0dd":"like alt","\ue0de":"globe alt","\ue0df":"flowchart alt","\ue0e0":"id alt","\ue0e1":"hourglass","\ue0e2":"globe","\ue0e3":"globe 2","\ue0e4":"floppy alt","\ue0e5":"drive alt","\ue0e6":"clipboard","\ue0e7":"calculator alt","\ue0e8":"floppy","\ue0e9":"easel","\ue0ea":"drive","\ue0eb":"dislike","\ue0ec":"datareport","\ue0ed":"currency","\ue0ee":"calulator","\ue0ef":"building","\ue0f0":"easel alt","\ue0f1":"dislike alt","\ue0f2":"datareport alt","\ue0f3":"currency alt","\ue0f4":"briefcase alt","\ue0f5":"target","\ue0f6":"shield","\ue0f7":"search alt","\ue0f8":"rook","\ue0f9":"puzzle alt","\ue0fa":"printer alt","\ue0fb":"percent","\ue0fc":"id 2 alt","\ue0fd":"building alt","\ue0fe":"briefcase","\ue0ff":"balance","\ue100":"wallet","\ue101":"search","\ue102":"puzzle","\ue103":"printer","\ue104":"pens","\ue105":"mug","\ue106":"like","\ue107":"id","\ue108":"id 2","\ue109":"flowchart","\ue600":"toggle","\ue601":"tabs","\ue602":"subscribe","\ue603":"slider","\ue604":"sidebar","\ue605":"share","\ue606":"pricing table","\ue607":"portfolio","\ue608":"number counter","\ue609":"header","\ue60a":"filtered portfolio","\ue60b":"divider","\ue60c":"cta","\ue60d":"countdown","\ue60e":"circle counter","\ue60f":"blurb","\ue610":"bar counters","\ue611":"audio","\ue612":"accordion","\ue613":"text","\ue614":"testimonial","\ue615":"shop","\ue616":"person","\ue617":"menu","\ue618":"map","\ue619":"login","\ue61a":"image","\ue61b":"gallery","\ue61c":"follow","\ue61d":"contact","\ue61e":"blog","\ue61f":"reset","\ue620":"code","\ue621":"underline","\ue622":"bold","\ue623":"italic","\ue624":"uppercase","\ue625":"divi","\ue626":"D","\ue900":"import export","\ue901":"double underline","\ue902":"smallcaps","\ue903":"strikethrough","\ue904":"menu expand","\ue905":"strikethrough","\ue906":"external link"};