<?php
/**
 * Border, Spacing and Shadow section.
 *
 * @uses admin/global/sui-components/sui-tabs
 * @uses ./border-spacing-shadow/options-container
 *
 * @package Hustle
 * @since 4.3.0
 */

$vanilla_hide  = ( isset( $vanilla_hide ) ) ? $vanilla_hide : false;
$device_suffix = empty( $device ) ? '' : '_' . $device;

$options_args = array(
	'settings'            => $settings,
	'is_optin'            => $is_optin,
	'device'              => $device,
	'smallcaps_singular'  => $smallcaps_singular,
	'capitalize_singular' => $capitalize_singular,
);

$options_container = $this->render(
	'admin/commons/sui-wizard/tab-appearance/row-advanced/border-spacing-shadow/options-container',
	$options_args,
	true
);
?>

<?php
printf(
	'<div class="sui-form-field"%s>',
	$vanilla_hide ? ' data-toggle-content="use-vanilla"' : ''
);
?>

	<h4 class="sui-settings-label"><?php esc_html_e( 'Border, Spacing and Shadow', 'hustle' ); ?></h4>

	<p class="sui-description" style="margin-bottom: 10px;">
		<?php /* translators: module type in smallcaps and singular. */ ?>
		<?php printf( esc_html__( 'Your %s elements have default spacings, borders, and box shadows. You can customize these properties as per your liking.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?>
		<?php
		if ( 'mobile' === $device ) {
			/* translators: 1. opening 'strong' tag, 2. closing 'strong' tag. */
			printf( esc_html__( '%1$sNote:%2$s Empty properties will inherit their value form the Desktop settings.', 'hustle' ), '<strong>', '</strong>' );				 		 	      	  		 
		}
		?>
	</p>

	<?php
	$this->render(
		'admin/global/sui-components/sui-tabs',
		array(
			'name'        => 'customize_border_shadow_spacing' . $device_suffix,
			'radio'       => true,
			'saved_value' => $settings[ 'customize_border_shadow_spacing' . $device_suffix ],
			'sidetabs'    => true,
			'content'     => true,
			'options'     => array(
				'default' => array(
					'value' => '0',
					'label' => esc_html__( 'Default Values', 'hustle' ),
				),
				'custom'  => array(
					'value'   => '1',
					'label'   => esc_html__( 'Custom', 'hustle' ),
					'content' => $options_container,
				),
			),
		)
	);
	?>

</div>