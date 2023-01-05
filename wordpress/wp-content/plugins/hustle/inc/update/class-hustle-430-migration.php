<?php
/**
 * File for Hustle_430_Migration class.
 *
 * @package Hustle
 * @since 4.3.0
 */

/**
 * Class Hustle_430_Migration.
 *
 * This class handles the migration when going from 4.x.x to 4.3.x.
 * We introduced new appearance options for which we need to migrate the
 * existing options so the module looks the same as before the upgrade.
 *
 * @since 4.3.0
 */
class Hustle_430_Migration {

	/**
	 * Flag name to mark the migration as "done".
	 *
	 * @since 4.3.0
	 */
	const MIGRATION_FLAG = 'hustle_430_migrated';

	/**
	 * Modules to migrate as an associative array.
	 * The module_id is the key, the module_mode is the value.
	 *
	 * @since 4.3.0
	 * @var array
	 */
	private $modules_to_migrate = array();

	/**
	 * Content metas of the modules to migrate in the current batch.
	 * The module ID is the key, and the content meta its value.
	 * Updated on each batch iteration.
	 *
	 * @since 4.3.0
	 * @var array
	 */
	private $content_metas = array();

	/**
	 * Current module ID during the metas iteration.
	 * Updated on each meta iteration.
	 *
	 * @var int
	 */
	private $module_id;

	/**
	 * Whether the current module is optin.
	 * Updated on each meta iteration.
	 *
	 * @var bool
	 */
	private $is_optin;

	/**
	 * Whether the colors should be switched to 'custom'.
	 * Updated on each meta iteration.
	 *
	 * @var bool
	 */
	private $switch_colors_to_custom;

	/**
	 * Hustle_401_Migration class constructor.
	 */
	public function __construct() {

		if ( $this->should_migrate() ) {
			add_action( 'init', array( $this, 'do_migration' ) );
		}
	}

	/**
	 * Checks whether we should run da migration.
	 *
	 * @since 4.1.0
	 *
	 * @return bool
	 */
	private function should_migrate() {

		// If migration is being forced, do it.
		if ( filter_input( INPUT_GET, 'run-430-migration', FILTER_VALIDATE_BOOLEAN ) ) {
			return true;
		}

		// If migration was already done, skip.
		if ( Hustle_Migration::is_migrated( self::MIGRATION_FLAG ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Does the migration from 4.x.x to 4.3.x.
	 *
	 * @since 4.3.0
	 */
	public function do_migration() {

		$this->modules_to_migrate = $this->get_all_non_ssharing_modules();

		// There are no modules to migrate. Set the flag as migrated and bail out.
		if ( empty( $this->modules_to_migrate ) ) {
			Hustle_Migration::migration_passed( self::MIGRATION_FLAG );
			return;
		}

		$limit = apply_filters( 'hustle_430_migration_limit', 100 );

		do {
			$offset = get_option( 'hustle_430_migration_offset', 0 );

			$module_ids = array_slice( array_keys( $this->modules_to_migrate ), $offset, $limit );

			$count_modules = count( $module_ids );
			$offset       += $limit;

			if ( ! empty( $count_modules ) ) {
				$batch_of_design_metas = $this->get_modules_design_meta( $module_ids );

				$this->content_metas = $this->get_modules_content_meta( $module_ids );

				$this->migrate_batch_of_metas( $batch_of_design_metas );
			}

			update_option( 'hustle_430_migration_offset', $offset );

		} while ( $count_modules === $limit );

		Hustle_Migration::migration_passed( self::MIGRATION_FLAG );
		delete_option( 'hustle_430_migration_offset' );
		delete_option( 'hustle_430_modules_to_migrate' );
	}

	/**
	 * Retrieves the ID and Mode of all modules that are not Social Sharing.
	 *
	 * @since 4.3.0
	 * @return array
	 */
	private function get_all_non_ssharing_modules() {
		$modules = get_option( 'hustle_430_modules_to_migrate', false );

		if ( ! $modules ) {
			$raw_modules = $this->fetch_non_ssharing_modules();
			$modules     = wp_list_pluck( $raw_modules, 'module_mode', 'module_id' );

			update_option( 'hustle_430_modules_to_migrate', $modules, false );
		}

		return $modules;
	}

	/**
	 * Fetch all non-ssharing modules id and mode.
	 *
	 * @since 4.3.0
	 * @return array
	 */
	private function fetch_non_ssharing_modules() {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$results = $wpdb->get_results(
			"SELECT module_id, module_mode
			FROM {$wpdb->prefix}hustle_modules
			WHERE module_type != 'social_sharing'"
		);

		return $results;
	}

	/**
	 * Gets all the stored design metas.
	 *
	 * @since 4.3.0
	 *
	 * @param array $module_ids Array with the IDs of the modules to retrieve the metas for.
	 * @return array
	 */
	private function get_modules_design_meta( $module_ids ) {
		global $wpdb;

		$results = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->prepare(
				"SELECT meta_id, module_id, meta_value
				FROM {$wpdb->prefix}hustle_modules_meta
				WHERE module_id IN (" . implode( ', ', array_fill( 0, count( $module_ids ), '%d' ) ) . ")
				AND meta_key = 'design'",
				$module_ids
			)
		);

		return $results;
	}

	/**
	 * Gets all the stored content metas.
	 *
	 * @since 4.3.0
	 *
	 * @param array $module_ids Array with the IDs of the modules to retrieve the metas for.
	 * @return array
	 */
	private function get_modules_content_meta( $module_ids ) {
		global $wpdb;

		$results = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->prepare(
				"SELECT module_id, meta_value
				FROM {$wpdb->prefix}hustle_modules_meta
				WHERE module_id IN (" . implode( ', ', array_fill( 0, count( $module_ids ), '%d' ) ) . ")
				AND meta_key = 'content'",
				$module_ids
			)
		);

		$metas = array();
		foreach ( $results as $result ) {
			$metas[ $result->module_id ] = json_decode( $result->meta_value );
		}

		return $metas;
	}

	/**
	 * Loops through the current batch of metas updating its settings in the database.
	 *
	 * @since 4.3.0
	 * @param array $design_metas Current batch of metas.
	 * @return void
	 */
	private function migrate_batch_of_metas( $design_metas ) {
		global $wpdb;

		foreach ( $design_metas as $meta ) {
			$old_design = json_decode( $meta->meta_value, true );

			// Check for an old module property. If it's not set, it means that this module was created in 4.3.0. Skip.
			if ( ! isset( $old_design['border'] ) ) {
				continue;
			}

			$this->module_id               = $meta->module_id;
			$this->is_optin                = 'optin' === $this->modules_to_migrate[ $meta->module_id ];
			$this->switch_colors_to_custom = false;

			// This should run before "migrate_colors" because "customize_colors" value depends on this.
			$new_border_spacing_shadow = $this->migrate_border_spacing_shadow( $old_design );

			$new_colors = $this->migrate_colors( $old_design );

			$new_customize_elements = $this->migrate_customize_elements( $old_design );

			$new_typography = $this->migrate_typography( $old_design );

			$new_design = $new_typography + $new_customize_elements + $new_colors + $new_border_spacing_shadow + $old_design;

			$new_design['enable_mobile_settings'] = '1';

			if ( '1' === $old_design['customize_size'] && 'all' === $old_design['apply_custom_size_to'] ) {
				$new_design['customize_size_mobile'] = '1';
				$new_design['custom_width_mobile']   = $old_design['custom_width'];
				$new_design['custom_height_mobile']  = $old_design['custom_height'];
			}

			// Save transformed conditions.
			$wpdb->update(
				Hustle_Db::modules_meta_table(),
				array( 'meta_value' => wp_json_encode( $new_design ) ), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				array( 'meta_id' => $meta->meta_id )
			);

			wp_cache_delete( $meta->module_id, 'hustle_module_meta' );
		}
	}

	/**
	 * Migrates the old settings to the new one for Advanced -> Border, Spacing, Shadow.
	 *
	 * @since 4.3.0
	 * @param array $old_design Original settings.
	 * @return array
	 */
	private function migrate_border_spacing_shadow( $old_design ) {

		$new_design = array();

		// Module Container -> Drop shadow.
		if ( '1' === $old_design['drop_shadow'] ) {
			$this->switch_colors_to_custom = true;

			$new_design['module_cont_drop_shadow_x']      = $old_design['drop_shadow_x'];
			$new_design['module_cont_drop_shadow_y']      = $old_design['drop_shadow_y'];
			$new_design['module_cont_drop_shadow_blur']   = $old_design['drop_shadow_blur'];
			$new_design['module_cont_drop_shadow_spread'] = $old_design['drop_shadow_spread'];
		}

		// Module Container -> Border.
		if ( '1' === $old_design['border'] ) {
			$this->switch_colors_to_custom = true;

			$new_design['module_cont_radius_top_left']     = $old_design['border_radius'];
			$new_design['module_cont_radius_top_right']    = $old_design['border_radius'];
			$new_design['module_cont_radius_bottom_right'] = $old_design['border_radius'];
			$new_design['module_cont_radius_bottom_left']  = $old_design['border_radius'];

			$new_design['module_cont_border_top']    = $old_design['border_weight'];
			$new_design['module_cont_border_right']  = $old_design['border_weight'];
			$new_design['module_cont_border_bottom'] = $old_design['border_weight'];
			$new_design['module_cont_border_left']   = $old_design['border_weight'];

			$new_design['module_cont_border_type']        = $old_design['border_type'];
			$new_design['module_cont_border_type_mobile'] = $old_design['border_type'];
		}

		// CTA.
		if ( 'outlined' === $old_design['cta_style'] ) {
			$new_design['cta_radius_top_left']     = $old_design['cta_border_radius'];
			$new_design['cta_radius_top_right']    = $old_design['cta_border_radius'];
			$new_design['cta_radius_bottom_right'] = $old_design['cta_border_radius'];
			$new_design['cta_radius_bottom_left']  = $old_design['cta_border_radius'];

			$new_design['cta_border_top']    = $old_design['cta_border_weight'];
			$new_design['cta_border_right']  = $old_design['cta_border_weight'];
			$new_design['cta_border_bottom'] = $old_design['cta_border_weight'];
			$new_design['cta_border_left']   = $old_design['cta_border_weight'];
		}

		// Inputs & selects.
		if ( 'outlined' === $old_design['form_fields_style'] ) {
			$new_design['input_radius_top_left']     = $old_design['form_fields_border_radius'];
			$new_design['input_radius_top_right']    = $old_design['form_fields_border_radius'];
			$new_design['input_radius_bottom_right'] = $old_design['form_fields_border_radius'];
			$new_design['input_radius_bottom_left']  = $old_design['form_fields_border_radius'];

			$new_design['input_border_top']    = $old_design['form_fields_border_weight'];
			$new_design['input_border_right']  = $old_design['form_fields_border_weight'];
			$new_design['input_border_bottom'] = $old_design['form_fields_border_weight'];
			$new_design['input_border_left']   = $old_design['form_fields_border_weight'];

			$new_design['input_border_type']        = $old_design['form_fields_border_type'];
			$new_design['input_border_type_mobile'] = $old_design['form_fields_border_type'];
		}

		// Submit button.
		if ( 'outlined' === $old_design['button_style'] ) {
			$new_design['submit_button_radius_top_left']     = $old_design['button_border_radius'];
			$new_design['submit_button_radius_top_right']    = $old_design['button_border_radius'];
			$new_design['submit_button_radius_bottom_right'] = $old_design['button_border_radius'];
			$new_design['submit_button_radius_bottom_left']  = $old_design['button_border_radius'];

			$new_design['submit_button_border_top']    = $old_design['button_border_weight'];
			$new_design['submit_button_border_right']  = $old_design['button_border_weight'];
			$new_design['submit_button_border_bottom'] = $old_design['button_border_weight'];
			$new_design['submit_button_border_left']   = $old_design['button_border_weight'];

			$new_design['submit_button_border_type']        = $old_design['button_border_type'];
			$new_design['submit_button_border_type_mobile'] = $old_design['button_border_type'];
		}

		// GDPR checkbox.
		if ( 'outlined' === $old_design['gdpr_checkbox_style'] ) {
			$new_design['gdpr_radius_top_left']     = $old_design['gdpr_border_radius'];
			$new_design['gdpr_radius_top_right']    = $old_design['gdpr_border_radius'];
			$new_design['gdpr_radius_bottom_right'] = $old_design['gdpr_border_radius'];
			$new_design['gdpr_radius_bottom_left']  = $old_design['gdpr_border_radius'];

			$new_design['gdpr_border_top']    = $old_design['gdpr_border_weight'];
			$new_design['gdpr_border_right']  = $old_design['gdpr_border_weight'];
			$new_design['gdpr_border_bottom'] = $old_design['gdpr_border_weight'];
			$new_design['gdpr_border_left']   = $old_design['gdpr_border_weight'];
		}

		// Maybe popups only?
		$new_design['popup_cont_padding_are_sides_linked_mobile'] = '0';
		$new_design['popup_cont_padding_left_mobile']             = 10;
		$new_design['popup_cont_padding_right_mobile']            = 10;

		$new_design['content_wrap_padding_top_mobile']    = 10;
		$new_design['content_wrap_padding_right_mobile']  = 10;
		$new_design['content_wrap_padding_bottom_mobile'] = 10;
		$new_design['content_wrap_padding_left_mobile']   = 10;

		// For informational modules.
		if ( ! $this->is_optin ) {
			if ( 'minimal' === $old_design['style'] ) {
				$new_design = $this->get_informational_minimal_advanced( $new_design );
			} elseif ( 'simple' === $old_design['style'] ) {
				$new_design = $this->get_informational_simple_advanced( $new_design );
			} else {
				// Cabriolet (stacked).
				$new_design = $this->get_informational_cabriolet_advanced( $new_design );
			}
		} else {
			$new_design = $this->get_optin_advanced( $new_design );
		}

		$new_design['customize_border_shadow_spacing']        = '1';
		$new_design['customize_border_shadow_spacing_mobile'] = '1';

		return $new_design;
	}

	/**
	 * Gets the advanced settings for optin modules.
	 *
	 * @since 4.3.0
	 *
	 * @param array $new_design New design for the module.
	 * @return array
	 */
	private function get_optin_advanced( $new_design ) {
		$content = $this->content_metas[ $this->module_id ];

		$new_design['form_cont_padding_top_mobile']    = 10;
		$new_design['form_cont_padding_right_mobile']  = 10;
		$new_design['form_cont_padding_bottom_mobile'] = 10;
		$new_design['form_cont_padding_left_mobile']   = 10;

		if ( empty( $content->title ) && ! empty( $content->sub_title ) ) {
			$new_design['subtitle_margin_are_sides_linked'] = '0';
			$new_design['subtitle_margin_top']              = 0;
		}

		if ( empty( $content->title ) && empty( $content->sub_title ) ) {
			if ( ! empty( $content->main_content ) ) {
				$new_design['main_content_margin_are_sides_linked'] = '0';
				$new_design['main_content_margin_top']              = 0;

				$new_design['cta_cont_margin_are_sides_linked_mobile'] = '0';
				$new_design['cta_cont_margin_top_mobile']              = 10;
			}

			if ( empty( $content->main_content ) && '1' === $content->show_cta ) {
				$new_design['cta_cont_margin_are_sides_linked'] = '0';
				$new_design['cta_cont_margin_top']              = 0;
			}
		} else {
			$new_design['main_content_margin_are_sides_linked_mobile'] = '0';
			$new_design['main_content_margin_top_mobile']              = 10;

			$new_design['cta_cont_margin_are_sides_linked_mobile'] = '0';
			$new_design['cta_cont_margin_top_mobile']              = 10;
		}

		return $new_design;
	}

	/**
	 * Retrieves the advanced settings for the Informational modules with the "minimal" (Default) layout.
	 *
	 * @since 4.3.0
	 *
	 * @param array $new_design New design for the module.
	 * @return array
	 */
	private function get_informational_minimal_advanced( $new_design ) {
		$content = $this->content_metas[ $this->module_id ];

		if ( ! empty( $content->title ) || ! empty( $content->sub_title ) ) {
			$new_design['main_content_padding_are_sides_linked'] = '0';
			$new_design['main_content_padding_top']              = 20;

			$new_design['main_content_padding_are_sides_linked_mobile'] = '0';
			$new_design['main_content_padding_top_mobile']              = 0;

			$new_design['main_content_border_are_sides_linked'] = '0';
			$new_design['main_content_border_top']              = 1;

			$new_design['layout_header_border_are_sides_linked'] = '0';
			if ( ! empty( $content->feature_image ) || ! empty( $content->main_content ) ) {
				$new_design['layout_header_border_bottom'] = 1;

			} elseif ( '0' === $content->show_cta && empty( $content->feature_image ) && empty( $content->main_content ) ) {
				$new_design['layout_header_border_bottom'] = 0;
			}

			$new_design['layout_header_padding_top_mobile']    = 10;
			$new_design['layout_header_padding_right_mobile']  = 10;
			$new_design['layout_header_padding_bottom_mobile'] = 10;
			$new_design['layout_header_padding_left_mobile']   = 10;
		}

		if ( empty( $content->title ) && ! empty( $content->sub_title ) ) {
			$new_design['subtitle_margin_top'] = 0;
		}

		if ( '1' === $content->show_cta ) {
			$new_design['layout_footer_padding_top']    = 20;
			$new_design['layout_footer_padding_right']  = 20;
			$new_design['layout_footer_padding_bottom'] = 20;
			$new_design['layout_footer_padding_left']   = 20;

			$new_design['layout_footer_padding_top_mobile']    = 10;
			$new_design['layout_footer_padding_right_mobile']  = 10;
			$new_design['layout_footer_padding_bottom_mobile'] = 10;
			$new_design['layout_footer_padding_left_mobile']   = 10;

			$new_design['cta_cont_margin_top']    = 0;
			$new_design['cta_cont_margin_right']  = 0;
			$new_design['cta_cont_margin_bottom'] = 0;
			$new_design['cta_cont_margin_left']   = 0;
		}

		return $new_design;
	}

	/**
	 * Retrieves the advanced settings for the Informational modules with the "simple" (Compact) layout.
	 *
	 * @since 4.3.0
	 *
	 * @param array $new_design New design for the module.
	 * @return array
	 */
	private function get_informational_simple_advanced( $new_design ) {
		$content = $this->content_metas[ $this->module_id ];

		$new_design['cta_cont_margin_are_sides_linked'] = '0';
		$new_design['cta_cont_margin_top']              = 0;

		// #1.
		if (
			( ! empty( $content->title ) && empty( $content->sub_title ) ) &&
			( '1' === $content->show_cta || ! empty( $content->main_content ) )
		) {
			$this->switch_colors_to_custom = true;

			$new_design['title_border_are_sides_linked'] = '0';
			$new_design['title_border_bottom']           = 1;

			$new_design['title_padding_are_sides_linked'] = '0';
			$new_design['title_padding_bottom']           = 20;

			$new_design['title_padding_are_sides_linked_mobile'] = '0';
			$new_design['title_padding_bottom_mobile']           = 10;
		}

		// #2.
		if (
			! empty( $content->sub_title ) &&
			( '1' === $content->show_cta || ! empty( $content->main_content ) )
		) {
			$this->switch_colors_to_custom = true;

			$new_design['subtitle_border_are_sides_linked'] = '0';
			$new_design['subtitle_border_bottom']           = 1;

			$new_design['subtitle_padding_are_sides_linked'] = '0';
			$new_design['subtitle_padding_bottom']           = 20;
		}

		// #3.
		if ( empty( $content->title ) && ! empty( $content->sub_title ) ) {
			$new_design['subtitle_margin_are_sides_linked'] = '0';
			$new_design['subtitle_margin_top']              = 0;
		}

		// #4.
		if ( ! empty( $content->title ) || ! empty( $content->sub_title ) ) {
			$this->switch_colors_to_custom = true;

			$new_design['main_content_border_are_sides_linked'] = '0';
			$new_design['main_content_border_top']              = 1;

			$new_design['main_content_padding_are_sides_linked'] = '0';
			$new_design['main_content_padding_top']              = 20;
		}

		// #5.
		if ( '1' === $content->show_cta ) {
			$this->switch_colors_to_custom = true;

			$new_design['main_content_border_are_sides_linked'] = '0';
			$new_design['main_content_border_bottom']           = 1;

			$new_design['main_content_padding_are_sides_linked'] = '0';
			$new_design['main_content_padding_bottom']           = 20;
		}

		// #7.
		if ( ! empty( $content->title ) || ! empty( $content->sub_title ) || ! empty( $content->main_content ) ) {
			$this->switch_colors_to_custom = true;

			$new_design['cta_cont_border_are_sides_linked'] = '0';
			$new_design['cta_cont_border_top']              = 1;

			// #8.
			$new_design['cta_cont_padding_are_sides_linked'] = '0';
			$new_design['cta_cont_padding_top']              = 20;

			$new_design['cta_cont_padding_are_sides_linked_mobile'] = '0';
			$new_design['cta_cont_padding_top_mobile']              = 10;
		}

		return $new_design;
	}

	/**
	 * Retrieves the advanced settings for the Informational modules with the "cabriolet" (Stacked) layout.
	 *
	 * @since 4.3.0
	 *
	 * @param array $new_design New design for the module.
	 * @return array
	 */
	private function get_informational_cabriolet_advanced( $new_design ) {
		$content = $this->content_metas[ $this->module_id ];

		$new_design['layout_header_padding_are_sides_linked'] = '0';
		$new_design['layout_header_padding_left']             = 0;
		$new_design['layout_header_padding_top']              = 0;

		$new_design['layout_header_border_are_sides_linked'] = '0';
		$new_design['layout_header_border_bottom']           = 0;

		$new_design['title_padding_are_sides_linked'] = '0';
		$new_design['title_padding_right']            = 30;

		$new_design['subtitle_padding_are_sides_linked'] = '0';
		$new_design['subtitle_padding_right']            = 30;

		if ( ! empty( $content->title ) || ! empty( $content->sub_title ) ) {
			if ( empty( $content->main_content ) && empty( $content->feature_image ) && '0' === $content->show_cta ) {
				$new_design['layout_header_padding_bottom'] = 0;
			} else {
				$new_design['layout_header_padding_are_sides_linked_mobile'] = '0';
				$new_design['layout_header_padding_bottom_mobile']           = 10;
			}
		}

		if ( '1' === $content->show_cta ) {
			$new_design['cta_cont_margin_are_sides_linked'] = '0';
			$new_design['cta_cont_margin_top']              = 0;

			if ( ! empty( $content->main_content ) ) {
				$this->switch_colors_to_custom = true;

				$new_design['main_content_padding_are_sides_linked'] = '0';
				$new_design['main_content_padding_bottom']           = 20;
				$new_design['main_content_border_bottom']            = 1;

				$new_design['main_content_padding_are_sides_linked_mobile'] = '0';
				$new_design['main_content_padding_bottom_mobile']           = 10;

				$new_design['cta_cont_padding_are_sides_linked'] = '0';
				$new_design['cta_cont_padding_top']              = 20;

				$new_design['cta_cont_border_are_sides_linked'] = '0';
				$new_design['cta_cont_border_top']              = 1;

				$new_design['cta_cont_padding_are_sides_linked_mobile'] = '0';
				$new_design['cta_cont_padding_top_mobile']              = 10;
			}
		}

		return $new_design;
	}

	/**
	 * Migrates colors.
	 *
	 * @since 4.3.0
	 * @param array $old_design Original settings.
	 * @return array
	 */
	private function migrate_colors( $old_design ) {
		$new_design = array();

		// For informational modules.
		if ( ! $this->is_optin ) {
			if ( 'simple' === $old_design['style'] ) {
				$new_design = $this->get_informational_simple_colors( $new_design );
			} elseif ( 'cabriolet' === $old_design['style'] ) {
				$new_design = $this->get_informational_cabriolet_colors( $new_design );
			}
		}

		if ( '0' === $old_design['customize_colors'] && $this->switch_colors_to_custom ) {
			$new_design = array_merge(
				Hustle_Palettes_Helper::get_palette_array( $old_design['color_palette'], $this->is_optin ),
				$new_design
			);

			$new_design['customize_colors'] = '1';
		}

		$new_design['module_cont_border']      = $old_design['border_color'];
		$new_design['module_cont_drop_shadow'] = $old_design['drop_shadow_color'];

		return $new_design;
	}

	/**
	 * Retrieves the colors for the Informational modules with the "simple" (Compact) layout.
	 *
	 * @since 4.3.0
	 *
	 * @param array $new_design New design for the module.
	 * @return array
	 */
	private function get_informational_simple_colors( $new_design ) {
		$content = $this->content_metas[ $this->module_id ];

		// #1.
		if (
			( ! empty( $content->title ) && empty( $content->sub_title ) ) &&
			( '1' === $content->show_cta || ! empty( $content->main_content ) )
		) {
			$new_design['title_border'] = 'rgba(0,0,0,0.16)';
		}

		// #2.
		if (
			! empty( $content->sub_title ) &&
			( '1' === $content->show_cta || ! empty( $content->main_content ) )
		) {
			$new_design['subtitle_border'] = 'rgba(0,0,0,0.16)';
		}

		// #4.
		if ( ! empty( $content->title ) && ! empty( $content->sub_title ) ) {
			$new_design['content_border'] = 'rgba(255,255,255,0.08)';
		}

		// #5.
		if ( '1' === $content->show_cta ) {
			$new_design['content_border'] = 'rgba(255,255,255,0.08)';
		}

		// #6.
		if ( empty( $content->title ) && empty( $content->sub_title ) && '1' === $content->show_cta ) {
			$new_design['content_border'] = 'rgba(0,0,0,0.16)';
		}

		// #7.
		if ( ! empty( $content->title ) || ! empty( $content->sub_title ) ) {
			if ( ! empty( $content->main_content ) ) {
				$new_design['cta_cont_border'] = 'rgba(0,0,0,0.16)';
			} else {
				$new_design['cta_cont_border'] = 'rgba(255,255,255,0.08)';
			}
		}

		return $new_design;
	}

	/**
	 * Retrieves the colors for the Informational modules with the "cabriolet" (Stacked) layout.
	 *
	 * @since 4.3.0
	 *
	 * @param array $new_design New design for the module.
	 * @return array
	 */
	private function get_informational_cabriolet_colors( $new_design ) {
		$content = $this->content_metas[ $this->module_id ];

		if ( ! empty( $content->main_content ) && '1' === $content->show_cta ) {
			$new_design['content_border']  = 'rgba(0,0,0,0.16)';
			$new_design['cta_cont_border'] = 'rgba(255,255,255,0.08)';
		}

		return $new_design;
	}

	/**
	 * Migrates Customize Elements.
	 *
	 * @since 4.3.0
	 * @param array $old_design Original settings.
	 * @return array
	 */
	private function migrate_customize_elements( $old_design ) {
		$new_design = array();

		$new_design['feature_image_fit_mobile'] = $old_design['feature_image_fit'];

		$new_design['feature_image_horizontal_position']        = $old_design['feature_image_horizontal'];
		$new_design['feature_image_horizontal_position_mobile'] = $old_design['feature_image_horizontal'];

		$new_design['feature_image_vertical_position']        = $old_design['feature_image_vertical'];
		$new_design['feature_image_vertical_position_mobile'] = $old_design['feature_image_vertical'];

		$new_design['feature_image_horizontal_value']        = $old_design['feature_image_horizontal_px'];
		$new_design['feature_image_horizontal_value_mobile'] = $old_design['feature_image_horizontal_px'];

		$new_design['feature_image_vertical_value']        = $old_design['feature_image_vertical_px'];
		$new_design['feature_image_vertical_value_mobile'] = $old_design['feature_image_vertical_px'];

		if ( 'joined' === $old_design['form_fields_proximity'] ) {
			$new_design['customize_form_fields_proximity']        = '0';
			$new_design['customize_form_fields_proximity_mobile'] = '0';
		} else {
			$new_design['customize_form_fields_proximity']        = '1';
			$new_design['customize_form_fields_proximity_mobile'] = '1';

			$new_design['form_fields_proximity_value']        = 10;
			$new_design['form_fields_proximity_value_mobile'] = 10;
		}

		// For informational modules.
		if ( ! $this->is_optin ) {
			if ( 'minimal' === $old_design['style'] ) {
				$new_design = $this->get_informational_minimal_customize( $new_design );
			}
		} else {
			$new_design['cta_buttons_alignment_mobile'] = 'left';

			if ( 'one' === $old_design['form_layout'] || 'two' === $old_design['form_layout'] ) {
				$new_design['optin_form_layout'] = 'inline';
			} else {
				$new_design['optin_form_layout'] = 'stacked';
			}
		}

		return $new_design;
	}

	/**
	 * Retrieves the customize elements for the Informational modules with the "minimal" (Default) layout.
	 *
	 * @since 4.3.0
	 *
	 * @param array $new_design New design for the module.
	 * @return array
	 */
	private function get_informational_minimal_customize( $new_design ) {
		$content = $this->content_metas[ $this->module_id ];

		if ( '1' === $content->show_cta ) {
			$new_design['cta_buttons_alignment']        = 'right';
			$new_design['cta_buttons_alignment_mobile'] = 'left';
		}

		if (
			! empty( $content->feature_image ) &&
			empty( $content->title ) && empty( $content->sub_title ) &&
			empty( $content->main_content ) && '0' === $content->show_cta
		) {
			$new_design['feature_image_height'] = 320;
		}

		return $new_design;
	}

	/**
	 * Migrates Typography.
	 *
	 * @since 4.3.0
	 * @param array $old_design Original settings.
	 * @return array
	 */
	private function migrate_typography( $old_design ) {
		$new_design = array();

		if ( ! $this->is_optin ) {
			$new_design['title_font_family']        = 'custom';
			$new_design['title_font_family_custom'] = 'Georgia';
			$new_design['title_font_size']          = 33;
			$new_design['title_font_weight']        = 400;
			$new_design['title_line_height']        = 38;
			$new_design['subtitle_font_weight']     = 'bold';
			$new_design['subtitle_line_height']     = 24;

		}

		return $new_design;
	}
}