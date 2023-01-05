<?php
$horizontal = 'close_icon_alignment_x' . ( $device ? '_' . $device : '' );
$settings_x = $settings[ $horizontal ];

$vertical   = 'close_icon_alignment_y' . ( $device ? '_' . $device : '' );
$settings_y = $settings[ $vertical ];
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-2">

		<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php esc_html_e( 'Alignment', 'hustle' ); ?></h5>

		<p class="sui-description"><?php esc_html_e( 'Adjust the horizontal and vertical alignment of your close icon to place it as per your required location.', 'hustle' ); ?></p>

		<div class="hui-fields-row">

			<?php // COL: Horizontal. ?>
			<div class="hui-fields-col" data-field-size="auto">

				<label class="sui-label"><?php esc_html_e( 'Horizontal', 'hustle' ); ?></label>

				<div class="sui-tabs sui-side-tabs">

					<input
						type="radio"
						name="<?php echo esc_attr( $horizontal ); ?>"
						value="left"
						id="hustle-<?php echo esc_attr( $horizontal ); ?>--left"
						class="sui-screen-reader-text hustle-tabs-option"
						data-attribute="<?php echo esc_attr( $horizontal ); ?>"
						aria-hidden="true"
						tabindex="-1"
						<?php checked( $settings_x, 'left' ); ?>
					/>

					<input
						type="radio"
						name="<?php echo esc_attr( $horizontal ); ?>"
						value="center"
						id="hustle-<?php echo esc_attr( $horizontal ); ?>--center"
						class="sui-screen-reader-text hustle-tabs-option"
						data-attribute="<?php echo esc_attr( $horizontal ); ?>"
						aria-hidden="true"
						tabindex="-1"
						<?php checked( $settings_x, 'center' ); ?>
					/>

					<input
						type="radio"
						name="<?php echo esc_attr( $horizontal ); ?>"
						value="right"
						id="hustle-<?php echo esc_attr( $horizontal ); ?>--right"
						class="sui-screen-reader-text hustle-tabs-option"
						data-attribute="<?php echo esc_attr( $horizontal ); ?>"
						aria-hidden="true"
						tabindex="-1"
						<?php checked( $settings_x, 'right' ); ?>
					/>

					<div role="tablist" class="sui-tabs-menu">

						<button
							role="tab"
							type="button"
							id="tab-<?php echo esc_attr( $key ); ?>-alignment-left"
							class="sui-tab-item"
							data-label-for="hustle-<?php echo esc_attr( $horizontal ); ?>--left"
							aria-selected="true"
						>
							<span class="hui-tab-icon-position-left" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Left', 'hustle' ); ?></span>
						</button>

						<button
							role="tab"
							type="button"
							id="tab-<?php echo esc_attr( $key ); ?>-alignment-center"
							class="sui-tab-item"
							data-label-for="hustle-<?php echo esc_attr( $horizontal ); ?>--center"
							aria-selected="false"
							tabindex="-1"
						>
							<span class="hui-tab-icon-position-center" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Center', 'hustle' ); ?></span>
						</button>

						<button
							role="tab"
							type="button"
							id="tab-<?php echo esc_attr( $key ); ?>-alignment-right"
							class="sui-tab-item"
							data-label-for="hustle-<?php echo esc_attr( $horizontal ); ?>--right"
							aria-selected="false"
							tabindex="-1"
						>
							<span class="hui-tab-icon-position-right" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Right', 'hustle' ); ?></span>
						</button>

					</div>

				</div>

			</div>

			<?php // COL: Vertical. ?>
			<div class="hui-fields-col">

				<label class="sui-label"><?php esc_html_e( 'Vertical', 'hustle' ); ?></label>

				<div class="sui-tabs sui-side-tabs">

					<input
						type="radio"
						name="<?php echo esc_attr( $vertical ); ?>"
						value="top"
						id="hustle-<?php echo esc_attr( $vertical ); ?>--top"
						class="sui-screen-reader-text hustle-tabs-option"
						data-attribute="<?php echo esc_attr( $vertical ); ?>"
						aria-hidden="true"
						tabindex="-1"
						<?php checked( $settings_y, 'top' ); ?>
					/>

					<input
						type="radio"
						name="<?php echo esc_attr( $vertical ); ?>"
						value="center"
						id="hustle-<?php echo esc_attr( $vertical ); ?>--center"
						class="sui-screen-reader-text hustle-tabs-option"
						data-attribute="<?php echo esc_attr( $vertical ); ?>"
						aria-hidden="true"
						tabindex="-1"
						<?php checked( $settings_y, 'center' ); ?>
					/>

					<input
						type="radio"
						name="<?php echo esc_attr( $vertical ); ?>"
						value="bottom"
						id="hustle-<?php echo esc_attr( $vertical ); ?>--bottom"
						class="sui-screen-reader-text hustle-tabs-option"
						data-attribute="<?php echo esc_attr( $vertical ); ?>"
						aria-hidden="true"
						tabindex="-1"
						<?php checked( $settings_y, 'bottom' ); ?>
					/>

					<div role="tablist" class="sui-tabs-menu">

						<button
							role="tab"
							type="button"
							id="tab-<?php echo esc_attr( $key ); ?>-alignment-top"
							class="sui-tab-item active"
							data-label-for="hustle-<?php echo esc_attr( $vertical ); ?>--top"
							aria-selected="true"
						>
							<span class="hui-tab-icon-position-top" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Top', 'hustle' ); ?></span>
						</button>

						<button
							role="tab"
							type="button"
							id="tab-<?php echo esc_attr( $key ); ?>-alignment-middle"
							class="sui-tab-item"
							data-label-for="hustle-<?php echo esc_attr( $vertical ); ?>--middle"
							aria-selected="false"
							tabindex="-1"
						>
							<span class="hui-tab-icon-position-middle" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Center', 'hustle' ); ?></span>
						</button>

						<button
							role="tab"
							type="button"
							id="tab-<?php echo esc_attr( $key ); ?>-alignment-bottom"
							class="sui-tab-item"
							data-label-for="hustle-<?php echo esc_attr( $vertical ); ?>--bottom"
							aria-selected="false"
							tabindex="-1"
						>
							<span class="hui-tab-icon-position-bottom" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Bottom', 'hustle' ); ?></span>
						</button>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>