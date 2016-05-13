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
					$( '.plugins-to-import, .import-summary' ).toggle();

					groups = response.data.groups;
					
					stageImport( groups );
					runImport( groups, $button.attr( 'name' ) );
				} else {
					console.log( 'Unable to stage files.' );
				}
			}
		});
	});

	function stageImport( groups ) {
		_.each( groups, function(items, type) {
			$( '#' + type + '-total' ).text( items.length );
		});
	}

	function runImport( groups, iterate_action ) {
		var groupPromises = [];

		_.each(groups, function(items, type) {
			var itemPromises = [];
			var $spinner = $( '#' + type + '-spinner' );

			$spinner.addClass( 'is-active' );

			var group = new $.Deferred();

			_.each(items, function(item) {
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
						var $processed = $( '#' + type + '-processed' );
						var processed_count = parseInt( $processed.text() );

						$processed.text( processed_count + 1);
					}
				});

				itemPromises.push(request);
			});

			$.when.apply(null, itemPromises).done(function() {
				$spinner.removeClass( 'is-active' );
				group.resolve();
			});

			groupPromises.push(group);
		});

		$.when.apply(null, groupPromises).done(function() {
			$( '#import-status' ).css( 'color', 'green' ).text( 'Import Complete!' );
		});

	}
});

