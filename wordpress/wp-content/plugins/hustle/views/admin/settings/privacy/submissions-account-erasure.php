<?php
/**
 * Account erasure row under the "privacy" tab.
 *
 * @package Hustle
 * @since 4.0.3
 */

?>
<fieldset class="sui-form-field">

	<label class="sui-settings-label"><?php esc_html_e( 'Account Erasure Requests', 'hustle' ); ?></label>

	<span class="sui-description" style="margin-bottom: 10px;">
		<?php
		printf(
			/* translators: 1. opening 'a' tag to the 'remove personal data' tool, 2. closing 'a' tag */
			esc_html__( 'When handling an %1$saccount erasure request%2$s that contains an email associated with a submission, what do you want to do?', 'hustle' ),
			'<a href="' . esc_url( admin_url( 'tools.php?page=remove_personal_data' ) ) . '">',
			'</a>'
		);
		?>
	</span>

	<?php
	$this->render(
		'admin/global/sui-components/sui-tabs',
		array(
			'name'        => 'retain_sub_on_erasure',
			'radio'       => true,
			'saved_value' => $settings['retain_sub_on_erasure'],
			'sidetabs'    => true,
			'content'     => false,
			'options'     => array(
				'1' => array(
					'value' => '1',
					'label' => esc_html__( 'Retain Submission', 'hustle' ),
				),
				'0' => array(
					'value' => '0',
					'label' => esc_html__( 'Remove Submission', 'hustle' ),
				),
			),
		)
	);
	?>

</fieldset>