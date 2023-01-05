<?php

/**
 * Conditions utils
 *
 * Most of the methods are courtesy Philipp Stracker
 *
 * Class Opt_In_Utils
 */
class Opt_In_Utils {

	/**
	 * CPT
	 *
	 * @var array
	 */
	private static $post_types;

	/**
	 * Array of administrator roles
	 *
	 * @var array
	 */
	private static $admin_roles;

	/**
	 * Returns the referrer.
	 *
	 * @return string
	 */
	public function get_referrer() {
		$referrer = '';

		$is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			|| ( ! empty( $_POST['_po_method_'] ) && 'raw' === $_POST['_po_method_'] ); // WPCS: CSRF ok.

		if ( isset( $_REQUEST['thereferrer'] ) ) { // WPCS: CSRF ok.
			$referrer = $_REQUEST['thereferrer']; // WPCS: CSRF ok.
		} elseif ( ! $is_ajax && isset( $_SERVER['HTTP_REFERER'] ) ) {
			// When doing Ajax request we NEVER use the HTTP_REFERER!
			$referrer = $_SERVER['HTTP_REFERER'];
		}

		return $referrer;
	}

	/**
	 * Tests if the current referrer is one of the referers of the list.
	 * Current referrer has to be specified in the URL param "thereferer".
	 *
	 * @param  array $list List of referers to check.
	 * @return bool
	 */
	public function test_referrer( $list ) {
		$response = false;
		if ( is_string( $list ) ) {
			$list = preg_split( '/\r\n|\r|\n/', $list );
		}
		if ( ! is_array( $list ) ) {
			return true;
		}

		$referrer = $this->get_referrer();

		if ( ! empty( $referrer ) ) {
			foreach ( $list as $item ) {
				$item = trim( $item );
				$res  = stripos( $referrer, $item );
				if ( false !== $res ) {
					$response = true;
					break;
				}
			}
		}

		return $response;
	}

	/**
	 * Returns current url
	 * should only be called after plugins_loaded hook is fired
	 *
	 * @return string
	 */
	public static function get_current_url() {
		if ( ! did_action( 'plugins_loaded' ) ) {
			new Exception( 'This method should only be called after plugins_loaded hook is fired' ); }

		global $wp;
		return add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
	}

	/**
	 * Checks if user is allowed to perform the ajax actions
	 *
	 * @since 4.0
	 * @param array $capability Hustle capability
	 * @param int   $module_id Optional. Module id
	 */
	public static function is_user_allowed_ajax( $capability, $module_id = null ) {
		if ( is_null( $module_id ) ) {
			$allowed = current_user_can( $capability );
		} else {
			$allowed = self::is_user_allowed( $capability, $module_id );
		}

		if ( ! $allowed ) {
			wp_send_json_error( __( 'Invalid request, you are not allowed to make this request', 'hustle' ) );
		}
	}

	/**
	 * Check is it admin role or not
	 *
	 * @param string|array $role
	 * @return bool
	 */
	public static function is_admin_role( $role ) {
		$admin_roles = array_keys( self::get_admin_roles() );

		if ( ! is_array( $role ) ) {
			return in_array( $role, $admin_roles, true );
		}

		return (bool) array_intersect( $role, $admin_roles );
	}

	/**
	 * Get admin role array
	 *
	 * @since 4.1.0
	 * @return array
	 */
	public static function get_admin_roles() {

		if ( is_null( self::$admin_roles ) ) {
			$admins    = array();
			$all_roles = wp_roles();

			if ( $all_roles->is_role( 'administrator' ) ) {
				$admins['administrator'] = ucfirst( translate_user_role( 'administrator', 'hustle' ) );

			} else {
				foreach ( $all_roles->roles as $name => $data ) {
					if ( ! empty( $data['capabilities']['manage_options'] ) && true === $data['capabilities']['manage_options'] ) {
						$admins[ $name ] = $data['name'];
					}
				}
			}

			self::$admin_roles = apply_filters( 'hustle_get_admin_roles', $admins );
		}

		return self::$admin_roles;
	}

	/**
	 * Checks if user has the capability
	 *
	 * @since 4.0
	 * @param array $capability Hustle capability
	 * @param int   $module_id Optional. Module id
	 * @return bool
	 */
	public static function is_user_allowed( $capability, $module_id = null ) {

		// Super admins can do everything.
		if ( current_user_can( 'setup_network' ) ) {
			return true;
		}

		$user               = wp_get_current_user();
		$current_user_caps  = (array) $user->allcaps;
		$current_user_roles = (array) $user->roles;

		if ( self::is_admin_role( $current_user_roles ) ) {
			// If editing a module and the user is godish, allow.
			return true;

		} elseif ( 'hustle_edit_module' === $capability && ! empty( $current_user_caps['hustle_create'] ) ) {
			// If the user can create, it also can edit. Allow.
			return true;

		} elseif ( is_null( $module_id ) ) {
			// If we're not editing a module, check for the requested capability.
			return ! empty( $current_user_caps[ $capability ] );

		} else {

			// If editing a module and the user isn't godish...
			$module = new Hustle_Module_Model( $module_id );

			// If the module isn't valid, abort.
			if ( is_wp_error( $module ) ) {
				return false;
			}

			// Check for the specific allowed roles.
			$allowed_roles = $module->get_edit_roles();
			return (bool) array_intersect( $allowed_roles, $current_user_roles );
		}

		return false;
	}

	/**
	 * Get's the status of the membership.
	 *
	 * @since 4.3.3
	 *
	 * @return string
	 */
	public static function get_membership_status() {
		// Dashboard is active.
		if ( class_exists( 'WPMUDEV_Dashboard' ) ) {
			// Get membership type.
			$status = WPMUDEV_Dashboard::$api->get_membership_type();
			// Check if API key is available.
			if ( 'free' === $status && WPMUDEV_Dashboard::$api->has_key() ) {
				$status = 'expired';
			}
		} else {
			$status = 'free';
		}
		return $status;
	}

	/**
	 * Checks whether Hustle is included in the membership.
	 *
	 * @since 4.3.3
	 *
	 * @return boolean
	 */
	public static function is_hustle_included_in_membership() {
		if ( class_exists( 'WPMUDEV_Dashboard' ) ) {
			if ( method_exists( 'WPMUDEV_Dashboard_Api', 'get_membership_projects' ) && method_exists( 'WPMUDEV_Dashboard_Api', 'get_membership_type' ) ) {
				$type     = WPMUDEV_Dashboard::$api->get_membership_type();
				$projects = WPMUDEV_Dashboard::$api->get_membership_projects();

				if ( ( 'unit' === $type && in_array( 1107020, $projects, true ) ) || ( 'single' === $type && 1107020 === $projects ) ) {
					return true;
				}

				if ( function_exists( 'is_wpmudev_member' ) ) {
					return is_wpmudev_member();
				}

				return false;
			}
		}

		return false;
	}

	/**
	 * Return URL link for wp.org, wpmudev, support, live chat, docs, installing plugin.
	 *
	 * @since 4.3.4
	 *
	 * @param string      $link_for The section to retrieve the link for.
	 * @param string|bool $campaign  Utm campaign tag to be used in link.
	 *
	 * @return string
	 */
	public static function get_link( $link_for, $campaign = false ) {
		$domain   = 'https://wpmudev.com';
		$wp_org   = 'https://wordpress.org';
		$utm_tags = ! $campaign ? '' : "?utm_source=hustle&utm_medium=plugin&utm_campaign={$campaign}";

		switch ( $link_for ) {
			case 'chat':
				$link = "{$domain}/live-support/{$utm_tags}";
				break;
			case 'plugin':
				$link = "{$domain}/project/hustle/{$utm_tags}";
				break;
			case 'support':
				if ( 'full' === self::get_membership_status() ) {
					$link = "{$domain}/hub/support/{$utm_tags}#get-support";
				} else {
					$link = "{$wp_org}/support/plugin/wordpress-popup";
				}
				break;
			case 'docs':
				$link = "{$domain}/docs/wpmu-dev-plugins/hustle/{$utm_tags}";
				break;
			case 'install_plugin':
				if ( self::is_hustle_included_in_membership() ) {
					// Return the pro plugin URL.
					$url  = WPMUDEV_Dashboard::$ui->page_urls->plugins_url;
					$link = $url . '#pid=1107020';
				} else {
					// Return the free URL.
					$link = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=wordpress-popup' ), 'install-plugin_wordpress-popup' );
				}
				break;
			case 'roadmap':
				$link = "{$domain}/roadmap/{$utm_tags}";
				break;
			case 'wpmudev':
				$link = "{$domain}/{$utm_tags}";
				break;
			case 'blog':
				$link = "{$domain}/blog/{$utm_tags}";
				break;
			default:
				$link = '';
				break;
		}

		return $link;
	}

	/**
	 * Checks if the ajax
	 *
	 * @since 1.0
	 * @param $action string ajax call action name
	 */
	public static function validate_ajax_call( $action ) {
		if ( ! check_ajax_referer( $action, false, false ) ) {
			wp_send_json_error( __( 'Invalid request, you are not allowed to make this request', 'hustle' ) ); }
	}

	/**
	 * Verify if current version is FREE
	 **/
	public static function _is_free() {
		$is_free = ! file_exists( Opt_In::$plugin_path . 'lib/wpmudev-dashboard/wpmudev-dash-notification.php' );

		return $is_free;
	}

	/**
	 * Get the user roles options.
	 *
	 * @since 4.0
	 *
	 * @return array
	 */
	public static function get_user_roles() {

		global $wp_roles;
		$roles = $wp_roles->get_names();

		return apply_filters( 'hustle_get_module_permissions_roles', $roles );
	}

	// ====================================
	// INTEGRATIONS
	// ====================================

	/**
	 * Used for sanitizing form submissions.
	 * This method will do a simple sanitation of $post_data. It applies sanitize_text_field() to the keys and values of the first level array.
	 * The keys from second level arrays are converted to numbers, and their values are sanitized with sanitize_text_field() as well.
	 * This method doesn’t do an exhaustive sanitation, so you should handled special cases if your integration requires something different.
	 * The names passed on $required_fields are searched into $post_data array keys. If the key is not set, an array with the key “errors” is returned.
	 *
	 * @since 3.0.5
	 * @param array $post_data The data to be sanitized and validated.
	 * @param array $required_fields Fields that must exist on $post_data so the validation doesn't fail.
	 * @return array
	 */
	public static function validate_and_sanitize_fields( $post_data, $required_fields = array() ) {
		// for serialized data or form
		if ( ! is_array( $post_data ) && is_string( $post_data ) ) {
			$post_string = $post_data;
			$post_data   = array();
			wp_parse_str( $post_string, $post_data );
		}

		$errors = array();
		foreach ( $required_fields as $key => $required_field ) {
			if ( ! isset( $post_data[ $required_field ] ) || ( empty( trim( $post_data[ $required_field ] ) ) && '0' !== $post_data[ $required_field ] ) ) {
				/* translators: ... */
				$errors[ $required_field ] = sprintf( __( 'Field %s is required.', 'hustle' ), $required_field );
				continue;
			}
		}

		if ( ! empty( $errors ) ) {
			return array( 'errors' => $errors );
		}

		$sanitized_data = array();
		foreach ( $post_data as $key => $post_datum ) {
			/**
			 * Sanitize here every request so we dont need to sanitize it again on other methods,
			 *  unless special treatment is required.
			 */
			$sanitized_data[ sanitize_text_field( $key ) ] = self::sanitize_text_input_deep( $post_datum );
		}

		return $sanitized_data;
	}

	/**
	 * Sanitizes the values of a multi-dimensional array.
	 * The keys of the sub-arrays are converted to numerical arrays.
	 * Sub-arrays are expected to have numerical indexes.
	 *
	 * @since 3.0.5
	 * @param array|string $value
	 * @return string
	 */
	public static function sanitize_text_input_deep( $value, $key = null ) {
		$value = is_array( $value ) ?
					array_map( array( 'Opt_In_Utils', 'sanitize_text_input_deep' ), $value, array_keys( $value ) ) :
					sanitize_text_field( $value );

		return $value;
	}

	/**
	 * Adds an entry to debug log
	 *
	 * By default it will check `WP_DEBUG` and HUSTLE_DEBUG to decide whether to add the log,
	 * then will check `filters`.
	 *
	 * @since 3.0.5
	 * @since 4.0 also checks HUSTLE_DEBUG
	 */
	public static function maybe_log() {

		$wp_debug_enabled = ( defined( 'WP_DEBUG' ) && WP_DEBUG );

		$enabled = ( defined( 'HUSTLE_DEBUG' ) && HUSTLE_DEBUG );

		$stored_settings       = Hustle_Settings_Admin::get_general_settings();
		$debug_setting_enabled = '1' === $stored_settings['debug_enabled'];

		$enabled = ( $wp_debug_enabled && ( $debug_setting_enabled || $enabled ) );

		/**
		 * Filter to enable or disable log for Hustle
		 *
		 * By default it will check `WP_DEBUG`
		 *
		 * @since 3.0.5
		 *
		 * @param bool $enabled current enabled status
		 */
		$enabled = apply_filters( 'hustle_enable_log', $enabled );

		if ( $enabled ) {
			$args    = func_get_args();
			$message = wp_json_encode( $args );
			if ( false !== $message ) {
				error_log( '[Hustle] ' . $message ); // phpcs:ignore
			}
		}
	}

	// ====================================
	// MISC?
	// ====================================

	/**
	 * Returns list of optin providers based on their declared classes that implement Opt_In_Provider_Interface
	 *
	 * @return array
	 */
	public static function get_post_types() {
		if ( empty( self::$post_types ) ) {
			/**
			 * Add all custom post types
			 */
			$post_types = array();
			$cpts       = get_post_types(
				array(
					'public'   => true,
					'_builtin' => false,
				),
				'objects'
			);
			foreach ( $cpts as $cpt ) {

				// skip ms_invoice
				if ( 'ms_invoice' === $cpt->name ) {
					continue;
				}

				$cpt_array['name']  = $cpt->name;
				$cpt_array['label'] = $cpt->label;
				$cpt_array['data']  = self::get_select2_data( $cpt->name );

				$post_types[ $cpt->name ] = $cpt_array;
			}
			self::$post_types = $post_types;
		}
		return self::$post_types;
	}


	/**
	 * Get usable object for select2
	 *
	 * @param $post_type post type
	 * @return array
	 */
	public static function get_select2_data( $post_type, $include_ids = null ) {
		$data = array();

		// We only make query if include_ids are set becuase in other cases the posts
		// the data is gathered with AJAX.
		if ( ! empty( $include_ids ) ) {
			$args = array(
				'numberposts' => -1,
				'post_type'   => $post_type,
				'post_status' => 'publish',
				'order'       => 'ASC',
				'post__in'    => $include_ids,
			);

			$posts = get_posts( $args );
			foreach ( $posts as $post ) {
				$data[] = (object) array(
					'id'   => $post->ID,
					'text' => $post->post_title,
				);
			}
		}

		return $data;
	}

	/**
	 * Return reCAPTCHA languages
	 *
	 * @since 4.0
	 * @return array
	 */
	public static function get_captcha_languages() {
		return apply_filters(
			'hustle_captcha_languages',
			array(
				'ar'     => esc_html__( 'Arabic', 'hustle' ),
				'af'     => esc_html__( 'Afrikaans', 'hustle' ),
				'am'     => esc_html__( 'Amharic', 'hustle' ),
				'hy'     => esc_html__( 'Armenian', 'hustle' ),
				'az'     => esc_html__( 'Azerbaijani', 'hustle' ),
				'eu'     => esc_html__( 'Basque', 'hustle' ),
				'bn'     => esc_html__( 'Bengali', 'hustle' ),
				'bg'     => esc_html__( 'Bulgarian', 'hustle' ),
				'ca'     => esc_html__( 'Catalan', 'hustle' ),
				'zh-HK'  => esc_html__( 'Chinese (Hong Kong)', 'hustle' ),
				'zh-CN'  => esc_html__( 'Chinese (Simplified)', 'hustle' ),
				'zh-TW'  => esc_html__( 'Chinese (Traditional)', 'hustle' ),
				'hr'     => esc_html__( 'Croatian', 'hustle' ),
				'cs'     => esc_html__( 'Czech', 'hustle' ),
				'da'     => esc_html__( 'Danish', 'hustle' ),
				'nl'     => esc_html__( 'Dutch', 'hustle' ),
				'en-GB'  => esc_html__( 'English (UK)', 'hustle' ),
				'en'     => esc_html__( 'English (US)', 'hustle' ),
				'et'     => esc_html__( 'Estonian', 'hustle' ),
				'fil'    => esc_html__( 'Filipino', 'hustle' ),
				'fi'     => esc_html__( 'Finnish', 'hustle' ),
				'fr'     => esc_html__( 'French', 'hustle' ),
				'fr-CA'  => esc_html__( 'French (Canadian)', 'hustle' ),
				'gl'     => esc_html__( 'Galician', 'hustle' ),
				'ka'     => esc_html__( 'Georgian', 'hustle' ),
				'de'     => esc_html__( 'German', 'hustle' ),
				'de-AT'  => esc_html__( 'German (Austria)', 'hustle' ),
				'de-CH'  => esc_html__( 'German (Switzerland)', 'hustle' ),
				'el'     => esc_html__( 'Greek', 'hustle' ),
				'gu'     => esc_html__( 'Gujarati', 'hustle' ),
				'iw'     => esc_html__( 'Hebrew', 'hustle' ),
				'hi'     => esc_html__( 'Hindi', 'hustle' ),
				'hu'     => esc_html__( 'Hungarain', 'hustle' ),
				'is'     => esc_html__( 'Icelandic', 'hustle' ),
				'id'     => esc_html__( 'Indonesian', 'hustle' ),
				'it'     => esc_html__( 'Italian', 'hustle' ),
				'ja'     => esc_html__( 'Japanese', 'hustle' ),
				'kn'     => esc_html__( 'Kannada', 'hustle' ),
				'ko'     => esc_html__( 'Korean', 'hustle' ),
				'lo'     => esc_html__( 'Laothian', 'hustle' ),
				'lv'     => esc_html__( 'Latvian', 'hustle' ),
				'lt'     => esc_html__( 'Lithuanian', 'hustle' ),
				'ms'     => esc_html__( 'Malay', 'hustle' ),
				'ml'     => esc_html__( 'Malayalam', 'hustle' ),
				'mr'     => esc_html__( 'Marathi', 'hustle' ),
				'mn'     => esc_html__( 'Mongolian', 'hustle' ),
				'no'     => esc_html__( 'Norwegian', 'hustle' ),
				'fa'     => esc_html__( 'Persian', 'hustle' ),
				'pl'     => esc_html__( 'Polish', 'hustle' ),
				'pt'     => esc_html__( 'Portuguese', 'hustle' ),
				'pt-BR'  => esc_html__( 'Portuguese (Brazil)', 'hustle' ),
				'pt-PT'  => esc_html__( 'Portuguese (Portugal)', 'hustle' ),
				'ro'     => esc_html__( 'Romanian', 'hustle' ),
				'ru'     => esc_html__( 'Russian', 'hustle' ),
				'sr'     => esc_html__( 'Serbian', 'hustle' ),
				'si'     => esc_html__( 'Sinhalese', 'hustle' ),
				'sk'     => esc_html__( 'Slovak', 'hustle' ),
				'sl'     => esc_html__( 'Slovenian', 'hustle' ),
				'es'     => esc_html__( 'Spanish', 'hustle' ),
				'es-419' => esc_html__( 'Spanish (Latin America)', 'hustle' ),
				'sw'     => esc_html__( 'Swahili', 'hustle' ),
				'sv'     => esc_html__( 'Swedish', 'hustle' ),
				'ta'     => esc_html__( 'Tamil', 'hustle' ),
				'te'     => esc_html__( 'Telugu', 'hustle' ),
				'th'     => esc_html__( 'Thai', 'hustle' ),
				'tr'     => esc_html__( 'Turkish', 'hustle' ),
				'uk'     => esc_html__( 'Ukrainian', 'hustle' ),
				'ur'     => esc_html__( 'Urdu', 'hustle' ),
				'vi'     => esc_html__( 'Vietnamese', 'hustle' ),
				'zu'     => esc_html__( 'Zulu', 'hustle' ),
			)
		);
	}

	/**
	 * Gets post property
	 *
	 * @since 4.0.4
	 * @param string $property Requested post property.
	 * @param string $default Fallback value.
	 * @return string
	 */
	public static function get_post_data( $property, $default = '' ) {
		global $post;

		if ( ! $post ) {
			// fallback on wp_ajax, `global $post` not available.
			$wp_referer = wp_get_referer();
			if ( $wp_referer ) {
				$post_id = ! function_exists( 'wpcom_vip_url_to_postid' ) ? url_to_postid( $wp_referer ) : wpcom_vip_url_to_postid( $wp_referer );
				if ( $post_id ) {
					$post_object = get_post( $post_id );
					// make sure it's wp_post.
					if ( $post_object instanceof WP_Post ) {
						// set global $post as $post_object retrieved from `get_post` for next usage.
						$post = $post_object; // phpcs:ignore
					}
				}
			}
		}

		$post_data = (array) $post;
		if ( isset( $post_data[ $property ] ) ) {
			return $post_data[ $property ];
		} else {
			return $default;
		}
	}

	// ====================================
	// MODULES HELPERS
	// ====================================

	/**
	 * Get current post id
	 *
	 * @since 4.0
	 *
	 * @return int|string
	 */
	public static function get_post_id() {
		return get_post() ? get_the_ID() : '0';
	}

	/**
	 * Gets the a current user's property.
	 *
	 * @since 4.0.4
	 * @param string $property The user's property to be retrieved.
	 * @return string
	 */
	public static function get_user_data( $property ) {
		$current_user = wp_get_current_user();

		if ( $current_user && $current_user->exists() ) {
			return $current_user->get( $property );
		}
		return '';
	}

	/**
	 * Replace a key in an array without changing its order.
	 *
	 * @since 4.0
	 *
	 * @param string $old_key
	 * @param string $new_key
	 * @param array  $array
	 * @return array
	 */
	public static function replace_array_key( $old_key, $new_key, $array ) {

		// Replace the name without changing the array's order.
		$keys_array = array_keys( $array );
		$index      = array_search( $old_key, $keys_array, true );

		if ( false === $index ) {
			return $array;
		}

		$keys_array[ $index ] = $new_key;

		$new_array = array_combine( $keys_array, array_values( $array ) );

		return $new_array;
	}

	/*
	 * Get the display name of a module type.
	 *
	 * @since 4.0
	 *
	 * @param string $module_type
	 * @param boolean $plural
	 * @param boolean $capitalized
	 * @return string
	 */
	public static function get_module_type_display_name( $module_type, $plural = false, $capitalized = false ) {

		$display_name = '';

		if ( Hustle_Module_Model::POPUP_MODULE === $module_type ) {
			if ( ! $plural ) {
				$display_name = __( 'pop-up', 'hustle' );
			} else {
				$display_name = __( 'pop-ups', 'hustle' );
			}
		} elseif ( Hustle_Module_Model::SLIDEIN_MODULE === $module_type ) {
			if ( ! $plural ) {
				$display_name = __( 'slide-in', 'hustle' );
			} else {
				$display_name = __( 'slide-ins', 'hustle' );
			}
		} elseif ( Hustle_Module_Model::EMBEDDED_MODULE === $module_type ) {
			if ( ! $plural ) {
				$display_name = __( 'embed', 'hustle' );
			} else {
				$display_name = __( 'embeds', 'hustle' );
			}
		} elseif ( Hustle_Module_Model::SOCIAL_SHARING_MODULE === $module_type ) {
			if ( ! $plural ) {
				$display_name = __( 'social sharing', 'hustle' );
			} else {
				$display_name = __( 'social shares', 'hustle' );
			}
		}

		if ( $capitalized ) {
			$display_name = ucwords( $display_name );
		}

		return $display_name;
	}

	/**
	 * Get page templates
	 *
	 * @since 4.0.3
	 */
	public static function hustle_get_page_templates() {
		$templates      = get_page_templates();
		$page_templates = array();
		foreach ( $templates as $template_name => $template_filename ) {
			$page_templates[ $template_filename ] = $template_name;
		}
		return $page_templates;
	}

	/**
	 * Add special scripts for IE if it's detected
	 *
	 * @global bool $is_IE
	 * @global bool $is_edge
	 */
	public static function maybe_add_scripts_for_ie() {
		global $is_IE, $is_edge;

		if ( $is_IE || $is_edge ) {
			wp_enqueue_script(
				'optin_admin_fitie',
				Opt_In::$plugin_url . 'assets/js/vendor/fitie/fitie.js',
				array(),
				Opt_In::VERSION,
				true
			);
		}
	}

	/**
	 * Check if WooCommerce is active or not
	 *
	 * @return bool
	 */
	public static function is_woocommerce_active() {
		return is_plugin_active( 'woocommerce/woocommerce.php' );
	}

	/**
	 * Gets the first key of an array.
	 *
	 * @since 4.0.0
	 *
	 * @param array $array
	 * @return mixed
	 */
	public static function array_key_first( array $array ) {
		return $array ? array_keys( $array )[0] : null;
	}

	/**
	 * Get the global placeholders for display.
	 * The array's key has the placeholder value, that's what's inserted between
	 * brackets and then replaced by self::replace_global_placeholders().
	 * The array's value has the display name for the placeholder.
	 *
	 * @since 4.0.3
	 * @see Opt_In_Utils::replace_global_placeholders()
	 * @return array
	 */
	public static function get_global_placeholders() {

		$placeholders = array(
			'site_url'   => __( 'Site URL', 'hustle' ),
			'site_name'  => __( 'Site name', 'hustle' ),
			'post_url'   => __( 'Post/page URL', 'hustle' ),
			'post_title' => __( 'Post/page title', 'hustle' ),
		);

		/**
		 * Filter the available global placeholders.
		 * These are used in some text fields, to be replaced by
		 * self::replace_global_placeholders().
		 *
		 * @since 4.0.3
		 * @see Opt_In_Utils::replace_global_placeholders()
		 * @return array
		 */
		return apply_filters( 'hustle_get_global_placeholders', $placeholders );
	}

	/**
	 * Replace the global placeholders from a string.
	 * These are added to some text fields by the admin.
	 * The available ones are returned by self::get_global_placeholders().
	 *
	 * @since 4.0.3
	 * @see Opt_In_Utils::replace_global_placeholders()
	 * @param string $string String with placeholders to be replaced.
	 * @return string
	 */
	public static function replace_global_placeholders( $string ) {

		preg_match_all( '/\{[^}]*\}/', $string, $matches );

		if ( ! empty( $matches[0] ) && is_array( $matches[0] ) ) {

			$defined_placeholders = array(
				'{site_url}'   => site_url(),
				'{site_name}'  => get_bloginfo( 'name' ),
				'{post_url}'   => get_permalink(),
				'{post_title}' => esc_html( get_the_title() ),
			);

			/**
			 * Filter the placeholders and their values.
			 * The keys of the array belong to the placeholder to be replaced.
			 * The values of the array belong to the value to use as replacement.
			 * Eg: [ '{post_url}' => get_permalink() ]
			 *
			 * @since 4.0.3
			 * @return array
			 */
			$defined_placeholders = apply_filters( 'hustle_global_placeholders_to_replace', $defined_placeholders );

			foreach ( $matches[0] as $placeholder ) {

				if ( key_exists( $placeholder, $defined_placeholders ) ) {
					$replacement = $defined_placeholders[ $placeholder ];

					if ( $replacement !== $placeholder ) {
						// Replace if we found something.
						$string = str_replace( $placeholder, $replacement, $string );
					}
				}
			}
		}

		return $string;
	}

	// Static stuff below.

	/**
	 * Returns array of browsers
	 *
	 * @since 4.1
	 * @return array|mixed|null|void
	 */
	public static function get_browsers() {

		$browsers = array(
			'chrome'  => __( 'Chrome', 'hustle' ),
			'firefox' => __( 'Firefox', 'hustle' ),
			'safari'  => __( 'Safari', 'hustle' ),
			'edge'    => __( 'Edge', 'hustle' ),
			'MSIE'    => __( 'Internet Explorer', 'hustle' ),
			'opera'   => __( 'Opera', 'hustle' ),
		);

		/**
		 * Filter the list of browsers
		 * Must return an associative array where the key is the browser's slug
		 * and the value is its display name.
		 *
		 * @since 4.1
		 */
		return apply_filters( 'hustle_get_browsers_list', $browsers );
	}

	/**
	 * Returns array of countries
	 *
	 * @return array|mixed|null|void
	 */
	public static function get_countries() {

		$countries = array(
			'AU' => __( 'Australia', 'hustle' ),
			'AF' => __( 'Afghanistan', 'hustle' ),
			'AL' => __( 'Albania', 'hustle' ),
			'DZ' => __( 'Algeria', 'hustle' ),
			'AS' => __( 'American Samoa', 'hustle' ),
			'AD' => __( 'Andorra', 'hustle' ),
			'AO' => __( 'Angola', 'hustle' ),
			'AI' => __( 'Anguilla', 'hustle' ),
			'AQ' => __( 'Antarctica', 'hustle' ),
			'AG' => __( 'Antigua and Barbuda', 'hustle' ),
			'AR' => __( 'Argentina', 'hustle' ),
			'AM' => __( 'Armenia', 'hustle' ),
			'AW' => __( 'Aruba', 'hustle' ),
			'AT' => __( 'Austria', 'hustle' ),
			'AZ' => __( 'Azerbaijan', 'hustle' ),
			'BS' => __( 'Bahamas', 'hustle' ),
			'BH' => __( 'Bahrain', 'hustle' ),
			'BD' => __( 'Bangladesh', 'hustle' ),
			'BB' => __( 'Barbados', 'hustle' ),
			'BY' => __( 'Belarus', 'hustle' ),
			'BE' => __( 'Belgium', 'hustle' ),
			'BZ' => __( 'Belize', 'hustle' ),
			'BJ' => __( 'Benin', 'hustle' ),
			'BM' => __( 'Bermuda', 'hustle' ),
			'BT' => __( 'Bhutan', 'hustle' ),
			'BO' => __( 'Bolivia', 'hustle' ),
			'BA' => __( 'Bosnia and Herzegovina', 'hustle' ),
			'BW' => __( 'Botswana', 'hustle' ),
			'BV' => __( 'Bouvet Island', 'hustle' ),
			'BR' => __( 'Brazil', 'hustle' ),
			'IO' => __( 'British Indian Ocean Territory', 'hustle' ),
			'BN' => __( 'Brunei', 'hustle' ),
			'BG' => __( 'Bulgaria', 'hustle' ),
			'BF' => __( 'Burkina Faso', 'hustle' ),
			'BI' => __( 'Burundi', 'hustle' ),
			'KH' => __( 'Cambodia', 'hustle' ),
			'CM' => __( 'Cameroon', 'hustle' ),
			'CA' => __( 'Canada', 'hustle' ),
			'CV' => __( 'Cape Verde', 'hustle' ),
			'KY' => __( 'Cayman Islands', 'hustle' ),
			'CF' => __( 'Central African Republic', 'hustle' ),
			'TD' => __( 'Chad', 'hustle' ),
			'CL' => __( 'Chile', 'hustle' ),
			'CN' => __( 'China, People\'s Republic of', 'hustle' ),
			'CX' => __( 'Christmas Island', 'hustle' ),
			'CC' => __( 'Cocos Islands', 'hustle' ),
			'CO' => __( 'Colombia', 'hustle' ),
			'KM' => __( 'Comoros', 'hustle' ),
			'CD' => __( 'Congo, Democratic Republic of the', 'hustle' ),
			'CG' => __( 'Congo, Republic of the', 'hustle' ),
			'CK' => __( 'Cook Islands', 'hustle' ),
			'CR' => __( 'Costa Rica', 'hustle' ),
			'CI' => __( 'Côte d\'Ivoire', 'hustle' ),
			'HR' => __( 'Croatia', 'hustle' ),
			'CU' => __( 'Cuba', 'hustle' ),
			'CW' => __( 'Curaçao', 'hustle' ),
			'CY' => __( 'Cyprus', 'hustle' ),
			'CZ' => __( 'Czech Republic', 'hustle' ),
			'DK' => __( 'Denmark', 'hustle' ),
			'DJ' => __( 'Djibouti', 'hustle' ),
			'DM' => __( 'Dominica', 'hustle' ),
			'DO' => __( 'Dominican Republic', 'hustle' ),
			'TL' => __( 'East Timor', 'hustle' ),
			'EC' => __( 'Ecuador', 'hustle' ),
			'EG' => __( 'Egypt', 'hustle' ),
			'SV' => __( 'El Salvador', 'hustle' ),
			'GQ' => __( 'Equatorial Guinea', 'hustle' ),
			'ER' => __( 'Eritrea', 'hustle' ),
			'EE' => __( 'Estonia', 'hustle' ),
			'ET' => __( 'Ethiopia', 'hustle' ),
			'FK' => __( 'Falkland Islands', 'hustle' ),
			'FO' => __( 'Faroe Islands', 'hustle' ),
			'FJ' => __( 'Fiji', 'hustle' ),
			'FI' => __( 'Finland', 'hustle' ),
			'FR' => __( 'France', 'hustle' ),
			'FX' => __( 'France, Metropolitan', 'hustle' ),
			'GF' => __( 'French Guiana', 'hustle' ),
			'PF' => __( 'French Polynesia', 'hustle' ),
			'TF' => __( 'French South Territories', 'hustle' ),
			'GA' => __( 'Gabon', 'hustle' ),
			'GM' => __( 'Gambia', 'hustle' ),
			'GE' => __( 'Georgia', 'hustle' ),
			'DE' => __( 'Germany', 'hustle' ),
			'GH' => __( 'Ghana', 'hustle' ),
			'GI' => __( 'Gibraltar', 'hustle' ),
			'GR' => __( 'Greece', 'hustle' ),
			'GL' => __( 'Greenland', 'hustle' ),
			'GD' => __( 'Grenada', 'hustle' ),
			'GP' => __( 'Guadeloupe', 'hustle' ),
			'GU' => __( 'Guam', 'hustle' ),
			'GT' => __( 'Guatemala', 'hustle' ),
			'GN' => __( 'Guinea', 'hustle' ),
			'GW' => __( 'Guinea-Bissau', 'hustle' ),
			'GY' => __( 'Guyana', 'hustle' ),
			'HT' => __( 'Haiti', 'hustle' ),
			'HM' => __( 'Heard Island And Mcdonald Island', 'hustle' ),
			'HN' => __( 'Honduras', 'hustle' ),
			'HK' => __( 'Hong Kong', 'hustle' ),
			'HU' => __( 'Hungary', 'hustle' ),
			'IS' => __( 'Iceland', 'hustle' ),
			'IN' => __( 'India', 'hustle' ),
			'ID' => __( 'Indonesia', 'hustle' ),
			'IR' => __( 'Iran', 'hustle' ),
			'IQ' => __( 'Iraq', 'hustle' ),
			'IE' => __( 'Ireland', 'hustle' ),
			'IL' => __( 'Israel', 'hustle' ),
			'IT' => __( 'Italy', 'hustle' ),
			'JM' => __( 'Jamaica', 'hustle' ),
			'JP' => __( 'Japan', 'hustle' ),
			'JT' => __( 'Johnston Island', 'hustle' ),
			'JO' => __( 'Jordan', 'hustle' ),
			'KZ' => __( 'Kazakhstan', 'hustle' ),
			'KE' => __( 'Kenya', 'hustle' ),
			'XK' => __( 'Kosovo', 'hustle' ),
			'KI' => __( 'Kiribati', 'hustle' ),
			'KP' => __( 'Korea, Democratic People\'s Republic of', 'hustle' ),
			'KR' => __( 'Korea, Republic of', 'hustle' ),
			'KW' => __( 'Kuwait', 'hustle' ),
			'KG' => __( 'Kyrgyzstan', 'hustle' ),
			'LA' => __( 'Lao People\'s Democratic Republic', 'hustle' ),
			'LV' => __( 'Latvia', 'hustle' ),
			'LB' => __( 'Lebanon', 'hustle' ),
			'LS' => __( 'Lesotho', 'hustle' ),
			'LR' => __( 'Liberia', 'hustle' ),
			'LY' => __( 'Libya', 'hustle' ),
			'LI' => __( 'Liechtenstein', 'hustle' ),
			'LT' => __( 'Lithuania', 'hustle' ),
			'LU' => __( 'Luxembourg', 'hustle' ),
			'MO' => __( 'Macau', 'hustle' ),
			'MK' => __( 'Macedonia', 'hustle' ),
			'MG' => __( 'Madagascar', 'hustle' ),
			'MW' => __( 'Malawi', 'hustle' ),
			'MY' => __( 'Malaysia', 'hustle' ),
			'MV' => __( 'Maldives', 'hustle' ),
			'ML' => __( 'Mali', 'hustle' ),
			'MT' => __( 'Malta', 'hustle' ),
			'MH' => __( 'Marshall Islands', 'hustle' ),
			'MQ' => __( 'Martinique', 'hustle' ),
			'MR' => __( 'Mauritania', 'hustle' ),
			'MU' => __( 'Mauritius', 'hustle' ),
			'YT' => __( 'Mayotte', 'hustle' ),
			'MX' => __( 'Mexico', 'hustle' ),
			'FM' => __( 'Micronesia', 'hustle' ),
			'MD' => __( 'Moldova', 'hustle' ),
			'MC' => __( 'Monaco', 'hustle' ),
			'MN' => __( 'Mongolia', 'hustle' ),
			'ME' => __( 'Montenegro', 'hustle' ),
			'MS' => __( 'Montserrat', 'hustle' ),
			'MA' => __( 'Morocco', 'hustle' ),
			'MZ' => __( 'Mozambique', 'hustle' ),
			'MM' => __( 'Myanmar', 'hustle' ),
			'NA' => __( 'Namibia', 'hustle' ),
			'NR' => __( 'Nauru', 'hustle' ),
			'NP' => __( 'Nepal', 'hustle' ),
			'NL' => __( 'Netherlands', 'hustle' ),
			'AN' => __( 'Netherlands Antilles', 'hustle' ),
			'NC' => __( 'New Caledonia', 'hustle' ),
			'NZ' => __( 'New Zealand', 'hustle' ),
			'NI' => __( 'Nicaragua', 'hustle' ),
			'NE' => __( 'Niger', 'hustle' ),
			'NG' => __( 'Nigeria', 'hustle' ),
			'NU' => __( 'Niue', 'hustle' ),
			'NF' => __( 'Norfolk Island', 'hustle' ),
			'MP' => __( 'Northern Mariana Islands', 'hustle' ),
			'MP' => __( 'Mariana Islands, Northern', 'hustle' ),
			'NO' => __( 'Norway', 'hustle' ),
			'OM' => __( 'Oman', 'hustle' ),
			'PK' => __( 'Pakistan', 'hustle' ),
			'PW' => __( 'Palau', 'hustle' ),
			'PS' => __( 'Palestine, State of', 'hustle' ),
			'PA' => __( 'Panama', 'hustle' ),
			'PG' => __( 'Papua New Guinea', 'hustle' ),
			'PY' => __( 'Paraguay', 'hustle' ),
			'PE' => __( 'Peru', 'hustle' ),
			'PH' => __( 'Philippines', 'hustle' ),
			'PN' => __( 'Pitcairn Islands', 'hustle' ),
			'PL' => __( 'Poland', 'hustle' ),
			'PT' => __( 'Portugal', 'hustle' ),
			'PR' => __( 'Puerto Rico', 'hustle' ),
			'QA' => __( 'Qatar', 'hustle' ),
			'RE' => __( 'Réunion', 'hustle' ),
			'RO' => __( 'Romania', 'hustle' ),
			'RU' => __( 'Russia', 'hustle' ),
			'RW' => __( 'Rwanda', 'hustle' ),
			'SH' => __( 'Saint Helena', 'hustle' ),
			'KN' => __( 'Saint Kitts and Nevis', 'hustle' ),
			'LC' => __( 'Saint Lucia', 'hustle' ),
			'PM' => __( 'Saint Pierre and Miquelon', 'hustle' ),
			'VC' => __( 'Saint Vincent and the Grenadines', 'hustle' ),
			'WS' => __( 'Samoa', 'hustle' ),
			'SM' => __( 'San Marino', 'hustle' ),
			'ST' => __( 'Sao Tome and Principe', 'hustle' ),
			'SA' => __( 'Saudi Arabia', 'hustle' ),
			'SN' => __( 'Senegal', 'hustle' ),
			'CS' => __( 'Serbia', 'hustle' ),
			'SC' => __( 'Seychelles', 'hustle' ),
			'SL' => __( 'Sierra Leone', 'hustle' ),
			'SG' => __( 'Singapore', 'hustle' ),
			'MF' => __( 'Sint Maarten', 'hustle' ),
			'SK' => __( 'Slovakia', 'hustle' ),
			'SI' => __( 'Slovenia', 'hustle' ),
			'SB' => __( 'Solomon Islands', 'hustle' ),
			'SO' => __( 'Somalia', 'hustle' ),
			'ZA' => __( 'South Africa', 'hustle' ),
			'GS' => __( 'South Georgia and the South Sandwich Islands', 'hustle' ),
			'ES' => __( 'Spain', 'hustle' ),
			'LK' => __( 'Sri Lanka', 'hustle' ),
			'XX' => __( 'Stateless Persons', 'hustle' ),
			'SD' => __( 'Sudan', 'hustle' ),
			'SD' => __( 'Sudan, South', 'hustle' ),
			'SR' => __( 'Suriname', 'hustle' ),
			'SJ' => __( 'Svalbard and Jan Mayen', 'hustle' ),
			'SZ' => __( 'Swaziland', 'hustle' ),
			'SE' => __( 'Sweden', 'hustle' ),
			'CH' => __( 'Switzerland', 'hustle' ),
			'SY' => __( 'Syria', 'hustle' ),
			'TW' => __( 'Taiwan, Republic of China', 'hustle' ),
			'TJ' => __( 'Tajikistan', 'hustle' ),
			'TZ' => __( 'Tanzania', 'hustle' ),
			'TH' => __( 'Thailand', 'hustle' ),
			'TG' => __( 'Togo', 'hustle' ),
			'TK' => __( 'Tokelau', 'hustle' ),
			'TO' => __( 'Tonga', 'hustle' ),
			'TT' => __( 'Trinidad and Tobago', 'hustle' ),
			'TN' => __( 'Tunisia', 'hustle' ),
			'TR' => __( 'Turkey', 'hustle' ),
			'TM' => __( 'Turkmenistan', 'hustle' ),
			'TC' => __( 'Turks and Caicos Islands', 'hustle' ),
			'TV' => __( 'Tuvalu', 'hustle' ),
			'UG' => __( 'Uganda', 'hustle' ),
			'UA' => __( 'Ukraine', 'hustle' ),
			'AE' => __( 'United Arab Emirates', 'hustle' ),
			'GB' => __( 'United Kingdom', 'hustle' ),
			'US' => __( 'United States of America (USA)', 'hustle' ),
			'UM' => __( 'US Minor Outlying Islands', 'hustle' ),
			'UY' => __( 'Uruguay', 'hustle' ),
			'UZ' => __( 'Uzbekistan', 'hustle' ),
			'VU' => __( 'Vanuatu', 'hustle' ),
			'VA' => __( 'Vatican City', 'hustle' ),
			'VE' => __( 'Venezuela', 'hustle' ),
			'VN' => __( 'Vietnam', 'hustle' ),
			'VG' => __( 'Virgin Islands, British', 'hustle' ),
			'VI' => __( 'Virgin Islands, U.S.', 'hustle' ),
			'WF' => __( 'Wallis And Futuna', 'hustle' ),
			'EH' => __( 'Western Sahara', 'hustle' ),
			'YE' => __( 'Yemen', 'hustle' ),
			'ZM' => __( 'Zambia', 'hustle' ),
			'ZW' => __( 'Zimbabwe', 'hustle' ),
		);

		/**
		 * Returns a list with countries
		 * Must be an associative array where the key is the country code
		 * and its value is its display name.
		 */
		return apply_filters( 'opt_in-country-list', $countries );
	}
}