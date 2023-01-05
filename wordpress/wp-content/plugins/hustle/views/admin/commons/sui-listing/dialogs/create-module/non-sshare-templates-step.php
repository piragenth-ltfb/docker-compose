<?php
/**
 * Templates options for creating non-sshare modules.
 *
 * @package Hustle
 * @since 4.3.0
 */

?>

<div class="sui-box">

	<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60 sui-spacing-bottom--30">

		<button class="sui-button-icon sui-button-float--right hustle-modal-close" data-modal-close>
			<span class="sui-icon-close sui-md" aria-hidden="true"></span>
			<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
		</button>

		<button class="sui-button-icon sui-button-float--left hustle-modal-go-back">
			<span class="sui-icon-chevron-left sui-md" aria-hidden="true"></span>
			<span class="sui-screen-reader-text"><?php esc_html_e( 'Go back to select module name and mode', 'hustle' ); ?></span>
		</button>

		<h3
			id="hustle-create-new-module-dialog-step-<?php echo esc_attr( $mode ); ?>-templates-label"
			class="sui-box-title sui-lg"
			style="max-width: 340px; margin-right: auto; margin-left: auto;"
		>
			<?php esc_html_e( 'Choose a Template', 'hustle' ); ?>
		</h3>

		<p id="hustle-create-new-module-dialog-step-<?php echo esc_attr( $mode ); ?>-templates-description"
			class="sui-description"
			style="max-width: 390px; margin-right: auto; margin-left: auto;"
		>
			<?php
			printf(
				/* translators: 1. number of templates, 2. opening 'a' tag to the templates' docs, 3. closing 'a' tag. */
				esc_html__( 'Please choose one of our %1$s pre-designed templates and customize it to fit your needs or start from scratch. %2$sLearn more%3$s.', 'hustle' ),
				count( $templates ),
				'<a href="' . esc_url( Opt_In_Utils::get_link( 'docs', 'choose_template_learnmore_link' ) . '#choosing-a-template' ) . '" target="_blank">',
				'</a>'
			);
			?>
		</p>

	</div>

	<div class="sui-box-body hui-templates-wrapper">

		<div class="hui-templates">

			<div class="hui-template-button">

				<h4 class="hui-screen-reader-highlight" tabindex="0"><?php esc_html_e( 'Clean Template', 'hustle' ); ?></h4>

				<button
					class="hustle-template-select-button hustle-template-option--none"
					aria-label="<?php esc_html_e( 'Build your template from scratch', 'hustle' ); ?>"
					data-template="none"
				>
					<span class="sui-icon-pencil sui-lg" aria-hidden="true"></span>
					<span><?php esc_html_e( 'Start from scratch', 'hustle' ); ?></span>
				</button>

			</div>

			<?php foreach ( $templates as $template_name => $data ) { ?>

				<div class="hui-template-card" tabindex="0">

					<div class="hui-template-card--image" aria-hidden="true">
						<img src="<?php echo esc_url( $data['thumbnail'] ); ?>" srcset="<?php echo esc_url( $data['thumbnail'] ); ?> 1x, <?php echo esc_url( $data['thumbnail2x'] ); ?> 2x" aria-hidden="true" />
						<div class="hui-template-card--mask" aria-hidden="true"></div>
					</div>

					<h4><?php echo esc_html( $data['label'] ); ?></h4>

					<p class="hui-screen-reader-highlight" tabindex="0"><?php echo esc_html( $data['description'] ); ?></p>

					<button
						class="sui-button sui-button-ghost sui-button-white hustle-template-preview-button"
						<?php /* translators: template name. */ ?>
						aria-label="<?php printf( esc_html__( 'Live preview %s template', 'hustle' ), esc_html( $data['label'] ) ); ?>"
						data-template="<?php echo esc_attr( $template_name ); ?>"
						data-module-type="<?php echo esc_attr( $this->admin->module_type ); ?>"
						data-module-mode="<?php echo esc_attr( $mode ); ?>"
					>
						<span class="sui-icon-eye" aria-hidden="true"></span>
						<?php esc_html_e( 'Preview', 'hustle' ); ?>
					</button>

					<button
						class="sui-button sui-button-blue hustle-template-select-button"
						<?php /* translators: template name. */ ?>
						aria-label="<?php printf( esc_html__( 'Build from %s template', 'hustle' ), esc_html( $data['label'] ) ); ?>"
						data-template="<?php echo esc_attr( $template_name ); ?>"
					>
						<?php esc_html_e( 'Choose Template', 'hustle' ); ?>
					</button>

				</div>

			<?php } ?>

		</div>

	</div>

	<div class="sui-box-body sui-content-center">

		<p class="sui-description"><?php esc_html_e( 'Watch this space! More templates are coming soon.', 'hustle' ); ?></p>

	</div>

</div>