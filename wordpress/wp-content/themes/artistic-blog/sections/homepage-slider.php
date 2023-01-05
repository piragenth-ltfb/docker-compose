<?php

add_action( 'bizberg_before_homepage_blog', 'artistic_blog_homepage_slider_wrapper' );
function artistic_blog_homepage_slider_wrapper(){

    $status = bizberg_get_theme_mod( 'artistic_blog_slider_post_status' );

    if( $status == false ){
        return;
    } ?>

    <section class="blog-banner artistic_blog_banner">
        <div class="container">
            <?php 
            echo wp_kses_post( artistic_blog_homepage_slider() );
            ?>
        </div>
    </section>

    <?php
}

function artistic_blog_homepage_slider(){

    ob_start();

    $args = array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page'      => apply_filters( 'artistic_blog_slider_limit', 2 )
    ); 

    $categories = bizberg_get_theme_mod( 'artistic_blog_slider_category' );
    if( !empty( array_filter( $categories ) ) ){
        $args['category__in'] = $categories;
    }

    $slider_query = new WP_Query( $args );

    if( $slider_query->have_posts() ): ?>

        <div class="slider">
            <div class="swiper-container swiper-container-blog">
            <div class="swiper-wrapper">
                
                <?php 
                while( $slider_query->have_posts() ): $slider_query->the_post(); 

                    global $post;
                    $category_detail = get_the_category( $post->ID );
                    $cat_name        = !empty( $category_detail[0]->name ) ? $category_detail[0]->name : ''; ?>

                    <div class="swiper-slide">
                        <div class="slide-inner">
                            
                            <a href="<?php the_permalink(); ?>">
                                <div class="slide-image" style="background-image:url( <?php the_post_thumbnail_url( 'medium_large' ); ?> )"></div>
                            </a>

                            <div class="swiper-content">
                                
                                <?php 
                                if( !empty( $cat_name ) ){ ?>
                                    <div class="content-category">
                                        <a href="<?php echo esc_url( get_category_link( $category_detail[0] ) ); ?>">
                                            <?php echo esc_html( $cat_name ); ?>
                                        </a>
                                    </div>
                                    <?php 
                                } ?>

                                <h2>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>        
                                    </a>
                                </h2>

                                <?php 
                                $meta = bizberg_get_theme_mod( 'ab_slider_meta' );
                                ?>

                                <div class="entry-meta">

                                    <?php 
                                    if( in_array( 'date', $meta ) ){ ?>
                                        <span class="entry-author">                                    
                                            <a href="<?php echo esc_url( home_url() ); ?>/<?php echo esc_attr( date( 'Y/m' , strtotime( get_the_date() ) ) ); ?>"><i class="far fa-calendar"></i>
                                                <?php 
                                                echo esc_html( get_the_date() );
                                                ?> 
                                            </a>
                                        </span>
                                        <?php 
                                    } 

                                    if( in_array( 'comment' , $meta ) ){ ?>
                                        <span class="entry-comments">                    
                                            <?php 
                                            bizberg_get_comments_number( $post );
                                            ?>  
                                        </span>
                                        <?php 
                                    } 

                                    if( in_array( 'read_time' , $meta ) ){ ?>
                                        <span class="entry-read-time">
                                            <?php bizberg_blog_read_time( $post ); ?>                               
                                        </span>
                                        <?php
                                    } ?>

                                </div>
                                
                            </div>
                            
                        </div> 
                    </div>
                    <?php 
                endwhile; ?>
            </div>
            </div>
        </div>
        <!-- Add Arrows -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        
    	<?php

    endif;

    wp_reset_postdata();

    return ob_get_clean();

}