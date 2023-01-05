<?php
// Direct Load
require_once dirname( __FILE__ ) . '/hustle-aweber.php';
require_once dirname( __FILE__ ) . '/hustle-aweber-form-settings.php';
require_once dirname( __FILE__ ) . '/hustle-aweber-form-hooks.php';
require_once dirname( __FILE__ ) . '/hustle-addon-aweber-exception.php';
require_once dirname( __FILE__ ) . '/hustle-addon-aweber-form-settings-exception.php';
require_once dirname( __FILE__ ) . '/lib/class-wp-aweber-api.php';
Hustle_Providers::get_instance()->register( 'Hustle_Aweber' );