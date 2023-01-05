<?php
// Direct Load
require_once dirname( __FILE__ ) . '/hustle-infusion-soft.php';
require_once dirname( __FILE__ ) . '/hustle-infusion-soft-form-settings.php';
require_once dirname( __FILE__ ) . '/hustle-infusion-soft-form-hooks.php';
Hustle_Providers::get_instance()->register( 'Hustle_Infusion_Soft' );