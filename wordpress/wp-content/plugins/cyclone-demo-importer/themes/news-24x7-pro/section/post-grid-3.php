<?php 

function news_24x7_get_homepage_news_section_5( $data ){

	$title            = !empty( $data['layout_5_title'] ) ? $data['layout_5_title'] : '';
	$categories       = !empty( $data['layout_5_categories'] ) ? $data['layout_5_categories'] : ''; 
	$limit            = !empty( $data['layout_5_limit'] ) ? $data['layout_5_limit'] : '6';
	$columns          = !empty( $data['layout_5_columns'] ) ? $data['layout_5_columns'] : '2|4';
	$title_line_color = !empty( $data['layout_5_title_line_color'] ) ? $data['layout_5_title_line_color'] : '#e5e5e5';
	$bg_color         = !empty( $data['layout_5_bg_color'] ) ? $data['layout_5_bg_color'] : '#f7f7f7';
	$thumbnail_status = !empty( $data['layout_5_thumbnail_status'] ) ? $data['layout_5_thumbnail_status'] : 'false';
	$spacing_top      = !empty( $data['layout_5_spacing_top'] ) || $data['layout_5_spacing_top'] == 0 ? $data['layout_5_spacing_top'] : '40';
	$spacing_bottom   = !empty( $data['layout_5_spacing_bottom'] ) || $data['layout_5_spacing_bottom'] == 0 ? $data['layout_5_spacing_bottom'] : '40'; ?>

	<div 
	class="news_24x7_post_grid_3" 
	style="background-color:<?php echo esc_attr( $bg_color ); ?>;margin-top: <?php echo esc_attr( $spacing_top ); ?>px;margin-bottom: <?php echo esc_attr( $spacing_bottom ); ?>px;">
		
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
		  		'post_status'    => 'publish',
		  		'posts_per_page' => ( $columns == '2|4' ? 2 : 3 )
		  	);

		  	if( !empty( $categories[0] ) ){
				$args['category__in'] = $categories;
			}

		  	$ignore_ids = [];

		  	$post_query = new WP_Query( $args ); ?>

			<div class="content">

				<?php 

			  	if( $post_query->have_posts() ): ?>
			  		
			  		<div class="news_row top">
			  			
			  			<div class="column <?php echo esc_attr( 'col_' . str_replace( '|' , '', $columns ) ); ?>">

			  				<?php 

			  				while( $post_query->have_posts() ): $post_query->the_post(); 

			  					global $post; 

			  					$post_id         = $post->ID;
			  					$category_detail = get_the_category( $post_id );
	   							$cat_name        = !empty( $category_detail[0]->name ) ? $category_detail[0]->name : '';

	   							$ignore_ids[] = $post_id; ?>
			  				
				  				<div class="post_list">

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
					  				<div class="image" style="background-image:url(<?php the_post_thumbnail_url( 'large' ); ?>)"></div>
									<a class="link" href="<?php the_permalink(); ?>"></a>

					  			</div>

					  			<?php 

					  		endwhile; 

					  		wp_reset_postdata(); ?>

			  			</div>

			  		</div>

			  		<?php 

			  	endif;

			  	$args1 = array(
			  		'post_type'      => 'post',
			  		'post_status'    => 'publish',
			  		'posts_per_page' => ( $columns == '2|4' ? ($limit-2) : ($limit-3) ),
			  		'post__not_in'   => $ignore_ids
			  	);

			  	if( !empty( $categories[0] ) ){
					$args1['category__in'] = $categories;
				}

			  	$post_query1 = new WP_Query( $args1 );

			  	if( $post_query1->have_posts() && $args1['posts_per_page'] > 0 ): ?>

		  			<div class="news_row bottom">

		  				<div class="column">

		  					<?php 

		  					while( $post_query1->have_posts() ): $post_query1->the_post();

		  						global $post;

		  						$post_id         = $post->ID;
			  					$category_detail = get_the_category( $post_id );
	   							$cat_name        = !empty( $category_detail[0]->name ) ? $category_detail[0]->name : ''; ?>
			  				
				  				<div class="list">
							  		
							  		<?php 
							  		if( has_post_thumbnail() && $thumbnail_status == 'false' ){ ?>	
					  					<div class="image_wrapper">
							  				<a href="<?php the_permalink(); ?>" class="image_link">
							  					<div class="image" style="background-image:url(<?php the_post_thumbnail_url( 'large' ); ?>)"></div>
							  				</a>
						  				</div>
						  				<?php 
						  			} ?>
						  				
					  				<h4>
					  					<a href="<?php the_permalink(); ?>">
					  						<?php the_title(); ?>				  					
					  					</a>
					  				</h4>

					  				<p class="time_ago">
					  					
					  					<?php 
					  					if( !empty( $cat_name ) ){ ?>
						  					<small>
						  						<a href="<?php echo esc_url( get_category_link( $category_detail[0] ) ); ?>">
						  							<?php echo esc_html( $cat_name ); ?>				  						
						  						</a>
							  				</small>
							  				<?php 
							  			} ?>

						  				<small class="time">
											<?php 
				  							echo esc_html( news_24x7_time_elapsed_string( $post->post_date ) ); 
				  							?>									
										</small>
									</p>

					  			</div>

					  			<?php 

					  		endwhile; ?>

			  			</div>

			  		</div>

			  		<?php 

			  	endif; 

			  	wp_reset_postdata(); ?>

		  	</div>

		</div>

	</div>

	<?php
}