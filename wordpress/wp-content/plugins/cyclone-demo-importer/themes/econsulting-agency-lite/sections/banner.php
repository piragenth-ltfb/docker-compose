<?php

function econsulting_agency_banner_subtitle_text(){
	return esc_html( bizberg_get_theme_mod( 'econsulting_agency_banner_subtitle' ) );
}

function econsulting_agency_banner_title_text(){
	$title     = bizberg_get_theme_mod( 'econsulting_agency_banner_title' );
	$title_arr = explode( ' ' , $title ); // convert to array
	if( !empty( $title_arr ) && is_array( $title_arr ) ){
		$title_arr = array_reverse( $title_arr ); // reverse array
		$title_arr[0] = '<span>' . $title_arr[0] . '</span>';
		return implode( ' ', array_reverse( $title_arr ) );
	}
	return $title;
}

function econsulting_agency_banner_description(){
	return esc_html( bizberg_get_theme_mod( 'econsulting_agency_banner_content' ) );
}

function econsulting_agency_banner_button(){
	$label = bizberg_get_theme_mod( 'econsulting_agency_banner_button_text' );
	$link  = bizberg_get_theme_mod( 'econsulting_agency_banner_button_link' );
	return '<a href="' . esc_url( $link ) . '" class="per-btn"><span>' . esc_html( $label ) . '</span><i class="fa fa-long-arrow-alt-right"></i></a>';
}

function econsulting_agency_banner_custom_field(){
	$icon  = bizberg_get_theme_mod( 'econsulting_agency_banner_custom_field_icon' );
	$label = bizberg_get_theme_mod( 'econsulting_agency_banner_custom_field_label' );
	$value = bizberg_get_theme_mod( 'econsulting_agency_banner_custom_field_value' );
	return '<i class="' . esc_attr( $icon ) . '"></i>
			<p><span>' . esc_html( $label ) . '</span> ' . esc_html( $value ) . '</p>';
}

add_action( 'bizberg_before_homepage_blog', 'econsulting_agency_banner_section' );
function econsulting_agency_banner_section(){

	$status = bizberg_get_theme_mod( 'econsulting_agency_banner_status' );

	if( $status == false ){
		return;
	}  ?>

	<section class="econsult-banner">
		<div class="container">
			<div class="econsult-banner-in">
				<div class="row">
					<div class="col-md-6">
						<div class="econsult-banner-image"></div>
					</div>
					<div class="col-md-6">
						<div class="econsult-banner-content">
							<h4 class="subtitle">
								<?php echo esc_html( econsulting_agency_banner_subtitle_text() ); ?>
							</h4>
							<h1>
								<?php echo wp_kses_post( econsulting_agency_banner_title_text() ); ?>
							</h1>
							<p><?php echo esc_html( econsulting_agency_banner_description() ); ?></p>
						</div>
						<div class="econsult-banner-btn">
							<span class="button_wrapper">
								<?php echo wp_kses_post( econsulting_agency_banner_button() ); ?>
							</span>
							<div class="econsult-det custom_field">
								<?php echo wp_kses_post( econsulting_agency_banner_custom_field() ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php 

	do_action( 'econsulting_agency_after_banner' );

}