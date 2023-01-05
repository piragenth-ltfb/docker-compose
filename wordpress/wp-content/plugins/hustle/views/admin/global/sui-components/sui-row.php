<?php
/**
 * SUI Row.
 *
 * @package Hustle
 * @since 4.3.0
 *
 * @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 */

$breakpoint = isset( $breakpoint ) ? $breakpoint : 'md';
?>

<div class="sui-row">

	<?php foreach ( $columns as $column ) { ?>

		<?php
		switch ( $breakpoint ) {
			case '':
			case 'none':
				$class = 'sui-col-' . $column['size'];
				break;

			case 'sm':
			case 'md':
			case 'lg':
				$class = 'sui-col-' . $breakpoint . '-' . $column['size'];
				break;

			case 'mobile':
				$class = 'sui-col-sm-' . $column['size'];
				break;

			case 'tablet':
				$class = 'sui-col-md-' . $column['size'];
				break;

			case 'desktop':
				$class = 'sui-col-lg-' . $column['size'];
				break;

			default:
				$class = 'sui-col-md-' . $column['size'];
				break;
		}
		?>

		<div class="<?php echo esc_attr( $class ); ?>">

			<?php echo $column['content']; ?>

		</div>

	<?php } ?>

</div>