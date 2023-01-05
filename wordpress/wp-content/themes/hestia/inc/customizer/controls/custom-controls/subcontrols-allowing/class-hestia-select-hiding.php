<?php
/**
 * Select control which allows to show/hide another control.
 *
 * @package    Hestia
 */

/**
 * Class Hestia_Select_Hiding
 */
class Hestia_Select_Hiding extends WP_Customize_Control {
	/**
	 * The type of customize control being rendered.
	 *
	 * @access public
	 * @var    string
	 */
	public $type = 'select-hiding';

	/**
	 * Subcontrols of each option
	 *
	 * @access public
	 * @var string
	 */
	public $subcontrols = array();

	/**
	 * Parent of control
	 *
	 * @access public
	 * @var string
	 */
	public $parent = '';

	/**
	 * Flag to display locked icon.
	 *
	 * @since  3.0.24
	 * @access public
	 * @var bool
	 */
	public $locked = false;

	/**
	 * Hestia_Select_Multiple constructor.
	 *
	 * @param WP_Customize_Manager $manager Customize manager object.
	 * @param string               $id Control id.
	 * @param array                $args Control arguments.
	 */
	public function __construct( WP_Customize_Manager $manager, $id, array $args = array() ) {
		parent::__construct( $manager, $id, $args );
		if ( ! class_exists( 'Hestia_Slider_Controls_Addon' ) && in_array( $id, array( 'hestia_slider_type' ), true ) ) {
			$this->locked = true;
		}
	}

	/**
	 * Loads the framework scripts/styles.
	 *
	 * @access public
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_script( 'hestia-select-hiding-controls', get_template_directory_uri() . '/inc/customizer/controls/custom-controls/subcontrols-allowing/script.js', array( 'jquery', 'customize-base' ), HESTIA_VERSION, true );
	}
	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.1.40
	 * @access public
	 * @return array
	 */
	public function json() {
		$json                = parent::json();
		$json['choices']     = $this->choices;
		$json['link']        = $this->get_link();
		$json['value']       = (array) $this->value();
		$json['id']          = $this->id;
		$json['subcontrols'] = $this->subcontrols;
		$json['parent']      = $this->parent;
		$json['locked']      = $this->locked;

		return $json;
	}


	/**
	 * Underscore JS template to handle the control's output.
	 *
	 * @since  1.1.40
	 * @access public
	 * @return void
	 */
	public function content_template() {
		?>
		<# if ( ! data.choices ) {
		return;
		} #>

		<label>
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{ data.label }}</span>
				<# } #>

					<# if ( data.description ) { #>
						<span class="description customize-control-description">{{{ data.description }}}</span>
						<# } #>

							<select {{{ data.link }}}>
								<# _.each( data.choices, function( label, choice ) {
										var proOptions = [ 'video' ];
										var selected = '';
										if ( choice === data.value ) {
											selected = 'selected="selected"';
										}
										#>
									<# if ( data.locked && _.indexOf( proOptions, choice ) !== -1 ) { #>
										<option value="{{ choice }}" class="hestia-locked-icon">{{ label }}</option>
									<# } else { #>
										<option value="{{ choice }}" {{selected}}>{{ label }}</option>
									<# } #>

											<# } ) #>
							</select>
							<# if ( data.locked ) { #>
							<span class="dashicons dashicons-lock"></span>
							<# } #>
		</label>
		<?php
	}
}
