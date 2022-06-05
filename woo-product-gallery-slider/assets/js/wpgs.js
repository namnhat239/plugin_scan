
(function ($) {
	'use strict';

	$(document).ready(function () {

		jQuery('.wpgs img').removeAttr('srcset');

		$('.woocommerce-product-gallery__image img').load(function () {

			var imageObj = $('.woocommerce-product-gallery__image img');

			if (!(imageObj.width() == 1 && imageObj.height() == 1)) {

				$('.wpgs-thumb-main-image').attr('src', imageObj.attr('src'));
				$('.wpgs-thumb-main-image').trigger('click');

			}
		});

		// Check if have Fancybox
		if (typeof $.fn.fancybox == 'function') {
			// Customize icons

			$.fancybox.defaults = $.extend(true, {}, $.fancybox.defaults, {

				thumbs: false

			});

			var selector = '.wpgs .slick-slide:not(.slick-cloned) a';

			// Skip cloned elements
			$().fancybox({
				selector: selector,
				backFocus: false
			});

			// Attach custom click event on cloned elements, 
			// trigger click event on corresponding link
			$(document).on('click', '.slick-cloned a', function (e) {
				$(selector)
					.eq(($(e.currentTarget).attr("data-slick-index") || 0) % $(selector).length)
					.trigger("click.fb-start", {
						$trigger: $(this)
					});
				return false;
			});
		}

	});

})(jQuery);

// Other code using $ as an alias to the other library
