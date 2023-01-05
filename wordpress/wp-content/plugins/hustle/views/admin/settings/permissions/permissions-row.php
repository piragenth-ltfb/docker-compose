<?php
/**
 * Permissions row for the "permissions" tab.
 *
 * @package Hustle
 * @since 4.1.0
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php echo esc_html( $label ); ?></span>
		<span class="sui-description"><?php echo esc_html( $description ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">
		<select
			form="<?php echo esc_attr( $form_id ); ?>"
			class="hustle-update-field-ajax sui-select"
			name="<?php echo esc_attr( $input_name ); ?>"
			multiple
		>

			<?php
			foreach ( $roles as $value => $label ) {
				$admin = Opt_In_Utils::is_admin_role( $value );
				printf(
					'<option value="%s" %s %s>%s</option>',
					esc_attr( $value ),
					selected( in_array( $value, $current_value, true ) || $admin, true, false ),
					disabled( $admin, true, false ),
					esc_html( $label )
				);
			}
			?>

		</select>
	</div>

</div>