<?php
// Direct Load
require_once dirname( __FILE__ ) . '/hustle-constantcontact.php';
require_once dirname( __FILE__ ) . '/hustle-constantcontact-form-settings.php';
require_once dirname( __FILE__ ) . '/hustle-constantcontact-form-hooks.php';
Hustle_Providers::get_instance()->register( 'Hustle_ConstantContact' );