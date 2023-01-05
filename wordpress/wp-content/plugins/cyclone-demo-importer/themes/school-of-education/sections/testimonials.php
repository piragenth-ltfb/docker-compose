<?php

function school_of_education_get_testimonials_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'school_of_education_testimonials_subtitle' );
	return esc_html( $subtitle );
}

function school_of_education_get_testimonials_title(){
	$title = bizberg_get_theme_mod( 'school_of_education_testimonials_title' );
	return esc_html( $title );
}

function school_of_education_get_testimonials_get_testimonial_content(){

	ob_start(); ?>

	<div class="row testimonial-slider">

		<?php 
		for ( $i=1; $i <= apply_filters( 'school_of_education_testimonials_limit', 4 ); $i++ ) { 

			$status      = bizberg_get_theme_mod( 'school_of_education_testimonials_content_status_' . $i );
			$name        = bizberg_get_theme_mod( 'school_of_education_testimonials_name_' . $i );
			$designation = bizberg_get_theme_mod( 'school_of_education_testimonials_designation_' . $i );
			$description = bizberg_get_theme_mod( 'school_of_education_testimonials_description_' . $i );
			$image_id    = bizberg_get_theme_mod( 'school_of_education_testimonials_image_' . $i );

			if( filter_var( $image_id , FILTER_VALIDATE_URL) === FALSE ){ // not a valid link
				$image_attributes = wp_get_attachment_image_src( $image_id , 'thumbnail' );
				$image_url = !empty( $image_attributes[0] ) ? $image_attributes[0] : '';
			} else {
				$image_url = $image_id;
			} 

			if( $status == true ){ ?>

		        <div class="col-sm-4 item">
		            <div class="testimonial-item">
		                <div class="details">
		                    <p class="m-0">
		                    	<?php echo esc_html( $description ); ?>
		                    </p>
		                </div>
		                <div class="author-info">
		                	
		                	<?php 
		                	if( !empty( $image_url ) ){ ?>
		                    	<img src="<?php echo esc_url( $image_url ); ?>" alt="image">
		                    	<?php 
		                    } ?>

		                    <div class="author-title">
		                        <h4><?php echo esc_html( $name ); ?></h4>
		                        <span><?php echo esc_html( $designation ); ?></span>
		                    </div>
		                </div>
		                <i class="fa fa-quote-left"></i>
			        </div>
			    </div>

			    <?php 

			}

		} ?>

	</div>

	<?php

	return ob_get_clean();

}

add_action( 'bizberg_before_homepage_blog', 'school_of_education_testimonials' );
function school_of_education_testimonials(){ 

	$status = bizberg_get_theme_mod( 'school_of_education_testimonials_status' );

	if( $status == false ){
		return;
	} ?>

	<section class="school-testimonial">
	    <div class="container">

	    	<div class="school-section-title">
				<h4><?php echo school_of_education_get_testimonials_subtitle(); ?></h4>
				<h3><?php echo school_of_education_get_testimonials_title(); ?></h3>
			</div>

			<div class="testimonial_wrapper">
		        <?php echo school_of_education_get_testimonials_get_testimonial_content(); ?>
	        </div>

	    </div>
	</section>

	<?php

	do_action( 'school_of_education_after_testimonials' );
	
}