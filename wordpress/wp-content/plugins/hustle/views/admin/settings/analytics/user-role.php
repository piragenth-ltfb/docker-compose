<?php
/**
 * Users roles section under the "analytics" tab.
 *
 * @package Hustle
 * @since 4.2.0
 */

global $wp_roles;
$roles       = Opt_In_Utils::get_user_roles();
$admin_roles = array();
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'User Role', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Choose the user roles you want to make the analytics widget available to.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">
		<select class="sui-select" name="role[]" multiple>
			<?php foreach ( $roles as $slug => $label ) { ?>
				<?php $admin = Opt_In_Utils::is_admin_role( $slug ); ?>
			<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( in_array( $slug, $value, true ) || $admin ); ?> <?php disabled( $admin ); ?> ><?php echo esc_html( $label ); ?></option>
			<?php } ?>
		</select>
	</div>

</div>