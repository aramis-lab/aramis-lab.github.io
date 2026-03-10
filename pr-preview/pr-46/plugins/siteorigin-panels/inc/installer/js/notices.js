jQuery( function( $ ) {
	$( '#siteorigin-installer-notice .siteorigin-notice-dismiss' ).on( 'click', function( e ) {
		e.preventDefault();
		var $$ = $( this ).trigger( 'blur' );
		$.get( $$.attr('href') );

		$( '#siteorigin-installer-notice' ).slideUp( function(){
			$( this ).remove();
		} )
	} );
} );
