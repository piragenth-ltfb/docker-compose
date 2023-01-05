<?php
// Direct Load
require_once dirname( __FILE__ ) . '/hustle-campaignmonitor.php';
require_once dirname( __FILE__ ) . '/hustle-campaignmonitor-form-settings.php';
require_once dirname( __FILE__ ) . '/hustle-campaignmonitor-form-hooks.php';
require_once dirname( __FILE__ ) . '/hustle-campaignmonitor-api.php';
Hustle_Providers::get_instance()->register( 'Hustle_Campaignmonitor' );