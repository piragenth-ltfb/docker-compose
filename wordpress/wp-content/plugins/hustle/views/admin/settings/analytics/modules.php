<?php
/**
 * Modules types section under the "analytics" tab.
 *
 * @package Hustle
 * @since 4.2.0
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Modules', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Select the Hustle modules for which the selected User Roles will see analytics in their WordPress Admin area.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<?php
		$checkboxes = array(
			'overall'        => __( 'Overall Analytics', 'hustle' ),
			'popup'          => __( 'Pop-ups', 'hustle' ),
			'slidein'        => __( 'Slide-ins', 'hustle' ),
			'embedded'       => __( 'Embeds', 'hustle' ),
			'social_sharing' => __( 'Social Share', 'hustle' ),
		);

		foreach ( $checkboxes as $value => $label ) {
			?>
			<label class="sui-checkbox sui-checkbox-stacked">
				<input type="checkbox" value="<?php echo esc_attr( $value ); ?>" name="modules[]" <?php echo in_array( $value, $values, true ) ? ' checked="checked"' : ''; ?> />
				<span></span>
				<span class="sui-description"><?php echo esc_html( $label ); ?></span>
			</label>
		<?php } ?>

	</div>

</div>