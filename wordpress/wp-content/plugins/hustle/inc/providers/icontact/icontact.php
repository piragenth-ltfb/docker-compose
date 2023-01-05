<?php
// Direct Load
require_once dirname( __FILE__ ) . '/hustle-icontact.php';
require_once dirname( __FILE__ ) . '/hustle-icontact-form-settings.php';
require_once dirname( __FILE__ ) . '/hustle-icontact-form-hooks.php';
Hustle_Providers::get_instance()->register( 'Hustle_Icontact' );