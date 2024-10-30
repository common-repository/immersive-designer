(function ($) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).ready(function () {
		let addToCartNotif = $('.immdg_nadd_to_cart_notif');
		addToCartNotif.hide();
		$('.idg-buy-btn').click(function (e) {
			e.preventDefault();

			const QueryString = window.location.search;
			const urlParams = new URLSearchParams(QueryString);
			let prodId = urlParams.get('immdg_prod_id');

			let selectedOpt = $('[class*="selected-opt"]');
			let selectedOptionChoose = [];
			let prodPrice = $('[id="idg-conf-price"]').text();
			if (selectedOpt.length > 0) {
				$(this).addClass('loading');
				$('.idg-buy-btn').attr('disabled', true);
				selectedOpt.each(function () {
					let optionPart = $(this).attr('apply-part');
					selectedOptionChoose.push(optionPart);
				});
				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						'action': 'immdg_add_product_to_cart',
						'product_id': prodId,
						'product_qty': 1,
						'options_selected': selectedOptionChoose,
						'product_price': prodPrice,
					},
					success: function () {
						$('.idg-buy-btn').removeClass('loading');
						jQuery(document.body).trigger('wc_fragment_refresh');
						addToCartNotif.show();
						$('.idg-buy-btn').attr('disabled', false);
					}
				});
			}
		});
	});

})(jQuery);
