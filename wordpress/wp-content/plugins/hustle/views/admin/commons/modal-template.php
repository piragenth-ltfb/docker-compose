<?php
/**
 * Shared template for simple modals.
 * We, markup dummies, are grateful.
 *
 * @package Hustle
 * @since 4.2.0
 */

$is_unwrapped = isset( $no_wrapper ) ? $no_wrapper : false;

$modal_id             = 'hustle-dialog--' . $modal_id;
$modal_title_id       = $modal_id . '-title';
$modal_description_id = $has_description ? $modal_id . '-description' : false;

$modal_claxx  = 'sui-modal';
$modal_claxx .= in_array( $modal_size, array( 'sm', 'md', 'lg', 'xl' ), true ) ? ' sui-modal-' . $modal_size : '';
$modal_claxx .= ! empty( $modal_class ) ? ' ' . $modal_class : '';

$sui_box_tag  = empty( $sui_box_tag ) ? 'div' : $sui_box_tag;
$sui_box_id   = ! empty( $sui_box_id ) ? ' id="' . esc_attr( $sui_box_id ) . '"' : '';
$sui_box_attr = ! empty( $sui_box_attr ) ? $sui_box_attr : false;

$header_classes       = ! empty( $header['classes'] ) ? ' ' . $header['classes'] : '';
$header_title         = ! $is_unwrapped ? $header['title'] : '';
$header_title_classes = ! empty( $header['title_classes'] ) ? ' ' . $header['title_classes'] : '';
$header_description   = ! empty( $header['description'] ) ? $header['description'] : false;
$header_descr_class   = ! isset( $header['description_classes'] ) ? 'sui-description' : $header['description_classes'];
$header_content       = ! empty( $header['content'] ) ? $header['content'] : false;

$body_classes     = ! empty( $body['classes'] ) ? ' ' . $body['classes'] : '';
$body_description = ! empty( $body['description'] ) && ! $header_description ? $body['description'] : false;
$body_descr_class = ! isset( $body['description_classes'] ) ? 'sui-description' : $body['description_classes'];
$body_content     = ! empty( $body['content'] ) ? $body['content'] : false;

$after_body_content = ! empty( $after_body_content ) ? $after_body_content : false;

$footer_classes = ! empty( $footer['classes'] ) ? ' ' . $footer['classes'] : '';
$footer_content = ! empty( $footer['content'] ) ? $footer['content'] : false;
$footer_buttons = ! empty( $footer['buttons'] ) ? $footer['buttons'] : false;
?>
<div class="<?php echo esc_attr( $modal_claxx ); ?>">

	<div
		class="sui-modal-content"
		role="dialog"
		id="<?php echo esc_attr( $modal_id ); ?>"
		aria-labelledby="<?php echo esc_attr( $modal_title_id ); ?>"
		<?php if ( $modal_description_id ) : ?>
			aria-describedby="<?php echo esc_attr( $modal_description_id ); ?>"
		<?php endif; ?>
	>

		<?php if ( $is_unwrapped ) { ?>

			<?php echo $body_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

		<?php } else { ?>

			<<?php echo esc_attr( $sui_box_tag ) . $sui_box_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php
				if ( $sui_box_attr ) :
					$this->render_attributes( $sui_box_attr );
				endif;
				?>
				class="sui-box"
			>

				<div class="sui-box-header<?php echo esc_attr( $header_classes ); ?>">

					<button class="sui-button-icon sui-button-float--right hustle-modal-close" data-modal-close>
						<span class="sui-icon-close sui-md" aria-hidden="true"></span>
						<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
					</button>

					<h3 id="<?php echo esc_attr( $modal_id ); ?>-title" class="sui-box-title<?php echo esc_attr( $header_title_classes ); ?>">
						<?php echo esc_html( $header_title ); ?>
					</h3>

					<?php if ( $header_description ) : ?>

						<p id="<?php echo esc_attr( $modal_description_id ); ?>" <?php echo $header_descr_class ? 'class="' . esc_attr( $header_descr_class ) . '"' : ''; ?>><?php echo esc_html( $header_description ); ?></p>

					<?php endif; ?>

					<?php
					if ( $header_content ) :
						echo $header_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					endif;
					?>

				</div>

				<?php if ( $body_content || $body_description ) : ?>

					<div class="sui-box-body<?php echo esc_attr( $body_classes ); ?>">

						<?php if ( $body_description ) : ?>

							<p id="<?php echo esc_attr( $modal_description_id ); ?>" <?php echo $body_descr_class ? 'class="' . esc_attr( $body_descr_class ) . '"' : ''; ?>><?php echo esc_html( $body_description ); ?></p>

						<?php endif; ?>

						<?php
						if ( $body_content ) :

							echo $body_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

						endif;
						?>

					</div>

				<?php endif; ?>

				<?php
				if ( $after_body_content ) :

					echo $after_body_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				endif;
				?>

				<?php if ( $footer_content || $footer_buttons ) : ?>

					<div class="sui-box-footer<?php echo esc_attr( $footer_classes ); ?>">

						<?php
						if ( $footer_content ) :
							echo $footer_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

						endif;

						if ( $footer_buttons ) :

							foreach ( $footer_buttons as $button ) :

								$button_classes = ! empty( $button['classes'] ) ? ' ' . $button['classes'] : '';
								$button_id      = ! empty( $button['id'] ) ? ' id="' . esc_attr( $button['id'] ) . '"' : '';
								$button_type    = ! empty( $button['is_submit'] ) ? '' : ' type="button"';
								$close_data     = ! empty( $button['is_close'] ) ? ' data-modal-close' : '';
								$has_load       = ! empty( $button['has_load'] ) ? true : false;
								$button_attrs   = ! empty( $button['attributes'] ) ? $button['attributes'] : false;
								$button_icon    = ! empty( $button['icon'] ) ? $button['icon'] : false;
								$text           = $button['text'];
								?>

								<button
									class="sui-button<?php echo esc_attr( $button_classes ); ?>"
									<?php echo $button_id . $button_type . $close_data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<?php
									if ( $button_attrs ) :
										$this->render_attributes( $button_attrs );
									endif;
									?>
								>
									<?php if ( $has_load ) : ?>
										<span class="sui-loading-text">
									<?php endif; ?>

										<?php if ( $button_icon ) : ?>
											<span class="sui-icon-<?php echo esc_attr( $button_icon ); ?>" aria-hidden="true"></span>
										<?php endif; ?>
										<?php echo esc_html( $text ); ?>

									<?php if ( $has_load ) : ?>
										</span>
										<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
									<?php endif; ?>

								</button>

								<?php
							endforeach;

						endif;
						?>

					</div>

				<?php endif; ?>

			</<?php echo esc_attr( $sui_box_tag ); ?>>

		<?php } ?>

	</div>

</div>