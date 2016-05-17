jQuery(document).ready(function($) {
	$form = $( '#marketify-oneclick-setup' );

	$form.on( 'submit', function(e) {
		return false;
	});

	$form.find( 'input[type=submit]' ).on( 'click', function(e) {
		e.preventDefault();

		var $button = $(this);

		var args = {
			action: 'astoundify_setup_guide_stage_import',
			security: astoundifySetupGuideImportContent.nonces.stage,
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
						var processed_count = parseInt( $processed.text() );

						$processed.text( processed_count + 1);

						if ( response.success == false ) {
							$errors.append( '<li>' + response.data + '</li>' );
						}

						if ( $processed.text() == $total.text() ) {
							typeElement( type, 'spinner' ).removeClass( 'is-active' );
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

