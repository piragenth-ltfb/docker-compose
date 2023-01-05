<?php

function artistic_blog_featured_title_subtitle(){

	$section_title = bizberg_get_theme_mod( 'featured_post_title' );
	$section_subtitle = bizberg_get_theme_mod( 'featured_post_subtitle' );

	if( empty( $section_title ) && empty( $section_subtitle ) ){
		return;
	}

	ob_start(); ?>

	<div class="section-heading-wrapper">
		<div class="section-title-wrapper">
			<h2 class="section-title"><?php echo wp_kses_post( $section_title ); ?></h2>
		</div>							
		<div class="section-description">
			<p><?php echo wp_kses_post( $section_subtitle ); ?></p>
		</div>								
	</div>

	<?php
	return ob_get_clean();

}

/* Add Featured Section */
add_action( 'bizberg_before_homepage_blog', 'artistic_blog_featured_posts' );
function artistic_blog_featured_posts(){

	if( bizberg_get_theme_mod( 'featured_post_status' ) != true ){
		return;
	} ?>

    <section class="artistic-blog-post featured_posts_wrapper">
        <div class="container">
            <div class="artistic-blog-post-outer">
            	<div class="row">
            		<div class="col-lg-12 col-md-12 col-xs-12 title_subtitle_wrapper">
            			<?php 
            			$title_subtitle = artistic_blog_featured_title_subtitle();
            			echo wp_kses_post( $title_subtitle );
            			?>
            		</div>
            	</div>

            	<?php 
            	$args = array(
            		'post_type'           => 'post',
            		'post_status'         => 'publish',
            		'posts_per_page'      => bizberg_get_theme_mod( 'featured_post_limit' ),
            		'ignore_sticky_posts' => 1
            	);

            	$featured_post_grid_category = bizberg_get_theme_mod( 'featured_post_grid_category' );
            	if( !empty( array_filter( $featured_post_grid_category ) ) ){
			        $args['category__in'] = $featured_post_grid_category;
			    }

            	$featured_query = new WP_Query( $args );

            	$columns                = bizberg_get_theme_mod( 'featured_post_column' );
            	$meta_options           = bizberg_get_theme_mod( 'featured_post_grid_meta_options' );
                $featured_overlay_color = bizberg_get_theme_mod( 'featured_overlay_color' );

            	if( $featured_query->have_posts() ): ?>

            		<div class="row">

	            		<?php

	            		while( $featured_query->have_posts() ): $featured_query->the_post();

	            			global $post;

	            			$category_detail = get_the_category( $post->ID );
	            			$category        =  bizberg_post_categories( $post, 1, false, false );
	            			$cat_name        = !empty( $category_detail[0]->name ) ? $category_detail[0]->name : ''; ?>
			                
		                    <div class="<?php artistic_blog_featured_col( $columns ); ?> mb-3">
		                        <div class="artistic-blog-post-item">
		                            <div class="artistic-blog-post-bg" style="background-image: linear-gradient( to right,<?php echo esc_attr( $featured_overlay_color ); ?>,<?php echo esc_attr( $featured_overlay_color ); ?> ),url( <?php the_post_thumbnail_url( 'medium_large' ); ?> );"></div>

		                            <?php 
		                            if( !empty( $cat_name ) ){ ?>
		                            	<div class="artistic-blog-post-cats">
		                            		<a class="featured_cat_background_<?php echo absint( $category_detail[0]->term_id ); ?>" href="<?php echo esc_url( get_category_link( $category_detail[0] ) ); ?>"><?php echo esc_html( $cat_name ); ?></a>
		                            	</div>
		                            	<?php 
		                            } ?>

		                            <div class="artistic-blog-post-content">
		                                <div class="artistic-blog-post-list">
		                                    <?php 
                                            if( !empty( $meta_options ) ){ ?>
                                                <div class="meta2">
                                                    <ul>
                                                        <?php 
                                                        if( in_array( 'date' , $meta_options ) ){ ?>
                                                            <li>
                                                            <a href="<?php echo esc_url( home_url() ); ?>/<?php echo esc_attr( date( 'Y/m' , strtotime( get_the_date() ) ) ); ?>">
                                                                <i class="far fa-calendar"></i>
                                                                <?php 
                                                                echo esc_html( get_the_date() ); 
                                                                ?> 
                                                                </a>
                                                            </li>
                                                            <?php 
                                                        } 

                                                        if( in_array( 'category' , $meta_options ) ){ ?>
                                                            <li>
                                                                <?php 
                                                                echo wp_kses_post( $category ); 
                                                                ?> 
                                                            </li>
                                                            <?php 
                                                        } 

                                                        if( in_array( 'comment' , $meta_options ) ){ ?>
                                                            <li>
                                                                <?php 
                                                                bizberg_get_comments_number( $post );
                                                                ?>   
                                                            </li>
                                                            <?php
                                                        } ?>
                                                    </ul>
                                                </div>
                                                <?php 
                                            } ?>
		                                </div>
		                                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
		                            </div>
		                            
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
        
    </section>

    <?php
}

function artistic_blog_featured_col( $col ){

	switch ( $col ) {

		case '2':
			echo 'col-lg-6 col-md-6 col-xs-12';
			break;
		
		default:
			echo 'col-lg-4 col-md-6 col-xs-12';
			break;
	}

}

add_filter( 'bizberg_inline_style', function( $inline_css ){

    $featured_category_colors = bizberg_get_theme_mod( 'featured_category_colors' );

    if( empty( $featured_category_colors ) ){
        return $inline_css;
    }

    foreach ( $featured_category_colors as $key => $value) {
        $inline_css .= '.featured_cat_background_' . absint( $value['cat_id'] ) . '{ background:' . esc_attr( $value['color'] ) . ' !important; }';
    }

    return $inline_css;

});