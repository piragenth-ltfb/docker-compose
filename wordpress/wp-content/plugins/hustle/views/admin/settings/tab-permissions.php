<?php
/**
 * Permissions tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

$form_id  = 'hustle-permissions-settings-form';
$settings = Hustle_Settings_Admin::get_permissions_settings();
$roles    = Opt_In_Utils::get_user_roles();
?>
<div id="permissions-box" class="sui-box" data-tab="permissions"
<?php
if ( 'permissions' !== $section ) {
	echo 'style="display: none;"';}
?>
>

	<div class="sui-box-header">
		<h2 class="sui-box-title"><?php esc_html_e( 'Permissions', 'hustle' ); ?></h2>
	</div>

	<div class="sui-box-body">

		<?php
		// SETTINGS: Create Modules.
		$this->render(
			'admin/settings/permissions/permissions-row',
			array(
				'roles'         => $roles,
				'form_id'       => $form_id,
				'label'         => __( 'Create Modules', 'hustle' ),
				'description'   => __( 'Choose the user roles which can create new modules.', 'hustle' ),
				'input_name'    => 'create[]',
				'current_value' => $settings['create'],
			)
		);

		// SETTINGS: Edit Existing Modules.
		$this->render(
			'admin/settings/permissions/edit-modules',
			array(
				'roles'   => $roles,
				'form_id' => $form_id,
			)
		);

		// SETTINGS: Access Email List.
		$this->render(
			'admin/settings/permissions/permissions-row',
			array(
				'roles'         => $roles,
				'form_id'       => $form_id,
				'label'         => __( 'Access Email List', 'hustle' ),
				'description'   => __( 'Choose the user roles which can access the Email List for the opt-in modules.', 'hustle' ),
				'input_name'    => 'access_emails[]',
				'current_value' => $settings['access_emails'],
			)
		);

		// SETTINGS: Edit Integrations.
		$this->render(
			'admin/settings/permissions/permissions-row',
			array(
				'roles'         => $roles,
				'form_id'       => $form_id,
				'label'         => __( 'Edit Integrations', 'hustle' ),
				'description'   => __( 'Choose the user roles which can access the Integrations page and connect or disconnect Hustle to 3rd party apps.', 'hustle' ),
				'input_name'    => 'edit_integrations[]',
				'current_value' => $settings['edit_integrations'],
			)
		);

		// SETTINGS: Edit Settings.
		$this->render(
			'admin/settings/permissions/permissions-row',
			array(
				'roles'         => $roles,
				'form_id'       => $form_id,
				'label'         => __( 'Edit Settings', 'hustle' ),
				'description'   => __( 'Choose the user roles which can access the Settings page and update any settings.', 'hustle' ),
				'input_name'    => 'edit_settings[]',
				'current_value' => $settings['edit_settings'],
			)
		);
		?>

		<?php // All inputs point to this form. We can't have them nested due to the filter's form. ?>
		<form id="<?php echo esc_attr( $form_id ); ?>"></form>

	</div>



	<div class="sui-box-footer">

		<div class="sui-actions-right">

			<button
				class="sui-button sui-button-blue hustle-settings-save"
				data-form-id="<?php echo esc_attr( $form_id ); ?>"
				data-target="permissions"
			>
				<span class="sui-loading-text"><?php esc_html_e( 'Save Settings', 'hustle' ); ?></span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>

		</div>

	</div>

</div>