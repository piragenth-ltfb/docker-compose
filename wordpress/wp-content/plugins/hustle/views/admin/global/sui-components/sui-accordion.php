<?php
/**
 * SUI Accordion
 *
 * @package Hustle
 * @since 4.3.0
 */

$class  = 'sui-accordion';
$class .= ( isset( $flushed ) && true === $flushed ) ? ' sui-accordion-flushed' : '';
?>

<div class="<?php echo esc_attr( $class ); ?>">

	<?php foreach ( $options as $k => $option ) { ?>
		<div
			class="sui-accordion-item<?php echo ! empty( $option['hidden'] ) ? ' sui-hidden' : ''; ?>"
			data-name="<?php echo esc_attr( $option['key'] ); ?>"
		>
			<div class="sui-accordion-item-header">

				<div class="sui-accordion-item-title">
					<?php echo esc_html( $option['title'] ); ?>
					<?php echo ! empty( $option['notes'] ) ? '<span style="margin-left: 5px; color: #888;" aria-hidden="true">|</span>' : ''; ?>
					<?php echo ! empty( $option['notes'] ) ? '<span class="sui-accordion-note" style="margin-left: 5px; color: #888;">' . esc_html( $option['notes'] ) . '</span>' : ''; ?>
					<button type="button" class="sui-button-icon sui-accordion-open-indicator" aria-label="<?php esc_html_e( 'Open item', 'hustle' ); ?>"><span class="sui-icon-chevron-down" aria-hidden="true"></span></button>
				</div>

			</div>

			<div class="sui-accordion-item-body">

				<?php echo $option['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

			</div>

		</div>

	<?php } ?>

	<?php if ( isset( $reset ) && true === $reset ) { ?>

		<div class="sui-accordion-footer">

			<div class="sui-accordion-col-12 hustle-reset-settings-block">

				<?php
				$this->render(
					'admin/global/sui-components/sui-button',
					array(
						'type'     => 'button',
						'label'    => esc_html__( 'Reset', 'hustle' ),
						'outlined' => true,
					)
				);
				?>

			</div>

		</div>

	<?php } ?>

</div>