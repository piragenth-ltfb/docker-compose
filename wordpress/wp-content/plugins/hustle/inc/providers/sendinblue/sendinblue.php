<?php
// Direct Load
require_once dirname( __FILE__ ) . '/hustle-sendinblue.php';
require_once dirname( __FILE__ ) . '/hustle-sendinblue-form-settings.php';
require_once dirname( __FILE__ ) . '/hustle-sendinblue-form-hooks.php';
require_once dirname( __FILE__ ) . '/hustle-sendinblue-api.php';
if ( '1.0' === get_option( 'hustle_provider_sendinblue_version' ) ) {
	Hustle_SendinBlue::get_instance()->slient_update_api();
}
Hustle_Providers::get_instance()->register( 'Hustle_SendinBlue' );