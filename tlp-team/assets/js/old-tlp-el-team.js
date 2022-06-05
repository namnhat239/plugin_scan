(function($) {
	'use strict';

	window.initTlpElTeam = function() {
		$('.rt-team-container').each(function() {
			var container = $(this),
				str = container.attr('data-layout'),
				html_loading =
					'<div class="rt-loading-overlay"></div><div class="rt-loading rt-ball-clip-rotate"><div></div></div>',
				preLoader = container.find('.ttp-pre-loader'),
				loader = container.find('.rt-content-loader'),
				IsotopeWrap = '',
				isIsotope = $('.tlp-team-isotope', container),
				IsoButton = $('.ttp-isotope-buttons', container),
				isCarousel = $('.rt-carousel-holder', container),
				remove_placeholder_loading = function() {
					loader.find('.rt-loading').fadeOut(300);
					loader.removeClass('ttp-pre-loader');
				};

			if (str) {
				var buttonFilter;
				if (preLoader.find('.rt-loading-overlay').length == 0) {
					preLoader.append(html_loading);
				}

				if (isCarousel.length) {
					isCarousel.imagesLoaded(function() {
						rtSliderInit();

						$(document).on('rttm_slider_loaded', function() {
							isCarousel.removeClass('slider-loading');
							remove_placeholder_loading();
						});
					});
				} else if (isIsotope.length) {
					if (!buttonFilter) {
						buttonFilter = IsoButton.find('button.selected').data('filter');
					}
					IsotopeWrap = isIsotope.imagesLoaded(function() {
						preFunction();
						IsotopeWrap.isotope({
							itemSelector: '.isotope-item',
							masonry: { columnWidth: '.isotope-item' },
							filter: function() {
								return buttonFilter ? $(this).is(buttonFilter) : true;
							}
						});
						setTimeout(function() {
							IsotopeWrap.isotope();
							remove_placeholder_loading();
						}, 100);
					});

					IsoButton.on('click touchstart', 'button', function(e) {
						e.preventDefault();
						buttonFilter = $(this).attr('data-filter');
						IsotopeWrap.isotope();
						$(this).parent().find('.selected').removeClass('selected');
						$(this).addClass('selected');
					});
				} else {
					container.imagesLoaded(function() {
						preFunction();
						remove_placeholder_loading();
					});
				}
			}
		});
	};

	$(document).on('ready', function() {
		initTlpElTeam();
	});

	$(window).on('load resize', function() {
		HeightResize();
	});

	function preFunction() {
		HeightResize();
	}

	function HeightResize() {
		var wWidth = $(window).width();
		$(".rt-team-container[data-layout*='isotope']").each(function() {
			var self = $(this),
				dCol = self.data('desktop-col'),
				tCol = self.data('tab-col'),
				mCol = self.data('mobile-col'),
				target = $(this).find('.rt-row.rt-content-loader.ttp-even');
			if ((wWidth >= 992 && dCol > 1) || (wWidth >= 768 && tCol > 1) || (wWidth < 768 && mCol > 1)) {
				target.imagesLoaded(function() {
					var tlpMaxH = 0;
					target.find('.even-grid-item').height('auto');
					target.find('.even-grid-item').each(function() {
						var $thisH = $(this).outerHeight();
						if ($thisH > tlpMaxH) {
							tlpMaxH = $thisH;
						}
					});
					target.find('.even-grid-item').height(tlpMaxH + 'px');
				});
			} else {
				target.find('.even-grid-item').height('auto');
			}
		});
	}

	function rtSliderInit() {
		$('.rttm-carousel-slider').each(function() {
			$(this).rttm_slider();
		});
	}

	var RttmSlider = function($slider) {
		this.$slider = $slider;
		this.slider = this.$slider.get(0);
		this.swiperSlider = this.slider.swiper || null;
		this.defaultOptions = {
			breakpointsInverse: true,
			observer: true,
			navigation: {
				nextEl: this.$slider.find('.swiper-button-next').get(0),
				prevEl: this.$slider.find('.swiper-button-prev').get(0)
			},
			pagination: {
				el: this.$slider.find('.swiper-pagination').get(0),
				type: 'bullets',
				clickable: true
			}
		};

		this.slider_enabled = 'function' === typeof Swiper;
		this.options = Object.assign({}, this.defaultOptions, this.$slider.data('options') || {});

		this.initSlider = function() {
			if (!this.slider_enabled) {
				return;
			}
			if (this.options.rtl) {
				this.$slider.attr('dir', 'rtl');
			}
			if (this.swiperSlider) {
				this.swiperSlider.parents = this.options;
				this.swiperSlider.update();
			} else {
				this.swiperSlider = new Swiper(this.$slider.get(0), this.options);
			}
		};

		this.imagesLoaded = function() {
			if (this.$slider.data('options').lazy) {
				this.$slider.trigger('rttm_slider_loaded', this);
				return;
			}

			var that = this;

			if (!$.isFunction($.fn.imagesLoaded) || $.fn.imagesLoaded.done) {
				this.$slider.trigger('rttm_slider_loading', this);
				this.$slider.trigger('rttm_slider_loaded', this);
				return;
			}

			this.$slider
				.imagesLoaded()
				.progress(function(instance, image) {
					that.$slider.trigger('rttm_slider_loading', [ that ]);
				})
				.done(function(instance) {
					that.$slider.trigger('rttm_slider_loaded', [ that ]);
				});
		};

		this.start = function() {
			var that = this;
			this.$slider.on('rttm_slider_loaded', this.init.bind(this));
			setTimeout(function() {
				that.imagesLoaded();
			}, 1);
		};

		this.init = function() {
			this.initSlider();
		};

		this.rtSwiper = function() {
			return new Swiper(this.$slider.get(0), this.options);
		};

		this.start();
	};

	$.fn.rttm_slider = function() {
		new RttmSlider(this);
		return this;
	};
})(jQuery);
