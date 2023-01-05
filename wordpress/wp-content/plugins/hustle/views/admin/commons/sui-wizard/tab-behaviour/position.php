<?php
/**
 * Module position section.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<?php /* translators: module type capitalized and in singular */ ?>
		<span class="sui-settings-label"><?php printf( esc_html__( '%s Position', 'hustle' ), esc_html( $capitalize_singular ) ); ?></span>
		<?php /* translators: module type in small caps and in singular */ ?>
		<span class="sui-description"><?php printf( esc_html__( 'Choose the position from which your %s will appear on screen.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
	</div>

	<div class="sui-box-settings-col-2 wpmudev-ui">

		<?php /* translators: module type in small caps and in singular */ ?>
		<label class="sui-settings-label"><?php printf( esc_html__( 'Choose %s position', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></label>

		<?php /* translators: module type in small caps and in singular */ ?>
		<span class="sui-description"><?php printf( esc_html__( 'Select the position from which your %s will appear on the browser window.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>

		<div class="hui-browser" style="margin-top: 10px;">

			<div class="hui-browser-bar" aria-hidden="true">
				<span></span>
				<span></span>
				<span></span>
			</div>

			<ul class="hui-browser-content">

				<li class="hui-first-row"><label for="hustle-module-position--nw">
					<input
						type="radio"
						value="nw"
						name="display_position"
						id="hustle-module-position--nw"
						data-attribute="display_position"
						<?php checked( $display_position, 'nw' ); ?>
					/>
					<span class="hui-browser-position--north-west" aria-hidden="true"></span>
					<?php /* translators: module type in small caps and in singular */ ?>
					<span class="sui-screen-reader-text"><?php printf( esc_html__( 'Show %s from top left', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
				</label></li>

				<li class="hui-first-row"><label for="hustle-module-position--n">
					<input
						type="radio"
						value="n"
						name="display_position"
						id="hustle-module-position--n"
						data-attribute="display_position"
						<?php checked( $display_position, 'n' ); ?>
					/>
					<span class="hui-browser-position--north" aria-hidden="true"></span>
					<?php /* translators: module type in small caps and in singular */ ?>
					<span class="sui-screen-reader-text"><?php printf( esc_html__( 'Show %s from top', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
				</label></li>

				<li class="hui-first-row"><label for="hustle-module-position--ne">
					<input
						type="radio"
						value="ne"
						name="display_position"
						id="hustle-module-position--ne"
						data-attribute="display_position"
						<?php checked( $display_position, 'ne' ); ?>
					/>
					<span class="hui-browser-position--north-east" aria-hidden="true"></span>
					<?php /* translators: module type in small caps and in singular */ ?>
					<span class="sui-screen-reader-text"><?php printf( esc_html__( 'Show %s from top right', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
				</label></li>

				<li><label for="hustle-module-position--w">
					<input
						type="radio"
						value="w"
						name="display_position"
						id="hustle-module-position--w"
						data-attribute="display_position"
						<?php checked( $display_position, 'w' ); ?>
					/>
					<span class="hui-browser-position--west" aria-hidden="true"></span>
					<?php /* translators: module type in small caps and in singular */ ?>
					<span class="sui-screen-reader-text"><?php printf( esc_html__( 'Show %s from left', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
				</label></li>

				<li aria-hidden="true"></li>

				<li><label for="hustle-module-position--e">
					<input
						type="radio"
						value="e"
						name="display_position"
						id="hustle-module-position--e"
						data-attribute="display_position"
						<?php checked( $display_position, 'e' ); ?>
					/>
					<span class="hui-browser-position--east" aria-hidden="true"></span>
					<?php /* translators: module type in small caps and in singular */ ?>
					<span class="sui-screen-reader-text"><?php printf( esc_html__( 'Show %s from right', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
				</label></li>

				<li class="hui-last-row"><label for="hustle-module-position--sw">
					<input
						type="radio"
						value="sw"
						name="display_position"
						id="hustle-module-position--sw"
						data-attribute="display_position"
						<?php checked( $display_position, 'sw' ); ?>
					/>
					<span class="hui-browser-position--south-west" aria-hidden="true"></span>
					<?php /* translators: module type in small caps and in singular */ ?>
					<span class="sui-screen-reader-text"><?php printf( esc_html__( 'Show %s from bottom left', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
				</label></li>

				<li class="hui-last-row"><label for="hustle-module-position--s">
					<input
						type="radio"
						value="s"
						name="display_position"
						id="hustle-module-position--s"
						data-attribute="display_position"
						<?php checked( $display_position, 's' ); ?>
					/>
					<span class="hui-browser-position--south" aria-hidden="true"></span>
					<?php /* translators: module type in small caps and in singular */ ?>
					<span class="sui-screen-reader-text"><?php printf( esc_html__( 'Show %s from bottom', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
				</label></li>

				<li class="hui-last-row"><label for="hustle-module-position--se">
					<input
						type="radio"
						value="se"
						name="display_position"
						id="hustle-module-position--se"
						data-attribute="display_position"
						<?php checked( $display_position, 'se' ); ?>
					/>
					<span class="hui-browser-position--south-east" aria-hidden="true"></span>
					<?php /* translators: module type in small caps and in singular */ ?>
					<span class="sui-screen-reader-text"><?php printf( esc_html__( 'Show %s from bottom right', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
				</label></li>

			</ul>

		</div>

	</div>

</div>