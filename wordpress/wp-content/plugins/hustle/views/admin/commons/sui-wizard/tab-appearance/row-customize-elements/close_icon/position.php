<?php
$name     = 'close_icon_position' . ( $device ? '_' . $device : '' );
$settings = $settings[ $name ];

$position = array(
	'outside' => esc_html__( 'Outside', 'hustle' ),
	'inside'  => esc_html__( 'Inside', 'hustle' ),
	'hidden'  => esc_html__( 'Hidden', 'hustle' ),
);
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-2">

		<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php esc_html_e( 'Position', 'wpmudev-popup' ); ?></h5>

		<p class="sui-description"><?php printf( esc_html__( 'Choose whether you want to place the close icon outside or inside of your %1$s container. You can also hide it from your %1$s if you wish so.', 'wpmudev-popup' ), esc_html( $smallcaps_singular ) ); ?></p>

		<div class="sui-tabs sui-side-tabs">

			<?php foreach ( $position as $key => $option ) { ?>

				<input
					type="radio"
					name="<?php echo esc_attr( $name ); ?>"
					value="<?php echo esc_attr( $option ); ?>"
					id="hustle-<?php echo esc_attr( $name ); ?>--<?php echo esc_attr( $key ); ?>"
					class="sui-screen-reader-text hustle-tabs-option"
					data-attributes="<?php echo esc_attr( $name ); ?>"
					aria-hidden="true"
					tabindex="-1"
					<?php checked( $settings, $key ); ?>
				/>

			<?php } ?>

			<div role="tablist" class="sui-tabs-menu">

				<?php foreach ( $position as $key => $option ) { ?>

					<button
						role="tab"
						type="button"
						id="tab-<?php echo esc_attr( $name ); ?>--<?php echo esc_attr( $key ); ?>"
						class="sui-tab-item"
						data-label-for="hustle-<?php echo esc_attr( $name ); ?>--<?php echo esc_attr( $key ); ?>"
						aria-selected="false"
						tabindex="-1"
					><?php echo esc_html( $option ); ?></button>

				<?php } ?>

			</div>

		</div>

	</div>

</div>