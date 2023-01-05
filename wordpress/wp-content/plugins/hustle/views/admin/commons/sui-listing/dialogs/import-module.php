<?php
/**
 * Dialog used in the modules' listing pages for importing modules.
 *
 * @package Hustle
 * @since 4.0.0
 */

$is_ssharing     = Hustle_Module_Model::SOCIAL_SHARING_MODULE === $this->admin->module_type;
$module_instance = new Hustle_Module_Model();

if ( ! $is_ssharing ) {

	$smallcaps_singular = Opt_In_Utils::get_module_type_display_name( $this->admin->module_type );

	$optin_settings_markup = $this->render(
		'admin/commons/sui-listing/dialogs/import-module-settings-section',
		array(
			'metas' => $module_instance->get_module_meta_names( $this->admin->module_type, Hustle_Module_Model::OPTIN_MODE, true ),
			'id'    => 'optin',
		),
		true
	);

	$info_settings_markup = $this->render(
		'admin/commons/sui-listing/dialogs/import-module-settings-section',
		array(
			'metas' => $module_instance->get_module_meta_names( $this->admin->module_type, Hustle_Module_Model::INFORMATIONAL_MODE, true ),
			'id'    => 'info',
		),
		true
	);

} else {
	$ssharing_settings_markup = $this->render(
		'admin/commons/sui-listing/dialogs/import-module-settings-section',
		array(
			'metas' => $module_instance->get_module_meta_names( $this->admin->module_type, '', true ),
			'id'    => 'ssharing',
		),
		true
	);
}

ob_start();

$notice_options = array(
	array(
		'type'  => 'inline_notice',
		'id'    => 'hustle-dialog--import-error-notice',
		'value' => '',
	),
);

$this->get_html_for_options( $notice_options );
?>

<div class="sui-form-field">

	<label class="sui-label"><?php esc_html_e( 'Configuration file', 'hustle' ); ?></label>

	<div class="sui-upload">

		<input
			id="hustle-import-file-input"
			class="hustle-file-input"
			type="file"
			name="import_file"
			value=""
			readonly="readonly"
			accept=".json"
		/>

		<label class="sui-upload-button" type="button" for="hustle-import-file-input">
			<span class="sui-icon-upload-cloud" aria-hidden="true"></span> <?php esc_html_e( 'Upload file', 'hustle' ); ?>
		</label>

		<div class="sui-upload-file">

			<span></span>

			<button type="button" aria-label="Remove file">
				<span class="sui-icon-close" aria-hidden="true"></span>
			</button>

		</div>

	</div>

	<span class="sui-description" style="margin-top: 10px;"><?php esc_html_e( 'Choose the configuration file (.json) to import the settings from.', 'hustle' ); ?></span>

</div>

<div id="hustle-import-modal-options" class="sui-form-field"></div>

<?php
$body_content = ob_get_clean();

$attributes = array(
	'modal_id'        => 'import',
	'has_description' => true,
	'modal_size'      => 'md',
	'sui_box_tag'     => 'form',
	'sui_box_id'      => 'hustle-import-module-form',

	'header'          => array(
		'classes'       => 'sui-flatten sui-content-center sui-spacing-top--60',
		/* translators: current module type display name capitalized and singular */
		'title'         => sprintf( __( 'Import %s', 'hustle' ), $capitalize_singular ),
		'title_classes' => 'sui-lg',
		'description'   => __( "Choose the configuration file and the settings you want to import. We'll import the settings which are available and apply to this module and keep the other settings to their default values.", 'hustle' ),
	),
	'body'            => array(
		'content' => $body_content,
	),
	'footer'          => array(
		'classes' => 'sui-content-separated',
		'buttons' => array(
			array(
				'classes'  => 'sui-button-ghost',
				'text'     => __( 'Cancel', 'hustle' ),
				'is_close' => true,
			),
			array(
				'id'         => 'hustle-import-module-submit-button',
				'classes'    => 'hustle-single-module-button-action',
				'icon'       => 'upload-cloud',
				'has_load'   => true,
				'text'       => __( 'Import', 'hustle' ),
				'attributes' => array(
					'data-hustle-action' => 'import',
					'data-form-id'       => 'hustle-import-module-form',
					'data-type'          => $this->admin->module_type,
					'disabled'           => 'disabled',
				),
			),
		),
	),
);

$this->render_modal( $attributes );
?>

<script id="hustle-import-modal-options-tpl" type="text/template">

<?php
/**
 * Non Social Sharing Markup.
 * We have different set of settings for an "optin" and an "informational" module,
 * reason why we need to split these import settings from social sharing settings
 * to avoid any error in the future.
 */
if ( ! $is_ssharing ) :
	?>

	<# if ( isNew ) { #>

		<label class="sui-label"><?php esc_html_e( 'Module type', 'hustle' ); ?></label>

		<div class="sui-tabs sui-side-tabs">

			<input tabindex="-1" type="radio" name="module_mode" value="default" id="hustle-import-options--default" style="display: none;" aria-hidden="true" hidden checked />
			<input tabindex="-1" type="radio" name="module_mode" value="<?php echo esc_attr( Hustle_Module_Model::OPTIN_MODE ); ?>" id="hustle-import-options--optin" style="display: none;" aria-hidden="true" hidden />
			<input tabindex="-1" type="radio" name="module_mode" value="<?php echo esc_attr( Hustle_Module_Model::INFORMATIONAL_MODE ); ?>" id="hustle-import-options--info" style="display: none;" aria-hidden="true" hidden />

			<div role="tablist" class="sui-tabs-menu">

				<button
					type="button"
					role="tab"
					id="hustle-import-options--default-tab"
					class="sui-tab-item active"
					aria-controls="hustle-import-options--default-content"
					aria-selected="true"
					data-label-for="hustle-import-options--default"
				>
					<?php esc_html_e( 'Default', 'hustle' ); ?>
				</button>

				<button
					type="button"
					role="tab"
					id="hustle-import-options--optin-tab"
					class="sui-tab-item"
					aria-controls="hustle-import-options--optin-content"
					aria-selected="false"
					data-label-for="hustle-import-options--optin"
				>
					<?php esc_html_e( 'Email Opt-in', 'hustle' ); ?>
				</button>

				<button
					type="button"
					role="tab"
					id="hustle-import-options--info-tab"
					class="sui-tab-item"
					aria-controls="hustle-import-options--info-content"
					aria-selected="false"
					data-label-for="hustle-import-options--info"
				>
					<?php esc_html_e( 'Informational', 'hustle' ); ?>
				</button>

			</div>

			<div class="sui-tabs-content">

				<div
					role="tabpanel"
					tabindex="0"
					id="hustle-import-options--optin-content"
					class="sui-tab-content sui-border-frame"
					aria-labelledby="hustle-import-options--optin-tab"
				>

					<?php echo $optin_settings_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

				</div>

				<div
					role="tabpanel"
					tabindex="0"
					id="hustle-import-options--info-content"
					class="sui-tab-content sui-border-frame"
					aria-labelledby="hustle-import-options--info-tab"
					hidden
				>

					<?php echo $info_settings_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

				</div>

			</div>

			<?php /* translators: module type in small caps and in singular. */ ?>
			<p class="sui-description"><?php printf( esc_html__( 'Choose the module type of the %s you want to create. The default is to take the module type from the configuration file and import all the settings from it.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

		</div>

	<# } else { #>

		<# if ( isOptin ) { #>
			<?php echo $optin_settings_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<# } else { #>
			<?php echo $info_settings_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<# } #>

	<# } #>

<?php else : ?>

	<?php echo $ssharing_settings_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

<?php endif; ?>

</script>