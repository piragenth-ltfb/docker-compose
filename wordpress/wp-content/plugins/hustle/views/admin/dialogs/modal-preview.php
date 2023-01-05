<?php
/**
 * Module's preview container.
 *
 * @package Hustle
 * @since 4.3.1
 */

$url = add_query_arg(
	array(
		'hustle_preview' => 1,
		'preview'        => 1,
	),
	site_url()
);
/**
 * Filters the URL to be used for the preview.
 *
 * @since 4.3.1
 */
$url = apply_filters( 'hustle_preview_url', $url );

ob_start();
?>

<div class="hui-preview">

	<button
		class="sui-button-icon hustle-modal-close sui-tooltip sui-tooltip-bottom"
		data-tooltip="<?php esc_html_e( 'Close', 'hustle' ); ?>"
		data-modal-close
	>
		<span class="sui-icon-close sui-md" aria-hidden="true"></span>
		<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
	</button>

	<div class="hui-preview-header">

		<div class="hui-left">

			<h3 id="hustle-dialog--preview-title" class="hui-preview-title"><?php esc_html_e( 'Preview', 'hustle' ); ?></h3>

			<p id="hustle-dialog--preview-description" class="hui-preview-description"></p>

		</div>

		<div class="hui-center">
			<button
				class="hustle-preview-device-button hui-preview-button-left sui-tooltip sui-tooltip-bottom sui-active"
				aria-label="<?php esc_html_e( 'Preview on desktop', 'hustle' ); ?>"
				data-device="desktop"
				data-selected="<?php esc_html_e( 'Desktop preview enabled', 'hustle' ); ?>"
				data-tooltip="<?php esc_html_e( 'Desktop', 'hustle' ); ?>"
			>
				<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"><defs><path id="a" d="M0 0h16v16H0z"/></defs><g fill="none" fill-rule="evenodd"><path d="M11.234 15a.246.246 0 00.188-.085.313.313 0 00.078-.22v-.324a.313.313 0 00-.078-.22.246.246 0 00-.188-.085h-1.5V12.45h5.282c.27 0 .502-.104.695-.314.16-.174.254-.379.281-.612L16 11.38v-9.31c0-.295-.096-.547-.29-.757A.912.912 0 0015.017 1H.984a.912.912 0 00-.695.314C.096 1.524 0 1.776 0 2.07v9.311c0 .295.096.544.29.748a.923.923 0 00.694.305h5.282v1.632H4.625a.27.27 0 00-.195.084.297.297 0 00-.086.221v.323a.297.297 0 00.21.297l.071.009h6.61zm3.016-4.451H1.75V2.886h12.5v7.663z" fill="#888" fill-rule="nonzero"/></g></svg>
				<span class="sui-screen-reader-text"><?php esc_html_e( 'Preview on desktop', 'hustle' ); ?></span>
			</button>
			<button
				class="hustle-preview-device-button hui-preview-button-right sui-tooltip sui-tooltip-bottom"
				aria-label="<?php esc_html_e( 'Preview on mobile', 'hustle' ); ?>"
				data-device="mobile"
				data-selected="<?php esc_html_e( 'Mobile preview enabled', 'hustle' ); ?>"
				data-tooltip="<?php esc_html_e( 'Mobile', 'hustle' ); ?>"
			>
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" aria-hidden="true"><defs><path id="a" d="M0 0h16v16H0z"/></defs><g fill="none" fill-rule="evenodd"><path fill="#888" fill-rule="nonzero" d="M13.42 16a.57.57 0 00.58-.578V.578A.57.57 0 0013.42 0H2.547A.57.57 0 002 .563v14.874a.57.57 0 00.548.563H13.42zm-1.355-1.86H3.919V1.86h8.146v12.28zm-4.081 1.391a.464.464 0 01-.347-.14.455.455 0 01-.137-.329c0-.124.046-.234.137-.328a.464.464 0 01.347-.14c.129 0 .24.047.33.14a.455.455 0 01.138.329.455.455 0 01-.137.328.445.445 0 01-.331.14z"/></g></svg>
				<span class="sui-screen-reader-text"><?php esc_html_e( 'Preview on mobile', 'hustle' ); ?></span>
			</button>
		</div>

		<div tabindex="-1" class="hui-right">
			<button
				id="hustle-preview-reload-module-button"
				class="sui-button-icon sui-tooltip sui-tooltip-bottom"
				aria-label="<?php esc_html_e( 'Reload Preview', 'hustle' ); ?>"
				data-tooltip="<?php esc_html_e( 'Reload', 'hustle' ); ?>"
			>
				<span class="sui-icon-refresh sui-md" aria-hidden="true"></span>
				<span class="sui-screen-reader-text"><?php esc_html_e( 'Reload Preview', 'hustle' ); ?></span>
			</button>
			<span class="hui-button-space" aria-hidden="true"></span>
		</div>

	</div>

	<div class="hui-preview-body">

		<div id="hustle-preview-loader" class="hui-preview-loader">
			<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
		</div>

		<div id="hustle-preview-iframe-container" class="hui-preview-iframe" style="display: none;" aria-hidden="true">
			<p id="hustle-sr-text-preview-selected-device" role="alert" class="sui-screen-reader-text" aria-live="polite"></p>

			<?php /* translators: Module type capitalized in singular, "Module" when used in the Dashboard page */ ?>
			<p id="hustle-sr-text-preview-loaded" role="alert" class="sui-screen-reader-text" aria-live="polite"><?php printf( esc_html__( '%s preview finished loading.', 'hustle' ), esc_html( $module_type ) ); ?></p>

			<iframe
				id="hustle-preview-iframe"
				title="<?php esc_html_e( 'A preview of your module', 'hustle' ); ?>"
				data-src="<?php echo esc_url( $url ); ?>"
				sandbox="allow-same-origin allow-scripts"
			></iframe>
		</div>

	</div>

</div>

<?php
$content = ob_get_clean();

$attributes = array(
	'modal_id'        => 'preview',
	'modal_class'     => 'hui-modal-preview',
	'modal_size'      => '',
	'no_wrapper'      => true,
	'has_description' => true,
	'body'            => array(
		'content' => $content,
	),
);

$this->render_modal( $attributes );