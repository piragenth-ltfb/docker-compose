<?php
/**
 * Title section.
 *
 * @var Hustle_Layout_Helper $this
 *
 * @package Hustle
 * @since 4.0.0
 */

// ELEMENT: Pagination (Mobile).
?>
<div class="hui-pagination hui-pagination-mobile">
	<?php $this->render( 'admin/email-lists/pagination-mobile' ); ?>
</div>

<div class="sui-box">

	<?php
	$items = count( $this->admin->entries_iterator() );
	// Filter Bar.
	$this->render(
		'admin/email-lists/pagination-desktop',
		array( 'is_bottom' => false )
	);
	?>

	<table class="hui-table-entries sui-table sui-table-flushed<?php echo $items ? ' sui-accordion' : ''; ?>">

		<thead>

			<tr>

				<th class="hui-column-id">
					<label for="hustle-check-all-top" class="sui-checkbox sui-checkbox-sm">
						<input type="checkbox" id="hustle-check-all-top" class="hustle-check-all">
						<span aria-hidden="true"></span>
						<span><?php esc_html_e( 'Id', 'hustle' ); ?></span>
					</label>
				</th>

				<?php
				$fields_mappers = $this->admin->get_fields_mappers();
				// Start from 1, since first one is ID.
				// Length is 3 because we only display the 4 common columns.
				$fields_headers = array_slice( $fields_mappers, 1, 3 );

				$fields_left = count( $fields_mappers ) - count( $fields_headers );

				foreach ( $fields_headers as $header ) :
					?>

					<th <?php echo isset( $header['class'] ) ? ' class="' . esc_attr( $header['class'] ) . '"' : ''; ?>><?php echo esc_html( $header['label'] ); ?></th>

				<?php endforeach; ?>

				<th data-num-hidden-fields="<?php echo ( $fields_left >= 0 ? $fields_left : 0 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"></th>

			</tr>

		</thead>

		<tbody class="hustle-list">

			<?php if ( $no_local_list ) { ?>
				<tr><td role="alert" class="hui-entries-alert" colspan="5">
					<p>
						<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
						<span class="sui-screen-reader-text"><?php echo $add_local_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</p>
				</td></tr>
			<?php } ?>

			<?php
			if ( $items ) :

				foreach ( $this->admin->entries_iterator() as $entry ) :

					$entry_id    = $entry['id'];
					$db_entry_id = $entry['entry_id'];

					$summary       = $entry['summary'];
					$summary_items = $summary['items'];

					$detail       = $entry['detail'];
					$detail_items = $detail['items'];

					$addons = $entry['addons'];
					?>

					<tr class="sui-accordion-item" data-entry-id="<?php echo esc_attr( $db_entry_id ); ?>">

						<?php foreach ( $summary_items as $key => $summary_item ) : ?>

							<?php if ( 1 === $summary_item['colspan'] ) : ?>

								<td class="hui-column-id sui-accordion-item-title">

									<label class="sui-checkbox sui-checkbox-sm">
										<input
											type="checkbox"
											name="ids[]"
											value="<?php echo esc_attr( $db_entry_id ); ?>"
											id="email-entry-<?php echo esc_attr( $db_entry_id ); ?>"
											class="hustle-listing-checkbox"
										/>
										<span aria-hidden="true"></span>
										<?php /* translators: entry id */ ?>
										<span><?php printf( esc_html__( '%2$sSelect entry number%3$s%1$s', 'hustle' ), esc_attr( $db_entry_id ), '<span class="sui-screen-reader-text">', '</span>' ); ?></span>
									</label>

								</td>

							<?php else : ?>

								<?php if ( 'hui-column-date' === $summary_item['class'] ) { ?>
									<td class="hui-column-date">
										<?php echo esc_html( $summary_item['value'] ); ?>
										<span class="sui-accordion-open-indicator" aria-hidden="true">
											<span class="sui-icon-chevron-down" aria-hidden="true"></span>
											<span class="sui-screen-reader-text"><?php esc_html_e( 'Click to open', 'hustle' ); ?></span>
										</span>
									</td>
								<?php } else { ?>
									<td
									<?php
									if ( ! empty( $summary_item['class'] ) ) {
										echo ' class="' . esc_attr( $summary_item['class'] ) . '"';}
									?>
									><?php echo esc_html( $summary_item['value'] ); ?></td>
								<?php } ?>

							<?php endif; ?>

							<?php if ( ! $summary['num_fields_left'] && ( count( $summary_items ) - 1 ) === $key ) : ?>

								<td><span class="hui-entry-button sui-accordion-open-indicator">
									<span class="sui-icon-chevron-down" aria-hidden="true"></span>
									<span class="sui-screen-reader-text"><?php esc_html_e( 'Click to open', 'hustle' ); ?></span>
								</span></td>

							<?php endif; ?>

						<?php endforeach; ?>

						<?php if ( $summary['num_fields_left'] ) : ?>

							<?php /* translators: remaining fields */ ?>
							<td><?php printf( esc_html__( '+ %s other fields', 'hustle' ), esc_html( $summary['num_fields_left'] ) ); ?>
							<span class="sui-accordion-open-indicator">
								<span class="sui-icon-chevron-down" aria-hidden="true"></span>
								<span class="sui-screen-reader-text"><?php esc_html_e( 'Click to open', 'hustle' ); ?></span>
							</span></td>

						<?php endif; ?>

					</tr>

					<tr class="sui-accordion-item-content">

						<td colspan="<?php echo esc_attr( $detail['colspan'] ); ?>">

							<div class="sui-box">

								<div class="sui-box-body">

									<h2>#<?php echo esc_html( $db_entry_id ); ?></h2>

									<div class="sui-box-settings-row sui-flushed">

										<div class="sui-box-settings-col-2">

											<ul class="hui-list">

												<?php foreach ( $detail_items as $detail_item ) : ?>

													<li>
														<strong><?php echo esc_html( $detail_item['label'] ); ?></strong>

														<?php $sub_entries = $detail_item['sub_entries']; ?>

														<?php if ( empty( $sub_entries ) ) { ?>
															<span class="sui-list-detail"
																style="margin-top: 0;">
																<?php echo ( $detail_item['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
															</span>
															<?php
														} else {
															foreach ( $sub_entries as $sub_entry ) {
																?>
																<div class="sui-form-field">
																	<span class="sui-settings-label"><?php echo esc_html( $sub_entry['label'] ); ?></span>
																	<span class="sui-list-detail"><?php echo ( $sub_entry['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
																</div>
																<?php
															}
														}
														?>


													</li>

												<?php endforeach; ?>

											</ul>

										</div>

									</div>

									<?php if ( ! empty( $addons ) ) : ?>

										<div class="sui-box-settings-row">

											<div class="sui-box-settings-col-2">

												<h3><?php esc_html_e( 'Active Integrations', 'hustle' ); ?></h3>

												<p><?php esc_html_e( 'You can check if the data is submitted to your active integrations and the information returned by the integrations if any.', 'hustle' ); ?></p>

												<table class="sui-table sui-accordion hui-table-entries-app">

													<thead>

														<tr>

															<th class="hui-column-name"><?php esc_html_e( 'Integration Name', 'hustle' ); ?></th>
															<th class="hui-column-data"><?php esc_html_e( 'Data sent to integration', 'hustle' ); ?></th>

														</tr>

													</thead>

													<tbody>

														<?php
														$num        = 0;
														$num_addons = count( $addons );

														foreach ( $addons as $addon ) :
															?>

															<tr class="sui-accordion-item<?php echo ( ++$num === $num_addons ) ? ' sui-table-item-last' : ''; ?> <?php echo ( $addon['summary']['data_sent'] ) ? 'sui-success' : 'sui-error'; ?>">

																<td class="hui-column-name sui-accordion-item-title" style="padding-bottom: 5px;">

																	<img
																		src="<?php echo esc_attr( $addon['summary']['icon'] ); ?>"
																		aria-hidden="true"
																	/>

																	<span><?php echo esc_attr( $addon['summary']['name'] ); ?></span>

																</td>

																<td class="hui-column-data" style="padding-bottom: 5px;">

																	<div class="hui-column-data--alignment">

																		<div class="hui-column-data--left"><?php $addon['summary']['data_sent'] ? esc_html_e( 'Yes', 'hustle' ) : esc_html_e( 'No', 'hustle' ); ?></div>

																		<div class="hui-column-data--right">

																			<a href="<?php echo esc_url( $wizard_page ); ?>" class="sui-button sui-button-ghost sui-accordion-item-action">
																				<span class="sui-icon-wrench-tool" aria-hidden="true"></span>
																				<?php esc_html_e( 'Configure', 'hustle' ); ?>
																			</a>

																			<button class="sui-button-icon sui-accordion-open-indicator">
																				<span class="sui-icon-chevron-down" aria-hidden="true"></span>
																				<span class="sui-screen-reader-text"><?php esc_html_e( 'Click to open', 'hustle' ); ?></span>
																			</button>

																		</div>

																	</div>

																</td>

															</tr>

															<tr class="sui-accordion-item-content <?php echo ( $addon['summary']['data_sent'] ) ? 'sui-success' : 'sui-error'; ?>">

																<td colspan="2">

																	<div class="sui-box">

																		<div class="sui-box-body">

																			<ul class="hui-list">

																				<?php foreach ( $addon['detail'] as $item ) : ?>

																					<li>
																						<strong><?php echo $item['label']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
																						<span><?php echo $item['value']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
																					</li>

																				<?php endforeach; ?>

																			</ul>

																		</div>

																		<div class="sui-box-footer hui-hidden-desktop">

																			<a href="<?php echo esc_url( $wizard_page ); ?>" class="sui-button sui-button-ghost sui-accordion-item-action">
																				<span class="sui-icon-wrench-tool" aria-hidden="true"></span>
																				<?php esc_html_e( 'Configure', 'hustle' ); ?>
																			</a>

																		</div>

																	</div>

																</td>

															</tr>

														<?php endforeach; ?>

													</tbody>

												</table>

											</div>

										</div>

									<?php endif; ?>

								</div>

								<div class="sui-box-footer">

									<button class="sui-button sui-button-red sui-button-ghost hustle-delete-entry-button"
										data-id="<?php echo esc_attr( $db_entry_id ); ?>"
										data-nonce=<?php echo esc_attr( wp_create_nonce( 'hustle_entries_request' ) ); ?>
										data-title="<?php esc_html_e( 'Delete Entry', 'hustle' ); ?>"
										data-description="<?php esc_html_e( 'Are you sure you wish to permanently delete this entry?', 'hustle' ); ?>"
									>
										<span class="sui-icon-trash" aria-hidden="true"></span>
										<?php esc_html_e( 'Delete', 'hustle' ); ?>
									</button>

								</div>

							</div>

						</td>

					</tr>

				<?php endforeach; ?>

			<?php else : ?>

				<tr>
					<td class="hui-column-notice" colspan="<?php echo count( $fields_headers ) + 2; ?>">

						<?php
						$notice_options = array(
							array(
								'type'  => 'inline_notice',
								'class' => 'sui-notice-error',
								'icon'  => 'info',
								'value' => esc_html__( 'No entries were found.', 'hustle' ),
							),
						);
						$this->get_html_for_options( $notice_options );
						?>

					</td>
				</tr>

			<?php endif; ?>

		</tbody>

	</table>

	<?php
	// Filter Bar.
	$this->render(
		'admin/email-lists/pagination-desktop',
		array(
			'actions_class' => 'hui-mobile-hidden',
			'is_bottom'     => true,
		)
	);
	?>

</div>