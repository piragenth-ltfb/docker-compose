<?php
/**
 * Underscore template for the schedule section.
 *
 * @package Hustle
 * @since 4.2.0
 */

?>
<script type="text/template" id="hustle-schedule-row-tpl">

	<# if ( '1' === is_schedule ) { #>

		<#
			const startDate = strings.startDate ? strings.startDate : '<?php esc_html_e( 'Immediately', 'hustle' ); ?>',
				startTime = strings.startTime ? strings.startTime : '',
				endDate   = strings.endDate ? strings.endDate : '<?php esc_html_e( 'Forever', 'hustle' ); ?>',
				endTime   = strings.endTime ? strings.endTime : ''
				weekdays  = strings.activeDays ? strings.activeDays : '',
				weektime  = strings.activeTime ? strings.activeTime : '';
		#>

		<div id="hustle-schedule-notice" class="sui-notice hui-notice-schedule {{ hasFinished ? 'sui-notice-error' : 'sui-notice-success' }}">

			<div class="sui-notice-content">

				<div class="sui-notice-message">

					<span class="sui-notice-icon sui-icon-clock sui-sm" aria-hidden="true"></span>

					<p><strong>{{ startDate }} <small>{{ startTime }}</small> - {{ endDate }} <small>{{ endTime }}</small></strong></p>

					<# if ( strings.activeDays && strings.activeTime ) { #>

						<?php /* translators: 1. opening 'strong' tag, 2. closing 'strong' tag, 3. week days, 4. week time */ ?>
						<p><small><?php printf( esc_html__( 'Active on %1$s%3$s%2$s between %1$s%4$s%2$s', 'hustle' ), '<strong>', '</strong>', '{{ weekdays }}', '{{ weektime }}' ); ?></small></p>

					<# } else { #>

						<# if ( strings.activeDays && ! strings.activeTime ) { #>

							<?php /* translators: 1. opening 'strong' tag, 2. closing 'strong' tag, 3. week days*/ ?>
							<p><small><?php printf( esc_html__( 'Active on %1$s%3$s%2$s', 'hustle' ), '<strong>', '</strong>', '{{ weekdays }}' ); ?></small></p>

						<# } else if ( ! strings.activeDays && strings.activeTime ) { #>

							<?php /* translators: 1. opening 'strong' tag, 2. closing 'strong' tag, 3. week time */ ?>
							<p><small><?php printf( esc_html__( 'Active %1$sEVERYDAY%2$s between %1$s%3$s%2$s', 'hustle' ), '<strong>', '</strong>', '{{ weektime }}' ); ?></small></p>

						<# } else { #>

							<# if ( strings.startDate || strings.endDate ) { #>
								<?php /* translators: 1. opening 'strong' tag, 2. closing 'strong' tag */ ?>
								<p><small><?php printf( esc_html__( 'Active %1$sEVERYDAY%2$s', 'hustle' ), '<strong>', '</strong>' ); ?></small></p>
							<# } #>

						<# } #>

					<# } #>

				</div>

				<div class="sui-notice-actions">

					<button id="hustle-schedule-focus" class="hui-notice-action sui-button-icon sui-tooltip sui-tooltip-top-right hustle-button-open-schedule-dialog" data-tooltip="<?php esc_html_e( 'Update or delete schedule', 'hustle' ); ?>">
						<span class="sui-icon-widget-settings-config" aria-hidden="true"></span>
						<span class="sui-screen-reader-text"><?php esc_html_e( 'Click to edit schedule settings', 'hustle' ); ?></span>
					</button>

				</div>

			</div>

		</div>

		<div id="hustle-will-be-shown-again" role="alert" {{ hasFinished ? '' : 'style="display: none;"' }}>
			<p class="sui-error-message">
				<# if ( hasFinished ) { #>
					<?php /* translators: module type in small caps and in singular */ ?>
					<?php printf( esc_html__( "Schedule for this pop-up is over, and your visitors won't see the %s anymore until you update or delete the schedule.", 'hustle' ), esc_html( $smallcaps_singular ) ); ?>
				<# } #>
			</p>
		</div>

	<# } else { #>

		<button id="hustle-schedule-focus" class="sui-button sui-button-ghost hustle-button-open-schedule-dialog">
			<span class="sui-icon-clock" aria-hidden="true"></span>
			<?php esc_html_e( 'Schedule', 'hustle' ); ?>
		</button>
	<# } #>

</script>