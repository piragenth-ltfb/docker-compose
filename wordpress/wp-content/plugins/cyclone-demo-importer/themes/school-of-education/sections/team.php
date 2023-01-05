<?php

function school_of_education_teams_get_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'school_of_education_teams_subtitle' );
	return esc_html( $subtitle );
}

function school_of_education_teams_get_title(){
	$title = bizberg_get_theme_mod( 'school_of_education_teams_title' );
	return esc_html( $title );
}

function school_of_education_teams_get_description(){
	$description = bizberg_get_theme_mod( 'school_of_education_teams_description' );
	return esc_html( $description );
}

function school_of_education_teams_get_team_content(){ 

	ob_start(); ?>

	<div class="row review-slider">

	 	<?php 

	 	for ($i=1; $i <= apply_filters( 'school_of_education_teams_limit', 4 ); $i++) {

	 		$status   = bizberg_get_theme_mod( 'school_of_education_teams_status_' . $i );
	 		$image_id = bizberg_get_theme_mod( 'school_of_education_teams_image_' . $i );
	 		$title    = bizberg_get_theme_mod( 'school_of_education_teams_content_title_' . $i );
	 		$subtitle = bizberg_get_theme_mod( 'school_of_education_teams_content_subtitle_' . $i );

	 		if( filter_var( $image_id , FILTER_VALIDATE_URL) === FALSE ){ // not a valid link
				$image_attributes = wp_get_attachment_image_src( $image_id , 'bizberg_medium' );
				$image_url = !empty( $image_attributes[0] ) ? $image_attributes[0] : '';
			} else {
				$image_url = $image_id;
			} 

	 		if( $status == true ){ ?>

		    	<div class="col-md-4">
		            <div class="team_member">
		            	<?php 
		            	if( !empty( $image_url ) ){ ?>
			                <figure class="effect-julia">
			                    <img src="<?php echo esc_url( $image_url ); ?>" alt="team">                            
			                </figure> 
			                <?php 
			            } ?>
		                <div class="member_name">
		                    <h4><?php echo esc_html( $title ); ?></h4>
		                    <span><?php echo esc_html( $subtitle ); ?></span>
		                </div>                      
		            </div>
		    	</div>

		    	<?php 

		    }

	    } ?>

	</div> 

	<?php

	return ob_get_clean();

}

add_action( 'bizberg_before_homepage_blog', 'school_of_education_teams' );
function school_of_education_teams(){

	$status = bizberg_get_theme_mod( 'school_of_education_teams_status' );

	if( $status == false ){
		return;
	} ?>

	<section class="school-team">
	    <div class="container">            
	        <div class="row">
	            <div class="col-md-5">
	                <!-- section title -->
	                <div class="text-left school-section-title">
	                    <h4>
	                    	<?php echo school_of_education_teams_get_subtitle(); ?>
	                    </h4>
	                    <h2>
	                    	<?php echo school_of_education_teams_get_title(); ?>
	                    </h2>
	                    <div class="testimonial-abt">
	                        <p>
	                        	<?php 
	                        	echo school_of_education_teams_get_description();
	                        	?>
	                        </p>
	                    </div>
	                </div>
	            </div>                  
	            <div class="col-md-7 team_wrapper">
	                <?php 
	                echo school_of_education_teams_get_team_content();
	                ?>               
	        	</div>
	        </div>
	    </div>
	</section>

	<?php

	do_action( 'school_of_education_after_teams' );
	
}