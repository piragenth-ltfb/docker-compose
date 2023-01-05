<?php
/**
 * Conditions section.
 *
 * @package Hustle
 * @since 4.0.0
 */

$image_1x = self::$plugin_url . 'assets/images/hustle-visibility.png';
$image_2x = self::$plugin_url . 'assets/images/hustle-visibility@2x.png';
?>

<?php // TEMPLATE: Visibility Group. ?>
<script id="hustle-visibility-group-box-tpl" type="text/template">

	<div id="hustle-visibility-group-{{ groupId }}" class="sui-box-builder">

		<div class="sui-box-builder-header">

			<div class="sui-builder-conditions">

				<div class="sui-builder-conditions-rule">
					<select
						name="show_or_hide_conditions"
						class="sui-select sui-select-sm sui-select-inline visibility-group-show-hide"
						data-width="110"
						data-group-attribute="show_or_hide_conditions"
						data-group-id="{{ groupId }}"
					>
						<option value="show" {{ _.selected( ( 'show' === show_or_hide_conditions ), true) }}><?php esc_html_e( 'Show', 'hustle' ); ?></option>
						<option value="hide" {{ _.selected( ( 'hide' === show_or_hide_conditions ), true) }}><?php esc_html_e( 'Hide', 'hustle' ); ?></option>
					</select>

					<span class="sui-builder-text"><?php esc_html_e( 'when', 'hustle' ); ?></span>

					<input type="hidden" name="filter_type" data-group-attribute="filter_type" data-group-id="{{ groupId }}" value="any">

					<select
						name="filter_type"
						class="sui-select sui-select-sm sui-select-inline visibility-group-filter-type"
						data-width="110"
						data-group-attribute="filter_type"
						data-group-id="{{ groupId }}"
					>
						<option value="all" {{ _.selected( ( 'all' === filter_type ), true) }}><?php esc_html_e( 'all', 'hustle' ); ?></option>
						<option value="any" {{ _.selected( ( 'any' === filter_type ), true) }}><?php esc_html_e( 'any', 'hustle' ); ?></option>
					</select>

					<span class="sui-builder-text"><?php esc_html_e( 'of the following conditions match.', 'hustle' ); ?></span>

				</div>

				<div class="sui-builder-conditions-actions">

					<button
						class="sui-button-icon sui-button-red hustle-remove-visibility-group"
						data-group-id="{{ groupId }}"
					>
						<span class="sui-icon-trash" aria-hidden="true"></span>
						<span class="sui-screen-reader-text"><?php esc_html_e( 'Delete visibility group', 'hustle' ); ?></span>
					</button>

				</div>

			</div>

			<?php if ( in_array( $module_type, array( 'social_sharing', 'embedded' ), true ) ) { ?>
				<div class="sui-builder-options sui-options-inline">

					<span class="sui-builder-text"><?php esc_html_e( 'Apply on', 'hustle' ); ?>
						<button class="sui-button-icon sui-tooltip sui-tooltip-constrained" data-tooltip="<?php esc_attr_e( 'Choose the display options to apply these visibility conditions on. Note that the visibility rules will only affect the options which are active on the Display Options page.', 'hustle' ); ?>" style="width: 22px; height: 22px;">
							<span class="sui-icon-info" aria-hidden="true"></span>
						</button>
					</span>

					<?php if ( 'social_sharing' === $module_type ) { ?>
						<label
							for="hustle-apply-on-float-{{ groupId }}"
							class="sui-checkbox sui-checkbox-sm"
						>
							<input
								type="checkbox"
								id="hustle-apply-on-float-{{ groupId }}"
								class="visibility-group-apply-on hustle-group-element"
								data-property="apply_on_floating"
								data-group-id="{{ groupId }}"
								{{ _.checked( apply_on_floating, true ) }}
							/>
							<span aria-hidden="true"></span>
							<span><?php esc_html_e( 'Floating Social', 'hustle' ); ?></span>
						</label>
					<?php } ?>

					<label
						for="hustle-apply-on-inline-{{ groupId }}"
						class="sui-checkbox sui-checkbox-sm"
					>
						<input
							type="checkbox"
							id="hustle-apply-on-inline-{{ groupId }}"
							class="visibility-group-apply-on hustle-group-element"
							data-property="apply_on_inline"
							data-group-id="{{ groupId }}"
							{{ _.checked( apply_on_inline, true ) }}
						/>
						<span aria-hidden="true"></span>
						<span><?php esc_html_e( 'Inline Content', 'hustle' ); ?></span>
					</label>

					<label
						for="hustle-apply-on-widget-{{ groupId }}"
						class="sui-checkbox sui-checkbox-sm"
					>
						<input
							type="checkbox"
							id="hustle-apply-on-widget-{{ groupId }}"
							class="visibility-group-apply-on hustle-group-element"
							data-property="apply_on_widget"
							data-group-id="{{ groupId }}"
							{{ _.checked( apply_on_widget, true ) }}
						/>
						<span aria-hidden="true"></span>
						<span><?php esc_html_e( 'Widget', 'hustle' ); ?></span>
					</label>

					<label
						for="hustle-apply-on-shortcode-{{ groupId }}"
						class="sui-checkbox sui-checkbox-sm"
					>
						<input
							type="checkbox"
							id="hustle-apply-on-shortcode-{{ groupId }}"
							class="visibility-group-apply-on hustle-group-element"
							data-property="apply_on_shortcode"
							data-group-id="{{ groupId }}"
							{{ _.checked( apply_on_shortcode, true ) }}
						/>
						<span aria-hidden="true"></span>
						<span><?php esc_html_e( 'Shortcode', 'hustle' ); ?></span>

						<?php /* translators: module type in small caps and in singular */ ?>
						<button class="sui-button-icon sui-tooltip sui-tooltip-constrained" data-tooltip="<?php printf( esc_attr__( 'By default, the shortcode displays your %1$s wherever you add it. However, you can apply visibility rules on your %1$s shortcode for better control. For example, you can use visibility rules to show your %1$s to logged-in users only or visitors from a specific country only.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?>" style="width: 22px; height: 22px;">
							<span class="sui-icon-info" aria-hidden="true"></span>
						</button>

					</label>

				</div>
			<?php } ?>

		</div>

		<div class="sui-box-builder-body">

			<div class="sui-builder-fields sui-accordion"></div>

			<button class="sui-button sui-button-dashed hustle-choose-conditions" data-group-id="{{ groupId }}">
				<span class="sui-icon-plus" aria-hidden="true"></span> <?php esc_html_e( 'Add Conditions', 'hustle' ); ?>
			</button>

			<div class="sui-box-builder-message-block">
				<?php /* translators: module type in small caps and in singular */ ?>
				<span class="sui-box-builder-message"><?php printf( esc_html__( 'No visibility condition added yet. Currently, your %s will appear everywhere on your website.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>

				<?php
				if ( ! $this->is_branding_hidden ) :
					echo $this->render_image_markup( $image_1x, $image_2x, 'sui-image sui-image-center' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				endif;
				?>

			</div>

		</div>

	</div>

</script>

<?php // TEMPLATE: Visibility Rule. ?>
<script id="hustle-visibility-rule-tpl" type="text/template">

	<div class="sui-accordion-item-header">

		<div class="sui-builder-field-label">
			<span>{{ title }}</span>
			<span class="sui-tag" style="margin-left: 10px;">{{ header }}</span>
		</div>

		<button
			class="sui-button-icon sui-button-red sui-hover-show sui-accordion-item-action hustle-remove-visibility-condition"
			data-group-id="{{ groupId }}"
			data-condition-id="{{ id }}"
		>
			<span class="sui-icon-trash" aria-hidden="true"></span>
			<span class="sui-screen-reader-text"><?php esc_html_e( 'Delete visibility rule', 'hustle' ); ?></span>
		</button>

		<span class="sui-builder-field-border sui-hover-show" aria-hidden="true"></span>

		<button class="sui-button-icon sui-accordion-open-indicator">
			<span class="sui-icon-chevron-down" aria-hidden="true"></span>
			<span class="sui-screen-reader-text"><?php esc_html_e( 'Open visibility rule', 'hustle' ); ?></span>
		</button>

	</div>

	<div class="sui-accordion-item-body">{{{ body }}}</div>

</script>

<?php // RULE: Posts. ?>
<script id="hustle-visibility-rule-tpl--posts" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose posts', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-filter_type-posts-except"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-filter_type-posts"
					value="except"
					id="{{ groupId }}-{{ type }}-filter_type-posts-except"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'except' ) }} />
				<?php esc_html_e( 'All posts except', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-filter_type-posts-only"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-filter_type-posts"
					value="only"
					id="{{ groupId }}-{{ type }}-filter_type-posts-only"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'only' ) }} />
				<?php esc_html_e( 'Only these posts', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">

				<select name=""
					id="{{ groupId }}-{{ type }}-filter_type-posts"
					class="sui-select sui-select-lg hustle-select-ajax"
					multiple="multiple"
					data-val="{{ posts }}"
					data-attribute="posts"
					data-placeholder="<?php esc_html_e( 'Start typing the name of posts...', 'hustle' ); ?>">

					<# _.each( optinVars.posts, function( post ) { #>
						<option value="{{ post.id }}"
							{{ _.selected( _.contains( posts, post.id.toString() ), true ) }}>
							{{ post.text }}
						</option>
					<# }); #>

				</select>

			</div>

		</div>

	</div>

</script>

<?php // RULE: Pages. ?>
<script id="hustle-visibility-rule-tpl--pages" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose pages', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-filter_type-pages-except"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-filter_type-pages"
					value="except"
					id="{{ groupId }}-{{ type }}-filter_type-pages-except"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'except' ) }} />
				<?php esc_html_e( 'All pages except', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-filter_type-pages-only"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-filter_type-pages"
					value="only"
					id="{{ groupId }}-{{ type }}-filter_type-pages-only"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'only' ) }} />
				<?php esc_html_e( 'Only these pages', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">

				<select name=""
					id="{{ groupId }}-{{ type }}-filter_type-pages"
					class="sui-select sui-select-lg hustle-select-ajax"
					multiple="multiple"
					data-val="{{ pages }}"
					data-attribute="pages"
					data-placeholder="<?php esc_html_e( 'Start typing the name of pages...', 'hustle' ); ?>">

					<# _.each( optinVars.pages, function( page ) { #>
						<option value="{{ page.id }}"
							{{ _.selected( _.contains( pages, page.id.toString() ), true ) }}>
							{{ page.text }}
						</option>
					<# }); #>

				</select>

			</div>

		</div>

	</div>

</script>

<?php // RULE: CPT. ?>
<script id="hustle-visibility-rule-tpl--post_type" type="text/template">
	<?php /* translators: custom post type label */ ?>
	<label class="sui-label"><?php printf( esc_html__( 'Choose %s', 'hustle' ), '{{ postTypeLabel }}' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-filter_type-{{postType}}-except"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-filter_type-{{postType}}"
					value="except"
					id="{{ groupId }}-{{ type }}-filter_type-{{postType}}-except"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'except' ) }} />
				<?php /* translators: custom post type label */ ?>
				<?php printf( esc_html__( 'All %s except', 'hustle' ), '{{ postTypeLabel }}' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-filter_type-{{postType}}-only"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-filter_type-{{postType}}"
					value="only"
					id="{{ groupId }}-{{ type }}-filter_type-{{postType}}-only"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'only' ) }} />
				<?php /* translators: custom post type label */ ?>
				<?php printf( esc_html__( 'Only these %s', 'hustle' ), '{{ postTypeLabel }}' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">

				<select name=""
					id="{{ groupId }}-{{ type }}-filter_type-{{postType}}"
					class="sui-select sui-select-lg hustle-select-ajax"
					multiple="multiple"
					data-val="{{ selected_cpts }}"
					data-attribute="selected_cpts"
					<?php /* translators: custom post type label */ ?>
					data-placeholder="<?php printf( esc_html__( 'Start typing the name of %s...', 'hustle' ), '{{ postTypeLabel }}' ); ?>">

					<# _.each( optinVars.post_types[postType].data, function( post ) { #>
						<option value="{{ post.id }}"
							{{ _.selected( _.contains( selected_cpts, post.id.toString() ), true ) }}>
							{{ post.text }}
						</option>
					<# }); #>

				</select>

			</div>

		</div>

	</div>

</script>

<?php // RULE: Categories. ?>
<script id="hustle-visibility-rule-tpl--categories" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose categories', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-filter_type-categories-except"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-filter_type-categories"
					value="except"
					id="{{ groupId }}-{{ type }}-filter_type-categories-except"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'except' ) }} />
				<?php esc_html_e( 'All categories except', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-filter_type-categories-only"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-filter_type-categories"
					value="only"
					id="{{ groupId }}-{{ type }}-filter_type-categories-only"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'only' ) }} />
				<?php esc_html_e( 'Only these categories', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">

				<select name=""
					id="{{ groupId }}-{{ type }}-filter_type-categories"
					class="sui-select sui-select-lg hustle-select-ajax"
					multiple="multiple"
					data-val="{{ categories }}"
					data-attribute="categories"
					data-placeholder="<?php esc_html_e( 'Start typing the name of categories...', 'hustle' ); ?>">

					<# _.each( optinVars.cats, function( cat ) { #>
						<option value="{{ cat.id }}" {{ _.selected( _.contains( categories, cat.id.toString() ), true ) }}>
							{{ cat.text }}
						</option>
					<# } ); #>

				</select>

				<span class="sui-description"><?php esc_html_e( 'Note that this condition affects the posts with selected categories and doesn\'t include category archives.', 'hustle' ); ?></span>

			</div>

		</div>

	</div>

</script>

<?php // RULE: Tags. ?>
<script id="hustle-visibility-rule-tpl--tags" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose tags', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-filter_type-tags-except"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-filter_type-tags"
					value="except"
					id="{{ groupId }}-{{ type }}-filter_type-tags-except"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'except' ) }} />
				<?php esc_html_e( 'All tags except', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-filter_type-tags-only"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-filter_type-tags"
					value="only"
					id="{{ groupId }}-{{ type }}-filter_type-tags-only"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'only' ) }} />
				<?php esc_html_e( 'Only these tags', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">

				<select name=""
					id="{{ groupId }}-{{ type }}-filter_type-tags"
					class="sui-select sui-select-lg hustle-select-ajax"
					multiple="multiple"
					data-val="{{ tags }}"
					data-attribute="tags"
					data-placeholder="<?php esc_html_e( 'Start typing the name of tags...', 'hustle' ); ?>">

					<# _.each( optinVars.tags, function( tag ) {  #>
						<option value="{{ tag.id }}" {{ _.selected( _.contains( tags, tag.id.toString() ), true ) }}>
							{{ tag.text }}
						</option>
					<# } ); #>

				</select>

				<span class="sui-description"><?php esc_html_e( 'Note that this condition affects the posts with selected tags and doesn\'t include tag archives.', 'hustle' ); ?></span>

			</div>

		</div>

	</div>

</script>

<?php // RULE: Visitor's logged in status. ?>
<script id="hustle-visibility-rule-tpl--visitor_logged_in_status" type="text/template">

	<label class="sui-label"><?php esc_html_e( "Visitor's status", 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-visitor-logged-status--logged_in"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-show_to"
					value="logged_in"
					id="{{ groupId }}-visitor-logged-status--logged_in"
					data-attribute="show_to"
					{{ _.checked( show_to, 'logged_in' ) }} />
				<?php esc_html_e( 'Logged in', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-visitor-logged-status--logged_out"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-show_to"
					value="logged_out"
					id="{{ groupId }}-visitor-logged-status--logged_out"
					data-attribute="show_to"
					{{ _.checked( show_to, 'logged_out' ) }} />
				<?php esc_html_e( 'Logged out', 'hustle' ); ?>
			</label>

		</div>

	</div>

</script>

<?php // RULE: Number of times visitor has seen. ?>
<script id="hustle-visibility-rule-tpl--shown_less_than" type="text/template">

<div class="sui-row">

	<div class="sui-col">

		<label class="sui-label"><?php esc_html_e( 'Condition', 'hustle' ); ?></label>

		<div class="sui-side-tabs">

			<div class="sui-tabs-menu">

				<label for="{{ groupId }}-visitor-seen--less_than"
					class="sui-tab-item">
					<input type="radio"
						name="{{ groupId }}-less_or_more"
						value="less_than"
						id="{{ groupId }}-visitor-seen--less_than"
						data-attribute="less_or_more"
						{{ _.checked( less_or_more, 'less_than' ) }} />
					<?php esc_html_e( 'Less than', 'hustle' ); ?>
				</label>

				<label for="{{ groupId }}-visitor-seen--more_than"
					class="sui-tab-item">
					<input type="radio"
						name="{{ groupId }}-less_or_more"
						value="more_than"
						id="{{ groupId }}-visitor-seen--more_than"
						data-attribute="less_or_more"
						{{ _.checked( less_or_more, 'more_than' ) }} />
					<?php esc_html_e( 'More than', 'hustle' ); ?>
				</label>

			</div>

		</div>
	</div>

	<div class="sui-col">

		<label class="sui-label"><?php esc_html_e( 'Number of views', 'hustle' ); ?></label>

		<input
			type="number"
			min="1"
			max="999"
			maxlength="3"
			value="{{ less_than }}"
			placeholder="<?php esc_html_e( 'E.g. 10', 'hustle' ); ?>"
			id="{{ groupId }}-shown_less_than_value"
			class="sui-form-control"
			data-attribute="less_than"
		/>

	</div>

</div>

</script>

<?php // RULE: Visitor's Device. ?>
<script id="hustle-visibility-rule-tpl--visitor_device" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose device', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label
				for="{{ groupId }}-{{ type }}-rule--visitor-device-mobiles"
				class="sui-tab-item"
			>
				<input
					type="radio"
					name="{{ groupId }}-{{ type }}-rule--visitor-device"
					value="mobile"
					id="{{ groupId }}-{{ type }}-rule--visitor-device-mobiles"
					data-tab-menu="mobiles"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'mobile' ) }}
				/>
				<?php esc_html_e( 'Mobile only', 'hustle' ); ?>
			</label>

			<label
				for="{{ groupId }}-{{ type }}-rule--visitor-device-desktops"
				class="sui-tab-item"
			>
				<input
					type="radio"
					name="{{ groupId }}-{{ type }}-rule--visitor-device"
					value="not_mobile"
					id="{{ groupId }}-{{ type }}-rule--visitor-device-desktops"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'not_mobile' ) }}
				/>
				<?php esc_html_e( 'Desktop only', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content" data-tab-content="mobiles">

				<div class="sui-notice">

					<div class="sui-notice-content">

						<div class="sui-notice-message">

							<span class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></span>
							<p style="margin-top: 0;"><?php esc_html_e( 'Mobile devices include both Phone and Tablet.', 'hustle' ); ?></p>

						</div>
					</div>
				</div>

			</div>

		</div>

	</div>

</script>

<?php // RULE: Referrer. ?>
<script id="hustle-visibility-rule-tpl--from_referrer" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose referrer', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-rule--visitor-referrer-true"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--visitor-referrer"
					value="true"
					id="{{ groupId }}-{{ type }}-rule--visitor-referrer-true"
					data-attribute="filter_type"
					{{ _.checked( filter_type, true ) }} />
				<?php esc_html_e( 'Specific referrer', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-rule--visitor-referrer-false"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--visitor-referrer"
					value="false"
					id="{{ groupId }}-{{ type }}-rule--visitor-referrer-false"
					data-attribute="filter_type"
					{{ _.checked( filter_type, false ) }} />
				<?php esc_html_e( 'Not a specific referrer', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">

				<textarea placeholder="<?php esc_html_e( 'Enter the referrer URL', 'hustle' ); ?>"
					class="sui-form-control"
					data-attribute="refs">{{{ refs }}}</textarea>

				<span class="sui-description"><?php esc_html_e( 'It can be a full URL or a pattern like “.website.com”. Enter one pattern/URL per line.', 'hustle' ); ?></span>

			</div>

		</div>

	</div>

</script>

<?php // RULE: Source of Arrival. ?>
<script id="hustle-visibility-rule-tpl--source_of_arrival" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose source of arrival', 'hustle' ); ?></label>

	<div style="margin-top: 10px;">

		<label for="{{ groupId }}-{{ type }}-rule--source-direct"
			class="sui-checkbox sui-checkbox-sm sui-checkbox-stacked">
			<input type="checkbox"
				data-attribute="source_direct"
				{{ _.checked( source_direct, true ) }}
				id="{{ groupId }}-{{ type }}-rule--source-direct" />
			<span aria-hidden="direct"></span>
			<span><?php esc_html_e( 'Direct', 'hustle' ); ?></span>
			<span
				style="height: 22px; cursor: pointer; margin-left: 5px;"
				class="sui-tooltip sui-tooltip-top-left sui-tooltip-constrained"
				<?php /* translators: module type in small caps and in singular */ ?>
				data-tooltip="<?php printf( esc_html__( "Visitor enters the URL of the page containing this %s directly inside the browser's address bar.", 'hustle' ), esc_html( $smallcaps_singular ) ); ?>"
			>
				<span class="sui-icon-info sui-sm" style="pointer-events: none; vertical-align: middle;" aria-hidden="true"></span>
			</span>
		</label>

		<label for="{{ groupId }}-{{ type }}-rule--source-external"
			class="sui-checkbox sui-checkbox-sm sui-checkbox-stacked">
			<input type="checkbox"
				data-attribute="source_external"
				{{ _.checked( source_external, true ) }}
				id="{{ groupId }}-{{ type }}-rule--source-external" />
			<span aria-hidden="external"></span>
			<span><?php esc_html_e( 'An external page', 'hustle' ); ?></span>
			<span
				style="height: 22px; cursor: pointer; margin-left: 5px;"
				class="sui-tooltip sui-tooltip-top-left sui-tooltip-constrained"
				<?php /* translators: module type in small caps and in singular */ ?>
				data-tooltip="<?php printf( esc_html__( 'Visitor arrives on the page containing this %s from another website.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?>"
			>
				<span class="sui-icon-info sui-sm" style="pointer-events: none; vertical-align: middle;" aria-hidden="true"></span>
			</span>
		</label>

		<label for="{{ groupId }}-{{ type }}-rule--source-internal"
			class="sui-checkbox sui-checkbox-sm sui-checkbox-stacked">
			<input type="checkbox"
				data-attribute="source_internal"
				{{ _.checked( source_internal, true ) }}
				id="{{ groupId }}-{{ type }}-rule--source-internal" />
			<span aria-hidden="internal"></span>
			<span><?php esc_html_e( 'An internal page', 'hustle' ); ?></span>
			<span
				style="height: 22px; cursor: pointer; margin-left: 5px;"
				class="sui-tooltip sui-tooltip-top-left sui-tooltip-constrained"
				<?php /* translators: module type in small caps and in singular */ ?>
				data-tooltip="<?php printf( esc_html__( 'Visitor arrives on the page containing this %s from another page on your website.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?>"
			>
				<span class="sui-icon-info sui-sm" style="pointer-events: none; vertical-align: middle;" aria-hidden="true"></span>
			</span>
		</label>

		<label for="{{ groupId }}-{{ type }}-rule--source-search"
			class="sui-checkbox sui-checkbox-sm sui-checkbox-stacked">
			<input type="checkbox"
				data-attribute="source_search"
				{{ _.checked( source_search, true ) }}
				id="{{ groupId }}-{{ type }}-rule--source-search" />
			<span aria-hidden="search"></span>
			<span><?php esc_html_e( 'A search engine', 'hustle' ); ?></span>
			<span
				style="height: 22px; cursor: pointer; margin-left: 5px;"
				class="sui-tooltip sui-tooltip-top-left sui-tooltip-constrained"
				<?php /* translators: module type in small caps and in singular */ ?>
				data-tooltip="<?php printf( esc_html__( 'Visitor arrives on the page containing this %s from a search engine result.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?>"
			>
				<span class="sui-icon-info sui-sm" style="pointer-events: none; vertical-align: middle;" aria-hidden="true"></span>
			</span>
		</label>

		<label for="{{ groupId }}-{{ type }}-rule--source-not-search"
			class="sui-checkbox sui-checkbox-sm sui-checkbox-stacked">
			<input type="checkbox"
				data-attribute="source_not_search"
				{{ _.checked( source_not_search, true ) }}
				id="{{ groupId }}-{{ type }}-rule--source-not-search" />
			<span aria-hidden="not_search"></span>
			<span><?php esc_html_e( 'Not a search engine', 'hustle' ); ?></span>
			<span
				style="height: 22px; cursor: pointer; margin-left: 5px;"
				class="sui-tooltip sui-tooltip-top-left sui-tooltip-constrained"
				<?php /* translators: module type in small caps and in singular */ ?>
				data-tooltip="<?php printf( esc_html__( 'Visitor arrives on the page containing this %s from anywhere except a search engine result.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?>"
			>
				<span class="sui-icon-info sui-sm" style="pointer-events: none; vertical-align: middle;" aria-hidden="true"></span>
			</span>
		</label>

	</div>

</script>

<?php // RULE: Specific URL. ?>
<script id="hustle-visibility-rule-tpl--on_url" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose specific urls', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-rule--specific-url-except"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--specific-url"
					value="except"
					id="{{ groupId }}-{{ type }}-rule--specific-url-except"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'except' ) }} />
				<?php esc_html_e( 'All URLs except', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-rule--specific-url-only"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--specific-url"
					value="only"
					id="{{ groupId }}-{{ type }}-rule--specific-url-only"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'only' ) }} />
				<?php esc_html_e( 'Only these URLs', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">

				<textarea placeholder="<?php esc_html_e( 'Enter the URLs', 'hustle' ); ?>"
					class="sui-form-control"
					data-attribute="urls">{{{ urls }}}</textarea>

				<span class="sui-description"><?php esc_html_e( 'Enter only one URL per line and URLs should not include "http://" or "https://". You can also use wildcards in URLs.', 'hustle' ); ?></span>

			</div>

		</div>

	</div>

</script>

<?php // RULE: Visitor's Browser. ?>
<script id="hustle-visibility-rule-tpl--on_browser" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose visitor’s browser', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-rule--specific-browser-except"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--specific-browser"
					value="except"
					id="{{ groupId }}-{{ type }}-rule--specific-browser-except"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'except' ) }} />
				<?php esc_html_e( 'All browser except', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-rule--specific-browser-only"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--specific-browser"
					value="only"
					id="{{ groupId }}-{{ type }}-rule--specific-browser-only"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'only' ) }} />
				<?php esc_html_e( 'Only these browsers', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">

				<select
					multiple="multiple"
					id="not_in_a_browser_browsers"
					class="sui-select"
					data-placeholder="<?php esc_attr_e( 'Start typing the name of browsers...', 'hustle' ); ?>"
					data-val="browsers"
					data-attribute="browsers"
				>

						<# _.each( _.keys( optinVars.browsers ), function( key ) { #>

							<option value="{{ key }}">{{ optinVars.browsers[key] }}</option>

						<# }); #>

				</select>

			</div>

		</div>

	</div>

</script>

<?php // RULE: Visitor Commented Before. ?>
<script id="hustle-visibility-rule-tpl--visitor_commented" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'If the visitor has ever commented before is', 'hustle' ); ?></label>

	<div class="sui-side-tabs"
		style="margin-bottom: 20px;">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-rule--comments-true"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--comments"
					value="true"
					id="{{ groupId }}-{{ type }}-rule--comments-true"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'true' ) }} />
				<?php esc_html_e( 'True', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-rule--comments-false"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--comments"
					value="false"
					id="{{ groupId }}-{{ type }}-rule--comments-false"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'false' ) }} />
				<?php esc_html_e( 'False', 'hustle' ); ?>
			</label>

		</div>

	</div>

	<div class="sui-notice" style="margin-top: 20px;">

		<div class="sui-notice-content">

			<div class="sui-notice-message">

				<span class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></span>

				<?php /* translators: 1. opening 'strong' tag, 2. closing 'strong' tag */ ?>
				<p style="margin-top: 0;"><?php printf( esc_html__( 'You might also want to combine this condition along with %1$sVisitor\'s logged in status%2$s.', 'hustle' ), '<strong>', '</strong>' ); ?></p>

			</div>
		</div>
	</div>

</script>

<?php // RULE: Visitor's Country. ?>
<script id="hustle-visibility-rule-tpl--visitor_country" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose visitor’s country', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-rule--country-except"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--country"
					value="except"
					id="{{ groupId }}-{{ type }}-rule--country-except"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'except' ) }} />
				<?php esc_html_e( 'Any country except', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-rule--country-only"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--country"
					value="only"
					id="{{ groupId }}-{{ type }}-rule--country-only"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'only' ) }} />
				<?php esc_html_e( 'Only these countries', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">

				<select
					multiple="multiple"
					id="not_in_a_country_countries"
					class="sui-select"
					data-placeholder="<?php esc_attr_e( 'Start typing the name of countries...', 'hustle' ); ?>"
					data-val="countries"
					data-attribute="countries">

						<# _.each( _.keys( optinVars.countries ), function( key ) { #>

							<option value="{{ key }}">{{ optinVars.countries[key] }}</option>

						<# }); #>

				</select>

			</div>

		</div>

	</div>

</script>


<?php // RULE: Specific roles. ?>
<script id="hustle-visibility-rule-tpl--user_roles" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose user roles', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-rule--specific-roles-except"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--specific-roles"
					value="except"
					id="{{ groupId }}-{{ type }}-rule--specific-roles-except"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'except' ) }} />
				<?php esc_html_e( 'All except', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-rule--specific-roles-only"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--specific-roles"
					value="only"
					id="{{ groupId }}-{{ type }}-rule--specific-roles-only"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'only' ) }} />
				<?php esc_html_e( 'Only these', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">

				<select multiple="multiple"
					data-placeholder="<?php esc_attr_e( 'Start typing the user roles...', 'hustle' ); ?>"
					id="{{ groupId }}-not_a_role"
					class="sui-select sui-select-lg"
					data-val="roles"
					data-attribute="roles">

						<# _.each( _.keys( optinVars.roles ), function( key ) { #>

							<option value="{{ key }}">{{ optinVars.roles[key] }}</option>

						<# }); #>

				</select>

			</div>

		</div>

	</div>

</script>

<?php // RULE: User registration based visibility. ?>
<script id="hustle-visibility-rule-tpl--user_registration" type="text/template">

	<?php $days_past = __( 'day(s) past the registration day', 'hustle' ); ?>

	<div style="margin-bottom: 20px;">
		<label class="sui-label">
			<?php esc_html_e( 'From', 'hustle' ); ?>
		</label>
		<input
			type="number"
			min="0"
			max="999"
			maxlength="3"
			value="{{ from_date }}"
			id="{{ groupId }}-shown_from_date"
			class="sui-form-control sui-input-sm sui-field-has-suffix"
			data-attribute="from_date"
		/>
		<span class="sui-field-suffix" aria-hidden="true"><?php echo esc_html( $days_past ); ?></span>
	</div>

	<div style="margin-bottom: 29px;">
		<label class="sui-label">
			<?php esc_html_e( 'Up to', 'hustle' ); ?>
		</label>
		<input
			type="number"
			min="0"
			max="999"
			maxlength="3"
			value="{{ to_date }}"
			id="{{ groupId }}-shown_to_date"
			class="sui-form-control sui-input-sm sui-field-has-suffix"
			data-attribute="to_date"
		/>
		<span class="sui-field-suffix" aria-hidden="true"><?php echo esc_html( $days_past ); ?></span>
	</div>
	<span class="sui-description">
		<?php esc_html_e( 'Note: "0" in the From field means immediately after registration and "0" in the Up to field means forever.', 'hustle' ); ?>
	</span>

</script>

<?php // RULE: User template based visibility. ?>
<script id="hustle-visibility-rule-tpl--page_templates" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose page templates', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-rule--specific-templates-except"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--specific-templates"
					value="except"
					id="{{ groupId }}-{{ type }}-rule--specific-templates-except"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'except' ) }} />
				<?php esc_html_e( 'All except', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-rule--specific-templates-only"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--specific-templates"
					value="only"
					id="{{ groupId }}-{{ type }}-rule--specific-templates-only"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'only' ) }} />
				<?php esc_html_e( 'Only these', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">

				<select multiple="multiple"
					data-placeholder="<?php esc_attr_e( 'Start typing the name of page templates...', 'hustle' ); ?>"
					id="not_a_template"
					class="sui-select sui-select-lg"
					data-val="templates"
					data-attribute="templates">

						<# _.each( _.keys( optinVars.templates ), function( key ) { #>

							<option value="{{ key }}">{{ optinVars.templates[key] }}</option>

						<# }); #>

				</select>

			</div>

		</div>

	</div>

</script>

<?php // RULE: Static Pages. ?>
<script id="hustle-visibility-rule-tpl--wp_conditions" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose static pages', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-rule--static-page-except"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--static-page"
					value="except"
					id="{{ groupId }}-{{ type }}-rule--static-page-except"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'except' ) }} />
				<?php esc_html_e( 'All except', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-rule--static-page-only"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--static-page"
					value="only"
					id="{{ groupId }}-{{ type }}-rule--static-page-only"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'only' ) }} />
				<?php esc_html_e( 'Only these', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">


				<select multiple="multiple"
					data-placeholder="<?php esc_attr_e( 'Start typing the name of static pages...', 'hustle' ); ?>"
					class="sui-select sui-select-lg"
					data-attribute="wp_conditions">

						<# _.each( _.keys( optinVars.wp_conditions ), function( key ) { #>

							<option value="{{ key }}">{{ optinVars.wp_conditions[key] }}</option>

						<# }); #>

				</select>

			</div>

		</div>

	</div>

</script>

<?php // RULE: Archive pages. ?>
<script id="hustle-visibility-rule-tpl--archive_pages" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose archive pages', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-rule--archive-page-except"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--archive-page"
					value="except"
					id="{{ groupId }}-{{ type }}-rule--archive-page-except"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'except' ) }} />
				<?php esc_html_e( 'All archives except', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-rule--archive-page-only"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--archive-page"
					value="only"
					id="{{ groupId }}-{{ type }}-rule--archive-page-only"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'only' ) }} />
				<?php esc_html_e( 'Only these archives', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">


				<select multiple="multiple"
					data-placeholder="<?php esc_attr_e( 'Start typing the name of archives...', 'hustle' ); ?>"
					class="sui-select sui-select-lg"
					data-attribute="archive_pages">

						<# _.each( _.keys( optinVars.archive_pages ), function( key ) { #>

							<option value="{{ key }}">{{ optinVars.archive_pages[key] }}</option>

						<# }); #>

				</select>

			</div>

		</div>

	</div>

</script>

<?php // RULE: WooCommerce Page. ?>
<script id="hustle-visibility-rule-tpl--wc_pages" type="text/template">

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-filter_type-wc-all-pages"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-filter_type-wc-pages"
					value="all"
					id="{{ groupId }}-{{ type }}-filter_type-wc-all-pages"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'all' ) }} />
				<?php esc_html_e( 'All WooCommerce pages', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-filter_type-wc-none-pages"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-filter_type-wc-pages"
					value="none"
					id="{{ groupId }}-{{ type }}-filter_type-wc-none-pages"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'none' ) }} />
				<?php esc_html_e( 'None', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">

				<span class="sui-description"><?php esc_html_e( 'Use this condition to affect either all or none of the WooCommerce pages.', 'hustle' ); ?></span>

			</div>

		</div>

	</div>

</script>

<?php // RULE: WooCommerce Categories. ?>
<script id="hustle-visibility-rule-tpl--wc_categories" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose WooCommerce categories', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-filter_type-wc-categories-except"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-filter_type-wc-categories"
					value="except"
					id="{{ groupId }}-{{ type }}-filter_type-wc-categories-except"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'except' ) }} />
				<?php esc_html_e( 'All except', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-filter_type-wc-categories-only"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-filter_type-wc-categories"
					value="only"
					id="{{ groupId }}-{{ type }}-filter_type-wc-categories-only"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'only' ) }} />
				<?php esc_html_e( 'Only these', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">

				<select name=""
					id="{{ groupId }}-{{ type }}-filter_type-wc-categories"
					class="sui-select sui-select-lg hustle-select-ajax"
					multiple="multiple"
					data-val="{{ wc_categories }}"
					data-attribute="wc_categories"
					data-placeholder="<?php esc_html_e( 'Start typing the name of WooCommerce categories...', 'hustle' ); ?>">

					<# _.each( optinVars.wc_cats, function( cat ) { #>
						<option value="{{ cat.id }}" {{ _.selected( _.contains( wc_categories, cat.id.toString() ), true ) }}>
							{{ cat.text }}
						</option>
					<# } ); #>

				</select>

				<span class="sui-description"><?php esc_html_e( 'Note that this condition affects the products with selected categories and doesn\'t include product category archives.', 'hustle' ); ?></span>

			</div>

		</div>

	</div>

</script>

<?php // RULE: WooCommerce Tags. ?>
<script id="hustle-visibility-rule-tpl--wc_tags" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose WooCommerce tags', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-filter_type-wc-tags-except"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-filter_type-wc-tags"
					value="except"
					id="{{ groupId }}-{{ type }}-filter_type-wc-tags-except"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'except' ) }} />
				<?php esc_html_e( 'All except', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-filter_type-wc-tags-only"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-filter_type-wc-tags"
					value="only"
					id="{{ groupId }}-{{ type }}-filter_type-wc-tags-only"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'only' ) }} />
				<?php esc_html_e( 'Only these', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">

				<select name=""
					id="{{ groupId }}-{{ type }}-filter_type-wc-tags"
					class="sui-select sui-select-lg hustle-select-ajax"
					multiple="multiple"
					data-val="{{ wc_tags }}"
					data-attribute="wc_tags"
					data-placeholder="<?php esc_html_e( 'Start typing the name of WooCommerce tags...', 'hustle' ); ?>">

					<# _.each( optinVars.wc_tags, function( tag ) { #>
						<option value="{{ tag.id }}" {{ _.selected( _.contains( wc_tags, tag.id.toString() ), true ) }}>
							{{ tag.text }}
						</option>
					<# } ); #>

				</select>

				<span class="sui-description"><?php esc_html_e( 'Note that this condition affects the products with selected tags and doesn\'t include product tag archives.', 'hustle' ); ?></span>

			</div>

		</div>

	</div>

</script>

<?php // RULE: WC Archive pages. ?>
<script id="hustle-visibility-rule-tpl--wc_archive_pages" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose WooCommerce archives', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-rule--wc-archive-page-except"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--wc-archive-page"
					value="except"
					id="{{ groupId }}-{{ type }}-rule--wc-archive-page-except"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'except' ) }} />
				<?php esc_html_e( 'All except', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-rule--wc-archive-page-only"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--wc-archive-page"
					value="only"
					id="{{ groupId }}-{{ type }}-rule--wc-archive-page-only"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'only' ) }} />
				<?php esc_html_e( 'Only these', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">


				<select multiple="multiple"
					data-placeholder="<?php esc_attr_e( 'Start typing the name of WooCommerce archives...', 'hustle' ); ?>"
					class="sui-select sui-select-lg"
					data-attribute="wc_archive_pages">

						<# _.each( _.keys( optinVars.wc_archive_pages ), function( key ) { #>

							<option value="{{ key }}">{{ optinVars.wc_archive_pages[key] }}</option>

						<# }); #>

				</select>

			</div>

		</div>

	</div>

</script>

<?php // RULE: WC Static Pages. ?>
<script id="hustle-visibility-rule-tpl--wc_static_pages" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'Choose WooCommerce static pages', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu">

			<label for="{{ groupId }}-{{ type }}-rule--wc-static-page-except"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--wc-static-page"
					value="except"
					id="{{ groupId }}-{{ type }}-rule--wc-static-page-except"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'except' ) }} />
				<?php esc_html_e( 'All except', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-rule--wc-static-page-only"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--wc-static-page"
					value="only"
					id="{{ groupId }}-{{ type }}-rule--wc-static-page-only"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'only' ) }} />
				<?php esc_html_e( 'Only these', 'hustle' ); ?>
			</label>

		</div>

		<div class="sui-tabs-content">

			<div class="sui-tab-content active">


				<select multiple="multiple"
					data-placeholder="<?php esc_attr_e( 'Start typing the name of WooCommerce static pages...', 'hustle' ); ?>"
					class="sui-select sui-select-lg"
					data-attribute="wc_static_pages">

						<# _.each( _.keys( optinVars.wc_static_pages ), function( key ) { #>

							<option value="{{ key }}">{{ optinVars.wc_static_pages[key] }}</option>

						<# }); #>

				</select>

			</div>

		</div>

	</div>

</script>

<?php // RULE: Cookie is set. ?>
<script id="hustle-visibility-rule-tpl--cookie_set" type="text/template">

	<label class="sui-label"><?php esc_html_e( 'If a browser cookie', 'hustle' ); ?></label>

	<div class="sui-side-tabs">

		<div class="sui-tabs-menu" data-tabs>

			<label for="{{ groupId }}-{{ type }}-rule--cookie-set-exists"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--cookie-set"
					value="exists"
					id="{{ groupId }}-{{ type }}-rule--cookie-set-exists"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'exists' ) }} />
				<?php esc_html_e( 'Exists', 'hustle' ); ?>
			</label>

			<label for="{{ groupId }}-{{ type }}-rule--cookie-set-doesnt_exists"
				class="sui-tab-item">
				<input type="radio"
					name="{{ groupId }}-{{ type }}-rule--cookie-set"
					value="doesnt_exists"
					id="{{ groupId }}-{{ type }}-rule--cookie-set-doesnt_exists"
					data-attribute="filter_type"
					{{ _.checked( filter_type, 'doesnt_exists' ) }} />
				<?php esc_html_e( 'Doesn\'t exist', 'hustle' ); ?>
			</label>

		</div>

		<div data-panes>

			<div class="sui-tab-boxed <# if( filter_type === 'exists' ) { #>active<# } #>">

				<div class="sui-form-field">

					<label class="sui-label"><?php esc_html_e( 'Cookie name', 'hustle' ); ?></label>

					<input
						type="text"
						value="{{ cookie_name }}"
						placeholder="<?php esc_html_e( 'Enter cookie name', 'hustle' ); ?>"
						id="{{ groupId }}-{{ type }}-cookie-name"
						class="sui-form-control"
						data-attribute="cookie_name"
						name="{{ groupId }}-{{ type }}-cookie-name"
					/>

				</div>
				<div class="select-content-switcher-wrapper" style="margin-bottom: 0;">
					<div class="sui-form-field" style="margin-bottom: 0;">

						<label class="sui-label" style="margin-top: 26px;"><?php esc_html_e( 'Value', 'hustle' ); ?></label>

						<select
							id="{{ groupId }}-{{ type }}-cookie_value_conditions"
							name="{{ groupId }}-{{ type }}-cookie_value_conditions"
							class="sui-select select-content-switcher"
							data-val="cookie_value_conditions"
							data-attribute="cookie_value_conditions"
							data-content-on="equals,contains,matches_pattern,doesnt_match_pattern,less_than,less_equal_than,greater_than,greater_equal_than,doesnt_contains,doesnt_equals"
						>
							<# _.each( _.keys( optinVars.wp_cookie_set ), function( key ) { #>
								<option
									value="{{ key }}"
									{{ _.selected( ( cookie_value_conditions === key ), true) }}
									<# if ( _.contains( ['less_than', 'greater_than', 'less_equal_than', 'greater_equal_than' ], key ) ) { #>
										data-switcher-menu="number"
									<# } else if ( _.contains( [ 'anything' ], key ) ) { #>
										data-switcher-menu="none"
									<# } else { #>
										data-switcher-menu="text"
									<# } #>
								>
									{{ optinVars.wp_cookie_set[key] }}
								</option>
							<# }); #>
						</select>

					</div>
					<div class="sui-form-field select-switcher-content" data-switcher-content="text" style="margin-top: 5px; margin-bottom: 0;">

						<input
							type="text"
							value="{{ cookie_value }}"
							placeholder="<?php esc_html_e( 'Enter cookie value', 'hustle' ); ?>"
							id="{{ groupId }}-{{ type }}-cookie-value"
							class="sui-form-control"
							data-attribute="cookie_value"
							name="{{ groupId }}-{{ type }}-cookie-value"
							style="margin-top:10px;"
						/>

					</div>

					<div class="sui-form-field select-switcher-content" data-switcher-content="number" style="margin-top: 5px; margin-bottom: 0;">

						<input
							type="number"
							value="{{ cookie_value }}"
							placeholder="<?php esc_html_e( 'Enter cookie value', 'hustle' ); ?>"
							id="{{ groupId }}-{{ type }}-cookie-value"
							class="sui-form-control"
							data-attribute="cookie_value"
							name="{{ groupId }}-{{ type }}-cookie-value"
							style="margin-top:10px;"
						/>

					</div>
				</div>

			</div>

			<div class="sui-tab-boxed <# if( filter_type === 'doesnt_exists' ) { #>active<# } #>">

				<div class="sui-form-field">

					<label class="sui-label"><?php esc_html_e( 'Cookie name', 'hustle' ); ?></label>

					<input
						type="text"
						value="{{ cookie_name }}"
						placeholder="<?php esc_html_e( 'Enter cookie name', 'hustle' ); ?>"
						id="{{ groupId }}-{{ type }}-cookie-name"
						class="sui-form-control"
						data-attribute="cookie_name"
						name="{{ groupId }}-{{ type }}-cookie-name"
					/>

				</div>

			</div>
		</div>

	</div>

</script>
<?php

/**
 * Visibility Conditions: Action for adding JS-templates on Admin area and other JS-code
 *
 * @since 4.1.0
 */
do_action( 'hustle_visibility_condition_templates' );