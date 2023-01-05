<?php 

function econsulting_agency_video_get_title(){
	$title = bizberg_get_theme_mod( 'econsulting_agency_video_title' );
	return esc_html( $title );
}

function econsulting_agency_video_get_button(){
	$label = bizberg_get_theme_mod( 'econsulting_agency_video_button_text' );
	$link  = bizberg_get_theme_mod( 'econsulting_agency_video_button_link' );
	return '<a href="' . esc_url( $link ) . '" class="per-btn"><span>' . esc_html( $label ) . '</span><i class="fa fa-long-arrow-alt-right"></i></a>';
}

function econsulting_agency_get_video(){

	ob_start();
	$channel = bizberg_get_theme_mod( 'econsulting_agency_video_url' );
	$id = '';

	if( $channel == 'youtube' ){
		$link = bizberg_get_theme_mod( 'econsulting_agency_video_url_youtube' );
		preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^\?&\"'>]+)/", $link, $matches);
		$id = !empty( $matches[1] ) ? $matches[1] : '';
	} else {
		$link = bizberg_get_theme_mod( 'econsulting_agency_video_url_vimeo' );
		preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/?(showcase\/)*([0-9))([a-z]*\/)*([0-9]{6,11})[?]?.*/", $link, $matches);
		$id = !empty( $matches[6] ) ? $matches[6] : '';
	} 

	if( empty( $id ) ){
		return false;
	} ?>

	<a href="#" 
	class="js-video-button" 
	data-video-id="<?php echo esc_attr( $id ); ?>" 
	data-channel="<?php echo esc_attr( $channel ); ?>">
		<div class="econsult-video-btn">						
			<i class="fas fa-play"></i>
			<i class="ripple"></i>
		</div>
	</a>
	<?php
	return ob_get_clean();
}

add_action( 'bizberg_before_homepage_blog', 'econsulting_agency_video_section' );
function econsulting_agency_video_section(){

	$status = bizberg_get_theme_mod( 'econsulting_agency_video_status' );

	if( $status != true ){
		return;
	} ?>

	<section class="econsult-call-to-action">
		<div class="container">
			<div class="row">
				<div class="col-md-7">
					<h2 class="title"><?php echo econsulting_agency_video_get_title(); ?></h2>
					<div class="button_wrapper">
					 	<?php echo wp_kses_post( econsulting_agency_video_get_button() ); ?>
					</div>
				</div>
				<div class="col-md-5 icon_wrapper">
					<?php echo wp_kses_post( econsulting_agency_get_video() ); ?>
				</div>
			</div>
		</div>
		<div class="overlay"></div>
	</section>

	<?php

	do_action( 'econsulting_agency_after_video' );

}