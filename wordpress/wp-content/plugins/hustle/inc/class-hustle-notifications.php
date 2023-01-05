<?php
/**
 * File for Hustle_Notifications class.
 *
 * @package Hustle
 * @since 4.2.0
 */

/**
 * Hustle_Notifications class.
 */
class Hustle_Notifications {

	const DISMISSED_USER_META = 'hustle_dismissed_notifications';

	/**
	 * Is Hustle free
	 *
	 * @since 4.2.0
	 * @var bool $is_free
	 */
	private $is_free;

	/**
	 * Whether the current user can update plugins.
	 *
	 * @since 4.2.2
	 * @var bool $user_can_update_plugins
	 */
	private $user_can_update_plugins;

	/**
	 * Instance of class.
	 *
	 * @since 4.2.2
	 * @var Hustle_Notifications|null $instance
	 */
	private static $instance = null;

	/**
	 * Return the plugin instance.
	 *
	 * @since 4.2.2
	 * @return Hustle_Notifications
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Hustle_Notifications constructor.
	 */
	public function __construct() {

		$this->is_free = Opt_In_Utils::_is_free();

		add_action( 'current_screen', array( $this, 'load_plugins_page_notices' ) );

		$cap = is_multisite() ? 'manage_network_plugins' : 'update_plugins';

		$this->user_can_update_plugins = current_user_can( $cap );

		add_action( 'wp_ajax_hustle_dismiss_notification', array( $this, 'dismiss_notification' ) );
	}

	/**
	 * Enqueues the notices to be shown within the plugin's pages.
	 *
	 * @since 4.2.0
	 */
	public function add_in_hustle_notices() {

		// Show upgrade notice only if this is free, and Hustle Pro is not already installed.
		if ( $this->is_free && ! file_exists( WP_PLUGIN_DIR . '/hustle/opt-in.php' ) ) {
			add_action( 'admin_notices', array( $this, 'show_pro_available_notice' ) );
		}

		if ( Hustle_Migration::check_tracking_needs_migration() ) {
			add_action( 'admin_notices', array( $this, 'show_migrate_tracking_notice' ) );
		}

		if ( Hustle_Migration::did_hustle_exist() ) {
			add_action( 'admin_notices', array( $this, 'show_review_css_after_migration_notice' ) );
		}

		if ( Hustle_Migration::is_migrated( 'hustle_40_migrated' ) ) {
			add_action( 'admin_notices', array( $this, 'show_visibility_behavior_update' ) );
		}

		add_action( 'admin_notices', array( $this, 'show_sendgrid_update_notice' ) );

		add_action( 'admin_notices', array( $this, 'show_provider_migration_notice' ) );
	}

	/**
	 * Print the notice
	 *
	 * @since 4.2.0
	 *
	 * @param string         $message Notice's message. Must be already escaped.
	 * @param boolean|string $name Notice's name.
	 * @param string         $type Notice's type error|success|info|warning.
	 * @param boolean        $is_dismissible Whether the notice is dismissible.
	 */
	private function show_notice( $message, $name = false, $type = 'info', $is_dismissible = false ) {
		$notices_types = array( 'info', 'success', 'error', 'warning' );

		$class  = 'notice';
		$class .= in_array( $type, $notices_types, true ) ? ' notice-' . $type : '';
		$nonce  = false;

		if ( $is_dismissible ) {
			$class .= ' is-dismissible';

			if ( $name ) {
				$class .= ' hustle-dismissible-admin-notice';
				$nonce  = wp_create_nonce( 'hustle_dismiss_notification' );
			}
		}
		?>

		<div
			<?php echo $name ? ' id="hustle-' . esc_attr( $name ) . '"' : ''; ?>
			class="<?php echo esc_attr( $class ); ?>"
			<?php echo $nonce ? ' data-nonce="' . esc_attr( $nonce ) . '"' : ''; ?>
			<?php echo $name ? ' data-name="' . esc_attr( $name ) . '"' : ''; ?>
		>
			<?php echo wp_kses_post( $message ); ?>
		</div>
		<?php
	}

	/**
	 * Dismiss the given notification
	 *
	 * @since 4.0.0
	 */
	public function dismiss_notification() {

		Opt_In_Utils::validate_ajax_call( 'hustle_dismiss_notification' );
		$notification_name = filter_input( INPUT_POST, 'name', FILTER_SANITIZE_STRING );

		if ( Hustle_Dashboard_Admin::MIGRATE_NOTICE_NAME !== $notification_name ) {
			self::add_dismissed_notification( $notification_name );
		} else {
			Hustle_Migration::mark_tracking_migration_as_completed();
		}

		wp_send_json_success();
	}

	/**
	 * Add a notification to the dismissed list.
	 *
	 * @since 4.0.0
	 *
	 * @param string $notification_name Notification slug.
	 */
	public static function add_dismissed_notification( $notification_name ) {

		$dismissed = get_user_meta( get_current_user_id(), self::DISMISSED_USER_META, true );

		if ( is_array( $dismissed ) ) {
			if ( in_array( $notification_name, $dismissed, true ) ) {
				return;
			}
			$dismissed[] = $notification_name;

		} else {
			$dismissed = array( $notification_name );
		}

		update_user_meta( get_current_user_id(), self::DISMISSED_USER_META, $dismissed );
	}

	/**
	 * Check if the given notification was dismissed.
	 *
	 * @since 4.0
	 *
	 * @param string $notification_name Notification slug.
	 * @return bool
	 */
	public static function was_notification_dismissed( $notification_name ) {
		$dismissed = get_user_meta( get_current_user_id(), self::DISMISSED_USER_META, true );

		return ( is_array( $dismissed ) && in_array( $notification_name, $dismissed, true ) );
	}

	/**
	 * Whether to show the 3.x to 4.x migration wizard modal
	 *
	 * @since 4.0.0
	 * @return boolean
	 */
	public static function is_show_migrate_tracking_notice() {

		if ( ! Hustle_Migration::check_tracking_needs_migration() ) {
			return false;
		}

		$page       = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$show_modal = filter_input( INPUT_GET, 'show-migrate', FILTER_VALIDATE_BOOLEAN );

		if ( $show_modal || ( Hustle_Data::ADMIN_PAGE === $page && ! self::was_notification_dismissed( Hustle_Dashboard_Admin::MIGRATE_MODAL_NAME ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Prints the html for the provider's migration notice.
	 *
	 * @since 4.2.0
	 *
	 * @param string $provider Provider's name.
	 * @param array  $provider_data Provider's data.
	 */
	private function get_provider_migration_notice_html( $provider, $provider_data = array() ) {
		$current_user = wp_get_current_user();

		$username = ! empty( $current_user->user_firstname ) ? $current_user->user_firstname : $current_user->user_login;

		$migrate_url = add_query_arg(
			array(
				'page'                    => Hustle_Data::INTEGRATIONS_PAGE,
				'show_provider_migration' => $provider,
				'integration_id'          => isset( $provider_data['id'] ) ? $provider_data['id'] : '',
			),
			'admin.php'
		);
		$provided_id = isset( $provider_data['id'] ) ? $provider . '_' . $provider_data['id'] : $provider;
		?>
		<div
			id='<?php echo esc_attr( "hustle_migration_notice__$provided_id" ); ?>'
			class="hustle-notice notice notice-warning hustle-provider-notice <?php echo esc_attr( "hustle_migration_notice__$provider" ); ?>"
			data-name="<?php echo esc_attr( $provider ); ?>"
			data-id="<?php echo isset( $provider_data['id'] ) ? esc_attr( $provider_data['id'] ) : ''; ?>"
			style="display: none"
		>
			<p>
			<?php $this->get_provider_migration_content( $provider, $username, $provider_data['name'] ); ?>
			</p>
			<p><a href="<?php echo esc_url( $migrate_url ); ?>" class="button-primary"><?php esc_html_e( 'Migrate Data', 'hustle' ); ?></a><a style="margin-left:20px; text-decoration: none;" href="#" class="dismiss-provider-migration-notice" data-name="<?php echo esc_attr( $provider ); ?>"><?php esc_html_e( 'Remind me later', 'hustle' ); ?></a></p>
		</div>
		<?php
	}

	/**
	 * Prints the copy for the notice for when migrating providers.
	 *
	 * @param string $provider Provider's slug.
	 * @param string $username User's name.
	 * @param string $identifier Aweber's account identifier.
	 */
	public function get_provider_migration_content( $provider, $username = '', $identifier = '' ) {
		switch ( $provider ) {
			case 'constantcontact':
				/* translators: user's name */
				$msg = sprintf( esc_html__( "Hey %s, we have updated our Constant Contact integration to support the latest v3.0 API. Since you are connected to the old API version, we recommend you to migrate your integration to the latest API version as we'll cease to support the deprecated API at some point.", 'hustle' ), $username );
				break;
			case 'infusionsoft':
				/* translators: user's name */
				$msg = sprintf( esc_html__( "Hey %s, we have updated our InfusionSoft integration to support the latest REST API. Since you are connected to the old API version, we recommend you to migrate your integration to the latest API version as we'll cease to support the deprecated API at some point.", 'hustle' ), $username );
				break;
			case 'aweber':
				/* translators: 1. user's name, */
				$msg = sprintf( esc_html__( "Hey %1\$s, we have updated our AWeber integration to support the oAuth 2.0. Since you are connected via oAuth 1.0, we recommend you to migrate your %2\$s integration to the latest authorization method as we'll cease to support the deprecated oAuth method at some point.", 'hustle' ), $username, $identifier );
				break;

			default:
				$msg = '';
				break;
		}

		echo esc_html( $msg );
	}

	/**
	 * Add the notices for the plugins page
	 *
	 * @since 4.2.0
	 */
	public function load_plugins_page_notices() {

		// Skip if the current page doesn't have 'current_screen' defined.
		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		$current_screen = get_current_screen();

		if ( 'plugins' !== $current_screen->id && 'plugins-network' !== $current_screen->id ) {
			return;
		}

		// Display admin notice about plugin deactivation.
		if ( is_multisite() ) {
			add_action( 'network_admin_notices', array( $this, 'hustle_activated_deactivated' ) );
		}

		// We want to show this in the subsite's plugin page as well.
		add_action( 'admin_notices', array( $this, 'hustle_activated_deactivated' ) );

		$this->add_in_hustle_row_messages();
	}

	/**
	 * Add notices in the plugin's row.
	 *
	 * @since 4.2.2
	 */
	private function add_in_hustle_row_messages() {

		if ( $this->is_free ) {
			add_action( 'in_plugin_update_message-wordpress-popup/popover.php', array( $this, 'in_plugin_update_message' ), 10, 2 );

			require_once Opt_In::$plugin_path . 'lib/free-dashboard/module.php';
			// Register the current plugin.
			do_action(
				'wdev-register-plugin', // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
				plugin_basename( __FILE__ ),             // 1. Plugin ID
				'Hustle',                                // 2. Plugin Title
				'/plugins/wordpress-popup/',             // 3. https://wordpress.org
				__( 'Sign Me Up', 'hustle' ), // 4. Email Button CTA
				'f68d9fbc51'                             // 5. Mailchimp List id
			);

		} else {

			add_action(
				'load-plugins.php',
				function() {
					add_action( 'after_plugin_row_hustle/opt-in.php', array( $this, 'in_plugin_update_message' ), 10, 3 );
				},
				22 // Must be called after Dashboard which is 21.
			);

			// Load dashboard notice.
			global $wpmudev_notices;
			$wpmudev_notices[] = array(
				'id'      => 1107020,
				'name'    => 'Hustle',
				'screens' => array(
					'toplevel_page_hustle',
					'optin-pro_page_inc_optin',
				),
			);
			require_once Opt_In::$plugin_path . 'lib/wpmudev-dashboard/wpmudev-dash-notification.php';
		}
	}

	/**
	 * Add notice to Hustle's row in the Plugins page
	 * Alert the members they should check out their modules when upgrading to 4.1.0.
	 *
	 * @since 4.0.4
	 *
	 * @param string $project_id Project ID.
	 * @param array  $plugin_data Plugin data.
	 * @param string $project_name Project name.
	 */
	public function in_plugin_update_message( $project_id, $plugin_data, $project_name = '' ) {
		$plugin_data    = (object) $plugin_data;
		$needed_version = $this->is_free ? '7.1' : '4.1';

		if ( empty( $plugin_data->new_version ) || empty( $plugin_data->plugin ) || $needed_version !== $plugin_data->new_version ) {
			return;
		}
		$heads_up = __( 'Heads up!', 'hustle' );
		/* translators: current version */
		$title = sprintf( __( 'We’ve fixed visibility conditions in Hustle %1$s which may affect the visibility behavior of your pop-ups and other modules.', 'hustle' ), $plugin_data->new_version );
		/* translators: current version */
		$description = sprintf( __( 'Prior to Hustle %1$s, the visibility engine would require you to set rules for every post type your theme used, not just the ones you specified to make it appear on correct pages. We’ve updated this behavior to only display modules based on the post types explicitly defined in your conditions. For Example, if you add a “Pages” condition to show your module on 1 page only, you’d no longer have to add other post type conditions to hide your module on them. After updating, we recommend double-checking your Hustle modules’ visibility conditions are working as expected.', 'hustle' ), $plugin_data->new_version );

		echo "<script type='text/javascript'>
			(function ($) {
				$(document).ready(function (e) {
					$( '.wp-list-table tr[data-plugin=\"" . esc_attr( $plugin_data->plugin ) . "\"] .notice-warning' ).append( '<hr><br><span><strong>" . esc_html( $heads_up ) . '</strong> ' . esc_html( $title ) . '</span><br><br><span>' . esc_html( $description ) . "</span><br><br>' );
				});
			})(jQuery);
		</script>";
	}

	/**
	 * **************************
	 * NOTICES
	 * **************************
	 */

	/**
	 * Available notifications.
	 *
	 * In plugins.php page.
	 *
	 * @see Hustle_Notifications::hustle_activated_deactivated()
	 *
	 * In Hustle pages.
	 * @see Hustle_Notifications::show_pro_available_notice()
	 * @see Hustle_Notifications::show_migrate_tracking_notice()
	 * @see Hustle_Notifications::show_review_css_after_migration_notice()
	 * @see Hustle_Notifications::show_sendgrid_update_notice()
	 * @see Hustle_Notifications::show_provider_migration_notice()
	 * @see Hustle_Notifications::show_visibility_behavior_update()
	 */

	/**
	 * Displays a notice on plugin activation and deactivaton.
	 * This is shown when either free or pro is active, and the other version (free or pro) is activated.
	 *
	 * @since 2.1.4
	 * @since 4.2.0 Moved from Opt_In to this class.
	 */
	public function hustle_activated_deactivated() {
		// Show the notice only to users who can do something about this.
		if ( ! $this->user_can_update_plugins ) {
			return;
		}

		// For Pro.
		if ( get_site_option( 'hustle_free_deactivated' ) ) {
			$message = '<p>' . esc_html__( 'Congratulations! You have activated Hustle Pro! We have automatically deactivated the free version.', 'hustle' ) . '</p>';
			$this->show_notice( $message, false, 'success', true );

			delete_site_option( 'hustle_free_deactivated' );
		}

		// For Free.
		if ( get_site_option( 'hustle_free_activated' ) ) {
			$message = '<p>' . esc_html__( 'You already have Hustle Pro activated. If you really wish to go back to the free version of Hustle, please deactivate the Pro version first', 'hustle' ) . '</p>';
			$this->show_notice( $message, false, 'error', true );

			delete_site_option( 'hustle_free_activated' );
		}
	}

	/**
	 * Displays an admin notice when the user is an active member and doesn't have Hustle Pro installed
	 * Shown in hustle pages. Per user notification.
	 *
	 * @since 3.0.6
	 */
	public function show_pro_available_notice() {
		// The notice was already dismissed.
		if ( self::was_notification_dismissed( 'hustle_pro_is_available' ) ) {
			return;
		}

		// Show the notice only to users who can do something about this and who are members.
		if ( ! $this->user_can_update_plugins || ! Opt_In_Utils::is_hustle_included_in_membership() ) {
			return;
		}

		$link = lib3()->html->element(
			array(
				'type'  => 'html_link',
				'value' => esc_html__( 'Upgrade' ),
				'url'   => esc_url( Opt_In_Utils::get_link( 'install_plugin' ) ),
				'class' => 'button-primary',
			),
			true
		);

		$profile = get_option( 'wdp_un_profile_data', '' );
		$name    = ! empty( $profile ) ? $profile['profile']['name'] : __( 'Hey', 'hustle' );

		$message = '<p>';
		/* translators: user's name */
		$message .= sprintf( esc_html__( '%s, it appears you have an active WPMU DEV membership but haven\'t upgraded Hustle to the pro version. You won\'t lose an any settings upgrading, go for it!', 'hustle' ), $name );
		$message .= '</p>';
		$message .= '<p>' . $link . '</p>';

		// used id hustle-notice-pro-is-available before.
		$this->show_notice( $message, 'hustle_pro_is_available', 'info', true );
	}

	/**
	 * Display the notice to migrate tracking and subscriptions data.
	 * Shown in hustle pages. Per user notification.
	 *
	 * @since 4.0.0
	 */
	public function show_migrate_tracking_notice() {

		if ( ! self::is_show_migrate_tracking_notice() ) {
			return;
		}

		$migrate_url = add_query_arg(
			array(
				'page'         => Hustle_Data::ADMIN_PAGE,
				'show-migrate' => 'true',
			),
			'admin.php'
		);

		$current_user = wp_get_current_user();
		$username     = ! empty( $current_user->user_firstname ) ? $current_user->user_firstname : $current_user->user_login;

		$message = '<p>';
		/* translators: user's name */
		$message .= sprintf( esc_html__( 'Hey %s, nice work on updating the Hustle! However, you need to migrate the data of your existing modules such as tracking data and email list manually.', 'hustle' ), esc_html( $username ) );
		$message .= '</p>';
		$message .= '<p><a href="' . esc_url( $migrate_url ) . '" class="button-primary">' . esc_html__( 'Migrate Data', 'hustle' ) . '</a><a href="#" class="hustle-notice-dismiss" style="margin-left:20px;">' . esc_html__( 'Dismiss', 'hustle' ) . '</a></p>';

		$this->show_notice( $message, 'tracking-migration-notice', 'warning', false );
	}

	/**
	 * Display a notice for reviewing the modules' custom css after migration.
	 * Shown in hustle pages. Per user notification.
	 *
	 * @since 4.0.0
	 */
	public function show_review_css_after_migration_notice() {
		if ( self::was_notification_dismissed( '40_custom_style_review' ) ) {
			return;
		}

		$current_user = wp_get_current_user();
		$username     = ! empty( $current_user->user_firstname ) ? $current_user->user_firstname : $current_user->user_login;

		$message  = '<p>';
		$message .= sprintf(
			/* translators: user's name */
			esc_html__( "Hey %s, we have improved Hustle’s front-end code in this update, which included modifying some CSS classes. Any custom CSS you were using may have been affected. We recommend reviewing the modules (which were using custom CSS) to ensure they don't need any adjustments.", 'hustle' ),
			esc_html( $username )
		);
		$message .= '</p>';

		$this->show_notice( $message, '40_custom_style_review', 'warning', true );
	}

	/**
	 * Display a notice for updating Marketing Campaings via Sendgrid.
	 * Shown in hustle pages. Per user notification.
	 *
	 * @since 4.0.4
	 */
	public function show_sendgrid_update_notice() {
		// Check if the notification is already dismissed.
		if ( self::was_notification_dismissed( 'hustle_sendgrid_update_showed' ) ) {
			return;
		}
		// Check if there is no Sendgrid intagration.
		if ( ! $this->is_provider_integrated( 'sendgrid' ) ) {
			self::add_dismissed_notification( 'hustle_sendgrid_update_showed' );
			return;
		}

		$integrations_url = add_query_arg(
			array( 'page' => Hustle_Data::INTEGRATIONS_PAGE ),
			'admin.php'
		);

		$current_user = wp_get_current_user();
		$username     = ! empty( $current_user->user_firstname ) ? $current_user->user_firstname : $current_user->user_login;

		$message  = '<p>';
		$message .= sprintf(
			/* translators: 1. user's name, 2. opening 'a' tag to sendgrid link, 3. closing 'a' tag, 4. opening 'b' tag, 5. closing 'b' tag */
			esc_html__( 'Hey %1$s, we have updated our %4$sSendGrid%5$s integration to support the %2$snew Marketing Campaigns%3$s. You need to review your existing SendGrid integration(s) and select the Marketing Campaigns version (new or legacy) you are using to avoid failed API calls.', 'hustle' ),
			esc_html( $username ),
			'<a href="https://sendgrid.com/blog/new-era-marketing-campaigns/" target="_blank">',
			'</a>',
			'<b>',
			'</b>'
		);
		$message .= '</p>';
		$message .= '<p><a href="' . esc_url( $integrations_url ) . '" class="button-primary">' . esc_html__( 'Review Integrations', 'hustle' ) . '</a></p>';

		$this->show_notice( $message, 'hustle_sendgrid_update_showed', 'warning', true );
	}

	/**
	 * Shows the provider's migration notice.
	 * Shown in hustle pages. Per user notification.
	 *
	 * @since 4.2.0
	 */
	public function show_provider_migration_notice() {

		$aweber_instances = get_option( 'hustle_provider_aweber_settings' );
		if ( ! empty( $aweber_instances ) ) {
			foreach ( $aweber_instances as $key => $instance ) {
				if ( ! array_key_exists( 'access_oauth2_token', $instance ) || empty( $instance['access_oauth2_token'] ) ) {
					$provider_data = array(
						'name' => $instance['name'],
						'id'   => $key,
					);
					$this->get_provider_migration_notice_html( 'aweber', $provider_data );
				}
			}
		}
	}

	/**
	 * Display a notice for reviewing visibility conditions after updating.
	 * Shown in hustle pages. Per user notification.
	 *
	 * @since 4.1.0
	 */
	public function show_visibility_behavior_update() {
		if ( self::was_notification_dismissed( '41_visibility_behavior_update' ) ) {
			return;
		}
		if ( $this->is_fresh_install() ) {
			return;
		}
		$url_params = array(
			'page'              => Hustle_Data::ADMIN_PAGE,
			'review-conditions' => 'true',
		);
		$url        = add_query_arg( $url_params, 'admin.php' );
		$link       = lib3()->html->element(
			array(
				'type'  => 'html_link',
				'value' => esc_html__( 'Check conditions', 'hustle' ),
				'url'   => esc_url_raw( $url ),
				'class' => 'button-primary',
			),
			true
		);

		$version = $this->is_free ? '7.1' : '4.1';

		ob_start();
		?>
		<p>
			<b><?php esc_html_e( 'Hustle - Module visibility behaviour update', 'hustle' ); ?></b>
		</p>

		<p>
			<?php /* translators: 4.1 version pro or free */ ?>
			<?php printf( esc_html__( 'Hustle %s fixes a visibility bug which may affect the visibility behavior of your popups and other modules. Please review the visibility conditions of each of your modules to ensure they will appear as you expect.', 'hustle' ), esc_attr( $version ) ); ?>
		</p>

		<p>
			<?php echo $link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<a href="#" class="dismiss-notice" style="margin-left:14px;"><?php esc_html_e( 'Dismiss', 'hustle' ); ?></a>
		</p>
		<?php
		$message = ob_get_clean();

		$this->show_notice( $message, '41_visibility_behavior_update', 'warning', true );
	}

	/**
	 * **************************
	 * NOTICES
	 * **************************
	 */
	/**
	 * Check is $provider integrated or not
	 *
	 * @since 4.0.4
	 * @param string $provider Provider slug.
	 * @return bool
	 */
	private function is_provider_integrated( $provider ) {
		$providers = Hustle_Provider_Utils::get_registered_addons_grouped_by_connected();
		$connected = wp_list_pluck( $providers['connected'], 'slug' );

		return in_array( $provider, $connected, true );
	}

	/**
	 * Checks whether the current install is a fresh one.
	 * It returns false if there was a version installed before.
	 *
	 * @since 4.3.0
	 *
	 * @return boolean
	 */
	private function is_fresh_install() {
		return ! get_site_option( 'hustle_previous_version', false );
	}
}