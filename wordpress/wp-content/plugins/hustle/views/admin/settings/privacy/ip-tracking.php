<?php
/**
 * IP tracking row under the "privacy" tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

$ip_tracking = 'on' === $settings['ip_tracking']; ?>

<fieldset class="sui-form-field">

	<label class="sui-settings-label"><?php esc_html_e( 'IP Tracking', 'hustle' ); ?></label>

	<span class="sui-description" style="margin-bottom: 10px;"><?php esc_html_e( 'Choose whether you want to track the IP address of your visitors while collecting tracking data and submissions.', 'hustle' ); ?></span>

	<?php
	$this->render(
		'admin/global/sui-components/sui-tabs',
		array(
			'name'        => 'ip_tracking',
			'radio'       => true,
			'saved_value' => $settings['ip_tracking'],
			'sidetabs'    => true,
			'content'     => false,
			'options'     => array(
				'on'  => array(
					'value' => 'on',
					'label' => esc_html__( 'Enable', 'hustle' ),
				),
				'off' => array(
					'value' => 'off',
					'label' => esc_html__( 'Disable', 'hustle' ),
				),
			),
		)
	);
	?>

</fieldset>