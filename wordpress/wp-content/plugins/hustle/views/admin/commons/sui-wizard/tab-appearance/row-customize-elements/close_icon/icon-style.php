<?php
// TODO: USE THE SUI COMPONENT.

$name     = 'close_icon_style' . ( $device ? '_' . $device : '' );
$settings = $settings[ $name ];
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-2">

		<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php esc_html_e( 'Icon Style', 'hustle' ); ?></h5>

		<p class="sui-description"><?php esc_html_e( 'Choose an icon style for the close icon.', 'hustle' ); ?></p>

		<div class="sui-tabs sui-side-tabs">

			<input
				type="radio"
				value="flat"
				name="<?php echo esc_attr( $name ); ?>"
				id="hustle-<?php echo esc_attr( $name ); ?>--flat"
				class="sui-screen-reader-text hustle-tabs-option"
				data-attribute="<?php echo esc_attr( $name ); ?>"
				aria-hidden="true"
				tabindex="-1"
				<?php checked( $settings, 'flat' ); ?>
			/>

			<input
				type="radio"
				value="square"
				name="<?php echo esc_attr( $name ); ?>"
				id="hustle-<?php echo esc_attr( $name ); ?>--square"
				class="sui-screen-reader-text hustle-tabs-option"
				data-attribute="<?php echo esc_attr( $name ); ?>"
				aria-hidden="true"
				tabindex="-1"
				<?php checked( $settings, 'square' ); ?>
			/>

			<input
				type="radio"
				value="circle"
				name="<?php echo esc_attr( $name ); ?>"
				id="hustle-<?php echo esc_attr( $name ); ?>--circle"
				class="sui-screen-reader-text hustle-tabs-option"
				data-attribute="<?php echo esc_attr( $name ); ?>"
				aria-hidden="true"
				tabindex="-1"
				<?php checked( $settings, 'circle' ); ?>
			/>

			<div role="tablist" class="sui-tabs-menu">

				<button
					role="tab"
					type="button"
					id="tab-<?php echo esc_attr( $key ); ?>-position--flat"
					class="sui-tab-item active"
					data-label-for="hustle-<?php echo esc_attr( $name ); ?>--flat"
					aria-selected="true"
				>
					<i class="hui-tab-icon-close-flat" aria-hidden="true"></i>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Close icon without any background shape.', 'hustle' ); ?></span>
				</button>

				<button
					role="tab"
					type="button"
					id="tab-<?php echo esc_attr( $key ); ?>-position--square"
					class="sui-tab-item"
					data-label-for="hustle-<?php echo esc_attr( $name ); ?>--square"
					aria-selected="false"
					tabindex="-1"
				>
					<i class="hui-tab-icon-close-square" aria-hidden="true"></i>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Close icon with square shape.', 'hustle' ); ?></span>
				</button>

				<button
					role="tab"
					type="button"
					id="tab-<?php echo esc_attr( $key ); ?>-position--circle"
					class="sui-tab-item"
					data-label-for="hustle-<?php echo esc_attr( $name ); ?>--circle"
					aria-selected="false"
					tabindex="-1"
				>
					<i class="hui-tab-icon-close-circle" aria-hidden="true"></i>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Close icon with circle shape.', 'hustle' ); ?></span>
				</button>

			</div>

		</div>

	</div>

</div>