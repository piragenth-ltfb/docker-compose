<?php
// Direct Load
require_once dirname( __FILE__ ) . '/hustle-mad-mimi.php';
require_once dirname( __FILE__ ) . '/hustle-mad-mimi-form-settings.php';
require_once dirname( __FILE__ ) . '/hustle-mad-mimi-form-hooks.php';
Hustle_Providers::get_instance()->register( 'Hustle_Mad_Mimi' );