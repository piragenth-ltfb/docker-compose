<?php
/**
 * NewsCard Theme Customizer
 *
 * @package NewsCard
 */

if ( ! class_exists( 'WP_Customize_Section' ) ) {
	return null;
}

function newscard_support_register($wp_customize){
	class NewsCard_Customize_NewsCard_Support extends WP_Customize_Control {
		public function render_content() { ?>
		<div class="theme-info">
			<a title="<?php esc_attr_e( 'Review NewsCard', 'newscard' ); ?>" href="<?php echo esc_url( 'https://wordpress.org/support/theme/newscard/reviews/?filter=5' ); ?>" target="_blank">
				<?php esc_html_e( 'Rate NewsCard', 'newscard' ); ?>
			</a>
			<a href="<?php echo esc_url( 'https://www.themehorse.com/theme-instruction/newscard/' ); ?>" title="<?php esc_attr_e( 'NewsCard Theme Instructions', 'newscard' ); ?>" target="_blank">
			<?php esc_html_e( 'Theme Instructions', 'newscard' ); ?>
			</a>
			<a href="<?php echo esc_url( 'https://www.themehorse.com/support-forum/' ); ?>" title="<?php esc_attr_e( 'Support Forum', 'newscard' ); ?>" target="_blank">
			<?php esc_html_e( 'Support Forum', 'newscard' ); ?>
			</a>
			<a href="<?php echo esc_url( 'https://www.themehorse.com/demos/newscard/' ); ?>" title="<?php esc_attr_e( 'NewsCard Demo', 'newscard' ); ?>" target="_blank">
			<?php esc_html_e( 'View Demo', 'newscard' ); ?>
			</a>
		</div>
		<?php
		}
	}

	class NewsCard_Customize_drop_down_Category_Control extends WP_Customize_Control {
		/**
		 * The type of customize control being rendered.
		 */
		public $type = 'select';
		/**
		 * Displays the multiple select on the customize screen.
		 */
		public function render_content() {
			$newscard_categories = get_categories(); ?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<select <?php $this->link(); ?>>
					<?php foreach ($newscard_categories as $category) : ?>
						<option value="<?php echo esc_attr($category->cat_ID); ?>">
							<?php echo esc_html($category->cat_name); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</label>
			<?php
		}
	}
}
add_action('customize_register', 'newscard_support_register');

/**
 * Upsell customizer section.
 *
 * @since  1.0.0
 * @access public
 */
class NewsCard_Customize_Section_Upsell extends WP_Customize_Section {

	/**
	 * The type of customize section being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'upsell';

	/**
	 * Custom button text to output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $pro_text = '';

	/**
	 * Custom pro button URL.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $pro_url = '';

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function json() {
		$json = parent::json();

		$json['pro_text'] = $this->pro_text;
		$json['pro_url']  = esc_url( $this->pro_url );

		return $json;
	}

	/**
	 * Outputs the Underscore.js template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	protected function render_template() { ?>

		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">
			<h3 class="accordion-section-title">
				{{ data.title }}

				<# if ( data.pro_text && data.pro_url ) { #>
				<a href="{{ data.pro_url }}" class="upgrade-to-pro" target="_blank">{{ data.pro_text }}</a>
				<# } #>
			</h3>
		</li>
	<?php }
}

function newscard_customize_custom_sections( $wp_customize ) {
	// Register custom section types.
	$wp_customize->register_section_type( 'NewsCard_Customize_Section_Upsell' );

	// Register sections.
	$wp_customize->add_section( new NewsCard_Customize_Section_Upsell( $wp_customize, 'theme_upsell', array(
		'title'					=> esc_html__( 'NewsCard Pro', 'newscard' ),
		'pro_text'				=> esc_html__( 'Upgrade to Pro', 'newscard' ),
		'pro_url'				=> 'https://www.themehorse.com/themes/newscard-pro',
		'priority'				=> 1,
	) ) );
}
add_action( 'customize_register', 'newscard_customize_custom_sections');

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function newscard_customize_register( $wp_customize ) {
	global $newscard_settings;
	$newscard_settings = newscard_get_option_defaults();

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'newscard_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'newscard_customize_partial_blogdescription',
		) );
	}

	// Section => Layout
	$wp_customize->add_section( 'newscard_content_layout_section', array(
		'title' 					=> __('Layout','newscard'),
		'priority'				=> 121,
	) );
	$wp_customize->add_setting('newscard_content_layout', array(
		'default'				=> 'right',
		'sanitize_callback'	=> 'newscard_sanitize_choices',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control('newscard_content_layout', array(
		'label'			=> __('Global Layout Setting','newscard'),
		'description'			=> __('Below options are global setting. Set individual layout from specific page/post.','newscard'),
		'section'				=> 'newscard_content_layout_section',
		'type'					=> 'radio',
		'checked'				=> 'checked',
		'choices'				=> array(
			'right'					=> __('Right Sidebar','newscard'),
			'left'					=> __('Left Sidebar','newscard'),
			'nosidebar'				=> __('No Sidebar','newscard'),
			'fullwidth'				=> __('No Sidebar Full Width','newscard'),
		),
	) );

	// Section => Social Profiles
	$wp_customize->add_section('newscard_social_profiles_setting', array(
		'title'					=> __('Social Profiles', 'newscard'),
		'priority'				=> 131,
	) );
	$wp_customize->add_setting('newscard_social_profiles', array(
		'default'				=> '',
		'sanitize_callback'		=> 'newscard_sanitize_social_links',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_social_profiles', array(
		'description' 			=> __('Add the full link to your Social Profiles. Seperate with comma ,','newscard'),
		'section'				=> 'newscard_social_profiles_setting',
		'type'					=> 'textarea'
	) );

	// Section => Header
	$wp_customize->add_section('newscard_custom_header_setting', array(
		'title'					=> __('Header', 'newscard'),
		'priority'				=> 141,
	) );
	$wp_customize->add_setting('newscard_top_bar_social_profiles', array(
		'default'				=> 0,
		'sanitize_callback'		=> 'newscard_sanitize_integer',
	) );
	$wp_customize->add_control( 'newscard_top_bar_social_profiles', array(
		'label'					=> __('Hide Social Profiles', 'newscard'),
		'section'				=> 'newscard_custom_header_setting',
		'type'					=> 'checkbox',
		'active_callback'		=> 'newscard_is_social_profiles_set'
	) );
	$wp_customize->add_setting( 'newscard_top_bar_hide', array(
		'default'				=> 0,
		'sanitize_callback'		=> 'newscard_sanitize_integer',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_top_bar_hide', array(
		'label'					=> __('Hide Top Bar', 'newscard'),
		'section'				=> 'newscard_custom_header_setting',
		'type'					=> 'checkbox',
	) );
	$wp_customize->add_setting( 'newscard_nav_uppercase', array(
		'default'				=> 1,
		'sanitize_callback'		=> 'newscard_sanitize_integer',
	) );
	$wp_customize->add_control( 'newscard_nav_uppercase', array(
		'label'					=> __('Navigation Uppercase', 'newscard'),
		'section'				=> 'newscard_custom_header_setting',
		'type'					=> 'checkbox'
	) );
	$wp_customize->add_setting( 'newscard_breadcrumbs_hide', array(
		'default'				=> 0,
		'sanitize_callback'		=> 'newscard_sanitize_integer',
	) );
	$wp_customize->add_control( 'newscard_breadcrumbs_hide', array(
		'label'					=> __('Hide Breadcrumbs', 'newscard'),
		'section'				=> 'newscard_custom_header_setting',
		'type'					=> 'checkbox'
	) );
	$wp_customize->add_setting( 'newscard_header_background',array(
		'sanitize_callback'		=> 'esc_url_raw',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control ( $wp_customize, 'newscard_header_background', array(
		'label'					=> __('Background Image', 'newscard'),
		'section'				=> 'newscard_custom_header_setting',
	) ) );
	$wp_customize->add_setting('newscard_header_bg_overlay', array(
		'default'				=> 'none',
		'sanitize_callback'		=> 'newscard_sanitize_choices',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control('newscard_header_bg_overlay', array(
		'label'					=> __('Background Overlay','newscard'),
		'section'				=> 'newscard_custom_header_setting',
		'type'					=> 'radio',
		'checked'				=> 'checked',
		'choices'				=> array(
			'dark'					=> __('Dark Overlay','newscard'),
			'light'					=> __('Light Overlay','newscard'),
			'none'					=> __('None','newscard'),
		),
	) );
	$wp_customize->add_setting( 'newscard_header_add_image',array(
		'sanitize_callback'		=> 'esc_url_raw',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control ( $wp_customize, 'newscard_header_add_image', array(
		'label'					=> __('Advertisement Image', 'newscard'),
		'section'				=> 'newscard_custom_header_setting',
	) ) );
	$wp_customize->add_setting('newscard_header_add_link', array(
		'default'				=> '',
		'sanitize_callback'		=> 'esc_url_raw',
	) );
	$wp_customize->add_control('newscard_header_add_link', array(
		'label'					=> __('Advertisement Image Url', 'newscard'),
		'section'				=> 'newscard_custom_header_setting',
		'type'					=> 'text',
	) );

	// Section => Top Stories Post
	$wp_customize->add_section( 'newscard_top_stories', array(
		'title'					=> __('Top Stories Post', 'newscard'),
		'priority'				=> 151,
	));
	$wp_customize->add_setting( 'newscard_top_stories_hide', array(
		'default'				=> 0,
		'sanitize_callback'		=> 'newscard_sanitize_integer',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_top_stories_hide', array(
		'label'					=> __('Hide Top Stories Post', 'newscard'),
		'section'				=> 'newscard_top_stories',
		'type'					=> 'checkbox',
	) );
	$wp_customize->add_setting('newscard_top_stories_title', array(
		'default'				=> __('Top Stories', 'newscard'),
		'sanitize_callback'		=> 'sanitize_text_field',
		'capability' 			=> 'edit_theme_options'
	));
	$wp_customize->add_control( 'newscard_top_stories_title', array(
		'label'					=> __('Posts Title', 'newscard'),
		'section'				=> 'newscard_top_stories',
		'active_callback'		=> 'newscard_is_top_stories_set',
		'type'					=> 'text',
	));
	$wp_customize->add_setting( 'newscard_top_stories_latest_post', array(
		'default'				=> 'latest',
		'sanitize_callback'		=> 'newscard_sanitize_choices',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_top_stories_latest_post', array(
		'section'				=> 'newscard_top_stories',
		'active_callback'		=> 'newscard_is_top_stories_set',
		'type'					=> 'radio',
		'checked'				=> 'checked',
		'choices'				=> array(
			'latest'				=> __('Show Latest Posts','newscard'),
			'category'				=> __('Show Posts from Category','newscard'),
		),
	) );
	$wp_customize->add_setting( 'newscard_top_stories_categories', array(
		'default'				=> array(),
		'sanitize_callback'		=> 'newscard_sanitize_select',
		'capability' 			=> 'edit_theme_options'
	));
	$wp_customize->add_control( new NewsCard_Customize_drop_down_Category_Control( $wp_customize, 'newscard_top_stories_categories', array(
		'label'				=> __('Choose Category', 'newscard'),
		'section'			=> 'newscard_top_stories',
		'active_callback'	=> 'newscard_is_top_stories_latest_post_set',
		'type'				=> 'select'
	) ) );

	// Panel => Banner
	$wp_customize->add_panel( 'newscard_banner_settings', array(
		'title'					=> __('Banner', 'newscard'),
		'priority'				=> 161,
	));

	// Section => Banner Settings
	$wp_customize->add_section( 'newscard_banner_global_settings', array(
		'title'					=> __('Banner Settings', 'newscard'),
		'panel'					=> 'newscard_banner_settings',
	));
	$wp_customize->add_setting('newscard_banner_display', array(
		'default'				=> 'front-blog',
		'sanitize_callback'		=> 'newscard_sanitize_choices',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control('newscard_banner_display', array(
		'label'					=> __('Display Option','newscard'),
		'description'			=> __('Make sure Banner Sections are enable.','newscard'),
		'section'				=> 'newscard_banner_global_settings',
		'type'					=> 'radio',
		'checked'				=> 'checked',
		'choices'				=> array(
			'front-only'			=> __('Show on Homepage only','newscard'),
			'front-blog'			=> __('Show on both Homepage and Posts Page','newscard'),
		),
	) );

	// Section => Featured Slider
	$wp_customize->add_section( 'newscard_banner_slider', array(
		'title'					=> __('Featured Slider', 'newscard'),
		'panel'					=> 'newscard_banner_settings',
	));
	$wp_customize->add_setting( 'newscard_banner_slider_posts_hide', array(
		'default'				=> 0,
		'sanitize_callback'		=> 'newscard_sanitize_integer',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_banner_slider_posts_hide', array(
		'label'					=> __('Hide Featured Slider', 'newscard'),
		'section'				=> 'newscard_banner_slider',
		'type'					=> 'checkbox',
	) );
	$wp_customize->add_setting('newscard_banner_slider_posts_title', array(
		'default'				=> __('Main Stories', 'newscard'),
		'sanitize_callback'		=> 'sanitize_text_field',
		'capability' 			=> 'edit_theme_options'
	));
	$wp_customize->add_control( 'newscard_banner_slider_posts_title', array(
		'label'					=> __('Posts Title', 'newscard'),
		'section'				=> 'newscard_banner_slider',
		'active_callback'		=> 'newscard_is_banner_slider_posts_set',
		'type'					=> 'text',
	));
	$wp_customize->add_setting( 'newscard_banner_slider_latest_post', array(
		'default'				=> 'latest',
		'sanitize_callback'		=> 'newscard_sanitize_choices',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_banner_slider_latest_post', array(
		'section'				=> 'newscard_banner_slider',
		'active_callback'		=> 'newscard_is_banner_slider_posts_set',
		'type'					=> 'radio',
		'checked'				=> 'checked',
		'choices'				=> array(
			'latest'				=> __('Show Latest Posts','newscard'),
			'category'				=> __('Show Posts from Category','newscard'),
		),
	) );
	$wp_customize->add_setting( 'newscard_banner_slider_post_categories', array(
		'default'				=> array(),
		'sanitize_callback'		=> 'newscard_sanitize_select',
		'capability' 			=> 'edit_theme_options'
	));
	$wp_customize->add_control( new NewsCard_Customize_drop_down_Category_Control( $wp_customize, 'newscard_banner_slider_post_categories', array(
		'label'					=> __('Choose Category', 'newscard'),
		'section'				=> 'newscard_banner_slider',
		'active_callback'		=> 'newscard_is_banner_slider_latest_post_set',
		'type'					=> 'select'
	) ) );

	// Section => Featured Posts 1
	$wp_customize->add_section( 'newscard_banner_featured_posts_1', array(
		'title'					=> __('Featured Posts 1', 'newscard'),
		'panel'					=> 'newscard_banner_settings',
	));
	$wp_customize->add_setting( 'newscard_banner_featured_posts_1_hide', array(
		'default'				=> 0,
		'sanitize_callback'		=> 'newscard_sanitize_integer',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_banner_featured_posts_1_hide', array(
		'label'					=> __('Hide Featured Posts 1', 'newscard'),
		'section'				=> 'newscard_banner_featured_posts_1',
		'type'					=> 'checkbox',
	) );
	$wp_customize->add_setting('newscard_banner_featured_posts_1_title', array(
		'default'				=> __('Editor\'s Pick', 'newscard'),
		'sanitize_callback'		=> 'sanitize_text_field',
		'capability' 			=> 'edit_theme_options'
	));
	$wp_customize->add_control( 'newscard_banner_featured_posts_1_title', array(
		'label'					=> __('Posts Title', 'newscard'),
		'section'				=> 'newscard_banner_featured_posts_1',
		'active_callback'		=> 'newscard_is_banner_featured_posts_1_set',
		'type'					=> 'text',
	));
	$wp_customize->add_setting( 'newscard_banner_featured_posts_1_latest_post', array(
		'default'				=> 'latest',
		'sanitize_callback'		=> 'newscard_sanitize_choices',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_banner_featured_posts_1_latest_post', array(
		'section'				=> 'newscard_banner_featured_posts_1',
		'active_callback'		=> 'newscard_is_banner_featured_posts_1_set',
		'type'					=> 'radio',
		'checked'				=> 'checked',
		'choices'				=> array(
			'latest'				=> __('Show Latest Posts','newscard'),
			'category'				=> __('Show Posts from Category','newscard'),
		),
	) );
	$wp_customize->add_setting( 'newscard_banner_featured_posts_1_post_categories', array(
		'default'				=> array(),
		'sanitize_callback'		=> 'newscard_sanitize_select',
		'capability' 			=> 'edit_theme_options'
	));
	$wp_customize->add_control( new NewsCard_Customize_drop_down_Category_Control( $wp_customize, 'newscard_banner_featured_posts_1_post_categories', array(
		'label'					=> __('Choose Category', 'newscard'),
		'section'				=> 'newscard_banner_featured_posts_1',
		'active_callback'		=> 'newscard_is_banner_featured_posts_1_latest_post_set',
		'type'					=> 'select'
	) ) );

	// Section => Featured Posts 2
	$wp_customize->add_section( 'newscard_banner_featured_posts_2', array(
		'title'					=> __('Featured Posts 2', 'newscard'),
		'panel'					=> 'newscard_banner_settings',
	));
	$wp_customize->add_setting( 'newscard_banner_featured_posts_2_hide', array(
		'default'				=> 0,
		'sanitize_callback'		=> 'newscard_sanitize_integer',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_banner_featured_posts_2_hide', array(
		'label'					=> __('Hide Featured Posts 2', 'newscard'),
		'section'				=> 'newscard_banner_featured_posts_2',
		'type'					=> 'checkbox',
	) );
	$wp_customize->add_setting('newscard_banner_featured_posts_2_title', array(
		'default'				=> __('Trending Stories', 'newscard'),
		'sanitize_callback'		=> 'sanitize_text_field',
		'capability' 			=> 'edit_theme_options'
	));
	$wp_customize->add_control( 'newscard_banner_featured_posts_2_title', array(
		'label'					=> __('Posts Title', 'newscard'),
		'section'				=> 'newscard_banner_featured_posts_2',
		'active_callback'		=> 'newscard_is_banner_featured_posts_2_set',
		'type'					=> 'text',
	));
	$wp_customize->add_setting( 'newscard_banner_featured_posts_2_latest_post', array(
		'default'				=> 'latest',
		'sanitize_callback'		=> 'newscard_sanitize_choices',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_banner_featured_posts_2_latest_post', array(
		'section'				=> 'newscard_banner_featured_posts_2',
		'active_callback'		=> 'newscard_is_banner_featured_posts_2_set',
		'type'					=> 'radio',
		'checked'				=> 'checked',
		'choices'				=> array(
			'latest'				=> __('Show Latest Posts','newscard'),
			'category'				=> __('Show Posts from Category','newscard'),
		),
	) );
	$wp_customize->add_setting( 'newscard_banner_featured_posts_2_post_categories', array(
		'default'				=> array(),
		'sanitize_callback'		=> 'newscard_sanitize_select',
		'capability' 			=> 'edit_theme_options'
	));
	$wp_customize->add_control( new NewsCard_Customize_drop_down_Category_Control( $wp_customize, 'newscard_banner_featured_posts_2_post_categories', array(
		'label'					=> __('Choose Category', 'newscard'),
		'section'				=> 'newscard_banner_featured_posts_2',
		'active_callback'		=> 'newscard_is_banner_featured_posts_2_latest_post_set',
		'type'					=> 'select'
	) ) );

	// Section => Header Featured Posts
	$wp_customize->add_section( 'newscard_header_featured_posts', array(
		'title'					=> __('Header Featured Posts', 'newscard'),
		'priority'				=> 171,
	));
	$wp_customize->add_setting( 'newscard_header_featured_posts_hide', array(
		'default'				=> 0,
		'sanitize_callback'		=> 'newscard_sanitize_integer',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_header_featured_posts_hide', array(
		'label'					=> __('Hide Header Featured Posts', 'newscard'),
		'section'				=> 'newscard_header_featured_posts',
		'type'					=> 'checkbox',
	) );
	$wp_customize->add_setting('newscard_header_featured_posts_banner_display', array(
		'default'				=> 'front-blog',
		'sanitize_callback'		=> 'newscard_sanitize_choices',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control('newscard_header_featured_posts_banner_display', array(
		'label'					=> __('Display Option','newscard'),
		'section'				=> 'newscard_header_featured_posts',
		'active_callback'		=> 'newscard_is_header_featured_posts_set',
		'type'					=> 'radio',
		'checked'				=> 'checked',
		'choices'				=> array(
			'front-only'			=> __('Show on Homepage only','newscard'),
			'front-blog'			=> __('Show on both Homepage and Posts Page','newscard'),
		),
	) );
	$wp_customize->add_setting('newscard_header_featured_posts_title', array(
		'default'				=> __('Popular Stories', 'newscard'),
		'sanitize_callback'		=> 'sanitize_text_field',
		'capability' 			=> 'edit_theme_options'
	));
	$wp_customize->add_control( 'newscard_header_featured_posts_title', array(
		'label'					=> __('Posts Title', 'newscard'),
		'section'				=> 'newscard_header_featured_posts',
		'active_callback'		=> 'newscard_is_header_featured_posts_set',
		'type'					=> 'text',
	));
	$wp_customize->add_setting( 'newscard_header_featured_latest_post', array(
		'default'				=> 'latest',
		'sanitize_callback'		=> 'newscard_sanitize_choices',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_header_featured_latest_post', array(
		'section'				=> 'newscard_header_featured_posts',
		'active_callback'		=> 'newscard_is_header_featured_posts_set',
		'type'					=> 'radio',
		'checked'				=> 'checked',
		'choices'				=> array(
			'latest'				=> __('Show Latest Posts','newscard'),
			'category'				=> __('Show Posts from Category','newscard'),
		),
	) );
	$wp_customize->add_setting( 'newscard_header_featured_post_categories', array(
		'default'				=> array(),
		'sanitize_callback'		=> 'newscard_sanitize_select',
		'capability' 			=> 'edit_theme_options'
	));
	$wp_customize->add_control( new NewsCard_Customize_drop_down_Category_Control( $wp_customize, 'newscard_header_featured_post_categories', array(
		'label'					=> __('Choose Category', 'newscard'),
		'section'				=> 'newscard_header_featured_posts',
		'active_callback'		=> 'newscard_is_header_featured_latest_post_set',
		'type'					=> 'select'
	) ) );

	// Section => Footer Featured Posts
	$wp_customize->add_section( 'newscard_footer_featured_posts', array(
		'title'					=> __('Footer Featured Posts', 'newscard'),
		'priority'				=> 181,
	));
	$wp_customize->add_setting( 'newscard_footer_featured_posts_hide', array(
		'default'				=> 0,
		'sanitize_callback'		=> 'newscard_sanitize_integer',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_footer_featured_posts_hide', array(
		'label'					=> __('Hide Footer Featured Posts', 'newscard'),
		'section'				=> 'newscard_footer_featured_posts',
		'type'					=> 'checkbox',
	) );
	$wp_customize->add_setting('newscard_footer_featured_posts_title', array(
		'default'				=> __('You may Missed', 'newscard'),
		'sanitize_callback'		=> 'sanitize_text_field',
		'capability' 			=> 'edit_theme_options'
	));
	$wp_customize->add_control( 'newscard_footer_featured_posts_title', array(
		'label'					=> __('Posts Title', 'newscard'),
		'section'				=> 'newscard_footer_featured_posts',
		'active_callback'		=> 'newscard_is_footer_featured_posts_set',
		'type'					=> 'text',
	));
	$wp_customize->add_setting( 'newscard_footer_featured_latest_post', array(
		'default'				=> 'latest',
		'sanitize_callback'		=> 'newscard_sanitize_choices',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_footer_featured_latest_post', array(
		'section'				=> 'newscard_footer_featured_posts',
		'active_callback'		=> 'newscard_is_footer_featured_posts_set',
		'type'					=> 'radio',
		'checked'				=> 'checked',
		'choices'				=> array(
			'latest'				=> __('Show Latest Posts','newscard'),
			'category'				=> __('Show Posts from Category','newscard'),
		),
	) );
	$wp_customize->add_setting( 'newscard_footer_featured_post_categories', array(
		'default'				=> array(),
		'sanitize_callback'		=> 'newscard_sanitize_select',
		'capability' 			=> 'edit_theme_options'
	));
	$wp_customize->add_control( new NewsCard_Customize_drop_down_Category_Control( $wp_customize, 'newscard_footer_featured_post_categories', array(
		'label'					=> __('Choose Category', 'newscard'),
		'section'				=> 'newscard_footer_featured_posts',
		'active_callback'		=> 'newscard_is_footer_featured_latest_post_set',
		'type'					=> 'select'
	) ) );

	// Section => NewCard Settings
	$wp_customize->add_section( 'newscard_main_global_settings', array(
		'title'					=> __('NewsCard Settings', 'newscard'),
		'priority'				=> 191,
	));
	$wp_customize->add_setting( 'newscard_featured_image_single', array(
		'default'				=> 0,
		'sanitize_callback'		=> 'newscard_sanitize_integer',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_featured_image_single', array(
		'label'					=> __('Show Featured Image in Posts Single', 'newscard'),
		'section'				=> 'newscard_main_global_settings',
		'type'					=> 'checkbox',
	) );
	$wp_customize->add_setting( 'newscard_featured_image_page', array(
		'default'				=> 0,
		'sanitize_callback'		=> 'newscard_sanitize_integer',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_featured_image_page', array(
		'label'					=> __('Show Featured Image in Page', 'newscard'),
		'section'				=> 'newscard_main_global_settings',
		'type'					=> 'checkbox',
	) );
	$wp_customize->add_setting( 'newscard_archive_title_label_hide', array(
		'default'				=> 0,
		'sanitize_callback'		=> 'newscard_sanitize_integer',
		'capability' 			=> 'edit_theme_options'
	) );
	$wp_customize->add_control( 'newscard_archive_title_label_hide', array(
		'label'					=> __('Hide Archive Title Label', 'newscard'),
		'section'				=> 'newscard_main_global_settings',
		'type'					=> 'checkbox',
	) );


	// Section => NewsCard Support
	$wp_customize->add_section('newscard_support', array(
		'title'					=> __('NewsCard Support', 'newscard'),
		'priority'				=> 191,
	));
	$wp_customize->add_setting('newscard_support', array(
		'default'				=> false,
		'capability'			=> 'edit_theme_options',
		'sanitize_callback'	=> 'wp_filter_nohtml_kses',
	));
	$wp_customize->add_control( new NewsCard_Customize_NewsCard_Support( $wp_customize, 'newscard_support', array(
		'label'					=> __('NewsCard Support','newscard'),
		'section'				=> 'newscard_support'
	) ) );
}
add_action( 'customize_register', 'newscard_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function newscard_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function newscard_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

function newscard_customizer_control_scripts() {
	wp_enqueue_style( 'newscard-customize-controls', get_template_directory_uri() . '/assets/css/customize-controls.css' );
	wp_enqueue_script( 'newscard-customizer-control-js', get_template_directory_uri() . '/assets/js/customizer-control.js', array(), false, true );
}
add_action( 'customize_controls_enqueue_scripts', 'newscard_customizer_control_scripts', 0 );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function newscard_customize_preview_js() {
	wp_enqueue_script( 'newscard-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'newscard_customize_preview_js' );

/**
 * Sanitize the values
 */
if ( ! function_exists( 'newscard_sanitize_choices' ) ) {
	/**
	 * Sanitization: select
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Setting $setting Setting instance.
	 *
	 * @return mixed Sanitized value.
	 */
	function newscard_sanitize_choices($input, $setting) {

		// Ensure input is a slug.
		$input = sanitize_key($input);

		// Get list of choices from the control associated with the setting.
		$choices = $setting->manager->get_control($setting->id)->choices;

		// If the input is a valid key, return it; otherwise, return the default.
		return (array_key_exists($input, $choices) ? $input : $setting->default);
	}
}

if ( ! function_exists( 'newscard_sanitize_integer' ) ) {
	/**
	 * Sanitization: number_absint
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Setting $setting Setting instance.
	 *
	 * @return int Sanitized number.
	 */
	function newscard_sanitize_integer($input) {
		return absint($input);
	}
}

if ( ! function_exists( 'newscard_sanitize_social_links' ) ) {
	/**
	 * Sanitization: html
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Setting $setting Setting instance.
	 *
	 * @return string Sanitized content.
	 */
	function newscard_sanitize_social_links($input) {
		$input = trim(trim($input), ',');
		$explodedInput = explode(',', $input);

		$output = '';
		foreach ($explodedInput as $key => $inputX) {
			$inputX = trim($inputX);
			if (!empty($inputX)) {
				if ($key === 0) {
					$output .= $inputX;
				} else {
					$output .= ', ' . $inputX;
				}
			}
		}
		return sanitize_textarea_field($output);
	}
}

if ( ! function_exists( 'newscard_sanitize_select' ) ) {
	/**
	 * Sanitization: text
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Setting $setting Setting instance.
	 *
	 * @return string Sanitized content.
	 */
	function newscard_sanitize_select($input) {
		if ($input !== '') {
			return $input;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'newscard_is_social_profiles_set' ) ) {
	/**
	 * Check if social profiles is set.
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function newscard_is_social_profiles_set($control) {

		if ( '' !== $control->manager->get_setting('newscard_social_profiles')->value() ) {
			return true;
		} else {
			return false;
		}

	}
}

if ( ! function_exists( 'newscard_is_top_stories_set' ) ) {
	/**
	 * Check if top stories is enable.
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function newscard_is_top_stories_set($control) {

		if ( 0 === $control->manager->get_setting('newscard_top_stories_hide')->value() ) {
			return true;
		} else {
			return false;
		}

	}
}

if ( ! function_exists( 'newscard_is_top_stories_latest_post_set' ) ) {
	/**
	 * Check if top stories is enable.
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function newscard_is_top_stories_latest_post_set($control) {

		if ( 'category' === $control->manager->get_setting('newscard_top_stories_latest_post')->value() && 0 === $control->manager->get_setting('newscard_top_stories_hide')->value() ) {
			return true;
		} else {
			return false;
		}

	}
}

if ( ! function_exists( 'newscard_is_header_featured_posts_set' ) ) {
	/**
	 * Check if Featured Posts is enable.
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function newscard_is_header_featured_posts_set($control) {

		if ( 0 === $control->manager->get_setting('newscard_header_featured_posts_hide')->value() ) {
			return true;
		} else {
			return false;
		}

	}
}

if ( ! function_exists( 'newscard_is_header_featured_latest_post_set' ) ) {
	/**
	 * Check if post category is enable.
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function newscard_is_header_featured_latest_post_set($control) {

		if ( 'category' === $control->manager->get_setting('newscard_header_featured_latest_post')->value() && 0 === $control->manager->get_setting('newscard_header_featured_posts_hide')->value() ) {
			return true;
		} else {
			return false;
		}

	}
}

if ( ! function_exists( 'newscard_is_footer_featured_posts_set' ) ) {
	/**
	 * Check if Featured Posts is enable.
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function newscard_is_footer_featured_posts_set($control) {

		if ( 0 === $control->manager->get_setting('newscard_footer_featured_posts_hide')->value() ) {
			return true;
		} else {
			return false;
		}

	}
}

if ( ! function_exists( 'newscard_is_footer_featured_latest_post_set' ) ) {
	/**
	 * Check if post category is enable.
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function newscard_is_footer_featured_latest_post_set($control) {

		if ( 'category' === $control->manager->get_setting('newscard_footer_featured_latest_post')->value() && 0 === $control->manager->get_setting('newscard_footer_featured_posts_hide')->value() ) {
			return true;
		} else {
			return false;
		}

	}
}

if ( ! function_exists( 'newscard_is_banner_slider_posts_set' ) ) {
	/**
	 * Check if Banner Slider Posts is enable.
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function newscard_is_banner_slider_posts_set($control) {

		if ( 0 === $control->manager->get_setting('newscard_banner_slider_posts_hide')->value() ) {
			return true;
		} else {
			return false;
		}

	}
}

if ( ! function_exists( 'newscard_is_banner_slider_latest_post_set' ) ) {
	/**
	 * Check if banner slider category is enable.
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function newscard_is_banner_slider_latest_post_set($control) {

		if ( 'category' === $control->manager->get_setting('newscard_banner_slider_latest_post')->value() && 0 === $control->manager->get_setting('newscard_banner_slider_posts_hide')->value() ) {
			return true;
		} else {
			return false;
		}

	}
}

if ( ! function_exists( 'newscard_is_banner_featured_posts_1_set' ) ) {
	/**
	 * Check if Banner Featured Posts 1 is enable.
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function newscard_is_banner_featured_posts_1_set($control) {

		if ( 0 === $control->manager->get_setting('newscard_banner_featured_posts_1_hide')->value() ) {
			return true;
		} else {
			return false;
		}

	}
}

if ( ! function_exists( 'newscard_is_banner_featured_posts_1_latest_post_set' ) ) {
	/**
	 * Check if banner featured post 1 category is enable.
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function newscard_is_banner_featured_posts_1_latest_post_set($control) {

		if ( 'category' === $control->manager->get_setting('newscard_banner_featured_posts_1_latest_post')->value() && 0 === $control->manager->get_setting('newscard_banner_featured_posts_1_hide')->value() ) {
			return true;
		} else {
			return false;
		}

	}
}

if ( ! function_exists( 'newscard_is_banner_featured_posts_2_set' ) ) {
	/**
	 * Check if Banner Featured Posts 2 is enable.
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function newscard_is_banner_featured_posts_2_set($control) {

		if ( 0 === $control->manager->get_setting('newscard_banner_featured_posts_2_hide')->value() ) {
			return true;
		} else {
			return false;
		}

	}
}

if ( ! function_exists( 'newscard_is_banner_featured_posts_2_latest_post_set' ) ) {
	/**
	 * Check if banner featured post 2 category is enable.
	 *
	 * @since 1.1.1
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function newscard_is_banner_featured_posts_2_latest_post_set($control) {

		if ( 'category' === $control->manager->get_setting('newscard_banner_featured_posts_2_latest_post')->value() && 0 === $control->manager->get_setting('newscard_banner_featured_posts_2_hide')->value() ) {
			return true;
		} else {
			return false;
		}

	}
}