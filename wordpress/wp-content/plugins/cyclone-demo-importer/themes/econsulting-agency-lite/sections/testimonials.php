<?php 

function econsulting_agency_testimonials_get_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'econsulting_agency_testimonials_subtitle' );
	return esc_html( $subtitle );
}

function econsulting_agency_testimonials_get_title(){
	$title = bizberg_get_theme_mod( 'econsulting_agency_testimonials_title' );
	return esc_html( $title );
}

function econsulting_agency_testimonials_get_content(){
	ob_start();
	$testimonials = bizberg_get_theme_mod( 'econsulting_agency_testimonials_list' );
	$testimonials = json_decode( $testimonials, true );
	if( !empty( $testimonials ) && is_array( $testimonials ) ){
		echo '<div class="testi-slider">';
		foreach ( $testimonials as $key => $testimonial ) {
			$name        = !empty( $testimonial['name'] ) ? $testimonial['name'] : '';
			$content     = !empty( $testimonial['content'] ) ? $testimonial['content'] : '';
			$position    = !empty( $testimonial['position'] ) ? $testimonial['position'] : '';
			$profile_pic = !empty( $testimonial['profile_pic'] ) && is_numeric( $testimonial['profile_pic'] ) ? wp_get_attachment_image_url( $testimonial['profile_pic'] , 'thumbnail' ) : $testimonial['profile_pic']; ?>
			<div class="col-md-4">
                <div class="econsult-testi-item text-center">
                    <div class="econsult-testi-details">
                        <p class="m-0"><?php echo esc_html( $content ); ?></p>
                    </div>
                    <div class="econsult-testi-author" style="<?php echo empty( $profile_pic ) ? 'margin-top: 0' : ''; ?>">
                        <img src="<?php echo esc_url( $profile_pic ); ?>">
                        <div class="econsult-testi-author-title">
                            <h4><?php echo esc_html( $name ); ?></h4>
                            <p><?php echo esc_html( $position ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
			<?php
		}
		echo '</div>';
	}
	return ob_get_clean();
}

add_action( 'bizberg_before_homepage_blog', 'econsulting_agency_testimonials_section' );
function econsulting_agency_testimonials_section(){

	$status = bizberg_get_theme_mod( 'econsulting_agency_testimonials_status' ); 

	if( $status != true ){
		return;
	} ?>

	<section class="econsult-testi">
		<div class="container">
			<div class="section-title">
				<p><?php echo econsulting_agency_testimonials_get_subtitle(); ?></p>
				<h2><?php echo econsulting_agency_testimonials_get_title(); ?></h2>
			</div>
			<div class="row">
                <?php echo econsulting_agency_testimonials_get_content(); ?>
            </div>
		</div>
	</section>

	<?php

	do_action( 'econsulting_agency_after_testimonials' );
	
}