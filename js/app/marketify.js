var Marketify = {};

Marketify.App = ( function($) {
	function menuSearch() {
		$( '.header-search-icon, .header-search-toggle' ).click(function(e) {
			e.preventDefault();

			$( '.search-form-overlay' ).toggleClass( 'active' );
		});
	}

	function footerHeight() {
		var checks = $( '.site-info, .footer-widget-areas' );

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

	function soliloquySliders() {
		if ( $(window).width() < 500 ) {
			var sliders = $( '.soliloquy' );

			$.each(sliders, function() {
				var image = $(this).find( 'img' ),
				    src   = image.prop( 'src' );

				console.log( src );

				$(this)
					.find( 'li' )
					.css({
						'height'           : $(window).outerHeight(),
						'background-image' : 'url(' + src + ')',
						'background-size'  : 'cover'
					});

				image.hide();
			});
		}
	}

	return {
		init : function() {
			menuSearch();
			footerHeight();
			soliloquySliders();

			$(window).resize(function() {
				footerHeight();
				soliloquySliders();
			});

			$(document).on( 'click', '.popup-trigger', function(e) {
				e.preventDefault();

				Marketify.App.popup({
					items : {
						src : $(this).attr( 'href' ),
						fixedContentPos: false,
						fixedBgPos: false,
						overflowY: 'scroll'
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

      $( '.edd_form fieldset > span legend' ).unwrap();

			$( '#bbpress-forums #bbp-user-wrapper h2.entry-title, #bbpress-forums fieldset.bbp-form legend, .fes-form h1, .fes-headers, .edd_form *:not(span) > legend' ).wrapInner( '<span></span>' );

			$('body').on('click.eddwlOpenModal', '.edd-add-to-wish-list', function (e) {
				$( '#edd-wl-modal-label' ).wrapInner( '<span></span>' );
			});

			$( '.download-sorting input, .download-sorting select' ).change(function(){
				$(this).closest( 'form' ).submit();
			});

			$( '.download-sorting span' ).click( function(e) {
				e.preventDefault();
				$(this).prev().attr( 'checked', true );
				$(this).closest( 'form' ).submit();
			});

			$( '.entry-image' ).bind( 'touchstart', function(e) {
				$(this).toggleClass( 'hover' );
			});

			$( '.individual-testimonial .avatar' ).wrap( '<div class="avatar-wrap"></div>' );

			$( '.edd_downloads_list:not(.has-slick)' ).each(function() {
				var pagination = $(this).closest( $( '#edd_download_pagination' ) );
				var clone = pagination.clone();

				pagination.remove();

				clone.insertAfter( $(this) );
			});

		},

		popup : function( args ) {
			return $.magnificPopup.open( $.extend( args, {
				type         : 'inline',
				overflowY    : 'hidden',
				removalDelay : 250
			} ) );
		},
	}
} )(jQuery);

jQuery(document).ready(function() {
	Marketify.App.init();
});
