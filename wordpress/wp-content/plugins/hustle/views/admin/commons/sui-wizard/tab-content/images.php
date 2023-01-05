<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">

		<span class="sui-settings-label"><?php esc_html_e( 'Images', 'hustle' ); ?></span>

		<?php /* translators: module type in smallcaps and singular. */ ?>
		<span class="sui-description"><?php printf( esc_html__( 'Add a feature image and a background image to your %s.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>

	</div>

	<div class="sui-box-settings-col-2">

		<?php
		// FIELD: Feature image.
		$this->render(
			'admin/commons/sui-wizard/tab-content/image-uploader',
			array(
				'settings'          => $settings,
				'attribute'         => 'feature_image',
				'field_title'       => __( 'Feature Image (optional)', 'hustle' ),
				'field_description' => __( 'We recommend adding a featured image that grabs visitors attention and helps to explain your offering in a better way.', 'hustle' ),
			)
		);

		// FIELD: Background image.
		$this->render(
			'admin/commons/sui-wizard/tab-content/image-uploader',
			array(
				'settings'          => $settings,
				'attribute'         => 'background_image',
				'field_title'       => __( 'Background Image (optional)', 'hustle' ),
				/* translators: module type in smallcaps and singular. */
				'field_description' => sprintf( __( 'Choose whether you want to add a background image to your %s or not.', 'hustle' ), $smallcaps_singular ),
			)
		);
		?>

	</div>

</div>