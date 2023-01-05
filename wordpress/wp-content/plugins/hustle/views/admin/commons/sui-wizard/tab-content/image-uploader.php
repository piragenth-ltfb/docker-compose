<?php
$image_url = $settings[ $attribute ];
?>

<div class="sui-form-field">

	<label class="sui-label"><?php echo esc_html( $field_title ); ?></label>

	<div id="hustle-choose-<?php echo esc_attr( $attribute ); ?>" class="sui-upload <?php echo empty( $image_url ) ? '' : 'sui-has_file'; ?>">

		<input
			type="file"
			name="<?php echo esc_attr( $attribute ); ?>"
			value="<?php echo esc_attr( $image_url ); ?>"
			data-attribute="<?php echo esc_attr( $attribute ); ?>"
			readonly="readonly"
		/>

		<div class="sui-upload-image" aria-hidden="true">

			<div class="sui-image-mask"></div>

			<div
				role="button"
				class="sui-image-preview hustle-image-uploader-browse"
				style="background-image: url(<?php echo esc_url( $image_url ); ?>);"
			></div>

		</div>

		<button class="sui-upload-button hustle-image-uploader-browse">
			<i class="sui-icon-upload-cloud" aria-hidden="true"></i> <?php esc_html_e( 'Upload image', 'hustle' ); ?>
		</button>

		<div class="sui-upload-file">

			<span class="hustle-upload-file-url"><?php echo esc_url( $image_url ); ?></span>

			<button class="hustle-image-uploader-clear" aria-label="<?php esc_attr_e( 'Clear', 'hustle' ); ?>">
				<i class="sui-icon-close" aria-hidden="true"></i>
			</button>

		</div>

	</div>

	<span class="sui-description"><?php echo esc_html( $field_description ); ?></span>

</div>