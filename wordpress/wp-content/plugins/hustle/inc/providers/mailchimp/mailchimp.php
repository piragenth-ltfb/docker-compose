<?php
// Direct Load
require_once dirname( __FILE__ ) . '/hustle-mailchimp.php';
require_once dirname( __FILE__ ) . '/hustle-mailchimp-form-settings.php';
require_once dirname( __FILE__ ) . '/hustle-mailchimp-form-hooks.php';
Hustle_Providers::get_instance()->register( 'Hustle_Mailchimp' );