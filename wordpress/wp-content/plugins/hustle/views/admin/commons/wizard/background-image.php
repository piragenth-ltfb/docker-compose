<div id="hustle-choose-background_image" class="sui-upload {{ ( !_.isEmpty( background_image ) ) ? 'sui-has_file' : '' }}">

	<input type="file"
		name="background_image"
		value="{{ background_image }}"
		data-attribute="background_image"
		readonly="readonly" />

	<div class="sui-upload-image" aria-hidden="true">

		<div class="sui-image-mask"></div>

		<div role="button" class="sui-image-preview wpmudev-background-image-browse" style="background-image: url({{ background_image }});"></div>

	</div>

	<button class="sui-upload-button wpmudev-background-image-browse">
		<i class="sui-icon-upload-cloud" aria-hidden="true"></i> <?php esc_html_e( 'Upload image', 'hustle' ); ?>
	</button>

	<div class="sui-upload-file">

		<span>{{ background_image }}</span>

		<button id="wpmudev-background-image-clear"
			aria-label="<?php esc_attr_e( 'Clear', 'hustle' ); ?>">
			<i class="sui-icon-close" aria-hidden="true"></i>
		</button>

	</div>

</div>