<?php 

function econsulting_agency_services_section_get_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'econsulting_agency_services_subtitle' );
	return esc_html( $subtitle );
}

function econsulting_agency_services_section_get_title(){
	$title = bizberg_get_theme_mod( 'econsulting_agency_services_title' );
	return esc_html( $title );
}

function econsulting_agency_services_section_get_content(){ 
	ob_start();
	$data = bizberg_get_theme_mod( 'econsulting_agency_services_list' );
	$data = json_decode( $data, true );
	if( !empty( $data ) && is_array( $data ) ){
		foreach( $data as $key => $value ){ 
			$icon       = !empty( $value['icon'] ) ? $value['icon'] : '';
			$label      = !empty( $value['label'] ) ? $value['label'] : '';
			$content    = !empty( $value['content'] ) ? $value['content'] : ''; 
			$link       = !empty( $value['link'] ) ? $value['link'] : 'javascript:void(0)';
			$link_label = !empty( $value['link_label'] ) ? $value['link_label'] : '';
			$color      = !empty( $value['color'] ) ? $value['color'] : '#ff4a17';  ?>
			<div class="col-md-3 col-sm-6 econsult-col service_section_<?php echo absint( $key ); ?>">
				<div class="econsult-services-item">
					<div class="econsult-services-icon">
						<i class="<?php echo esc_html( $icon ); ?>"></i>
					</div>
					<div class="econsult-services-content">
						<h4>
							<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $label ); ?></a>
						</h4>
						<p><?php echo esc_html( $content ); ?></p>
					</div>
					<div class="econsult-services-btn">
						<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $link_label ); ?> <i class="fa fa-angle-right"></i></a>
					</div>
				</div>
				<style>
					.service_section_<?php echo absint( $key ); ?> .econsult-services-icon i {
						color: <?php echo esc_attr( $color ); ?>;
					}
					.service_section_<?php echo absint( $key ); ?> .econsult-services-item:hover h4 a {
						color: <?php echo esc_attr( $color ); ?>;	
					}
					.service_section_<?php echo absint( $key ); ?> .econsult-services-btn a i {
						background : <?php echo esc_attr( Kirki_Color::adjust_brightness( $color, 95 ) ); ?>;
					}
					.service_section_<?php echo absint( $key ); ?> .econsult-services-item:hover .econsult-services-btn a {
						color: <?php echo esc_attr( $color ); ?>;
					}
					.service_section_<?php echo absint( $key ); ?> .econsult-services-item:hover .econsult-services-btn a i {
						background: <?php echo esc_attr( $color ); ?>;
					}
				</style>
			</div>
			<?php
		}
	}
	return ob_get_clean();
}

add_action( 'bizberg_before_homepage_blog', 'econsulting_agency_services_section' );
function econsulting_agency_services_section(){ ?>

	<section class="econsult-services">
		<div class="container">
			<div class="econsult-services-in">
				<div class="section-title">
					<p><?php echo econsulting_agency_services_section_get_subtitle(); ?></p>
					<h2><?php echo econsulting_agency_services_section_get_title(); ?></h2>
				</div>
				<div class="econsult-services-main">
					<?php echo econsulting_agency_services_section_get_content(); ?>
				</div>
			</div>
		</div>
	</section>

	<?php

	do_action( 'econsulting_agency_after_services' );
	
}