<?php 

function econsulting_agency_call_to_action_get_icon(){
	$icon = bizberg_get_theme_mod( 'econsulting_agency_call_to_action_icon' );
	return '<i class="' . esc_attr( $icon ) . '"></i>';
}

function econsulting_agency_call_to_action_get_title(){
	$title = bizberg_get_theme_mod( 'econsulting_agency_call_to_action_title' );
	return esc_html( $title );
}

function econsulting_agency_call_to_action_get_button(){
	$label = bizberg_get_theme_mod( 'econsulting_agency_call_to_action_button_text' );
	$link  = bizberg_get_theme_mod( 'econsulting_agency_call_to_action_button_link' );
	return '<a href="' . esc_url( $link ) . '" class="per-btn"><span>' . esc_attr( $label ) . '</span><i class="fa fa-long-arrow-alt-right"></i></a>';
}

add_action( 'bizberg_before_homepage_blog', 'econsulting_agency_call_to_action_section' );
function econsulting_agency_call_to_action_section(){ 

	$status = bizberg_get_theme_mod( 'econsulting_agency_call_to_action_status' ); 

	if( $status != true ){
		return;
	} ?>

	<section class="econsult-callto">
		<div class="container">
			<div class="econsult-callto-main">
				<div class="econsult-callto-icon">
					<?php echo econsulting_agency_call_to_action_get_icon(); ?>
				</div>
				<h2><?php echo econsulting_agency_call_to_action_get_title(); ?></h2>
				<div class="button">
					<?php echo wp_kses_post( econsulting_agency_call_to_action_get_button() ); ?>
				</div>
			</div>
		</div>
	</section>

	<?php

	do_action( 'econsulting_agency_after_call_to_action' );
	
}