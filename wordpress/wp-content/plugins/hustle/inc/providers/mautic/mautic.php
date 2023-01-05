<?php
// Direct Load
require_once dirname( __FILE__ ) . '/hustle-mautic.php';
require_once dirname( __FILE__ ) . '/hustle-mautic-form-settings.php';
require_once dirname( __FILE__ ) . '/hustle-mautic-form-hooks.php';
Hustle_Providers::get_instance()->register( 'Hustle_Mautic' );