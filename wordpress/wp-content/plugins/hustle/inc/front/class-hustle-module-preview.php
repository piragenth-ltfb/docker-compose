<?php
/**
 * Module's preview page handler.
 *
 * @package Hustle
 * @since 4.3.1
 */

/**
 * Hustle_Module_Preview class.
 *
 * @since 4.3.1
 */
class Hustle_Module_Preview {

	/**
	 * Hustle_Module_Preview constructor.
	 *
	 * @since 4.3.1
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_preview_scripts' ) );

		add_action( 'wp_footer', array( $this, 'wp_footer' ) );

		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

		add_filter( 'the_title', array( $this, 'the_title' ) );

		add_filter( 'the_content', array( $this, 'show_after_page_post_content' ) );

		// With a priority of 20 to override possible WC's filter.
		add_filter( 'show_admin_bar', '__return_false', 20 );
	}

	/**
	 * Registers the script used in the preview iframe.
	 *
	 * @since 4.3.1
	 */
	public function register_preview_scripts() {
		Hustle_Module_Front::add_hui_scripts();

		wp_register_script(
			'hustle_preview_script',
			Opt_In::$plugin_url . 'assets/js/preview.min.js',
			array( 'jquery' ),
			Opt_In::VERSION,
			true
		);

		$vars = array(
			'ajaxurl'         => admin_url( 'admin-ajax.php', is_ssl() ? 'https' : 'http' ),
			'days_and_months' => array(
				'days_full'    => Hustle_Time_Helper::get_week_days(),
				'days_short'   => Hustle_Time_Helper::get_week_days( 'short' ),
				'days_min'     => Hustle_Time_Helper::get_week_days( 'min' ),
				'months_full'  => Hustle_Time_Helper::get_months(),
				'months_short' => Hustle_Time_Helper::get_months( 'short' ),
			),
		);

		wp_localize_script(
			'hustle_preview_script',
			'hustleVars',
			$vars
		);
		wp_enqueue_script( 'hustle_preview_script' );
	}

	/**
	 * Set of actions to run on the wp_footer.
	 *
	 * @since 4.3.1
	 */
	public function wp_footer() {
		Hustle_Module_Front::print_front_styles();
		$this->render_non_inline_preview_container();
		$this->maybe_print_forminator_scripts();
	}

	/**
	 * Print forminator scripts for preview.
	 * Used by Dashboard, Wizards, and Listings.
	 *
	 * @since 4.3.1
	 */
	private function maybe_print_forminator_scripts() {
		// Add Forminator's front styles and scripts for preview.
		if ( defined( 'FORMINATOR_VERSION' ) ) {
			forminator_print_front_styles( FORMINATOR_VERSION );
			forminator_print_front_scripts( FORMINATOR_VERSION );

		}
	}

	/**
	 * Set the amount of posts to 1 per page.
	 * Useful when the first page is one containing posts.
	 *
	 * @since 4.3.1
	 *
	 * @param WP_Query $query The WP_Query instance.
	 */
	public function pre_get_posts( $query ) {
		$query->set( 'posts_per_page', 1 );
	}

	/**
	 * Adds a custom title for the page/post.
	 *
	 * @since 4.3.1
	 *
	 * @param string $title Title to be displayed.
	 * @return string
	 */
	public function the_title( $title ) {
		if ( ! in_the_loop() ) {
			return $title;
		}
		return esc_html__( 'Hustle Preview', 'hustle' );
	}

	/**
	 * Replaces the_content by a container to render inline modules in.
	 * Used for rendering embeds.
	 *
	 * @since 4.3.1
	 *
	 * @param string $content Current post/page content.
	 * @return string
	 */
	public function show_after_page_post_content( $content ) {
		return '<div id="module-preview-inline-container"></div>' . $content;
	}

	/**
	 * Adds a container at the bottom of the page to render non-inline modules in.
	 * Used for popups and slide-ins.
	 *
	 * @since 4.3.1
	 */
	private function render_non_inline_preview_container() {
		echo '<div id="module-preview-container"></div>';
	}
}