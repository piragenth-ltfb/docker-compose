<?php
// Direct Load
require_once dirname( __FILE__ ) . '/hustle-e-newsletter.php';
require_once dirname( __FILE__ ) . '/hustle-e-newsletter-form-settings.php';
require_once dirname( __FILE__ ) . '/hustle-e-newsletter-form-hooks.php';
Hustle_Providers::get_instance()->register( 'Hustle_E_Newsletter' );