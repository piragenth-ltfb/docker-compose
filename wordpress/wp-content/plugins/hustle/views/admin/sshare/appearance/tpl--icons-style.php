<?php
/**
 * Icons style section.
 *
 * @var bool $is_empty True if neither inline nor floating display types are enabled.
 *
 * @package Hustle
 * @since 4.0.0
 */

$options = array(
	'flat'    => array(
		'value'     => 'flat',
		'label'     => __( 'Default', 'hustle' ),
		'hui-icon'  => 'social-facebook',
		'icon-size' => 'sm',
	),
	'outline' => array(
		'value'     => 'outline',
		'label'     => __( 'Outlined', 'hustle' ),
		'hui-icon'  => 'social-facebook hui-icon-outlined',
		'icon-size' => 'sm',
	),
	'rounded' => array(
		'value'     => 'rounded',
		'label'     => __( 'Circle', 'hustle' ),
		'hui-icon'  => 'social-facebook hui-icon-circle',
		'icon-size' => 'sm',
	),
	'squared' => array(
		'value'     => 'squared',
		'label'     => __( 'Square', 'hustle' ),
		'hui-icon'  => 'social-facebook hui-icon-square',
		'icon-size' => 'sm',
	),
);
?>
<div id="hustle-appearance-icons-style" class="sui-box-settings-row"<?php echo $is_empty ? ' style="display: none;"' : ''; ?>>

	<div class="sui-box-settings-col-1">

		<span class="sui-settings-label"><?php esc_html_e( 'Icons Style', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Choose the style for your social icons as per your need.', 'hustle' ); ?></span>

	</div>

	<div class="sui-box-settings-col-2">

		<?php
		$this->render(
			'admin/global/sui-components/sui-tabs',
			array(
				'name'        => 'icon_style',
				'radio'       => true,
				'saved_value' => $icon_style,
				'sidetabs'    => true,
				'content'     => false,
				'options'     => $options,
			)
		);
		?>

	</div>

</div>