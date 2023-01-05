<?php
/**
 * OPTION: Visibility on mobile.
 *
 * @package Hustle
 */

$visibility = '0' === $settings['feature_image_hide_on_mobile']
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-2">

		<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php esc_html_e( 'Visibility', 'hustle' ); ?></h5>

		<p class="sui-description"><?php esc_html_e( 'Make the feature image visible or hidden on mobile.', 'hustle' ); ?></p>

		<div class="sui-side-tabs sui-tabs">
			<input type="radio"
				name="feature_image_hide_on_mobile"
				value="0"
				id="hustle-feature-image-visible"
				class="sui-screen-reader-text hustle-tabs-option"
				data-attribute="feature_image_hide_on_mobile"
				aria-hidden="true"
				tabindex="-1"
				<?php checked( $visibility ); ?>
			/>

			<input type="radio"
				name="feature_image_hide_on_mobile"
				value="1"
				id="hustle-feature-image-hidden"
				class="sui-screen-reader-text hustle-tabs-option"
				data-attribute="feature_image_hide_on_mobile"
				aria-hidden="true"
				tabindex="-1"
				<?php checked( ! $visibility ); ?>
			/>

			<div role="tablist" class="sui-tabs-menu">

				<button
					type="button"
					role="tab"
					class="sui-tab-item"
					aria-selected="false"
					tabindex="-1"
					data-label-for="hustle-feature-image-visible"
				/><?php esc_attr_e( 'Visible', 'hustle' ); ?></button>

				<button
					type="button"
					role="tab"
					class="sui-tab-item"
					aria-selected="false"
					tabindex="-1"
					data-label-for="hustle-feature-image-hidden"
				/><?php esc_attr_e( 'Hidden', 'hustle' ); ?></button>

			</div>

		</div>

	</div>

</div>