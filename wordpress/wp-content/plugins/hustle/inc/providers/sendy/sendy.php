<?php
// Direct Load
require_once dirname( __FILE__ ) . '/hustle-sendy-api.php';
require_once dirname( __FILE__ ) . '/hustle-sendy.php';
require_once dirname( __FILE__ ) . '/hustle-sendy-form-settings.php';
require_once dirname( __FILE__ ) . '/hustle-sendy-form-hooks.php';
Hustle_Providers::get_instance()->register( 'Hustle_Sendy' );