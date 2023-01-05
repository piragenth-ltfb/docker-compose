<?php 

function econsulting_agency_map_get_map_iframe(){ 
	ob_start(); 
	$location = bizberg_get_theme_mod( 'econsulting_agency_map_location' );
	$zoom     = bizberg_get_theme_mod( 'econsulting_agency_map_zoom' ); ?>
	<iframe 
	height="400" 
	width="100%" 
	src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=+(<?php echo esc_attr( $location ); ?>)&amp;t=&amp;z=<?php echo esc_attr( $zoom ); ?>&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
	<?php
	return ob_get_clean();
}

add_action( 'bizberg_before_homepage_blog', 'econsulting_agency_map_section' );
function econsulting_agency_map_section(){

	$status = bizberg_get_theme_mod( 'econsulting_agency_map_status' );

	if( $status != true ){
		return;
	} ?>

	<section class="econsult-map">
        <div class="container">
        	<div class="map">
	            <?php 
	            echo econsulting_agency_map_get_map_iframe();
	            ?>
	        </div>
	    </div>
    </section>

	<?php

	do_action( 'econsulting_agency_after_map' );

}