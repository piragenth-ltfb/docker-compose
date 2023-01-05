<?php

function school_of_education_get_about_us_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'school_of_education_about_us_subtitle1' );
	return esc_html( $subtitle );
}

function school_of_education_get_about_us_title(){
	$title = bizberg_get_theme_mod( 'school_of_education_about_us_title' );
	return esc_html( $title );
}

function school_of_education_get_about_us_content(){
	$content = bizberg_get_theme_mod( 'school_of_education_about_us_content' );
	return esc_html( $content );
}

function school_of_education_get_about_us_button(){
	ob_start(); 
	$label     = bizberg_get_theme_mod( 'school_of_education_about_us_button_label' ); 
	$link      = bizberg_get_theme_mod( 'school_of_education_about_us_button_link' );
	$button_bg = bizberg_get_theme_mod( 'school_of_education_about_us_button_background_color' ); ?>
	<a href="<?php echo esc_url( $link ); ?>" class="per-btn about_us_btn">
        <span><?php echo esc_html( $label ); ?></span><i class="fa fa-long-arrow-alt-right"></i>
    </a>
    <style>
    	.about_us_btn::before,
    	.about_us_btn:hover::before{
    		background: <?php echo esc_attr( $button_bg ); ?>;
    	}
    </style>
	<?php
	return ob_get_clean();
}

add_action( 'bizberg_before_homepage_blog', 'school_of_education_about_us_section' );
function school_of_education_about_us_section(){

	$status = bizberg_get_theme_mod( 'school_of_education_about_us_status' ); 

	if( $status == false ){
		return;
	} ?>

	<section class="school-about">
		<div class="container">
	        <div class="school-about-main">
	            <div class="row">
	                <div class="col-lg-6">
	                </div>
	                <div class="col-lg-6">
	                    <div class="school-about-content">
		                    <h4>
		                    	<?php echo school_of_education_get_about_us_subtitle(); ?>
		                    </h4>
		                    <h3 class="about-title">
		                    	<?php echo school_of_education_get_about_us_title(); ?>
		                    </h3>
		                    <p class="content"><?php echo school_of_education_get_about_us_content(); ?></p>		                    
		                    <div class="button_wrapper">
		                    	<?php echo school_of_education_get_about_us_button(); ?>
		                    </div>
		                </div> 
	                </div>
	            </div>   
	        </div>
		</div>
	</section>

	<?php

	do_action( 'school_of_education_after_about_us' );
	
}