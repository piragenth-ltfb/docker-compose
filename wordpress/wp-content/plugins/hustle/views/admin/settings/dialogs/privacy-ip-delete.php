<?php
/**
 * Modal for deleting ips in the "privacy" tab.
 *
 * @package Hustle
 * @since 4.0.3
 */

ob_start();
?>
<label class="sui-label" style="margin-bottom: 5px;"><?php esc_html_e( 'Delete IP Addresses', 'hustle' ); ?></label>

<div class="sui-side-tabs">

	<div class="sui-tabs-menu">

		<label for="hustle-remove-ips--all" class="sui-tab-item active">
			<input type="radio"
				name="range"
				id="hustle-remove-ips--all"
				value="all"
				checked
			/>
			<?php esc_html_e( 'All IPs', 'hustle' ); ?>
		</label>

		<label for="hustle-remove-ips--range" class="sui-tab-item">
			<input type="radio"
			name="range"
			id="hustle-remove-ips--range"
			value="range"
			data-tab-menu="only-ips"
		/>
			<?php esc_html_e( 'Specific IPs Only', 'hustle' ); ?>
		</label>

	</div>

	<div class="sui-tabs-content">

		<div class="sui-tab-boxed"
			data-tab-content="only-ips">

			<label for="hustle-remove-specific-ips" class="sui-label"><?php esc_html_e( 'Delete Specific IPs', 'hustle' ); ?></label>

			<textarea name="ips"
				rows="16"
				placeholder="<?php esc_html_e( 'Enter your IP addresses here...', 'hustle' ); ?>"
				id="hustle-remove-specific-ips"
				class="sui-form-control"></textarea>

			<span class="sui-description" style="margin-bottom: 20px;"><?php esc_html_e( 'Type one IP address per line. Both IPv4 and IPv6 are supported. IP ranges are also accepted in format xxx.xxx.xxx.xxx-xxx.xxx.xxx.xxx.', 'hustle' ); ?></span>

		</div>

	</div>

</div>

<?php
$body_content = ob_get_clean();

$attributes = array(
	'modal_id'        => 'delete-ips',
	'has_description' => true,
	'modal_size'      => 'md',
	'sui_box_tag'     => 'form',
	'sui_box_id'      => 'hustle-delete-ip-form',

	'header'          => array(
		'classes'       => 'sui-flatten sui-content-center sui-spacing-top--60',
		'title'         => __( 'Delete IP Addresses', 'hustle' ),
		'title_classes' => 'sui-lg',
		'description'   => __( 'Choose the IP addresses you want to delete from your database permanently. Note that this will only remove the IP addresses from the database leaving rest of the tracking data intact.', 'hustle' ),
	),
	'body'            => array(
		'content' => $body_content,
	),
	'footer'          => array(
		'classes' => 'sui-content-separated',
		'buttons' => array(
			array(
				'classes'  => 'sui-button-ghost',
				'text'     => __( 'Cancel', 'hustle' ),
				'is_close' => true,
			),
			array(
				'id'         => 'hustle-delete-ips-submit',
				'classes'    => 'sui-button-red sui-button-ghost hustle-delete',
				'icon'       => 'trash',
				'has_load'   => true,
				'text'       => __( 'Delete IP Addresses', 'hustle' ),
				'attributes' => array(
					'data-nonce'   => wp_create_nonce( 'hustle_remove_ips' ),
					'data-form-id' => 'hustle-delete-ip-form',
				),
			),
		),
	),
);

$this->render_modal( $attributes );
?>