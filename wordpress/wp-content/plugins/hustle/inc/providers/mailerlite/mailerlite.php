<?php
// Direct Load
require_once dirname( __FILE__ ) . '/hustle-mailerlite.php';
require_once dirname( __FILE__ ) . '/hustle-mailerlite-form-settings.php';
require_once dirname( __FILE__ ) . '/hustle-mailerlite-form-hooks.php';
Hustle_Providers::get_instance()->register( 'Hustle_MailerLite' );