<?php
/**
 * Summary section.
 *
 * @package Hustle
 * @since 4.0.0
 */

// Summary details.
$emails_collected = Hustle_Entry_Model::get_total_entries_count();
$active_app       = 'activecampaign';
$active_list      = 'Weekly Newsletter';
$active_app_name  = 'ActiveCampaign';
$active_icon      = self::$plugin_url . 'inc/providers/' . $active_app . '/images/icon.png';

// Summary list (table).
$providers      = Hustle_Provider_Utils::get_registered_providers_list();
$available_apps = count( $providers );
$connected_apps = 0;
foreach ( $providers as $slug => $data ) {
	if ( $data['is_connected'] ) {
		$connected_apps++;
	}
}
?>
<div class="<?php echo esc_attr( implode( ' ', $sui['summary']['classes'] ) ); ?>">
	<div class="sui-summary-image-space" aria-hidden="true" style="<?php echo esc_attr( $sui['summary']['style'] ); ?>"></div>
	<div class="sui-summary-segment">
		<div class="sui-summary-details">
			<span class="sui-summary-large"><?php echo $emails_collected; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<span class="sui-summary-sub"><?php esc_html_e( 'Emails Collected', 'hustle' ); ?></span>
			<?php if ( 0 === $emails_collected ) { ?>
				<span class="sui-summary-detail"><?php esc_html_e( 'None', 'hustle' ); ?></span>
			<?php } else { ?>
				<!--<span class="sui-summary-detail">
					<img
						width="20"
						height="20"
						src="<?php echo esc_url( $active_icon ); ?>"
						alt="<?php echo esc_html( $active_app_name ); ?>"
						aria-hidden="true"
					/>
					<?php echo esc_html( $active_list ); ?>
				</span>-->
			<?php } ?>
			<!--<span class="sui-summary-sub"><?php esc_html_e( 'Most Active Lists for an App', 'hustle' ); ?></span>-->
		</div>
	</div>
	<div class="sui-summary-segment">
		<ul class="sui-list">
			<li>
				<span class="sui-list-label"><?php esc_html_e( 'Available Apps', 'hustle' ); ?></span>
				<span class="sui-list-detail"><?php echo esc_html( $available_apps ); ?></span>
			</li>
			<li>
				<span class="sui-list-label"><?php esc_html_e( 'Connected Apps', 'hustle' ); ?></span>
				<?php
				if ( 0 === $connected_apps ) {
					echo '<span class="sui-list-detail">0</span>';
				} else {
					echo '<span class="sui-list-detail">' . esc_html( $connected_apps ) . '</span>';
				}
				?>
			</li>
		</ul>
	</div>
</div>