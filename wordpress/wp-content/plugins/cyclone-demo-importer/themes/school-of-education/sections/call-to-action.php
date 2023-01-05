<?php

function school_of_education_get_call_to_action_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'school_of_education_call_to_action_subtitle' );
	return esc_html( $subtitle );
}

function school_of_education_get_call_to_action_title(){
	$title = bizberg_get_theme_mod( 'school_of_education_call_to_action_title' );
	return esc_html( $title );
}

function school_of_education_get_call_to_action_description(){
	$description = bizberg_get_theme_mod( 'school_of_education_call_to_action_description' );
	return wp_kses_post( nl2br( $description ) );
}

function school_of_education_get_call_to_action_button(){ 

	ob_start();

	$label = bizberg_get_theme_mod( 'school_of_education_call_to_action_btn_label' );
	$link  = bizberg_get_theme_mod( 'school_of_education_call_to_action_btn_link' ); ?>

	<a href="<?php echo esc_url( $link ); ?>" class="per-btn">
       <span><?php echo esc_html( $label ); ?></span><i class="fa fa-long-arrow-alt-right"></i>
    </a>
	<?php
	return ob_get_clean();

}

add_action( 'bizberg_before_homepage_blog', 'school_of_education_call_to_action_section' );
function school_of_education_call_to_action_section(){ 

	$status = bizberg_get_theme_mod( 'school_of_education_call_to_action_status' );

	if( $status == false ){
		return;
	} ?>

	<section class="school-call-to-action">
		<div class="container">
			<div class="row">
				<div class="col-md-6"></div>
				<div class="col-md-6">
					<div class="school-call-content">
						<h4>
							<?php 
							echo school_of_education_get_call_to_action_subtitle();
							?>
						</h4>
						<h3>
							<?php 
							echo school_of_education_get_call_to_action_title();
							?>
						</h3>
						<p><?php echo school_of_education_get_call_to_action_description(); ?></p>
						<div class="call_btn_wrapper">
							<?php echo school_of_education_get_call_to_action_button(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php

	do_action( 'school_of_education_after_call_to_action' );
	
}