<?php
/**
 * Template for rendering some markup elements.
 *
 * @package Hustle
 * @since 4.0.0
 */

$element_type = strtolower( $type );
$type_class   = 'optin_' . $element_type . '_' . $element_type . ' ' . $element_type;
$for          = ( isset( $for ) ) ? $for : '';
$attributes   = isset( $attributes ) ? $attributes : array();

// FIELD TYPE: Label.
if ( 'label' === $element_type ) { ?>
	<label
		<?php echo isset( $for ) ? 'for="' . esc_attr( $for ) . '"' : ''; ?>
		class="<?php echo isset( $class ) ? esc_attr( $class ) : 'sui-label'; ?>"
		<?php $this->render_attributes( isset( $attributes ) ? $attributes : array() ); ?>
	>
		<?php echo $value; // phpcs:ignore ?>
		<?php if ( isset( $note ) && ! empty( $note ) ) { ?>
			<span class="sui-label-note"><?php echo $note; // phpcs:ignore ?></span>
		<?php } ?>
	</label>

	<?php
	// FIELD TYPE: Description.
} elseif ( 'description' === $element_type ) {
	?>
	<span class="sui-description"><?php echo $value; // phpcs:ignore ?></span>

	<?php
	// FIELD TYPE: Notice.
} elseif ( 'notice' === $element_type ) {
	?>

	<div
		<?php echo ! empty( $id ) ? 'id="' . esc_attr( $id ) . '"' : ''; ?>
		class="sui-notice <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>"
		<?php $this->render_attributes( $attributes ); ?>
	>

		<div class="sui-notice-content">

			<div class="sui-notice-message">

				<?php if ( ! empty( $icon ) ) : ?>
					<span class="sui-notice-icon sui-icon-<?php echo esc_attr( $icon ); ?> sui-md" aria-hidden="true"></span>
				<?php endif; ?>
				<p><?php echo $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, Make sure $value is properly escaped! We're not escaping it in here. ?></p>

			</div>

		</div>

	</div>

	<?php
	// FIELD TYPE: Textarea.
} elseif ( 'textarea' === $element_type ) {
	?>
	<textarea <?php $this->render_attributes( isset( $attributes ) ? $attributes : array() ); ?>
		name="<?php echo esc_attr( $name ); ?>"
		id="<?php echo esc_attr( $id ); ?>"
		cols="30" rows="10"><?php echo esc_textarea( $value ? $value : $default ); ?></textarea>

	<?php
	// FIELD TYPE: Select.
} elseif ( 'select' === $element_type ) {
	?>
	<select
		<?php echo empty( $name ) ? '' : 'name="' . esc_attr( $name ) . '"'; ?>
		<?php echo empty( $id ) ? '' : 'id="' . esc_attr( $id ) . '"'; ?>
		<?php echo empty( $class ) ? '' : 'class="' . esc_attr( $class ) . '"'; ?>
		<?php echo empty( $nonce ) ? '' : 'data-nonce="' . esc_attr( $nonce ) . '"'; ?>
		<?php $this->render_attributes( isset( $attributes ) ? $attributes : array() ); ?>
	>
		<?php
		foreach ( $options as $value => $label ) :
			$label     = ! empty( $label ) ? $label : '&#8205;';
			$_selected = is_array( $selected ) && empty( $selected ) ? '' : $selected;
			?>
			<option <?php selected( $_selected, $value ); ?> value="<?php echo esc_attr( $value ); ?>"><?php echo esc_attr( $label ); ?></option>
		<?php endforeach; ?>
	</select>

	<?php
	// FIELD TYPE: Multiple Select.
} elseif ( 'multiselect' === $element_type ) {
	?>
	<select
		<?php echo empty( $name ) ? '' : 'name="' . esc_attr( $name ) . '"'; ?>
		<?php echo empty( $id ) ? '' : 'id="' . esc_attr( $id ) . '"'; ?>
		<?php echo empty( $class ) ? '' : 'class="' . esc_attr( $class ) . '"'; ?>
		<?php $this->render_attributes( isset( $attributes ) ? $attributes : array() ); ?>
	>
		<?php
		$_selected = empty( $selected ) ? array() : $selected;
		foreach ( $options as $value => $label ) :
			$label    = ! empty( $label ) ? $label : '&#8205;';
			$selected = is_array( $_selected ) && in_array( absint( $value ), $_selected, true ) ? 'selected' : '';
			?>
				<option  <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $value ); ?>"><?php echo esc_attr( $label ); ?></option>
		<?php endforeach; ?>
	</select>
	<?php
	// FIELD TYPE: Link.
} elseif ( 'link' === $element_type ) {
	?>
	<a <?php $this->render_attributes( isset( $attributes ) ? $attributes : array() ); ?>
		href="<?php echo esc_url( $href ); ?>"
		target="<?php echo isset( $target ) ? esc_attr( $target ) : '_self'; ?>"
		id="<?php echo isset( $id ) ? esc_attr( $id ) : ''; ?>"
		class="<?php echo esc_attr( $type_class ); ?> <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>"
			<?php echo isset( $style ) ? 'style="' . esc_attr( $style ) . '"' : ''; ?>><?php echo esc_html( $text ); ?></a>

	<?php
	// FIELD TYPE: Wrapper.
} elseif ( 'wrapper' === $element_type ) {
	?>
	<div
		<?php $this->render_attributes( isset( $attributes ) ? $attributes : array() ); ?>
		<?php echo isset( $id ) ? 'id="' . esc_attr( $id ) . '"' : ''; ?>
		class="
		<?php
		if ( empty( $is_not_field_wrapper ) ) {
			echo 'sui-form-field ';}
		?>
	<?php
	if ( isset( $class ) ) {
			echo esc_attr( $class );}
	?>
"
		<?php echo isset( $style ) ? 'style="' . esc_attr( $style ) . '"' : ''; ?>
	>
		<?php
		foreach ( (array) $elements as $element ) {
			$this->render( 'general/option', $element );
		}
		?>
	</div>

	<?php
	// FIELD TYPE: Radio (Group).
} elseif ( 'radios' === $element_type ) {
	$_selected = -1;

	if ( isset( $default ) ) {
		$_selected = $default;
	}

	if ( isset( $selected ) ) {
		$_selected = $selected;
	}

	if ( is_array( $_selected ) && empty( $_selected ) ) {
		$_selected = '';
	}

	foreach ( $options as $value => $label ) {
		$label_before = isset( $label_before ) ? $label_before : false;
		?>

		<label
			<?php echo isset( $field_id ) ? 'for="' . esc_attr( $field_id ) . '"' : ''; ?>
			class="sui-radio<?php echo isset( $class ) ? ' ' . esc_attr( $class ) : ''; ?>"
		>

			<input
				type="radio"
				<?php echo isset( $name ) ? 'name="' . esc_attr( $name ) . '"' : ''; ?>
				<?php echo 'value="' . esc_attr( $value ) . '"'; ?>
				<?php echo isset( $field_id ) ? 'id="' . esc_attr( $field_id ) . '-' . esc_attr( str_replace( ' ', '-', strtolower( $value ) ) ) . '"' : ''; ?>
				<?php $this->render_attributes( isset( $item_attributes ) ? $item_attributes : array() ); ?>
				<?php selected( $_selected, $value ); ?>
			/>

			<span aria-hidden="true"></span>

			<?php echo ! empty( $label ) ? '<span>' . esc_html( $label ) . '</span>' : ''; ?>

		</label>

	<?php } ?>

	<?php
	// FIELD TYPE: Radio.
} elseif ( 'radio' === $element_type ) {
	?>

	<label
		<?php echo isset( $id ) ? 'for="' . esc_attr( $id ) . '"' : ''; ?>
		class="sui-radio<?php echo isset( $class ) ? ' ' . esc_attr( $class ) : ''; ?>"
	>
		<input
			type="radio"
			<?php echo isset( $name ) ? 'name="' . esc_attr( $name ) . '"' : ''; ?>
			<?php echo isset( $value ) ? 'value="' . esc_attr( $value ) . '"' : ''; ?>
			<?php echo isset( $id ) ? 'id="' . esc_attr( $id ) . '"' : ''; ?>
			<?php $this->render_attributes( isset( $attributes ) ? $attributes : array() ); ?>
		/>
		<span aria-hidden="true"></span>
		<?php echo isset( $label ) ? '<span>' . esc_html( $label ) . '</span>' : ''; ?>
	</label>

	<?php
	// FIELD TYPE: Checkbox (Group).
} elseif ( 'checkboxes' === $element_type ) {

	$_selected = -1;

	if ( isset( $default ) ) {
		$_selected = $default;
	}

	if ( isset( $selected ) ) {
		$_selected = $selected;
	}

	foreach ( $options as $value => $label ) {

		$id      = esc_attr( $id . "-" . str_replace( " ", "-", strtolower( $value ) ) ); // phpcs:ignore
		$checked = is_array( $_selected ) ? in_array( $value, $_selected ) ? checked(true, true, false) : "" : checked( $_selected, $value, false ); // phpcs:ignore
		?>

		<label
			<?php echo isset( $id ) ? 'for="' . esc_attr( $id ) . '"' : ''; ?>
			class="sui-checkbox<?php echo isset( $class ) ? ' ' . esc_attr( $class ) : ''; ?>"
		>

			<input
				type="checkbox"
				<?php echo isset( $name ) ? 'name="' . esc_attr( $name ) . '"' : ''; ?>
				<?php echo 'value="' . esc_attr( $value ) . '"'; ?>
				<?php echo isset( $id ) ? 'id="' . esc_attr( $id ) . '"' : ''; ?>
				<?php $this->render_attributes( isset( $item_attributes ) ? $item_attributes : array() ); ?>
				<?php echo esc_html( $checked ); ?>
			/>

			<span aria-hidden="true"></span>

			<?php echo ! empty( $label ) ? '<span>' . esc_html( $label ) . '</span>' : ''; ?>

		</label>

	<?php } ?>

	<?php
	// FIELD TYPE: Checkbox.
} elseif ( 'checkbox' === $element_type ) {
	?>

	<label
		<?php echo isset( $id ) ? 'for="' . esc_attr( $id ) . '"' : ''; ?>
		class="sui-checkbox<?php echo isset( $class ) ? ' ' . esc_attr( $class ) : ''; ?>"
	>
		<input
			type="checkbox"
			<?php echo isset( $name ) ? 'name="' . esc_attr( $name ) . '"' : ''; ?>
			<?php echo isset( $value ) ? 'value="' . esc_attr( $value ) . '"' : ''; ?>
			<?php echo isset( $id ) ? 'id="' . esc_attr( $id ) . '"' : ''; ?>
			<?php $this->render_attributes( isset( $attributes ) ? $attributes : array() ); ?>
		/>
		<span aria-hidden="true"></span>
		<?php echo isset( $label ) ? '<span>' . esc_html( $label ) . '</span>' : ''; ?>
	</label>

	<?php
	// FIELD TYPE: Checkbox (Toggle).
} elseif ( 'checkbox_toggle' === $element_type ) {
	?>
	<label
		<?php echo isset( $id ) ? 'for="' . esc_attr( $id ) . '"' : ''; ?>
		class="sui-toggle"
	>
		<input
			type="checkbox"
			name="<?php echo esc_attr( $name ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php echo ( ! empty( $id ) && ! empty( $label ) ? 'aria-labelledby="' . esc_attr( $label ) . '-label"' : '' ); ?>
			<?php echo ( ! empty( $id ) && ! empty( $description ) ? 'aria-describedby="' . esc_attr( $description ) . '-label"' : '' ); ?>
			<?php echo isset( $id ) ? 'id="' . esc_attr( $id ) . '"' : ''; ?>
			<?php $this->render_attributes( isset( $attributes ) ? $attributes : array() ); ?>
		/>
		<span class="sui-toggle-slider" aria-hidden="true"></span>

		<?php if ( isset( $label ) && '' !== $label ) { ?>
			<span <?php echo isset( $id ) ? 'id="' . esc_attr( $id ) . '-label" ' : ''; ?>class="sui-toggle-label"><?php echo esc_html( $label ); ?></span>
		<?php } ?>
		<?php if ( isset( $description ) && '' !== $description ) { ?>
			<span <?php echo isset( $id ) ? 'id="' . esc_attr( $id ) . '-description" ' : ''; ?>class="sui-description"><?php echo esc_html( $description ); ?></span>
		<?php } ?>
	</label>

	<?php
	// FIELD TYPE: Checkbox (Toggle).
} elseif ( 'sui_tabs' === $element_type ) {
	?>

		<?php echo ! empty( $label ) ? '<span>' . esc_html( $label ) . '</span>' : ''; ?>

		<div class="sui-side-tabs" style="margin-top: 5px;">

			<div class="sui-tabs-menu">
			<?php
			foreach ( $options as $key => $option_title ) {

				$field_id = esc_attr( $name . '-' . str_replace( ' ', '-', strtolower( $key ) ) );
				?>
				<label for="hustle-<?php echo esc_html( $field_id ); ?>"
					class="sui-tab-item <?php echo $key === $value ? 'active' : ''; ?>"
				>
					<input
						type="radio"
						name="<?php echo esc_html( $name ); ?>"
						value="<?php echo esc_html( $key ); ?>"
						id="hustle-<?php echo esc_html( $field_id ); ?>"
						<?php checked( $key, $value ); ?>
					/>
					<?php echo esc_html( $option_title ); ?>
				</label>

			<?php } ?>

			</div>

			<?php if ( ! empty( $description ) ) { ?>
				<span class="sui-description"><?php echo esc_html( $description ); ?></span>
			<?php } ?>

		</div>


	<?php
	// TAG TYPE: Small.
} elseif ( 'small' === $element_type ) {
	?>
	<p><small <?php $this->render_attributes( isset( $attributes ) ? $attributes : array() ); ?> for="<?php echo esc_attr( $for ); ?>">
		<?php echo $value; // phpcs:ignore ?>
	</small></p>

	<?php
	// FIELD TYPE: Error label.
} elseif ( 'error' === $element_type ) {
	?>
	<span
		<?php $this->render_attributes( isset( $attributes ) ? $attributes : array() ); ?>
		<?php echo ( isset( $id ) ? 'id="' . esc_attr( $id ) . '"' : '' ); ?>
		class="sui-error-message<?php echo isset( $class ) ? ' ' . esc_attr( $class ) : ''; ?>"
	>
		<?php echo $value; // phpcs:ignore ?>
	</span>

	<?php
	// FIELD TYPE: Ajax button.
	// This button is not an input submit.
} elseif ( 'button' === $element_type ) {
	?>
	<button
		<?php echo isset( $name ) ? 'name="' . esc_attr( $name ) . '"' : ''; ?>
		<?php echo ( isset( $id ) ? 'id="' . esc_attr( $id ) . '"' : '' ); ?>
		class="sui-button sui-button-ghost <?php echo esc_attr( $type_class ); ?> <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>"
		<?php $this->render_attributes( isset( $attributes ) ? $attributes : array() ); ?>
	>
		<?php echo $value; // phpcs:ignore ?>
	</button>

	<?php
	// FIELD TYPE: Ajax button.
	// This button is not an input submit.
} elseif ( 'ajax_button' === $element_type ) {
	?>
	<button <?php $this->render_attributes( isset( $attributes ) ? $attributes : array() ); ?> <?php echo ( isset( $id ) ? 'id="' . esc_attr( $id ) . '"' : '' ); ?> class="hustle-onload-icon-action sui-button <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>">
	<span class="sui-loading-text"><?php echo esc_html( $value ); ?></span><span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
	</button>

	<?php
	// FIELD TYPE: Button.
} elseif ( 'submit_button' === $element_type ) {
	?>
	<button type="submit"<?php $this->render_attributes( isset( $attributes ) ? $attributes : array() ); ?> <?php echo ( isset( $id ) ? 'id="' . esc_attr( $id ) . '"' : '' ); ?> class="sui-button <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>">
   		<?php echo $value; // phpcs:ignore ?>
	</button>

	<?php
	// FIELD TYPE: Password.
} elseif ( 'password-reset' === $element_type ) {
	?>
	<div class="sui-with-button sui-with-button-icon">

		<input
			<?php $this->render_attributes( isset( $attributes ) ? $attributes : array() ); ?>
			type="password"
			<?php echo isset( $name ) ? 'name="' . esc_attr( $name ) . '"' : ''; ?>
			value="<?php echo isset( $value ) ? esc_attr( $value ) : ''; ?>"
			<?php echo isset( $placeholder ) ? 'placeholder="' . esc_attr( $placeholder ) . '"' : ''; ?>
			id="<?php echo isset( $id ) ? esc_attr( $id ) : ''; ?>"
			class="sui-form-control <?php echo esc_attr( $type_class ); ?> <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>"
		/>

		<button class="sui-button-icon">
			<span aria-hidden="true" class="sui-icon-eye"></span>
			<span class="sui-password-text sui-screen-reader-text"><?php esc_html_e( 'Show Password', 'hustle' ); ?></span>
			<span class="sui-password-text sui-screen-reader-text sui-hidden"><?php esc_html_e( 'Hide Password', 'hustle' ); ?></span>
		</button>

	</div>
	<?php
	// FIELD TYPE: Raw.
} elseif ( 'raw' === $element_type ) {
	?>
	<?php echo $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<?php } else { ?>
	<?php echo isset( $icon ) ? '<div class="sui-control-with-icon">' : ''; ?>

		<input
			<?php $this->render_attributes( isset( $attributes ) ? $attributes : array() ); ?>
			type="<?php echo esc_attr( $element_type ); ?>"
			<?php echo isset( $name ) ? 'name="' . esc_attr( $name ) . '"' : ''; ?>
			value="<?php echo isset( $value ) ? esc_attr( $value ) : ''; ?>"
			<?php echo isset( $placeholder ) ? 'placeholder="' . esc_attr( $placeholder ) . '"' : ''; ?>
			id="<?php echo isset( $id ) ? esc_attr( $id ) : ''; ?>"
			class="sui-form-control <?php echo esc_attr( $type_class ); ?> <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>"
		/>

		<?php echo isset( $icon ) ? '<span class="sui-icon-' . esc_attr( $icon ) . '" aria-hidden="true"></span>' : ''; ?>

	<?php echo isset( $icon ) ? '</div>' : ''; ?>
	<?php
}