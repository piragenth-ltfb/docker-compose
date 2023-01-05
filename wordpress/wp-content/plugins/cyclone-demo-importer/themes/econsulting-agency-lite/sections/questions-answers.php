<?php 

function econsulting_agency_questions_answers_get_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'econsulting_agency_questions_answers_subtitle' );
	return esc_html( $subtitle );
}

function econsulting_agency_questions_answers_get_title(){
	$title = bizberg_get_theme_mod( 'econsulting_agency_questions_answers_title' );
	return esc_html( $title );
}

function econsulting_agency_questions_answers_get_content(){
	$content = bizberg_get_theme_mod( 'econsulting_agency_questions_answers_content' );
	return wp_kses_post( $content );
}

function econsulting_agency_questions_answers_get_faq(){
	ob_start();
	$data = bizberg_get_theme_mod( 'econsulting_agency_questions_answers_list' );
	$data = json_decode( $data, true );
	if( !empty( $data ) && is_array( $data ) ){
		foreach( $data as $key => $value ){
			$title   = !empty( $value['title'] ) ? $value['title'] : '';
			$content = !empty( $value['content'] ) ? $value['content'] : ''; ?>
			<div class="panel panel-default <?php echo ( empty( $key ) ? 'active' : '' ); ?>">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne<?php echo absint($key); ?>">
							<?php echo esc_html( $title ); ?>
							<span class="fas"></span>
						</a>
					</h4>
				</div>
				<div id="collapseOne<?php echo absint($key); ?>" class="panel-collapse collapse <?php echo ( empty( $key ) ? 'in' : '' ); ?>">
					<div class="panel-body">
						<?php echo wp_kses_post( $content ); ?>
					</div>
				</div>
			</div>
			<?php
		}
	}
	return ob_get_clean();
}

add_action( 'bizberg_before_homepage_blog', 'econsulting_agency_questions_answers_section' );
function econsulting_agency_questions_answers_section(){

	$status = bizberg_get_theme_mod( 'econsulting_agency_questions_answers_status' );
	if( $status != true ){
		return;
	} ?>

	<section class="econsult-faq">
		<div class="container">
			<div class="econsult-faq-main">
				<div class="section-title">
					<p class="sub">
						<?php echo econsulting_agency_questions_answers_get_subtitle(); ?>
					</p>
					<h2>
						<?php echo econsulting_agency_questions_answers_get_title(); ?>
					</h2>
					<p class="description"><?php echo econsulting_agency_questions_answers_get_content(); ?></p>
				</div>
				<div class="econsult-faq-accordion">
					<div class="panel-group" id="accordion">
						<?php echo econsulting_agency_questions_answers_get_faq(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php

	do_action( 'econsulting_agency_after_questions_answers' );
	
}