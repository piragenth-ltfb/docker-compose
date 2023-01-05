<?php
/**
 * Mailster direct load.
 *
 * @package hustle
 *
 * @since 4.4.0
 */

/**
 * Loads the classes required for mailster and registers the integrations in Hustle.
 */
require_once dirname( __FILE__ ) . '/class-hustle-mailster.php';
require_once dirname( __FILE__ ) . '/class-hustle-mailster-form-settings.php';
require_once dirname( __FILE__ ) . '/class-hustle-mailster-form-hooks.php';
Hustle_Providers::get_instance()->register( 'Hustle_Mailster' );