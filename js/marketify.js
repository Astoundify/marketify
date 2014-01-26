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
			$( '.site-header' ).toggleClass( 'toggled' );
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

			$( '.edd_download.content-grid-download' ).attr( 'style', '' );

			$( '.edd-slg-login-wrapper' ).each(function() {
				var link  = $(this).find( 'a' );
				var title = link.attr( 'title' );

				link.html(title).prepend( '<span></span' );
			});

			$( '.comment_form_rating .edd_reviews_rating_box' ).find('a').on('click', function (e) {
				e.preventDefault();

				$( '.comment_form_rating .edd_reviews_rating_box' ).find('a').removeClass( 'active' );

				$( this ).addClass( 'active' );
			});

			$( '#bbpress-forums #bbp-user-wrapper h2.entry-title, #bbpress-forums fieldset.bbp-form legend' ).wrapInner( '<span></span>' );

			$('body').on('click.eddwlOpenModal', '.edd-add-to-wish-list', function (e) {
				$( '#edd-wl-modal-label' ).wrapInner( '<span></span>' );
			});

			$( 'a' ).live( 'touchstart', function(e) {
				$(this).trigger( 'hover' );
			});

			$( 'a' ).live( 'touchend', function(e) {
				$(this).trigger( 'blur' );
			});
		},

		popup : function( args ) {
			return $.magnificPopup.open( $.extend( args, {
				type         : 'inline',
				overflowY    : 'hidden',
				removalDelay : 250
			} ) );
		},

		downloadStandard : function () {
			$( '.download-image.flexslider' ).flexslider({
				slideshow     : false,
				animation     : 'fade',
				animationLoop : false,
				itemWidth     : 360,
				itemMargin    : 0,
				minItems      : 1,
				maxItems      : 1,
				controlNav    : false,
				smoothHeight  : true,
				prevText      : '<i class="icon-left-open-big"></i>',
				nextText      : '<i class="icon-right-open-big"></i>'
			});
		},

		featuredPopular : function() {
			$( '.marketify_widget_featured_popular.popular .flexslider' ).flexslider({
				animation      : "slide",
				slideshow      : false,
				animationLoop  : false,
				itemWidth      : 360,
				itemMargin     : 30,
				minItems       : 1,
				maxItems       : 3,
				directionNav   : false
			});
		}
	}
} )(jQuery);

Marketify.Widgets = ( function($) {
	var widgetSettings = {};

	return {
		init : function() {
			$.each( marketifySettings.widgets, function(m, value) {
				var cb       = value.cb;
				var settings = value.settings;
				var fn       = Marketify.Widgets[cb];

				widgetSettings[m] = settings;

				if ( typeof fn === 'function' )
					fn( m );
			} );

			$( '.widget_woothemes_features, .widget_woothemes_testimonials' ).find( '.fix' ).remove();
		},

		marketify_widget_featured_popular : function( widget_id ) {
			var settings = widgetSettings[ widget_id ];

			var slider = $( '.marketify_widget_featured_popular .flexslider' ).flexslider({
				animation      : "slide",
				slideshow      : settings.scroll,
				slideshowSpeed : settings.speed,
				animationLoop  : false,
				itemWidth      : 360,
				itemMargin     : 30,
				minItems       : 1,
				maxItems       : 3,
				directionNav  : false,
				start          : function(slider) {
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
		},

		widget_woothemes_testimonials : function( widget_id ) {
			if ( this.alreadyCalled )
				return;

			var quotes = $('.individual-testimonial');

			if ( quotes.length == 2 ) {
				$( '.individual-testimonial' ).fadeIn();

				return;
			}

			var settings = widgetSettings[ widget_id ];

			quotes.find( ':first-child, :nth-child(2n)' ).addClass( 'active' );

			function cycleQuotes () {
				var current = quotes.filter(".active"), next;

				if (current.length == 0 || (next = current.next().next()).length == 0 ) {
					next = quotes.slice(0,2);
				}

				current.removeClass( 'active' ).fadeOut(400).promise().done(function(){
					next.addClass( 'active' ).fadeIn();
				});

				setTimeout(cycleQuotes, settings.speed);
			}

			cycleQuotes();

			this.alreadyCalled = true;
		}
	}

} )(jQuery);

jQuery(document).ready(function() {
	Marketify.App.init();
	Marketify.Widgets.init();
});

jQuery(window).load(function() {
	Marketify.App.downloadStandard();
	Marketify.App.featuredPopular();
});