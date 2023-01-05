<?php
/**
 * Markup for the modal to create Social Sharing modules on listing page.
 *
 * @package Hustle
 * @since 4.3.0
 */

?>
<div class="sui-box">

	<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">

		<button class="sui-button-icon sui-button-float--right hustle-modal-close" data-modal-close>
			<span class="sui-icon-close sui-md" aria-hidden="true"></span>
			<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
		</button>

		<?php /* translators: module's type capitalized and in singular. */ ?>
		<h3 id="hustle-create-new-module-dialog-label" class="sui-box-title sui-lg"><?php printf( esc_html__( 'Create %s', 'hustle' ), esc_html( $capitalize_singular ) ); ?></h3>

		<?php /* translators: module's type in small caps and in singular. */ ?>
		<p id="hustle-create-new-module-dialog-description" class="sui-description"><?php printf( esc_html__( "Let's give your new %s module a name. What would you like to name it?", 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

	</div>

	<div class="sui-box-body">

		<div class="sui-form-field">

			<?php /* translators: module's type in small caps and in singular. */ ?>
			<label for="hustle-module-name" class="sui-screen-reader-text"><?php printf( esc_html__( '%s name', 'hustle' ), esc_html( $capitalize_singular ) ); ?></label>

			<div class="sui-with-button sui-inside">

				<input
					type="text"
					name="name"
					autocomplete="off"
					placeholder="<?php esc_html_e( 'E.g. Social Sharing', 'hustle' ); ?>"
					id="hustle-module-name"
					class="sui-form-control sui-required"
					autofocus
				/>

				<button id="hustle-create-module" class="sui-button-icon sui-button-blue sui-button-filled sui-button-lg" disabled>
					<span class="sui-loading-text">
						<span class="sui-icon-arrow-right" aria-hidden="true"></span>
					</span>
					<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Done', 'hustle' ); ?></span>
				</button>

			</div>

			<span id="error-empty-name" class="sui-error-message" style="display: none;"><?php esc_html_e( 'Please add a name for this module.', 'hustle' ); ?></span>

			<span class="sui-description"><?php esc_html_e( 'This will not be visible anywhere on your website', 'hustle' ); ?></span>

		</div>

	</div>

	<?php if ( ! $this->is_branding_hidden ) { ?>
		<img
			src="<?php echo esc_url( self::$plugin_url . 'assets/images/hustle-create.png' ); ?>"
			srcset="<?php echo esc_url( self::$plugin_url . 'assets/images/hustle-create.png' ); ?> 1x, <?php echo esc_url( self::$plugin_url . 'assets/images/hustle-create@2x.png' ); ?> 2x"
			<?php /* translators: module's type capitalized and in singular. */ ?>
			alt="<?php printf( esc_html__( 'Create New %s', 'hustle' ), esc_html( $capitalize_singular ) ); ?>"
			class="sui-image sui-image-center"
			aria-hidden="true"
		/>
	<?php } ?>

</div>