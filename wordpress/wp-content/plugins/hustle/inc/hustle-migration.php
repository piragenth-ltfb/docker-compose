<?php

/**
 * Class Hustle_Migration
 *
 * @class Hustle_Migration
 */
class Hustle_Migration {

	/**
	 * Instance of Hustle_410_Migration.
	 *
	 * @since 4.1.0
	 * @var Hustle_410_Migration
	 */
	public $migration_410;

	/**
	 * Hustle_Migration instance.
	 *
	 * @since 4.1.0
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Whether any of the modules had custom css.
	 *
	 * @since 4.0
	 * @var boolean
	 */
	private $custom_css_migrated = false;

	private static $tracking_meta_keys = array(
		'popup_view',
		'popup_conversion',
		'slidein_view',
		'slidein_conversion',
		'after_content_view',
		'shortcode_view',
		'floating_social_view',
		'floating_social_conversion',
		'widget_view',
		'after_content_conversion',
		'shortcode_conversion',
		'widget_conversion',
		'subscription',
	);

	/**
	 * Get an istance of this class.
	 *
	 * @since 4.1.0
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {

		$this->is_multisite = is_multisite();

		add_action( 'wp_ajax_hustle_migrate_tracking', array( $this, 'migrate_tracking_and_subscriptions' ) );

		if ( $this->is_migration() ) {
			add_action( 'init', array( $this, 'do_hustle_30_migration' ) );
		}

		$this->migration_410 = new Hustle_410_Migration();

		$this->migration_430 = new Hustle_430_Migration();
	}

	/**
	 * Check whether we should run da migration.
	 *
	 * @since 4.0
	 * @return boolean
	 */
	private function is_migration() {

		// If migration is being forced, do it.
		if ( filter_input( INPUT_GET, 'reset_migration', FILTER_VALIDATE_BOOLEAN ) ) {
			return true;
		}

		// If migration was already done, skip.
		if ( self::is_migrated( 'hustle_30_migrated' ) ) {
			return false;
		}

		// If it's a fresh install, no need to migrate.
		return self::did_hustle_exist();
	}

	/**
	 * Get the previously installed version according to our flag.
	 *
	 * @since 4.2.1
	 *
	 * @return string|false
	 */
	public static function get_previous_installed_version() {
		return get_site_option( 'hustle_previous_version', false );
	}

	/**
	 * Check if a spesific migration is passed
	 *
	 * @param string $key Migration key
	 * @return bool
	 */
	public static function is_migrated( $key ) {
		$keys = get_option( 'hustle_migrations', null );
		if ( is_null( $keys ) ) {
			self::change_migration_options();
			$keys = get_option( 'hustle_migrations', array() );
		}

		return in_array( $key, $keys, true );
	}

	/**
	 * Save migration key
	 *
	 * @param string $key Migration key
	 */
	public static function migration_passed( $key ) {
		$keys = get_option( 'hustle_migrations', array() );
		if ( ! in_array( $key, $keys, true ) ) {
			$keys[] = $key;
			update_option( 'hustle_migrations', $keys );
		}
	}

	/**
	 * Remove the passed migration flag.
	 *
	 * @since 4.1.0
	 *
	 * @param string $key Flag name.
	 */
	public static function remove_migration_passed_flag( $flag ) {
		$keys = get_option( 'hustle_migrations', array() );
		if ( in_array( $flag, $keys, true ) ) {
			$key = array_search( $flag, $keys, true );

			if ( false !== $key ) {
				unset( $keys[ $key ] );
				update_option( 'hustle_migrations', $keys );
			}
		}
	}

	/**
	 * Resave migration keys to a new format
	 */
	private static function change_migration_options() {
		$keys = array(
			'hustle_20_migrated',
			'hustle_30_migrated',
			'hustle_30_tracking_migrated',
		);

		foreach ( $keys as $key ) {
			$option = get_option( $key );
			if ( $option ) {
				self::migration_passed( $key );
				delete_option( $key );
			}
		}
	}

	/**
	 * Check whether the tracking and subscriptions data needs to be migrated.
	 *
	 * @since 4.0
	 * @return bool
	 */
	public static function check_tracking_needs_migration() {

		// If migration was already done, skip.
		if ( self::is_migrated( 'hustle_30_tracking_migrated' ) ) {
			return false;
		}

		// If it's a fresh install, no need to migrate.
		if ( ! self::did_hustle_exist() ) {
			return false;
		}

		// If there isn't data to migrate, we're done.
		return self::is_tracking_subscription_data_to_migrate();
	}

	/**
	 * Check whether this is a new 4.0 installation.
	 *
	 * @since 4.0
	 * @return bool
	 */
	public static function did_hustle_exist() {
		$hustle_20_migrated = self::is_migrated( 'hustle_20_migrated' );

		return $hustle_20_migrated;
	}

	// Migrating from Hustle 3.x
	public function do_hustle_30_migration() {

		// Update tables on migration.
		Hustle_Db::maybe_create_tables( true );

		// Migrate global settings.
		$this->migrate_settings();

		$modules = $this->get_all_hustle_modules();
		if ( ! empty( $modules ) ) {
			array_map( array( __CLASS__, 'migrate_hustle_30' ), $modules );
		}

		if ( ! $this->custom_css_migrated ) {
			Hustle_Notifications::add_dismissed_notification( 'show_review_css_after_migration_notice' );
		}

		self::migration_passed( 'hustle_30_migrated' );
	}

	public function migrate_hustle_30( $old_module ) {

		// Don't migrate the modules that don't belong to the blog requesting the migration (useful on MU).
		if ( get_current_blog_id() !== (int) $old_module->blog_id ) {
			return;
		}

		if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $old_module->module_type ) {
			$this->migrate_non_sshare_module( $old_module );
		} else {
			$this->migrate_sshare_module( $old_module );
		}

	}

	public function get_all_hustle_modules() {
		$module_collection_instance = Hustle_Module_Collection::instance();
		return $module_collection_instance->get_hustle_30_modules( get_current_blog_id() );
	}

	private function migrate_sshare_module( $old_module ) {

		if ( ! $this->is_multisite || is_main_site( get_current_blog_id() ) ) {
			$module = new Hustle_SShare_Model( $old_module->module_id );
			$module->save();

		} else {

			// The tables in multisite are no longer shared between the sites of the network.
			// Instead, each site has its own tables, so they're empty and we should move the content there.
			$module = new Hustle_SShare_Model();

			$module->module_id   = $old_module->module_id;
			$module->active      = $old_module->active;
			$module->module_name = $old_module->module_name;
			$module->module_type = $old_module->module_type;
			$module->save_from_migration();

			// Shortcode.
			$module->update_meta( Hustle_Module_Model::KEY_SHORTCODE_ID, $old_module->meta['shortcode_id'] );

			// Track types.
			if ( isset( $old_module->meta['track_types'] ) ) {

				// Change 'floating_social' track type to 4.0 'floating' one.
				if ( isset( $old_module->meta['track_types']['floating_social'] ) ) {
					$old_module->meta['track_types']['floating'] = $old_module->meta['track_types']['floating_social'];
					unset( $old_module->meta['track_types']['floating_social'] );
				}

				$module->update_meta( Hustle_Module_Model::TRACK_TYPES, $old_module->meta['track_types'] );
			}
		}

		// Services.
		$content = $this->parse_sshare_content_meta( $module, $old_module );

		// Display.
		$display = $this->parse_sshare_display_meta( $module, $old_module );

		// Appearance.
		$design = $this->parse_sshare_design_meta( $module, $old_module );

		// Visibility.
		$visibility = $this->parse_visibility_meta( $module, $old_module );

		// Edit roles.
		$edit_roles = ! is_null( get_role( 'administrator' ) ) ? array( 'administrator' ) : array();

		$data = array(
			'id'         => $module->id,
			'content'    => $content,
			'design'     => $design,
			'display'    => $display,
			'visibility' => $visibility,
			'edit_roles' => $edit_roles,
		);

		$module->update_module( $data );
	}

	/**
	 * Parse the old content to the new format.
	 *
	 * @since 4.0
	 *
	 * @param Hustle_SShare_Model $module
	 * @param object              $old_module
	 * @return array
	 */
	private function parse_sshare_content_meta( $module, $old_module ) {
		$content = $module->get_content()->to_array();

		if ( $this->is_multisite ) {
			$content = array_merge( $content, $old_module->meta['content'] );
		}

		if ( 'native' !== $content['service_type'] || 'none' === $content['click_counter'] || '0' === $content['click_counter'] ) {
			$content['counter_enabled'] = '0';
		} else {
			$content['counter_enabled'] = '1';
		}

		if ( isset( $content['social_icons'] ) && ! empty( $content['social_icons'] ) ) {

			if ( isset( $content['social_icons']['google'] ) ) {
				unset( $content['social_icons']['google'] );
			}

			$platforms_with_counter_endpoint = Hustle_SShare_Model::get_networks_counter_endpoint();

			$social_platforms = Hustle_SShare_Model::get_social_platform_names();

			foreach ( $content['social_icons'] as $platform => $data ) {

				if ( 'native' === $content['click_counter'] ) {
					// Set to 'native' only if the platform has a native counter.
					$counter_type = in_array( $platform, $platforms_with_counter_endpoint, true ) ? 'native' : 'click';
				} else {
					// Applies for both 'click', '0', and 'none' click_counter.
					$counter_type = 'click';
				}
				$data['platform']                     = $platform;
				$data['type']                         = $counter_type;
				$data['label']                        = ! empty( $social_platforms[ $platform ] ) ? $social_platforms[ $platform ] : ucfirst( $platform );
				$content['social_icons'][ $platform ] = $data;
			}
		}

		return $content;
	}

	/**
	 * Parse the old design to the new one.
	 *
	 * @since 4.0
	 *
	 * @param Hustle_SShare_Model $module
	 * @param object              $old_module
	 * @return array
	 */
	private function parse_sshare_design_meta( $module, $old_module ) {

		$design     = $module->get_design()->to_array();
		$old_design = $old_module->meta['design'];

		if ( $this->is_multisite ) {
			$design = array_merge( $design, $old_module->meta['design'] );
		}

		$design['floating_customize_colors']   = $this->is_true( $old_design['customize_colors'] ) ? '1' : '0';
		$design['floating_icon_bg_color']      = $old_design['icon_bg_color'];
		$design['floating_icon_color']         = $old_design['icon_color'];
		$design['floating_bg_color']           = $old_design['floating_social_bg'];
		$design['floating_animate_icons']      = $this->is_true( $old_design['floating_social_animate_icons'] ) ? '1' : '0';
		$design['floating_drop_shadow']        = $this->is_true( $old_design['drop_shadow'] ) ? '1' : '0';
		$design['floating_drop_shadow_x']      = $old_design['drop_shadow_x'];
		$design['floating_drop_shadow_y']      = $old_design['drop_shadow_y'];
		$design['floating_drop_shadow_blur']   = $old_design['drop_shadow_blur'];
		$design['floating_drop_shadow_spread'] = $old_design['drop_shadow_spread'];
		$design['floating_drop_shadow_color']  = $old_design['drop_shadow_color'];
		$design['floating_inline_count']       = $this->is_true( $old_design['floating_inline_count'] ) ? '1' : '0';

		$design['widget_customize_colors'] = $this->is_true( $old_design['customize_widget_colors'] ) ? '1' : '0';

		// Same keys, making sure the value type is correct. String '1'|'0'.
		$design['widget_animate_icons'] = $this->is_true( $old_design['widget_animate_icons'] ) ? '1' : '0';
		$design['widget_drop_shadow']   = $this->is_true( $old_design['widget_drop_shadow'] ) ? '1' : '0';
		$design['widget_inline_count']  = $this->is_true( $old_design['widget_inline_count'] ) ? '1' : '0';

		return $design;
	}

	/**
	 * Parse ssharing specific display settings.
	 *
	 * @since 4.0
	 *
	 * @param Hustle_SShare_Model $module
	 * @param object              $old_module
	 * @return array
	 */
	private function parse_sshare_display_meta( $module, $old_module ) {

		$display      = $this->parse_display_meta( $module, $old_module );
		$old_settings = $old_module->meta['settings'];

		$test_types = isset( $old_module->meta['test_types'] ) ? $old_module->meta['test_types'] : array();

		if ( ! $this->is_true( $old_settings['floating_social_enabled'] ) ) {
			$display['float_desktop_enabled'] = '0';
			$display['float_mobile_enabled']  = '0';

		} elseif ( isset( $test_types['floating_social'] ) && $this->is_true( $test_types['floating_social'] ) ) {
			$display['float_desktop_enabled'] = '0';
			$display['float_mobile_enabled']  = '0';
		}

		// We didn't differentiate 'mobile' and 'desktop' floating in 3.x,
		// so the old settings apply to both.

		// We're removing old 'content' location since it never worked.
		$location_type                   = 'selector' === $old_settings['location_type'] ? 'css_selector' : 'screen';
		$display['float_desktop_offset'] = $location_type;
		$display['float_mobile_offset']  = $location_type;

		$display['float_desktop_css_selector'] = $old_settings['location_target'];
		$display['float_mobile_css_selector']  = $old_settings['location_target'];

		$display['float_desktop_position'] = $old_settings['location_align_x'];
		$display['float_mobile_position']  = $old_settings['location_align_x'];

		$display['float_desktop_position_y'] = $old_settings['location_align_y'];
		$display['float_mobile_position_y']  = $old_settings['location_align_y'];

		$offset_y                          = 'top' === $old_settings['location_align_y'] ? $old_settings['location_top'] : $old_settings['location_bottom'];
		$display['float_desktop_offset_y'] = $offset_y;
		$display['float_mobile_offset_y']  = $offset_y;

		$offset_x                          = 'right' === $old_settings['location_align_x'] ? $old_settings['location_right'] : $old_settings['location_left'];
		$display['float_desktop_offset_x'] = $offset_x;
		$display['float_mobile_offset_x']  = $offset_x;

		return $display;
	}

	/**
	 * Migrate the modules that are popups, slideins and embedded.
	 *
	 * @since 4.0
	 * @param object $old_module
	 */
	private function migrate_non_sshare_module( $old_module ) {

		if ( ! $this->is_multisite || is_main_site( get_current_blog_id() ) ) {
			$module = new Hustle_Module_Model( $old_module->module_id );

			// Modules with 'test mode' enabled should be drafts.
			if ( $this->is_true( $old_module->test_mode ) ) {
				$module->active = '0';
			}

			// Add the new 'module_mode' property.
			$module->module_mode = $this->get_module_mode( $old_module->meta['content'] );
			$module->save();

		} else {

			// The tables in multisite are no longer shared between the sites of the network.
			// Instead, each site has its own tables, so they're empty and we should move the content there.
			$module = new Hustle_Module_Model();

			// Modules with 'test mode' enabled should be drafts.
			$module->active = ! $this->is_true( $old_module->test_mode ) ? $old_module->active : '0';

			$module->module_id   = $old_module->module_id;
			$module->module_name = $old_module->module_name;
			$module->module_type = $old_module->module_type;
			$module->module_mode = $this->get_module_mode( $old_module->meta['content'] );
			$module->save_from_migration();

			// Shortcode.
			$module->update_meta( Hustle_Module_Model::KEY_SHORTCODE_ID, $old_module->meta['shortcode_id'] );

			// Track types.
			if ( isset( $old_module->meta['track_types'] ) && ! empty( $old_module->meta['track_types'] ) ) {

				// Change 'after_content' track type to 4.0 'inline' one.
				if ( isset( $old_module->meta['track_types']['after_content'] ) ) {
					$old_module->meta['track_types']['inline'] = $old_module->meta['track_types']['after_content'];
					unset( $old_module->meta['track_types']['after_content'] );
				}
				$module->update_meta( Hustle_Module_Model::TRACK_TYPES, $old_module->meta['track_types'] );
			}
		}

		// Handling metas.
		// Content.
		$content = $this->parse_content_meta( $module, $old_module );

		// Emails.
		$emails = $this->parse_email_meta( $module, $old_module );

		// Integrations. For 'optins' only.
		$integrations_settings = array();
		if ( 'optin' === $module->module_mode ) {
			$integrations_settings = $this->migrate_integrations( $module, $old_module );
		}

		// Appearance.
		$design = $this->parse_design_meta( $module, $old_module );

		// Display options. For Embedded modules only.
		if ( Hustle_Module_Model::EMBEDDED_MODULE === $old_module->module_type ) {
			$display = $this->parse_display_meta( $module, $old_module );
		} else {
			$display = array();
		}

		// Visibility.
		$visibility = $this->parse_visibility_meta( $module, $old_module );

		// Behavior.
		$settings = $this->parse_settings_meta( $module, $old_module );

		// Edit roles.
		$edit_roles = ! is_null( get_role( 'administrator' ) ) ? array( 'administrator' ) : array();

		$data = array(
			'id'                    => $module->id,
			'content'               => $content,
			'emails'                => $emails,
			'design'                => $design,
			'integrations_settings' => $integrations_settings,
			'display'               => $display,
			'visibility'            => $visibility,
			'settings'              => $settings,
			'edit_roles'            => $edit_roles,
		);

		$module->update_module( $data );

	}

	/**
	 * Get the module's mode according to the old content.
	 * 'optin' if email collection was enabled, 'informational' otherwise.
	 * Empty string for Social sharing modules that doesn't have email collection.
	 *
	 * @since 4.0
	 *
	 * @param array $content
	 * @return string
	 */
	private function get_module_mode( $content ) {
		$mode = 'informational';
		if ( isset( $content['use_email_collection'] ) && $this->is_true( $content['use_email_collection'] ) ) {
			$mode = 'optin';
		}

		return $mode;
	}

	/**
	 * Create the new 'content' settings according to the old module.
	 * The old data is used to replace the defaults.
	 * The old unused data is not being removed atm.
	 *
	 * @since 4.0
	 *
	 * @param Hustle_Module_Model $module
	 * @param object              $old_module
	 * @return array
	 */
	private function parse_content_meta( $module, $old_module ) {
		$content = $module->get_content()->to_array();

		if ( $this->is_multisite ) {
			$content = array_merge( $content, $old_module->meta['content'] );
		}

		if ( ! $this->is_true( $content['has_title'] ) ) {
			$content['title']     = '';
			$content['sub_title'] = '';
		}

		if ( ! $this->is_true( $content['use_feature_image'] ) ) {
			$content['feature_image'] = '';
		}

		$content['show_cta'] = $this->is_true( $content['show_cta'] ) ? '1' : '0';

		return $content;
	}

	/**
	 * Create the new 'emails' settings according to the old module.
	 *
	 * @since 4.0
	 *
	 * @param Hustle_Module_Model $module
	 * @param object              $old_module
	 * @return array
	 */
	private function parse_email_meta( $module, $old_module ) {
		$emails      = $module->get_emails()->to_array();
		$old_content = $old_module->meta['content'];

		if ( ! isset( $old_content['form_elements'] ) ) {
			return $emails;
		}

		$old_form_fields = $old_content['form_elements'];

		if ( is_string( $old_form_fields ) ) {
			$old_form_fields = json_decode( $old_form_fields, true );
		}

		foreach ( $old_form_fields as $name => $properties ) {

			if ( true === $old_form_fields[ $name ]['required'] ) {
				$old_form_fields[ $name ]['required'] = 'true';
			}

			if ( 'url' === $old_form_fields[ $name ]['type'] || 'email' === $old_form_fields[ $name ]['type'] ) {
				$old_form_fields[ $name ]['validate'] = 'true';
			}

			if ( isset( $old_form_fields[ $name ]['delete'] ) ) {
				$can_delete = $old_form_fields[ $name ]['delete'];
				unset( $old_form_fields[ $name ]['delete'] );
			} else {
				$can_delete = true;
			}
			$old_form_fields[ $name ]['can_delete'] = $can_delete;

		}

		// Replace old 'f_name' by 'first_name' so we can stop doing legacy conversions along the plugin.
		if ( isset( $old_form_fields['f_name'] ) ) {
			$old_form_fields['f_name']['name'] = 'first_name';
			$old_form_fields                   = Opt_In_Utils::replace_array_key( 'f_name', 'first_name', $old_form_fields );
		}

		// Replace old 'l_name' by 'last_name' so we can stop doing legacy conversions along the plugin.
		if ( isset( $old_form_fields['l_name'] ) ) {
			$old_form_fields['l_name']['name'] = 'last_name';
			$old_form_fields                   = Opt_In_Utils::replace_array_key( 'l_name', 'last_name', $old_form_fields );
		}

		// Set the new recaptcha properties according to what was used in 3.x
		if ( isset( $old_form_fields['recaptcha'] ) ) {
			$old_form_fields['recaptcha']['recaptcha_type']  = 'full';
			$old_form_fields['recaptcha']['recaptcha_theme'] = 'light';
		}

		// Use the 4.0 error message.
		if ( isset( $old_form_fields['submit'] ) ) {
			$old_form_fields['submit']['error_message'] = __( 'Please fill out all required fields.', 'hustle' );
		}

		// Make gdpr a form field for optins.
		if ( isset( $old_content['show_gdpr'] ) && $this->is_true( $old_content['show_gdpr'] ) ) {
			$old_form_fields['gdpr'] = array(
				'label'        => 'gdpr',
				'required'     => 'true',
				'css_classes'  => '',
				'type'         => 'gdpr',
				'name'         => 'gdpr',
				'can_delete'   => 'true',
				'placeholder'  => '',
				'gdpr_message' => $old_content['gdpr_message'],
			);
		}

		$emails['form_elements'] = $module->sanitize_form_elements( $old_form_fields );

		$emails['after_successful_submission'] = $old_content['after_successful_submission'];
		$emails['success_message']             = $old_content['success_message'];
		$emails['auto_close_success_message']  = $this->is_true( $old_content['auto_close_success_message'] ) ? '1' : '0';

		if ( isset( $old_content['auto_close_time'] ) ) {
			$emails['auto_close_time'] = $old_content['auto_close_time'];
		}

		if ( isset( $old_content['auto_close_unit'] ) ) {
			$emails['auto_close_unit'] = $old_content['auto_close_unit'];
		}

		if ( isset( $old_content['redirect_url'] ) ) {
			$emails['redirect_url'] = $old_content['redirect_url'];
		}

		return $emails;
	}

	/**
	 * Create the new 'design' settings according to the old module.
	 * The old data is used to replace the defaults.
	 * The old unused data is not being removed atm.
	 *
	 * @since 4.0
	 *
	 * @param Hustle_Module_Model $module
	 * @param object              $old_module
	 * @return array
	 */
	private function parse_design_meta( $module, $old_module ) {
		$design = $module->get_design()->to_array();
		if ( $this->is_multisite ) {
			$design = array_merge( $design, $old_module->meta['design'] );
		}
		$old_content = $old_module->meta['content'];

		$design['feature_image_hide_on_mobile'] = $this->is_true( $old_content['feature_image_hide_on_mobile'] ) ? '1' : '0';

		// There's a bug in 3.x that applied customized colors even when disabled.
		// Turning on "customize_colors" to keep the same appearance in front after migration.
		$design['customize_colors'] = '1';
		$design['border']           = $this->is_true( $design['border'] ) ? '1' : '0';
		$design['drop_shadow']      = $this->is_true( $design['drop_shadow'] ) ? '1' : '0';
		$design['customize_size']   = $this->is_true( $design['customize_size'] ) ? '1' : '0';

		$is_optin = 'optin' === $module->module_mode;
		if ( $is_optin ) {

			// When making a module 'optin' in 3.x, the selected palette remained as the informational one.
			if ( in_array( $design['style'], Hustle_Palettes_Helper::get_palettes_names(), true ) ) {
				$design['color_palette'] = $design['style'];
			} else {
				$design['color_palette'] = 'gray_slate';
			}

			if ( isset( $design['button_border_color'] ) ) {
				$design['optin_submit_button_static_bo'] = $design['button_border_color'];
				$design['optin_submit_button_active_bo'] = $design['button_border_color'];
				$design['optin_submit_button_active_bo'] = $design['button_border_color'];
				$design['optin_submit_button_hover_bo']  = $design['button_border_color'];
			}

			// When input's borders is disabled...
			if ( ! $this->is_true( $design['form_fields_border'] ) ) {

				// Always make the input's style "outlined" instead of "flat" in order
				// to keep the input's borders highlighted on error.
				$design['form_fields_border'] = '1';

				// Make the borders invisible in all states, except for the error one.
				$design['optin_input_static_bo'] = $design['optin_input_static_bg'];
				$design['optin_input_hover_bo']  = $design['optin_input_hover_bg'];
				$design['optin_input_active_bo'] = $design['optin_input_active_bg'];

				// And make the border's attributes match the 3.x on error one.
				$design['form_fields_border_radius'] = '0';
				$design['form_fields_border_weight'] = '1';
				$design['form_fields_border_type']   = 'solid';

			} elseif ( isset( $design['form_fields_border_color'] ) ) {

				$design['optin_input_static_bo'] = $design['form_fields_border_color'];
				$design['optin_input_hover_bo']  = $design['form_fields_border_color'];
				$design['optin_input_active_bo'] = $design['form_fields_border_color'];
			}

			if ( isset( $design['optin_input_icon'] ) ) {
				$design['optin_input_icon_hover'] = $design['optin_input_icon'];
				$design['optin_input_icon_focus'] = $design['optin_input_icon'];
			}

			if ( isset( $design['optin_check_radio_bg'] ) ) {
				$design['optin_check_radio_bg_checked'] = $design['optin_check_radio_bg'];
			}

			// Modules before 3.0.3 don't have gdpr options.
			if ( isset( $design['gdpr_border_color'] ) ) {
				$design['gdpr_chechbox_border_static'] = $design['gdpr_border_color'];
				$design['gdpr_chechbox_border_active'] = $design['gdpr_border_color'];
			}

			// When gdpr checkbox's border is disabled...
			if ( isset( $design['gdpr_border'] ) && ! $this->is_true( $design['gdpr_border'] ) ) {

				// Always make the input's style "outlined" instead of "flat" in order
				// to keep the input's borders highlighted on error.
				$design['gdpr_border'] = '1';

				// Make the borders invisible in all states, except for the error one.
				$design['gdpr_chechbox_border_static'] = $design['gdpr_chechbox_background_static'];
				$design['gdpr_chechbox_border_active'] = $design['gdpr_checkbox_background_active'];

				// And make the border's attributes match the 3.x on error one.
				$design['gdpr_border_radius'] = '0';
				$design['gdpr_border_weight'] = '2';
				$design['gdpr_border_type']   = 'solid';

			}

			$design['optin_input_error_background'] = $design['optin_input_static_bg'];

			$design['form_fields_style']   = empty( $design['form_fields_border'] ) || 'false' === $design['form_fields_border'] ? 'flat' : 'outlined';
			$design['button_style']        = empty( $design['button_border'] ) || 'false' === $design['button_border'] ? 'flat' : 'outlined';
			$design['gdpr_checkbox_style'] = empty( $design['gdpr_border'] ) || 'false' === $design['gdpr_border'] ? 'flat' : 'outlined';

		} else {
			$design['title_color_alt']    = $design['title_color'];
			$design['subtitle_color_alt'] = $design['subtitle_color'];
		}

		if ( ! empty( trim( $design['custom_css'] ) ) ) {
			$this->custom_css_migrated = true;
			$new_css                   = $this->parse_custom_css( $design['custom_css'], $is_optin );
			$design['custom_css']      = $new_css . ' /*' . $design['custom_css'] . '*/';
		}

		return $design;
	}

	/**
	 * Migrate the old providers to the new format.
	 *
	 * @uses Hustle_Provider_Abstract::migrate_30
	 * @since 4.0
	 *
	 * @param Hustle_Module_Model $module
	 * @param object              $old_module
	 * @return array
	 */
	private function migrate_integrations( $module, $old_module ) {
		$old_content           = $old_module->meta['content'];
		$integrations_settings = array();

		if ( $this->is_true( $old_content['save_local_list'] ) ) {
			$local_list = Hustle_Provider_Utils::get_provider_by_slug( 'local_list' );
			if ( isset( $old_content['local_list_name'] ) && ! empty( $old_content['local_list_name'] ) ) {
				$list_name = $old_content['local_list_name'];
			} else {
				$list_name = __( 'List', 'hustle' ) . ' ' . $module->module_id;
			}

			$local_list_form_settings = $local_list->get_provider_form_settings( $module->module_id );
			$local_list_form_settings->save_form_settings_values( array( 'local_list_name' => $list_name ) );
		}

		if ( ! empty( $old_content['email_services'] ) ) {

			foreach ( $old_content['email_services'] as $slug => $data ) {

				$provider = Hustle_Provider_Utils::get_provider_by_slug( $slug );
				if ( $provider instanceof Hustle_Provider_Abstract ) {

					$migrated = $provider->migrate_30( $module, $old_module );

					if ( ! $migrated ) {
						Opt_In_Utils::maybe_log( __METHOD__ . ': Module ' . $module->module_id . ' with email provider ' . $slug . ' could not be migrated.' );
					}
				}
			}

			$active_email_service = $old_content['active_email_service'];
			if ( 'mailchimp' === $active_email_service ) {
				$mailchimp_settings = $old_content['email_services']['mailchimp'];
				if ( isset( $mailchimp_settings['allow_subscribed_users'] ) && 'allow' === $mailchimp_settings['allow_subscribed_users'] ) {
					$integrations_settings['allow_subscribed_users'] = '1';
				}
			}

			$integrations_settings['active_integrations'] = $active_email_service;
		}

		return $integrations_settings;
	}

	/**
	 * Create the new 'display options' settings according to the old module.
	 * Used by Embedded and Social sharing modules only.
	 *
	 * @since 4.0
	 *
	 * @param Hustle_Module_Model $module
	 * @param object              $old_module
	 * @return array
	 */
	private function parse_display_meta( $module, $old_module ) {
		$display      = $module->get_display()->to_array();
		$old_settings = $old_module->meta['settings'];
		$test_types   = isset( $old_module->meta['test_types'] ) ? $old_module->meta['test_types'] : array();

		if ( isset( $old_settings['after_content_enabled'] ) && $this->is_true( $old_settings['after_content_enabled'] ) ) {
			if ( ! isset( $test_types['after_content'] ) || ! $this->is_true( $test_types['after_content'] ) ) {
				$display['inline_enabled'] = '1';
			}
		}

		if ( isset( $old_settings['widget_enabled'] ) && ! $this->is_true( $old_settings['widget_enabled'] ) ) {
			$display['widget_enabled'] = '0';

		} elseif ( isset( $test_types['widget'] ) && $this->is_true( $test_types['widget'] ) ) {
			$display['widget_enabled'] = '0';
		}

		if ( isset( $old_settings['shortcode_enabled'] ) && ! $this->is_true( $old_settings['shortcode_enabled'] ) ) {
			$display['shortcode_enabled'] = '0';

		} elseif ( isset( $test_types['shortcode'] ) && $this->is_true( $test_types['shortcode'] ) ) {
			$display['shortcode_enabled'] = '0';
		}

		return $display;
	}

	/**
	 * Create the new 'settings' settings according to the old module.
	 * The old data is used to replace the defaults.
	 * The old unused data is not being removed atm.
	 *
	 * @since 4.0
	 *
	 * @param Hustle_Module_Model $module
	 * @param object              $old_module
	 * @return array
	 */
	private function parse_settings_meta( $module, $old_module ) {
		$settings     = $module->get_settings()->to_array();
		$old_settings = $old_module->meta['settings'];
		$old_content  = $old_module->meta['content'];
		if ( $this->is_multisite ) {
			$settings = array_merge( $settings, $old_settings );
		}

		if ( isset( $old_settings['allow_scroll_page'] ) ) {
			$settings['allow_scroll_page'] = $this->is_true( $old_settings['allow_scroll_page'] ) ? '1' : '0';
		}

		if ( isset( $old_settings['not_close_on_background_click'] ) ) {
			$settings['close_on_background_click'] = ! $this->is_true( $old_settings['not_close_on_background_click'] ) ? '1' : '0';
		}

		if ( isset( $old_settings['auto_hide'] ) ) {
			$settings['auto_hide'] = $this->is_true( $old_settings['auto_hide'] ) ? '1' : '0';
		}

		// The 3.x default was an empty string, which behaved as "no_animation".
		if ( isset( $old_settings['animation_in'] ) && '' === $old_settings['animation_in'] ) {
			$settings['animation_in'] = 'no_animation';
		}

		// The 3.x default was an empty string, which behaved as "no_animation".
		if ( isset( $old_settings['animation_out'] ) && '' === $old_settings['animation_out'] ) {
			$settings['animation_out'] = 'no_animation';
		}

		// An old bug where this setting was empty, and the wrong option showed up selected in wizard.
		if ( empty( $old_settings['after_close'] ) ) {
			$settings['after_close'] = 'keep_show';
		}

		if ( isset( $old_content['after_subscription'] ) ) {
			$settings['hide_after_subscription'] = $old_content['after_subscription'];
		}

		if ( Hustle_Module_Model::EMBEDDED_MODULE !== $module->module_type && isset( $old_settings['triggers'] ) ) {

			// Check for click trigger.
			if ( isset( $old_settings['triggers']['trigger'] ) && 'click' === $old_settings['triggers']['trigger'] ) {
				$settings['triggers']['enable_on_click_shortcode'] = '1';
				$settings['triggers']['enable_on_click_element']   = '1';
			}

			// The time trigger switch was removed, so make the time to show '0' if it was turend off.
			if ( ! $this->is_true( $old_settings['triggers']['on_time'] ) ) {
				$settings['triggers']['on_time_delay'] = '0';
			}

			// Same keys. Making sure the value's type is the same that we're using in 4.0.
			if ( isset( $old_settings['triggers']['on_exit_intent_per_session'] ) ) {
				$settings['triggers']['on_exit_intent_per_session'] = $this->is_true( $old_settings['triggers']['on_exit_intent_per_session'] ) ? '1' : '0';
			}

			if ( isset( $old_settings['triggers']['on_exit_intent_delayed'] ) ) {
				$settings['triggers']['on_exit_intent_delayed'] = $this->is_true( $old_settings['triggers']['on_exit_intent_delayed'] ) ? '1' : '0';
			}

			if ( isset( $old_settings['on_adblock']['on_exit_intent_delayed'] ) ) {
				$settings['triggers']['on_adblock'] = $this->is_true( $old_settings['triggers']['on_adblock'] ) ? '1' : '0';
			}
		}

		return $settings;
	}

	/**
	 * Create the new 'visibility' settings according to the old module.
	 *
	 * @since 4.0
	 *
	 * @param Hustle_Module_Model $module
	 * @param object              $old_module
	 * @return array
	 */
	private function parse_visibility_meta( $module, $old_module ) {
		$conditions   = $module->get_visibility()->to_array();
		$old_settings = $old_module->meta['settings'];

		if ( isset( $old_settings['conditions'] ) ) {

			$old_conditions = $old_settings['conditions'];

			$group_id = substr( md5( wp_rand() ), 0, 10 );

			$new_conditions = array();

			// Visitor logged in status.
			if ( isset( $old_conditions['visitor_logged_in'] ) && 'true' === $old_conditions['visitor_logged_in'] ) {
				$new_conditions['visitor_logged_in_status']['show_to'] = 'logged_in';

			} elseif ( isset( $old_conditions['visitor_not_logged_in'] ) && 'true' === $old_conditions['visitor_not_logged_in'] ) {
				$new_conditions['visitor_logged_in_status']['show_to'] = 'logged_out';
			}

			// Visitor's device.
			if ( isset( $old_conditions['only_on_mobile'] ) && 'true' === $old_conditions['only_on_mobile'] ) {
				$new_conditions['visitor_device']['filter_type'] = 'mobile';

			} elseif ( isset( $old_conditions['not_on_mobile'] ) && 'true' === $old_conditions['not_on_mobile'] ) {
				$new_conditions['visitor_device']['filter_type'] = 'not_mobile';
			}

			// Referrer.
			if ( isset( $old_conditions['from_specific_ref'] ) ) {
				$new_conditions['from_referrer']['filter_type'] = 'true';
				$new_conditions['from_referrer']['refs']        = $old_conditions['from_specific_ref']['refs'];

			} elseif ( isset( $old_conditions['not_from_specific_ref'] ) ) {
				$new_conditions['from_referrer']['filter_type'] = 'false';
				$new_conditions['from_referrer']['refs']        = $old_conditions['from_specific_ref']['refs'];
			}

			// Source of arrival.
			if ( isset( $old_conditions['not_from_internal_link'] ) || isset( $old_conditions['from_search_engine'] ) ) {

				if ( isset( $old_conditions['not_from_internal_link'] ) && 'true' === $old_conditions['not_from_internal_link'] ) {
					$new_conditions['source_of_arrival']['source_external'] = 'true';

				}

				if ( isset( $old_conditions['from_search_engine'] ) && 'true' === $old_conditions['from_search_engine'] ) {
					$new_conditions['source_of_arrival']['source_search'] = 'true';
				}
			}

			// On URL.
			if ( isset( $old_conditions['on_specific_url'] ) ) {
				$new_conditions['on_url']['filter_type'] = 'only';
				$new_conditions['on_url']['urls']        = $old_conditions['on_specific_url']['urls'];

			} elseif ( isset( $old_conditions['not_on_specific_url'] ) ) {
				$new_conditions['on_url']['filter_type'] = 'except';
				$new_conditions['on_url']['urls']        = $old_conditions['not_on_specific_url']['urls'];
			}

			// Visitor has commented.
			if ( isset( $old_conditions['visitor_has_commented'] ) && 'true' === $old_conditions['visitor_has_commented'] ) {
				$new_conditions['visitor_commented']['filter_type'] = 'true';

			} elseif ( isset( $old_conditions['visitor_has_never_commented'] ) && 'true' === $old_conditions['visitor_has_never_commented'] ) {
				$new_conditions['visitor_commented']['filter_type'] = 'false';
			}

			// Country.
			if ( isset( $old_conditions['in_a_country'] ) ) {
				$new_conditions['visitor_country']['filter_type'] = 'only';
				$new_conditions['visitor_country']['countries']   = $old_conditions['in_a_country']['countries'];

			} elseif ( isset( $old_conditions['not_in_a_country'] ) ) {
				$new_conditions['visitor_country']['filter_type'] = 'except';
				$new_conditions['visitor_country']['countries']   = $old_conditions['not_in_a_country']['countries'];
			}

			// 404.
			if ( isset( $old_conditions['only_on_not_found'] ) && 'true' === $old_conditions['only_on_not_found'] ) {
				$new_conditions['page_404']['show'] = 'true';
			}

			// Module shown less than.
			if ( isset( $old_conditions['shown_less_than'] ) ) {
				$new_conditions['shown_less_than']['filter_type'] = 'limited';
				$new_conditions['shown_less_than']['less_than']   = $old_conditions['shown_less_than']['less_than'];
			}

			// Custom Post Types
			$post_types = Opt_In_Utils::get_post_types();
			$cpts       = wp_list_pluck( $post_types, 'label', 'name' );
			foreach ( $cpts as $slug => $label ) {
				if ( isset( $old_conditions[ $label ] ) ) {
					$new_conditions[ $slug ] = $old_conditions[ $label ];
				}
			}

			$regular_conditions_keys = array( 'pages', 'posts', 'categories', 'tags' );
			foreach ( $regular_conditions_keys as $key ) {
				if ( isset( $old_conditions[ $key ] ) ) {
					$new_conditions[ $key ] = $old_conditions[ $key ];
				}
			}

			$new_visibility                             = array();
			$new_visibility[ $group_id ]                = $new_conditions;
			$new_visibility[ $group_id ]['group_id']    = $group_id;
			$new_visibility[ $group_id ]['filter_type'] = 'any';

			$visibility = array( 'conditions' => $new_visibility );

		} else {
			$visibility = array();
		}

		return $visibility;
	}

	/**
	 * Migrate global settings.
	 *
	 * @since 4.0
	 */
	private function migrate_settings() {

		$current_settings = get_option( 'hustle_settings', array() );

		// Email sender address and name settings.
		$old_email_sender_settings = get_option( 'hustle_global_email_settings' );
		if ( $old_email_sender_settings ) {
			$current_settings['general']['sender_email_address'] = isset( $old_email_sender_settings['sender_email_address'] ) ? $old_email_sender_settings['sender_email_address'] : get_option( 'admin_email', '' );
			$current_settings['general']['sender_email_name']    = isset( $old_email_sender_settings['sender_email_name'] ) ? $old_email_sender_settings['sender_email_name'] : get_option( 'blogname', '' );
		}

		// Unsubscription email and messages.
		$old_unsubscription_settings = get_option( 'hustle_global_unsubscription_settings' );
		if ( $old_unsubscription_settings ) {
			$current_settings['unsubscribe']['messages'] = isset( $old_unsubscription_settings['messages'] ) ? $old_unsubscription_settings['messages'] : '';
			$current_settings['unsubscribe']['email']    = isset( $old_unsubscription_settings['email'] ) ? $old_unsubscription_settings['email'] : '';
		}

		update_option( 'hustle_settings', $current_settings );
	}

	/**
	 * Take all classes and replace them with the new ones.
	 *
	 * @since 4.0
	 *
	 * @param string $custom_css
	 * @param bool   $is_optin
	 * @return string
	 */
	private function parse_custom_css( $custom_css, $is_optin ) {

		$replace_values = array(
			'.wph-modal'                         => '', // Main wrapper (no need to migrate this, main wrapper "hustle-ui" it's automatically added on 4.0)
			'.hustle-modal'                      => '.hustle-layout', // Content wrapper
			'.wph-modal-active'                  => '.hustle-show', // Active class
			'.hustle-modal-title'                => '.hustle-title', // Title
			'.hustle-modal-subtitle'             => '.hustle-subtitle', // Subtitle
			'section .hustle-modal-article'      => '.hustle-content',
			'.hustle-modal-article'              => '.hustle-content',
			'section'                            => '.hustle-content',
			'.hustle-layout .hustle-modal-close' => '.hustle-modal-close', // .hustle-layout (previously .hustle-modal) is no longer a parent.
			'.hustle-modal-close .hustle-icon'   => '.hustle-button-close [class*="hustle-icon-"]', // Close button (icon)
			'.hustle-modal-close'                => '.hustle-button-close', // Close button
			'.hustle-modal-image'                => '.hustle-image', // Feat. image
			'.hustle-modal-cta'                  => '.hustle-button-cta', // Call to action
			'.hustle-modal-image_only'           => '.hustle-image-only', // Image only
			'.hustle-modal-mobile_hidden'        => '.hustle-hide-until-sm', // Mobile hidden
			'.hustle-modal-content'              => '.hustle-layout-content',
			'.hustle-modal-footer'               => '.hustle-layout-footer',
		);

		if ( $is_optin ) {

			$extra_classes = array(
				'.hustle-modal-body'                    => '.hustle-layout-body', // Body
				'footer'                                => '.hustle-layout-form', // Form container
				'.hustle-modal-optin_form'              => '.hustle-layout-form', // Form container
				'.hustle-modal-optin_field'             => '.hustle-field', // Form field(s)
				'.hustle-modal-optin_group'             => '.hustle-form-options', // Provider's extra options
				'.hustle-modal-optin_button button'     => '.hustle-button-submit', // Submit button
				'.hustle-modal-optin_button'            => '.hustle-button-submit', // Submit button
				'.hustle-modal-optin_field input'       => '.hustle-input', // Inputs
				'.hustle-modal-provider-args-container' => '.hustle-form-options', // Provider's extra options
				'.hustle-modal-one'                     => '.hustle-optin--default', // Layout 1 (Default)
				'.hustle-modal-two'                     => '.hustle-optin--compact', // Layout 2 (Compact)
				'.hustle-modal-three'                   => '.hustle-optin--focus-optin', // Layout 3 (Optin Focus)
				'.hustle-modal-four'                    => '.hustle-optin--focus-content', // Layout 4 (Content Focus)
				'.hustle-layout .hustle-modal-success'  => '.hustle-success',
				'.hustle-modal-success'                 => '.hustle-success',
			);

		} else {

			$extra_classes = array(
				'.hustle-layout .hustle-modal-body' => '.hustle-layout', // Body
				'.hustle-modal-body'                => '.hustle-layout', // Body
				'.hustle-modal-simple'              => '.hustle-info--compact', // Simple (Compact)
				'.hustle-modal-minimal'             => '.hustle-info--default', // Minimal (Default)
				'.hustle-modal-cabriolet'           => '.hustle-info--stacked', // Cabriolet (Stacked)
				'.hustle-modal-header'              => '.hustle-layout-header',
			);
		}

		$replace_values = array_merge( $replace_values, $extra_classes );

		foreach ( $replace_values as $old => $new ) {
			$custom_css = preg_replace( '/' . $old . '(?!-|[a-z])/m', $new, $custom_css );
		}

		return $custom_css;

	}

	private function _migrate_page_shares( $page_shares ) {
		$ss = new Hustle_SShare_Model();
		// floating social views
		foreach ( $page_shares as $val ) {
			$ss->id = $val->optin_id;
			$ss->add_meta( $val->meta_key, $val->meta_value );
		}
	}

	private function finish_tracking_subscription_migration( $migrated_rows = 0 ) {
		// Set the flag that we already migrated the tracking.
		self::mark_tracking_migration_as_completed();
		wp_send_json_success(
			array(
				'current_meta'  => 'done',
				'migrated_rows' => $migrated_rows,
			)
		);
	}

	public static function mark_tracking_migration_as_completed() {
		delete_option( 'hustle_30_migration_data' );
		self::migration_passed( 'hustle_30_tracking_migrated' );
	}

	/**
	 * Check whether there's tracking and subscriptions data to be migrated.
	 *
	 * @since 4.0
	 *
	 * @return boolean
	 */
	public static function is_tracking_subscription_data_to_migrate() {

		$migration_process_data = get_option( 'hustle_30_migration_data', array() );

		if ( ! empty( $migration_process_data ) ) {
			return true;
		}

		$blog_modules_id = Hustle_Module_Collection::instance()->get_30_modules_ids_by_blog( get_current_blog_id() );

		// If we don't have modules, finish.
		if ( empty( $blog_modules_id ) ) {
			self::mark_tracking_migration_as_completed();
			return false;
		}

		$total_entries = self::get_tracking_submissions_count( $blog_modules_id );

		// If we don't have tracking nor submissions, finish.
		if ( ! $total_entries ) {
			self::mark_tracking_migration_as_completed();
			return false;
		}

		return true;
	}

	/**
	 * Migrate tracking and subscription data.
	 * This is done via ajax in order to avoid timeouts.
	 *
	 * @since 4.0
	 */
	public function migrate_tracking_and_subscriptions() {
		Opt_In_Utils::validate_ajax_call( 'hustle-migrate-tracking-and-subscriptions' );

		global $wpdb;
		$main_site_table = $wpdb->base_prefix . Hustle_Db::TABLE_HUSTLE_MODULES_META;
		$batch_limit     = intval( apply_filters( 'hustle_migration_tracking_batch_limit', 50 ) );

		$migration_data = get_option( 'hustle_30_migration_data', array() );

		// Things to get in the first run only.
		if ( ! empty( $migration_data ) ) {
			$blog_modules_id = $migration_data['blog_modules_id'];
			$current_meta    = $migration_data['current_meta'];

		} else {

			$blog_modules_id = Hustle_Module_Collection::instance()->get_30_modules_ids_by_blog( get_current_blog_id() );

			// If we don't have modules, finish.
			if ( empty( $blog_modules_id ) ) {
				$this->finish_tracking_subscription_migration();
			}

			$total_entries = self::get_tracking_submissions_count( $blog_modules_id, $wpdb );

			// If we don't have tracking nor submissions, finish.
			if ( ! $total_entries ) {
				$this->finish_tracking_subscription_migration();
			}

			$current_meta = 0;

			// If there's enough data for 1 run only.
			if ( $batch_limit > $total_entries ) {
				$total_batches = 1;
			} else {
				$total_batches = round( intval( $total_entries ) / intval( $batch_limit ) );
			}

			$migration_data = array(
				'blog_modules_id'      => $blog_modules_id,
				'current_meta'         => $current_meta,
				'total_entries'        => $total_entries,
				'migrated_rows'        => 0,
				'percentage_per_batch' => 100 / $total_batches,
				'migrated_percentage'  => 0,
			);

			update_option( 'hustle_30_migration_data', $migration_data );
		}
		$migrated_rows = $migration_data['migrated_rows'];

		$metas = $this->get_paged_metas( $blog_modules_id, $current_meta, $batch_limit, $wpdb );

		// If there aren't more metas, we finished.
		if ( ! $metas ) {
			$this->finish_tracking_subscription_migration( $migrated_rows );
		}

		foreach ( $metas as $meta ) {

			$migrated_rows++;

			// Store the new views, conversions, and subscriptions.
			if ( false !== stripos( $meta->meta_key, 'view' ) ) {
				$current_meta = $this->migrate_tracking( $meta, 'view' );

			} elseif ( false !== stripos( $meta->meta_key, 'conversion' ) ) {
				$current_meta = $this->migrate_tracking( $meta, 'conversion' );

			} elseif ( 'subscription' === $meta->meta_key ) {
				$current_meta = $this->migrate_subscription( $meta );

			} elseif ( false !== stripos( $meta->meta_key, 'page_shares' ) ) {
				$current_meta = $this->migrate_sshare_page_counter( $meta );
			}
		}

		// If there aren't more metas, we finished.
		if ( ! $current_meta ) {
			$this->finish_tracking_subscription_migration( $migrated_rows );
		}

		// Update last the stored data of the last batch.
		$migration_data['current_meta']         = $current_meta;
		$migration_data['migrated_rows']        = $migrated_rows;
		$migration_data['migrated_percentage'] += $migration_data['percentage_per_batch'];
		update_option( 'hustle_30_migration_data', $migration_data );

		$response = array(
			'migrated_percentage' => round( $migration_data['migrated_percentage'], 2 ),
			'migrated_rows'       => $migrated_rows,
			'total_entries'       => $migration_data['total_entries'],
		);

		wp_send_json_success( $response );

	}

	/**
	 * Get the 3.x metas of a module, paginated.
	 * This just retrieves tracking (views and conversions) and subscriptions.
	 *
	 * @since 4.0
	 *
	 * @param int     $current_module the last module that was processed in the previous page.
	 * @param int     $current_meta the last meta that was processed in the previous page
	 * @param boolean $wpdb
	 * @return array
	 */
	private function get_paged_metas( $modules_id, $current_meta, $limit = 10, $wpdb = false ) {

		if ( ! $wpdb ) {
			global $wpdb;
		}

		$meta_keys_placeholders = implode( ', ', array_fill( 0, count( self::$tracking_meta_keys ), '%s' ) );
		$meta_key_query         = $wpdb->prepare(
			"`meta_key` IN ({$meta_keys_placeholders})", // phpcs:ignore
			self::$tracking_meta_keys
		);

		$modules_id_placeholders = implode( ', ', array_fill( 0, count( $modules_id ), '%d' ) );
		$modules_id_query        = $wpdb->prepare(
			"`module_id` IN ({$modules_id_placeholders})", // phpcs:ignore
			$modules_id
		);

		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$query = $wpdb->prepare(
			'SELECT *
			FROM `' . $wpdb->base_prefix . "hustle_modules_meta`
			WHERE `meta_id` > %d
			AND (({$modules_id_query}
			AND {$meta_key_query})
			OR `meta_key` LIKE %s)
			ORDER BY `meta_id` ASC
			LIMIT %d",
			$current_meta,
			'%page_shares',
			$limit
		);
		// phpcs:enable

		$metas = $wpdb->get_results( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		return $metas;
	}

	private static function get_tracking_submissions_count( $modules_id, $wpdb = false ) {

		if ( ! $wpdb ) {
			global $wpdb;
		}
		$modules_id_placeholders = implode( ', ', array_fill( 0, count( $modules_id ), '%d' ) );

		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
		$modules_id_query = $wpdb->prepare(
			"`module_id` IN ({$modules_id_placeholders})",
			$modules_id
		);
		// phpcs:enable

		$meta_keys_placeholders = implode( ', ', array_fill( 0, count( self::$tracking_meta_keys ), '%s' ) );
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
		$meta_keys_query = $wpdb->prepare(
			"`meta_key` IN ({$meta_keys_placeholders})",
			self::$tracking_meta_keys
		);
		// phpcs:enable

		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$query = $wpdb->prepare(
			"SELECT COUNT(*)
			FROM `{$wpdb->base_prefix}hustle_modules_meta`
			WHERE ({$modules_id_query}
			AND {$meta_keys_query})
			OR `meta_key` LIKE %s",
			'%page_shares'
		);
		// phpcs:enable

		return $wpdb->get_var( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Store the new tracking view.
	 *
	 * @since 4.0
	 * @param object $old_view
	 * @param string $tracking_type view|conversion
	 */
	private function migrate_tracking( $old_view, $tracking_type ) {

		$old_data = json_decode( $old_view->meta_value, true );

		// Data coming from 2.x has 'optin_id' instead of 'module_id'.
		$module_id = isset( $old_data['module_id'] ) ? $old_data['module_id'] : $old_data['optin_id'];

		if ( isset( $old_data['module_type'] ) ) {
			$module_type = $old_data['module_type'];
		} else {
			// Conversions didn't store the module_type. Try to get it without making a db call.
			if ( false !== stripos( $old_view->meta_key, 'popup' ) ) {
				$module_type = Hustle_Module_Model::POPUP_MODULE;

			} elseif ( false !== stripos( $old_view->meta_key, 'slidein' ) ) {
				$module_type = Hustle_Module_Model::SLIDEIN_MODULE;

			} else {
				// It can be either an embed or ssharing module. No way to know it unless retrieving it.
				$module_type = $this->get_module_type_by_module_id( $module_id );
			}
		}
		$meta_key        = $old_view->meta_key;
		$date_created    = date_i18n( 'Y-m-d H:i:s', $old_data['date'] );
		$module_sub_type = null;

		// Define the subtype for embeds and social sharing modules.
		if ( Hustle_Module_Model::EMBEDDED_MODULE === $module_type || Hustle_Module_Model::SOCIAL_SHARING_MODULE === $module_type ) {

			// TODO: use constants here instead.
			if ( false !== stripos( $meta_key, 'shortcode' ) ) {
				$module_sub_type = 'shortcode';
			} elseif ( false !== stripos( $meta_key, 'widget' ) ) {
				$module_sub_type = 'widget';
			} elseif ( false !== stripos( $meta_key, 'after_content' ) ) {
				$module_sub_type = 'inline';
			} elseif ( false !== stripos( $meta_key, 'floating' ) ) {
				$module_sub_type = 'floating';
			}
		}

		$tracking = Hustle_Tracking_Model::get_instance();
		$tracking->save_tracking( $module_id, $tracking_type, $module_type, $old_data['page_id'], $module_sub_type, $date_created, $old_data['ip'] );

		return $old_view->meta_id;
	}

	/**
	 * Migrate 3.x subscription.
	 *
	 * @since 4.0
	 *
	 * @param object $old_subscription
	 * @return int
	 */
	private function migrate_subscription( $old_subscription ) {

		$data = json_decode( $old_subscription->meta_value, true );

		$date_created      = date_i18n( 'Y-m-d H:i:s', $data['time'] );
		$entry             = new Hustle_Entry_Model();
		$entry->entry_type = $data['module_type'];
		$entry->module_id  = $old_subscription->module_id;

		$entry->save( $date_created );

		$entry_data = array();
		foreach ( $data as $name => $value ) {
			if ( 'time' === $name ) {
				continue;
			}

			// Getting rid of legacy stuff by transforming it already.
			if ( 'l_name' === $name ) {
				$name = 'last_name';
			} elseif ( 'f_name' === $name ) {
				$name = 'first_name';
			}
			$entry_data[] = array(
				// Remove trailing underscores. Used in 3.x when the fields' name had spaces.
				'name'  => preg_replace( '/_+$/', '', $name ),
				'value' => $value,
			);
		}
		$entry->set_fields( $entry_data, $date_created );

		return $old_subscription->meta_id;
	}

	/**
	 * Migrate 3.x per page ssharing count.
	 *
	 * @since 4.0
	 *
	 * @param object $old_counter
	 * @return int
	 */
	private function migrate_sshare_page_counter( $old_counter ) {

		$page_id = $old_counter->module_id;
		$count   = $old_counter->meta_value;

		$tracking = Hustle_Tracking_Model::get_instance();
		$tracking->save_old_migrated_sshare_page_count( $page_id, $count );

		return $old_counter->meta_id;
	}

	/**
	 * Get the module_type by the module_id.
	 *
	 * @since 4.0
	 *
	 * @param int $module_id
	 * @return string
	 */
	private function get_module_type_by_module_id( $module_id ) {
		global $wpdb;

		// This should be cached as long as the query is the same in the same load.
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$module_type = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT `module_type`
			FROM  ' . Hustle_Db::modules_table() .
				' WHERE `module_id`=%d',
				$module_id
			)
		);
		// phpcs:enable

		return $module_type;
	}

	/**
	 * Helper function to check different values
	 * previously given to properties which all mean true.
	 *
	 * @param mixed $value
	 * @return boolean
	 */
	private function is_true( $value ) {
		if ( '1' === $value || 'true' === $value || 1 === $value || true === $value ) {
			return true;
		}
		return false;
	}
}