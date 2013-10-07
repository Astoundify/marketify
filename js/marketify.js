var Marketify = {};

Marketify.App = ( function($) {
	function menuSearch() {
		$( '.main-navigation .search-form .search-submit' ).click(function(e) {
			if ( $( '.main-navigation .search-form .search-field' ).val() == '' )
				e.preventDefault();
			
			$( '.main-navigation .search-form' )
				.addClass( 'active' )
		});
	}

	function menuMobile() {
		var container, button, menu;

		container = $( '#site-navigation' );

		if ( ! container )
			return;

		button = container.find( $( 'h1' ) );
		
		if ( 'undefined' === typeof button )
			return;

		menu = container.find( $( 'ul:first-of-type' ) );

		// Hide menu toggle button if menu is empty and return early.
		if ( 'undefined' === typeof menu ) {
			button.css( 'display', 'none' );
			
			return;
		}

		if ( ! menu.hasClass( 'nav-menu' ) )
			menu.addClass( 'nav-menu' );

		button.click(function() {
			container.toggleClass( 'toggled' );
		});
	}

	function skipLink() {
		var is_webkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
		    is_opera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
		    is_ie     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

		if ( ( is_webkit || is_opera || is_ie ) && 'undefined' !== typeof( document.getElementById ) ) {
			var eventMethod = ( window.addEventListener ) ? 'addEventListener' : 'attachEvent';
			window[ eventMethod ]( 'hashchange', function() {
				var element = document.getElementById( location.hash.substring( 1 ) );

				if ( element ) {
					if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) )
						element.tabIndex = -1;

					element.focus();
				}
			}, false );
		}
	}

	function footerHeight() {
		var checks = $( '.site-info, .site-footer .row' );

		checks.each(function() {
			var min      = 0;
			var children = $(this).children();

			children.each(function() {
				if ( $(this).outerHeight() > min )
					min = $(this).outerHeight();
			});
			
			if ( $(window).width() < 978 )
				children.css( 'height', 'auto' );
			else
				children.css( 'height', min );
		});
	}

	return {
		init : function() {
			menuSearch();
			menuMobile();
			skipLink();
			footerHeight();

			$(window).resize(function() {
				footerHeight();
			});

			$( '.popup-trigger' ).click(function(e) {
				e.preventDefault();

				Marketify.App.popup({
					items : {
						src : $(this).attr( 'href' )
					}
				});
			});

			$( '.edd-slg-login-wrapper' ).each(function() {
				var link  = $(this).find( 'a' );
				var title = link.attr( 'title' );

				link.html(title).prepend( '<span></span' );
			});
		},

		popup : function( args ) {
			return $.magnificPopup.open( $.extend( args, { 
				type            : 'inline',
				alignTop        : true
			} ) );
		},
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
			start         : function(slider) {
				slider.css( 'display', 'none' );

				$( '.marketify_widget_featured_popular .flexslider:first-of-type' ).fadeIn( 'slow' );
			}
		});

		$( '.marketify_widget_featured_popular .home-widget-title span' ).click(function() {
			if ( 0 == $(this).index() ) {
				$( '.marketify_widget_featured_popular .flexslider' ).hide();
				$( '.marketify_widget_featured_popular .flexslider:first-of-type' ).fadeIn();
			} else {
				$( '.marketify_widget_featured_popular .flexslider' ).hide();
				$( '.marketify_widget_featured_popular .flexslider:last-of-type' ).fadeIn();
			}

			slider.resize();
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