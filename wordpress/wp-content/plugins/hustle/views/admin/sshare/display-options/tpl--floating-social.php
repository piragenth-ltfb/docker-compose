<?php
/**
 * Floating display type section.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">

		<span class="sui-settings-label"><?php esc_html_e( 'Floating Social', 'hustle' ); ?></span>

		<span class="sui-description"><?php esc_html_e( 'Add a floating social bar to your website and also customize its location.', 'hustle' ); ?></span>

	</div>

	<div class="sui-box-settings-col-2">

		<?php
		// SETTINGS: Enable on desktop.
		$this->render(
			'admin/sshare/display-options/tpl--position-settings',
			array(
				'label'       => esc_html__( 'on desktop', 'hustle' ),
				'description' => esc_html__( 'Enabling this will add a floating social bar to your website when visitors are  on a desktop.', 'hustle' ),
				'prefix'      => 'float_desktop',
				'settings'    => $settings,
				'offset_x'    => true,
				'offset_y'    => true,
				'positions'   => array(
					'left'   => array(
						'label'   => esc_html__( 'Left', 'hustle' ),
						'image1x' => 'social-position/float-desktop-left.png',
						'image2x' => 'social-position/float-desktop-left@2x.png',
					),
					'right'  => array(
						'label'   => esc_html__( 'Right', 'hustle' ),
						'image1x' => 'social-position/float-desktop-right.png',
						'image2x' => 'social-position/float-desktop-right@2x.png',
					),
					'center' => array(
						'label'   => esc_html__( 'Centered', 'hustle' ),
						'image1x' => 'social-position/float-desktop-center.png',
						'image2x' => 'social-position/float-desktop-center@2x.png',
					),
				),
			)
		);

		// SETTINGS: Enable on mobile.
		$this->render(
			'admin/sshare/display-options/tpl--position-settings',
			array(
				'label'       => esc_html__( 'on mobile', 'hustle' ),
				'description' => esc_html__( 'Enabling this will add a floating social bar to your website when visitors are  on a mobile device.', 'hustle' ),
				'prefix'      => 'float_mobile',
				'settings'    => $settings,
				'offset_x'    => true,
				'offset_y'    => true,
				'positions'   => array(
					'left'   => array(
						'label'   => esc_html__( 'Left', 'hustle' ),
						'image1x' => 'social-position/float-mobile-left.png',
						'image2x' => 'social-position/float-mobile-left@2x.png',
					),
					'right'  => array(
						'label'   => esc_html__( 'Right', 'hustle' ),
						'image1x' => 'social-position/float-mobile-right.png',
						'image2x' => 'social-position/float-mobile-right@2x.png',
					),
					'center' => array(
						'label'   => esc_html__( 'Centered', 'hustle' ),
						'image1x' => 'social-position/float-mobile-center.png',
						'image2x' => 'social-position/float-mobile-center@2x.png',
					),
				),
			)
		);
		?>

	</div>

</div>