window.cImageWidget = window.cImageWidget || {};

( function( window, undefined ) {

	window.wp = window.wp || {};
	var document = window.document;
	var $ = window.jQuery;

	var imageSetting = function( $trigger ) {
		var $target = $( '#' + $trigger.data( 'target' ) );

		var frame = wp.media.frames._frame = wp.media( {
			title: 'Choose an Image',
			button: {
				text: 'Use Image'
			},
			multiple: false
		} );

		var bindFrame = function( e ) {
			e.stopPropagation();

			frame.open();

			frame.on( 'select', function() {
				var attachment = frame
					.state()
					.get( 'selection' )
					.first()
					.toJSON();

				$target.val( attachment.sizes.full.url );
				$target.trigger( 'change' );
			} );
		}

		$trigger.on( 'click', bindFrame );
	}

	var bindButtons = function() {
		$( '.js-widget-settings-add-media' ).each( function() {
			return imageSetting( $( this ) )
		} );
	};

	$( function() {
		bindButtons();

		$( document ).on( 'widget-added', bindButtons );
	} );

} )( window );
