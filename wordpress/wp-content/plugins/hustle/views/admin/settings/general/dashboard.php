<?php
/**
 * Dashboard section under the "general" tab.
 *
 * @package Hustle
 * @since 4.0.4
 */

$types = array(
	'popup'          => array(
		'lowercase'      => Opt_In_Utils::get_module_type_display_name( 'popup', true ),
		'capitalize'     => Opt_In_Utils::get_module_type_display_name( 'popup', true, true ),
		'setting_prefix' => 'popup',
	),
	'slidein'        => array(
		'lowercase'      => Opt_In_Utils::get_module_type_display_name( 'slidein', true ),
		'capitalize'     => Opt_In_Utils::get_module_type_display_name( 'slidein', true, true ),
		'setting_prefix' => 'slidein',
	),
	'embedded'       => array(
		'lowercase'      => Opt_In_Utils::get_module_type_display_name( 'embedded', true ),
		'capitalize'     => Opt_In_Utils::get_module_type_display_name( 'embedded', true, true ),
		'setting_prefix' => 'embedded',
	),
	'social_sharing' => array(
		'lowercase'      => Opt_In_Utils::get_module_type_display_name( 'social_sharing', true ),
		'capitalize'     => Opt_In_Utils::get_module_type_display_name( 'social_sharing', true, true ),
		'setting_prefix' => 'social_sharing',
	),
);
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Dashboard', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Customize the Hustle dashboard as per your liking.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<label id="" class="sui-settings-label"><?php esc_html_e( 'Modules Listing', 'hustle' ); ?></label>
		<span id="" class="sui-description"><?php esc_html_e( 'Choose the number of modules and the preferred status you want to see on your Hustle dashboard for each module type.', 'hustle' ); ?></span>

		<div class="sui-tabs sui-tabs-overflow" style="margin-top: 10px;">

			<div tabindex="-1" class="sui-tabs-navigation" aria-hidden="true">
				<button type="button" class="sui-button-icon sui-tabs-navigation--left">
					<span class="sui-icon-chevron-left"></span>
				</button>
				<button type="button" class="sui-button-icon sui-tabs-navigation--right">
					<span class="sui-icon-chevron-right"></span>
				</button>
			</div>

			<div role="tablist" class="sui-tabs-menu">

				<?php foreach ( $types as $module_type => $data ) : ?>

					<button
						type="button"
						role="tab"
						id="hustle-<?php echo esc_html( $module_type ); ?>-modules-tab"
						class="sui-tab-item<?php echo ( 'popup' === $module_type ) ? ' active' : ''; ?>"
						aria-controls="hustle-<?php echo esc_html( $module_type ); ?>-modules-content"
						aria-selected="<?php echo ( 'popup' === $module_type ) ? 'true' : 'false'; ?>"
						<?php echo ( 'popup' === $module_type ) ? '' : 'tabindex="-1"'; ?>
					>
						<?php echo esc_html( $data['capitalize'] ); ?>
					</button>

				<?php endforeach; ?>

			</div>

			<div class="sui-tabs-content">

				<?php
				foreach ( $types as $module_type => $data ) :
					$number_label       = empty( $data['number_label'] ) ? $data['capitalize'] : $data['number_label'];
					$number_description = empty( $data['number_description'] ) ? __( 'recent ', 'hustle' ) . $data['lowercase'] : $data['number_description'];
					?>

					<div
						role="tabpanel"
						tabindex="0"
						id="hustle-<?php echo esc_html( $module_type ); ?>-modules-content"
						class="sui-tab-content<?php echo ( 'popup' === $module_type ) ? ' active' : ''; ?>"
						aria-labelledby="hustle-<?php echo esc_html( $module_type ); ?>-modules-tab"
						<?php echo ( 'popup' === $module_type ) ? '' : 'hidden'; ?>
					>

						<div class="sui-form-field">

							<label
								for="hustle-<?php echo esc_html( $module_type ); ?>-number"
								id="hustle-<?php echo esc_html( $module_type ); ?>-number-label"
								class="sui-settings-label"
							>
								<?php /* translators: module type in plural */ ?>
								<?php printf( esc_html__( 'Number of %s', 'hustle' ), esc_html( $number_label ) ); ?>
							</label>

							<span id="hustle-<?php echo esc_html( $module_type ); ?>-number-description" class="sui-description">
								<?php /* translators: module type in plural */ ?>
								<?php printf( esc_html__( 'Choose the number of %s to be shown on your dashboard.', 'hustle' ), esc_html( $number_description ) ); ?>
							</span>

							<input
								type="number"
								min="1"
								value="<?php echo intval( $settings[ $data['setting_prefix'] . '_on_dashboard' ] ); ?>"
								name="<?php echo esc_attr( $data['setting_prefix'] . '_on_dashboard' ); ?>"
								id="hustle-<?php echo esc_html( $module_type ); ?>-number"
								class="sui-form-control sui-input-sm"
								style="max-width: 100px; margin-top: 10px;"
								aria-labelledby="hustle-<?php echo esc_html( $module_type ); ?>-number-label"
								aria-describedby="hustle-<?php echo esc_html( $module_type ); ?>-number-description"
							/>

						</div>

							<div class="sui-form-field">

								<label id="hustle-<?php echo esc_html( $module_type ); ?>-status-label" class="sui-settings-label"><?php esc_html_e( 'Status', 'hustle' ); ?></label>

								<span id="hustle-<?php echo esc_html( $module_type ); ?>-status-description" class="sui-description" style="margin-bottom: 10px;">
									<?php /* translators: 1. module type in lower case */ ?>
									<?php printf( esc_html__( 'By default, we display %1$s with any status. However, you can display %1$s with a specific status only on the dashboard.', 'hustle' ), esc_html( $data['lowercase'] ) ); ?>
								</span>

								<label
									for="hustle-<?php echo esc_attr( $module_type ); ?>-status--published"
									class="sui-checkbox sui-checkbox-stacked sui-checkbox-sm"
								>
									<input
										type="checkbox"
										name="published_<?php echo esc_attr( $module_type ); ?>_on_dashboard"
										value="1"
										id="hustle-<?php echo esc_attr( $module_type ); ?>-status--published"
										<?php checked( $settings[ 'published_' . $module_type . '_on_dashboard' ] ); ?>
									>
									<span aria-hidden="true"></span>
									<span><?php esc_html_e( 'Published', 'hustle' ); ?></span>
								</label>

								<label
									for="hustle-<?php echo esc_attr( $module_type ); ?>-status--drafts"
									class="sui-checkbox sui-checkbox-stacked sui-checkbox-sm"
								>
									<input
										type="checkbox"
										id="hustle-<?php echo esc_attr( $module_type ); ?>-status--drafts"
										name="draft_<?php echo esc_attr( $module_type ); ?>_on_dashboard"
										value="1"
										<?php checked( $settings[ 'draft_' . $module_type . '_on_dashboard' ] ); ?>
									>
										<span aria-hidden="true"></span>
										<span><?php esc_html_e( 'Drafts', 'hustle' ); ?></span>
									</label>

							</div>

					</div>

				<?php endforeach; ?>

			</div>

		</div>

	</div>

</div>