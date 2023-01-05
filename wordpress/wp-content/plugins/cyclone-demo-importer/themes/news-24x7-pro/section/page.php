<?php 

function news_24x7_get_homepage_news_section_6( $data ){ 

	$page_id        = !empty( $data['layout_6_page'] ) ? $data['layout_6_page'] : '';
	$bg_color       = !empty( $data['layout_6_bg_color'] ) ? $data['layout_6_bg_color'] : '#f7f7f7';
	$spacing_top    = !empty( $data['layout_6_spacing_top'] ) || $data['layout_6_spacing_top'] == 0 ? $data['layout_6_spacing_top'] : '40';
	$spacing_bottom = !empty( $data['layout_6_spacing_bottom'] ) || $data['layout_6_spacing_bottom'] == 0 ? $data['layout_6_spacing_bottom'] : '40';

	if( empty( $page_id ) ){
		return;
	}

	$args = array(
		'post_type'      => 'page',
		'posts_per_page' => 1,
		'page_id'        => $page_id
	);  

	$page_query = new WP_Query( $args );

	if( $page_query->have_posts() ):

		while( $page_query->have_posts() ): $page_query->the_post(); ?>

			<div 
			class="news_24x7_homepage_news_section_6" 
			style="background-color:<?php echo esc_attr( $bg_color ); ?>;margin-top:<?php echo esc_attr( $spacing_top ); ?>px;margin-bottom:<?php echo esc_attr( $spacing_bottom ); ?>px;">
				
				<div class="container">
					
					<?php the_content(); ?>

				</div>

			</div>

			<?php

		endwhile;

	endif;

	wp_reset_postdata();

}