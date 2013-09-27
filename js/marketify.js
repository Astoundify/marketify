var Marketify = {};

Marketify.App = ( function($) {
	function menuSearch() {
		$( '.menu-search-toggle' ).click(function(e) {
			e.preventDefault();

			$( '.main-navigation .search-wrapper' ).fadeToggle();
		});
	}

	return {
		init : function() {
			menuSearch();
		}
	}
} )(jQuery);

Marketify.Widgets = ( function($) {

	function marketify_widget_featured_popular() {
		var slider = $( '.flexslider' ).flexslider({
			animation     : "slide",
			animationLoop : false,
			itemWidth     : 360,
			itemMargin    : 30,
			minItems      : 1,
			maxItems      : 3,
			directionNav  : false,
		});

		$( '.marketify_widget_featured_popular .flexslider' ).hide().filter( ':first-of-type' ).fadeIn( 'slow' );

		$( '.marketify_widget_featured_popular .home-widget-title span' ).click(function() {
			console.log( slider );
			slider.resize();

			if ( 0 == $(this).index() ) {
				$( '.marketify_widget_featured_popular .flexslider' ).hide();
				$( '.marketify_widget_featured_popular .flexslider:first-of-type' ).fadeIn();
			} else {
				$( '.marketify_widget_featured_popular .flexslider' ).hide();
				$( '.marketify_widget_featured_popular .flexslider:last-of-type' ).fadeIn();
			}
		});
	}

	return {
		init : function() {
			marketify_widget_featured_popular();
		}
	}

} )(jQuery);

jQuery(function($) {
	Marketify.App.init();
	Marketify.Widgets.init();
});