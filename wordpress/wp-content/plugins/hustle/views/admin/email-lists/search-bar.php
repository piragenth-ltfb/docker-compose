<?php
/**
 * Search bar section.
 *
 * @var Hustle_Layout_Helper $this
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box">

	<div class="hui-box-entries-search">

		<form id="hustle-entries-search-form" class="hui-search-left" method="get">

			<input
				type="hidden"
				name="page"
				value="hustle_entries"
			/>

			<select
				name="module_type"
				class="sui-select sui-select-sm sui-select-inline"
				onchange="submit()"
				data-width="150"
			>
				<?php foreach ( $this->admin->get_module_types() as $module_type => $name ) { ?>
					<option value="<?php echo esc_attr( $module_type ); ?>" <?php echo selected( $module_type, $this->admin->get_current_module_type() ); ?>><?php echo esc_html( $name ); ?></option>
				<?php } ?>
			</select>

			<?php echo $this->admin->render_module_switcher(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

			<button class="sui-button sui-button-blue" onclick="submit()">
				<?php esc_html_e( 'Show Email List', 'hustle' ); ?>
			</button>

		</form>

		<?php if ( $has_entries ) : ?>

			<div class="hui-search-right">

				<form method="post">
					<input type="hidden" name="hustle_action" value="export_listing">
					<input type="hidden" name="id" value="<?php echo esc_attr( $module->id ); ?>">
					<?php wp_nonce_field( 'hustle_module_export_listing' ); ?>
					<button class="sui-button sui-button-ghost">
						<span class="sui-icon-paperclip" aria-hidden="true"></span>
						<?php esc_html_e( 'Export CSV', 'hustle' ); ?>
					</button>
				</form>

			</div>

		<?php endif; ?>

	</div>

</div>