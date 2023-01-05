<?php
/**
 * Main wrapper for the 'Content' tab.
 *
 * @uses ../tab-content/
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box" <?php echo ( 'content' !== $section ) ? ' style="display: none;"' : ''; ?> data-tab="content">

	<div class="sui-box-header">

		<h2 class="sui-box-title"><?php esc_html_e( 'Content', 'hustle' ); ?></h2>

	</div>

	<div id="hustle-wizard-content" class="sui-box-body">

		<?php
		// SETTING: Title.
		$this->render(
			'admin/commons/sui-wizard/tab-content/title',
			array(
				'settings'           => $settings,
				'smallcaps_singular' => $smallcaps_singular,
			)
		);

		// SETTING: Feature Image.
		$this->render(
			'admin/commons/sui-wizard/tab-content/images',
			array(
				'settings'           => $settings,
				'smallcaps_singular' => $smallcaps_singular,
			)
		);

		// SETTING: Main Content.
		$this->render(
			'admin/commons/sui-wizard/tab-content/main-content',
			array( 'main_content' => $settings['main_content'] )
		);

		// SETTING: Call To Action.
		$this->render(
			'admin/commons/sui-wizard/tab-content/call-to-action',
			array(
				'settings'            => $settings,
				'smallcaps_singular'  => $smallcaps_singular,
				'capitalize_singular' => $capitalize_singular,
			)
		);

		if ( ! empty( $module_type ) && 'embedded' !== $module_type ) {

			// SETTING: "Never See This Link" Again.
			$this->render(
				'admin/commons/sui-wizard/tab-content/never-see-link',
				array( 'settings' => $settings )
			);
		}
		?>

	</div>

	<div class="sui-box-footer">

		<div class="sui-actions-right">
			<button class="sui-button sui-button-icon-right wpmudev-button-navigation" data-direction="next">
				<span class="sui-loading-text">
					<?php echo $is_optin ? esc_html__( 'Emails', 'hustle' ) : esc_html__( 'Appearance', 'hustle' ); ?> <span class="sui-icon-arrow-right" aria-hidden="true"></span>
				</span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>
		</div>

	</div>

</div>