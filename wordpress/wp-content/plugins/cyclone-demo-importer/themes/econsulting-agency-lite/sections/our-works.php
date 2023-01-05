<?php 

function econsulting_agency_our_work_get_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'econsulting_agency_our_work_subtitle' );
	return esc_html( $subtitle );
}

function econsulting_agency_our_work_get_title(){
	$title = bizberg_get_theme_mod( 'econsulting_agency_our_work_title' );
	return esc_html( $title );
}

function econsulting_agency_our_work_get_slider(){ 
	ob_start();
	$sliders = bizberg_get_theme_mod( 'econsulting_agency_our_work_list' );
	$sliders = json_decode( $sliders, true );
	if( !empty( $sliders ) && is_array( $sliders ) ){ ?>
		<div class="work_slider">
			<?php 
			foreach( $sliders as $slider ){

				$title    = !empty( $slider['label'] ) ? $slider['label'] : '';
				$subtitle = !empty( $slider['subtitle'] ) ? $slider['subtitle'] : '';
				$link     = !empty( $slider['link'] ) ? $slider['link'] : '';
				$image    = !empty( $slider['image'] ) ? $slider['image'] : ''; 

				if( is_numeric( $image ) ){
					$image = wp_get_attachment_image_url( $image , 'large' );
				} ?>

		    	<div class="items_slide">
		    		<div class="item_image" style="background-image:url(<?php echo esc_url( $image ); ?>);"></div>
		    		<div class="title_wrapper">
		    			<h4 class="work_title">
		    				<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $title ); ?></a>
		    			</h4>
		    			<span class="work_cat">
		    				<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $subtitle ); ?></a>
		    			</span>
		    		</div>
			    	<a href="<?php echo esc_url( $link ); ?>" class="position-cover"></a>
			    </div>
			    <?php 
			} ?>
		</div>
		<?php
	}
	return ob_get_clean();
}

add_action( 'bizberg_before_homepage_blog', 'econsulting_agency_our_work_section' );
function econsulting_agency_our_work_section(){

	$status = bizberg_get_theme_mod( 'econsulting_agency_our_work_status' );
	if( $status != true ){
		return;
	} ?>

	<section class="econsult-works">
		<div class="container">
			<div class="section-title">
				<p><?php echo econsulting_agency_our_work_get_subtitle(); ?></p>
				<h2><?php echo econsulting_agency_our_work_get_title(); ?></h2>
			</div>
		</div>
		<div class="work_slider_wrapper">
			<?php echo wp_kses_post( econsulting_agency_our_work_get_slider() ); ?>
		</div>
	</section>

	<?php

	do_action( 'econsulting_agency_after_our_work' );

}