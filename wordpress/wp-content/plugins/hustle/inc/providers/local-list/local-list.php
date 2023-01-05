<?php
// Direct Load
require_once dirname( __FILE__ ) . '/hustle-local-list.php';
require_once dirname( __FILE__ ) . '/hustle-local-list-form-settings.php';
require_once dirname( __FILE__ ) . '/hustle-local-list-form-hooks.php';
Hustle_Providers::get_instance()->register( 'Hustle_Local_List' );