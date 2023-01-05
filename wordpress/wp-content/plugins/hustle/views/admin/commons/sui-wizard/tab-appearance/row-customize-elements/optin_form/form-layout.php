<?php
$name     = 'optin_form_layout' . ( $device ? '_' . $device : '' );
$settings = $settings[ $name ];
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-2">

		<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php echo esc_html_e( 'Form Layout', 'hustle' ); ?></h5>

		<p class="sui-description"><?php printf( esc_html__( 'Choose whether your %s form should be inline or stacked.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

		<div class="sui-tabs sui-side-tabs">

			<input
				type="radio"
				value="inline"
				id="hustle-<?php echo esc_attr( $name ); ?>--inline"
				class="sui-screen-reader-text hustle-tabs-option"
				data-attribute="<?php echo esc_attr( $name ); ?>"
				aria-hidden="true"
				tabindex="-1"
				<?php checked( $settings, 'inline' ); ?>
			/>

			<input
				type="radio"
				value="stacked"
				id="hustle-<?php echo esc_attr( $name ); ?>--stacked"
				class="sui-screen-reader-text hustle-tabs-option"
				data-attribute="<?php echo esc_attr( $name ); ?>"
				aria-hidden="true"
				tabindex="-1"
				<?php checked( $settings, 'stacked' ); ?>
			/>

			<div role="tablist" class="sui-tabs-menu">

				<button
					role="tab"
					type="button"
					id="tab-<?php echo esc_attr( $name ); ?>--inline"
					class="sui-tab-item active"
					data-label-for="hustle-<?php echo esc_attr( $name ); ?>--inline"
					aria-selected="true"
				>
					<span class="hui-tab-icon-position-inline" aria-hidden="true"></span>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Inline', 'hustle' ); ?></span>
				</button>

				<button
					role="tab"
					type="button"
					id="tab-<?php echo esc_attr( $name ); ?>--stacked"
					class="sui-tab-item"
					data-label-for="hustle-<?php echo esc_attr( $name ); ?>--stacked"
					aria-selected="false"
					tabindex="-1"
				>
					<span class="hui-tab-icon-position-stacked" aria-hidden="true"></span>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Stacked', 'hustle' ); ?></span>
				</button>

			</div>

		</div>

	</div>

</div>