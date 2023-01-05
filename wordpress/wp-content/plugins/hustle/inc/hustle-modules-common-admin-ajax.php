<?php
if ( ! class_exists( 'Hustle_Modules_Common_Admin_Ajax' ) ) :
	/**
	 * Class Hustle_Modules_Common_Admin_Ajax.
	 * Intended for Pop-up, Slide-in, Embeds, and Social sharing modules common ajax actions on admin side.
	 *
	 * @since 4.0
	 */
	class Hustle_Modules_Common_Admin_Ajax {

		private $module_model_instance;

		public function __construct() {
			add_action( 'wp_ajax_hustle_save_module', array( $this, 'save_module' ) );

			add_action( 'wp_ajax_hustle_fetch_font_families', array( $this, 'fetch_font_families' ) );

			add_action( 'wp_ajax_hustle_create_new_module', array( $this, 'create_new_module' ) );
			add_action( 'wp_ajax_hustle_preview_module', array( $this, 'preview_module' ) );
			add_action( 'wp_ajax_hustle_tracking_data', array( $this, 'get_tracking_data' ) );

			// Handle bulk actions.
			add_action( 'wp_ajax_hustle_listing_bulk', array( $this, 'handle_bulk_action' ) );

			// Handle the actions for a single module.
			add_action( 'wp_ajax_hustle_module_handle_single_action', array( $this, 'handle_single_action' ) );

			// Used for Gutenberg.
			add_action( 'wp_ajax_hustle_render_module', array( $this, 'render_module' ) );
			add_action( 'wp_ajax_hustle_get_module_id_by_shortcode', array( $this, 'get_module_id_by_shortcode' ) );

			// Ajax search for posts/pages/categories/tags visibility conditions.
			add_action( 'wp_ajax_get_new_condition_ids', array( $this, 'get_new_condition_ids' ) );
		}

		/**
		 * Saves new optin to db
		 *
		 * @since 1.0
		 */
		public function save_module() {

			Opt_In_Utils::validate_ajax_call( 'hustle_save_module_wizard' );

			$module_id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
			Opt_In_Utils::is_user_allowed_ajax( 'hustle_edit_module', $module_id );

			$_post = stripslashes_deep( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

			$error_array = array();

			try {
				if ( empty( $module_id ) ) {
					throw new Exception();
				}

				$module = Hustle_Module_Collection::instance()->return_model_from_id( $module_id );
				if ( is_wp_error( $module ) ) {
					throw new Exception();
				}

				$_post      = $module->sanitize_module( $_post );
				$validation = $module->validate_module( $_post );
				if ( true !== $validation ) {
					$error_array = array( 'data' => $validation['error'] );
					throw new Exception();
				}

				$updated = $module->update_module( $_post );

				if ( isset( $updated['success'] ) && false === $updated['success'] ) {
					$error_array = array( 'data' => $updated['error'] );
					throw new Exception();
				}

				if ( false === $updated ) {
					$error_array = array( 'data' => $updated );
					throw new Exception();
				}
			} catch ( Exception $e ) {
				wp_send_json_error( $error_array );
			}

			wp_send_json_success();
		}

		/**
		 * Gets custom font families for the Appearance settings in wizard.
		 *
		 * @since 4.3.0
		 * @return void
		 */
		public function fetch_font_families() {
			Opt_In_Utils::validate_ajax_call( 'hustle_fetch_font_families' );

			$families = Hustle_Meta_Base_Design::get_font_families_names();

			wp_send_json_success( $families );
		}

		/**
		 * Create a new module of any type
		 *
		 * @since 4.0.0
		 * @throws Exception When the module could not be created.
		 */
		public function create_new_module() {
			Opt_In_Utils::validate_ajax_call( 'hustle_create_new_module' );
			Opt_In_Utils::is_user_allowed_ajax( 'hustle_create' );

			$error_array = array( 'message' => __( 'Something went wrong while creating your pop-up. Please try again.', 'hustle' ) );

			try {
				$module_type = filter_input( INPUT_POST, 'module_type', FILTER_SANITIZE_STRING );
				$module_name = filter_input( INPUT_POST, 'module_name', FILTER_SANITIZE_STRING );

				if ( empty( $module_type ) || empty( $module_name ) ) {
					$error_array['message'] = __( 'The module name or module type is missing. Please make sure you added a name for the module.' );
					throw new Exception();
				}

				// Check we're not passing the free limits.
				if ( Hustle_Data::was_free_limit_reached( $module_type ) ) {
					$url_args = array(
						Hustle_Module_Admin::UPGRADE_MODAL_PARAM => 'true',
						'page' => Hustle_Data::get_listing_page_by_module_type( $module_type ),
					);

					$error_url   = add_query_arg( $url_args, 'admin.php' );
					$error_array = array( 'redirect_url' => $error_url );
					throw new Exception();
				}

				// All good so far, let's create the module.
				$module_data = array(
					'module_name' => $module_name,
					'module_type' => $module_type,
				);

				if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $module_type ) {
					$module_mode = filter_input( INPUT_POST, 'module_mode', FILTER_SANITIZE_STRING );
					$module_mode = Hustle_Module_Model::OPTIN_MODE === $module_mode ? Hustle_Module_Model::OPTIN_MODE : Hustle_Module_Model::INFORMATIONAL_MODE;

					$module_data['module_mode'] = $module_mode;

					$template = filter_input( INPUT_POST, 'module_template', FILTER_SANITIZE_STRING );

					$templates_helper = new Hustle_Templates_Helper();
					$module_data     += $templates_helper->get_template( $template, $module_mode );

					$module_model = new Hustle_Module_Model();
				} else {
					$module_model = new Hustle_SShare_Model();
				}

				$module_id = $module_model->create_new( $module_data );
				if ( ! $module_id ) {
					throw new Exception();
				}
			} catch ( Exception $e ) {
				wp_send_json_error( $error_array );
			}

			$success_url_args = array(
				'page' => Hustle_Data::get_wizard_page_by_module_type( $module_type ),
				'id'   => $module_id,
				'new'  => 'true',
			);
			$success_url      = add_query_arg( $success_url_args, 'admin.php' );

			wp_send_json_success( array( 'redirect_url' => $success_url ) );
		}

		/**
		 * Loads the requested module via AJAX.
		 *
		 * @since 4.0.0
		 */
		public function preview_module() {

			// TODO: check nonce.
			Hustle_Renderer_Abstract::ajax_load_module();
			wp_send_json_error( __( 'Invalid module type', 'hustle' ) );
		}

		/**
		 * Handle getting tracking data from listing
		 */
		public function get_tracking_data() {
			$id = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT );
			Opt_In_Utils::validate_ajax_call( 'module_get_tracking_data' . $id );

			$data = Hustle_Module_Page_Abstract::get_tracking_charts_markup( $id );

			wp_send_json_success( $data );
		}

		/**
		 * Handle all ajax requests related to single module's actions.
		 * -Toggle status ( publish|draft )
		 * -Duplicate
		 * -Import ( both into a new or an existing module )
		 * -Delete
		 * -Toggle tracking ( enabled|disabled )
		 * -Reset tracking
		 *
		 * @since 4.0.3
		 */
		public function handle_single_action() {

			Opt_In_Utils::validate_ajax_call( 'hustle_single_action' );

			$module_id = filter_input( INPUT_POST, 'moduleId', FILTER_SANITIZE_STRING );
			$module    = Hustle_Model::get_module( $module_id );
			$action    = filter_input( INPUT_POST, 'hustleAction', FILTER_SANITIZE_STRING );

			try {

				// Only move forward with an invalid $module when we're importing a new one.
				if ( is_wp_error( $module ) && 'import' !== $action ) {
					throw new Exception( __( 'Invalid module.', 'hustle' ) );
				}

				$context = filter_input( INPUT_POST, 'context', FILTER_SANITIZE_STRING );

				switch ( $action ) {
					case 'toggle-status':
						$this->action_toggle_module_status( $module );
						break;

					case 'clone':
						$this->action_duplicate_module( $module, $context );
						break;

					case 'import':
						$this->action_import_module( $module );
						break;

					case 'delete':
						$this->action_delete_module( $module, $context );
						break;

					case 'toggle-tracking':
						$this->action_toggle_tracking( $module );
						break;

					case 'reset-tracking':
						$this->action_reset_tracking( $module, $context );
						break;

					default:
						throw new Exception( __( 'Invalid action.', 'hustle' ) );
				}
			} catch ( Exception $e ) {

				$message = 'invalid_permissions' !== $e->getMessage() ? $e->getMessage() : __( "You don't have enough permission to do this.", 'hustle' );

				wp_send_json_error(
					array(
						'notification' => array(
							'status'  => 'error',
							'message' => $message,
						),
					)
				);
			}
		}

		/**
		 * Handle the "Publish/Draft" module action.
		 *
		 * @since 4.0.3
		 *
		 * @param Hustle_Model $module Hustle_Module_Model or Hustle_SShare_Model.
		 * @throws Exception
		 */
		private function action_toggle_module_status( Hustle_Model $module ) {

			if ( ! Opt_In_Utils::is_user_allowed( 'hustle_edit_module', $module->module_id ) ) {
				throw new Exception( 'invalid_permissions' );
			}

			$was_published = '1' === $module->active;

			if ( $was_published ) {
				$module->deactivate();
			} else {
				$module->activate();
			}

			wp_send_json_success(
				array(
					'callback'            => 'actionToggleStatus',
					'is_active'           => ! $was_published,
					'is_tracking_enabled' => ! empty( $module->get_tracking_types() ),
				)
			);
		}

		/**
		 * Handle the "duplicate" module action.
		 *
		 * @since 4.0.3
		 *
		 * @param Hustle_Model $module Hustle_Module_Model or Hustle_SShare_Model.
		 * @param string       $context dashboard|listing|edit_page.
		 * @throws Exception
		 */
		private function action_duplicate_module( Hustle_Model $module, $context ) {

			if ( ! current_user_can( 'hustle_create' ) ) {
				throw new Exception( 'invalid_permissions' );
			}

			$module->duplicate_module();

			if ( 'edit_page' !== $context ) {
				$url_params = array(
					'page'        => Hustle_Data::get_listing_page_by_module_type( $module->module_type ),
					'show-notice' => 'success',
					'notice'      => 'module_duplicated',
				);

				$args = array( 'url' => add_query_arg( $url_params, 'admin.php' ) );
			} else {
				$args = array(
					'notification' => array(
						'status'  => 'success',
						'message' => esc_html__( 'Module successfully duplicated.', 'hustle' ),
					),
				);
			}

			wp_send_json_success( $args );
		}

		/**
		 * Handle the "import" module action.
		 * This is used for importing both into a new and an existing module.
		 *
		 * @since 4.0.0
		 * @since 4.0.3 $module param added. Callback for handle_single_action() instead of the ajax request.
		 *
		 * @param Hustle_Module_Model|WP_Error $module WP_Error in case the import is creating a new module. Hustle_Module_Model otherwise.
		 * @throws Exception This is caught by the parent function.
		 */
		private function action_import_module( $module ) {

			$is_new_module = is_wp_error( $module );

			if ( ! $is_new_module && ! current_user_can( 'hustle_create' ) ) {
				throw new Exception( 'invalid_permissions' );
			}

			$module_type = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING );

			if ( ! $module_type || ! in_array( $module_type, Hustle_Data::get_module_types(), true ) ) {
				throw new Exception( __( "The module's type is not valid.", 'hustle' ) );
			}

			// Send error if the user can't create new modules but is importing a new one.
			if ( $is_new_module && Hustle_Data::was_free_limit_reached( $module_type ) ) {

				$url = add_query_arg(
					array(
						'page' => Hustle_Data::get_listing_page_by_module_type( $module_type ),
						Hustle_Module_Admin::UPGRADE_MODAL_PARAM => 'true',
					),
					'admin.php'
				);

				wp_send_json_error( array( 'redirect_url' => $url ) );
			}

			try {
				// Get the file containing the module data.
				$file = isset( $_FILES['import_file'] ) ? $_FILES['import_file'] : false;

				if ( ! $file ) {
					throw new Exception( __( 'The file is required', 'hustle' ) );

				} elseif ( ! empty( $file['error'] ) ) {
					throw new Exception( sprintf( __( 'Error: %s', 'hustle' ), esc_html( $file['error'] ) ) );
				}

				// Get the file's content.
				$overrides = array(
					'test_form' => false,
					'test_type' => false,
				);

				$wp_file = wp_handle_upload( $file, $overrides );
				if ( empty( $wp_file['file'] ) ) {
					throw new Exception( __( "The file can't be empty", 'hustle' ) );
				}

				$filename     = $wp_file['file'];
				$file_content = file_get_contents( $filename ); // phpcs:ignore WordPress.WP.AlternativeFunctions

				// Import file if it's json format
				$data = array();
				if ( strpos( $filename, '.json' ) || strpos( $filename, '.JSON' ) ) {
					$data = json_decode( $file_content, true );

				} else {
					throw new Exception( __( 'The file must be a JSON string.', 'hustle' ) );
				}

				// Make sure the file to import comes from 4.0 or greater.
				if ( ! isset( $data['plugin'] ) || ! isset( $data['plugin']['version'] ) ) {
					throw new Exception( __( 'The file is either invalid or doesn\'t have any configurations. Please check your file or upload another file.', 'hustle' ) );

				}

				// Prevent importing ssharing into other module types, and vice versa.
				if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE === $module_type ) {
					if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $data['data']['module_type'] ) {
						throw new Exception( __( "You can't import modules of other than \"Social Sharing\" type into Social Sharing modules.", 'hustle' ) );
					}
				} else {
					if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE === $data['data']['module_type'] ) {
						throw new Exception( __( "You can't import Social Sharing modules into other module types.", 'hustle' ) );
					}
				}

				$source_mode = $data['data']['module_mode'];
				$target_mode = $is_new_module ? filter_input( INPUT_POST, 'module_mode', FILTER_SANITIZE_STRING ) : $module->module_mode;

				if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $module_type ) {

					// If the mode is changing, adjust the title and subtitle colors to remain the same in front.
					if ( 'default' === $target_mode ) {
						$target_mode = $source_mode;

					} elseif ( $target_mode !== $source_mode ) {

						$is_optin = Hustle_Module_Model::OPTIN_MODE === $target_mode;

						if ( $is_optin ) {
							$data['meta']['design']['title_color']    = $data['meta']['design']['title_color_alt'];
							$data['meta']['design']['subtitle_color'] = $data['meta']['design']['subtitle_color_alt'];
						} else {
							$data['meta']['design']['title_color_alt']    = $data['meta']['design']['title_color'];
							$data['meta']['design']['subtitle_color_alt'] = $data['meta']['design']['subtitle_color'];
						}
					}
				}

				// Import a new module.
				if ( $is_new_module ) {
					$this->import_new_module( $module_type, $target_mode, $data );

				} else {
					// Import the settings into an existing module.
					$this->import_into_existing_module( $module, $module_type, $data );
				}
			} catch ( Exception $e ) {
				wp_send_json_error(
					array(
						'callback' => 'actionImportDisplayError',
						'message'  => $e->getMessage(),
					)
				);
			}

		}

		/**
		 * Handle the "delete" module action.
		 *
		 * @since 4.0.3
		 *
		 * @param Hustle_Module_Model $module
		 * @param string              $context 'dashboard'|'listing'
		 * @throws Exception
		 */
		private function action_delete_module( $module, $context ) {

			if ( ! current_user_can( 'hustle_create' ) ) {
				throw new Exception( 'invalid_permissions' );
			}

			$module->delete();

			$page = 'dashboard' !== $context ? Hustle_Data::get_listing_page_by_module_type( $module->module_type ) : Hustle_Data::ADMIN_PAGE;

				$url_params = array(
					'page'        => $page,
					'show-notice' => 'success',
					'notice'      => 'module_deleted',
				);

			wp_send_json_success( array( 'url' => add_query_arg( $url_params, 'admin.php' ) ) );
		}

		/**
		 * Handle the "toggle tracking" module action.
		 *
		 * @since 4.0.3
		 *
		 * @param Hustle_Model $module Hustle_Module_Model or Hustle_SShare_Model.
		 * @throws Exception
		 */
		private function action_toggle_tracking( Hustle_Model $module ) {

			if ( ! Opt_In_Utils::is_user_allowed( 'hustle_edit_module', $module->module_id ) ) {
				throw new Exception( 'invalid_permissions' );
			}

			$was_tracking_enabled = false;
			$message              = '';
			$is_embed_or_sshare   = Hustle_Module_Model::EMBEDDED_MODULE === $module->module_type || Hustle_Module_Model::SOCIAL_SHARING_MODULE === $module->module_type;

			$response = array(
				'is_embed_or_sshare' => $is_embed_or_sshare,
				'is_active'          => '1' === $module->active,
				'callback'           => 'actionToggleTracking',
			);

			if ( ! $is_embed_or_sshare ) {
				// Popups and slideins.

				$was_tracking_enabled = $module->is_track_type_active( $module->module_type );

				if ( $was_tracking_enabled ) {
					$module->disable_type_track_mode( $module->module_type );

				} else {
					$module->enable_type_track_mode( $module->module_type );
				}

				$message = $was_tracking_enabled ?
					sprintf( esc_html__( 'Tracking is disabled on %s', 'hustle' ), '<strong>' . esc_html( $module->module_name ) . '</strong>' ) :
					sprintf( esc_html__( 'Tracking is enabled on %s', 'hustle' ), '<strong>' . esc_html( $module->module_name ) . '</strong>' );

				$response['was_enabled'] = $was_tracking_enabled;

			} else {
				// Embeds and social sharing.
				$enabled_track_types = filter_input( INPUT_POST, 'tracking_sub_types', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
				if ( is_null( $enabled_track_types ) ) {
					$enabled_track_types = array();
				}

				$module->update_submitted_tracking_types( $enabled_track_types );

				$message = sprintf( esc_html__( 'Tracking updated on %s', 'hustle' ), '<strong>' . esc_html( $module->module_name ) . '</strong>' );

				$response['enabled_types'] = implode( ',', $enabled_track_types );
			}

			$response['message'] = $message;

			wp_send_json_success( $response );
		}

		/**
		 * Handle the "Reset tracking" module action.
		 *
		 * @since 4.0.3
		 *
		 * @param Hustle_Model $module Hustle_Module_Model or Hustle_SShare_Model.
		 * @param string       $context dashboard|listing|edit_page.
		 * @throws Exception
		 */
		private function action_reset_tracking( Hustle_Model $module, $context ) {

			if ( ! Opt_In_Utils::is_user_allowed( 'hustle_edit_module', $module->module_id ) ) {
				throw new Exception( 'invalid_permissions' );
			}

			$tracking = Hustle_Tracking_Model::get_instance();
			$tracking->delete_data( $module->module_id );

			if ( 'edit_page' !== $context ) {
				$url_params = array(
					'page'        => Hustle_Data::get_listing_page_by_module_type( $module->module_type ),
					'show-notice' => 'success',
					'notice'      => 'module_tracking_reset',
				);

				$args = array( 'url' => add_query_arg( $url_params, 'admin.php' ) );
			} else {
				$args = array(
					'notification' => array(
						'status'  => 'success',
						'message' => esc_html__( "Module's tracking data successfully reset.", 'hustle' ),
					),
				);
			}

			wp_send_json_success( $args );
		}


		/**
		 * Parse the imported data before saving.
		 *
		 * @since 4.0.3
		 *
		 * @param array  $data Incoming module's data.
		 * @param string $module_mode Target module's mode.
		 * @return array
		 *
		 * @throws Exception
		 */
		private function apply_import_filters( $data, $module_mode ) {

			// Ssharing modules don't have mode.
			if ( ! empty( $module_mode ) ) {
				$module_mode = Hustle_Module_Model::INFORMATIONAL_MODE === $module_mode ? 'info' : Hustle_Module_Model::OPTIN_MODE;

			} else {
				$module_mode = 'ssharing';
			}

			$metas = filter_input( INPUT_POST, $module_mode . '_metas', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );

			if ( empty( $metas ) ) {
				throw new Exception( __( 'Please select the settings to import.', 'hustle' ) );
			}

			// No filter was applied. Import everything.
			if ( in_array( 'all', $metas, true ) ) {
				return $data;
			}

			// Remove the metas that the user chose not to import.
			foreach ( $data['meta'] as $name => $value ) {
				if ( ! in_array( $name, $metas, true ) ) {
					unset( $data['meta'][ $name ] );
				}
			}

			return $data;
		}

		/**
		 * Import the settings into an existing module.
		 *
		 * @since 4.0.0
		 *
		 * @param Hustle_Module_Model $module
		 * @param string              $module_type
		 * @param array               $data
		 *
		 * @throws Exception
		 */
		private function import_into_existing_module( $module, $module_type, $data ) {

			// Check if the current user can do this.
			$is_allowed = Opt_In_Utils::is_user_allowed( 'hustle_edit_module', $module->id );
			if ( ! $is_allowed ) {
				throw new Exception( sprintf( __( 'Access denied. You do not have permission to perform this action.', 'hustle' ) ) );
			}

			$data = $this->apply_import_filters( $data, $module->module_mode );

			// Import the module's metas.
			$current_data = $module->get_module_metas_as_array();
			foreach ( $current_data as $meta_key => $current_value ) {

				if ( isset( $data['meta'][ $meta_key ] ) ) {

					if ( 'visibility' === $meta_key ) {
						$incoming_value = $data['meta'][ $meta_key ];
					} else {
						// Filter the incoming array to get rid of what doesn't belong to this module type.
						$incoming_value = array_intersect_key( $data['meta'][ $meta_key ], $current_value );
					}

					$meta_to_update = array_merge( $current_value, $incoming_value );
					$module->update_meta( $meta_key, $meta_to_update );
				}
			}

			$module->save();
			$module->clean_module_cache();

			$url = add_query_arg(
				array(
					'page'        => Hustle_Data::get_listing_page_by_module_type( $module_type ),
					'show-notice' => 'success',
					'notice'      => 'module_imported',
				),
				'admin.php'
			);

			wp_send_json_success( array( 'url' => $url ) );
		}

		/**
		 * Import the settings into a new module.
		 *
		 * @since 4.0
		 *
		 * @param string $module_type
		 * @param string $target_mode
		 * @param array  $data
		 *
		 * @throws Exception
		 */
		private function import_new_module( $module_type, $target_mode, $data ) {
			Opt_In_Utils::is_user_allowed_ajax( 'hustle_create' );

			if ( 'default' !== $target_mode ) {
				$data = $this->apply_import_filters( $data, $target_mode );
			}

			$data['data']['module_type'] = $module_type;

			// Set the module mode according to the selected settings.
			$data['data']['module_mode'] = Hustle_Module_Model::INFORMATIONAL_MODE === $target_mode ? $target_mode : Hustle_Module_Model::OPTIN_MODE;

			$module_data = array_merge( $data['data'], $data['meta'] );

			if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $module_type ) {
				$module_model = new Hustle_Module_Model();
			} else {
				$module_model = new Hustle_SShare_Model();
			}

			$module_id = $module_model->create_new( $module_data );

			if ( ! empty( $module_id ) ) {
				$module = new Hustle_Module_Model( $module_id );
				$module->clean_module_cache();

				$url = add_query_arg(
					array(
						'page'        => Hustle_Data::get_listing_page_by_module_type( $module_type ),
						'show-notice' => 'success',
						'notice'      => 'module_imported',
					),
					'admin.php'
				);

				wp_send_json_success( array( 'url' => $url ) );
			}

			throw new Exception( __( 'Creating a new module went wrong', 'hustle' ) );
		}

		/**
		 * Handle bulk action from listings
		 */
		public function handle_bulk_action() {
			Opt_In_Utils::validate_ajax_call( 'hustle-bulk-action' );
			$type   = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING );
			$hustle = filter_input( INPUT_POST, 'hustle', FILTER_SANITIZE_STRING );
			$ids    = isset( $_POST['ids'] ) ? $_POST['ids'] : array(); // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
			if ( ! is_array( $ids ) || empty( $ids ) ) {
				wp_send_json_error( __( 'Failed', 'hustle' ) );
			}

			foreach ( $ids as $id ) {
				$id     = intval( $id );
				$module = Hustle_Module_Collection::instance()->return_model_from_id( $id );
				if ( is_wp_error( $module ) ) {
					continue;
				}
				if ( $module->module_type !== $type && in_array( $type, array( 'popup', 'embedded', 'slidein' ), true ) ) {
					continue;
				}

				$can_edit   = Opt_In_Utils::is_user_allowed( 'hustle_edit_module', $id );
				$can_create = current_user_can( 'hustle_create' );

				switch ( $hustle ) {
					case 'publish':
						if ( $can_edit ) {
							$module->activate();
						}
						break;

					case 'unpublish':
						if ( $can_edit ) {
							$module->deactivate();
						}
						break;

					case 'clone':
						if ( $can_create && ! Hustle_Data::was_free_limit_reached( $module->module_type ) ) {
							$module->duplicate_module();
						}
						break;

					case 'delete':
						if ( $can_create ) {
							$module->delete();
						}
						break;

					case 'disable-tracking':
						if ( $can_edit ) {
							$module->disable_type_track_mode( $type, true );
						}
						break;

					case 'enable-tracking':
						if ( $can_edit ) {
							$module->enable_type_track_mode( $type, true );
						}
						break;

					case 'reset-tracking':
						if ( $can_edit ) {
							$tracking = Hustle_Tracking_Model::get_instance();
							$tracking->delete_data( $id );
						}
						break;

					default:
						wp_send_json_error( __( 'Failed', 'hustle' ) );
				}
			}
			wp_send_json_success();
		}

		public function render_module() {
			Opt_In_Utils::validate_ajax_call( 'hustle_gutenberg_get_module' );

			$shortcode_id = filter_input( INPUT_GET, 'shortcode_id', FILTER_SANITIZE_STRING );

			if ( ! $shortcode_id ) {
				wp_send_json_error();
			}

			$module_id = Hustle_Model::get_module_id_by_shortcode_id( $shortcode_id );
			$module    = Hustle_Model::get_module( $module_id );

			if ( is_wp_error( $module ) ) {
				wp_send_json_error();
			}

			if ( Hustle_Module_Model::EMBEDDED_MODULE === $module->module_type || Hustle_Module_Model::SOCIAL_SHARING_MODULE === $module->module_type ) {
				$sub_type = Hustle_Module_Model::SHORTCODE_MODULE;
			} else {
				$sub_type = '';
			}
			ob_start();

			$module->display( $sub_type, '', true );

			$html = ob_get_clean();

			$style = '<style type="text/css" class="hustle-module-styles-' . $module->id . '">' . $module->get_decorated()->get_module_styles( $module->module_type ) . '</style>';

			$response = array(
				'data'  => array(
					'module_id'    => $module->module_id,
					'shortcode_id' => $shortcode_id,
				),
				'html'  => $html,
				'style' => $style,
			);

			wp_send_json_success( $response );
		}

		/**
		 * Get the module_id by the shortcode_id provided.
		 * Used by Gutenberg to create blocks.
		 *
		 * @since 3.0.7
		 *
		 * @return void
		 */
		public function get_module_id_by_shortcode() {
			Opt_In_Utils::validate_ajax_call( 'hustle_gutenberg_get_module' );

			$shortcode_id = filter_input( INPUT_GET, 'shortcode_id', FILTER_SANITIZE_STRING );

			$module_id = Hustle_Model::get_module_id_by_shortcode_id( $shortcode_id );
			$module    = Hustle_Model::get_module( $module_id );
			if ( is_wp_error( $module ) ) {
				wp_send_json_error();
			}
			wp_send_json_success( array( 'module_id' => $module->id ) );
		}


		/**
		 * Get posts/pages/tags/categories for visibility options via ajax.
		 * Finds and repares select2 options.
		 *
		 * @global type $wpdb
		 * @since 3.0.7
		 */
		public function get_new_condition_ids() {
			global $wpdb;

			$post_type = filter_input( INPUT_POST, 'postType', FILTER_SANITIZE_STRING );
			$search    = filter_input( INPUT_POST, 'search' );
			$result    = array();
			$limit     = 30;

			if ( ! empty( $post_type ) ) {
				if ( in_array( $post_type, array( 'tag', 'category', 'wc_category', 'wc_tag' ), true ) ) {
					$args = array(
						'hide_empty' => false,
						'number'     => $limit,
					);
					if ( $search ) {
						$args['search'] = $search;
					}
					if ( 'tag' === $post_type ) {
						$args['taxonomy'] = 'post_tag';
					} elseif ( 'wc_category' === $post_type ) {
						$args['taxonomy'] = 'product_cat';
					} elseif ( 'wc_tag' === $post_type ) {
						$args['taxonomy'] = 'product_tag';
					}
					$result = array_map( array( 'Hustle_Module_Page_Abstract', 'terms_to_select2_data' ), get_categories( $args ) );
				} else {
					global $wpdb;
					$result = $wpdb->get_results(
						$wpdb->prepare(
							"SELECT ID as id, post_title as text FROM {$wpdb->posts} "
							. "WHERE post_type = %s AND post_status = 'publish' AND post_title LIKE %s LIMIT " . intval( $limit ),
							$post_type,
							'%' . $search . '%'
						)
					);
				}
			}

			wp_send_json_success( $result );
		}

	}

endif;