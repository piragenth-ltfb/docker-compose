<?php
/**
 * Main content section.
 *
 * @package Hustle
 * @since 4.0.0
 */

ob_start();

require Opt_In::$plugin_path . 'assets/css/sui-editor.min.css';
$editor_css = ob_get_clean();
$editor_css = '<style>' . $editor_css . '</style>';
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-2">

		<span class="sui-settings-label sui-dark"><?php esc_html_e( 'Main Content', 'hustle' ); ?></span>

		<?php
		wp_editor(
			$main_content,
			'main_content',
			array(
				'media_buttons'    => true,
				'textarea_name'    => 'main_content',
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

</div>