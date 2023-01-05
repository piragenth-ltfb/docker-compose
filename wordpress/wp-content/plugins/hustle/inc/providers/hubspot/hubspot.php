<?php
// Direct Load
require_once dirname( __FILE__ ) . '/hustle-hubspot.php';
require_once dirname( __FILE__ ) . '/hustle-hubspot-form-settings.php';
require_once dirname( __FILE__ ) . '/hustle-hubspot-form-hooks.php';
Hustle_Providers::get_instance()->register( 'Hustle_HubSpot' );