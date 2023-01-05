<?php

function school_of_education_get_our_courses_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'school_of_education_our_courses_subtitle' );
	return esc_html( $subtitle );
}

function school_of_education_get_our_courses_title(){
	$title = bizberg_get_theme_mod( 'school_of_education_our_courses_title' );
	return esc_html( $title );
}

function school_of_education_get_our_courses_content(){

	ob_start();

	echo '<div class="row courses_slider">';

	for ( $i = 1; $i <= apply_filters( 'school_of_education_our_courses_limit', 3 ); $i++) {

		$title          = bizberg_get_theme_mod( 'school_of_education_our_courses_content_title_' . $i );
		$content        = bizberg_get_theme_mod( 'school_of_education_our_courses_content_description_' . $i );
		$label          = bizberg_get_theme_mod( 'school_of_education_our_courses_content_button_label_' . $i );
		$link           = bizberg_get_theme_mod( 'school_of_education_our_courses_content_button_link_' . $i );
		$image_id       = bizberg_get_theme_mod( 'school_of_education_our_courses_content_image_' . $i );
		$color          = bizberg_get_theme_mod( 'school_of_education_our_courses_color_' . $i );
		$content_status = bizberg_get_theme_mod( 'school_of_education_our_courses_status_' . $i );

		$image_url = '';
		$button = 'button_' . $i;
		$featured_course_item = 'featured-course-item-' . $i;

		if( filter_var( $image_id , FILTER_VALIDATE_URL) === FALSE ){ // not a valid link
			$image_attributes = wp_get_attachment_image_src( $image_id , 'medium_large' );
			$image_url = !empty( $image_attributes[0] ) ? $image_attributes[0] : '';
		} else {
			$image_url = $image_id;
		} 

		if( $content_status == true ){ ?>
	 	
			<div class="<?php echo ( $i == 1 ? 'col-lg-4 col-md-6 col-sm-12' : 'col-lg-4 col-md-6 col-sm-6' ); ?>">
				<div class="course">
					<div class="featured-course-item <?php echo esc_attr( $featured_course_item ); ?>">
		                <div class="feat-img" style="background-image: url( <?php echo esc_url( $image_url ); ?> )">
		                </div>
		                <div class="feat-inn">
		                    <h4>
		                    	<?php 
		                    	echo esc_html( $title );
		                    	?>
		                    </h4>
		                    <p>
		                    	<?php echo esc_html( $content ); ?>
		                    </p>

		                    <?php 
		                    if( !empty( $label ) ){ ?>
			                    <a href="<?php echo esc_url( $link ); ?>" class="readmore-btn <?php echo esc_attr( $button ); ?>">
			                    	<?php echo esc_html( $label ); ?> <i class="fa fa-long-arrow-alt-right"></i>
			                    </a>
			                    <?php 
		                   	} ?>

		                </div>  
		            </div>
				</div>
				<style>
					.school-featured-course .featured-course-item a.<?php echo esc_attr( $button ); ?>, 
					.school-featured-course .featured-course-item a.<?php echo esc_attr( $button ); ?>:hover{
						color: <?php echo esc_attr( $color ); ?>;
					}
					.<?php echo esc_attr( $featured_course_item ); ?>:before {
						background: <?php echo esc_attr( $color ); ?>;
					}
				</style>
			</div>		

		 	<?php

		}
		
	} 

	echo '</div>';

	return ob_get_clean();

}

add_action( 'bizberg_before_homepage_blog', 'school_of_education_our_courses' );
function school_of_education_our_courses(){

	$status = bizberg_get_theme_mod( 'school_of_education_our_courses_status' );

	if( $status == false ){
		return;
	} ?>

	<section class="school-featured-course">
		<div class="container">
			<div class="school-section-title">
				<h4>
					<?php echo school_of_education_get_our_courses_subtitle(); ?>
				</h4>
				<h3>
					<?php echo school_of_education_get_our_courses_title(); ?>
				</h3>
			</div>
			<div class="featured_courses_wrapper">
				<?php echo school_of_education_get_our_courses_content(); ?>
			</div>
		</div>
	</section>

	<?php

	do_action( 'school_of_education_after_courses' );
	
}