<?php
 /**
 * Register widget area and Sidebar.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @package NewsCard
 */
/****************************************************************************************/

/**
 * Function to register the widget areas(sidebar) and widgets.
 */
function newscard_widgets_init() {

	// Registering Right Sidebar
	register_sidebar( array(
		'name' 				=> __('Right Sidebar', 'newscard') ,
		'id' 				=> 'newscard_right_sidebar',
		'description' 		=> __('Shows widgets at Right Side.', 'newscard'),
		'before_widget' 	=> '<section id="%1$s" class="widget %2$s">',
		'after_widget' 		=> '</section>',
		'before_title' 		=> '<h3 class="widget-title">',
		'after_title' 		=> '</h3>',
	)	);

	// Registering Left Sidebar
	register_sidebar( array(
		'name' 				=> __('Left Sidebar', 'newscard') ,
		'id' 				=> 'newscard_left_sidebar',
		'description' 		=> __('Shows widgets at Left Side.', 'newscard'),
		'before_widget' 	=> '<section id="%1$s" class="widget %2$s">',
		'after_widget' 		=> '</section>',
		'before_title' 		=> '<h3 class="widget-title">',
		'after_title' 		=> '</h3>',
	) );

	// Registering Front Page Template Content Section
	register_sidebar(array(
		'name' 				=> __('Front Page Content Section', 'newscard') ,
		'id' 				=> 'newscard_front_page_content_section',
		'description' 		=> __('Shows widgets on Front Page Template Content Section. Suitable widget: TH: Horizontal/Vertical Posts, TH: Card/Block Posts and TH: Recent Posts', 'newscard'),
		'before_widget' 	=> '<section id="%1$s" class="widget %2$s">',
		'after_widget' 		=> '</section>',
		'before_title' 		=> '<h2 class="widget-title">',
		'after_title' 		=> '</h2>',
	));

	// Registering Front Page Template Sidebar Section
	register_sidebar(array(
		'name' 				=> __('Front Page Sidebar Section', 'newscard') ,
		'id' 				=> 'newscard_front_page_sidebar_section',
		'description' 		=> __('Shows widgets on Front Page Template Sidebar Section. Suitable widget: TH: Horizontal/Vertical Posts, TH: Card/Block Posts and TH: Recent Posts', 'newscard'),
		'before_widget' 	=> '<section id="%1$s" class="widget %2$s">',
		'after_widget' 		=> '</section>',
		'before_title' 		=> '<h2 class="widget-title">',
		'after_title' 		=> '</h2>',
	));

	// Registering Footer Sidebar 1
	register_sidebar( array(
		'name' 				=> __('Footer - Column 1', 'newscard') ,
		'id' 				=> 'newscard_footer_sidebar',
		'description' 		=> __('Shows widgets at Footer Column 1.', 'newscard'),
		'before_widget' 	=> '<section id="%1$s" class="widget %2$s">',
		'after_widget' 		=> '</section>',
		'before_title' 		=> '<h3 class="widget-title">',
		'after_title' 		=> '</h3>',
	) );

	// Registering Footer Sidebar 2
	register_sidebar( array(
		'name' 				=> __('Footer - Column 2', 'newscard'),
		'id' 				=> 'newscard_footer_column2',
		'description' 		=> __('Shows widgets at Footer Column 2.', 'newscard'),
		'before_widget' 	=> '<section id="%1$s" class="widget %2$s">',
		'after_widget' 		=> '</section>',
		'before_title' 		=> '<h3 class="widget-title">',
		'after_title' 		=> '</h3>',
	) );

	// Registering Footer Sidebar 3
	register_sidebar( array(
		'name' 				=> __('Footer - Column 3', 'newscard'),
		'id' 				=> 'newscard_footer_column3',
		'description' 		=> __('Shows widgets at Footer Column 3.', 'newscard'),
		'before_widget' 	=> '<section id="%1$s" class="widget %2$s">',
		'after_widget' 		=> '</section>',
		'before_title' 		=> '<h3 class="widget-title">',
		'after_title' 		=> '</h3>',
	) );

	// Registering Footer Sidebar 4
	register_sidebar( array(
		'name' 				=> __('Footer - Column 4', 'newscard'),
		'id' 				=> 'newscard_footer_column4',
		'description' 		=> __('Shows widgets at Footer Column 4.', 'newscard'),
		'before_widget' 	=> '<section id="%1$s" class="widget %2$s">',
		'after_widget' 		=> '</section>',
		'before_title' 		=> '<h3 class="widget-title">',
		'after_title' 		=> '</h3>',
	) );

	register_widget("newscard_horizontal_vertical_posts");
	register_widget("newscard_card_block_posts");
	register_widget("newscard_recent_posts");
}
add_action('widgets_init', 'newscard_widgets_init');

/****************************************************************************************/
/**
 * Widget for Front Page Template.
 * Construct the widget.
 * i.e. Posts.
 */
class newscard_horizontal_vertical_posts extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname' => 'newscard-widget-horizontal-vertical-posts',
			'description' => __('Display Horizontal/Vertical Posts', 'newscard')
		);
		parent::__construct(false, $name = __('TH: Horizontal/Vertical Posts', 'newscard') , $widget_ops);
	}

	function form($instance) {

		$instance = wp_parse_args(
			(array) $instance,
			array(
				'widget_title' => '',
				'category' => '',
				'type' => 1,
				'style' => 0,
			)
		);
		$title = isset( $instance['widget_title'] ) ? $instance['widget_title'] : '';
		$type = ( isset($instance['type']) && is_numeric($instance['type']) ) ? (int) $instance['type'] : 1; ?>
		<p>
			<?php esc_html_e('Set featured image on the related post if you need to display image.', 'newscard'); ?>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" type="checkbox" value="1" <?php checked( '1', absint($instance['style']) ); ?>/>
			<label for="<?php echo $this->get_field_id('style'); ?>">
				<?php esc_html_e('Horizontal Style','newscard'); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('widget_title'); ?>">
				<?php esc_html_e('Title: ', 'newscard'); ?>
			</label>
			<input id="<?php echo $this->get_field_id('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
		</p>
		<p>
			<input type="radio" id="<?php echo ($this->get_field_id('type') . '-1'); ?>" name="<?php echo ($this->get_field_name('type')); ?>" value="1" <?php checked($type == 1, true); ?>>
			<label for="<?php echo ($this->get_field_id('type') . '-1'); ?>" class="input-label"><?php esc_html_e('Latest Posts', 'newscard'); ?></label>
			<br>
			<input type="radio" id="<?php echo ($this->get_field_id( 'type') . '-2'); ?>" name="<?php echo ($this->get_field_name('type')); ?>" value="2" <?php checked($type == 2, true); ?>>
			<label for="<?php echo ($this->get_field_id('type') . '-2'); ?>" class="input-label"><?php esc_html_e('Show Posts from Category', 'newscard'); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>">
				<?php esc_html_e('Choose Category:', 'newscard'); ?>
			</label>
			<?php wp_dropdown_categories(
				array(
					'show_option_none' => ' ',
					'name' => $this->get_field_name('category') ,
					'selected' => $instance['category']
				)
			); ?>
		</p>
		<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['category'] = absint($new_instance['category']);
		$instance['style'] = absint($new_instance['style']);
		$instance['widget_title'] = sanitize_text_field($new_instance['widget_title']);
		$instance['type'] = ( isset($new_instance['type']) && $new_instance['type'] > 0 && $new_instance['type'] < 3 ) ? (int) $new_instance['type'] : 1;
		return $instance;
	}

	function widget($args, $instance) {

		$category = isset($instance['category']) ? $instance['category'] : '';
		$style = empty($instance['style']) ? '' : $instance['style'];
		$widget_title = apply_filters( 'widget_title', empty( $instance['widget_title'] ) ? '' : $instance['widget_title'], $instance, $this->id_base );
		$type = ( isset($instance['type']) && is_numeric($instance['type']) ) ? (int) $instance['type'] : 1;
		global $post;

		$post_type = array(
			'posts_per_page' => 5,
			'post_type' => array('post'),
			'post__not_in' => get_option('sticky_posts'),
		);
		if ( $type == 2 ) {
			$post_type['category__in'] = $category;
		}

		$get_featured_posts = new WP_Query($post_type);

		echo $args['before_widget']; ?>
		<?php if ( !empty($widget_title) ) {
			echo $args['before_title'] . $widget_title . $args['after_title'];
		} ?>
		<div class="row gutter-parent-14<?php echo ($style == 0) ? ' post-vertical' : ' post-horizontal' ;?>">
			<div class="<?php echo ($style == 0) ? 'col-md-6 ' : 'col-12 ' ;?>first-col">
			<?php
			$i=1;
			while ($get_featured_posts->have_posts()):$get_featured_posts->the_post(); ?>
			<?php if ( $i == 1 ) { ?>
				<div class="post-boxed main-post clearfix<?php echo ($style == 1) ? ' inlined' : '' ;?>">
					<?php if ( has_post_thumbnail() ) { ?>
						<div class="post-img-wrap">
							<a href="<?php the_permalink(); ?>" class="post-img" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(),'full')); ?>');"></a>
						</div>
					<?php } ?>
					<div class="post-content">
						<div class="entry-meta category-meta">
							<div class="cat-links"><?php the_category(' '); ?></div>
						</div><!-- .entry-meta -->
						<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h3>' ); ?>
						<div class="entry-meta">
							<?php newscard_posted_on(); ?>
						</div>
						<div class="entry-content">
							<?php the_excerpt(); ?>
						</div><!-- .entry-content -->
					</div>
				</div><!-- post-boxed -->
			</div>
			<div class="<?php echo ($style == 0) ? 'col-md-6 ' : 'col-12 ' ;?>second-col">
				<?php if ( $style == 1 ) { ?>
					<div class="row">
				<?php }
				} else {
					if ( $style == 1 ) { ?>
						<div class="col-md-6 post-col">
					<?php } ?>
					<div class="post-boxed inlined clearfix">
						<?php if ( has_post_thumbnail() ) { ?>
							<div class="post-img-wrap">
								<a href="<?php the_permalink(); ?>" class="post-img" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(),'full')); ?>');"></a>
							</div>
						<?php } ?>
						<div class="post-content">
							<div class="entry-meta category-meta">
								<div class="cat-links"><?php the_category(' '); ?></div>
							</div><!-- .entry-meta -->
							<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h3>' ); ?>
							<div class="entry-meta">
								<?php newscard_posted_on(); ?>
							</div>
						</div>
					</div><!-- .post-boxed -->
					<?php if ( $style == 1 ) { ?>
						</div><!-- .col-md-6 .post-col -->
					<?php }
				}
				$i++;
				endwhile;
				// Reset Post Data
				wp_reset_postdata(); ?>
				<?php if ( $style == 1 ) { ?>
					</div><!-- .row -->
				<?php } ?>
			</div>
		</div><!-- .row gutter-parent-14 -->

		<?php echo $args['after_widget'] . '<!-- .widget_featured_post -->';
	}
}

/****************************************************************************************/
/**
 * Widget for Front Page Template.
 * Construct the widget.
 * i.e. Posts.
 */
class newscard_card_block_posts extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname' => 'newscard-widget-card-block-posts',
			'description' => __('Display Card/Block Posts', 'newscard')
		);
		parent::__construct(false, $name = __('TH: Card/Block Posts', 'newscard') , $widget_ops);
	}

	function form($instance) {

		$instance = wp_parse_args(
			(array) $instance,
			array(
				'widget_title' => '',
				'category' => '',
				'type' => 1,
				'style' => 0,
			)
		);
		$title = isset( $instance['widget_title'] ) ? $instance['widget_title'] : '';
		$type = ( isset($instance['type']) && is_numeric($instance['type']) ) ? (int) $instance['type'] : 1; ?>
		<p>
			<?php esc_html_e('Set featured image on the related post if you need to display Image.', 'newscard'); ?>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" type="checkbox" value="1" <?php checked( '1', absint($instance['style']) ); ?>/>
			<label for="<?php echo $this->get_field_id('style'); ?>">
				<?php esc_html_e('Block Style','newscard'); ?>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('widget_title'); ?>">
				<?php esc_html_e('Title: ', 'newscard'); ?>
			</label>
			<input id="<?php echo $this->get_field_id('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
		</p>
		<p>
			<input type="radio" id="<?php echo ($this->get_field_id('type') . '-1'); ?>" name="<?php echo ($this->get_field_name('type')); ?>" value="1" <?php checked($type == 1, true); ?>>
			<label for="<?php echo ($this->get_field_id('type') . '-1'); ?>" class="input-label"><?php esc_html_e('Latest Posts', 'newscard'); ?></label>
			<br>
			<input type="radio" id="<?php echo ($this->get_field_id( 'type') . '-2'); ?>" name="<?php echo ($this->get_field_name('type')); ?>" value="2" <?php checked($type == 2, true); ?>>
			<label for="<?php echo ($this->get_field_id('type') . '-2'); ?>" class="input-label"><?php esc_html_e('Show Posts from Category', 'newscard'); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>">
				<?php esc_html_e('Choose Category:', 'newscard'); ?>
			</label>
			<?php wp_dropdown_categories(
				array(
					'show_option_none' => ' ',
					'name' => $this->get_field_name('category') ,
					'selected' => $instance['category']
				)
			); ?>
		</p>
		<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['category'] = absint($new_instance['category']);
		$instance['style'] = absint($new_instance['style']);
		$instance['widget_title'] = sanitize_text_field($new_instance['widget_title']);
		$instance['type'] = ( isset($new_instance['type']) && $new_instance['type'] > 0 && $new_instance['type'] < 3 ) ? (int) $new_instance['type'] : 1;
		return $instance;
	}

	function widget($args, $instance) {

		$category = isset($instance['category']) ? $instance['category'] : '';
		$style = empty($instance['style']) ? '' : $instance['style'];
		$widget_title = apply_filters( 'widget_title', empty( $instance['widget_title'] ) ? '' : $instance['widget_title'], $instance, $this->id_base );
		$type = ( isset($instance['type']) && is_numeric($instance['type']) ) ? (int) $instance['type'] : 1;
		global $post;

		$post_type = array(
			'posts_per_page' => 2,
			'post_type' => array('post'),
			'post__not_in' => get_option('sticky_posts'),
		);
		if ( $type == 2 ) {
			$post_type['category__in'] = $category;
		}

		$get_featured_posts = new WP_Query($post_type);

		echo $args['before_widget']; ?>
			<?php if ( !empty($widget_title) ) {
				echo $args['before_title'] . $widget_title . $args['after_title'];
			} ?>
			<div class="row gutter-parent-14">
				<?php while ($get_featured_posts->have_posts()):$get_featured_posts->the_post(); ?>
					<div class="col-sm-6 post-col">
						<div class="post-item<?php echo ($style == 0) ? ' post-boxed' : ' post-block' ;?>">
							<?php if ( has_post_thumbnail() && $style == 0 ) { ?>
								<div class="post-img-wrap">
									<a href="<?php the_permalink(); ?>" class="post-img" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(),'full')); ?>');"></a>
									<div class="entry-meta category-meta">
										<div class="cat-links"><?php the_category(' '); ?></div>
									</div><!-- .entry-meta -->
								</div><!-- .post-img-wrap -->
							<?php }
							if ( $style == 0 ) { ?>
								<div class="post-content">
									<?php if ( !has_post_thumbnail() ) { ?>
										<div class="entry-meta category-meta">
											<div class="cat-links"><?php the_category(' '); ?></div>
										</div><!-- .entry-meta -->
									<?php } ?>
									<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h3>' ); ?>
									<div class="entry-meta">
										<?php newscard_posted_on(); ?>
									</div>
									<div class="entry-content">
										<?php the_excerpt(); ?>
									</div><!-- .entry-content -->
								</div><!-- .post-content -->
							<?php } else { ?>
								<div class="post-img-wrap">
									<a href="<?php the_permalink(); ?>" class="post-img" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(),'full')); ?>');"></a>
								</div><!-- .post-img-wrap -->
								<div class="entry-header">
									<div class="entry-meta category-meta">
										<div class="cat-links"><?php the_category(' '); ?></div>
									</div><!-- .entry-meta -->
									<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h3>' ); ?>
									<div class="entry-meta">
										<?php newscard_posted_on(); ?>
									</div>
								</div><!-- .entry-header -->
							<?php } ?>
						</div><!-- .post-item -->
					</div><!-- .col-sm-6 .post-col -->
				<?php endwhile;
				// Reset Post Data
				wp_reset_postdata(); ?>
			</div><!-- .row gutter-parent-14 -->

		<?php echo $args['after_widget'] . '<!-- .widget_featured_post -->';
	}
}

/****************************************************************************************/
/**
 * Widget for Any Sidebars.
 * Construct the widget.
 * i.e. Name and posts.
 */
class newscard_recent_posts extends WP_Widget {
	function __construct() {
		$widget_ops = array(
			'classname' => 'newscard-widget-recent-posts',
			'description' => __('Display Recent Posts', 'newscard')
		);
		parent::__construct(false, $name = __('TH: Recent Posts', 'newscard') , $widget_ops);
	}
	function form($instance) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'number' => '4',
				'widget_title' => '',
			)
		);
		$title = isset( $instance['widget_title'] ) ? $instance['widget_title'] : ''; ?>
		<p>
			<?php esc_html_e('Set featured image on the related post if you need to display Image.', 'newscard'); ?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('widget_title'); ?>">
				<?php esc_html_e('Title:', 'newscard'); ?>
			</label>
			<input id="<?php echo $this->get_field_id('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>">
			<?php esc_html_e( 'Number of Post:', 'newscard' ); ?>
			</label>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo absint($instance[ 'number']); ?>" size="3"/>
		</p>
		<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['number'] = absint( $new_instance['number'] );
		$instance['widget_title'] = sanitize_text_field($new_instance['widget_title']);
		return $instance;
	}

	function widget($args, $instance) {

		$widget_title = apply_filters( 'widget_title', empty( $instance['widget_title'] ) ? '' : $instance['widget_title'], $instance, $this->id_base );
		$number = empty($instance['number']) ? 4 : $instance['number'];
		global $post;

		$get_featured_posts = new WP_Query(
			array(
				'posts_per_page' => $number,
				'post_type' => array('post'),
				'post__not_in' => get_option('sticky_posts'),
			)
		);

		echo $args['before_widget']; ?>

			<?php if ( !empty($widget_title) ) {
				echo $args['before_title'] . $widget_title . $args['after_title'];
			} ?>
			<div class="row gutter-parent-14">
				<?php if ($number > 0) {
					$i = 0;
					while ($get_featured_posts->have_posts()):$get_featured_posts->the_post(); ?>
						<div class="col-md-6 post-col">
							<div class="post-boxed inlined clearfix">
								<?php if ( has_post_thumbnail() ) { ?>
									<div class="post-img-wrap">
										<a href="<?php the_permalink(); ?>" class="post-img" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(),'full')); ?>');"></a>
									</div>
								<?php } ?>
								<div class="post-content">
									<div class="entry-meta category-meta">
										<div class="cat-links"><?php the_category(' '); ?></div>
									</div><!-- .entry-meta -->
									<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h3>' ); ?>
									<div class="entry-meta">
										<?php newscard_posted_on(); ?>
									</div>
								</div>
							</div><!-- post-boxed -->
						</div><!-- col-md-6 -->
						<?php $i++;
					endwhile;
					// Reset Post Data
					wp_reset_postdata();
				} ?>
			</div><!-- .row .gutter-parent-14-->

		<?php echo $args['after_widget'] . '<!-- .widget_recent_post -->';
	}
}
