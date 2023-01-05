<?php 

function econsulting_agency_work_process_get_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'econsulting_agency_work_process_subtitle' );
	return esc_html( $subtitle );
}

function econsulting_agency_work_process_get_title(){
	$title = bizberg_get_theme_mod( 'econsulting_agency_work_process_title' );
	return esc_html( $title );
}

function econsulting_agency_work_process_get_content(){
	ob_start();
	$content = bizberg_get_theme_mod( 'econsulting_agency_work_process_list' );
	$content = json_decode( $content, true );
	if( !empty( $content ) && is_array( $content ) ){
		foreach ( $content as $key => $value ) {
			$color   = !empty( $value['color'] ) ? $value['color'] : '';
			$label   = !empty( $value['label'] ) ? $value['label'] : '';
			$content = !empty( $value['content'] ) ? $value['content'] : ''; ?>
			<div class="process_content_items">
				<div class="process_content_items_icon">
					<span style="background: <?php echo esc_attr( $color ); ?>"><?php echo esc_attr( sprintf("%02d", ($key+1) ) ); ?></span>
				</div>
				<div class="process_content_items_content">
					<h4><?php echo esc_attr( $label ); ?></h4>
					<p><?php echo wp_kses_post( $content ); ?></p>
				</div>
			</div>
			<?php
		}
	}
	return ob_get_clean();
}

add_action( 'bizberg_before_homepage_blog', 'econsulting_agency_work_process_section' );
function econsulting_agency_work_process_section(){ 

	$status = bizberg_get_theme_mod( 'econsulting_agency_work_process_status' ); 

	if( $status != true ){
		return;
	} ?>

	<section class="econsult-our-process">
		<div class="container">
			<div class="row">
				<div class="col-md-5">
					<div class="process_image"></div>
				</div>
				<div class="col-md-7">
					<div class="section-title">
						<p><?php echo econsulting_agency_work_process_get_subtitle(); ?></p>
						<h2><?php echo econsulting_agency_work_process_get_title();  ?></h2>
					</div>

					<div class="process_content">
						<?php echo econsulting_agency_work_process_get_content(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php

	do_action( 'econsulting_agency_after_work_process' );
	
}