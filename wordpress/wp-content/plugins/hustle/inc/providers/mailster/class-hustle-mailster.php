<?php
/**
 * Hustle_Mailster class.
 *
 * @package hustle
 *
 * @since 4.4.0
 */

/**
 * Class Hustle_Mailster.
 *
 * @since 4.4.0
 */
class Hustle_Mailster extends Hustle_Provider_Abstract {

	/**
	 * Provider Instance
	 *
	 * @since 4.4.0
	 *
	 * @var self|null
	 */
	protected static $_instance;

	/**
	 * Provider slug.
	 *
	 * @since 4.4.0
	 *
	 * @var string
	 */
	protected $_slug = 'mailster';

	/**
	 * Provider version.
	 *
	 * @var string
	 */
	protected $_version = '1.0.0';

	/**
	 * Provider's name class name.
	 *
	 * @since 4.4.0
	 *
	 * @var string
	 */
	protected $_class = __CLASS__;

	/**
	 * Provider's title.
	 *
	 * @since 4.4.0
	 *
	 * @var string
	 */
	protected $_title = 'Mailster';

	/**
	 * Whether there can be multiple global instances.
	 *
	 * @since 4.4.0
	 *
	 * @var string
	 */
	protected $is_multi_on_global = false;

	/**
	 * Set of options that must be set in order to be connected.
	 *
	 * @since 4.4.0
	 *
	 * @var array
	 */
	protected $_completion_options = array( 'active' );

	/**
	 * Class name of form settings.
	 *
	 * @since 4.4.0
	 *
	 * @var string
	 */
	protected $_form_settings = 'Hustle_Mailster_Form_Settings';

	/**
	 * Class name of form hooks
	 *
	 * @since 4.4.0
	 * @var string
	 */
	protected $_form_hooks = 'Hustle_Mailster_Form_Hooks';

	/**
	 * Get Instance
	 *
	 * @return self|null
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Provider constructor.
	 */
	public function __construct() {
		$this->_icon_2x = plugin_dir_url( __FILE__ ) . 'images/icon.png';
		$this->_logo_2x = plugin_dir_url( __FILE__ ) . 'images/logo.png';
	}

	/**
	 * Checks whether Mailster is active.
	 *
	 * @since 4.4.0
	 *
	 * @return boolean
	 */
	private function is_plugin_active() {
		return function_exists( 'mailster' );
	}

	/**
	 * Checks if the settings are completed.
	 *
	 * @since 4.4.0
	 *
	 * @param string $multi_id Multi-integration ID, if any.
	 * @return boolean
	 */
	protected function settings_are_completed( $multi_id = '' ) {
		return $this->is_plugin_active();
	}

	/**
	 * Get the wizard callbacks for the global settings.
	 *
	 * @since 4.4.0
	 *
	 * @return array
	 */
	public function settings_wizards() {
		return array(
			array(
				'callback'     => array( $this, 'configure' ),
				'is_completed' => array( $this, 'settings_are_completed' ),
			),
		);
	}

	/**
	 * Configure the API key settings. Global settings.
	 *
	 * @since 4.4.0
	 *
	 * @param array $submitted_data The data submitted.
	 * @param bool  $is_submit Whether the current request is a submission.
	 *
	 * @return array
	 */
	public function configure( $submitted_data, $is_submit ) {
		$has_errors = false;
		$active     = $this->is_connected();

		if ( $is_submit ) {

			$active = ! empty( $submitted_data['active'] );
			// If not active, activate it.
			if ( ! Hustle_Provider_Utils::is_provider_active( $this->_slug ) ) {

				// TODO: Wrap this in a friendlier method.
				$activated = Hustle_Providers::get_instance()->activate_addon( $this->_slug );
				if ( ! $activated ) {
					$error_message = esc_html( $this->provider_connection_falied() );
					$has_errors    = true;
				} else {
					$this->save_settings_values( array( 'active' => $active ) );
				}
			} else {
				$this->save_settings_values( array( 'active' => $active ) );
			}

			if ( ! $has_errors ) {

				return array(
					'html'         => Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Mailster Added', 'hustle' ), __( 'You can now go to your pop-ups, slide-ins and embeds and assign them to this integration', 'hustle' ) ),
					'buttons'      => array(
						'close' => array(
							'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Close', 'hustle' ), 'sui-button-ghost sui-button-center', 'close' ),
						),
					),
					'redirect'     => false,
					'has_errors'   => false,
					'notification' => array(
						'type' => 'success',
						'text' => '<strong>' . $this->get_title() . '</strong> ' . __( 'successfully connected', 'hustle' ),
					),
				);

			}
		}

		$options = array(
			array(
				'type'  => 'hidden',
				'name'  => 'active',
				'value' => 1,
			),
		);

		$step_html = Hustle_Provider_Utils::get_integration_modal_title_markup(
			__( 'Connect Mailster', 'hustle' ),
			__( 'Mailster lets you create, manage, and send email newsletter campaings from within your WordPress site.', 'hustle' )
		);

		if ( ! $this->is_plugin_active() ) {
			$has_errors    = true;
			$error_message = sprintf(
				/* translators: 1. opening 'a' tag to the Mailster's wp page, 2. closing 'a' tag, 3. opening 'b' tag, 4. closing 'b' tag */
				esc_html__( 'This integration requires the %1$sMailster Newsletter Plugin%2$s, %3$sversion 2.2%4$s or newer. Install and activate it and try again.', 'hustle' ),
				'<a href="https://mailster.co/" target="_blank">',
				'</a>',
				'<b>',
				'</b>'
			);

			$error_notice = array(
				'type'  => 'notice',
				'icon'  => 'info',
				'class' => 'sui-notice-error',
				'value' => $error_message,
			);
			array_unshift( $options, $error_notice );
		}

		$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

		if ( ! $this->is_plugin_active() ) {
			$buttons = array(
				'close' => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Close', 'hustle' ), 'sui-button-ghost sui-button-center', 'close' ),
				),
			);
		} elseif ( $this->is_connected() ) {
			$buttons = array(
				'disconnect' => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup(
						__( 'Disconnect', 'hustle' ),
						'sui-button-ghost sui-button-center',
						'disconnect',
						true
					),
				),
			);
		} else {
			$buttons = array(
				'connect' => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup(
						__( 'Activate', 'hustle' ),
						'sui-button-center',
						'connect',
						true
					),
				),
			);

		}

		$response = array(
			'html'       => $step_html,
			'buttons'    => $buttons,
			'has_errors' => $has_errors,
		);

		return $response;
	}
}