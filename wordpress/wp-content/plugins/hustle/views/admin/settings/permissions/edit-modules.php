<?php
/**
 * Edit modules section under the "permissions" tab.
 *
 * @package Hustle
 * @since 4.1.0
 */

$modules_data     = Hustle_Module_Collection::instance()->get_all_paginated();
$filter           = $modules_data['filter'];
$modules          = $modules_data['modules'];
$modules_total    = $modules_data['total'];
$entries_per_page = $modules_data['entries_per_page'];
$modules_ids      = array();

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Edit Existing Modules', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Choose the user roles which can edit the existing modules.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<?php
		// TABLE: Modules.
		$filtered = ! empty( $filter['role'] ) && 'any' !== $filter['role'] || ! empty( $filter['q'] )
				|| 4 > count( $filter['types'] ) && ! empty( $filter['types'] );

		if ( 0 === count( $modules ) && ! $filtered ) {
			?>

			<?php
			$this->get_html_for_options(
				array(
					array(
						'type'  => 'inline_notice',
						'icon'  => 'info',
						'value' => esc_html__( "You haven't created any module yet.", 'hustle' ),
					),
				)
			);
			?>

			<?php
		} else {

			// PAGINATION: Structure.

			$this->render(
				'admin/commons/pagination',
				array(
					'total'            => $modules_total,
					'entries_per_page' => $entries_per_page,
					'filterclass'      => 'sui-pagination-open-filter',
					'filter'           => $filter,
					'section'          => 'permissions',
				)
			);

			// PAGINATION: Filter.
			$values = array(
				'popup'          => __( 'Pop-up', 'hustle' ),
				'slidein'        => __( 'Slide-in', 'hustle' ),
				'embedded'       => __( 'Embed', 'hustle' ),
				'social_sharing' => __( 'Share', 'hustle' ),
			);
			?>

			<form method="get" class="sui-pagination-filter">

				<input type="hidden" name="page" value="hustle_settings" />
				<input type="hidden" name="section" value="permissions" />

				<?php // FILTER: Module Type. ?>
				<div class="sui-row">

					<div class="sui-col-12">

						<div class="sui-form-field">

							<label class="sui-label"><?php esc_html_e( 'Module type', 'hustle' ); ?></label>

							<?php foreach ( $values as $value => $module ) { ?>

								<label class="sui-checkbox">
									<input type="checkbox"
										name="filter[types][]"
										value="<?php echo esc_attr( $value ); ?>"
										<?php echo empty( $filter['types'] ) || in_array( $value, $filter['types'], true ) ? ' checked="checked"' : ''; ?>
										/>
									<span aria-hidden="true"></span>
									<span><?php echo esc_html( $module ); ?></span>
								</label>

							<?php } ?>

						</div>

					</div>

				</div>

				<?php // FILTER: Keyword. ?>
				<div class="sui-row">

					<div class="sui-col-12">

						<div class="sui-form-field">

							<label for="hustle-filter-keyword" class="sui-label"><?php esc_html_e( 'Module name has keyword', 'hustle' ); ?></label>

							<div class="sui-control-with-icon">

								<input type="text"
									name="filter[q]"
									placeholder="<?php esc_html_e( 'E.g. Discount', 'hustle' ); ?>"
									value="<?php echo esc_attr( isset( $filter['q'] ) ? esc_attr( $filter['q'] ) : '' ); ?>"
									id="hustle-filter-keyword"
									class="sui-form-control" />

								<span class="sui-icon-magnifying-glass-search" aria-hidden="true"></span>

							</div>

						</div>

					</div>

				</div>

				<?php
				// FILTER(S): Role and Sort.
				?>
				<div class="sui-row">

					<?php // FILTER: Role Assigned. ?>
					<div class="sui-col-md-6">

						<div class="sui-form-field">

							<label class="sui-label"><?php esc_html_e( 'Use role assigned for editing', 'hustle' ); ?></label>

							<select name="filter[role]" id="hustle-select-filter-role">
								<option value="any"><?php esc_html_e( 'Any', 'hustle' ); ?></option>
								<?php
								foreach ( $roles as $value => $label ) {
									if ( Opt_In_Utils::is_admin_role( $value ) ) {
										continue;
									}
									printf(
										'<option value="%s" %s>%s</option>',
										esc_attr( $value ),
										isset( $filter['role'] ) && $filter['role'] === $value ? 'selected="selected"' : '',
										esc_html( $label )
									);
								}
								?>
							</select>

						</div>

					</div>

					<?php // FILTER: Sort By. ?>
					<div class="sui-col-md-6">

						<div class="sui-form-field">

							<label class="sui-label"><?php esc_html_e( 'Sort by', 'hustle' ); ?></label>

							<select name="filter[sort]" id="hustle-select-filter-sort">
								<?php
								$values = array(
									'module_name' => __( 'Name', 'hustle' ),
									'module_id'   => __( 'Id', 'hustle' ),
									'module_type' => __( 'Type', 'hustle' ),
								);

								foreach ( $values as $value => $label ) {
									printf(
										'<option value="%s" %s>%s</option>',
										esc_attr( $value ),
										isset( $filter['sort'] ) && $filter['sort'] === $value ? 'selected="selected"' : '',
										esc_html( $label )
									);
								}
								?>
							</select>

						</div>

					</div>

				</div>

				<?php // FILTER: Footer. ?>
				<div class="sui-filter-footer">

					<div class="sui-actions-right">

						<input type="submit"
							value="<?php esc_attr_e( 'Apply', 'hustle' ); ?>"
							class="sui-button" />

					</div>

				</div>

			</form>

			<?php
			if ( 0 === count( $modules ) && $filtered ) {

				$this->get_html_for_options(
					array(
						array(
							'type'  => 'inline_notice',
							'icon'  => 'info',
							'value' => esc_html__( "You don't have any module corresponding to these filter parameters.", 'hustle' ),
						),
					)
				);

			} else {
				?>
				<table class="sui-table">

					<thead>
						<tr>
							<th><?php esc_html_e( 'Module', 'hustle' ); ?></th>
							<th><?php esc_html_e( 'User Role', 'hustle' ); ?></th>
						</tr>
					</thead>

					<tbody>

					<?php
					foreach ( $modules as $module ) :
						$modules_ids[] = $module->module_id;
						?>

						<tr data-module-id="<?php echo esc_attr( $module->module_id ); ?>">
							<td class="sui-table-item-title"><span class="sui-icon-<?php echo esc_attr( $module->module_type ); ?>"></span> <?php echo esc_html( $module->module_name ); ?></td>
							<td><select
									form="<?php echo esc_attr( $form_id ); ?>"
									class="sui-select-sm sui-select"
									name="modules[<?php echo esc_attr( $module->module_id ); ?>][]"
									multiple
								>
								<?php
								$current = $module->get_edit_roles();
								foreach ( $roles as $value => $label ) {
									$admin = Opt_In_Utils::is_admin_role( $value );
									printf(
										'<option value="%s" %s %s>%s</option>',
										esc_attr( $value ),
										selected( in_array( $value, $current, true ) || $admin, true, false ),
										disabled( $admin, true, false ),
										esc_html( $label )
									);
								}
								?>
							</td></select>
						</tr>

					<?php endforeach; ?>

					<?php if ( ! empty( $modules_ids ) ) : ?>
						<input
							name="modules_ids"
							type="hidden"
							value="<?php echo esc_attr( join( ',', $modules_ids ) ); ?>"
							form="<?php echo esc_attr( $form_id ); ?>"
						>
					<?php endif; ?>

					</tbody>

				</table>
			<?php } ?>

		<?php } ?>

	</div>

</div>