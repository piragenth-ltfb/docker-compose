<?php
/**
 * Vanilla theme option under the advanced row.
 *
 * @package Hustle
 * @since 4.3.0
 */

$name     = 'use_vanilla';
$settings = $settings[ $name ];
?>

<div class="sui-form-field">

	<h4 class="sui-settings-label"><?php esc_html_e( 'Vanilla theme', 'hustle' ); ?></h4>

	<?php /* translators: module type in lowercase and singular. */ ?>
	<p class="sui-description" style="margin-bottom: 10px;"><?php printf( esc_html__( "Enable this option if you don't want to use the styling Hustle adds to your %s.", 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

	<div class="sui-tabs sui-side-tabs">
		<input
			type="radio"
			name="<?php echo esc_attr( $name ); ?>"
			value="1"
			id="hustle-<?php echo esc_attr( $name ); ?>--enable"
			class="sui-screen-reader-text hustle-tabs-option"
			data-attribute="<?php echo esc_attr( $name ); ?>"
			aria-hidden="true"
			tabindex="-1"
			<?php checked( $settings, '1' ); ?>
		/>

		<input
			type="radio"
			name="<?php echo esc_attr( $name ); ?>"
			value="0"
			id="hustle-<?php echo esc_attr( $name ); ?>--disable"
			class="sui-screen-reader-text hustle-tabs-option"
			data-attribute="<?php echo esc_attr( $name ); ?>"
			aria-hidden="true"
			tabindex="-1"
			<?php checked( $settings, '0' ); ?>
		/>

		<div role="tablist" class="sui-tabs-menu">

			<button
				role="tab"
				type="button"
				id="tab-<?php echo esc_attr( $name ); ?>--enable"
				class="sui-tab-item"
				data-label-for="hustle-<?php echo esc_attr( $name ); ?>--enable"
				aria-controls="tab-content-<?php echo esc_attr( $name ); ?>--enable"
			>
				<?php esc_html_e( 'Enable', 'hustle' ); ?>
			</button>

			<button
				role="tab"
				type="button"
				id="tab-<?php echo esc_attr( $name ); ?>--disable"
				class="sui-tab-item"
				data-label-for="hustle-<?php echo esc_attr( $name ); ?>--disable"
				aria-controls="tab-content-<?php echo esc_attr( $name ); ?>--disable"
			>
				<?php esc_html_e( 'Disable', 'hustle' ); ?>
			</button>

		</div>

		<div class="sui-tabs-content">

			<div
				role="tabpanel"
				tabindex="0"
				id="tab-content-<?php echo esc_attr( $name ); ?>--enable"
				class="sui-tab-content active"
				aria-labelledby="tab-<?php echo esc_attr( $name ); ?>--enable"
			>

				<?php
				$this->get_html_for_options(
					array(
						array(
							'type'     => 'inline_notice',
							'is_alert' => true,
							'icon'     => 'info',
							/* translators: module type in lowercase and singular. */
							'value'    => sprintf( esc_html__( "You have opted for no stylesheet to be enqueued. The %s will inherit styles from your theme's CSS.", 'hustle' ), esc_html( $smallcaps_singular ) ),
						),
					)
				);
				?>

			</div>

		</div>

	</div>

</div>