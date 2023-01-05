<?php
$info_default = self::$plugin_url . 'assets/images/layouts/layout-info-default';
$info_compact = self::$plugin_url . 'assets/images/layouts/layout-info-compact';
$info_stacked = self::$plugin_url . 'assets/images/layouts/layout-info-stacked';

$optin_default = self::$plugin_url . 'assets/images/layouts/layout-optin-default';
$optin_compact = self::$plugin_url . 'assets/images/layouts/layout-optin-compact';
$optin_focus   = self::$plugin_url . 'assets/images/layouts/layout-optin-focus';
$content_focus = self::$plugin_url . 'assets/images/layouts/layout-content-focus';
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">

		<?php if ( $is_optin ) { ?>

			<h3 id="hustle-row-layout" class="sui-settings-label"><?php esc_html_e( 'Choose Layout', 'hustle' ); ?></h3>

			<p class="sui-description"><?php printf( esc_html__( 'Select from one of the pre-built layouts for your %s as per your liking.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

		<?php } else { ?>

			<h3 id="hustle-row-layout" class="sui-settings-label"><?php esc_html_e( 'Layout', 'hustle' ); ?></h3>

			<p class="sui-description"><?php printf( esc_html__( 'Choose one of the pre-built layouts for your %s content.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

		<?php } ?>
	</div>

	<div class="sui-box-settings-col-2">

		<div role="radiogroup" class="sui-form-field" aria-labelledby="hustle-row-layout">

			<?php if ( $is_optin ) { ?>

				<label for="hustle-layout-one" class="sui-radio-image">

					<?php $this->hustle_image( $optin_default, 'png', '', true ); ?>

					<span class="sui-radio sui-radio-sm">
						<input type="radio"
							name="form_layout"
							value="one"
							id="hustle-layout-one"
							data-attribute="form_layout"
							<?php checked( $layout, 'one' ); ?>
						/>
						<span aria-hidden="true"></span>
						<span><?php esc_html_e( 'Default', 'hustle' ); ?></span>
					</span>

				</label>

				<label for="hustle-layout-two" class="sui-radio-image">

					<?php $this->hustle_image( $optin_compact, 'png', '', true ); ?>

					<span class="sui-radio sui-radio-sm">
						<input type="radio"
							name="form_layout"
							value="two"
							id="hustle-layout-two"
							data-attribute="form_layout"
							<?php checked( $layout, 'two' ); ?>
						/>
						<span aria-hidden="true"></span>
						<span><?php esc_html_e( 'Compact', 'hustle' ); ?></span>
					</span>

				</label>

				<label for="hustle-layout-three" class="sui-radio-image">

					<?php $this->hustle_image( $optin_focus, 'png', 'sui-graphic', true ); ?>

					<span class="sui-radio sui-radio-sm">
						<input type="radio"
							name="form_layout"
							value="three"
							id="hustle-layout-three"
							data-attribute="form_layout"
							<?php checked( $layout, 'three' ); ?>
						/>
						<span aria-hidden="true"></span>
						<span><?php esc_html_e( 'Opt-in Focus', 'hustle' ); ?></span>
					</span>

				</label>

				<label for="hustle-layout-four" class="sui-radio-image">

					<?php $this->hustle_image( $content_focus, 'png', 'sui-graphic', true ); ?>

					<span class="sui-radio sui-radio-sm">
						<input type="radio"
							name="form_layout"
							value="four"
							id="hustle-layout-four"
							data-attribute="form_layout"
							<?php checked( $layout, 'four' ); ?>
						/>
						<span aria-hidden="true"></span>
						<span><?php esc_html_e( 'Content Focus', 'hustle' ); ?></span>
					</span>

				</label>

			<?php } else { ?>

				<label for="hustle-layout-minimal" class="sui-radio-image">

					<?php $this->hustle_image( $info_default, 'png', 'sui-graphic', true ); ?>

					<span class="sui-radio sui-radio-sm">
						<input type="radio"
							name="style"
							value="minimal"
							id="hustle-layout-minimal"
							data-attribute="style"
							<?php checked( $layout, 'minimal' ); ?>
						/>
						<span aria-hidden="true"></span>
						<span><?php esc_html_e( 'Default', 'hustle' ); ?></span>
					</span>

				</label>

				<label for="hustle-layout-simple" class="sui-radio-image">

					<?php $this->hustle_image( $info_compact, 'png', 'sui-graphic', true ); ?>

					<span class="sui-radio sui-radio-sm">
						<input type="radio"
							name="style"
							value="simple"
							id="hustle-layout-simple"
							data-attribute="style"
							<?php checked( $layout, 'simple' ); ?>
						/>
						<span aria-hidden="true"></span>
						<span><?php esc_html_e( 'Compact', 'hustle' ); ?></span>
					</span>

				</label>

				<label for="hustle-layout-cabriolet" class="sui-radio-image">

					<?php $this->hustle_image( $info_stacked, 'png', 'sui-graphic', true ); ?>

					<span class="sui-radio sui-radio-sm">
						<input type="radio"
							name="style"
							value="cabriolet"
							id="hustle-layout-cabriolet"
							data-attribute="style"
							<?php checked( $layout, 'cabriolet' ); ?>
						/>
						<span aria-hidden="true"></span>
						<span><?php esc_html_e( 'Stacked', 'hustle' ); ?></span>
					</span>

				</label>

			<?php } ?>

		</div>

	</div>

</div>