<?php
// Direct Load
require_once dirname( __FILE__ ) . '/hustle-get-response.php';
require_once dirname( __FILE__ ) . '/hustle-get-response-form-settings.php';
require_once dirname( __FILE__ ) . '/hustle-get-response-form-hooks.php';
Hustle_Providers::get_instance()->register( 'Hustle_Get_Response' );