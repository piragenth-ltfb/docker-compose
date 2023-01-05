<?php
/**
 * Markup for the CTA alignment options in both mobile and desktop settings.
 *
 * @since 4.3.0
 * @package Hustle
 */

$name     = 'cta_buttons_alignment' . ( $device ? '_' . $device : '' );
$settings = $settings[ $name ];

$alignment = array(
	'left'   => esc_html__( 'Left', 'hustle' ),
	'center' => esc_html__( 'Center', 'hustle' ),
	'right'  => esc_html__( 'Right', 'hustle' ),
	'full'   => esc_html__( 'Full', 'hustle' ),
);
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-2">

		<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php esc_html_e( 'Alignment', 'hustle' ); ?></h5>

		<p class="sui-description"><?php esc_html_e( 'Align your CTA button or make it full width to take up the available space using the last option.', 'hustle' ); ?></p>

		<div class="sui-tabs sui-side-tabs">

			<?php foreach ( $alignment as $key => $option_name ) { ?>

				<input
					type="radio"
					value="<?php echo esc_attr( $key ); ?>"
					id="hustle-<?php echo esc_attr( $name ); ?>--<?php echo esc_attr( $key ); ?>"
					class="sui-screen-reader-text hustle-tabs-option"
					data-attribute="<?php echo esc_attr( $name ); ?>"
					aria-hidden="true"
					tabindex="-1"
					<?php checked( $settings, $key ); ?>
				/>

			<?php } ?>

			<div role="tablist" class="sui-tabs-menu">

				<?php foreach ( $alignment as $key => $option_name ) { ?>

					<button
						role="tab"
						type="button"
						id="tab-<?php echo esc_attr( $name ); ?>--<?php echo esc_attr( $key ); ?>"
						class="sui-tab-item"
						data-label-for="hustle-<?php echo esc_attr( $name ); ?>--<?php echo esc_attr( $key ); ?>"
						aria-selected="false"
						tabindex="-1"
					>
						<span class="hui-tab-icon-position-<?php echo esc_attr( $key ); ?>" aria-hidden="true"></span>
						<span class="sui-screen-reader-text"><?php echo esc_html( $option_name ); ?></span>
					</button>

				<?php } ?>

			</div>

		</div>

	</div>

</div>