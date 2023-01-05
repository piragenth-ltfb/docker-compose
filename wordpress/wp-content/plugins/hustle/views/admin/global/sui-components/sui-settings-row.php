<?php
/**
 * SUI Settings Row
 *
 * @package Hustle
 * @since 4.3.0
 *
 * @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 */

$vanilla_hide              = ( isset( $vanilla_hide ) ) ? $vanilla_hide : false;
$label_tag                 = ( isset( $label_tag ) && ! empty( $label_tag ) ) ? $label_tag : 'h3';
$has_label                 = ( isset( $label ) && ! empty( $label ) ) ? true : false;
$has_description           = ( isset( $description ) && ! empty( $description ) ) ? true : false;
$has_multiline_description = ( isset( $multi_description ) && ! empty( $multi_description ) ) ? true : false;
$description_class         = ( isset( $label ) ) ? 'hustle-' . sanitize_title( $label ) . '-elements-row' : '';
?>

<?php
printf(
	'<div class="sui-box-settings-row %s"%s>',
	$description_class,
	$vanilla_hide ? ' data-toggle-content="use-vanilla"' : ''
);
?>

	<?php if ( $has_label || $has_multiline_description || $has_description ) { ?>

		<div class="sui-box-settings-col-1">

			<?php
			if ( $has_label ) {

				switch ( $label_tag ) {

					case 'h3':
						echo '<h3 class="sui-settings-label">' . $label . '</h3>';
						break;

					case 'h4':
						echo '<h4 class="sui-settings-label">' . $label . '</h4>';
						break;

					case 'h5':
						echo '<h5 class="sui-settings-label">' . $label . '</h5>';
						break;

					case 'h6':
						echo '<h6 class="sui-settings-label">' . $label . '</h6>';
						break;

					case 'p':
						echo '<p class="sui-settings-label">' . $label . '</p>';
						break;

					default:
						echo '<h2 class="sui-settings-label">' . $label . '</h2>';
						break;
				}
			}

			if ( $has_multiline_description ) {

				$paragraph = '';

				foreach ( $multi_description as $k => $description ) {
					$paragraph .= '<p class="sui-description">' . $description . '</p>';
				}

				echo $paragraph;

			} elseif ( $has_description ) {
				echo '<p class="sui-description">' . $description . '</p>';
			}
			?>

		</div>

	<?php } ?>

	<div class="sui-box-settings-col-2">

		<?php echo $content; ?>

	</div>

</div>