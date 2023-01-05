<?php
/**
 * SUI Tab Options.
 *
 * @package Hustle
 * @since 4.3.0
 */

$wrapper  = 'sui-tabs';
$wrapper .= ( isset( $sidetabs ) && true === $sidetabs ) ? ' sui-side-tabs' : '';
$wrapper .= ( isset( $flushed ) && true === $flushed ) ? ' sui-tabs-flushed' : '';
$wrapper .= ( isset( $overflow ) && true === $overflow ) ? ' sui-tabs-overflow' : '';
$wrapper .= ( isset( $class ) && ! empty( $class ) ) ? ' ' . $class : '';

$radio_allowed   = ( isset( $radio ) && true === $radio ) ? true : false;
$content_allowed = ( isset( $content ) && true === $content ) ? true : false;
$content_class   = empty( $content_class ) ? '' : ' ' . $content_class;

reset( $options );
$first_tab = key( $options );
?>

<div
	<?php echo ! empty( $id ) ? 'id="' . esc_attr( $id ) . '"' : ''; ?>
	class="<?php echo esc_attr( $wrapper ); ?>"
>

	<?php
	foreach ( $options as $key => $option ) {
		$value = ( isset( $option['value'] ) && ( ! empty( $option['value'] ) || '0' === $option['value'] ) ) ? $option['value'] : $key;
		?>

		<?php if ( $radio_allowed ) { ?>
			<input
				type="radio"
				name="<?php echo esc_attr( $name ); ?>"
				value="<?php echo esc_attr( $value ); ?>"
				id="hustle-<?php echo esc_attr( $name ); ?>--<?php echo esc_attr( $key ); ?>"
				class="sui-screen-reader-text hustle-tabs-option"
				data-attribute="<?php echo esc_attr( $name ); ?>"
				aria-hidden="true"
				tabindex="-1"
				<?php checked( $saved_value, $value ); ?>
			/>
		<?php } ?>
	<?php } ?>

	<?php if ( isset( $overflow ) && true === $overflow ) { ?>

		<div
			tabindex="-1"
			class="sui-tabs-navigation"
			aria-hidden="true"
		>
			<button
				type="button"
				class="sui-button-icon sui-tabs-navigation--left"
			>
				<i class="sui-icon-chevron-left"></i>
			</button>
			<button
				type="button"
				class="sui-button-icon sui-tabs-navigation--right"
			>
				<i class="sui-icon-chevron-right"></i>
			</button>
		</div>

	<?php } ?>

	<div role="tablist" class="sui-tabs-menu">

		<?php
		foreach ( $options as $key => $option ) {

			$label      = $option['label'];
			$sui_icon   = ( isset( $option['sui-icon'] ) && ! empty( $option['sui-icon'] ) ) ? $option['sui-icon'] : '';
			$hui_icon   = ( isset( $option['hui-icon'] ) && ! empty( $option['hui-icon'] ) ) ? $option['hui-icon'] : '';
			$tabcontent = ( isset( $option['content'] ) && ! empty( $option['content'] ) ) ? 'tab-content-' . $name . '-' . $key : '';

			$has_sui_icon = ( isset( $option['sui-icon'] ) && ! empty( $option['sui-icon'] ) ) ? true : false;
			$has_hui_icon = ( isset( $option['hui-icon'] ) && ! empty( $option['hui-icon'] ) ) ? true : false;
			$has_content  = ( isset( $option['content'] ) && ! empty( $option['content'] ) ) ? true : false;

			$button_class     = ( ! $radio_allowed && $key === $first_tab ) ? 'sui-tab-item active' : 'sui-tab-item';
			$button_selected  = ( ! $radio_allowed && $key === $first_tab ) ? 'true' : 'false';
			$button_controls  = $tabcontent;
			$button_index     = ( 'false' === $button_selected ) ? '-1' : '';
			$button_label_for = 'hustle-' . $name . '--' . $key;
			?>

			<button
				role="tab"
				type="button"
				id="tab-<?php echo esc_attr( $name ); ?>-<?php echo esc_attr( $key ); ?>"
				class="<?php echo esc_attr( $button_class ); ?>"
				aria-selected="<?php echo esc_attr( $button_selected ); ?>"
				<?php echo ( $content_allowed && $has_content ) ? 'aria-controls="' . esc_attr( $button_controls ) . '"' : ''; ?>
				<?php echo ( 'false' === $button_selected ) ? 'tabindex="' . esc_attr( $button_index ) . '"' : ''; ?>
				<?php echo $radio_allowed ? 'data-label-for="' . esc_attr( $button_label_for ) . '"' : ''; ?>
			>
				<?php
				if ( $has_sui_icon || $has_hui_icon ) {

					if ( $has_sui_icon ) {
						$icon_size = ! empty( $option['icon-size'] ) ? ' sui-' . $option['icon-size'] : '';
						echo '<span class="sui-icon-' . esc_attr( $sui_icon ) . esc_attr( $icon_size ) . '" aria-hidden="true" style="pointer-events: none;"></span>';
					} elseif ( $has_hui_icon ) {
						$icon_size = ! empty( $option['icon-size'] ) ? ' hui-' . $option['icon-size'] : '';
						echo '<span class="hui-icon-' . esc_attr( $hui_icon ) . esc_attr( $icon_size ) . '" aria-hidden="true" style="pointer-events: none;"></span>';
					}

					echo '<span class="sui-screen-reader-text">' . esc_html( $label ) . '</span>';

				} else {
					echo esc_html( $label );
				}
				?>
			</button>

		<?php } ?>

	</div>

	<?php if ( $content_allowed ) { ?>

		<div class="sui-tabs-content<?php echo esc_attr( $content_class ); ?>">

			<?php
			foreach ( $options as $key => $option ) {

				$tabclass  = 'sui-tab-content';
				$tabclass .= ( isset( $option['boxed'] ) && true === $option['boxed'] ) ? ' sui-tab-boxed' : '';
				$tabclass .= ( isset( $option['class'] ) && ! empty( $option['class'] ) ) ? ' ' . $option['class'] : '';

				if ( ! $radio_allowed && $key === $first_tab ) {
					$tabclass .= ' active';
				}

				$has_content = ( isset( $option['content'] ) && ! empty( $option['content'] ) ) ? true : false;
				?>

				<?php if ( $has_content ) { ?>

					<div
						role="tabpanel"
						tabindex="0"
						id="tab-content-<?php echo esc_attr( $name ); ?>-<?php echo esc_attr( $key ); ?>"
						class="<?php echo esc_attr( $tabclass ); ?>"
						aria-labelledby="tab-<?php echo esc_attr( $name ); ?>-<?php echo esc_attr( $key ); ?>"
						<?php echo ( ! $radio_allowed && $key === $first_tab ) ? '' : 'hidden'; ?>
					>
						<?php echo $option['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>

				<?php } ?>

			<?php } ?>

		</div>

	<?php } ?>

</div>