<?php
/**
 * Template for the accordions' content for color palettes.
 *
 * @since 4.3.0
 * @package Hustle
 */

$first_tab_label   = true;
$first_tab_content = true;
?>
<div class="sui-box">

	<div class="sui-box-body">

		<?php if ( isset( $palette['group_states'] ) ) : ?>

			<div class="sui-tabs sui-tabs-flushed">

				<div data-tabs>

					<?php foreach ( $palette['group_states'] as $key_state => $state ) : ?>

						<div <?php echo $first_tab_label ? 'class="active"' : ''; ?>>
							<?php echo esc_html( $state['name'] ); ?>
						</div>

						<?php $first_tab_label = false; ?>
					<?php endforeach; ?>

				</div>

				<div data-panes>

					<?php foreach ( $palette['group_states'] as $key_state => $state ) : ?>

						<div <?php echo $first_tab_content ? 'class="active"' : ''; ?>>

							<?php foreach ( $state['colors'] as $key_color => $color ) : ?>

								<div class="sui-form-field">

									<label class="sui-label"><?php echo esc_html( $color['name'] ); ?></label>

									<?php
									if ( ! $is_settings_page ) {
										$this->sui_colorpicker( $key_color, $color['value'], $color['alpha'], false, $settings[ $color['value'] ] );
									} else {
										$this->sui_colorpicker( $key_color, $color['value'], $color['alpha'], true );
									}
									?>

								</div>

							<?php endforeach; ?>

						</div>

						<?php $first_tab_content = false; ?>
					<?php endforeach; ?>

				</div>

			</div>

		<?php else : ?>

			<?php foreach ( $palette['colors'] as $key_color => $color ) : ?>

				<div class="sui-form-field">

					<label class="sui-label"><?php echo esc_html( $color['name'] ); ?></label>

					<?php
					if ( ! $is_settings_page ) {
						$this->sui_colorpicker( $key_color, $color['value'], $color['alpha'], false, $settings[ $color['value'] ] );
					} else {
						$this->sui_colorpicker( $key_color, $color['value'], $color['alpha'], true );
					}
					?>

				</div>

			<?php endforeach; ?>

		<?php endif; ?>

	</div>

</div>