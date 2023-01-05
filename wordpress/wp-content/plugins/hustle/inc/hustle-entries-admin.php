<?php
/**
 * Class Hustle_Entries_Admin
 * Handle the email lists.
 *
 * @since 4.0
 */
class Hustle_Entries_Admin extends Hustle_Admin_Page_Abstract {

	/**
	 * Module types with titles.
	 *
	 * @since 4.3.1
	 * @var array
	 */
	private $module_types;

	/**
	 * Merged default parameter with $_REQUEST
	 *
	 * @since 4.0
	 * @var array
	 */
	private $screen_params = array();

	/**
	 * Current module model
	 *
	 * @since 4.0
	 * @var null|Hustle_Module_Model
	 */
	private $module = null;

	/**
	 * Current module_id
	 *
	 * @since 4.0
	 * @var int
	 */
	protected $module_id = 0;

	/**
	 * Entries array.
	 *
	 * @since 4.0
	 * @var string
	 */
	private $entries = array();

	/**
	 * Page number
	 *
	 * @since 4.0
	 * @var int
	 */
	protected $page_number = 1;

	/**
	 * Total Entries
	 *
	 * @since 4.0
	 * @var int
	 */
	protected $total_entries = 0;

	/**
	 * Total filterd Entries
	 *
	 * @since 4.0
	 * @var int
	 */
	protected $filtered_total_entries = 0;

	/**
	 * @since 4.0
	 * @var Hustle_Provider_Abstract[]
	 */
	private static $registered_addons = null;

	/**
	 * Filters to be used
	 *
	 * [key=>value]
	 * ['search'=>'search term']
	 *
	 * @since 4.0
	 * @var array
	 */
	public $filters = array();

	/**
	 * Order to be used
	 *
	 * [key=>order]
	 * ['entry_date' => 'ASC']
	 *
	 * @since 4.0
	 * @var array
	 */
	public $order = array();

	/**
	 * Nested Mappers
	 *
	 * @since 4.0
	 * @var array
	 */
	protected $fields_mappers = array();

	public function init() {

		$this->page = 'hustle_entries';

		$this->page_title = __( 'Hustle Email Lists', 'hustle' );

		$this->page_menu_title = __( 'Email Lists', 'hustle' );

		$this->page_capability = 'hustle_access_emails';

		$this->page_template_path = 'admin/entries';

		// Show the first page if current page doesn't have entries.
		add_filter( 'removable_query_args', array( $this, 'maybe_remove_paged' ) );
	}

	/**
	 * Remove paged get attribute if there aren't entries and it's not the first page
	 *
	 * @since 4.3.1
	 * @param array $removable_query_args URL query args to be removed.
	 * @return array
	 */
	public function maybe_remove_paged( $removable_query_args ) {
		$paged = filter_input( INPUT_GET, 'paged', FILTER_VALIDATE_INT );

		if ( $paged && 1 !== $paged && 'hustle_entries' === $this->current_page ) {
			$per_page      = Hustle_Settings_Admin::get_per_page( 'submission' );
			$offset        = ( $paged - 1 ) * $per_page;
			$module_id     = filter_input( INPUT_GET, 'module_id', FILTER_VALIDATE_INT );
			$total_entries = Hustle_Entry_Model::count_entries( $module_id );
			if ( $total_entries <= $offset ) {
				$_SERVER['REQUEST_URI'] = remove_query_arg( 'paged' );
				$removable_query_args[] = 'paged';
				unset( $_GET['paged'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
		}

		return $removable_query_args;
	}

	/**
	 * Get the arguments used when rendering the main page.
	 *
	 * @since 4.0.1
	 * @return array
	 */
	public function get_page_template_args() {

		$types  = $this->get_module_types();
		$module = $this->get_module_model();

		$filter_types = array(
			'search_email',
			'order_by',
			'date_range',
		);

		$is_filtered = false;
		foreach ( $filter_types as $type ) {
			$is_filtered = $is_filtered || filter_input( INPUT_GET, $type );
		}
		if ( $module && $module->active ) {
			$integrations  = $module->get_integrations_settings()->to_array();
			$no_local_list = false === strpos( $integrations['active_integrations'], 'local_list' );
		} else {
			$no_local_list = false;
		}

		return array(
			'module'             => $module,
			'entries'            => $this->get_entries(),
			'global_entries'     => Hustle_Entry_Model::global_count_entries(),
			'module_name'        => ! empty( $module->module_type ) && isset( $types[ $module->module_type ] ) ? $types[ $module->module_type ] : '',
			'is_module_selected' => (bool) $this->get_current_module_id(),
			'is_filtered'        => $is_filtered,
			'no_local_list'      => $no_local_list,
		);
	}

	public function current_page_loaded() {
		parent::current_page_loaded();
		$this->before_render();

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue scripts for the submissions page.
	 *
	 * @since 4.2.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			'hustle-entries-moment',
			Opt_In::$plugin_url . 'assets/js/vendor/moment.min.js',
			array( 'jquery' ),
			Opt_In::VERSION,
			true
		);

		wp_enqueue_script(
			'hustle-entries-datepicker-range',
			Opt_In::$plugin_url . 'assets/js/vendor/daterangepicker.min.js',
			array( 'jquery', 'hustle-entries-moment' ),
			'3.0.5',
			true
		);
	}

	/**
	 * Register the js variables to be localized for this page.
	 *
	 * @since 4.3.1
	 *
	 * @return array
	 */
	protected function get_vars_to_localize() {
		$current_array = parent::get_vars_to_localize();

		// These labels are used in getDaterangepickerRanges(), entries.js.
		// These keys must match the keys from there.
		$datepicker_ranges = array(
			'today'            => esc_html__( 'Today', 'hustle' ),
			'yesterday'        => esc_html__( 'Yesterday', 'hustle' ),
			'last_seven_days'  => esc_html__( 'Last 7 Days', 'hustle' ),
			'last_thirty_days' => esc_html__( 'Last 30 Days', 'hustle' ),
			'this_month'       => esc_html__( 'This Month', 'hustle' ),
			'last_month'       => esc_html__( 'Last Month', 'hustle' ),
		);

		$current_array['daterangepicker'] = array(
			'daysOfWeek' => Hustle_Time_Helper::get_week_days( 'min' ),
			'monthNames' => Hustle_Time_Helper::get_months(),
			'ranges'     => $datepicker_ranges,
		);

		return $current_array;
	}

	/**
	 * Populating the current page parameters
	 *
	 * @since 4.0.0
	 */
	public function populate_screen_params() {
		$screen_params = array(
			'module_type' => 'popup',
			'module_id'   => 0,
		);

		$this->screen_params = array_merge( $screen_params, $_REQUEST );// WPCS CSRF ok.
	}

	/**
	 * Executed Action before rendering the page.
	 *
	 * @since 4.0
	 */
	public function before_render() {
		$this->populate_screen_params();
		$this->prepare_entries_page();
		$this->export();
	}

	/**
	 * Get the module types for the entries page.
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */
	public function get_module_types() {
		if ( empty( $this->module_types ) ) {
			$module_types = Hustle_Data::get_module_types();

			unset( $module_types[ Hustle_Model::SOCIAL_SHARING_MODULE ] );

			$types_with_title = array();
			foreach ( $module_types as $type ) {
				$types_with_title[ $type ] = Opt_In_Utils::get_module_type_display_name( $type, false, true );
			}
			$this->module_types = $types_with_title;
		}

		return $this->module_types;
	}

	/**
	 * Prepare entries
	 *
	 * @since 4.0
	 */
	private function prepare_entries_page() {
		$this->module = $this->get_module_model();
		// Module not found
		if ( ! $this->module ) {
			// if module_id available remove it from request, and redirect
			if ( $this->get_current_module_id() ) {
				$url = remove_query_arg( 'module_id' );
				if ( wp_safe_redirect( $url ) ) {
					exit;
				}
			}
		} else {
			// as page's before_render()
			$this->prepare_page();
		}
	}

	/**
	 * Return the html for the module switcher.
	 *
	 * @since 4.0
	 *
	 * @return string
	 */
	public function render_module_switcher() {

		$modules = $this->get_modules();

		// TODO: get the module types from the right place.
		$module_types = $this->get_module_types();
		$current_type = $this->get_current_module_type();
		$empty_option = isset( $module_types[ $current_type ] ) ? $module_types[ $current_type ] : $module_types['popup'];

		$html = '<select name="module_id" class="sui-select sui-select-sm sui-select-inline" data-width="250" data-search="true" data-placeholder="' . __( 'Choose', 'hustle' ) . ' ' . $empty_option . '">';

		$html .= '<option></option>';

		foreach ( $modules as $module ) {

			$title = ! empty( $module->module_name ) ? $module->module_name : $module->module_id;
			$html .= '<option value="' . $module->module_id . '" ' . selected( $module->module_id, $this->get_current_module_id(), false ) . '>' . $title . '</option>';
		}

		$html .= '</select>';

		return $html;
	}

	/**
	 * Return the modules of the current type.
	 *
	 * @since 4.0
	 *
	 * @return array Hustle_Module_Model[]
	 */
	public function get_modules() {
		$module_types = $this->get_module_types();
		$current_type = $this->get_current_module_type();
		$module_type  = isset( $module_types[ $current_type ] ) ? $current_type : 'popup';
		$args         = array(
			'module_type' => $module_type,
			'module_mode' => 'optin',
		);
		$modules      = Hustle_Module_Collection::instance()->get_all( null, $args );

		return $modules;
	}

	/**
	 * Get module model if the requested module_id is available and matches module_type
	 *
	 * @since 4.0
	 *
	 * @return bool|Hustle_Module_Model|null
	 */
	public function get_module_model() {

		if ( $this->get_current_module_id() ) {

			$module = new Hustle_Module_Model( $this->get_current_module_id() );

			if ( is_wp_error( $module ) ) {
				return null;
			}

			if ( ! $module instanceof Hustle_Module_Model ) {
				return null;
			}

			if ( $module->module_type !== $this->get_current_module_type() ) {
				return null;
			}

			return $module;
		}

		return null;
	}

	/**
	 * Get current module type
	 *
	 * @since 4.0
	 *
	 * @return mixed
	 */
	public function get_current_module_type() {
		return $this->screen_params['module_type'];
	}

	/**
	 * Get current module id
	 *
	 * @since 4.0
	 *
	 * @return mixed
	 */
	public function get_current_module_id() {
		return $this->screen_params['module_id'];
	}

	// ====================


	/**
	 * Get Entries
	 *
	 * @since 4.0
	 * @return array
	 */
	public function get_entries() {
		return $this->entries;
	}

	/**
	 * Get Page Number
	 *
	 * @since 4.0
	 * @return int
	 */
	public function get_page_number() {
		return $this->page_number;
	}

	/**
	 * Get Per Page
	 *
	 * @since 1.0
	 * @return int
	 */
	public function get_per_page() {
		return $this->per_page;
	}

	/**
	 * The total filtered entries
	 *
	 * @since 4.0
	 * @return int
	 */
	public function filtered_total_entries() {
		return $this->filtered_total_entries;
	}

	/**
	 * Prepare email list page
	 * admin_page_entries as 'before_render'
	 */
	private function prepare_page() {
		$this->module_id = (int) $this->module->module_id;

		$this->parse_filters();
		$this->parse_order();

		$this->per_page = Hustle_Settings_Admin::get_per_page( 'submission' );

		// don't use filter_input() here, because of see maybe_remove_paged() method.
		$pagenum           = ! empty( $_GET['paged'] ) ? (int) $_GET['paged'] : 1; // phpcs:ignore
		$this->page_number = max( 1, $pagenum );

		/**
		 * Fires on custom form page entries render before request and result processed
		 *
		 * @since 4.0
		 */
		do_action( 'hustle_admin_page_entries', $this->module_id, $this->module, $pagenum );

		$this->process_request();
		$this->prepare_results();
	}

	/**
	 * Process the current request.
	 *
	 * @since 4.0
	 */
	private function process_request() {

		// Start modifying data.
		if ( ! isset( $_REQUEST['hustle_nonce'] ) ) {
			return;
		}

		$nonce = $_REQUEST['hustle_nonce']; // WPCS: CSRF OK
		if ( ! wp_verify_nonce( $nonce, 'hustle_entries_request' ) ) {
			return;
		}

		if ( ! current_user_can( 'hustle_access_emails' ) ) {
			return;
		}

		$action = filter_input( INPUT_POST, 'hustle_action', FILTER_SANITIZE_STRING );
		if ( empty( $action ) ) {
			$action = filter_input( INPUT_POST, 'hustle_action_bottom', FILTER_SANITIZE_STRING );
		}

		switch ( $action ) {
			case 'delete':
				$entry_id = filter_var( $_REQUEST['id'], FILTER_VALIDATE_INT );

				if ( ! $entry_id ) {
					return;
				}
				Hustle_Entry_Model::delete_by_entry( $this->module_id, $entry_id );
				break;

			case 'delete-all':
				$entries = filter_input( INPUT_POST, 'ids', FILTER_SANITIZE_STRING );
				if ( ! empty( $entries ) ) {
					$entries = explode( ',', $entries );
					Hustle_Entry_Model::delete_by_entries( $this->module_id, $entries );
				}
				break;

			default:
				return;
		}

		$url_params = array(
			'page'        => isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : 'hustle_entries',
			'module_type' => isset( $_REQUEST['module_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['module_type'] ) ) : '',
			'module_id'   => $this->module_id,
			'paged'       => isset( $_REQUEST['paged'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['paged'] ) ) : 1,
		);

		$url = add_query_arg( $url_params, 'admin.php' );
		wp_safe_redirect( $url ); // Redirect to the first entry page.
		exit;
	}

	/**
	 * Prepare the entries to be shown.
	 *
	 * @since 4.0
	 */
	private function prepare_results() {

		if ( is_object( $this->module ) ) {
			$paged    = $this->page_number;
			$per_page = $this->per_page;
			$offset   = ( $paged - 1 ) * $per_page;

			$this->total_entries = Hustle_Entry_Model::count_entries( $this->module_id );

			$args = array(
				'module_id' => $this->module_id,
				'per_page'  => $per_page,
				'offset'    => $offset,
				'order_by'  => 'entries.date_created',
				'order'     => 'ASC',
			);

			$args = wp_parse_args( $this->filters, $args );
			$args = wp_parse_args( $this->order, $args );

			$count = 0;

			$this->entries                = Hustle_Entry_Model::query_entries( $args, $count );
			$this->filtered_total_entries = $count;
		}
	}

	/**
	 * @return array
	 */
	public function entries_iterator() {
		/**
		 * @example
		 * {
		 *  id => 'ENTRY_ID'
		 *  summary = [
		 *      'num_fields_left' => true/false,
		 *      'items' => [
		 *          [
		 *              'colspan' => 2/...,
		 *              'value' => '----',
		 *          ]
		 *          [
		 *              'colspan' => 2/...
		 *              value' => '----',
		 *          ]
		 *      ],
		 *  ],
		 *  detail = [
		 *      'colspan' => '',
		 *      'items' => [
		 *          [
		 *              'label' => '----',
		 *              'value' => '-----'
		 *              'sub_entries' => [
		 *                  [
		 *                      'label' => '----',
		 *                      'value' => '-----'
		 *                  ]
		 *              ]
		 *          ]
		 *          [
		 *              'label' => '----',
		 *              'value' => '-----'
		 *          ]
		 *      ],
		 * ]
		 * }
		 */
		$entries_iterator = array();

		$total_colspan  = 5; // Colspan for ID + Date Submitted + Active Integrations + Email + Accordion chevron.
		$fields_mappers = $this->get_fields_mappers();

		// Start from 4, since first four are ID, Date, Active Integrations, and Email.
		$fields_left = count( $fields_mappers ) - 4;

		// All headers including ID + Date, start from 0 and max is 4.
		$headers = array_slice( $fields_mappers, 0, 4 );

		$numerator_id = $this->total_entries;
		if ( $this->page_number > 1 ) {
			$numerator_id = $this->total_entries - ( ( $this->page_number - 1 ) * $this->per_page );
		}

		foreach ( $this->entries as $entry ) {
			/**@var Hustle_Entry_Model $entry */

			// create placeholder
			$iterator = array(
				'id'       => $numerator_id,
				'entry_id' => $entry->entry_id,
				'summary'  => array(),
				'detail'   => array(),
				'addons'   => array(),
			);

			$iterator['summary']['num_fields_left'] = $fields_left;
			$iterator['summary']['items']           = array();

			$iterator['detail']['colspan'] = $total_colspan;
			$iterator['detail']['items']   = array();

			// Build array for summary row
			$summary_items = array();
			foreach ( $headers as $header ) {

				$colspan = 2;
				$class   = '';

				if ( isset( $header['type'] ) ) {

					if ( 'entry_entry_id' === $header['type'] ) {
						$summary_items[] = array(
							'colspan' => 1,
							'value'   => $numerator_id,
						);
						continue;

					} elseif ( 'entry_time_created' === $header['type'] ) {
						$colspan = 3;
						$class   = 'hui-column-date';

					} elseif ( 'entry_integrations' === $header['type'] ) {
						$class = 'hui-column-apps';

					}
				}

				$value = $this->get_entry_field_value( $entry, $header, '', false, 100 );

				$summary_items[] = array(
					'colspan' => $colspan,
					'value'   => $value,
					'class'   => $class,
				);
			}

			// Build array for -content row
			$detail_items = array();
			foreach ( $fields_mappers as $mapper ) {
				// Skip entry id and Active integrations
				if ( isset( $mapper['type'] ) && ( 'entry_entry_id' === $mapper['type'] || 'entry_integrations' === $mapper['type'] ) ) {
					continue;
				}

				$label       = $mapper['label'];
				$value       = $this->get_entry_field_value( $entry, $mapper, '', true );
				$sub_entries = array();

				$detail_items[] = array(
					'label'       => $label,
					'value'       => $value,
					'sub_entries' => $sub_entries,
				);

			}

			// Additional render for addons
			$addons_detail_items = $this->attach_addon_on_render_entry( $entry );
			// $detail_items        = array_merge( $detail_items, $addons_detail_items );

			$addons = array();
			foreach ( $addons_detail_items as $provider_meta ) {
				foreach ( $provider_meta as $meta ) {
					$addons[] = array(
						'summary' => array(
							'name'      => $meta['name'],
							'icon'      => $meta['icon'],
							'data_sent' => $meta['data_sent'],
						),
						'detail'  => $meta['sub_entries'],
					);
				}
			}

			$iterator['summary']['items'] = $summary_items;
			$iterator['detail']['items']  = $detail_items;

			$iterator['addons'] = $addons;

			$entries_iterator[] = $iterator;
			$numerator_id --;
		}

		return $entries_iterator;
	}


	/**
	 * Get Fields Mappers based on current state of form
	 *
	 * @return array
	 */
	public function get_fields_mappers() {
		if ( empty( $this->fields_mappers ) ) {
			$this->fields_mappers = $this->build_fields_mappers();
		}

		return $this->fields_mappers;
	}


	private function build_fields_mappers() {
		$module              = $this->module;
		$fields              = $module->get_form_fields();
		$ignored_field_types = Hustle_Entry_Model::ignored_fields();

		$mappers = array(
			array(
				// read model's property
				'property' => 'entry_id', // must be on entries
				'label'    => __( 'ID', 'hustle' ),
				'type'     => 'entry_entry_id',
			),
			array(
				// read model's property
				'property' => 'time_created', // must be on entries
				'label'    => __( 'Date Submitted', 'hustle' ),
				'type'     => 'entry_time_created',
				'class'    => 'hui-column-date',
			),
			array(
				// read entry meta
				'meta_key' => 'active_integrations',
				'label'    => __( 'Active Integrations', 'hustle' ),
				'type'     => 'entry_integrations',
				'class'    => 'hui-column-apps',
			),
			array(
				// required meta key
				'meta_key' => 'email', // must be on entries
				'label'    => __( 'Email', 'hustle' ),
				'type'     => 'email',
			),
		);

		foreach ( $fields as $field ) {

			$field_type = $field['type'];

			if ( 'email' === $field['name'] || in_array( $field_type, $ignored_field_types, true ) ) {
				continue;
			}

			// if ( ! empty( $visible_fields ) ) {
			// if ( ! in_array( $field->slug, $visible_fields, true ) ) {
			// continue;
			// }
			// }

			// base mapper for every field
			$mapper             = array();
			$mapper['meta_key'] = $field['name'];
			$mapper['label']    = $field['label'];
			$mapper['type']     = $field_type;

			if ( ! empty( $mapper ) ) {
				$mappers[] = $mapper;
			}
		}

		return $mappers;
	}

	/**
	 * Get entry field value helper
	 *
	 * @param Hustle_Entry_Model $entry
	 * @param                             $mapper
	 * @param string             $sub_meta_key
	 * @param bool               $allow_html
	 * @param int                $truncate
	 *
	 * @return string
	 */
	private function get_entry_field_value( $entry, $mapper, $sub_meta_key = '', $allow_html = false, $truncate = PHP_INT_MAX ) {
		/** @var Hustle_Entry_Model $entry */
		if ( isset( $mapper['property'] ) ) {
			if ( property_exists( $entry, $mapper['property'] ) ) {
				$property = $mapper['property'];
				// casting property to string
				if ( is_array( $entry->$property ) ) {
					$value = implode( ', ', $entry->$property );
				} else {
					$value = (string) $entry->$property;
				}
			} else {
				$value = '';
			}
		} else {
			$meta_value = $entry->get_meta( $mapper['meta_key'], '' );
			// meta_key based
			$value = Hustle_Entry_Model::meta_value_to_string( $mapper['type'], $meta_value, $allow_html, $truncate );

		}

		return $value;
	}

	/**
	 * Executor for adding additional items on entry page.
	 *
	 * @see Hustle_Provider_Form_Hooks_Abstract::on_render_entry()
	 * @since 4.0
	 *
	 * @param Hustle_Entry_Model $entry_model
	 * @return array
	 */
	private function attach_addon_on_render_entry( Hustle_Entry_Model $entry_model ) {
		$additonal_items = array();
		// Find all registered addons so history can be shown even for deactivated addons.
		$registered_addons = $this->get_registered_addons();

		foreach ( $registered_addons as $registered_addon ) {
			try {
				$form_hooks = $registered_addon->get_addon_form_hooks( $this->module_id );
				$meta_data  = Hustle_Provider_Utils::find_addon_meta_data_from_entry_model( $registered_addon, $entry_model );

				$addon_additional_items = $form_hooks->on_render_entry( $entry_model, $meta_data );
				$addon_additional_items = self::format_addon_additional_items( $addon_additional_items );

				// $additonal_items = array_merge( $additonal_items, $addon_additional_items );
				$additonal_items[] = $addon_additional_items;
			} catch ( Exception $e ) {
				Opt_In_Utils::maybe_log( $registered_addon->get_slug(), 'failed to on_render_entry', $e->getMessage() );
			}
		}

		return $additonal_items;
	}


	/**
	 * Ensuring additional items for addons meet the entries data requirements.
	 * Format used:
	 * - label
	 * - value
	 * - subentries[]
	 *      - label
	 *      - value
	 *
	 * @since 4.0
	 *
	 * @param  array $addon_additional_items
	 * @return mixed
	 */
	private static function format_addon_additional_items( $addon_additional_items ) {
		// to `name` and `value` basis
		$formatted_additional_items = array();

		if ( ! is_array( $addon_additional_items ) ) {
			return array();
		}

		foreach ( $addon_additional_items as $additional_item ) {
			if ( ! isset( $additional_item['name'] ) || ! isset( $additional_item['data_sent'] ) || ! isset( $additional_item['sub_entries'] ) ) {
				continue;
			}
			// Make sure label and value exist, without it, it will display an empty row, so leave it.
			// if ( ! isset( $additional_item['label'] ) || ! isset( $additional_item['value'] ) ) {
			// continue;
			// }

			$sub_entries = array();

			// Check if sub_entries are available.
			if ( isset( $additional_item['sub_entries'] ) && is_array( $additional_item['sub_entries'] ) ) {
				foreach ( $additional_item['sub_entries'] as $sub_entry ) {
					// Make sure label and value exist, without it, it will display empty row.
					if ( ! isset( $sub_entry['label'] ) || ! isset( $sub_entry['value'] ) ) {
						continue;
					}
					$sub_entries[] = array(
						'label' => $sub_entry['label'],
						'value' => $sub_entry['value'],
					);
				}
			}

			// $formatted_additional_items[] = array(
			// 'label'       => $additional_item['label'],
			// 'value'       => $additional_item['value'],
			// 'sub_entries' => $sub_entries,
			// );

			$formatted_additional_items[] = array(
				'name'        => $additional_item['name'],
				'icon'        => $additional_item['icon'],
				'data_sent'   => $additional_item['data_sent'],
				'sub_entries' => $sub_entries,
			);
		}

		return $formatted_additional_items;
	}

	/**
	 * Get Globally registered Addons, avoid overhead for checking registered addons many times
	 *
	 * @since 4.0
	 *
	 * @return array|Hustle_Provider_Abstract[]
	 */
	public function get_registered_addons() {
		if ( empty( self::$registered_addons ) ) {
			self::$registered_addons = array();

			$registered_addons = Hustle_Provider_Utils::get_registered_addons();
			foreach ( $registered_addons as $registered_addon ) {
				try {
					$form_hooks = $registered_addon->get_addon_form_hooks( $this->module_id );
					if ( $form_hooks instanceof Hustle_Provider_Form_Hooks_Abstract ) {
						self::$registered_addons[] = $registered_addon;
					}
				} catch ( Exception $e ) {
					Opt_In_Utils::maybe_log( $registered_addon->get_slug(), 'failed to get_addon_form_hooks', $e->getMessage() );
				}
			}
		}

		return self::$registered_addons;
	}

	/**
	 * Parsing filters from $_REQUEST
	 *
	 * @since 4.0
	 */
	protected function parse_filters() {
		$request_data = $_REQUEST;// WPCS CSRF ok.
		$data_range   = isset( $request_data['date_range'] ) ? sanitize_text_field( $request_data['date_range'] ) : '';
		$search       = isset( $request_data['search_email'] ) ? sanitize_text_field( $request_data['search_email'] ) : '';

		$filters = array();
		if ( ! empty( $data_range ) ) {
			$date_ranges = explode( ' - ', $data_range );
			if ( is_array( $date_ranges ) && isset( $date_ranges[0] ) && isset( $date_ranges[1] ) ) {
				$date_ranges[0] = date( 'Y-m-d', strtotime( $date_ranges[0] ) );
				$date_ranges[1] = date( 'Y-m-d', strtotime( $date_ranges[1] ) );

				$filters['date_created'] = array( $date_ranges[0], $date_ranges[1] );
			}
		}
		if ( ! empty( $search ) ) {
			$filters['search_email'] = $search;
		}

		$this->filters = $filters;
	}

	/**
	 * Parsing order from $_REQUEST
	 *
	 * @since 4.0
	 */
	protected function parse_order() {
		$valid_order_bys = array(
			'entries.date_created',
			'entries.entry_id',
		);

		$valid_orders = array(
			'DESC',
			'ASC',
		);
		$request_data = $_REQUEST;// WPCS CSRF ok.
		$order_by     = isset( $request_data['order_by'] ) ? sanitize_text_field( $request_data['order_by'] ) : 'entries.date_created';
		$order        = isset( $request_data['order'] ) ? sanitize_text_field( $request_data['order'] ) : 'DESC';

		if ( ! empty( $order_by ) ) {
			if ( ! in_array( $order_by, $valid_order_bys, true ) ) {
				$order_by = 'entries.date_created';
			}

			$this->order['order_by'] = $order_by;
		}

		if ( ! empty( $order ) ) {
			$order = strtoupper( $order );
			if ( ! in_array( $order, $valid_orders, true ) ) {
				$order = 'DESC';
			}

			$this->order['order'] = $order;
		}
	}

	/**
	 * Flag whether box filter is open or nope
	 *
	 * @since 4.0
	 * @return bool
	 */
	// protected function is_filter_box_enabled() {
	public function is_filter_box_enabled() {
		return ( ! empty( $this->filters ) && ! empty( $this->order ) );
	}

	/**
	 * Get module type param
	 *
	 * @since 4.0
	 * @return string
	 */
	public function get_module_type() {
		return $this->screen_params['module_type'];
	}

	/**
	 * Get module id param
	 *
	 * @since 4.0
	 * @return string
	 */
	public function get_module_id() {
		return $this->screen_params['module_id'];
	}

	/**
	 * Export the entries of the current module.
	 *
	 * @since 4.0
	 */
	private function export() {

		$action = filter_input( INPUT_POST, 'hustle_action', FILTER_SANITIZE_STRING );
		if ( 'export_listing' !== $action ) {
			return;
		}
		$nonce = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
		if ( ! wp_verify_nonce( $nonce, 'hustle_module_export_listing' ) ) {
			return;
		}

		if ( ! current_user_can( 'hustle_access_emails' ) ) {
			return;
		}

		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		if ( ! $id ) {
			return;
		}

		$module   = new Hustle_Module_Model( $id );
		$filename = sprintf(
			'hustle-%s-%s-%s-%s-emails.csv',
			$module->module_type,
			date( 'Ymd-his' ),
			get_bloginfo( 'name' ),
			$module->module_name
		);
		$filename = strtolower( sanitize_file_name( $filename ) );

		$entries = $this->get_entries_for_export();

		$fp = fopen( 'php://memory', 'w' ); // phpcs:disable WordPress.WP.AlternativeFunctions.file_system_read_fopen -- disable phpcs because it writes memory
		foreach ( $entries as $entry ) {
			$fields = self::get_formatted_csv_fields( $entry );
			fputcsv( $fp, $fields );
		}
		fseek( $fp, 0 );

		header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header( 'Content-Description: File Transfer' );
		header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );
		header( 'Content-Disposition: attachment; filename="' . $filename . '";' );
		header( 'Expires: 0' );
		header( 'Pragma: public' );

		// print BOM Char for Excel Compatible
		echo chr( 239 ) . chr( 187 ) . chr( 191 );// wpcs xss ok. excel generated content

		// Send the generated csv lines to the browser.
		if ( function_exists( 'fpassthru' ) ) {
			fpassthru( $fp );
		} elseif ( function_exists( 'stream_get_contents' ) ) {
			echo stream_get_contents( $fp ); // phpcs:ignore xss ok.
		}

		exit();

	}

	/**
	 * Get the entries as a ready to export csv array.
	 *
	 * @since 4.0
	 * @return array
	 */
	private function get_entries_for_export() {

		$headers = $this->get_fields_mappers();

		$headers[] = array(
			'meta_key' => 'hustle_ip',
			'label'    => 'IP',
			'type'     => 'ip',
		);

		$header_labels = wp_list_pluck( $headers, 'label' );
		$entries       = array( $header_labels );

		$all_entries = Hustle_Entry_Model::get_entries( $this->module_id );

		// Get all entries.
		foreach ( $all_entries as $entry ) {

			$row = array();

			// Get the entry's value for each header.
			foreach ( $headers as $header ) {
				$value = $this->get_entry_field_value( $entry, $header, '', false );
				$row[] = $value;
			}

			$entries[] = $row;
		}

		return $entries;
	}

	// ====================

	/**
	 * Format csv fields.
	 *
	 * @since 4.0
	 *
	 * @param array $fields
	 * @return array|string
	 */
	public static function get_formatted_csv_fields( $fields ) {
		if ( empty( $fields ) || ! is_array( $fields ) ) {
			return $fields;
		}

		$formatted_fields = array();

		foreach ( $fields as $field ) {
			if ( ! is_scalar( $field ) ) {
				$formatted_fields[] = '';
				continue;
			}
			if ( is_scalar( $field ) ) {
				$formatted_fields[] = self::escape_csv_data( $field );
			}
		}

		return $formatted_fields;
	}

	/**
	 * Escape a string to be used in a CSV context
	 *
	 * Taken from Forminator, where was taken from WooCommerce CSV Exporter
	 *
	 * @see   https://github.com/woocommerce/woocommerce/blob/master/includes/export/abstract-wc-csv-exporter.php
	 *
	 * @since 1.6
	 *
	 * Malicious input can inject formulas into CSV files, opening up the possibility
	 * for phishing attacks and disclosure of sensitive information.
	 *
	 * Additionally, Excel exposes the ability to launch arbitrary commands through
	 * the DDE protocol.
	 *
	 * @see   http://www.contextis.com/resources/blog/comma-separated-vulnerabilities/
	 * @see   https://hackerone.com/reports/72785
	 *
	 * @since 4.0
	 *
	 * @param string $data CSV field to escape.
	 *
	 * @return string
	 */
	public static function escape_csv_data( $data ) {
		$active_content_triggers = array( '=', '+', '-', '@' );
		if ( in_array( mb_substr( $data, 0, 1 ), $active_content_triggers, true ) ) {
			$data = "'" . $data . "'";
		}

		return $data;
	}
}