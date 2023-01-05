<?php 

function news_24x7_get_homepage_news_section_7( $data ){

	$title                 = !empty( $data['layout_7_title'] ) ? $data['layout_7_title'] : '';
	$categories            = !empty( $data['layout_7_categories'] ) ? $data['layout_7_categories'] : '';
	$limit                 = !empty( $data['layout_7_limit'] ) ? $data['layout_7_limit'] : '5';
	$title_line_color      = !empty( $data['layout_7_title_line_color'] ) ? $data['layout_7_title_line_color'] : '#e5e5e5';
	$bg_color              = !empty( $data['layout_7_bg_color'] ) ? $data['layout_7_bg_color'] : '#f7f7f7';
	$spacing_top           = !empty( $data['layout_7_spacing_top'] ) || $data['layout_7_spacing_top'] == 0 ? $data['layout_7_spacing_top'] : '40';
	$spacing_bottom        = !empty( $data['layout_7_spacing_bottom'] ) || $data['layout_7_spacing_bottom'] == 0 ? $data['layout_7_spacing_bottom'] : '40'; 
	$layout                = !empty( $data['layout_7_layout'] ) ? $data['layout_7_layout'] : '1'; 
	$image_height          = !empty( $data['layout_7_image_height'] ) ? $data['layout_7_image_height'] : '350';

	$id = 'news_24x7_post_grid_4_' . wp_generate_password( 12, false, false ); ?>

	<style>
		
		<?php 
		switch ( $layout ) {

			case '1':
				echo '#' . $id . ' .content .item:first-child{grid-column: span 2;}';
				break;

			case '2':
				echo '#' . $id . ' .content .item:first-child{grid-column: span 2;}';
				echo '#' . $id . ' .content .item:last-child{grid-column: span 2;}';
				break;

			case '4':
				echo '#' . $id . ' .content .item:nth-child(1){grid-column: span 2;}';
				echo '#' . $id . ' .content .item:nth-child(4){grid-column: span 2;}';
				echo '#' . $id . ' .content .item:nth-child(5){grid-column: span 2;}';
				echo '#' . $id . ' .content .item:nth-child(8){grid-column: span 2;}';
				echo '#' . $id . ' .content .item:nth-child(9){grid-column: span 2;}';
				echo '#' . $id . ' .content .item:nth-child(12){grid-column: span 2;}';
				echo '#' . $id . ' .content .item:nth-child(13){grid-column: span 2;}';
				echo '#' . $id . ' .content .item:nth-child(16){grid-column: span 2;}';
				echo '#' . $id . ' .content .item:nth-child(17){grid-column: span 2;}';
				echo '#' . $id . ' .content .item:nth-child(20){grid-column: span 2;}';
				break;
			
			default:
				// code...
				break;
		}
		?>

	</style>

	<div 
	id = "<?php echo esc_attr( $id ); ?>"
	class="news_24x7_post_grid_4"
	style="background-color: <?php echo esc_attr( $bg_color ); ?>;margin-top: <?php echo esc_attr( $spacing_top ); ?>px;margin-bottom: <?php echo esc_attr( $spacing_bottom ); ?>px;">

		<div class="container">
			
			<?php 
			if( !empty( $title ) ){ ?>
				<div class="section-title news24x7">
					<h4 class="related-title">
						<span class="title">
							<?php echo esc_html( $title ); ?>						
						</span>
						<span class="titledot"></span>
						<span class="titleline" style="border-color:<?php echo esc_attr( $title_line_color ); ?>"></span>
					</h4>		
			  	</div>
		  		<?php 
		  	}

		  	$args = array(
		  		'post_type'      => 'post',
		  		'posts_per_page' => $limit,
		  		'post_status'    => 'publish'
		  	);

		  	if( !empty( $categories[0] ) ){
				$args['category__in'] = $categories;
			}

		  	$post_quey = new WP_Query( $args );

		  	if( $post_quey->have_posts() ):	?>

			  	<div class="content">

			  		<?php 

			  		while( $post_quey->have_posts() ): $post_quey->the_post();

			  			global $post; 

	  					$post_id         = $post->ID;
	  					$category_detail = get_the_category( $post_id );
						$cat_name        = !empty( $category_detail[0]->name ) ? $category_detail[0]->name : ''; ?>
			  		
				  		<div class="item">
									
							<div class="image" 
							style="background-image:url(<?php the_post_thumbnail_url( 'large' ); ?>);height: <?php echo esc_attr( $image_height ); ?>px;">
								
								<div class="meta-info">

									<?php 
					  				if( !empty( $cat_name ) ){ ?>
										<a href="<?php echo esc_url( get_category_link( $category_detail[0] ) ); ?>" class="post-category">
											<?php echo esc_html( $cat_name ); ?>
										</a>
										<?php 
						  			} ?>
																				
									<div class="title-wrap">
										<h4 class="title">
											<a href="<?php the_permalink(); ?>">
												<?php the_title(); ?>		
											</a>
										</h4> 
									</div>
								</div>

								<div class="overlay"></div>

								<a href="<?php the_permalink(); ?>" class="full_image_link"></a>

							</div>

						</div>

						<?php 

					endwhile; ?>

			  	</div>

		  		<?php 

		  	endif;

		  	wp_reset_postdata(); ?>

		</div>

	</div>

	<?php
}