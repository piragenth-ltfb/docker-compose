<?php
/**
 * Templates for different field types and elements.
 * Markup for us, frontend dummies.
 * These are the variables that are used in most of the elements this file:
 *
 * $type          string   The field/element type.
 *                         select|checkboxes|checkbox|{anything else as a regular input}
 * $is_template   bool     Whether the field is used in an underscore template. Default: false.
 * $id            string   ID property of the field.
 * $class         string   Classes of the field.
 * $name          string   Name of the field. For $is_template, it should
 *                         have the same name as the js property that contains its value.
 * $attributes    array    Associative array with other properties for the field. @see Hustle_Layout_Helper::render_attributes().
 *
 *
 * $options       array    Set of options for rendering the element. Used by select|checkboxes|side_tabs.
 *                         For select and checkboxes, it's an associative array where the key of the pair is the
 *                         option's "value" property, and the value of the pair is the displayed label of the option. .
 *                         For side_tabs, read more on the element's section.
 *
 * $selected      string   Used only if ! $is_template. The current stored value of the field. Must match the
 *                |array  'key' of its respective option pair in the $options array.
 *                         Used by select|checkboxes|checkbox|checkbox_toggle|side_tabs.
 *
 *
 * $value         string   Value of the field.
 *                         Make sure it's properly escaped when rendering 'inline_notice'.
 *
 * $label         string   Label for the input. Used by checkbox|checkbox_toggle.
 * $description   string   Description for the input. Used by checkbox|checkbox_toggle.
 *
 * $placeholder   string   TO BE DEPRECATED favoring accessibility. Placeholder of the field.
 * $icon          string   Name of the icon as per SUI names. Used by text|number.
 * $icon_position string   Whether the icon goes before or after the input. Allowed values: before|after.
 *
 * $elements      array    Array with the options to render withing the wrapper. Used by wrapper.
 *
 * $tag           string   Tag name for inline HTML elements. Used by inline_element.
 *
 * NOTE: enable phpcs when editing stuff. Make sure what's left is okay. Disable it again afterwards.
 *
 * @package Hustle
 * @since 4.2.0
 * @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 */

// Flag for when the option is used in underscore template files.
$is_template = isset( $is_template ) ? $is_template : false;
$attributes  = isset( $attributes ) ? $attributes : array();

$label_attributes = isset( $label_attributes ) ? $label_attributes : array();

switch ( $type ) :

	// ELEMENT: Wrapper div.
	case 'wrapper':
		?>
		<div
			<?php echo empty( $id ) ? '' : 'id="' . esc_attr( $id ) . '"'; ?>
			<?php echo empty( $class ) ? '' : 'class="' . esc_attr( $class ) . '"'; ?>
			<?php $this->render_attributes( $attributes ); ?>
		>
			<?php
			foreach ( $elements as $element ) {
				$this->render( 'admin/commons/options', $element );
			}
			?>
		</div>

		<?php
		break;

	// ELEMENT: Select.
	case 'select':
		if ( self::$dont_init_selects &&
			0 !== strpos( $name, 'custom_height_unit' ) && 0 !== strpos( $name, 'custom_width_unit' ) ) {
			$new_class = ' none-sui';
			$class     = empty( $class ) ? $new_class : $class . $new_class;
		}
		?>
		<select
			id="<?php echo empty( $id ) ? 'hustle-select-' . esc_attr( $name ) : esc_attr( $id ); ?>"
			name="<?php echo esc_attr( $name ); ?>"
			<?php echo empty( $class ) ? '' : 'class="' . esc_attr( $class ) . '"'; ?>
			<?php echo empty( $placeholder ) ? '' : 'data-placeholder="' . esc_attr( $placeholder ) . '"'; ?>
			<?php $this->render_attributes( $attributes ); ?>
			tabindex="-1"
			aria-hidden="true"
		>

			<?php if ( ! empty( $placeholder ) ) : ?>
				<option></option>
				<?php
			endif;

			// Fully server's side rendered field.
			if ( ! $is_template ) :

				foreach ( $options as $value => $label ) :
					$label     = ! empty( $label ) ? $label : '&#8205;';
					$_selected = is_array( $selected ) && empty( $selected ) ? '' : $selected;
					?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $_selected, $value ); ?>>
						<?php echo esc_html( $label ); ?>
					</option>
					<?php
				endforeach;

			else :

				foreach ( $options as $value => $label ) :
					?>
					<option value="<?php echo esc_attr( $value ); ?>" {{ _.selected( <?php echo $name; ?>, '<?php echo $value; ?>' ) }}>
						<?php echo esc_html( $label ); ?>
					</option>
					<?php
				endforeach;

			endif;
			?>

		</select>

		<?php
		break;

	// ELEMENT: Multiple checkboxes.
	case 'checkboxes':
		// Fully server's side rendered field.
		if ( ! $is_template ) :

			$_selected = isset( $selected ) ? $selected : array();
			if ( ! is_array( $_selected ) ) {
				$_selected = array( $_selected );
			}

			foreach ( $options as $value => $label ) :
				?>

				<label class="sui-checkbox <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>">

					<input
						type="checkbox"
						name="<?php echo esc_attr( $name ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
						<?php echo isset( $id ) ? 'id="' . esc_attr( $id . '-' . $value ) . '"' : ''; ?>
						<?php $this->render_attributes( $attributes ); ?>
						<?php checked( in_array( $value, $_selected, true ) ); ?>
					/>

					<span aria-hidden="true"></span>

					<span><?php echo esc_html( $label ); ?></span>

				</label>

				<?php
			endforeach;

		else : // Field expecting parameters from underscore templating.

			foreach ( $options as $value => $label ) :
				?>

				<label class="sui-checkbox <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>">

					<input
						type="checkbox"
						name="<?php echo esc_attr( $name ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
						<?php echo isset( $id ) ? 'id="' . esc_attr( $id . '-' . $value ) . '"' : ''; ?>
						<?php $this->render_attributes( $attributes ); ?>
						{{ _.checked( <?php echo $name; ?>.includes( '<?php echo $value; ?>' ), true ) }}
					/>

					<span aria-hidden="true"></span>
					<span><?php echo esc_html( $label ); ?></span>

				</label>

				<?php
			endforeach;

		endif;
		break;

	// ELEMENT: Checkbox.
	case 'checkbox':
		// If $value is not set, this is an on/off checkbox.
		if ( ! isset( $value ) ) {
			$_checked = ! $is_template ? checked( '1', $selected, false ) : '{{ _.checked( "1", ' . $name . ' ) }}';
		} else {
			$_checked = ! $is_template ? checked( $value, $selected, false ) : '{{ _.checked( "' . $value . '", ' . $name . ' ) }}';
		}
		?>

		<label class="sui-checkbox <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>" <?php $this->render_attributes( $label_attributes ); ?>>

			<input
				type="checkbox"
				name="<?php echo esc_attr( $name ); ?>"
				<?php echo isset( $value ) ? 'value="' . esc_attr( $value ) . '"' : ''; ?>
				<?php echo isset( $id ) ? 'id="' . esc_attr( $id . '-' . $value ) . '"' : ''; ?>
				aria-labelledby="hustle-checkbox-<?php echo esc_attr( $name ); ?>-label"
				<?php echo empty( $description ) ? '' : 'aria-describedby="hustle-checkbox-' . esc_attr( $name ) . '-description"'; ?>
				<?php $this->render_attributes( $attributes ); ?>
				<?php echo $_checked; ?>
			/>
			<span aria-hidden="true"></span>
			<span id="hustle-checkbox-<?php echo esc_attr( $name ); ?>-label"><?php echo wp_kses_post( $label ); ?></span>

			<?php if ( ! empty( $description ) ) : ?>
				<span id="hustle-checkbox-<?php echo esc_attr( $name ); ?>-description" class="sui-description"><?php echo esc_html( $description ); ?></span>
			<?php endif; ?>

		</label>

		<?php
		break;

	// ELEMENT: Toggle checkbox.
	case 'checkbox_toggle':
		if ( is_array( $selected ) ) {
			$_checked = checked( in_array( $value, $selected, true ), true, false );
		} else {
			$_checked = ! $is_template ? checked( $value, $selected, false ) : '{{ _.checked( "' . $value . '", ' . $name . ' ) }}';
		}
		?>

		<label
			class="sui-toggle <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>"
			<?php echo empty( $id ) ? '' : 'id="' . esc_attr( $id ) . '"'; ?>
			<?php $this->render_attributes( $label_attributes ); ?>
		>
			<input
				type="checkbox"
				name="<?php echo esc_attr( $name ); ?>"
				aria-labelledby="hustle-toggle-<?php echo esc_attr( $name ); ?>-label"
				<?php echo empty( $value ) ? '' : 'value="' . esc_attr( $value ) . '"'; ?>
				<?php echo empty( $description ) ? '' : 'aria-describedby="hustle-toggle-' . esc_attr( $name ) . '-description"'; ?>
				<?php $this->render_attributes( $attributes ); ?>
				<?php echo $_checked; ?>
			/>
			<span class="sui-toggle-slider" aria-hidden="true"></span>

			<span id="hustle-toggle-<?php echo esc_attr( $name ); ?>-label" class="sui-toggle-label"><?php echo esc_html( $label ); ?></span>

			<?php if ( ! empty( $description ) ) : ?>
				<span id="hustle-toggle-<?php echo esc_attr( $name ); ?>-description" class="sui-description"><?php echo esc_html( $description ); ?></span>
			<?php endif; ?>
		</label>

		<?php
		break;

	case 'side_tabs':
		/**
		 * $options is an array, containing another array for each tab.
		 * This is the structure for each tab's array:
		 *
		 * Array(
		 *  'value'         => string Input's value.
		 *  'label'         => string Tab's label.
		 *  'has_content'   => bool   Optional. Default: false. Whether the tab has dependent content.
		 *  'content_id'    => string Optional. ID of the dependent content IF 'content_htlm' isn't provided.
		 *  'content_html'  => string Optional. Markup for the dependent content. Skip if 'content_id' is provided.
		 *  'content_label' => string Optional. Screen reader label for the content when 'content_html' is specified.
		 * )
		 */
		$tabs_attributes = empty( $tabs_attributes ) ? array() : $tabs_attributes;
		?>
		<div class="sui-tabs sui-side-tabs <?php echo empty( $class ) ? '' : esc_attr( $class ); ?>">

			<?php foreach ( $options as $data ) { ?>
				<?php $_checked = ! $is_template ? checked( $data['value'], $selected, false ) : '{{ _.checked( "' . $data['value'] . '", ' . $name . ' ) }}'; ?>

				<input
					type="radio"
					name="<?php echo esc_attr( $name ); ?>"
					value="<?php echo esc_attr( $data['value'] ); ?>"
					id="hustle-<?php echo esc_attr( $name ); ?>--<?php echo esc_attr( $data['value'] ); ?>"
					class="sui-screen-reader-text hustle-tabs-option"
					aria-hidden="true"
					tabindex="-1"
					<?php $this->render_attributes( $attributes ); ?>
					<?php echo $_checked; ?>
				/>

			<?php } ?>

			<div role="tablist" class="sui-tabs-menu">

				<?php foreach ( $options as $data ) { ?>
					<?php
					if ( $data['has_content'] ) {
						$tab_content_id = ! empty( $data['content_id'] ) ? $data['content_id'] : 'tab-content-' . $name . '-' . $data['value'] . '-settings';
					}
					?>

					<button
						role="tab"
						type="button"
						id="tab-<?php echo esc_attr( $name ); ?>--<?php echo esc_attr( $data['value'] ); ?>"
						class="sui-tab-item"
						data-label-for="hustle-<?php echo esc_attr( $name ); ?>--<?php echo esc_attr( $data['value'] ); ?>"
						<?php echo empty( $tab_content_id ) ? '' : ' aria-controls="' . esc_attr( $tab_content_id ) . '"'; ?>
						aria-selected="false"
						tabindex="-1"
					><?php echo esc_html( $data['label'] ); ?></button>

				<?php } ?>

			</div>

			<div class="sui-tabs-content">

				<?php
				foreach ( $options as $data ) :

					if ( empty( $data['content_html'] ) || empty( $data['has_content'] ) ) {
						continue;
					}

					$tab_content_id = ! empty( $data['content_id'] ) ? $data['content_id'] : 'tab-content-' . $name . '-' . $data['value'] . '-settings';
					?>
					<div
						role="tabpanel"
						tabindex="-1"
						id="<?php echo esc_attr( $tab_content_id ); ?>"
						class="sui-tab-content"
						aria-label="<?php echo esc_attr( $data['content_label'] ); ?>"
						<?php $this->render_attributes( $tabs_attributes ); ?>
					>
						<?php echo $data['content_html']; ?>
					</div>

				<?php endforeach; ?>

			</div>

		</div>

		<?php
		break;

	// ELEMENT: Simple inline element.
	case 'inline_element':
		?>
		<<?php echo esc_attr( $tag ); ?>
			<?php echo empty( $id ) ? '' : 'id="' . esc_attr( $id ) . '"'; ?>
			<?php echo empty( $class ) ? '' : 'class="' . esc_attr( $class ) . '"'; ?>
			<?php $this->render_attributes( $attributes ); ?>
		>
			<?php echo wp_kses_post( $value ); ?>
		</<?php echo esc_attr( $tag ); ?>>
		<?php
		break;

	// ELEMENT: Inline notice.
	case 'inline_notice':
		// We're assuming that if there's no value, this is an inline alert notice, not a static one.
		$is_alert = empty( $value );
		?>

		<div
			<?php echo ! $is_alert ? '' : 'role="alert" aria-live="assertive"'; ?>
			<?php echo ! empty( $id ) ? 'id="' . esc_attr( $id ) . '"' : ''; ?>
			class="sui-notice <?php echo isset( $class ) ? esc_attr( $class ) : ''; ?>"
			<?php $this->render_attributes( $attributes ); ?>
		>

			<?php if ( ! $is_alert ) : ?>

				<div class="sui-notice-content">

					<div class="sui-notice-message">

						<?php if ( ! empty( $icon ) ) : ?>
							<span class="sui-notice-icon sui-icon-<?php echo esc_attr( $icon ); ?> sui-md" aria-hidden="true"></span>
						<?php endif; ?>
						<p><?php echo $value; // Make sure $value is properly escaped! We're not escaping it in here. ?></p>

					</div>

				</div>

			<?php endif; ?>

		</div>

		<?php
		break;

	// ELEMENT: Simple input.
	default:
		$_value = ! $is_template ? $value : '{{' . $name . '}}';

		if ( isset( $icon ) ) :
			?>
			<div class="sui-control-with-icon">
			<?php if ( 'before' === $icon_position ) : ?>
				<span class="sui-icon-<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></span>
			<?php endif; ?>
		<?php endif; ?>

			<input
				type="<?php echo esc_attr( $type ); ?>"
				name="<?php echo esc_attr( $name ); ?>"
				value="<?php echo esc_attr( $_value ); ?>"
				<?php echo ( 'number' === $type && isset( $min ) && '' !== $min ) ? 'min="' . esc_attr( $min ) . '"' : ''; ?>
				<?php echo ( 'number' === $type && isset( $max ) && '' !== $max ) ? 'max="' . esc_attr( $max ) . '"' : ''; ?>
				class="sui-form-control<?php echo isset( $class ) ? ' ' . esc_attr( $class ) : ''; ?>"
				<?php $this->render_attributes( $attributes ); ?>
				<?php echo isset( $id ) ? 'id="' . esc_attr( $id ) . '"' : ''; ?>
				<?php echo isset( $placeholder ) ? 'placeholder="' . esc_attr( $placeholder ) . '"' : ''; ?>
			/>

		<?php if ( isset( $icon ) ) : ?>
			<?php if ( 'after' === $icon_position ) : ?>
				<i class="sui-icon-<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
			<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php
endswitch;