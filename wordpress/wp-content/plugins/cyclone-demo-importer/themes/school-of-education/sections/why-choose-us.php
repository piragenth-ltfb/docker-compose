<?php

function school_of_education_get_why_choose_us_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'school_of_education_why_choose_us_subtitle' );
	return esc_html( $subtitle );
}

function school_of_education_get_why_choose_us_title(){
	$title = bizberg_get_theme_mod( 'school_of_education_why_choose_us_title' );
	return esc_html( $title );
}

function school_of_education_why_choose_us_section_content(){ 

	ob_start();

	$content = bizberg_get_theme_mod( 'school_of_education_why_choose_us_repeater' );
	$content = is_array( $content ) ? $content : json_decode( urldecode( $content ), true );

	foreach( $content as $key => $value ){

		$title   = !empty( $value['title'] ) ? $value['title'] : '';
		$icon    = !empty( $value['icon'] ) ? $value['icon'] : '';
		$content = !empty( $value['content'] ) ? $value['content'] : '';
		$color   = !empty( $value['color'] ) ? $value['color'] : '#000';
		$id      = 'why_choose_us_' . $key; ?>

		<div class="col-lg-6 col-md-6 <?php echo esc_attr( $id ); ?>">
			<div class="school-choose-list hover_animation_1">
				<div class="school-choose-list-inner hover_animation_1_inner">
		            <div class="school-choose-icon">
		                <i style="background: <?php echo esc_attr( $color ); ?>" class="<?php echo esc_attr( $icon ); ?>"></i>
		            </div>
		            <div class="school-choose-content">
		                <h4 class="title hover_underline_animation" style="color:<?php echo esc_attr( $color ); ?>">
		                	<?php echo esc_html( $title ); ?>
		                </h4>
		                <p class="text">
		                	<?php echo esc_html( $content ); ?>
		                </p>
		            </div>
	            </div>
	        </div>
		</div>

		<style>
			.<?php echo esc_attr( $id ); ?> .hover_animation_1_inner::before,
			.<?php echo esc_attr( $id ); ?> .hover_animation_1_inner::after,
			.<?php echo esc_attr( $id ); ?> .hover_animation_1::before, 
			.<?php echo esc_attr( $id ); ?> .hover_animation_1::after {
				background: <?php echo esc_attr( $color ); ?>;
			}
		</style>

		<?php

	}

	return ob_get_clean();

}

add_action( 'bizberg_before_homepage_blog', 'school_of_education_why_choose_us_section' );
function school_of_education_why_choose_us_section(){ 

	$status = bizberg_get_theme_mod( 'school_of_education_why_choose_us_status' );

	if( $status == false ){
		return;
	}

	$content = bizberg_get_theme_mod( 'school_of_education_why_choose_us_repeater' );
	$content = is_array( $content ) ? $content : json_decode( urldecode( $content ), true ); ?>

	<section class="school-choose">
		<div class="container">
			<div class="school-section-title">
				<h4>
					<?php echo school_of_education_get_why_choose_us_subtitle(); ?>
				</h4>
				<h3>
					<?php 
					echo school_of_education_get_why_choose_us_title();
					?>
				</h3>
			</div>
			<div class="row">
				<?php 
				if( !empty( $content ) && is_array( $content ) ){
					echo school_of_education_why_choose_us_section_content();
				} ?>
			</div>
		</div>
	</section>

	<?php

	do_action( 'school_of_education_after_why_choose_us' );
	
}