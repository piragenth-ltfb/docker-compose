<?php
/**
 * Displays the listing page view when there are not modules.
 *
 * @package Hustle
 * @since 4.0.0
 */

$image_1x = self::$plugin_url . 'assets/images/hustle-welcome.png';
$image_2x = self::$plugin_url . 'assets/images/hustle-welcome@2x.png';
?>

<div class="sui-box sui-message sui-message-lg">


	<?php
	if ( ! $this->is_branding_hidden ) :
		echo $this->render_image_markup( $image_1x, $image_2x, 'sui-image' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	endif;
	?>

	<div class="sui-message-content">

		<?php if ( isset( $message ) && '' !== $message ) { ?>

			<p><?php echo esc_html( $message ); ?></p>

		<?php } else { ?>

			<p><?php esc_html_e( "You don't have any module yet. Click on create button to start.", 'hustle' ); ?></p>

		<?php } ?>

		<?php if ( $capability['hustle_create'] ) { ?>

			<p>
				<button
					id="hustle-create-new-module"
					class="sui-button sui-button-blue hustle-create-module"
				>
					<span class="sui-icon-plus" aria-hidden="true"></span> <?php esc_html_e( 'Create', 'hustle' ); ?>
				</button>

				<button
					class="sui-button hustle-import-module-button"
				>
					<span class="sui-icon-upload-cloud" aria-hidden="true"></span> <?php esc_html_e( 'Import', 'hustle' ); ?>
				</button>
			</p>

		<?php } ?>

	</div>

</div>