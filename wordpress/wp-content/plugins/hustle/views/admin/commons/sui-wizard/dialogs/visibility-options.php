<?php
/**
 * Modal for selecting the visibility conditions.
 *
 * @package Hustle
 * @since 4.0.0
 */

if ( isset( $smallcaps_singular ) ) {
	$smallcaps_singular = $smallcaps_singular;
} else {
	$smallcaps_singular = esc_html__( 'module', 'hustle' );
}
$post_types = wp_list_pluck( Opt_In_Utils::get_post_types(), 'label', 'name' );

$body = array();

if ( Opt_In_Utils::is_woocommerce_active() ) {
	ob_start(); ?>

	<div class="sui-tabs">

		<div role="tablist" class="sui-tabs-menu">

			<button
				type="button"
				role="tab"
				id="hustle-general-conditions"
				class="sui-tab-item active"
				aria-controls="hustle-general-conditions"
				aria-selected="true"
			><?php esc_html_e( 'General', 'hustle' ); ?></button>

			<button
				type="button"
				role="tab"
				id="hustle-wc-conditions"
				class="sui-tab-item"
				aria-controls="hustle-wc-conditions"
				aria-selected="false"
				tabindex="-1"
			><?php esc_html_e( 'Woocommerce', 'hustle' ); ?></button>

		</div>

	</div>

	<?php
	$content = ob_get_clean();
	$body = array(
		'classes' => 'sui-spacing-bottom--0',
		'content' => $content,
	);
}

ob_start();
?>
<div class="sui-box-selectors sui-box-selectors-col-2" style="margin-top: 0; margin-bottom: 0;">

<ul class="sui-spacing-slim">

	<?php
		// Divide before CTP and after that.
		$first_conditions = array(
			'posts' => __( 'Posts', 'hustle' ),
			'pages' => __( 'Pages', 'hustle' ),
		);
		$last_conditions  = array(
			'categories'               => __( 'Categories', 'hustle' ),
			'tags'                     => __( 'Tags', 'hustle' ),
			'archive_pages'            => __( 'Archive Pages', 'hustle' ),
			'wp_conditions'            => __( 'Static Pages', 'hustle' ),
			'user_roles'               => __( 'User Roles', 'hustle' ),
			'page_templates'           => __( 'Page Templates', 'hustle' ),
			'visitor_device'           => __( 'Visitor\'s Device', 'hustle' ),
			'on_browser'               => __( 'Visitor\'s Browser', 'hustle' ),
			'visitor_logged_in_status' => __( 'Logged in status', 'hustle' ),
			'visitor_country'          => __( 'Visitor\'s Country', 'hustle' ),
			'source_of_arrival'        => __( 'Source of Arrival', 'hustle' ),
			'from_referrer'            => __( 'Referrer', 'hustle' ),
			'on_url'                   => __( 'Specific URL', 'hustle' ),
			'user_registration'        => __( 'After Registration', 'hustle' ),
			'shown_less_than'          => __( 'Number of times visitor has seen', 'hustle' ),
			'visitor_commented'        => __( 'Visitor Commented Before', 'hustle' ),
			'cookie_set'               => __( 'Browser Cookie', 'hustle' ),
		);
		$conditions       = array_merge( $first_conditions, $post_types, $last_conditions );

		if ( Opt_In_Utils::is_woocommerce_active() ) {
			// Devide before CTP and after that.
			$first_wc_conditions = array(
				'wc_pages' => __( 'All Woocommerce Pages', 'hustle' ),
			);
			$last_wc_conditions  = array(
				'wc_categories'    => __( 'WooCommerce Categories', 'hustle' ),
				'wc_tags'          => __( 'WooCommerce Tags', 'hustle' ),
				'wc_archive_pages' => __( 'WooCommerce Archives', 'hustle' ),
				'wc_static_pages'  => __( 'WooCommerce Static Pages', 'hustle' ),
			);
			$conditions          = array_merge( $first_wc_conditions, $conditions, $last_wc_conditions );
		}

		/**
		 * Visibility Conditions
		 *
		 * @since 4.1.0
		 *
		 * @param array $conditions Visibility Conditions.
		 */
		$conditions = apply_filters( 'hustle_visibility_condition_options', $conditions );

		foreach ( $conditions as $key => $label ) {
			?>
		<li class="<?php echo 'wc_' === substr( $key, 0, 3 ) || 'product' === $key ? 'wc' : 'general'; ?>_condition"><label for="hustle-condition--<?php echo esc_attr( $key ); ?>" class="sui-box-selector">
			<input type="checkbox"
				value="<?php echo esc_attr( $key ); ?>"
				name="visibility_options"
				id="hustle-condition--<?php echo esc_attr( $key ); ?>"
				class="hustle-visibility-condition-option" />
			<span><?php echo esc_html( $label ); ?></span>
		</label></li>

	<?php } ?>

</ul>

</div>

<?php
$after_body_content = ob_get_clean();

$attributes = array(
	'modal_id'           => 'visibility-options',
	'has_description'    => true,
	'modal_size'         => 'lg',

	'header'             => array(
		'classes'       => 'sui-content-center sui-spacing-top--60 sui-flatten',
		'title'         => __( 'Choose Conditions', 'hustle' ),
		'title_classes' => 'sui-lg',
		/* translators: module type in small caps and in singular */
		'description'   => sprintf( __( 'Choose the visibility conditions which you want to apply on the %s.', 'hustle' ), $smallcaps_singular ),
	),
	'body' => $body,
	'after_body_content' => $after_body_content,
	'footer'             => array(
		'classes' => 'sui-content-separated',
		'buttons' => array(
			array(
				'classes'  => 'sui-button-ghost',
				'text'     => __( 'Cancel', 'hustle' ),
				'is_close' => true,
			),
			array(
				'id'       => 'hustle-add-conditions',
				'has_load' => true,
				'text'     => __( 'Add Conditions', 'hustle' ),
			),
		),
	),
);

$this->render_modal( $attributes );