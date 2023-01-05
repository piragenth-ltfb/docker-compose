<?php
/**
 * Template used by the "Import" dialog (./import-module.php).
 *
 * Used to display the checkboxes for selecting the module's meta to be imported.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<label class="sui-label"><?php esc_html_e( 'Settings to import', 'hustle' ); ?></label>

<ul class="hui-inputs-list">

	<li><label for="hustle-import-<?php echo esc_attr( $id ); ?>--all" class="sui-checkbox sui-checkbox-stacked sui-checkbox-sm">
			<input
				class="hustle-import-check-all-checkbox"
				type="checkbox"
				id="hustle-import-<?php echo esc_attr( $id ); ?>--all"
				name="<?php echo esc_attr( $id ); ?>_metas[]"
				value="all"
				checked
			/>
			<span aria-hidden="true"></span>
			<span><?php esc_html_e( 'All', 'hustle' ); ?></span>
		</label>

		<ul>

			<?php
			// Don't print in the default order to follow the design.
			$half   = ceil( count( $metas ) / 2 );
			$chunks = array_chunk( $metas, $half );

			for ( $i = 0; $i <= $half; $i++ ) {

				foreach ( $chunks as $chunk => $data ) {

					if ( isset( $data[ $i ] ) ) :
						$name  = $data[ $i ]['name'];
						$label = $data[ $i ]['label'];

						?>

						<li><label for="hustle-import-<?php echo esc_attr( $id ); ?>--<?php echo esc_attr( $name ); ?>" class="sui-checkbox sui-checkbox-stacked sui-checkbox-sm">
							<input
								type="checkbox"
								class="hustle-module-meta-checkbox"
								id="hustle-import-<?php echo esc_attr( $id ); ?>--<?php echo esc_attr( $name ); ?>"
								name="<?php echo esc_attr( $id ); ?>_metas[]"
								value="<?php echo esc_attr( $name ); ?>"
								checked
							/>
							<span aria-hidden="true"></span>
							<span><?php echo esc_html( $label ); ?></span>
						</label></li>

						<?php
					endif;
				}
			}
			?>

		</ul>

	</li>

</ul>