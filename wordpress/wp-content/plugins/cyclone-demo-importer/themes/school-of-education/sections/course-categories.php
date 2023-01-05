<?php

function school_of_education_get_course_categories_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'school_of_education_course_category_subtitle' );
	return esc_html( $subtitle );
}

function school_of_education_get_course_categories_title(){
	$title = bizberg_get_theme_mod( 'school_of_education_course_category_title' );
	return esc_html( $title );
}

function school_of_education_get_course_content(){

	ob_start();

	$courses = bizberg_get_theme_mod( 'school_of_education_course_category_content_repeater' );
	$courses = is_array( $courses ) ? $courses : json_decode( urldecode( $courses ), true );

	echo '<div class="partner-slider">';

	foreach( $courses as $course ){

		$title       = !empty( $course['title'] ) ? $course['title'] : ''; 
		$icon        = !empty( $course['icon'] ) ? $course['icon'] : '';
		$background  = !empty( $course['background'] ) ? $course['background'] : '#000';
		$link        = !empty( $course['link'] ) ? $course['link'] : ''; ?>

		<div class="col-lg-3 course_cat_slick">

			<?php 
			if( !empty( $link ) ){ ?>
				<a href="<?php echo esc_url( $link ); ?>">
				<?php 
			} ?>

				<div class="school-category-item" style="background:<?php echo esc_attr( $background ); ?>;">
					<i class="<?php echo esc_attr( $icon ); ?>"></i>
					<h5 class="category-title">
						<?php 
						echo esc_html( $title );
						?>
					</h5>
				</div>
			
			<?php 
			if( !empty( $link ) ){ ?>
				</a>
				<?php 
			}?>

		</div>

		<?php

	}

	echo '</div>';

	return ob_get_clean();

}

add_action( 'bizberg_before_homepage_blog', 'school_of_education_course_categories_section' );
function school_of_education_course_categories_section(){ 

	$status = bizberg_get_theme_mod( 'school_of_education_course_category_status' );

	if( $status ==  false ){
		return;
	}

	$courses = bizberg_get_theme_mod( 'school_of_education_course_category_content_repeater' );
	$courses = is_array( $courses ) ? $courses : json_decode( urldecode( $courses ), true );

	if( !empty( $courses ) && is_array( $courses ) ){ ?>

		<section class="school-category">
			<div class="container">
				<div class="school-section-title">
					<h4><?php echo school_of_education_get_course_categories_subtitle(); ?></h4>
					<h3><?php echo school_of_education_get_course_categories_title(); ?></h3>
				</div>
				<div class="school-category-main">					
						<?php echo school_of_education_get_course_content(); ?>					
				</div>
			</div>
		</section>

		<?php

	}

	do_action( 'school_of_education_after_courses_categories' );

}