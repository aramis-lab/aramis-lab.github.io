jQuery( function( $ ) {
	var currentSection = '';
	$( '.page-sections a' ).on( 'click', function( e ) {
		e.preventDefault();

		$( '.page-sections li' ).removeClass( 'active-section' );
		var $$ = $( this );

		currentSection = $$.data( 'section' );
		$$.parent( 'li' ).addClass( 'active-section' );

		$( '.siteorigin-installer-item').show();
		if ( currentSection != 'all' ) {
			$( '.siteorigin-installer-item:not(.siteorigin-' + currentSection + ')' ).hide();
		}

		$( window ).trigger( 'resize' );
	} );

	$( 'span.siteorigin-installer' ).on( 'click', function( e ) {
		e.preventDefault();

		var $$ = $( this );
		if ( ! $$.hasClass( 'disabled' ) ) {
			$$.addClass( 'disabled' );
			$.post(
				soInstallerAdmin.manageUrl,
				{
					'type' : $$.data( 'type' ),
					'slug' : $$.data( 'slug' ),
					'task' : $$.data( 'status' ),
					'version' : $$.data( 'version' )
				},
				function( data ) {
					if ( $$.data( 'status' ) == 'activate' ) {
						if ( $$.data( 'type' ) == 'themes' ) {
							var $activeItem = $( '.siteorigin-installer-item-active.siteorigin-themes' )
							$activeItem.addClass( 'siteorigin-installer-item-inactive' ).removeClass( 'siteorigin-installer-item-active' );
							$activeItem.find( '.siteorigin-installer' ).removeClass( 'disabled' );
							$activeItem.removeClass( 'siteorigin-installer-item-active' );
							$( '.siteorigin-themes span.siteorigin-installer' ).show();
							$$.hide();
						}
						$$.parents( '.siteorigin-installer-item-inactive' ).removeClass( 'siteorigin-installer-item-inactive' ).addClass( 'siteorigin-installer-item-active' );
						if ( $$.data( 'type' ) == 'plugins' ) {
							$$.remove();
						}
					} else if ( $$.data( 'status' ) == 'update' ) {
						$$.remove();
					} else {
						$$.removeClass( 'disabled' );
						$$.text( soInstallerAdmin.activateText )
						$$.data( 'status', 'activate' );
					}
					$( window ).trigger( 'resize' );
				}
			);
		}
	} );

	$( '.page-sections li:first-of-type a' ).trigger( 'click' );

	// Make sure product heights are all the same on a row by row basis.
	$( window ).on( 'resize', function() {
		var $products = $( '.siteorigin-installer-item:visible' ).css( 'height', 'auto' );
		var largestHeight = [];
		var column = 0;

		$( '.siteorigin-installer-item-body' ).css( 'height', 'auto' );

		$products.each( function( index ) {
			column = index / 3;
			// Turnicate column number - IE 11 friendly.
			column = column < 0 ? Math.ceil( column ) : Math.floor( column );
			$( this ).data( 'column', column )

			largestHeight[ column ] = Math.max( typeof largestHeight[ column ] == 'undefined' ? 0 : largestHeight[ column ], $( this ).height() );
		} );

		$products.each( function () {
			$( this ).find( '.siteorigin-installer-item-body' ).css( 'height', largestHeight[ $( this ).data( 'column' ) ] + 'px' );
		} );

	} ).trigger( 'resize' );
} );
