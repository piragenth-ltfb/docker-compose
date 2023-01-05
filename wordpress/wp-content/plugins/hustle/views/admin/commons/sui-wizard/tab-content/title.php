<?php
/**
 * Title section.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Title', 'hustle' ); ?></span>
		<?php /* translators: module type in small caps and in singular */ ?>
		<span class="sui-description"><?php printf( esc_html__( 'Add a title and a subtitle to your %s.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<div class="sui-form-field">

			<label for="hustle_module_title" class="sui-label"><?php esc_html_e( 'Title (optional)', 'hustle' ); ?></label>
			<input type="text"
				name="title"
				placeholder="<?php esc_html_e( 'E.g. Weekly Newsletter', 'hustle' ); ?>"
				value="<?php echo esc_attr( $settings['title'] ); ?>"
				id="hustle_module_title"
				class="sui-form-control"
				data-attribute="title" />

		</div>

		<div class="sui-form-field">

			<label for="hustle_module_sub_title" class="sui-label"><?php esc_html_e( 'Subtitle (optional)', 'hustle' ); ?></label>
			<input type="text"
				name="sub_title"
				placeholder="<?php esc_html_e( "E.g. You don't want to miss this offer.", 'hustle' ); ?>"
				value="<?php echo esc_attr( $settings['sub_title'] ); ?>"
				data-attribute="sub_title"
				id="hustle_module_sub_title"
				class="sui-form-control" />

		</div>

	</div>

</div>