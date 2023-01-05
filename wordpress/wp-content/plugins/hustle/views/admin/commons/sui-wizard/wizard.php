<?php
/**
 * Main wrapper for wizards.
 *
 * @uses ../../dialogs/migrate-dismiss-confirmation.php
 * @uses ../../footer/footer.php
 * @uses ../dialogs/
 * @uses ../templates/
 * @uses ../navigation.php
 * @uses ../status-bar.php
 * @uses ../dialogs/modal-preview.php
 *
 * @package Hustle
 * @since 4.0.0
 */

$docs_section = Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $module_type ? 'content' : 'social-sharing';
?>

<div class="sui-header hui-header-with-settings">

	<div class="hui-header-title">

		<?php /* translators: module type capitalized and in singular */ ?>
		<h1 class="sui-header-title"><?php printf( esc_html__( 'Edit %s', 'hustle' ), esc_html( $capitalize_singular ) ); ?></h1>

		<?php $this->render( 'admin/commons/view-documentation', array( 'docs_section' => $docs_section ) ); ?>

	</div>

	<div class="hui-header-settings">

		<div class="sui-actions-left">

			<div id="hustle-module-name-wrapper" class="sui-form-field">

				<label for="hustle-module-name" id="hustle-module-name-label" class="sui-screen-reader-text"><?php esc_html_e( 'Name your form', 'hustle' ); ?></label>

				<input
					type="text"
					name="module_name"
					value="<?php echo htmlspecialchars( $module_name, ENT_QUOTES, 'UTF-8' ); ?>"
					placeholder="<?php esc_html_e( 'E.g. Newsletter', 'hustle' ); ?>"
					id="hustle-module-name"
					class="sui-form-control"
					data-attribute="module_name"
					aria-labelledby="hustle-module-name-label"
					aria-describedby="hustle-module-name-message"
				/>

				<p role="alert" id="hustle-module-name-error" class="sui-error-message" style="display: none; margin-bottom: 0px;" data-error-message="<?php esc_html_e( 'This field is required.', 'hustle' ); ?>"></p>

			</div>

			<?php // Dropdown list. ?>
			<div id="hustle-module-more-options" class="sui-dropdown sui-accordion-item-action">

				<?php
				$this->render(
					'admin/commons/sui-listing/elements/actions',
					array(
						'edit_page'            => true,
						'module'               => $module,
						'smallcaps_singular'   => $smallcaps_singular,
						'capitalize_singular'  => $capitalize_singular,
					)
				);
				?>

			</div>

			<?php
			// Create button.
			$args = array(
				'page'          => Hustle_Data::get_listing_page_by_module_type( $module->module_type ),
				'create-module' => 'true',
			);

			$tooltip = sprintf( __( 'Create New %s', 'hustle' ), $capitalize_singular );
			?>
			<a
				href="<?php echo esc_url( add_query_arg( $args, 'admin.php' ) ); ?>"
				class="sui-button-icon sui-tooltip sui-tooltip-bottom"
				data-tooltip="<?php echo esc_attr( $tooltip ); ?>"
			>
				<span class="sui-icon-plus sui-md" aria-hidden="true"></span>
			</a>

		</div>

		<?php $this->render( 'admin/commons/view-documentation', array( 'docs_section' => $docs_section ) ); ?>

	</div>

</div>

<div id="hustle-floating-notifications-wrapper" class="sui-floating-notices"></div>

<div id="<?php echo esc_attr( $page_id ); ?>" class="sui-row-with-sidenav" data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle_save_module_wizard' ) ); ?>" data-id="<?php echo $module_id ? esc_attr( $module_id ) : '-1'; ?>">

	<?php
	// ELEMENT: Side Navigation.
	$this->render(
		'admin/commons/sui-wizard/navigation',
		array(
			'is_optin'             => isset( $module_mode ) ? $module_mode : false,
			'section'              => $page_tab,
			'wizard_tabs'          => $wizard_tabs,
			'module_name'          => $module_name,
			'module_type'          => $module_type,
			'module'               => $module,
			'smallcaps_singular'   => $smallcaps_singular,
			'capitalize_singular'  => $capitalize_singular,
		)
	);
	?>

	<div class="hustle-wizard-main-view">

		<?php
		// ELEMENT: Status Bar.
		$this->render(
			'admin/commons/sui-wizard/status-bar',
			array(
				'is_active' => $module_status,
				'module_type' => $module_type,
			)
		);
		?>

		<?php
		foreach ( $wizard_tabs as $option ) {

			$option_array = array();

			if ( isset( $option['support'] ) ) {
				$option_array = $option['support'];
			}

			if ( isset( $option['is_optin'] ) ) {

				if ( $module_mode ) :

					$this->render(
						$option['template'],
						$option_array
					);

				endif;

			} else {

				$this->render(
					$option['template'],
					$option_array
				);
			}
		}
		?>

	</div>

</div>

<?php $this->render( 'admin/footer/footer' ); ?>

<?php if ( isset( $module_mode ) && $module_mode ) : ?>

	<?php
	// DIALOG: Integrations.
	$this->render(
		'admin/dialogs/modal-integration',
		array( 'module_type' => $module_type )
	);

	// DIALOG: Optin Fields.
	$this->render(
		'admin/commons/sui-wizard/dialogs/optin-fields',
		array(
			'form_elements' => $form_elements,
		)
	);

	// DIALOG: Edit Field.
	$this->render(
		'admin/commons/sui-wizard/dialogs/edit-field',
		array(
			'available_recaptchas' => Hustle_Settings_Admin::get_available_recaptcha_versions(),
		)
	);

	// DIALOG: Final Field.
	$this->render( 'admin/dialogs/final-integration-form-delete', array() );

	// Row: Optin Field Row template.
	$this->render( 'admin/commons/sui-wizard/elements/form-field', array() );
	?>

<?php endif; ?>

<?php
// DIALOG: add schedule. Only for non-ssharing modules.
if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $module->module_type ) {

	$behavior_settings = $module->get_settings()->to_array();
	$this->render(
		'admin/commons/sui-wizard/dialogs/add-schedule',
		array(
			'module_type' => $module_type,
			'settings'    => $behavior_settings['schedule'],
		)
	);
}

// CHECK: Visibility Tab.
if ( array_key_exists( 'visibility', $wizard_tabs ) ) {

	// DIALOG: Visibility.
	$this->render(
		'admin/commons/sui-wizard/dialogs/visibility-options',
		array( 'smallcaps_singular' => $smallcaps_singular )
	);

	// TEMPLATE: Conditions.
	$this->render(
		'admin/commons/sui-wizard/tab-visibility/conditions',
		array(
			'smallcaps_singular' => $smallcaps_singular,
			'module_type'        => $module_type,
		)
	);
}

// CHECK: Services Tab.
if ( array_key_exists( 'services', $wizard_tabs ) ) {

	// DIALOG: Social Platforms.
	$this->render(
		'admin/commons/sui-wizard/dialogs/add-platforms',
		array()
	);
}

// DIALOG: Publish Flow.
$this->render(
	'admin/commons/sui-wizard/dialogs/publish-flow',
	array(
		'capitalize_singular' => $capitalize_singular,
		'smallcaps_singular'  => $smallcaps_singular,
	)
);

// DIALOG: delete confirmation. For tracking.
$this->render( 'admin/commons/sui-listing/dialogs/delete-module' );

// DIALOG: Dissmiss migrate tracking notice modal confirmation.
if ( Hustle_Notifications::is_show_migrate_tracking_notice() ) {
	$this->render( 'admin/dialogs/migrate-dismiss-confirmation' );
}

// Preview.
$this->render( 'admin/dialogs/modal-preview', array( 'module_type' => $capitalize_singular ) );
?>