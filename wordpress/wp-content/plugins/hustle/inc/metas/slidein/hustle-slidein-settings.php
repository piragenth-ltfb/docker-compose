<?php

class Hustle_Slidein_Settings extends Hustle_Meta_Base_Settings {

	public function get_defaults() {
		$base = parent::get_defaults();

		// Specific for slidein.
		$settings = array_merge(
			$base,
			array(
				'display_position' => 's',
				'auto_hide'        => '0',
				'auto_hide_unit'   => 'seconds',
				'auto_hide_time'   => '5',
			)
		);

		return $settings;
	}
}