<?php
/**
 * Platform item for within the ./add-platforms.php modal.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<script id="hustle-add-platform-li-tpl" type="text/template">

	<li><label for="hustle-social--{{platform}}" class="sui-box-selector sui-box-selector-vertical">
		<input
			type="checkbox"
			name="hustle-social-platforms"
			class="hustle-add-platforms-option"
			value="{{platform}}"
			id="hustle-social--{{platform}}"
		/>
		<span>
			<span class="sui-icon-social" aria-hidden="true">
				<span class="hui-icon-social-{{platform_style}} hui-icon-circle"></span>
			</span>
			{{label}}
		</span>
	</label></li>

</script>