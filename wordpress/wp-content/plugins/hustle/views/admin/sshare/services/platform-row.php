<?php
/**
 * Platform row.
 *
 * @package Hustle
 * @since 4.0.0
 */

$global_placeholders = Opt_In_Utils::get_global_placeholders();
?>
<script id="hustle-platform-row-tpl" type="text/template">

	<div class="sui-builder-field sui-accordion-item sui-can-move ui-sortable-handle" id="hustle-platform-{{platform}}" data-platform="{{platform}}">

		<div class="sui-accordion-item-header">

			<span class="sui-icon-drag" aria-hidden="true"></span>

			<div class="sui-builder-field-label">

				<span class="sui-icon-social" aria-hidden="true">

					<span class="hui-icon-social-{{platform_style}} hui-icon-circle"></span>

				</span>

				<span>{{label}}</span>

			</div>

			<button class="sui-button-icon sui-button-red hustle-remove-social-service" data-platform="{{platform}}">
				<span class="sui-icon-trash" aria-hidden="true"></span>
				<span class="sui-screen-reader-text"><?php esc_html_e( 'Remove platform', 'hustle' ); ?></span>
			</button>

			<div class="sui-builder-field-border" aria-hidden="true"></div>

			<button class="sui-button-icon sui-accordion-open-indicator">
				<span class="sui-icon-chevron-down" aria-hidden="true"></span>
				<span class="sui-screen-reader-text"><?php esc_html_e( 'Open platform settings', 'hustle' ); ?></span>
			</button>

		</div>

		<div class="sui-accordion-item-body">

			<div class="sui-form-field" data-toggle-content="counter-enabled">

				<label class="sui-label"><?php esc_html_e( 'Counter type', 'hustle' ); ?></label>

				<# if ( hasCounter ) { #>

					<div class="sui-side-tabs">

						<div class="sui-tabs-menu">

							<label for="hustle-{{platform}}-counter--click" class="sui-tab-item">
								<input
									type="radio"
									value="click"
									name="{{platform}}_type"
									data-attribute="{{platform}}_type"
									data-tab-menu="{{platform}}-type-click"
									id="hustle-{{platform}}-counter--click"
									{{ _.checked( ( 'click' === type ), true) }}
								/>
								<?php esc_html_e( 'Click', 'hustle' ); ?>
							</label>

							<label for="hustle-{{platform}}-counter--native" class="sui-tab-item">
								<input
									type="radio"
									value="native"
									name="{{platform}}_type"
									data-attribute="{{platform}}_type"
									data-tab-menu="{{platform}}-type-native"
									id="hustle-{{platform}}-counter--native"
									{{ _.checked( ( 'native' === type ), true) }}
								/>
								<?php esc_html_e( 'Native', 'hustle' ); ?>
							</label>

						</div>

						<# if ( 'twitter' === platform ) { #>
							<div class="sui-tabs-content">
								<div class="sui-tab-content" data-tab-content="{{platform}}-type-native">
									<span class="sui-description">
										<?php
										printf(
											/* translators: 1. opening 'a' tag, 2. closing 'a' tag */
											esc_html__( 'Twitter deprecated its native counter functionality. Sign-up to %1$sthis service%2$s in order to retrieve your Twitter stats. Keep in mind that this only tracks new shares after you register your site.', 'hustle' ),
											'<a href="http://www.twitcount.com/" target="_blank">',
											'</a>'
										);
										?>
									</span>
								</div>
							</div>
						<# } #>

					</div>

				<# } else { #>

					<div class="sui-notice" style="margin-top: 10px;">

						<div class="sui-notice-content">

							<div class="sui-notice-message">

								<span class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></span>
								<p style="margin: 0;"><?php esc_html_e( 'This social service only supports Click counter as there is no API support for Native counter.', 'hustle' ); ?></p>

							</div>
						</div>
					</div>

				<# } #>

			</div>

			<div class="sui-form-field" data-toggle-content="counter-enabled">

				<label class="sui-label"><?php esc_html_e( 'Default counter', 'hustle' ); ?></label>

				<input
					type="number"
					name="{{platform}}_counter"
					data-attribute="{{platform}}_counter"
					value="{{counter}}"
					placeholder="<?php esc_html_e( 'E.g. 0', 'hustle' ); ?>"
					class="sui-form-control"
				/>

			</div>

			<# if ( 'email' !== platform ) { #>

				<div class="sui-form-field hustle-social-url-field">

					<# if ( hasEndpoint ) { #>

						<label class="sui-label"><?php esc_html_e( 'Custom URL (optional)', 'hustle' ); ?></label>

					<# } else { #>

						<label class="sui-label"><?php esc_html_e( 'Custom URL', 'hustle' ); ?></label>

					<# } #>

					<input
						type="url"
						name="{{platform}}_link"
						data-attribute="{{platform}}_link"
						value="{{link}}"
						placeholder="<?php esc_html_e( 'Type the custom URL here', 'hustle' ); ?>"
						class="sui-form-control"
					/>

					<# if ( hasEndpoint ) { #>

						<span class="sui-description"><?php esc_html_e( 'Redirect visitors to this URL when they click the icon. Leaving this blank will share the page link instead.', 'hustle' ); ?></span>

					<# } else { #>
						<p class="sui-error-message" style="width:100%; display:none; text-align:right;"><?php esc_html_e( 'A custom URL is required to redirect your users.', 'hustle' ); ?></p>

						<span class="sui-description"><?php esc_html_e( 'Redirect visitors to this URL when they click the icon. Note that a valid redirect URL is required to show this icon to your visitors.', 'hustle' ); ?></span>

					<# } #>

				</div>

			<# } else { #>

				<div class="sui-form-field">

					<label for="hustle-sshare-email--subject" id="hustle-sshare-email--subject-label" class="sui-label"><?php esc_html_e( 'Email subject', 'hustle' ); ?></label>

					<div class="sui-insert-variables">

						<input
							type="text"
							name="{{platform}}_title"
							data-attribute="{{platform}}_title"
							value="{{title}}"
							id="hustle-sshare-email--subject"
							class="sui-form-control"
							aria-labelledby="hustle-sshare-email--subject-label"
						/>

						<select class="hustle-select-field-variables" data-field="{{platform}}_title">

							<?php foreach ( $global_placeholders as $placeholder => $display_name ) : ?>
								<option value="{<?php echo esc_attr( $placeholder ); ?>}"><?php echo esc_html( $display_name ); ?></option>
							<?php endforeach; ?>

						</select>

					</div>

				</div>

				<div class="sui-form-field">

					<label
						for="hustle-sshare-email--body"
						id="hustle-sshare-email--body-label"
						class="sui-label"
					>
						<?php esc_html_e( 'Email body', 'hustle' ); ?>
						<span class="sui-label-note"><?php esc_html_e( 'Use the “+” icon to add variable(s)', 'hustle' ); ?></span>
					</label>

					<div class="sui-insert-variables">

						<textarea
							name="{{platform}}_message"
							data-attribute="{{platform}}_message"
							id="hustle-sshare-email--body"
							class="sui-form-control"
							aria-labelledby="hustle-sshare-email--body-label"
						>{{message}}</textarea>

						<select class="hustle-select-field-variables" data-field="{{platform}}_message">

							<?php foreach ( $global_placeholders as $placeholder => $display_name ) : ?>
								<option value="{<?php echo esc_attr( $placeholder ); ?>}" data-content="{<?php echo esc_attr( $placeholder ); ?>}" role="option"><?php echo esc_html( $display_name ); ?></option>
							<?php endforeach; ?>

						</select>

					</div>

				</div>

			<# } #>

		</div>

	</div>

</script>