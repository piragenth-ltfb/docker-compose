<?php
/**
 * Submissions behavior section.
 *
 * @package Hustle
 * @since 4.0.0
 */

// Tinymce editor styles.
ob_start();
require Opt_In::$plugin_path . 'assets/css/sui-editor.min.css';
$editor_css = ob_get_clean();
$editor_css = '<style>' . $editor_css . '</style>';

// Success message tab content.
ob_start();
?>
<div class="sui-form-field">

	<label class="sui-label sui-label-editor"><?php esc_html_e( 'Message', 'hustle' ); ?></label>

	<?php
	wp_editor(
		$settings['success_message'],
		'success_message',
		array(
			'media_buttons'    => false,
			'textarea_name'    => 'success_message',
			'editor_css'       => $editor_css,
			'tinymce'          => array(
				'content_css' => self::$plugin_url . 'assets/css/sui-editor.min.css',
			),
			'editor_height'    => 192,
			'drag_drop_upload' => false,
		)
	);
	?>

</div>

<div id="section-auto-close-success-message" class="sui-form-field">

	<label class="sui-label"><?php esc_html_e( 'Auto close success message', 'hustle' ); ?></label>

	<select name="auto_close_success_message" class="sui-select" data-attribute="auto_close_success_message">
		<option value="0" <?php selected( $settings['auto_close_success_message'], '0' ); ?>>
			<?php esc_html_e( 'Never', 'hustle' ); ?>
		</option>
		<option value="1" <?php selected( $settings['auto_close_success_message'], '1' ); ?>>
			<?php esc_html_e( 'After specified time', 'hustle' ); ?>
		</option>
	</select>

	<div class="sui-row <?php echo '0' === $settings['auto_close_success_message'] ? 'sui-hidden' : ''; ?>">

		<div class="sui-col-md-6">
			<input type="number"
				name="auto_close_time"
				data-attribute="auto_close_time"
				value="<?php echo esc_attr( $settings['auto_close_time'] ); ?>"
				placeholder="0"
				min="0"
				class="sui-form-control" />
		</div>

		<div class="sui-col-md-6">
			<select class="sui-select" name="auto_close_unit" data-attribute="auto_close_unit">
				<option value="seconds" <?php selected( $settings['auto_close_unit'], 'seconds' ); ?>>
					<?php esc_html_e( 'seconds', 'hustle' ); ?>
				</option>
				<option value="minutes" <?php selected( $settings['auto_close_unit'], 'minutes' ); ?>>
					<?php esc_html_e( 'minutes', 'hustle' ); ?>
				</option>
			</select>
		</div>

	</div>

</div>
<?php
$show_success_content = ob_get_clean();

// Redirect tab content.
ob_start();
?>

<div class="sui-form-field">
	<label class="sui-label"><?php esc_html_e( 'Redirect URL', 'hustle' ); ?></label>
	<input type="url"
		name="redirect_url"
		data-attribute="redirect_url"
		value="<?php echo esc_attr( esc_url_raw( $settings['redirect_url'] ) ); ?>"
		placeholder="<?php esc_html_e( 'E.g. http://website.com', 'hustle' ); ?>"
		class="sui-form-control" />
</div>
<?php
$redirect_content = ob_get_clean();

$options = array(
	'show_success' => array(
		'value'   => 'show_success',
		'label'   => __( 'Success message', 'hustle' ),
		'boxed'   => true,
		'content' => $show_success_content,
	),
	'redirect'     => array(
		'value'   => 'redirect',
		'label'   => __( 'Redirect', 'hustle' ),
		'boxed'   => true,
		'content' => $redirect_content,
	),
);
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Submission Behavior', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Show a success message to the visitors or redirect them to another URL on successful form submission.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<?php
		$this->render(
			'admin/global/sui-components/sui-tabs',
			array(
				'name'        => 'after_successful_submission',
				'radio'       => true,
				'saved_value' => $settings['after_successful_submission'],
				'sidetabs'    => true,
				'content'     => true,
				'options'     => $options,
			)
		);
		?>

	</div>

</div>