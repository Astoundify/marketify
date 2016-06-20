jQuery(document).ready(function($) {
	var importRunning = false;

	function beforeUnload() {
		if ( importRunning ) {
			return 'Please do not leave while an import is in progress.';
		}
	};

	$(window).bind( 'beforeunload', beforeUnload );

	$form = $( '#astoundify-content-importer' );

	$form.on( 'submit', function(e) {
		return false;
	});

	$form.find( 'input[type=submit]' ).on( 'click', function(e) {
		e.preventDefault();

		var $button = $(this);

		var args = {
			action: 'astoundify_content_importer',
			security: astoundifyContentImporter.nonces.stage,
			style: $( 'input[name=demo_style]:checked' ).val()
		};

		return $.ajax({
			type: 'POST',
			url: ajaxurl, 
			data: args, 
			dataType: 'json',
			success: function(response) {
				if ( response.success ) {
					$( '#plugins-to-import' ).hide();
					$( '#import-summary' ).show();

					groups = response.data.groups;
					items = response.data.items;
					
					stageImport( groups );
					runImport( items, $button.attr( 'name' ) );
				} else {
					$( '#import-errors' ).html( '<li>' + response.data + '</li>' );
				}
			}
		});
	});

	function stageImport( groups ) {
		_.each( groups, function(items, type) {
			var total = items.length;

			if ( 0 == total ) {
				$( '#import-type-' + type ).hide();
			} else {
				typeElement( type, 'spinner' ).addClass( 'is-active' );
				typeElement( type, 'processed' ).text(0);
				typeElement( type, 'total' ).text(total);
			}
		});
	}

	function runImport( items, iterate_action ) {
		var $errors = $( '#import-errors' ).html( '' );
		var dfd = $.Deferred().resolve();
		var total_processed_count = 0;
		var total_to_process = items.length;
		var $stepTitle = $( '#step-status-import-content' );

		importRunning = true;

		_.each(items, function(item) {
			dfd = dfd.then(function() {
				var type = item.type;
				var $processed = typeElement( type, 'processed' );
				var $total = typeElement( type, 'total' );

				args = {
					action: 'astoundify_importer_iterate_item',
					iterate_action: iterate_action,
					item: item
				}

				var request = $.ajax({
					type: 'POST',
					url: ajaxurl,
					data: args,
					dataType: 'json',
					success: function(response) {
						/**
						 * These should be hooked in instead
						 */

						// log error
						if ( response.success == false ) {
							$errors.append( '<li>' + response.data + '</li>' );
						}

						// update group info
						var processed_count = parseInt( $processed.text() );
						$processed.text( processed_count + 1);

						if ( $processed.text() == $total.text() ) {
							typeElement( type, 'spinner' ).removeClass( 'is-active' );
						}

						// update action buttons and step title
						total_processed_count = total_processed_count + 1;

						if ( total_processed_count == total_to_process ) {
							importRunning = false;

							if ( 'import' == iterate_action ) {
								$stepTitle.text( $stepTitle.data( 'string-complete' ) ).removeClass( 'step-incomplete' ).addClass( 'step-complete' );
							} else {
								$stepTitle.text( $stepTitle.data( 'string-incomplete' ) ).removeClass( 'step-complete' ).addClass( 'step-incomplete' );
							}
						}
					}
				});

				return request;
			});
		});
	}

	function typeElement( type, element ) {
		return $( '#' + type + '-' + element );
	}
});
