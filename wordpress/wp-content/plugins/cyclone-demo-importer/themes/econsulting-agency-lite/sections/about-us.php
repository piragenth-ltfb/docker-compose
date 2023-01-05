<?php 

function econsulting_agency_get_about_us_title(){
	$title = bizberg_get_theme_mod( 'econsulting_agency_about_us_title' );
	return wp_kses_post( $title );
}

function econsulting_agency_get_about_us_content(){
	$content = bizberg_get_theme_mod( 'econsulting_agency_about_us_content' );
	return wpautop( wp_kses_post( $content ) );
}

function econsulting_agency_get_about_us_button(){
	ob_start(); 
	$label = bizberg_get_theme_mod( 'econsulting_agency_about_us_button_text' );
	$link  = bizberg_get_theme_mod( 'econsulting_agency_about_us_button_link' ); ?>
	<a href="<?php echo esc_url( $link ); ?>" class="per-btn"><span><?php echo esc_html( $label ); ?></span><i class="fa fa-long-arrow-alt-right"></i></a>
	<?php
	return ob_get_clean();
}

add_action( 'bizberg_before_homepage_blog', 'econsulting_agency_about_us_section' );
function econsulting_agency_about_us_section(){

	$status = bizberg_get_theme_mod( 'econsulting_agency_about_us_status' );

	if( $status != true ){
		return;
	} ?>

	<section class="econsult-about-us">
		<div class="container">
			<div class="row">
				<div class="col-md-6 abt_image_wrapper">
					<div class="about-us-image"></div>
				</div>
				<div class="col-md-6 about-us-content-wrapper">
					<div class="about-us-content">
						<h4><?php echo wp_kses_post( econsulting_agency_get_about_us_title() ); ?></h4>
						<div class="content">
							<?php echo econsulting_agency_get_about_us_content(); ?>
						</div>
						<div class="button_wrapper">
						 	<?php 
						 	echo wp_kses_post( econsulting_agency_get_about_us_button() );
						 	?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php

	do_action( 'econsulting_agency_after_about_us' );
	
}