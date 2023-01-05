<?php
/**
 * Pricing upsell controls.
 *
 * @package Hestia
 */

/**
 * Class Hestia_Pricing_Upsell_Controls
 */
class Hestia_Pricing_Upsell_Controls extends Hestia_Front_Page_Section_Controls_Abstract {

	/**
	 * Set the section data for generating the customizer basic settings
	 *
	 * @return array
	 */
	protected function set_section_data() {
		return array(
			'slug'             => 'pricing',
			'title'            => esc_html__( 'Pricing', 'hestia' ),
			'priority'         => 75,
			'initially_hidden' => true,
		);
	}

	/**
	 * Add controls.
	 */
	public function add_controls() {
		$this->add_tabs();
		$description = sprintf(
			/* translators: %s is the Learn more link */
			__( 'More Options Available for Pricing in the PRO version. %s', 'hestia' ),
			/* translators: %s is the Learn more label*/
			sprintf(
				'<a class="button button-primary" target="_blank" href="' . tsdk_utmify( 'https://themeisle.com/themes/hestia-pro/upgrade/', 'pricingsection' ) . '" style="display: block; clear: both; width: fit-content; margin: 15px 0;">%s</a>',
				__( 'Upgrade to Unlock', 'hestia' )
			)
		);
		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_pricing_upsell_notice',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'section'     => 'hestia_pricing',
					'description' => $description . '<hr style="margin-left: 0px; border-bottom: none;">',
					'priority'    => 1,
					'type'        => 'hidden',
				)
			)
		);
		$table_one_features_default = sprintf( '<b>%1$s</b> %2$s', esc_html__( '1', 'hestia' ), esc_html__( 'Domain', 'hestia' ) ) .
			sprintf( '\n<b>%1$s</b> %2$s', esc_html__( '1GB', 'hestia' ), esc_html__( 'Storage', 'hestia' ) ) .
			sprintf( '\n<b>%1$s</b> %2$s', esc_html__( '100GB', 'hestia' ), esc_html__( 'Bandwidth', 'hestia' ) ) .
			sprintf( '\n<b>%1$s</b> %2$s', esc_html__( '2', 'hestia' ), esc_html__( 'Databases', 'hestia' ) );

		$table_two_features_default = sprintf( '<b>%1$s</b> %2$s', esc_html__( '5', 'hestia' ), esc_html__( 'Domain', 'hestia' ) ) .
			sprintf( '\n<b>%1$s</b> %2$s', esc_html__( 'Unlimited', 'hestia' ), esc_html__( 'Storage', 'hestia' ) ) .
			sprintf( '\n<b>%1$s</b> %2$s', esc_html__( 'Unlimited', 'hestia' ), esc_html__( 'Bandwidth', 'hestia' ) ) .
			sprintf( '\n<b>%1$s</b> %2$s', esc_html__( 'Unlimited', 'hestia' ), esc_html__( 'Databases', 'hestia' ) );

		$table_one_args = array(
			'id'       => 'hestia_pricing_table_one',
			'defaults' => array(
				'title'       => esc_html__( 'Basic Package', 'hestia' ),
				'price'       => '<small>$</small>0',
				'features'    => $table_one_features_default,
				'button_text' => esc_html__( 'Free Download', 'hestia' ),
				'button_link' => esc_url( '#' ),
			),
			'labels'   => array(
				'title'       => esc_html__( 'Pricing Table One: Title', 'hestia' ),
				'icon'        => esc_html__( 'Pricing Table One: Icon', 'hestia' ),
				'price'       => esc_html__( 'Pricing Table One: Price', 'hestia' ),
				'features'    => esc_html__( 'Pricing Table One: Features', 'hestia' ),
				'button_text' => esc_html__( 'Pricing Table One: Text', 'hestia' ),
				'button_link' => esc_html__( 'Pricing Table One: Link', 'hestia' ),
			),
		);

		$table_two_args = array(
			'id'       => 'hestia_pricing_table_two',
			'defaults' => array(
				'title'       => esc_html__( 'Premium Package', 'hestia' ),
				'price'       => '<small>$</small>49',
				'features'    => $table_two_features_default,
				'button_text' => esc_html__( 'Order Now', 'hestia' ),
				'button_link' => esc_url( '#' ),
			),
			'labels'   => array(
				'title'       => esc_html__( 'Pricing Table Two: Title', 'hestia' ),
				'icon'        => esc_html__( 'Pricing Table Two: Icon', 'hestia' ),
				'price'       => esc_html__( 'Pricing Table Two: Price', 'hestia' ),
				'features'    => esc_html__( 'Pricing Table Two: Features', 'hestia' ),
				'button_text' => esc_html__( 'Pricing Table Two: Text', 'hestia' ),
				'button_link' => esc_html__( 'Pricing Table Two: Link', 'hestia' ),
			),
		);

		$this->add_pricing_table_controls( $table_one_args );
		$this->add_pricing_table_controls( $table_two_args );
	}

	/**
	 * Add section tabs.
	 */
	private function add_tabs() {
		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_pricing_tabs',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'section'  => 'hestia_pricing',
					'priority' => 1,
					'tabs'     => array(
						'general' => array(
							'label'  => esc_html__( 'General', 'hestia' ),
							'icon'   => 'admin-tools',
							'locked' => true,
						),
						'first'   => array(
							'label'  => esc_html__( 'First', 'hestia' ),
							'icon'   => 'list-view',
							'locked' => true,
						),
						'second'  => array(
							'label'  => esc_html__( 'Second', 'hestia' ),
							'icon'   => 'list-view',
							'locked' => true,
						),
					),
					'controls' => array(
						'general' => array(
							'hestia_pricing_hide'     => array(),
							'hestia_pricing_title'    => array(),
							'hestia_pricing_subtitle' => array(),
						),
						'first'   => array(
							'hestia_pricing_table_one_title' => array(),
							'hestia_pricing_table_one_icon' => array(),
							'hestia_pricing_table_one_price' => array(),
							'hestia_pricing_table_one_features' => array(),
							'hestia_pricing_table_one_link' => array(),
							'hestia_pricing_table_one_text' => array(),
						),
						'second'  => array(
							'hestia_pricing_table_two_title' => array(),
							'hestia_pricing_table_two_icon' => array(),
							'hestia_pricing_table_two_price' => array(),
							'hestia_pricing_table_two_features' => array(),
							'hestia_pricing_table_two_link' => array(),
							'hestia_pricing_table_two_text' => array(),
						),

					),
				),
				'Hestia_Customize_Control_Tabs'
			)
		);
	}

	/**
	 * Add pricing table controls.
	 */
	private function add_pricing_table_controls( $args ) {
		$this->add_control(
			new Hestia_Customizer_Control(
				$args['id'] . '_title',
				array(
					'default'           => $args['defaults']['title'],
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'    => $args['labels']['title'],
					'section'  => 'hestia_pricing',
					'priority' => 15,
				)
			)
		);

		$this->add_control(
			new Hestia_Customizer_Control(
				$args['id'] . '_icon',
				array(
					'transport' => 'postMessage',
				),
				array(
					'label'    => $args['labels']['icon'],
					'section'  => 'hestia_pricing',
					'priority' => 16,
				),
				'Hestia_Iconpicker'
			)
		);

		$this->add_control(
			new Hestia_Customizer_Control(
				$args['id'] . '_price',
				array(
					'default'           => $args['defaults']['price'],
					'sanitize_callback' => 'wp_kses_post',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'    => $args['labels']['price'],
					'section'  => 'hestia_pricing',
					'priority' => 20,
				)
			)
		);

		$this->add_control(
			new Hestia_Customizer_Control(
				$args['id'] . '_features',
				array(
					'default'           => $args['defaults']['features'],
					'sanitize_callback' => 'wp_kses_post',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'       => $args['labels']['features'],
					'description' => esc_html__( 'Separate your features by adding \n between lines.', 'hestia' ),
					'section'     => 'hestia_pricing',
					'priority'    => 25,
					'type'        => 'textarea',
				)
			)
		);

		$this->add_control(
			new Hestia_Customizer_Control(
				$args['id'] . '_link',
				array(
					'default'           => $args['defaults']['button_link'],
					'sanitize_callback' => 'esc_url_raw',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'    => $args['labels']['button_link'],
					'section'  => 'hestia_pricing',
					'priority' => 30,
				)
			)
		);

		$this->add_control(
			new Hestia_Customizer_Control(
				$args['id'] . '_text',
				array(
					'default'           => $args['defaults']['button_text'],
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'    => $args['labels']['button_text'],
					'section'  => 'hestia_pricing',
					'priority' => 35,
				)
			)
		);
	}

	/**
	 * Change controls.
	 *
	 * @return void
	 */
	public function change_controls() {
		$this->change_customizer_object( 'setting', 'hestia_pricing_title', 'default', esc_html__( 'Choose a plan for your next project', 'hestia' ) );
		$this->change_customizer_object( 'setting', 'hestia_pricing_subtitle', 'default', esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia' ) );
	}
}
