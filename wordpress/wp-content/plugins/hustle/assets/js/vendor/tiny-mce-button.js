( function( $ ) {
    tinymce.PluginManager.add( 'hustle', function( editor, url ) {

		if ( -1 === $.inArray( editor.id, hustleData.available_editors ) ) {
			return;
		}

		let options = '';
		$.each( hustleData.fields, function( name, label ) {
			options += `<li><button value="{${name}}">${label}</button></li>`;
		});

		let html = `<div class="sui-dropdown sui-dropdown-right">
		<button class="sui-button-icon sui-dropdown-anchor sui-tooltip sui-tooltip-top-left" data-tooltip="${hustleData.button_title}">
			<i class="sui-icon-layout" aria-hidden="true"></i>
			<span class="sui-screen-reader-text">Opt-in options</span>
		</button>
		<ul class="hustle-button-options hustle-fields-placeholders-options" data-mce-id="${editor.id}">${options}</ul></div>`.trim();

		// Add the custom button to the editor.
        editor.addButton( 'hustlefields', {
            icon: false,
			classes: editor.id + '-button',
            image: url.split( '/' ).slice( 0, -2 ).join( '/' ) + '/img/hustle.png', //16x16 recommended
			onPostRender: function() {
				$( '.mce-' + editor.id + '-button' ).html( html );
			}
        });

    });

    $( 'body' ).on( 'click', '.hustle-button-options', function( e ) {
		var $this = $( e.target ),
			$mce  = $( this ).data('mce-id');
		tinymce.get( $mce ).insertContent( $this.val() );
	});

} (jQuery) );
