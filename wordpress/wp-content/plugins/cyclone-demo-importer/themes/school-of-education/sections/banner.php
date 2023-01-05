<?php

function school_of_education_get_banner_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'school_of_education_banner_subtitle' );
	return wp_kses_post( $subtitle );
}

function school_of_education_get_banner_title(){
	$title = bizberg_get_theme_mod( 'school_of_education_banner_title' );
	return wp_kses_post( $title );
}

function school_of_education_get_banner_content(){
	$content = bizberg_get_theme_mod( 'school_of_education_banner_content' );
	return wp_kses_post( $content );
}

function school_of_education_get_banner_buttons(){
	$buttons_arr = bizberg_get_theme_mod( 'school_of_education_banner_buttons' );
	$buttons_arr = is_array( $buttons_arr ) ? $buttons_arr : json_decode( urldecode( $buttons_arr ), true );
	$text = '';
	if( !empty( $buttons_arr ) && is_array( $buttons_arr ) ){
		foreach( $buttons_arr as $key => $button ){

			$title             = !empty( $button['title'] ) ? esc_attr( $button['title'] ) : '';
			$link              = !empty( $button['link'] ) ? esc_url( $button['link'] ) : '';
			$background_color  = !empty( $button['background_color'] ) ? esc_attr( $button['background_color'] ) : '';
			$text_color        = !empty( $button['text_color'] ) ? esc_attr( $button['text_color'] ) : '';
			$id                = 'banner_button_' . $key;

			$text .= '<a href="' . $link . '" class="per-btn" id="' . $id . '" style="color:' . $text_color  . '">
	                    <span>' . $title . '</span><i class="fa fa-long-arrow-alt-right"></i>
	                </a>';

	        $text .= '<style>#' . $id . '::before{ background: ' . $background_color . ' }</style>';
		}
	}
	return $text;
}

add_action( 'bizberg_before_homepage_blog', 'school_of_education_banner_section' );
function school_of_education_banner_section(){ ?>

	<div id="tf-partical-wrap" class=" wow fadeIn" data-wow-duration="0.8s" data-wow-delay="0.35s"></div>

	<?php 

	$status = bizberg_get_theme_mod( 'school_of_education_banner_status' );
	if( $status == false ){
		return;
	}

	?>

	<section class="school-banner">
		<div class="container">
			<div class="row">				
				<div class="col-md-5">
					<div class="banner-content-image"></div>
				</div>
				<div class="col-md-7">
					<div class="banner-content">
						<h4 class="elementor-title1">
							<?php echo school_of_education_get_banner_subtitle(); ?>
						</h4>
						<h1 class="title elementor-title1">
							<a href="javascript:void(0)">
								<?php echo school_of_education_get_banner_title(); ?>
							</a>
						</h1>
						<p><?php echo school_of_education_get_banner_content(); ?></p>
						<div class="banner-btn">
	                        <?php echo school_of_education_get_banner_buttons(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php

	do_action( 'school_of_education_after_banner' );
	
}