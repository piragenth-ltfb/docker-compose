<?php

function school_of_education_get_pricing_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'school_of_education_pricing_subtitle' );
	return esc_html( $subtitle );
}

function school_of_education_get_pricing_title(){
	$title = bizberg_get_theme_mod( 'school_of_education_pricing_title' );
	return esc_html( $title );
}

function school_of_education_pricing_content() { 

	for ( $i = 1; $i <= apply_filters( 'school_of_education_pricing_limit', 3 ); $i++ ) {

		$status         = bizberg_get_theme_mod( 'school_of_education_pricing_content_status_' . $i );
		$title          = bizberg_get_theme_mod( 'school_of_education_pricing_content_title_' . $i );
		$currency       = bizberg_get_theme_mod( 'school_of_education_pricing_content_currency_' . $i );
		$price          = bizberg_get_theme_mod( 'school_of_education_pricing_content_price_' . $i );
		$duration       = bizberg_get_theme_mod( 'school_of_education_pricing_content_duration_' . $i );
		$description    = bizberg_get_theme_mod( 'school_of_education_pricing_content_description_' . $i );
		$color          = bizberg_get_theme_mod( 'school_of_education_pricing_content_color_' . $i );
		$btn_label      = bizberg_get_theme_mod( 'school_of_education_pricing_content_btn_label_' . $i );
		$btn_link       = bizberg_get_theme_mod( 'school_of_education_pricing_content_btn_link_' . $i );
		$btn_background = bizberg_get_theme_mod( 'school_of_education_pricing_content_btn_background_' . $i );
		$btn_color      = bizberg_get_theme_mod( 'school_of_education_pricing_content_btn_text_color_' . $i );
		$class1         = $class = 'pricing_' . $i; 
		$class1        .= ( $i == 1 ? ' col-md-4 col-sm-12 col-xs-12' : ' col-md-4 col-sm-6 col-xs-12' ); 

		if( $status == true ){  ?>
			<div class="<?php echo esc_attr( $class1 ); ?>">
			    <div class="price-box">
			    	<div class="price_box_inner_wrapper">
				        <div class="price-header">
				            <h4 style="color: <?php echo esc_attr( $color ); ?>"><?php echo esc_html( $title ); ?></h4>
				            <h2 style="color: <?php echo esc_attr( $color ); ?>">
				            	<sup class="dolers"><?php echo esc_html( $currency ); ?></sup>
				            	<span><?php echo esc_html( $price ); ?></span>
				            	<?php 
				            	if( !empty( $duration ) ){ ?>
				            		<small>/<?php echo esc_html( $duration ); ?></small>
				            		<?php 
				            	} ?>
				            </h2>
				        </div>
				        <div class="price-item-list">

				        	<?php 
				        	$points = explode( "\n" , $description );
				        	if( array_filter( $points ) ){ ?>
					            <ul>
					               	<?php 
					               	foreach ( $points as $key => $value ) {
					               		echo '<li>' . esc_html( $value ) . '</li>';
					               	}
					               	?>
					            </ul>
					            <?php 
					        } ?>

				        </div>
				        <div class="price-link">
				            <a href="<?php echo esc_url( $btn_link ); ?>" class="per-btn">
				                <span style="color: <?php echo esc_attr( $btn_color ); ?>">
				                	<?php echo esc_html( $btn_label ); ?>
				                </span><i class="fa fa-long-arrow-alt-right"></i>
				            </a>
				        </div>
				    </div>
			    </div>
		    </div>
		    <style>
		    	.<?php echo esc_attr( $class ); ?> .price-box .per-btn::before{
		    		background: <?php echo esc_attr( $btn_background ); ?>;
		    	}
		    	.<?php echo esc_attr( $class ); ?> .price-box::before,.<?php echo esc_attr( $class ); ?> .price-box::after {
		    		background: <?php echo esc_attr( $color ); ?>;
		    	}
		    	.<?php echo esc_attr( $class ); ?> .price_box_inner_wrapper::before, .<?php echo esc_attr( $class ); ?> .price_box_inner_wrapper::after {
		    		background: <?php echo esc_attr( $color ); ?>;
		    	}
		    </style>
		    <?php
		}
	}
}

add_action( 'bizberg_before_homepage_blog', 'school_of_education_pricing' );
function school_of_education_pricing(){

	$status = bizberg_get_theme_mod( 'school_of_education_pricing_status' );

	if( $status == false ){
		return;
	} ?>

	<section class="school-pricing">
	    <div class="container">
	    	<div class="school-section-title">
				<h4>
					<?php echo school_of_education_get_pricing_subtitle(); ?>
				</h4>
				<h3>
					<?php echo school_of_education_get_pricing_title(); ?>
				</h3>
			</div>
	        <div class="price-list">
	            <div class="row">

	            	<?php
	            	school_of_education_pricing_content();
	            	?>

	            </div>
	        </div>
	    </div>
	</section>

	<?php

	do_action( 'school_of_education_after_pricing' );

}