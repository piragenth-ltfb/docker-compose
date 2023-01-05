<?php

function school_of_education_counter_container(){

	ob_start();
	$counters = bizberg_get_theme_mod( 'school_of_education_counter_section_homepage' );
	$counters = is_array( $counters ) ? $counters : json_decode( rawurldecode( $counters ), true );

	foreach ( $counters as $key => $value ) {
		$title  = !empty( $value['title'] ) ? $value['title'] : '';
		$number = !empty( $value['number'] ) ? $value['number'] : '';
		$color  = !empty( $value['color'] ) ? $value['color'] : '';
		$prefix = !empty( $value['prefix'] ) ? $value['prefix'] : ''; ?>
	 	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
	        <div class="counter-item d-line">
	            <h3 style="color: <?php echo esc_attr( $color ); ?>;">
	            	<span class="school-value">
	            		<?php echo esc_html( $number ); ?>
	            	</span><?php echo esc_html( $prefix ); ?>
	            </h3>
	            <p><?php echo esc_html( $title ); ?></p>
	        </div>    
	    </div>
	 	<?php
	} 

	return ob_get_clean();

}

add_action( 'bizberg_before_homepage_blog', 'school_of_education_counter_section' );
function school_of_education_counter_section(){

	$status = bizberg_get_theme_mod( 'school_of_education_counter_status' );

	if( $status == false ){
		return;
	}

	$counters = bizberg_get_theme_mod( 'school_of_education_counter_section_homepage' );
	$counters = is_array( $counters ) ? $counters : json_decode( rawurldecode( $counters ), true );

	if( !empty( $counters ) && is_array( $counters ) ){ ?>

		<section class="school-counter">
			<div class="container">
				<div class="counter">
		            <div class="row">	                  
		            	<?php echo school_of_education_counter_container(); ?>
		            </div>
		        </div>
			</div>
		</section>
		
		<?php

	}

	do_action( 'school_of_education_after_counter' );

}