/**
 * Trigger to the Customizer control to update controls.
 */

( function( api ) {

	//Support for Upgrade to Pro button
	api.sectionConstructor['upsell'] = api.Section.extend( {
		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );
