<?php
$name     = 'feature_image_position' . ( $device ? '_' . $device : '' );
$settings = $settings[ $name ];

$alignment = array(
	'left'  => array(
		'show' => true,
		'name' => esc_html__( 'Left', 'hustle' ),
	),
	'right' => array(
		'show' => true,
		'name' => esc_html__( 'Right', 'hustle' ),
	),
	'above' => array(
		'show' => $is_optin ? true : false,
		'name' => esc_html__( 'Above', 'hustle' ),
	),
	'below' => array(
		'show' => $is_optin ? true : false,
		'name' => esc_html__( 'Below', 'hustle' ),
	),
);
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-2">

		<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php esc_html_e( 'Alignment', 'hustle' ); ?></h5>

		<p class="sui-description"><?php printf( esc_html__( 'Choose how do you want to align your feature image inside your %s.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

		<div class="sui-tabs sui-side-tabs">

			<?php foreach ( $alignment as $key => $align ) { ?>

				<?php if ( true === $align['show'] ) { ?>

					<input
						type="radio"
						name="<?php echo esc_attr( $name ); ?>"
						value="<?php echo esc_attr( $key ); ?>"
						id="hustle-<?php echo esc_attr( $name ); ?>--<?php echo esc_attr( $key ); ?>"
						class="sui-screen-reader-text hustle-tabs-option"
						data-attribute="<?php echo esc_attr( $name ); ?>"
						aria-hidden="true"
						tabindex="-1"
						<?php checked( $settings, $key ); ?>
					/>

				<?php } ?>

			<?php } ?>

			<div role="tablist" class="sui-tabs-menu">

				<?php foreach ( $alignment as $key => $align ) { ?>

					<?php if ( true === $align['show'] ) { ?>

						<button
							type="button"
							role="tab"
							id="tab-<?php echo esc_attr( $name ); ?>-alignment-<?php echo esc_attr( $key ); ?>"
							class="sui-tab-item"
							data-label-for="hustle-<?php echo esc_attr( $name ); ?>--<?php echo esc_attr( $key ); ?>"
							aria-selected="false"
							tabindex="-1"
						><?php echo esc_html( $align['name'] ); ?></button>

					<?php } ?>

				<?php } ?>

			</div>

		</div>

	</div>

</div>