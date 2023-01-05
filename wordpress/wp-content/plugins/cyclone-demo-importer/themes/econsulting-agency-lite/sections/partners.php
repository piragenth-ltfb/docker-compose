<?php 

function econsulting_agency_partners_get_title(){
	$title = bizberg_get_theme_mod( 'econsulting_agency_partners_title' );
	return esc_html( $title );
}

function econsulting_agency_partners_get_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'econsulting_agency_partners_subtitle' );
	return esc_html( $subtitle );
}

function econsulting_agency_partners_get_content(){

	ob_start();
	$data = bizberg_get_theme_mod('econsulting_agency_partners_list');
	$data = json_decode( $data, true );
	if( !empty( $data ) && is_array( $data ) ){
		foreach( $data as $key => $value ){ 

			$image = !empty( $value['partner_image'] ) ? $value['partner_image'] : '';

			if( is_numeric( $image ) ){
				$image = wp_get_attachment_image_url( $image , 'full' );
			} ?>

			<div class="econsult-partner-in">
				<div class="econsult-partner-item">
					<div class="econsult-partner-img">
			        	<div class="econsult-partner-mainimg">
			        		 <img src="<?php echo esc_url( $image ); ?>" alt="img" class="main-img">
			        	</div>
			        	<div class="econsult-partner-hoverimg">
			        		 <img src="<?php echo esc_url( $image ); ?>" alt="img" class="hover-img">
			        	</div>
		        	</div>
		        </div>
	    	</div>

			<?php
		}
	}
	return ob_get_clean();

}

add_action( 'bizberg_before_homepage_blog', 'econsulting_agency_partners_section' );
function econsulting_agency_partners_section(){

	$status = bizberg_get_theme_mod( 'econsulting_agency_partners_status' ); 

	if( $status != true ){
		return;
	} ?>

	<section class="econsult-partner">
		<div class="container">
			<div class="section-title">
				<p><?php echo econsulting_agency_partners_get_subtitle(); ?></p>
				<h2><?php echo econsulting_agency_partners_get_title(); ?></h2>
			</div>
			<div class="econsult-partner-main">
				<?php echo wp_kses_post( econsulting_agency_partners_get_content() ); ?>
			</div>
		</div>
	</section>

	<?php

	do_action( 'econsulting_agency_after_partners' );
	
}