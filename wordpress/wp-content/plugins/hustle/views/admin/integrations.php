<?php
/**
 * Main wrapper for the Integrations page.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>

<div class="sui-header">

	<h1 class="sui-header-title"><?php esc_html_e( 'Integrations', 'hustle' ); ?></h1>
	<?php $this->render( 'admin/commons/view-documentation', array( 'docs_section' => 'integrations' ) ); ?>
</div>

<div id="hustle-floating-notifications-wrapper" class="sui-floating-notices"></div>

<!-- BOX: Summary -->
<?php $this->render( 'admin/integrations-page/summary', array( 'sui' => $sui ) ); ?>

<div class="sui-row">

	<!-- BOX: Connected Apps -->
	<div class="sui-col-md-6">

		<?php $this->render( 'admin/integrations-page/connected-apps' ); ?>

	</div>

	<!-- BOX: Available Apps -->
	<div class="sui-col-md-6">

		<?php $this->render( 'admin/integrations-page/available-apps' ); ?>

	</div>

</div>

<!-- Integrations modal -->
<?php $this->render( 'admin/dialogs/modal-integration' ); ?>

<!-- Active integration remove modal -->
<?php $this->render( 'admin/dialogs/remove-active-integration' ); ?>

<!-- CTCT integration migration modal. Not using this. -->
<?php // $this->render( 'admin/dialogs/modal-migrate-ctct' ); ?>

<!-- Aweber integration migration modal -->
<?php $this->render( 'admin/dialogs/modal-migrate-aweber' ); ?>

<?php
// Global Footer.
$this->render( 'admin/footer/footer' );
?>

<?php
// DIALOG: Dissmiss migrate tracking notice modal confirmation.
if ( Hustle_Notifications::is_show_migrate_tracking_notice() ) {
	$this->render( 'admin/dialogs/migrate-dismiss-confirmation' );
}
?>