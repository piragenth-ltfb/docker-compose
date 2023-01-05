<?php

function school_of_education_get_services(){

	ob_start();

	$services = bizberg_get_theme_mod( 'school_of_education_services_repeater' );
	$services = is_array( $services ) ? $services : json_decode( urldecode( $services ), true );

	foreach( $services as $service ){

		$background   = !empty( $service['background'] ) ? $service['background'] : '#000';
		$title        = !empty( $service['title'] ) ? $service['title'] : '';
		$content      = !empty( $service['content'] ) ? $service['content'] : '';
		$icon         = !empty( $service['icon'] ) ? $service['icon'] : '';
		$link         = !empty( $service['link'] ) ? $service['link'] : ''; ?>

		<div class="col-lg-3 col-md-6 col-sm-6 sof_services_col">

			<?php 
			if( !empty( $link ) ){ ?>
			<a href="<?php echo esc_url( $link ); ?>">
				<?php 
			} ?>

				<div class="school-featured-item" style="background:<?php echo esc_attr( $background ); ?>;">
					<i class="<?php echo esc_attr( $icon ); ?>"></i>
					<div class="school-featured-content">
						<h4><?php echo esc_html( $title ); ?></h4>
						<p><?php echo esc_html( $content ); ?></p>
					</div>
				</div>

			<?php 
			if( !empty( $link ) ){ ?>
			</a>
			<?php 
			} ?>

		</div>

		<?php 
	}

	return ob_get_clean();

}

add_action( 'bizberg_before_homepage_blog', 'school_of_education_service_section' );
function school_of_education_service_section(){ 

	$status = bizberg_get_theme_mod('school_of_education_services_status');

	if( $status == false ){
		return;
	}

	$services = bizberg_get_theme_mod( 'school_of_education_services_repeater' );
	$services = is_array( $services ) ? $services : json_decode( urldecode( $services ), true );

	if( !empty( $services ) && is_array( $services ) ){ ?>

		<section class="school-featured">

			<div class="container">

				<div class="school-featured-main">

					<?php 
					echo school_of_education_get_services();
					?>

				</div>

			</div>

		</section>

		<?php
	} 

	do_action( 'school_of_education_after_services' );
		
}