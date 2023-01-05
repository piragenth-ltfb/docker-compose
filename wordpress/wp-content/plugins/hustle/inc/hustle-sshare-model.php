<?php

class Hustle_SShare_Model extends Hustle_Model {

	const SETTINGS_KEY       = 'sshare_counters';
	const COUNTER_META_KEY   = 'hustle_shares';
	const TIMESTAMP_META_KEY = 'hustle_timestamp';
	const REFRESH_OPTION_KEY = 'hustle_ss_refresh_counters';
	const FLOAT_DESKTOP      = 'float_desktop';
	const FLOAT_MOBILE       = 'float_mobile';
	const FLOAT_MODULE       = 'floating';

	public static function instance() {
		_deprecated_function( __METHOD__, '4.3.0', 'new Hustle_SShare_Model' );
		return new self();
	}

	/**
	 * Get the sub-types for this module.
	 *
	 * @since 4.2.0
	 *
	 * @return array
	 */
	public static function get_sshare_types( $with_titles = false ) {
		if ( ! $with_titles ) {
			return array( self::FLOAT_MODULE, 'inline', 'widget', 'shortcode' );
		} else {
			return array(
				self::FLOAT_MODULE => __( 'Floating', 'hustle' ),
				'inline'           => __( 'Inline', 'hustle' ),
				'widget'           => __( 'Widget', 'hustle' ),
				'shortcode'        => __( 'Shortcode', 'hustle' ),
			);
		}
	}

	public function get_content() {
		return new Hustle_SShare_Content( $this->get_settings_meta( self::KEY_CONTENT, '{}', true ), $this );
	}

	public function get_design() {
		return new Hustle_SShare_Design( $this->get_settings_meta( self::KEY_DESIGN, '{}', true ), $this );
	}

	/**
	 * Get the stored settings for the "Display" tab.
	 *
	 * @since 4.0
	 *
	 * @return Hustle_SShare_Display
	 */
	public function get_display() {
		return new Hustle_SShare_Display( $this->get_settings_meta( self::KEY_DISPLAY_OPTIONS, '{}', true ), $this );
	}

	/**
	 * Gets the instance of the decorator class for this module type.
	 *
	 * @since 4.3.0
	 *
	 * @return Hustle_Decorator_Sshare
	 */
	public function get_decorator_instance() {
		return new Hustle_Decorator_Sshare( $this );
	}
	/**
	 * Create a new module of the provided mode and type.
	 *
	 * @since 4.0
	 *
	 * @param array $data Must contain the Module's 'mode', 'name' and 'type.
	 * @return int|false Module ID if successfully saved. False otherwise.
	 */
	public function create_new( $data ) {
		// Verify it's a valid module type.
		if ( self::SOCIAL_SHARING_MODULE !== $data['module_type'] ) {
			return false;
		}

		// Save to modules table.
		$this->module_name = sanitize_text_field( $data['module_name'] );
		$this->module_type = $data['module_type'];
		$this->active      = 0;
		$this->module_mode = '';
		$this->save();

		// Save the new module's meta.
		$this->store_new_module_meta( $data );

		return $this->id;
	}

	/**
	 * Store the defaults meta when creating a new module.
	 *
	 * @since 4.0.0
	 *
	 * @param array $data Data to store.
	 */
	private function store_new_module_meta( $data ) {
		$def_content  = apply_filters( 'hustle_module_get_' . self::KEY_CONTENT . '_defaults', $this->get_content()->to_array(), $this, $data );
		$content_data = empty( $data['content'] ) ? $def_content : array_merge( $def_content, $data['content'] );

		$def_design  = apply_filters( 'hustle_module_get_' . self::KEY_DESIGN . '_defaults', $this->get_design()->to_array(), $this, $data );
		$design_data = empty( $data['design'] ) ? $def_design : array_merge( $def_design, $data['design'] );

		// Visibility settings.
		$def_visibility  = apply_filters( 'hustle_module_get_' . self::KEY_VISIBILITY . '_defaults', $this->get_visibility()->to_array(), $this, $data );
		$visibility_data = empty( $data['visibility'] ) ? $def_visibility : array_merge( $def_visibility, $data['visibility'] );

		// Display options.
		$def_display  = apply_filters( 'hustle_module_get_' . self::KEY_DISPLAY_OPTIONS . '_defaults', $this->get_display()->to_array(), $this, $data );
		$display_data = empty( $data['display'] ) ? $def_display : array_merge( $def_display, $data['display'] );

		// Save to meta table.
		$this->update_meta( self::KEY_CONTENT, $content_data );
		$this->update_meta( self::KEY_DESIGN, $design_data );
		$this->update_meta( self::KEY_VISIBILITY, $visibility_data );
		$this->update_meta( self::KEY_DISPLAY_OPTIONS, $display_data );
	}

	/**
	 * Sanitize/Replace the module's data.
	 *
	 * @param array $data Data to sanitize.
	 * @return array Sanitized data.
	 */
	public function sanitize_module( $data ) {
		return $data;
	}

	/**
	 * Validates the module's data.
	 *
	 * @since 4.0.3
	 *
	 * @param array $data Data to validate.
	 * @return array
	 */
	public function validate_module( $data ) {

		$icons    = isset( $data['content']['social_icons'] ) ? $data['content']['social_icons'] : array();
		$display  = $data['display'];
		$selector = array(
			'desktop' => isset( $display['float_desktop_offset'] ) ? $display['float_desktop_offset'] : '',
			'mobile'  => isset( $display['float_mobile_offset'] ) ? $display['float_mobile_offset'] : '',
		);

		$errors = array();

		// Name validation.
		if ( empty( sanitize_text_field( $data['module']['module_name'] ) ) ) {
			$errors['error']['name_error'] = __( 'This field is required', 'hustle' );
		}

		// Social platform url check.
		if ( ! empty( $icons ) ) {
			foreach ( $icons as $key => $icon ) {
				$icon_with_enpoints = self::get_sharing_endpoints();
				if ( ! in_array( $icon['platform'], $icon_with_enpoints, true ) && empty( $icon['link'] ) ) {
					$errors['error']['icon_error'][] = $icon['platform'];
				}
			}
		}

		// css selector check
		if ( ! empty( $selector ) ) {
			if ( 'css_selector' === $selector['desktop'] && empty( $display['float_desktop_css_selector'] )
			&& ! empty( $display['float_desktop_enabled'] ) ) {
				$errors['error']['selector_error'][] = 'float_desktop';
			}
			if ( 'css_selector' === $selector['mobile'] && empty( $display['float_mobile_css_selector'] ) && ! empty( $display['float_mobile_enabled'] ) ) {
				$errors['error']['selector_error'][] = 'float_mobile';
			}
		}

		// return errors if any.
		if ( ! empty( $errors ) ) {
			$errors['success'] = false;
			return $errors;
		}

		return true;
	}

	/**
	 * Updates the metas specific for Social Sharing modules.
	 *
	 * @since 4.3.0
	 * @param array $data Data to save.
	 * @return void
	 */
	protected function update_module_metas( $data ) {
		// Meta used in all module types.
		if ( isset( $data['content'] ) ) {
			$this->update_meta( self::KEY_CONTENT, $data['content'] );
		}
		// Meta used in all module types.
		if ( isset( $data['visibility'] ) ) {
			$this->update_meta( self::KEY_VISIBILITY, $data['visibility'] );
		}

		if ( isset( $data['design'] ) ) {
			$this->update_meta( self::KEY_DESIGN, $data['design'] );
		}

		if ( isset( $data['display'] ) ) {
			$this->update_meta( self::KEY_DISPLAY_OPTIONS, $data['display'] );
		}

		// Force all counters to retrieve the data from the APIs.
		self::refresh_all_counters();
	}

	/**
	 * Get the module's data. Used to display it.
	 *
	 * @since 4.3.0
	 *
	 * @return array
	 */
	public function get_module_data_to_display() {
		return $this->get_data();
	}

	/**
	 * Get the sub-types for this module.
	 *
	 * @since 4.3.1
	 *
	 * @return array
	 */
	public function get_sub_types( $with_titles = false ) {
		return self::get_sshare_types( $with_titles );
	}

	/**
	 * Return whether or not the requested counter type is enabled
	 *
	 * @since 3.0.3
	 *
	 * @param string $type
	 * @return boolean
	 */
	public function is_click_counter_type_enabled( $type ) {
		$content = $this->get_content()->to_array();
		if ( isset( $content['click_counter'] ) && $content['click_counter'] === $type ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get social patform names
	 *
	 * @return array
	 */
	public static function get_social_platform_names() {
		$social_platform_names = array(
			'facebook'      => esc_html__( 'Facebook', 'hustle' ),
			'twitter'       => esc_html__( 'Twitter', 'hustle' ),
			'pinterest'     => esc_html__( 'Pinterest', 'hustle' ),
			'reddit'        => esc_html__( 'Reddit', 'hustle' ),
			'linkedin'      => esc_html__( 'LinkedIn', 'hustle' ),
			'vkontakte'     => esc_html__( 'Vkontakte', 'hustle' ),
			'fivehundredpx' => esc_html__( '500px', 'hustle' ),
			'houzz'         => esc_html__( 'Houzz', 'hustle' ),
			'instagram'     => esc_html__( 'Instagram', 'hustle' ),
			'twitch'        => esc_html__( 'Twitch', 'hustle' ),
			'youtube'       => esc_html__( 'YouTube', 'hustle' ),
			'telegram'      => esc_html__( 'Telegram', 'hustle' ),
			'whatsapp'      => esc_html__( 'WhatsApp', 'hustle' ),
			'email'         => esc_html__( 'Email', 'hustle' ),
		);

		/**
		 * Social networks list
		 *
		 * @since 4.0.4
		 *
		 * @param array $social_platform_names {slug} => {name}
		 */
		return apply_filters( 'hustle_social_platform_names', $social_platform_names );
	}

	/**
	 * Get the stored counter of each network.
	 *
	 * @since 3.0.3
	 *
	 * @param integer $post_id
	 * @return array
	 */
	public function get_stored_network_shares( $post_id ) {

		if ( 0 !== $post_id ) {
			$stored_counters = get_post_meta( $post_id, self::COUNTER_META_KEY, true );

		} else {
			$sshare_networks_option = Hustle_Settings_Admin::get_hustle_settings( self::SETTINGS_KEY );

			$stored_counters = ! empty( $sshare_networks_option[ self::COUNTER_META_KEY ] ) ? $sshare_networks_option[ self::COUNTER_META_KEY ] : array();
		}

		return $stored_counters;
	}

	/**
	 * Check if stored values should be used.
	 *
	 * @since 3.0.3
	 *
	 * @param integer $post_id
	 * @param bool    $check_expiration_time Optional. Check expiration time or not
	 * @return bool
	 */
	public function should_use_stored( $post_id, $check_expiration_time = false ) {

		// If we're in a page/post...
		if ( 0 !== $post_id ) {

			// Don't use stored if we don't have anything stored in this post.
			if ( ! get_post_meta( $post_id, self::COUNTER_META_KEY ) ) {
				return false;
			}

			// If we're in somewhere that's not a page/post...
		} else {

			// Don't use stored if we don't have anything stored in the options;
			$sshare_networks_option = Hustle_Settings_Admin::get_hustle_settings( self::SETTINGS_KEY );
			if ( empty( $sshare_networks_option ) || empty( $sshare_networks_option[ self::COUNTER_META_KEY ] ) ) {
				return false;
			}
		}

		// Do use stored values if traffic is a crawler/bot.
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && preg_match( '/bot|crawler|ia_archiver|mediapartners-google|80legs|wget|voyager|baiduspider|curl|yahoo!|slurp/i', $_SERVER['HTTP_USER_AGENT'] ) ) {
			return true;
		}

		if ( $check_expiration_time ) {

			if ( 0 !== $post_id ) {
				$timestamp = intval( get_post_meta( $post_id, self::TIMESTAMP_META_KEY, true ) );
			} else {
				$timestamp = isset( $sshare_networks_option[ self::TIMESTAMP_META_KEY ] ) ? $sshare_networks_option[ self::TIMESTAMP_META_KEY ] : 'true';
			}

			// Don't use stored if the expiration time of the counter already passed.
			if ( 'true' === $timestamp || time() > ( $timestamp + ( 6 * 60 * 60 ) ) ) {
				return false;
			}

			// Don't use stored if the counter hasn't beeen updated after the last time that all counters were cleared.
			$clear_counters_time = get_option( self::REFRESH_OPTION_KEY, false );
			if ( $clear_counters_time && $timestamp < intval( $clear_counters_time ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Set the number of shares of each network in an array. Like $result[facebook] = 44
	 *
	 * @since 3.0.3
	 * @since 4.0
	 *
	 * @param array   $network
	 * @param integer $post_id
	 * @return array
	 */
	public function retrieve_networks_shares( $networks, $post_id ) {

		// TODO: handle homepage when post_id is not a specific page
		$post_id = apply_filters( 'hustle_network_shares_post_id', $post_id );

		$current_link = ( 0 !== $post_id && get_permalink( $post_id ) ) ? get_permalink( $post_id ) : home_url();
		// We share URL without trailing slash, So we need to remove slash when we fetch from API.
		$current_link = untrailingslashit( $current_link );
		$current_link = apply_filters( 'hustle_network_shares_from_url', $current_link );

		// If we should use the stored values instead of retrieving them from the API.
		$should_use_stored = apply_filters( 'hustle_sshare_should_use_stored_counters', $this->should_use_stored( $post_id, true ), $post_id, $current_link );
		if ( $should_use_stored ) {

			$networks_info = $this->get_stored_network_shares( $post_id );

			// Call the API for the networks we don't have the value stored.
			$missing_networks = array_diff( $networks, array_keys( $networks_info ) );
			if ( ! empty( $missing_networks ) ) {
				$missing_info = $this->get_refreshed_counters( $current_link, $missing_networks, $post_id );

				$networks_info = array_merge( $networks_info, $missing_info );
			}
		} else {
			$networks_info = $this->get_refreshed_counters( $current_link, $networks, $post_id );
		}

		return $networks_info;
	}

	/**
	 * Get the counter from the APIs, store it in the post_meta, and store the current time for expiration.
	 *
	 * @since 4.0
	 *
	 * @param string $current_link
	 * @param array  $networks
	 * @param id     $post_id
	 * @return array
	 */
	public function get_refreshed_counters( $current_link, $networks, $post_id ) {

		// Get array with json formatted data for each active network
		$networks_info = $this->get_networks_data_from_api( $current_link, $networks );
		$networks_info = $this->format_networks_api_raw_data( $networks_info, false );

		// Store the values for "caching".
		if ( 0 !== $post_id ) {

			// Store the values of all new networks.
			$stored_info = get_post_meta( $post_id, self::COUNTER_META_KEY, true );
			if ( ! empty( $stored_info ) ) {
				$networks_info = array_merge( $stored_info, $networks_info );
			}

			// Set the counters values for current post.
			update_post_meta( $post_id, self::COUNTER_META_KEY, $networks_info );

			// Set the counter update time.
			update_post_meta( $post_id, self::TIMESTAMP_META_KEY, time() );

		} else {

			// Store the values of all new networks.
			$stored_info = Hustle_Settings_Admin::get_hustle_settings( self::SETTINGS_KEY );
			if ( ! empty( $stored_info[ self::COUNTER_META_KEY ] ) ) {
				$networks_info = array_merge( $stored_info[ self::COUNTER_META_KEY ], $networks_info );
			}

			// Set global option for pages that are not a post.
			$networks_data = array(
				self::COUNTER_META_KEY   => $networks_info,
				self::TIMESTAMP_META_KEY => time(),
			);

			Hustle_Settings_Admin::update_hustle_settings( $networks_data, self::SETTINGS_KEY );
		}

		return $networks_info;
	}

	/**
	 * Get the data from each network's API
	 *
	 * @since 3.0.3
	 *
	 * @param string $current_link, array $social_networks, array $options
	 * @return array
	 */
	private function get_networks_data_from_api( $current_link, $social_networks = array(), $options = array() ) {
		$result = array();

		$networks_endpoint = self::get_networks_counter_endpoint( false, $current_link );

		foreach ( $social_networks as $network ) {

			if ( isset( $networks_endpoint[ $network ] ) ) {
				$url = $networks_endpoint[ $network ];
			} else {
				continue;
			}

			$response      = wp_remote_get( $url );
			$response_body = wp_remote_retrieve_body( $response );

			if ( $response_body ) {
				$result[ $network ] = $response_body;
			}
		}

		return $result;
	}

	/**
	 * Get the API URL for each network, or the name of the networks with a counter endpoint.
	 *
	 * @since 4.0
	 *
	 * @param string $networks only
	 * @param string $current_link
	 * @return array
	 */
	public static function get_networks_counter_endpoint( $networks_only = true, $current_link = '' ) {

		if ( $networks_only ) {
			return apply_filters(
				'hustle_networks_with_counter_endpoint',
				array( 'facebook', 'twitter', 'reddit', 'vkontakte' )
			);
		}

		$current_link = rawurlencode( $current_link );

		return apply_filters(
			'hustle_native_share_counter_enpoints',
			array(
				'facebook'  => 'https://graph.facebook.com/?fields=og_object{engagement{count}}&id=' . $current_link,
				// There's no official twitter api for doing this. This alternative requires signing in.
				'twitter'   => 'https://counts.twitcount.com/counts.php?url=' . $current_link,
				'reddit'    => 'https://www.reddit.com/api/info.json?url=' . $current_link,
				'vkontakte' => 'https://vk.com/share.php?act=count&url=' . $current_link,
			)
		);
	}

	/**
	 * Format the raw response from the networks so it can be displayed in front.
	 *
	 * @since 4.0
	 *
	 * @param array   $networks_data
	 * @param boolean $shorten_count If true, 1000 shares would be formatted to 1K.
	 * @return array
	 */
	private function format_networks_api_raw_data( $networks_data, $shorten_count = true ) {

		$formatted = array();

		foreach ( $networks_data as $network => $response ) {

			// Get "count" from each network's response and add the "count" number to $formatted array
			$get_formatted_response = 'format_' . $network . '_api_response';
			if ( ! is_callable( array( $this, $get_formatted_response ) ) ) {
				continue;
			}

			$formatted[ $network ] = $this->{$get_formatted_response}( $networks_data[ $network ] );

			if ( $shorten_count ) {
				$formatted[ $network ] = $this->shorten_count( $formatted[ $network ] );
			}
		}

		return $formatted;
	}

	/**
	 * Format a given number to display it nicely. 10K instead of 10093
	 *
	 * @since 3.0.3
	 *
	 * @param integer $count
	 * @return string
	 */
	private function shorten_count( $count ) {
		$count = intval( $count );
		if ( $count < 1000 ) {
			return $count;
		} elseif ( $count < 1000000 ) {
			return round( $count / 1000, 1, PHP_ROUND_HALF_DOWN ) . __( ' K', 'hustle' );
		} else {
			return round( $count / 1000000, 1, PHP_ROUND_HALF_DOWN ) . __( ' M', 'hustle' );
		}
	}

	/**
	 * Set option to trigger the refresh of the counters
	 *
	 * @since 3.0.3
	 */
	public static function refresh_all_counters() {
		update_option( self::REFRESH_OPTION_KEY, time() );
	}

	/**
	 * Format the response of each API to get the counter
	 *
	 * @since 3.0.3
	 *
	 * @param string $response
	 * @return integer
	 */
	private function format_facebook_api_response( $response ) {
		$response   = json_decode( $response, true );
		$engagement = ! empty( $response['og_object'] ) && ! empty( $response['og_object']['engagement']['count'] ) ? intval( $response['og_object']['engagement']['count'] ) : 0;

		return $engagement;
	}

	private function format_twitter_api_response( $response ) {
		$response = json_decode( $response, true );
		return isset( $response['count'] ) ? intval( $response['count'] ) : 0;
	}

	private function format_pinterest_api_response( $response ) {
		preg_match( '/^receiveCount\((.*)\)$/', $response, $match );
		if ( ! isset( $match[1] ) ) {
			return 0;
		}
		$response = json_decode( $match[1], true );
		return isset( $response['count'] ) ? intval( $response['count'] ) : 0;
	}

	private function format_reddit_api_response( $response ) {
		$response = json_decode( $response, true );
		if ( ! isset( $response['data']['children'] ) ) {
			return 0;
		}
		$data    = $response['data']['children'];
		$counter = 0;
		foreach ( $data as $sub ) {
			if ( ! isset( $sub['data']['subreddit_subscribers'] ) ) {
				continue;
			}
			$counter = $counter + intval( $sub['data']['subreddit_subscribers'] );
		}
		return $counter;
	}

	private function format_vkontakte_api_response( $response ) {
		preg_match( '/^VK\.Share\.count\(.{1,3}(.*)\)/', $response, $match );
		if ( ! isset( $match[1] ) ) {
			return 0;
		}
		return intval( $match[1] );
	}

	/**
	 * Get the network's sharing endpoints.
	 *
	 * @since 4.0
	 *
	 * @param bool $networks_only
	 * @return array
	 */
	public static function get_sharing_endpoints( $networks_only = true ) {

		if ( $networks_only ) {
			return apply_filters(
				'hustle_networks_with_share_enpoints',
				array( 'facebook', 'twitter', 'pinterest', 'reddit', 'linkedin', 'vkontakte', 'whatsapp', 'email' )
			);

		}

		global $wp;
		$current_url = rawurlencode( home_url( $wp->request ) );

		// let users filter the title
		$title = apply_filters( 'hustle_social_share_platform_title', rawurlencode( html_entity_decode( esc_html( get_the_title() ) ) ) );

		return apply_filters(
			'hustle_native_share_enpoints',
			array(

				'facebook'  => 'https://www.facebook.com/sharer/sharer.php?u=' . $current_url,
				'twitter'   => 'https://twitter.com/intent/tweet?url=' . $current_url . '&text=' . $title,
				'pinterest' => 'https://www.pinterest.com/pin/create/button/?url=' . $current_url,
				'reddit'    => 'https://www.reddit.com/submit?url=' . $current_url,
				'linkedin'  => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $current_url,
				'vkontakte' => 'https://vk.com/share.php?url=' . $current_url,
				'whatsapp'  => 'https://api.whatsapp.com/send?text=' . $current_url,
				'email'     => 'mailto:?subject=' . $title . '&body=' . $current_url,

			),
			$current_url
		);
	}

	public function get_renderer() {
		return new Hustle_Renderer_Sshare();
	}

}