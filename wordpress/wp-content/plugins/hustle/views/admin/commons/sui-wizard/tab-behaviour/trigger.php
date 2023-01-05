<?php
/**
 * Triggers section.
 *
 * @package Hustle
 * @since 4.0.0
 */

$time_unit_options = array(
	'seconds' => __( 'seconds', 'hustle' ),
	'minutes' => __( 'minutes', 'hustle' ),
	'hours'   => __( 'hours', 'hustle' ),
);

$triggers_options = array(
	'time'        => __( 'Time', 'hustle' ),
	'scroll'      => __( 'Scroll', 'hustle' ),
	'click'       => __( 'Click', 'hustle' ),
	'exit_intent' => __( 'Exit Intent', 'hustle' ),
	'adblock'     => __( 'Ad-Block', 'hustle' ),
);
?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<?php /* translators: module type capitalized and in singular */ ?>
		<span class="sui-settings-label"><?php printf( esc_html__( '%s Trigger', 'hustle' ), esc_html( $capitalize_singular ) ); ?></span>

		<span class="sui-description">
			<?php /* translators: module type in smallcaps and in singular */ ?>
			<?php printf( esc_html__( 'Enable and configure the triggers you want to use for this %s.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?>
		</span>
		<span class="sui-description">
			<?php
			printf(
				/* translators: 1. opening 'strong' tag, 2. closing 'strong' tag, 3. module type in smallcaps and in singular */
				esc_html__( '%1$sNote%2$s: You must enable at least one trigger for your %3$s to appear.', 'hustle' ),
				'<strong>',
				'</strong>',
				esc_html( $smallcaps_singular )
			);
			?>
		</span>
	</div>

	<div class="sui-box-settings-col-2">

		<div id="hustle-behavior-trigger" class="sui-accordion">

			<?php foreach ( $triggers_options as $key => $label ) : ?>

				<div
					class="sui-accordion-item hustle-trigger-accordion-item"
					data-trigger="<?php echo esc_attr( $key ); ?>"
				>

					<div class="sui-accordion-item-header">
						<div class="sui-accordion-item-title">
							<?php
							$this->get_html_for_options(
								array(
									array(
										'type'       => 'checkbox_toggle',
										'name'       => 'trigger',
										'class'      => 'sui-accordion-item-action',
										'label'      => $label,
										'value'      => $key,
										'selected'   => $triggers['trigger'],
										'attributes' => array(
											'data-attribute' => 'triggers.trigger',
										),
									),
								)
							);
							?>
						</div>
						<div class="sui-accordion-col-auto">
							<button class="sui-button-icon sui-accordion-open-indicator" aria-label="<?php esc_html_e( 'Click to open or close the item content' ); ?>">
								<span class="sui-icon-chevron-down" aria-hidden="true"></span>
							</button>
						</div>
					</div><?php // END .sui-accordion-item-header. ?>

					<div class="sui-accordion-item-body">

						<div class="sui-box">

							<div class="sui-box-body">

								<?php
								$this->render(
									'admin/commons/sui-wizard/tab-behaviour/trigger/' . str_replace( '_', '-', $key ),
									array(
										'triggers'     => $triggers,
										'capitalize_singular' => $capitalize_singular,
										'smallcaps_singular' => $smallcaps_singular,
										'time_unit_options' => $time_unit_options, // For exit intent, adblock, time.
										'shortcode_id' => $shortcode_id, // Only for click.
									)
								);
								?>

							</div>

						</div>

					</div><?php // END .sui-accordion-item-body. ?>

				</div>

			<?php endforeach; ?>

		</div>

	</div>

</div>