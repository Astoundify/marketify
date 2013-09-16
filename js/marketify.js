var Marketify = {};

Marketify.Widgets = ( function($	) {

	function marketify_widget_featured_popular() {
		$( '.flexslider' ).flexslider({
			animation: "slide",
			animationLoop: false,
			itemWidth: 360,
			itemMargin: 30,
			minItems : 2,
			maxItems : 3
		});
	}

	return {
		init : function() {
			//marketify_widget_featured_popular();
		}
	}

} )(jQuery);

jQuery(function($) {
	Marketify.Widgets.init();
});