<?php
/**
 * Mailpoet v3 class.
 *
 * @package hustle
 *
 * @since 4.4.0
 */

/**
 * Class Hustle_Mailpoet.
 *
 * @since 4.4.0
 */
class Hustle_Mailpoet extends Hustle_Provider_Abstract {

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
	protected $_slug = 'mailpoet';

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
	protected $_title = 'Mailpoet';

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
	protected $_form_settings = 'Hustle_Mailpoet_Form_Settings';

	/**
	 * Class name of form hooks
	 *
	 * @since 4.4.0
	 * @var string
	 */
	protected $_form_hooks = 'Hustle_Mailpoet_Form_Hooks';

	/**
	 * Mailpoet API instance.
	 *
	 * @since 4.4.0
	 *
	 * @var null|\MailPoet\API\API
	 */
	private $api;

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
	 * Gets an instance of Mailpoet's API.
	 *
	 * @since 4.4.0
	 *
	 * @return \MailPoet\API\API
	 */
	public function get_api() {
		if ( is_null( $this->api ) ) {
			$this->api = \MailPoet\API\API::MP( 'v1' );
		}
		return $this->api;
	}

	/**
	 * Checks whether Mailpoet v3 is active.
	 *
	 * @since 4.4.0
	 *
	 * @return boolean
	 */
	private function is_plugin_active() {
		return class_exists( \MailPoet\API\API::class );
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
					'html'         => Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Mailpoet Added', 'hustle' ), __( 'You can now go to your pop-ups, slide-ins and embeds and assign them to this integration', 'hustle' ) ),
					'buttons'      => array(
						'close' => array(
							'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Close', 'hustle' ), 'sui-button-ghost', 'close' ),
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

		if ( ! $this->is_plugin_active() ) {
			$has_errors    = true;
			$error_message = sprintf(
				/* translators: 1. opening 'a' tag to the Mailpoet's wp page, 2. closing 'a' tag */
				esc_html__( 'This integration requires the %1$sMailpoet 3%2$s. Install and activate it and try again.', 'hustle' ),
				'<a href="https://wordpress.org/plugins/mailpoet/" target="_blank">',
				'</a>'
			);
		}

		$options = array(
			array(
				'type'  => 'hidden',
				'name'  => 'active',
				'value' => 1,
			),
		);

		$step_html = Hustle_Provider_Utils::get_integration_modal_title_markup(
			__( 'Connect Mailpoet 3', 'hustle' ),
			__( 'Mailpoet lets you send beautiful emails that reach inboxes every time and create loyal subscribers.', 'hustle' )
		);

		if ( $has_errors ) {
			$error_notice = array(
				'type'  => 'notice',
				'icon'  => 'info',
				'class' => 'sui-notice-error',
				'value' => $error_message,
			);
			array_unshift( $options, $error_notice );

		}

		$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

		$is_edit = $this->is_connected() ? true : false;

		if ( ! $this->is_plugin_active() ) {
			$buttons = array(
				'close' => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Close', 'hustle' ), 'sui-button-ghost sui-button-center', 'close' ),
				),
			);
		} elseif ( $is_edit ) {
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