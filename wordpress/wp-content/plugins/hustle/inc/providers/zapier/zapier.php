<?php
// Direct Load
require_once dirname( __FILE__ ) . '/hustle-zapier-api.php';
require_once dirname( __FILE__ ) . '/hustle-zapier.php';
require_once dirname( __FILE__ ) . '/hustle-zapier-form-settings.php';
require_once dirname( __FILE__ ) . '/hustle-zapier-form-hooks.php';
Hustle_Providers::get_instance()->register( 'Hustle_Zapier' );