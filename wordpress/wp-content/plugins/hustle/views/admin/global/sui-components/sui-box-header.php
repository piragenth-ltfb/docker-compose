<?php
/**
 * SUI Box Header.
 *
 * @package Hustle
 * @since 4.3.0
 *
 * @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 */

$heading = ( isset( $heading ) && ! empty( $heading ) ) ? $heading : 0;
$icon    = ( isset( $icon ) && ! empty( $icon ) ) ? '<span class="sui-icon-' . $icon . ' sui-lg" aria-hidden="true"></span>' : '';
$pro_tag = ( isset( $pro_tag ) && true === $pro_tag ) ? '<span class="sui-tag sui-tag-pro" style="margin-left: 10px" aria-hidden="true">Pro</span>' : '';
?>

<div class="sui-box-header">

	<?php
	switch ( $heading ) {
		case 3:
			echo '<h3 class="sui-box-title">' . $icon . $title . '</h3>' . $pro_tag;
			break;
		case 4:
			echo '<h4 class="sui-box-title">' . $icon . $title . '</h4>' . $pro_tag;
			break;
		case 5:
			echo '<h5 class="sui-box-title">' . $icon . $title . '</h5>' . $pro_tag;
			break;
		case 6:
			echo '<h6 class="sui-box-title">' . $icon . $title . '</h6>' . $pro_tag;
			break;
		default:
			echo '<h2 class="sui-box-title">' . $icon . $title . '</h2>' . $pro_tag;
			break;
	}
	?>

</div>