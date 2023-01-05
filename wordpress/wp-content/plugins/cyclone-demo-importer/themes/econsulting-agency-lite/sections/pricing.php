<?php 

function econsulting_agency_pricing_get_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'econsulting_agency_pricing_subtitle' );
	return esc_html( $subtitle );
}

function econsulting_agency_pricing_get_title(){
	$title = bizberg_get_theme_mod( 'econsulting_agency_pricing_title' );
	return esc_html( $title );
}

function econsulting_agency_pricing_get_pricing_table(){

	ob_start();

	$data = bizberg_get_theme_mod( 'econsulting_agency_pricing_list' );
	$data = json_decode( $data, true );

	if( !empty( $data ) && is_array( $data ) ){

		foreach ( $data as $key1 => $value ) { 

			$label                  = !empty( $value['label'] ) ? $value['label'] : '';
			$title_color            = !empty( $value['title_color'] ) ? $value['title_color'] : ''; 
			$currency               = !empty( $value['currency'] ) ? $value['currency'] : '';
			$price                  = !empty( $value['price'] ) ? $value['price'] : '';
			$price                  = explode( '.', $price, 2 );
			$currency_color         = !empty( $value['currency_color'] ) ? $value['currency_color'] : '';
			$features               = !empty( $value['features'] ) ? explode( "\n", $value['features'] ) : '';
			$content_background     = !empty( $value['content_background'] ) ? $value['content_background'] : '';
			$separator_color        = !empty( $value['separator_color'] ) ? $value['separator_color'] : '';
			$text_color             = !empty( $value['text_color'] ) ? $value['text_color'] : '';
			$button_status          = !empty( $value['button_status'] ) ? $value['button_status'] : '';
			$button_label           = !empty( $value['button_label'] ) ? $value['button_label'] : '';
			$button_link            = !empty( $value['button_link'] ) ? $value['button_link'] : '#';
			$btn_background_color   = !empty( $value['btn_background_color'] ) ? $value['btn_background_color'] : '';
			$btn_label_color_normal = !empty( $value['btn_label_color_normal'] ) ? $value['btn_label_color_normal'] : '';
			$tag_status             = !empty( $value['tag_status'] ) ? $value['tag_status'] : '';
			$tag_label              = !empty( $value['tag_label'] ) ? $value['tag_label'] : '';
			$tag_background         = !empty( $value['tag_background'] ) ? $value['tag_background'] : ''; ?>
			
			<div 
			class="econsult-pricing-item pricing_table_<?php echo absint( $key1 ); ?>"
			style="background-color: <?php echo esc_attr( $content_background ); ?>;">
				<div 
				class="pricing-header"
				style="border-bottom-color: <?php echo esc_attr( $separator_color ); ?>;">

					<?php 
					if( $tag_status == 'true' ){ ?>
						<div 
						class="pricing-tag" 
						style="background-color:<?php echo esc_attr( $tag_background ); ?>;"><?php echo esc_html( $tag_label ); ?></div>
						<?php 
					} ?>

					<h4 
					class="pricing-title"
					style="color: <?php echo esc_attr( $title_color ); ?>"><?php echo esc_html( $label ); ?></h4>
					<div class="pricing-price_wrap">

						<span 
						class="pricing_currency"
						style="color: <?php echo esc_attr( $currency_color ); ?>"><?php echo esc_html( $currency ); ?></span>

						<h3 
						class="pricing-price"
						style="color: <?php echo esc_attr( $currency_color ); ?>"><?php echo ( !empty( $price[0] ) ? absint( $price[0] ) : '' ); ?><span class="price_decimal"><?php echo ( !empty( $price[1] ) ? absint( $price[1] ) : '' ); ?></span></h3>

					</div>
				</div>
				<div class="pricing-content" style="color: <?php echo esc_attr( $text_color ); ?>">
					<?php 
					if( !empty( $features ) ){ ?>
						<ul>
							<?php 
							foreach ( $features as $key => $value ) {
								echo '<li>' . esc_html( $value ) . '</li>';
							}
							?>
						</ul>
						<?php 
					} ?>
				</div>
				<?php 
				if( $button_status == 'true' ){ ?>
					<div class="pricing-footer">
						<a 
						style="color: <?php echo esc_attr( $btn_label_color_normal ); ?>;"
						href="<?php echo esc_url( $button_link ); ?>" class="per-btn"><span><?php echo esc_html( $button_label ); ?></span><i class="fa fa-long-arrow-alt-right"></i></a>
					</div>
					<?php 
				} ?>
				<style>
					.pricing_table_<?php echo absint( $key1 ); ?> .per-btn::before{
						background : <?php echo esc_attr( $btn_background_color ); ?>
					}
				</style>
			</div>

			<?php

		}

	}

	return ob_get_clean();

}

add_action( 'bizberg_before_homepage_blog', 'econsulting_agency_pricing_section' );
function econsulting_agency_pricing_section(){

	$status = bizberg_get_theme_mod( 'econsulting_agency_pricing_status' );

	if( $status != true ){
		return;
	} ?>

	<section class="econsult-pricing">
		<div class="container">
			<div class="section-title">
				<p><?php echo econsulting_agency_pricing_get_subtitle(); ?></p>
				<h2><?php echo econsulting_agency_pricing_get_title(); ?></h2>
			</div>
			<div class="econsult-pricing-main">
				<?php echo econsulting_agency_pricing_get_pricing_table(); ?>
			</div>
		</div>
	</section>

	<?php

	do_action( 'econsulting_agency_after_pricing' );
	
}