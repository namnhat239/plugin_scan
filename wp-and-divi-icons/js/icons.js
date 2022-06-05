/*
WP and Divi Icons by Divi Space, an Aspen Grove Studios company
Licensed under the GNU General Public License v3 (see ../license.txt)

This plugin includes code based on parts of the Divi theme and/or the
Divi Builder, Copyright (c) Elegant Themes, licensed GPLv2+, used under GPLv3 (see ../license.txt).
*/

var agsdi_icons_loaded = [], wadi_config = {
	parentIds: '#main-content, #et-main-area, #page-container, #et-boc',
	noBeforeElements: [
		['et_pb_main_blurb_image', 'et_pb_image_wrap'],
		['et_pb_shop'],
		['et_pb_comments_module']
	],
	noAfterElements: [
		['et_pb_main_blurb_image', 'et_pb_image_wrap'],
		['et_pb_shop'],
		['et_pb_comments_module'],
		['et_overlay'],
		['et_font_icon', '*'],
		['et_pb_main_blurb_image', 'et-pb-icon'],
		['et_pb_comments_module'],
		['et_pb_icon', 'et-pb-icon'],
	],
	parentsAsClasses: [],
	observerEnabled: true
};


for (var i = 0; i < wadi_config.noBeforeElements.length; ++i) {
	if (wadi_config.noBeforeElements[i].length === 2 && wadi_config.parentsAsClasses.indexOf(wadi_config.noBeforeElements[i][0]) === -1) {
		wadi_config.parentsAsClasses.push( wadi_config.noBeforeElements[i][0] );
	}
}
for (var i = 0; i < wadi_config.noAfterElements.length; ++i) {
	if (wadi_config.noAfterElements[i].length === 2 && wadi_config.parentsAsClasses.indexOf(wadi_config.noAfterElements[i][0]) === -1) {
		wadi_config.parentsAsClasses.push( wadi_config.noAfterElements[i][0] );
	}
}

window.agsdi_render_icons = function(container, noClasses, reload) {
	
	var $container = jQuery(container);

	$container.find('.et-pb-icon:not([data-icon]),.et-pb-icon:not([data-icon])').each(function() {
		var iconId = jQuery(this).text();
		if (iconId.substr(0,6) === 'agsdi-' || iconId.substr(0,7) === 'agsdix-') {
			jQuery(this).attr('data-icon', iconId).html('').addClass('agsdi-loaded');
		} else {
			jQuery(this).addClass('agsdi-loaded');
		}
	});

	$container.find('[data-icon]').addBack('[data-icon]').each(function() {
		
		if (this.className && this.className.indexOf('i-agsdi') !== -1) {
			
			var classNames = this.className.split(' '), classesToRemove = '';
			
			for (var i = 0; i < classNames.length; ++i) {
				var className = classNames[i].trim();
				if (className.substr(0, 7) === 'i-agsdi') {
					classesToRemove += className + ' ';
				}
			}
			if (classesToRemove) {
				jQuery(this).removeClass(classesToRemove);
				
				// Removing classes will trigger the rendering function again so we can exit for now
				return;
			}
		}
		
		var $this = jQuery(this), iconId = $this.attr('data-icon');
		
		if ( iconId.length > 6 && (iconId.substr(0,6) === 'agsdi-' || iconId.substr(0,7) === 'agsdix-') ) {
			var iconClass = 'i-' + iconId.replace(/ /, '-');
		
			if (!noClasses) {
				$this.addClass(iconClass);
				for (var i = 0; i < wadi_config.parentsAsClasses.length; ++i) {
					if ($this.closest('.' + wadi_config.parentsAsClasses[i]).length) {
						$this.addClass('agsdi-parent-' + wadi_config.parentsAsClasses[i]);
					}
				}
			}
			
			if (window.wadi_icons && window.wadi_icons[iconId]) {
				
				
				var iconSelector = noClasses
								? '[data-icon="' + iconId + '"]'
								: '.' + iconClass,
					parentSelector = '',
					$currentParent = $this.parent().closest(wadi_config.parentIds);
				
				while ($currentParent.length) {
					parentSelector = '#' + $currentParent.attr('id') + ' ' + parentSelector;
					$currentParent = $currentParent.parent().closest(wadi_config.parentIds);
				}
				
				if (reload || !agsdi_icons_loaded[parentSelector+iconSelector]) {
				
					var beforeSelector = parentSelector + ' ' + iconSelector, beforeSelector2 = '', notParents = {};
					for (var i = 0; i < wadi_config.noBeforeElements.length; ++i) {
						if ( wadi_config.noBeforeElements[i].length === 2 ) {
							if (!notParents[wadi_config.noBeforeElements[i][0]]) {
								beforeSelector += ':not(.agsdi-parent-'
													+ wadi_config.noBeforeElements[i][0]
													+ ')';
													
								notParents[wadi_config.noBeforeElements[i][0]] = [];
							}
							if (wadi_config.noBeforeElements[i][1] !== '*') {
								notParents[wadi_config.noBeforeElements[i][0]].push(wadi_config.noBeforeElements[i][1])
							}
						} else {
							beforeSelector += ':not(.'
								+ wadi_config.noBeforeElements[i][0]
								+ ')';

						}
						
					}
					
					for (notParent in notParents) {
						if (notParents[notParent].length) {
							beforeSelector2 += (beforeSelector2 ? ',' : '')
														+ parentSelector
														+ ' '
														+ iconSelector
														+ '.agsdi-parent-'
														+ notParent
							for (var i = 0; i < notParents[notParent].length; ++i) {
								beforeSelector2 += ':not(.'
												+ notParents[notParent][i]
												+ ')';
							}
							beforeSelector2 += ':before';
						}
					}
					
					
					var afterSelector = parentSelector + ' ' + iconSelector, afterSelector2 = '';
					notParents = {};
					for (var i = 0; i < wadi_config.noAfterElements.length; ++i) {
						if ( wadi_config.noAfterElements[i].length === 2 ) {
							if (!notParents[wadi_config.noAfterElements[i][0]]) {
								afterSelector += ':not(.agsdi-parent-'
													+ wadi_config.noAfterElements[i][0]
													+ ')';
													
								notParents[wadi_config.noAfterElements[i][0]] = [];
							}
							if (wadi_config.noAfterElements[i][1] !== '*') {
								notParents[wadi_config.noAfterElements[i][0]].push(wadi_config.noAfterElements[i][1])
							}
						} else {
							afterSelector += ':not(.'
								+ wadi_config.noAfterElements[i][0]
								+ ')';

						}
						
					}
					
					for (notParent in notParents) {
						if (notParents[notParent].length) {
							afterSelector2 += (afterSelector2 ? ',' : '')
														+ parentSelector
														+ ' '
														+ iconSelector
														+ '.agsdi-parent-'
														+ notParent
							for (var i = 0; i < notParents[notParent].length; ++i) {
								afterSelector2 += ':not(.'
												+ notParents[notParent][i]
												+ ')';
							}
							afterSelector2 += ':after';
						}
					}
				
					var iconCss = beforeSelector + ':before,' + beforeSelector2 + ',' + afterSelector + ':after,' + afterSelector2 + '{content:"\\' + window.wadi_icons[iconId] + '"!important;';
					
					if (window.wadi_fonts) {
						for (var iconPrefix in window.wadi_fonts) {
							
							if (iconId.indexOf(iconPrefix) === 0) {
								if ( iconPrefix === 'agsdix-fas' ) {
									iconCss += 'font-weight: 900!important;';
								}
								iconCss += 'font-family:"' + window.wadi_fonts[iconPrefix] + '"!important;';
								break;
							}
						}
					}
					
					iconCss += '}\n';
					
					
					var $style = $container.closest('html').find('#agsdi-icons-style');
					if (!$style.length) {
						$style = jQuery('<style id="agsdi-icons-style">').appendTo( $container.closest('html').find('head:first') );
					}
					$style.append(iconCss);
					
					if (!reload) {
						agsdi_icons_loaded[parentSelector+iconSelector] = true;
					}
					
				}
				
			} else {
				
				var svgIconUrl = null;

				if (iconId.substring(0, 12) === 'agsdix-mcip-') {
					var iconDirs = {
						'uni': 'ags-universal',
						'lin': 'ags-lineal',
						'out': 'ags-outline',
						'mul': 'ags-multicolor',
						'han': 'ags-hand-drawn',
						'fil': 'ags-filled',
						'ske': 'ags-sketch',
						'tri': 'ags-tri-color',
						'ele': 'ags-elegant',
					};
					
					var iconDirId = iconId.substring(12, 15);
					
					if (iconDirs[iconDirId]) {
						svgIconUrl = ags_divi_icons_config.pluginDirUrl + '/icon-packs/' + iconDirs[iconDirId] + '/multicolor/' + iconId.substring(16) + '.svg';
					}
				}

				if (svgIconUrl) {

					if (!window.wadi_svg_icons) {
						window.wadi_svg_icons = {};
					}

					if (typeof window.wadi_svg_icons[iconId] === 'undefined') {
						window.wadi_svg_icons[iconId] = {
							queue: [this],
							svg: null
						};

						jQuery.get(
							svgIconUrl,
							{},
							function (response) {
								window.wadi_svg_icons[iconId].svg = response;

								jQuery(window.wadi_svg_icons[iconId].queue).html(response);
								window.wadi_svg_icons[iconId].queue = [];
							},
							'text'
						);
					} else if (window.wadi_svg_icons[iconId].svg) {
						jQuery(this).html(window.wadi_svg_icons[iconId].svg);
					} else {
						window.wadi_svg_icons[iconId].queue.push(this);
					}

				}

			}
			
		}
	});
	
}


jQuery(document).ready(function($) {
	
	var MO = window.MutationObserver ? window.MutationObserver : window.WebkitMutationObserver;
	if (MO) {
		
		(new MO(function(events) {
			
			if (wadi_config.observerEnabled) {
			
				for (var i = 0; i < events.length; ++i) {
					var event = events[i];
					
					if (event.addedNodes && event.addedNodes.length) {
							
						for (var j = 0; j < event.addedNodes.length; ++j) {
							
							if (event.addedNodes[j].nodeType === 3) {
								var $target = jQuery(event.target);
								if ($target.hasClass('et-pb-icon')) {
									var iconId = $target.text().trim();
									if ( iconId ) {
										$target.attr('data-icon', iconId);
										if ( iconId.substr(0,5) === 'agsdi' && !document.getElementById('et-fb-app') ) {
											$target.html('');
										}
										agsdi_render_icons( $target.parent() );
									}
								}
							} else {
								agsdi_render_icons( jQuery(event.addedNodes[j]) );
							}
							
							
						}
						
					} else if (event.type === 'attributes') {
						var dataIconAttribute = event.target.getAttribute('data-icon');
						
						if ( dataIconAttribute && ( !event.target.className || event.target.className.indexOf('i-' + dataIconAttribute.replace(/ /, '-')) === -1 ) ) {
							agsdi_render_icons( event.target );
						} else if ( event.attributeName === 'class' && event.oldValue && event.oldValue.indexOf('agsdi-loaded') !== -1 ) {
							$(event.target).addClass('agsdi-loaded');
						}
					} else {
						agsdi_render_icons( jQuery(event.target).parent() );
					}
				}
			
			}
		})).observe(document.body, {
			childList: true,
			subtree: true,
			attributeOldValue: true,
			attributeFilter: [
				'data-icon',
				'class' // needed in case something strips the agsdi-loaded class
			]
		});
	}
	
	if (window.wadi_fonts) {
		
		var $style = $('#agsdi-icons-style');
		if (!$style.length) {
			$style = jQuery('<style id="agsdi-icons-style">').appendTo( $('head') );
		}
		
		$style.append('html:before { content: \'a\'; position: absolute; top: -999px; left: -999px; }');
		
		var loadedFonts = [];
		
		var loadInterval = setInterval( function() {
			for (iconPrefix in wadi_fonts) {
				if ( loadedFonts.indexOf( wadi_fonts[iconPrefix] ) === -1 ) {
					$style.append('html:before { font-family: "' + wadi_fonts[iconPrefix] + '"; }');
					loadedFonts.push( wadi_fonts[iconPrefix] );
					return;
				}
			}
			clearInterval(loadInterval);
		}, 300 );
	}
	
	agsdi_render_icons( $('body') );
});
