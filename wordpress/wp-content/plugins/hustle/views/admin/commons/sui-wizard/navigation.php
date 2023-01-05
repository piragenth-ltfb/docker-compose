<?php
/**
 * Wizard lateral navigation.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-sidenav">

	<div class="sui-sidenav-sticky sui-sidenav-hide-md">

		<ul class="sui-vertical-tabs sui-alt-design">

			<?php
			foreach ( $wizard_tabs as $key => $option ) {

				$tab_name = $key;

				if ( isset( $option['name'] ) && '' !== $option['name'] ) {
					$tab_name = $option['name'];
				}

				if ( isset( $option['is_optin'] ) ) {

					if ( $is_optin ) :
						?>

						<li class="sui-vertical-tab">
							<a href="#" data-tab="<?php echo esc_html( $key ); ?>" class="<?php echo $key === $section ? 'current' : ''; ?>">
								<?php echo esc_html( $tab_name ); ?>
							</a>
						</li>

						<?php
					endif;

				} else {
					?>

					<li class="sui-vertical-tab">
						<a href="#" data-tab="<?php echo esc_html( $key ); ?>" class="<?php echo $key === $section ? 'current' : ''; ?>">
							<?php echo esc_html( $tab_name ); ?>
						</a>
					</li>

					<?php
				}
			}
			?>

		</ul>

	</div>

	<div class="sui-sidenav-hide-lg">

		<label class="sui-label"><?php esc_html_e( 'Navigate', 'hustle' ); ?></label>

		<select class="sui-mobile-nav" style="display: none;">
			<?php
			foreach ( $wizard_tabs as $key => $data ) {

				printf(
					'<option value="%1$s" %2$s>%3$s</option>',
					esc_attr( $key ),
					selected( $section, $key, false ),
					esc_html( $data['name'] )
				);

			}
			?>
		</select>

	</div>

</div>