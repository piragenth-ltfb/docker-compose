<?php
/**
 * Markup for the modal to create non-Social sharing modules on listing pages.
 *
 * @package Hustle
 * @since 4.3.0
 */

$templates_helper = new Hustle_Templates_Helper();
?>
<div
	id="hustle-create-new-module-step-1"
	class="sui-modal-slide sui-active"
	data-modal-size="sm"
>

	<div class="sui-box">

		<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">

			<button class="sui-button-icon sui-button-float--right hustle-modal-close" data-modal-close>
				<span class="sui-icon-close sui-md" aria-hidden="true"></span>
				<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
			</button>

			<?php /* translators: module's type capitalized and in singular. */ ?>
			<h3 id="hustle-create-new-module-dialog-label" class="sui-box-title sui-lg"><?php printf( esc_html__( 'Create %s', 'hustle' ), esc_html( $capitalize_singular ) ); ?></h3>

			<?php /* translators: module's type in lowercase and in singular. */ ?>
			<p id="hustle-create-new-module-dialog-description" class="sui-description"><?php printf( esc_html__( "Let's start by giving your new %s a name and choosing the type based on your goal for this campaign.", 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

		</div>

		<div class="sui-box-body">

			<div class="sui-form-field">

				<h4 id="hustle-module-name-label" for="hustle-module-name" class="sui-label"><?php esc_html_e( 'Name', 'hustle' ); ?></h4>

				<input
					type="text"
					name="name"
					autocomplete="off"
					placeholder="<?php esc_html_e( 'E.g. Weekly Newsletter', 'hustle' ); ?>"
					id="hustle-module-name"
					aria-labelledby="hustle-module-name-label"
					class="sui-form-control sui-required"
					autofocus
				/>

				<span id="error-empty-name" class="sui-error-message" style="display: none;"><?php esc_html_e( 'Please add a name for this module.', 'hustle' ); ?></span>

			</div>

			<div role="radiogroup" class="sui-form-field" aria-labelledby="create-module-field--type">

				<h4 id="create-module-field--type" class="sui-label"><?php esc_html_e( 'Type', 'hustle' ); ?></h4>

				<label for="hustle-create-new-module-select-mode--optin" class="sui-radio sui-radio-sm sui-radio-stacked" style="margin-bottom: 5px;">

					<input
						type="radio"
						name="mode"
						id="hustle-create-new-module-select-mode--optin"
						value="optin"
						aria-labelledby="hustle-create-new-module-select-mode--optin-label"
						checked="checked"
					/>

					<span aria-hidden="true"></span>

					<span id="hustle-create-new-module-select-mode--optin-label"><?php esc_html_e( 'Email Opt-in', 'hustle' ); ?></span>

				</label>

				<p class="sui-description sui-radio-description" style="margin-bottom: 20px; margin-right: 0;"><?php esc_html_e( 'Perfect for newsletter signups, or collecting user data.', 'hustle' ); ?></p>

				<label for="hustle-create-new-module-select-mode--informational" class="sui-radio sui-radio-sm sui-radio-stacked" style="margin-bottom: 5px;">

					<input
						type="radio"
						name="mode"
						id="hustle-create-new-module-select-mode--informational"
						value="informational"
						aria-labelledby="hustle-create-new-module-select-mode--informational-label"
					/>

					<span aria-hidden="true"></span>

					<span id="hustle-create-new-module-select-mode--informational-label"><?php esc_html_e( 'Informational', 'hustle' ); ?></span>

				</label>

				<p class="sui-description sui-radio-description" style="margin-right: 0;"><?php esc_html_e( 'Perfect for promotional offers with Call to Action.', 'hustle' ); ?></p>

			</div>

		</div>

		<div class="sui-box-footer sui-flatten sui-content-center">

			<button id="hustle-go-to-templates-button" class="sui-button sui-button-blue sui-button-icon-right" disabled>
				<?php esc_html_e( 'Choose Template', 'hustle' ); ?>
				<span class="sui-icon-chevron-right" aria-hidden="true"></span>
			</button>

		</div>

	</div>

</div>

<div
	id="hustle-create-new-module-step-optin-templates"
	class="sui-modal-slide sui-active"
	data-modal-size="xl"
>

	<?php
	// Templates options.
	$this->render(
		'admin/commons/sui-listing/dialogs/create-module/non-sshare-templates-step',
		array(
			'templates'          => $templates_helper->get_optin_templates_data(),
			'smallcaps_singular' => $smallcaps_singular,
			'mode'               => Hustle_Module_Model::OPTIN_MODE,
		)
	);
	?>

</div>

<div
	id="hustle-create-new-module-step-informational-templates"
	class="sui-modal-slide sui-active"
	data-modal-size="xl"
>

	<?php
	// Templates options.
	$this->render(
		'admin/commons/sui-listing/dialogs/create-module/non-sshare-templates-step',
		array(
			'templates'          => $templates_helper->get_informational_templates_data(),
			'smallcaps_singular' => $smallcaps_singular,
			'mode'               => Hustle_Module_Model::INFORMATIONAL_MODE,
		)
	);
	?>

</div>