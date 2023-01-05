<?php
/**
 * API keys section under the "recaptcha" tab.
 *
 * @package Hustle
 * @since 4.0.4
 */

$types = array(
	'v2_checkbox'  => array(
		'menu'  => esc_html__( 'v2 Checkbox', 'hustle' ),
		'label' => esc_html__( 'reCAPTCHA v2 Checkbox', 'hustle' ),
	),
	'v2_invisible' => array(
		'menu'  => esc_html__( 'v2 Invisible', 'hustle' ),
		'label' => esc_html__( 'reCAPTCHA v2 Invisible', 'hustle' ),
	),
	'v3_recaptcha' => array(
		'menu'  => esc_html__( 'reCaptcha v3', 'hustle' ),
		'label' => esc_html__( 'reCAPTCHA v3', 'hustle' ),
	),
);
?>

<div class="sui-form-field">

	<label id="hustle-api-keys-label" class="sui-settings-label"><?php esc_html_e( 'API Keys', 'hustle' ); ?></label>

	<span id="hustle-api-keys-description" class="sui-description">
		<?php /* translators: 1. opening 'a' tag to recaptcha's list, 2. closing 'a' tag */ ?>
		<?php printf( esc_html__( 'Enter the API keys for each reCAPTCHA type you want to use in your opt-ins. Note that each reCAPTCHA type requires a different set of API keys. %1$sGenerate API keys%2$s.', 'hustle' ), '<a href="https://www.google.com/recaptcha/admin#list" target="_blank">', '</a>' ); ?>
	</span>

	<div class="sui-tabs sui-tabs-overflow" style="margin-top: 10px;">

		<div role="tablist" class="sui-tabs-menu">

			<?php
			$i = 0;
			foreach ( $types as $key => $text ) :
				$i++;
				?>

				<button
					type="button"
					role="tab"
					id="hustle-api-key-tab--<?php echo esc_attr( $key ); ?>"
					class="sui-tab-item<?php echo ( 1 === $i ) ? ' active' : ''; ?>"
					aria-controls="hustle-api-key-content--<?php echo esc_attr( $key ); ?>"
					aria-selected="<?php echo ( 1 === $i ) ? 'true' : 'false'; ?>"
					<?php echo ( 1 === $i ) ? '' : 'tabindex="-1"'; ?>
				>
					<?php echo esc_html( $text['menu'] ); ?>
				</button>

				<?php
			endforeach;
			?>

		</div>

		<div class="sui-tabs-content">

			<?php
			$i = 0;
			foreach ( $types as $key => $text ) :
				$i++;
				?>

				<div
					role="tabpanel"
					tabindex="0"
					id="hustle-api-key-content--<?php echo esc_attr( $key ); ?>"
					class="sui-tab-content<?php echo ( 1 === $i ) ? ' active' : ''; ?>"
					aria-labelledby="hustle-api-key-tab--<?php echo esc_attr( $key ); ?>"
					<?php echo ( 1 === $i ) ? '' : 'hidden'; ?>
				>

					<?php /* translators: recaptcha key */ ?>
					<span class="sui-description"><?php printf( esc_html__( 'Enter the API keys for %s type below:', 'hustle' ), esc_html( $text['label'] ) ); ?></span>

					<div class="sui-form-field">

						<label for="hustle-<?php echo esc_attr( $key ); ?>-site-key" id="hustle-<?php echo esc_attr( $key ); ?>-site-key-label" class="sui-label"><?php esc_html_e( 'Site Key', 'hustle' ); ?></label>

						<input
							type="text"
							name="<?php echo esc_attr( $key . '_site_key' ); ?>"
							value="<?php echo esc_attr( $settings[ $key . '_site_key' ] ); ?>"
							placeholder="<?php esc_html_e( 'Enter your site key here', 'hustle' ); ?>"
							id="hustle-<?php echo esc_attr( $key ); ?>-site-key"
							class="sui-form-control"
							aria-labelledby="hustle-<?php echo esc_attr( $key ); ?>-site-key-label"
						/>

					</div>

					<div class="sui-form-field">

						<label for="hustle-<?php echo esc_attr( $key ); ?>-secret-key" id="hustle-<?php echo esc_attr( $key ); ?>-site-secret-label" class="sui-label"><?php esc_html_e( 'Secret Key', 'hustle' ); ?></label>

						<input
							type="text"
							name="<?php echo esc_attr( $key . '_secret_key' ); ?>"
							value="<?php echo esc_attr( $settings[ $key . '_secret_key' ] ); ?>"
							placeholder="<?php esc_html_e( 'Enter your secret key here', 'hustle' ); ?>"
							id="hustle-<?php echo esc_attr( $key ); ?>-secret-key"
							class="sui-form-control"
							aria-labelledby="hustle-<?php echo esc_attr( $key ); ?>-secret-key-label"
						/>

					</div>

					<div class="sui-form-field" data-id="<?php echo esc_attr( $key ); ?>" data-render-id="0">

						<label class="sui-label"><?php esc_html_e( 'reCAPTCHA Preview', 'hustle' ); ?></label>

						<div
							id="hustle-modal-recaptcha-<?php echo esc_attr( $key ); ?>-0"
							class="hustle-recaptcha-preview-container hustle-recaptcha"
							style="display: none;"
							data-sitekey=""
							data-version="<?php echo esc_attr( $key ); ?>"
							data-theme="light"
							data-size="<?php echo 'v2_checkbox' === $key ? 'full' : 'invisible'; ?>"
							data-badge="<?php echo 'v2_checkbox' === $key ? '' : 'inline'; ?>"
						></div>

						<?php
						$this->get_html_for_options(
							array(
								array(
									'type'       => 'inline_notice',
									'class'      => 'hustle-recaptcha-' . esc_attr( $key ) . '-preview-notice',
									'icon'       => 'info',
									'value'      => esc_html__( 'Save your API keys to load the reCAPTCHA preview.', 'hustle' ),
									'attributes' => array(
										'style' => 'margin-top: 0;',
									),
								),
							)
						);
						?>

					</div>

				</div>

			<?php endforeach; ?>

		</div>

	</div>

</div>