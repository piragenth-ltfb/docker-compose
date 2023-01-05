<?php
/**
 * Modal for toggling tracking for Ssharing and Embed modules' display types.
 *
 * @package Hustle
 * @since 4.0.0
 */

$attributes = array(
	'modal_id'        => 'manage-tracking',
	'has_description' => true,
	'modal_size'      => 'sm',
	'sui_box_tag'     => 'form',
	'sui_box_id'      => 'hustle-manage-tracking-form',

	'header'          => array(
		'classes'       => 'sui-flatten sui-content-center sui-spacing-top--60',
		'title'         => __( 'Manage Tracking', 'hustle' ),
		'title_classes' => 'sui-lg',
		'description'   => __( 'Manage the conversion tracking for all the display options of this module.', 'hustle' ),
	),
	'body'            => array(
		'content' => '<div id="hustle-manage-tracking-form-container"></div>',
	),
	'footer'          => array(
		'classes' => 'sui-flatten sui-content-separated sui-spacing-top--0',
		'buttons' => array(
			array(
				'classes'  => 'sui-button-ghost',
				'text'     => __( 'Cancel', 'hustle' ),
				'is_close' => true,
			),
			array(
				'id'         => 'hustle-button-toggle-tracking-types',
				'classes'    => 'hustle-single-module-button-action',
				'has_load'   => true,
				'text'       => __( 'Update', 'hustle' ),
				'attributes' => array(
					'data-hustle-action' => 'toggle-tracking',
					'data-form-id'       => 'hustle-manage-tracking-form',
				),
			),
		),
	),
);

$this->render_modal( $attributes );
?>

<script id="hustle-manage-tracking-form-tpl" type="text/template">

	<table class="sui-table">

		<tbody>

			<?php foreach ( $multiple_charts as $chart_key => $chart ) : ?>

				<tr id="hustle-subtype-row-<?php echo esc_attr( $chart_key ); ?>">

					<th><?php echo esc_html( $chart ); ?></th>

					<td style="text-align: right;">

						<div class="sui-form-field" style="display: inline-block;">

							<label
								for="hustle-module-tracking--<?php echo esc_attr( $chart_key ); ?>"
								class="sui-toggle"
								style="margin: 0;"
							>
								<input
									type="checkbox"
									name="tracking_sub_types[]"
									value="<?php echo esc_attr( $chart_key ); ?>"
									id="hustle-module-tracking--<?php echo esc_attr( $chart_key ); ?>"
									{{ _.checked( _.contains( enabledTrackings, '<?php echo esc_attr( $chart_key ); ?>' ), true ) }}
									aria-labelledby="hustle-module-tracking--<?php echo esc_attr( $chart_key ); ?>-label"
								/>
								<span aria-hidden="true" class="sui-toggle-slider" aria-hidden="true"></span>

								<?php /* translators: display type in small caps and in singular. */ ?>
								<span id="hustle-module-tracking--<?php echo esc_attr( $chart_key ); ?>-label" class="sui-screen-reader-text"><?php printf( esc_html__( 'Enable %s tracking', 'hustle' ), esc_html( $chart ) ); ?></span>
							</label>

						</div>

					</td>

				</tr>

			<?php endforeach; ?>

		</tbody>

	</table>

</script>