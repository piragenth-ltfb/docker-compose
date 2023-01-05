<?php

function school_of_education_get_map(){
	ob_start();
	$location = bizberg_get_theme_mod( 'school_of_education_map_location' ); 
	$zoom     = bizberg_get_theme_mod( 'school_of_education_map_zoom' ); ?>
	<div style="width: 100%">
        <iframe 
        src="//maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=+(<?php echo esc_attr( $location ); ?>)&amp;t=&amp;z=<?php echo esc_attr( $zoom ); ?>&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
    </div>
	<?php
	return ob_get_clean();
}

function school_of_education_map_title(){
	$title = bizberg_get_theme_mod( 'school_of_education_map_title' );
	return esc_html( $title );
}

function school_of_education_map_description(){
	$description = bizberg_get_theme_mod( 'school_of_education_map_description' );
	return esc_html( $description );
}

function school_of_education_map_get_info(){ 

	ob_start();

	for ( $i=1; $i <= apply_filters( 'school_of_education_contact_limit', 3 ); $i++ ) {

		$title  = bizberg_get_theme_mod( 'school_of_education_map_contact_info_title_' . $i );
		$desc   = bizberg_get_theme_mod( 'school_of_education_map_contact_info_description_' . $i );
		$icon   = bizberg_get_theme_mod( 'school_of_education_map_contact_info_icon_' . $i );
		$status = bizberg_get_theme_mod( 'school_of_education_map_contact_status_' . $i ); 

		if( $status == true ){ ?>
	                        	 	
			<div class="info-item">
	            <div class="info-icon">
	                <i class="<?php echo esc_attr( $icon ); ?>"></i>
	            </div>
	            <div class="info-content">
	                <h4><?php echo esc_html( $title ); ?></h4>
	                <p class="m-0"><?php echo wp_kses_post( nl2br( $desc ) ); ?></p>
	            </div>
	        </div>

		 	<?php

		}

	} 

	return ob_get_clean();

}

add_action( 'bizberg_after_homepage_blog', 'school_of_education_map' );
function school_of_education_map(){

	do_action( 'school_of_education_after_blog' );

	$status = bizberg_get_theme_mod( 'school_of_education_map_status' );

	if( $status == false ){
		return;
	} ?>

	<section class="school-contact">
	    <div class="container">
	        <div class="contact-info">
	            <div class="row">
	                <div class="col-lg-8 col-md-6">
	                    <div class="map">
					        <?php echo school_of_education_get_map(); ?>
					    </div>
	                </div>
	                <div class="col-lg-4 col-md-6 sfe_col">
	                    <div class="">
	                        <h4 class="title"><?php echo school_of_education_map_title(); ?></h4>
	                        <p class="contact-para"><?php echo school_of_education_map_description(); ?></p>
	                        <div class="info_item_wrapper">
	                        	<?php 
	                        	echo school_of_education_map_get_info();
	                        	?>		                        
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</section>

	<?php

	do_action( 'school_of_education_after_map' );

}