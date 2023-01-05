<?php
/**
 * Triggers section.
 *
 * @package Hustle
 * @since 4.0.0
 */

$is_popup     = Hustle_Module_Model::POPUP_MODULE === $module_type;
$column_class = $is_popup ? 'sui-col-md-6' : 'sui-col';
$animation_in = $settings['animation_in'];
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Animation Settings', 'hustle' ); ?></span>
		<?php /* translators: module type in small caps and in singular */ ?>
		<span class="sui-description"><?php printf( esc_html__( 'Choose how you want your %s to animate on entrance & exit.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<div class="sui-row">

			<div class="<?php echo esc_attr( $column_class ); ?>">

				<div class="sui-form-field">

					<?php /* translators: module type capitalized and in singular */ ?>
					<label class="sui-label"><?php printf( esc_html__( '%s entrance animation', 'hustle' ), esc_html( $capitalize_singular ) ); ?></label>

					<select class="sui-select" name="animation_in" data-search="true" data-attribute="animation_in">

						<option value="no_animation"
							<?php selected( ( 'no_animation' === $animation_in || '' === $animation_in ) ); ?>>
							<?php esc_attr_e( 'No Animation', 'hustle' ); ?>
						</option>

						<option value="bounceIn"
							<?php selected( $animation_in, 'bounceIn' ); ?>>
							<?php esc_attr_e( 'Bounce In', 'hustle' ); ?>
						</option>

						<option value="bounceInUp"
							<?php selected( $animation_in, 'bounceInUp' ); ?>>
							<?php esc_attr_e( 'Bounce In Up', 'hustle' ); ?>
						</option>

						<option value="bounceInRight"
							<?php selected( $animation_in, 'bounceInRight' ); ?>>
							<?php esc_attr_e( 'Bounce In Right', 'hustle' ); ?>
						</option>

						<option value="bounceInDown"
							<?php selected( $animation_in, 'bounceInDown' ); ?>>
							<?php esc_attr_e( 'Bounce In Down', 'hustle' ); ?>
						</option>

						<option value="bounceInLeft"
							<?php selected( $animation_in, 'bounceInLeft' ); ?>>
							<?php esc_attr_e( 'Bounce In Left', 'hustle' ); ?>
						</option>

						<option value="fadeIn"
							<?php selected( $animation_in, 'fadeIn' ); ?>>
							<?php esc_attr_e( 'Fade In', 'hustle' ); ?>
						</option>

						<option value="fadeInUp"
							<?php selected( $animation_in, 'fadeInUp' ); ?>>
							<?php esc_attr_e( 'Fade In Up', 'hustle' ); ?>
						</option>

						<option value="fadeInRight"
							<?php selected( $animation_in, 'fadeInRight' ); ?>>
							<?php esc_attr_e( 'Fade In Right', 'hustle' ); ?>
						</option>

						<option value="fadeInDown"
							<?php selected( $animation_in, 'fadeInDown' ); ?>>
							<?php esc_attr_e( 'Fade In Down', 'hustle' ); ?>
						</option>

						<option value="fadeInLeft"
							<?php selected( $animation_in, 'fadeInLeft' ); ?>>
							<?php esc_attr_e( 'Fade In Left', 'hustle' ); ?>
						</option>

						<option value="rotateIn"
							<?php selected( $animation_in, 'rotateIn' ); ?>>
							<?php esc_attr_e( 'Rotate In', 'hustle' ); ?>
						</option>

						<option value="rotateInDownLeft"
							<?php selected( $animation_in, 'rotateInDownLeft' ); ?>>
							<?php esc_attr_e( 'Rotate In Down Left', 'hustle' ); ?>
						</option>

						<option value="rotateInDownRight"
							<?php selected( $animation_in, 'rotateInDownRight' ); ?>>
							<?php esc_attr_e( 'Rotate In Down Right', 'hustle' ); ?>
						</option>

						<option value="rotateInUpLeft"
							<?php selected( $animation_in, 'rotateInUpLeft' ); ?>>
							<?php esc_attr_e( 'Rotate In Up Left', 'hustle' ); ?>
						</option>

						<option value="rotateInUpRight"
							<?php selected( $animation_in, 'rotateInUpRight' ); ?>>
							<?php esc_attr_e( 'Rotate In Up Right', 'hustle' ); ?>
						</option>

						<option value="slideInUp"
							<?php selected( $animation_in, 'slideInUp' ); ?>>
							<?php esc_attr_e( 'Slide In Up', 'hustle' ); ?>
						</option>

						<option value="slideInRight"
							<?php selected( $animation_in, 'slideInRight' ); ?>>
							<?php esc_attr_e( 'Slide In Right', 'hustle' ); ?>
						</option>

						<option value="slideInDown"
							<?php selected( $animation_in, 'slideInDown' ); ?>>
							<?php esc_attr_e( 'Slide In Down', 'hustle' ); ?>
						</option>

						<option value="slideInLeft"
							<?php selected( $animation_in, 'slideInLeft' ); ?>>
							<?php esc_attr_e( 'Slide In Left', 'hustle' ); ?>
						</option>

						<option value="zoomIn"
							<?php selected( $animation_in, 'zoomIn' ); ?>>
							<?php esc_attr_e( 'Zoom In', 'hustle' ); ?>
						</option>

						<option value="zoomInUp"
							<?php selected( $animation_in, 'zoomInUp' ); ?>>
							<?php esc_attr_e( 'Zoom In Up', 'hustle' ); ?>
						</option>

						<option value="zoomInRight"
							<?php selected( $animation_in, 'zoomInRight' ); ?>>
							<?php esc_attr_e( 'Zoom In Right', 'hustle' ); ?>
						</option>

						<option value="zoomInDown"
							<?php selected( $animation_in, 'zoomInDown' ); ?>>
							<?php esc_attr_e( 'Zoom In Down', 'hustle' ); ?>
						</option>

						<option value="zoomInLeft"
							<?php selected( $animation_in, 'zoomInLeft' ); ?>>
							<?php esc_attr_e( 'Zoom In Left', 'hustle' ); ?>
						</option>

						<option value="rollIn"
							<?php selected( $animation_in, 'rollIn' ); ?>>
							<?php esc_attr_e( 'Roll In', 'hustle' ); ?>
						</option>

						<option value="lightSpeedIn"
							<?php selected( $animation_in, 'lightSpeedIn' ); ?>>
							<?php esc_attr_e( 'Light Speed In', 'hustle' ); ?>
						</option>

						<option value="newspaperIn"
							<?php selected( $animation_in, 'newspaperIn' ); ?>>
							<?php esc_attr_e( 'Newspaper In', 'hustle' ); ?>
						</option>

					</select>

				</div>

			</div>

			<?php if ( $is_popup ) : ?>
				<?php $animation_out = $settings['animation_out']; ?>

				<div class="<?php echo esc_attr( $column_class ); ?>">

					<div class="sui-form-field">

						<?php /* translators: module type capitalized and in singular */ ?>
						<label class="sui-label"><?php printf( esc_html__( '%s exit animation', 'hustle' ), esc_html( $capitalize_singular ) ); ?></label>

						<select class="sui-select" data-search="true" data-attribute="animation_out">

							<option value="no_animation"
								<?php selected( ( 'no_animation' === $animation_out || '' === $animation_out ) ); ?>>
								<?php esc_attr_e( 'No Animation', 'hustle' ); ?>
							</option>

							<option value="bounceOut"
								<?php selected( $animation_out, 'bounceOut' ); ?>>
								<?php esc_attr_e( 'Bounce Out', 'hustle' ); ?>
							</option>

							<option value="bounceOutUp"
								<?php selected( $animation_out, 'bounceOutUp' ); ?>>
								<?php esc_attr_e( 'Bounce Out Up', 'hustle' ); ?>
							</option>

							<option value="bounceOutRight"
								<?php selected( $animation_out, 'bounceOutRight' ); ?>>
								<?php esc_attr_e( 'Bounce Out Right', 'hustle' ); ?>
							</option>

							<option value="bounceOutDown"
								<?php selected( $animation_out, 'bounceOutDown' ); ?>>
								<?php esc_attr_e( 'Bounce Out Down', 'hustle' ); ?>
							</option>

							<option value="bounceOutLeft"
								<?php selected( $animation_out, 'bounceOutLeft' ); ?>>
								<?php esc_attr_e( 'Bounce Out Left', 'hustle' ); ?>
							</option>

							<option value="fadeOut"
								<?php selected( $animation_out, 'fadeOut' ); ?>>
								<?php esc_attr_e( 'Fade Out', 'hustle' ); ?>
							</option>

							<option value="fadeOutUp"
								<?php selected( $animation_out, 'fadeOutUp' ); ?>>
								<?php esc_attr_e( 'Fade Out Up', 'hustle' ); ?>
							</option>

							<option value="fadeOutRight"
								<?php selected( $animation_out, 'fadeOutRight' ); ?>>
								<?php esc_attr_e( 'Fade Out Right', 'hustle' ); ?>
							</option>

							<option value="fadeOutDown"
								<?php selected( $animation_out, 'fadeOutDown' ); ?>>
								<?php esc_attr_e( 'Fade Out Down', 'hustle' ); ?>
							</option>

							<option value="fadeOutLeft"
								<?php selected( $animation_out, 'fadeOutLeft' ); ?>>
								<?php esc_attr_e( 'Fade Out Left', 'hustle' ); ?>
							</option>

							<option value="rotateOut"
								<?php selected( $animation_out, 'rotateOut' ); ?>>
								<?php esc_attr_e( 'Rotate Out', 'hustle' ); ?>
							</option>

							<option value="rotateOutUpLeft"
								<?php selected( $animation_out, 'rotateOutUpLeft' ); ?>>
								<?php esc_attr_e( 'Rotate Out Up Left', 'hustle' ); ?>
							</option>

							<option value="rotateOutUpRight"
								<?php selected( $animation_out, 'rotateOutUpRight' ); ?>>
								<?php esc_attr_e( 'Rotate Out Up Right', 'hustle' ); ?>
							</option>

							<option value="rotateOutDownLeft"
								<?php selected( $animation_out, 'rotateOutDownLeft' ); ?>>
								<?php esc_attr_e( 'Rotate Out Down Left', 'hustle' ); ?>
							</option>

							<option value="rotateOutDownRight"
								<?php selected( $animation_out, 'rotateOutDownRight' ); ?>>
								<?php esc_attr_e( 'Rotate Out Down Right', 'hustle' ); ?>
							</option>

							<option value="slideOutUp"
								<?php selected( $animation_out, 'slideOutUp' ); ?>>
								<?php esc_attr_e( 'Slide Out Up', 'hustle' ); ?>
							</option>

							<option value="slideOutRight"
								<?php selected( $animation_out, 'slideOutRight' ); ?>>
								<?php esc_attr_e( 'Slide Out Right', 'hustle' ); ?>
							</option>

							<option value="slideOutDown"
								<?php selected( $animation_out, 'slideOutDown' ); ?>>
								<?php esc_attr_e( 'Slide Out Down', 'hustle' ); ?>
							</option>

							<option value="slideOutLeft"
								<?php selected( $animation_out, 'slideOutLeft' ); ?>>
								<?php esc_attr_e( 'Slide Out Left', 'hustle' ); ?>
							</option>

							<option value="zoomOut"
								<?php selected( $animation_out, 'zoomOut' ); ?>>
								<?php esc_attr_e( 'Zoom Out', 'hustle' ); ?>
							</option>

							<option value="zoomOutUp"
								<?php selected( $animation_out, 'zoomOutUp' ); ?>>
								<?php esc_attr_e( 'Zoom Out Up', 'hustle' ); ?>
							</option>

							<option value="zoomOutRight"
								<?php selected( $animation_out, 'zoomOutRight' ); ?>>
								<?php esc_attr_e( 'Zoom Out Right', 'hustle' ); ?>
							</option>

							<option value="zoomOutDown"
								<?php selected( $animation_out, 'zoomOutDown' ); ?>>
								<?php esc_attr_e( 'Zoom Out Down', 'hustle' ); ?>
							</option>

							<option value="zoomOutLeft"
								<?php selected( $animation_out, 'zoomOutLeft' ); ?>>
								<?php esc_attr_e( 'Zoom Out Left', 'hustle' ); ?>
							</option>

							<option value="rollOut"
								<?php selected( $animation_out, 'rollOut' ); ?>>
								<?php esc_attr_e( 'Roll Out', 'hustle' ); ?>
							</option>

							<option value="lightSpeedOut"
								<?php selected( $animation_out, 'animation_out' ); ?>>
								<?php esc_attr_e( 'Light Speed Out', 'hustle' ); ?>
							</option>

							<option value="newspaperOut"
								<?php selected( $animation_out, 'newspaperOut' ); ?>>
								<?php esc_attr_e( 'Newspaper Out', 'hustle' ); ?>
							</option>

						</select>

					</div>

				</div>

			<?php endif; ?>

		</div>

	</div>

</div>