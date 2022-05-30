(function($){
	
	acf.fields.extended_color_picker = acf.field.extend({
		
		type: 'extended-color-picker',
		$input: null,
		$transparent: null,
		$fieldpalette: null,
		$color_palette: null,
		
		actions: {
			'ready':	'initialize',
			'append':	'initialize'
		},
		
		focus: function(){
			
			this.$input = this.$field.find('input[type="text"]');

			this.$transparent = 'url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAAHnlligAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHJJREFUeNpi+P///4EDBxiAGMgCCCAGFB5AADGCRBgYDh48CCRZIJS9vT2QBAggFBkmBiSAogxFBiCAoHogAKIKAlBUYTELAiAmEtABEECk20G6BOmuIl0CIMBQ/IEMkO0myiSSraaaBhZcbkUOs0HuBwDplz5uFJ3Z4gAAAABJRU5ErkJggg==)';
			
		},
		
		initialize: function(){

			// reference
			var $input = this.$input,
				$transparent = this.$transparent,
				$fieldpalette = this.$field.find('.acf-color-picker').data('palette'),				
				$standardpalette = rgbaColorPicker.palette;
			
			if ( $fieldpalette == 'no-palette' || $standardpalette == '' ) {
				$color_palette = false;
			} else if ( $fieldpalette != '' ) {
				$color_palette = $fieldpalette.split(';');
			} else {
				$color_palette = rgbaColorPicker.palette;
			}

			var eventTarget,
				colorResultTarget,
				hiddenTarget,
				valueTarget;

			// args
			var args = {
				
				defaultColor: false,
				palettes: $color_palette,
				hide: true,
				change: function(event) {
					// timeout is required to ensure the $input val is correct
					setTimeout(function(){
						
						eventTarget = $(event.target).parents('[data-target="target"]');
														
						hiddenTarget = eventTarget.find('.hiddentarget');
							
						valueTarget = eventTarget.find('.valuetarget');

						acf.val( hiddenTarget, valueTarget.val() );
						
					}, 1);
				},
				clear: function(event) {
					// timeout is required to ensure the $input val is correct
					setTimeout(function(){
						
						eventTarget = $(event.target).parents('[data-target="target"]');
							
						colorResultTarget = eventTarget.find('.wp-color-result');
						
						hiddenTarget = eventTarget.find('.hiddentarget');
						
						valueTarget = eventTarget.find('.valuetarget');
						
						colorResultTarget.css({
							'background-image' : $transparent,
							'background-color' : 'transparent'
						});

						acf.val( hiddenTarget, valueTarget.val() );
						
					}, 1);
				}
				
			}
	 			
	 		// iris
			this.$input.wpColorPicker(args);
			
		}
		
	});
	
})(jQuery);
