<?php
/**
 * Title section.
 *
 * @package Hustle
 * @since 4.0.0
 */

$inline_below = self::$plugin_url . 'assets/images/embed-position-below';
$inline_above = self::$plugin_url . 'assets/images/embed-position-above';
$inline_both  = self::$plugin_url . 'assets/images/embed-position-both';

?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">

		<span class="sui-settings-label"><?php esc_html_e( 'Manage Display Options', 'hustle' ); ?></span>

		<span class="sui-description"><?php printf( esc_html__( 'Enable/Disable the various options available to display your embed on the front-end.', 'hustle' ), 'aaa' ); ?></span>

	</div>

	<div class="sui-box-settings-col-2">

		<div class="sui-form-field">

			<label for="hustle-module-inline" class="sui-toggle hustle-toggle-with-container" data-toggle-on="inline-enabled">
				<input
					type="checkbox"
					name="inline_enabled"
					data-attribute="inline_enabled"
					id="hustle-module-inline"
					aria-labelledby="hustle-module-inline-label"
					<?php checked( $settings['inline_enabled'], '1' ); ?>
				/>

				<span class="sui-toggle-slider" aria-hidden="true"></span>

				<span id="hustle-module-inline-label" class="sui-toggle-label"><?php esc_html_e( 'Inline Content', 'hustle' ); ?></span>

				<span class="sui-description" style="display: none;" data-toggle-content="inline-enabled"><?php /* translators: strong HTML-tag. */ printf( esc_html__( 'Add your Embed above the page content, below it, or at both locations on any page that uses the %1$s"the_content()"%2$s method (e.g. Posts, Pages, etc.) for displaying content.', 'hustle' ), '<strong>', '</strong>' ); ?></span>
			</label>

			<div id="hustle-inline-toggle-wrapper" class="sui-toggle-content sui-border-frame" style="display: none;" data-toggle-content="inline-enabled">

				<span class="sui-settings-label"><?php esc_html_e( 'Position', 'hustle' ); ?></span>
				<span class="sui-description" style="margin-bottom: 10px;"><?php esc_html_e( 'Choose the position for the inline embed with respect to the content.', 'hustle' ); ?></span>

				<label for="hustle-inline-below" class="sui-radio-image">

					<?php $this->hustle_image( $inline_below, 'png', '', true ); ?>

					<span class="sui-radio sui-radio-sm">
						<input
							type="radio"
							name="inline_position"
							value="below"
							id="hustle-inline-below"
							data-attribute="inline_position"
							<?php checked( $settings['inline_position'], 'below' ); ?>
						/>
						<span aria-hidden="true"></span>
						<span><?php esc_html_e( 'Below', 'hustle' ); ?></span>
					</span>

				</label>

				<label for="hustle-inline-above" class="sui-radio-image">

					<?php $this->hustle_image( $inline_above, 'png', '', true ); ?>

					<span class="sui-radio sui-radio-sm">
						<input
							type="radio"
							name="inline_position"
							value="above"
							id="hustle-inline-above"
							data-attribute="inline_position"
							<?php checked( $settings['inline_position'], 'above' ); ?>
						/>
						<span aria-hidden="true"></span>
						<span><?php esc_html_e( 'Above', 'hustle' ); ?></span>
					</span>

				</label>

				<label for="hustle-inline-both" class="sui-radio-image">

					<?php $this->hustle_image( $inline_both, 'png', 'sui-graphic', true ); ?>

					<span class="sui-radio sui-radio-sm">
						<input type="radio"
							name="inline_position"
							value="both"
							id="hustle-inline-both"
							data-attribute="inline_position"
							<?php checked( $settings['inline_position'], 'both' ); ?> />
						<span aria-hidden="true"></span>
						<span><?php esc_html_e( 'Both', 'hustle' ); ?></span>
					</span>

				</label>

			</div>

		</div>

		<div class="sui-form-field">

			<label for="hustle-module-widget" class="sui-toggle hustle-toggle-with-container" data-toggle-on="widget-enabled">

				<input
					type="checkbox"
					name="widget_enabled"
					data-attribute="widget_enabled"
					id="hustle-module-widget"
					aria-labelledby="hustle-module-widget-label"
					<?php checked( $settings['widget_enabled'], '1' ); ?>
				/>

				<span class="sui-toggle-slider" aria-hidden="true"></span>

				<span id="hustle-module-widget-label" class="sui-toggle-label"><?php esc_html_e( 'Widget', 'hustle' ); ?></span>

				<span class="sui-description" style="display: none;" data-toggle-content="widget-enabled">
					<?php
					printf(
						/* translators: 'Appearance' string linked to the widgets lists */
						esc_html__( 'Enabling this will add this embed to widget named "Hustle" under the Available Widgets list as a possible option. You can go to %s and configure this widget to show your embed in the sidebars.', 'hustle' ),
						sprintf(
							'<strong>%1$s > %2$s</strong>',
							esc_html__( 'Appearance', 'hustle' ),
							sprintf(
								'<a href="%1$s" target="_blank">%2$s</a>',
								esc_url( admin_url( 'widgets.php' ) ),
								esc_html__( 'Widgets', 'hustle' )
							)
						)
					);
					?>
				</span>

			</label>

		</div>

		<div class="sui-form-field">

			<label for="hustle-module-shortcode" class="sui-toggle hustle-toggle-with-container" data-toggle-on="shortcode-enabled">

				<input
					type="checkbox"
					name="shortcode_enabled"
					id="hustle-module-shortcode"
					data-attribute="shortcode_enabled"
					aria-labelledby="hustle-module-shortcode-label"
					<?php checked( $settings['shortcode_enabled'], '1' ); ?>
				/>

				<span class="sui-toggle-slider" aria-hidden="true"></span>

				<span id="hustle-module-shortcode-label" class="sui-toggle-label"><?php esc_html_e( 'Shortcode', 'hustle' ); ?></span>

				<span class="sui-description" data-toggle-content="shortcode-enabled"><?php esc_html_e( 'Use shortcode to display your embed anywhere you want to. Just copy the shortcode and paste it wherever you want to render your embed.', 'hustle' ); ?></span>

			</label>

			<div id="hustle-shortcode-toggle-wrapper" class="sui-toggle-content sui-border-frame" data-toggle-content="shortcode-enabled">

				<span class="sui-description"><?php esc_html_e( 'Shortcode to render your embed', 'hustle' ); ?></span>

				<div class="sui-with-button sui-with-button-inside">
					<input type="text"
						class="sui-form-control"
						value='[wd_hustle id="<?php echo esc_attr( $shortcode_id ); ?>" type="embedded"/]'
						readonly="readonly">
					<button class="sui-button-icon hustle-copy-shortcode-button">
						<span aria-hidden="true" class="sui-icon-copy"></span>
						<span class="sui-screen-reader-text"><?php esc_html_e( 'Copy shortcode', 'hustle' ); ?></span>
					</button>

				</div>

			</div>

		</div>

	</div>

</div>