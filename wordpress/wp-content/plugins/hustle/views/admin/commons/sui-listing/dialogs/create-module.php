<?php
/**
 * Markup for the modal "Create Module" on listing pages.
 *
 * @package Hustle
 * @since 4.0.0
 */

$create_options_file = Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $this->admin->module_type ? 'create-non-sshare' : 'create-sshare';
?>

<div class="sui-modal sui-modal-sm">

	<div
		role="dialog"
		id="hustle-dialog--create-new-module"
		class="sui-modal-content"
		aria-modal="true"
		<?php /* translators: module type in smallcaps and singular. */ ?>
		aria-labelledby="hustle-create-new-module-dialog-label"
		aria-describedby="hustle-create-new-module-dialog-description"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle_create_new_module' ) ); ?>"
		data-error-message="<?php esc_attr_e( 'Something went wrong while creating your pop-up. Please try again.', 'hustle' ); ?>"
		aria-live="polite"
	>
		<?php
		// Create module options.
		$this->render(
			'admin/commons/sui-listing/dialogs/create-module/' . $create_options_file,
			array(
				'capitalize_singular' => $capitalize_singular,
				'smallcaps_singular'  => $smallcaps_singular,
			)
		);
		?>

	</div>

</div>