/*
This file was copied from WordPress, copyright 2011-2019 by the contributors,
released under the GNU General Public License version 2 or later, licensed
by this project under the GPLv3 (see ../license.txt for GPLv3).

This file also contains code copied from and based on TinyMCE, copyright
Ephox Corporation, licensed by this project under the GPLv3 (see ../license.txt).

Original file path: wp-includes/js/tinymce/plugins/wpgallery/plugin.js
Also includes code from wp-includes/js/tinymce/plugins/image/plugin.js
Also includes code from wp-includes/js/tinymce/themes/modern/theme.js
*/

/* global tinymce */
(function() {
var $ = jQuery;
var icons;

// Need to update in Gutenberg integration too
var iconFilters = {
    
	'agsdi-': wp.i18n.__('Free Icons', 'ds-icon-expansion'),
    
	'agsdix-seth': wp.i18n.__('Elegant Themes', 'ds-icon-expansion'),
};

tinymce.PluginManager.add('agsdi_icons', function( editor ) {
  var DOM = tinymce.util.Tools.resolve('tinymce.dom.DOMUtils').DOM;

  editor.on('Init', function() {
	agsdi_render_icons( editor.dom.doc.body, true, true );

	var MO = window.MutationObserver ? window.MutationObserver : window.WebkitMutationObserver;
	if (MO) {
		
		(new MO(function(events) {
			for (var i = 0; i < events.length; ++i) {
				var event = events[i];
				
				if (event.addedNodes && event.addedNodes.length) {
						
					for (var j = 0; j < event.addedNodes.length; ++j) {
						agsdi_render_icons( event.addedNodes[j], true, true );
					}
					
				} else {
					agsdi_render_icons( event.target, true, true );
				}
			}
		})).observe(editor.dom.doc.body, {childList: true, subtree: true, attributeFilter: ['data-icon']});
	}

  });
  
  function getAttrib(image, name) {
    if (image.hasAttribute(name)) {
      return image.getAttribute(name);
    } else {
      return '';
    }
  }
  
  function defaultData() {
    return {
      icon: 'agsdi-aspengrovestudios',
      color: '',
      size: '48px',
      title: '',
      'class': '',
    };
  }
  
  function create(data) {
	var $icon = $('<span>').attr({
		'contenteditable': false
		}).addClass('agsdi-icon');
    write(data, $icon[0]);
    return $icon[0];
  }
  
  function read(icon) {
	var $icon = $(icon);
	var iconData = defaultData();
    iconData.icon = $icon.attr('data-icon');
    iconData.title = $icon.text();
    if ( iconData.title === getDefaultIconTitle(iconData.icon) ) {
        delete iconData.title;
    }
    iconData['class'] = $icon.clone().removeClass('agsdi-icon agsdi-selected').attr('class');
	
	var iconStyle = $icon.attr('style');
	if (iconStyle) {
		iconStyle = iconStyle.split(';');
		for (var i = 0; i < iconStyle.length; ++i) {
			iconStyle[i] = iconStyle[i].trim();
			var colonPos = iconStyle[i].indexOf(':');
			if (colonPos !== -1) {
				var property = iconStyle[i].substr(0, colonPos).toLowerCase();
				switch (property) {
					case 'color':
						iconData.color = iconStyle[i].substr(colonPos + 1).trim();
						if (iconData.color.substr(-10).toLowerCase() === '!important') {
							iconData.color = iconData.color.substr(0, iconData.color.length - 10).trim();
						}
						break;
					case 'font-size':
						iconData.size = iconStyle[i].substr(colonPos + 1).trim();
						break;
				}
			}
		}
	}
    return iconData;
  }
  
   function write(newData, icon) {
	   var $icon = $(icon);
	   var style = '';
	   if (newData.color) {
		style += 'color:' + newData.color + '!important;';
	   }
	   if (newData.size) {
		style += 'font-size:' + newData.size + ';';
	   }
	   if (!newData.title || !newData.title.trim()) {
			newData.title = getDefaultIconTitle(newData.icon);
	   }
	   $icon.attr({
			'data-icon': newData.icon,
			'data-mce-style': style,
			style: style,
			'class': 'agsdi-icon' + (newData['class'] ? ' ' + newData['class'] : '')
		}).text(newData.title);
  }
  
  function getDefaultIconTitle(icon) {
	var lastSpacePos = icon.lastIndexOf(' ');
	var firstDashPos = icon.indexOf('-', lastSpacePos === -1 ? 0 : lastSpacePos);
	if (firstDashPos !== -1 && icon.substring(0, 6) !== 'agsdi-' && icon.substring(0, 9) !== 'agsdix-fa') {
		firstDashPos = icon.indexOf('-', firstDashPos + 1);
	}
	return (firstDashPos === -1 ? icon : icon.substr(firstDashPos + 1)).replace(/\-/g, ' ') + ' icon';
  }
  
   function getSelectedIcon(editor) {
    var iconElm = editor.selection.getNode();
    if (iconElm) {
		var $iconElm = $(iconElm);
		if ($iconElm.is('a')) {
			$iconElm = $iconElm.children('span.agsdi-icon:first');
		}
		if (!$iconElm.is('span.agsdi-icon')) {
			return null;
		}
    }
    return $iconElm[0];
  }
  
  function readIconDataFromSelection(editor) {
    var icon = getSelectedIcon(editor);
    return icon ? read(icon) : defaultData();
  }
  
  function insertIconAtCaret(editor, data) {
    var elm = create(data);
    editor.dom.setAttrib(elm, 'data-mce-id', '__mcenew');
    editor.focus();
    editor.selection.setContent(elm.outerHTML);
    var insertedElm = editor.dom.select('*[data-mce-id="__mcenew"]')[0];
    editor.dom.setAttrib(insertedElm, 'data-mce-id', null);
    editor.selection.select(insertedElm);
  }
  
  function deleteIcon(editor, icon) {
    if (icon) {
      editor.dom.remove(icon);
      editor.focus();
      editor.nodeChanged();
      if (editor.dom.isEmpty(editor.getBody())) {
        editor.setContent('');
        editor.selection.setCursorLocation();
      }
    }
  }
  
  function writeIconDataToSelection(editor, data) {
    var icon = getSelectedIcon(editor);
    write(data, icon);
      editor.selection.select(icon);
  }
  
  function insertOrUpdateIcon(editor, data) {
    var icon = getSelectedIcon(editor);
    if (icon) {
      if (data.icon) {
        writeIconDataToSelection(editor, data);
      } else {
        deleteIcon(editor, icon);
      }
    } else if (data.icon) {
      insertIconAtCaret(editor, data);
    }
  }
  
  var IconPicker = tinymce.ui.Widget.extend({
    init: function (settings) {
      var self = this;
      self._super(settings);
      self.classes.add('agsdi-icon-picker');
    },
    repaint: function () {
      var self = this;
      var style, rect, borderBox, borderW, borderH = 0, lastRepaintRect;
      style = self.getEl().style;
      rect = self._layoutRect;
      lastRepaintRect = self._lastRepaintRect || {};
      var doc = document;
      borderBox = self.borderBox;
      borderW = borderBox.left + borderBox.right + 8;
      borderH = borderBox.top + borderBox.bottom;
      if (rect.x !== lastRepaintRect.x) {
        style.left = rect.x + 'px';
        lastRepaintRect.x = rect.x;
      }
      if (rect.y !== lastRepaintRect.y) {
        style.top = rect.y + 'px';
        lastRepaintRect.y = rect.y;
      }
      if (rect.w !== lastRepaintRect.w) {
        style.width = rect.w - borderW + 'px';
        lastRepaintRect.w = rect.w;
      }
      if (rect.h !== lastRepaintRect.h) {
        style.height = rect.h - borderH + 'px';
        lastRepaintRect.h = rect.h;
      }
      self._lastRepaintRect = lastRepaintRect;
      self.fire('repaint', {}, false);

      return self;
    },
    renderHtml: function () {
      var self = this;
	  var $iconPicker = $('<div>').attr('id', self._id).addClass(self.classes.toString());
	  var $filterSelect = $('<select>')
		.addClass('agsdi-picker-filter-tinymce')
		.attr({
			'onchange': 'agsdi_filter(' + JSON.stringify(self._id) + ', this)'
		})
		.append(
			$('<option>').val('').text( wp.i18n.__('All Icons', 'ds-icon-expansion') )
		)
		.appendTo($iconPicker);
	  $('<input>').attr({
		type: 'search',
		placeholder: wp.i18n.__('Search icons...', 'ds-icon-expansion'),
		oninput: 'agsdi_search(this);'
	  }).addClass('agsdi-picker-search-tinymce').appendTo($iconPicker);
	  var $iconPickerIcons = $('<div>').addClass('agsdi-icons').appendTo($iconPicker);
	  var renderIcons = function($iconPickerIcons) {
		$iconPickerIcons.empty();
		var value = self.state.get('value');
		$.each(icons, function(iconIndex, iconId) {
			if (iconId != 'agsdix-null') {
				var $icon = $('<span>').attr('data-icon-pre', iconId);
				if (iconId === value) {
					$icon.addClass('agsdi-selected');
				}
				$icon.appendTo($iconPickerIcons);
			}
		});
		self.fire('load');
	  };
	  $('<div>').text(wp.i18n.__('Loading icons...', 'ds-icon-expansion')).appendTo($iconPickerIcons);
	  
	  function processIcons() {
			var $el = $(self.getEl());
			
			var $filterSelect = $el.find('.agsdi-picker-filter-tinymce:first'), activeFilters = [];
			for ( var iconPrefixesStr in iconFilters ) {
				var iconPrefixes = iconPrefixesStr.split(',');
				for (var i = 0, foundIcon = false; i < iconPrefixes.length; ++i) {
					for (var j = 0; j < icons.length; ++j) {
						if ( icons[j].substring(0, iconPrefixes[i].length) === iconPrefixes[i] ) {
							foundIcon = true;
							break;
						}
					}
					
					if (foundIcon) {
						activeFilters.push([
							iconPrefixesStr,
							iconFilters[ iconPrefixesStr ]
						]);
						break;
					}
				}
			}
			
			activeFilters.sort(function(a, b) {
				if (b[1] < a[1]) {
					return 1;
				}
				if (b[1] > a[1]) {
					return -1;
				}
				return 0;
			});
			
			for ( var i = 0; i < activeFilters.length; i++) {
				$('<option>')
					.val( activeFilters[i][0] )
					.text( activeFilters[i][1] )
					.appendTo( $filterSelect );
			}
			
            var $agsdiIcons = $el.find('.agsdi-icons:first');
			renderIcons($agsdiIcons);
			self._handleScroll( $agsdiIcons );
			setTimeout(function() {
              self._handleScroll( $agsdiIcons  );
            }, 200)
	  }
	  
	  if (icons) {
		setTimeout(processIcons, 500);
	  } else {
		var start = Date.now ? Date.now() : 0;
		$.post(window.ajaxurl ? window.ajaxurl : ETBuilderBackendDynamic.ajaxUrl, {action: 'agsdi_get_icons'}, function(response) {
			if (response.success && response.data) {
				icons = response.data;
			}
			setTimeout( processIcons, Math.max( 1, 500 - ( (Date.now ? Date.now() : 500) - start ) ) );
		}, 'json');
	  }
      return $iconPicker[0].outerHTML;
    },
    handleScroll: function(ev) {
       if (ev.target) {
           if (this.handleScrollTimeout) {
              clearTimeout(this.handleScrollTimeout);
           }
           var self = this;
           this.handleScrollTimeout = setTimeout(function() {
              self._handleScroll( $(ev.target) );
           }, 200);
       }
    },
    _handleScroll: function($iconPickerIcons) {
	  var topMax = $iconPickerIcons.height();
	  $iconPickerIcons.find('[data-icon-pre]:visible').each(function() {
		var thisTop = $(this).position().top;
		 if ( thisTop >= -32 && thisTop <= topMax ) {
			$(this).attr({
			   'data-icon': $(this).attr('data-icon-pre'),
			   'data-icon-pre': null
			});
		 }
	  });
	  $iconPickerIcons.find('[data-icon]').each(function() {
		var $this = $(this);
		var thisTop = $this.is(':visible') ? $this.position().top : -33;
		 if ( thisTop < -32 || thisTop > topMax ) {
			$(this).attr({
			   'data-icon-pre': $this.attr('data-icon'),
			   'data-icon': null,
			   'class': null
			});
		 }
	  });
    },
    
    value: function (value) {
      if (arguments.length) {
        this.state.set('value', value);
        return this;
      }
      return this.state.get('value');
    },
    postRender: function () {
      var self = this;
	  var $iconPickerIcons = $(self.getEl()).find('.agsdi-icons:first');
      self._super();
     $iconPickerIcons.on('click', '[data-icon]', function() {
		var iconId = $(this).data('icon');
        self.state.set('value', iconId);
        self.fire('change');
      });

	  $iconPickerIcons
		.on('scroll', function(ev) { self.handleScroll(ev); })
		.on('agsdi:search agsdi:filter', function() { self._handleScroll( $iconPickerIcons ); });
      self._handleScroll( $iconPickerIcons );

    },
    bindStates: function () {
      var self = this;
      self.state.on('change:value', function (e) {
        var $iconPickerIcons = $(self.getEl()).find('.agsdi-icons:first');
		$iconPickerIcons
			.find('.agsdi-selected')
			.removeClass('agsdi-selected');
		$iconPickerIcons
			.find('[data-icon=\'' + e.value + '\']:first')
			.addClass('agsdi-selected');
      });
      return self._super();
    },
    remove: function () {
      this.$el.off();
      this._super();
    }
  });
  tinymce.ui.Factory.add('agsdi-icon-picker', IconPicker);
  
  var Credit = tinymce.ui.Widget.extend({
    init: function (settings) {
      var self = this;
      self._super(settings);
      self.classes.add('agsdi-credit');
    },
    repaint: function () {
      var self = this;
      var style, rect, borderBox, borderW, borderH = 0, lastRepaintRect;
      style = self.getEl().style;
      rect = self._layoutRect;
      lastRepaintRect = self._lastRepaintRect || {};
      var doc = document;
      borderBox = self.borderBox;
      borderW = borderBox.left + borderBox.right + 8;
      borderH = borderBox.top + borderBox.bottom;
      if (rect.x !== lastRepaintRect.x) {
        style.left = rect.x + 'px';
        lastRepaintRect.x = rect.x;
      }
      if (rect.y !== lastRepaintRect.y) {
        style.top = rect.y + 'px';
        lastRepaintRect.y = rect.y;
      }
      if (rect.w !== lastRepaintRect.w) {
        style.width = rect.w - borderW + 'px';
        lastRepaintRect.w = rect.w;
      }
      if (rect.h !== lastRepaintRect.h) {
        style.height = rect.h - borderH + 'px';
        lastRepaintRect.h = rect.h;
      }
      self._lastRepaintRect = lastRepaintRect;
      self.fire('repaint', {}, false);
      return self;
    },
    renderHtml: function () {
      var link
        
        
        link = 'WP &amp; Divi Icons by <a href="https://aspengrovestudios.com/?utm_source=wp-and-divi-icons&amp;utm_medium=plugin-credit-link&amp;utm_content=wp-editor" target="_blank">Aspen Grove Studios</a>'
        
        
    var $credit = $('<div>')
							.attr('id', this._id)
							.addClass(this.classes.toString() + ' agsdi-picker-credit')
							.html(link)
      return $credit[0].outerHTML;
    }
  });
  tinymce.ui.Factory.add('agsdi-credit', Credit);
  
  var IconPreview = tinymce.ui.Widget.extend({
    init: function (settings) {
      var self = this;
      self._super(settings);
      self.classes.add('agsdi-icon-preview');
	  self.on('repaint', this.updateIconPreview);
    },
    repaint: function () {
      var self = this;
      var style, rect, borderBox, borderW, borderH = 0, lastRepaintRect;
      style = self.getEl().style;
      rect = self._layoutRect;
      lastRepaintRect = self._lastRepaintRect || {};
      var doc = document;
      borderBox = self.borderBox;
      borderW = borderBox.left + borderBox.right + 8;
      borderH = borderBox.top + borderBox.bottom;
      if (rect.x !== lastRepaintRect.x) {
        style.left = rect.x + 'px';
        lastRepaintRect.x = rect.x;
      }
      if (rect.y !== lastRepaintRect.y) {
        style.top = rect.y + 'px';
        lastRepaintRect.y = rect.y;
      }
      if (rect.w !== lastRepaintRect.w) {
        style.width = rect.w - borderW + 'px';
        lastRepaintRect.w = rect.w;
      }
      if (rect.h !== lastRepaintRect.h) {
        style.height = rect.h - borderH + 'px';
        lastRepaintRect.h = rect.h;
      }
      self._lastRepaintRect = lastRepaintRect;
      self.fire('repaint', {}, false);
      return self;
    },
    renderHtml: function () {
	  var $iconPreview = $('<div>')
							.attr('id', this._id)
							.addClass(this.classes.toString())
							.append($('<label>').text(wp.i18n.__('Preview', 'ds-icon-expansion')));
	  var $iconPreviewInner = $('<div>').addClass('agsdi-icon-preview').appendTo($iconPreview);
      return $iconPreview[0].outerHTML;
    },
	
	updateIconPreview: function() {
		var $container = $(this.getEl()).children('.agsdi-icon-preview').empty();
		var $preview = this.getIconPreview();
		$container.append($preview);
		$preview.css({
			left: (($container.innerWidth() - $preview.width()) / 2) + 'px',
			top: (($container.innerHeight() - $preview.height()) / 2) + 'px'
		});
	},
	getIconPreview: function() {
		var $icon = $('<span>').attr('data-icon', this.settings.icon);
		if (this.settings.color) {
			$icon.css('color', this.settings.color);
		}
		if (this.settings.size) {
			$icon.css('font-size', this.settings.size);
		}
		return $icon;
	},
	setIcon: function(icon) {
		this.settings.icon = icon;
		this.updateIconPreview();
	},
	setColor: function(color) {
		this.settings.color = color;
		this.updateIconPreview();
	},
	setSize: function(size) {
		this.settings.size = size;
		this.updateIconPreview();
	}
  });
  tinymce.ui.Factory.add('agsdi-icon-preview', IconPreview);
  
  var Message = tinymce.ui.Widget.extend({
    init: function (settings) {
      var self = this;
      self._super(settings);
	  if (this.settings.className) {
		self.classes.add(this.settings.className);
	  }
    },
    repaint: function () {
      var self = this;
      var style, rect, borderBox, borderW, borderH = 0, lastRepaintRect;
      style = self.getEl().style;
      rect = self._layoutRect;
      lastRepaintRect = self._lastRepaintRect || {};
      var doc = document;
      borderBox = self.borderBox;
      borderW = borderBox.left + borderBox.right + 8;
      borderH = borderBox.top + borderBox.bottom;
      if (rect.x !== lastRepaintRect.x) {
        style.left = rect.x + 'px';
        lastRepaintRect.x = rect.x;
      }
      if (rect.y !== lastRepaintRect.y) {
        style.top = rect.y + 'px';
        lastRepaintRect.y = rect.y;
      }
      if (rect.w !== lastRepaintRect.w) {
        style.width = rect.w - borderW + 'px';
        lastRepaintRect.w = rect.w;
      }
      if (rect.h !== lastRepaintRect.h) {
        style.height = rect.h - borderH + 'px';
        lastRepaintRect.h = rect.h;
      }
      self._lastRepaintRect = lastRepaintRect;
      self.fire('repaint', {}, false);
      return self;
    },
    renderHtml: function () {
      return $('<div>')
					.attr('id', this._id)
					.addClass(this.classes.toString())
					.append($('<p>').html(this.settings.message))[0].outerHTML;
    }
  });
  tinymce.ui.Factory.add('agsdi-message', Message);
  
  function getDialogItems(editor) {
	  function onSizeSliderChange(event) {
		var sizeControl = event.control.rootControl.find('#size')[0];
		sizeControl.value(Math.round(event.control.value()) + 'px');
		sizeControl.fire('change', {noSliderUpdate: true});
	  }
    var generalFormItems = [
		{
			name: 'panel-main',
			type: 'panel',
			layout: 'grid',
			columns: 3,
			alignH: [
				'stretch',
				'left',
				'right'
			],
			alignV: 'stretch',
			items: [
				{
					name: 'icon',
					type: 'agsdi-icon-picker',
					onchange: function(event) {
						var icon = this.value();
						this.rootControl.find('#preview')[0].setIcon(icon);
						if (icon.substr(0, 11) === 'agsdix-smc-') {
							this.rootControl.find('#color').parent().hide();
							this.rootControl.find('#colorpicker').hide();
							this.rootControl.find('#multi-color-message').show();
						} else {
							this.rootControl.find('#color').parent().show();
							this.rootControl.find('#colorpicker').show();
							this.rootControl.find('#multi-color-message').hide();
						}
						$(this.rootControl.find('#title')[0].getEl()).attr('placeholder', getDefaultIconTitle(icon));
					},
					onload: function() {
						var previewControl = this.rootControl.find('#preview')[0];
						if (previewControl) {
							previewControl.setIcon(this.value());
						}
					}
				},
				{
					type: 'container',
					layout: 'fit',
					items: [
						{
							type: 'form',
							items: [
							  {
								name: 'color',
								type: 'textbox',
								label: 'Icon color',
								onchange: function(event) {
									var rootControl = event.control.rootControl;
									var newColor = this.state.get('value');
									if (!event.noUpdatePicker) {
										rootControl.find('#colorpicker')[0].value(newColor);
									}
									rootControl.find('#preview')[0].setColor(newColor);
								}
							  },
							  {
								name: 'colorpicker',
								type: 'colorpicker',
								onchange: function(event) {
									var colorControl = event.control.rootControl.find('#color')[0];
									colorControl.state.set('value', event.control.value());
									colorControl.fire('change', {noUpdatePicker: true});
								}
							  },
							  {
								name: 'multi-color-message',
								type: 'agsdi-message',
								hidden: true,
								className: 'agsdi-multi-color-message',
								message: ags_divi_icons_tinymce_config.multiColorMessage
							  },
							  {
								name: 'size',
								type: 'textbox',
								label: 'Icon size',
								onchange: function(event) {
									var newValue = this.value().trim();
									if (!event.noSliderUpdate && newValue.substr(-2).toLowerCase() === 'px') {
										var sliderValue = newValue.substr(0, newValue.length - 2);
										if (!isNaN(sliderValue)) {
											sliderValue = Math.round(sliderValue);
											if (sliderValue < 16) {
												sliderValue = 16;
											} else if (sliderValue > 128) {
												sliderValue = 128;
											}
											event.control.rootControl.find('#size_slider')[0].value(sliderValue);
										}
									}
									
									event.control.rootControl.find('#preview')[0].setSize(newValue);
									
								}
							  },
							  {
								name: 'size_slider',
								type: 'slider',
								minValue: 16,
								maxValue: 128,
								value: 16,
								previewFilter: function(value) {
									return Math.round(value) + 'px';
								},
								ondrag: onSizeSliderChange,
								ondragstart: onSizeSliderChange,
								ondragend: onSizeSliderChange
							  },
							  {
								name: 'title',
								type: 'textbox',
								label: 'Icon title'
							  },
							  {
								name: 'class',
								type: 'textbox',
								label: 'Icon class(es)'
							  },
							  {
								name: 'style-inherit-message',
								type: 'agsdi-message',
								className: 'agsdi-style-inherit-message',
								message: ags_divi_icons_tinymce_config.styleInheritMessage
							  }
							]
						}
					]
				},
				{
					name: 'preview',
					type: 'agsdi-icon-preview'
				},
			]
		},
		
		{
			type: 'agsdi-credit'
		}
		/*{
		  label: 'Border style',
		  type: 'listbox',
		  name: 'borderStyle',
		  width: 90,
		  maxWidth: 90,
		  onselect: function (evt) {
			updateStyle(editor, evt.control.rootControl);
		  },
		  values: [
			{
			  text: 'Select...',
			  value: ''
			},
		  ]
		}*/
	];
    return generalFormItems;
  }

  function curry(f) {
    var x = [];
    for (var _i = 1; _i < arguments.length; _i++) {
      x[_i - 1] = arguments[_i];
    }
    var args = new Array(arguments.length - 1);
    for (var i = 1; i < arguments.length; i++)
      args[i - 1] = arguments[i];
    return function () {
      var x = [];
      for (var _i = 0; _i < arguments.length; _i++) {
        x[_i] = arguments[_i];
      }
      var newArgs = new Array(arguments.length);
      for (var j = 0; j < newArgs.length; j++)
        newArgs[j] = arguments[j];
      var all = args.concat(newArgs);
      return f.apply(null, all);
    };
  }

  
  
  function Dialog(editor) {
    return {
		open: function() {
		  var selectedIcon = getSelectedIcon(editor), data = readIconDataFromSelection(editor);
		  var win;
			win = editor.windowManager.open({
			  title: wp.i18n.__('Insert Icon', 'ds-icon-expansion'),
			  data: data,
			  body: getDialogItems(editor),
			  minWidth: 800,
			  classes: 'agsdi-insert-dialog', // good
			  onSubmit: curry(
				  function submitForm(editor, evt) {
				    if (selectedIcon) {
						var $linkParent = $(selectedIcon).parent('a');
						editor.selection.select($linkParent.length ? $linkParent[0] : selectedIcon);
					}
					var win = evt.control.getRoot();
					editor.undoManager.transact(function () {
					  var data = Object.assign(readIconDataFromSelection(editor), win.toJSON());
					  insertOrUpdateIcon(editor, data);
					});
				  }, editor)
			});
			win.find('#icon').fire('change');
			win.find('#color').fire('change');
			win.find('#size').fire('change');
		}
	};
  }

  
  function hasIconClass(node) {
    var className = node.attr('class');
    return className && /\bagsdi\-icon\b/.test(className);
  };
  
  function toggleContentEditableState(state) {
    return function (nodes) {
      var i = nodes.length, node;
      while (i--) {
        node = nodes[i];
        if (hasIconClass(node)) {
          node.attr('contenteditable', state ? 'false' : null);
        }
      }
    };
  }

  function unselect() {
	var icons = editor.dom.select( 'span.agsdi-icon' );
	if (icons.length) {
		$(icons).data('agsdi-selected', null);
	}
  }
  
    editor.on('preInit', function () {
      editor.parser.addNodeFilter('span', toggleContentEditableState(true));
      editor.serializer.addNodeFilter('span', toggleContentEditableState(false));
	  
	  var linkFormat = editor.formatter.get('link');
	  var oldOnFormat = linkFormat[0].onformat;
	  linkFormat.push(Object.assign({}, linkFormat[0], {
			selector: 'span.agsdi-icon',
			ceFalseOverride: true,
			onformat: function() {
				var oldElement = arguments[0];
				arguments[0] = $('<a>').append($(arguments[0]).clone())[0];
				oldOnFormat.apply(null, arguments);
				editor.dom.replace(arguments[0], oldElement);
				editor.selection.select(arguments[0]);
			},
			onmatch: null
		})
	  );
	  editor.formatter.register('link',
		linkFormat
	  );
		// For linked icons, always select the link rather than the icon span itself
		editor.selection.selectorChanged('a > span.agsdi-icon', function(isSelected, selection) {
			if (isSelected && selection.parents && selection.parents[1]) {
				editor.selection.select(selection.parents[1]);
			}
		});
    });
	  
    editor.addButton('agsdi_icons', {
      icon: 'agsdi',
      tooltip: wp.i18n.__('Insert Icon', 'ds-icon-expansion'),
      onclick: Dialog(editor).open,
      stateSelector: 'span.agsdi-icon'
    });

	// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('...');
	editor.addCommand( 'agsdi_icon', function() {
		Dialog( editor ).open();
	});
	
	editor.on( 'mouseup', function( event ) {
		var dom = editor.dom,
			node = event.target;
		if ( node.nodeName === 'SPAN' && $(node).hasClass('agsdi-icon') ) {
			
			// Don't trigger on right-click
			if ( event.button !== 2 ) {
				if ( $(node).data('agsdi-selected') ) {
					Dialog( editor ).open();
				} else {
					unselect();
					$(node).data('agsdi-selected', 1);
				}
			}
		} else {
			unselect();
		}
	});
	

	// Display icon instead of span in the element path
	editor.on( 'ResolveName', function( event ) {
		var dom = editor.dom,
			node = event.target;

		if ( node.nodeName === 'SPAN' && $(node).hasClass('agsdi-icon') ) {
			event.name = 'icon';
		}
	});

	editor.on( 'PostProcess', function( event ) {
		if ( event.get ) {
			unselect();
		}
	});
});

// Following code from Page Builder Everywhere
tinymce._agsdi_init = tinymce.init;
tinymce.init = function(arg1) {
	if (!window.tinyMCEPreInit) {
		if (arg1.content_css) {
			arg1.content_css += ',' + ags_divi_icons_config.mceStyles;
		} else {
			arg1.content_css = ags_divi_icons_config.mceStyles;
		}
		if (arg1.toolbar) {
			arg1.plugins += ' agsdi_icons';
			arg1.toolbar += ',agsdi_icons';
		}
	}
	
	return tinymce._agsdi_init(arg1);
}
// End code from Page Builder Everywhere


})();

function agsdi_filter(elId, selectField) {
	var filter = jQuery(selectField).val();
	
	if (filter) {
		filter = filter.split(',');
		var $style = jQuery('#' + elId + '-agsdi-filter-style');
		if (!$style.length) {
			$style = jQuery('<style>').attr('id', elId + '-agsdi-filter-style').appendTo('head:first');
		}
		
		var css = '#' + elId + ' .agsdi-icons [data-icon],#' + elId + ' .agsdi-icons [data-icon-pre]{ display: none; }';
		for ( var i = 0; i < filter.length; ++i ) {
			css += '#' + elId + ' .agsdi-icons [data-icon^="' + filter[i] + '"], #' + elId + ' .agsdi-icons [data-icon-pre^="' + filter[i] + '"]{ display: inline-block; }';
		}
		
		$style.text(css);
	} else {
		jQuery('#' + elId + '-agsdi-filter-style').remove();
	}
	
	jQuery('#' + elId + ' .agsdi-icons:first').trigger('agsdi:filter');
}