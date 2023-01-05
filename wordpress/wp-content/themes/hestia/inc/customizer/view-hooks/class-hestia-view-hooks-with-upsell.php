<?php
/**
 * Class that handle the show/hide hooks.
 *
 * @package Hestia
 */

/**
 * Class Hestia_View_Hooks
 */
class Hestia_View_Hooks_With_Upsell {

	/**
	 * Initialize function.
	 */
	public function init() {
		if ( ! $this->should_load() ) {
			return;
		}
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 99 );
		add_action( 'wp', array( $this, 'render_hook_placeholder' ) );
		add_action( 'wp_head', array( $this, 'render_hook_placeholder_css' ) );
	}

	/**
	 * Check user role before allowing the class to run
	 *
	 * @return bool
	 */
	private function should_load() {
		return current_user_can( 'administrator' );
	}

	/**
	 * Admin Bar Menu
	 *
	 * @param array $wp_admin_bar Admin bar menus.
	 */
	function admin_bar_menu( $wp_admin_bar = array() ) {
		if ( is_admin() ) {
			return;
		}

		$title = __( 'Show Hooks', 'hestia' );

		$href = add_query_arg( 'hestia_preview_hook', 'show' );
		if ( isset( $_GET['hestia_preview_hook'] ) && 'show' === $_GET['hestia_preview_hook'] ) {
			$title = __( 'Hide Hooks', 'hestia' );
			$href  = remove_query_arg( 'hestia_preview_hook' );
		}

		$wp_admin_bar->add_menu(
			array(
				'title'  => sprintf( '%s <span class="dashicons dashicons-lock"></span>', $title ),
				'id'     => 'hestia_preview_hook',
				'parent' => false,
				'href'   => $href,
			)
		);
	}

	/**
	 * Beautify hook names.
	 *
	 * @param string $hook Hook name.
	 *
	 * @return string
	 */
	public static function beautify_hook( $hook ) {
		$hook_label = str_replace( '_', ' ', $hook );
		$hook_label = str_replace( 'hestia', ' ', $hook_label );
		$hook_label = str_replace( 'woocommerce', ' ', $hook_label );
		$hook_label = ucwords( $hook_label );
		return $hook_label;
	}

	/**
	 * Render hook placeholder.
	 */
	public function render_hook_placeholder() {
		if ( ! isset( $_GET['hestia_preview_hook'] ) || 'show' !== $_GET['hestia_preview_hook'] ) {
			return;
		}
		$hooks = $this->hook_lists();
		foreach ( $hooks as $hooks_in_category ) {
			foreach ( $hooks_in_category as $hook_value ) {
				$hook_label = self::beautify_hook( $hook_value );
				add_action(
					$hook_value,
					function () use ( $hook_label ) {
						echo '<div class="hestia-hook-wrapper hestia-hook-upsell-wrapper">';
						echo '<div class="hestia-hook-placeholder">';
						echo '<span class="hestia-hook-label">' . esc_html( $hook_label ) . '</span>';
						echo '<div class="hestia-hook-upsell">' . __( 'Add content to this location conditionally using', 'hestia' ) . '<a href="' . tsdk_utmify( 'https://themeisle.com/themes/hestia-pro/upgrade/', 'viewhooks' ) . '" target="_blank"> ' . __( 'Hestia PRO', 'hestia' ) . '</a></div>';
						echo '</div>';
						echo '</div>';
					}
				);
			}
		}
	}

	/**
	 * Hook lists.
	 */
	private function hook_lists() {
		$hooks = array(
			'header'     => array(
				'hestia_before_header_content_hook',
				'hestia_before_header_hook',
				'hestia_after_header_hook',
				'hestia_after_header_content_hook',
			),
			'footer'     => array(
				'hestia_before_footer_hook',
				'hestia_after_footer_hook',
				'hestia_before_footer_content_hook',
				'hestia_after_footer_content_hook',
				'hestia_before_footer_widgets_hook',
				'hestia_after_footer_widgets_hook',
			),
			'frontpage'  => array(
				'hestia_before_big_title_section_hook',
				'hestia_before_big_title_section_content_hook',
				'hestia_top_big_title_section_content_hook',
				'hestia_big_title_section_buttons',
				'hestia_bottom_big_title_section_content_hook',
				'hestia_after_big_title_section_content_hook',
				'hestia_after_big_title_section_hook',
				'hestia_before_team_section_hook',
				'hestia_before_team_section_content_hook',
				'hestia_top_team_section_content_hook',
				'hestia_bottom_team_section_content_hook',
				'hestia_after_team_section_content_hook',
				'hestia_after_team_section_hook',
				'hestia_before_features_section_hook',
				'hestia_before_features_section_content_hook',
				'hestia_top_features_section_content_hook',
				'hestia_bottom_features_section_content_hook',
				'hestia_after_features_section_content_hook',
				'hestia_after_features_section_hook',
				'hestia_before_pricing_section_hook',
				'hestia_before_pricing_section_content_hook',
				'hestia_top_pricing_section_content_hook',
				'hestia_bottom_pricing_section_content_hook',
				'hestia_after_pricing_section_content_hook',
				'hestia_after_pricing_section_hook',
				'hestia_before_about_section_hook',
				'hestia_after_about_section_hook',
				'hestia_before_shop_section_hook',
				'hestia_before_shop_section_content_hook',
				'hestia_top_shop_section_content_hook',
				'hestia_bottom_shop_section_content_hook',
				'hestia_after_shop_section_content_hook',
				'hestia_after_shop_section_hook',
				'hestia_before_testimonials_section_hook',
				'hestia_before_testimonials_section_content_hook',
				'hestia_top_testimonials_section_content_hook',
				'hestia_bottom_testimonials_section_content_hook',
				'hestia_after_testimonials_section_content_hook',
				'hestia_after_testimonials_section_hook',
				'hestia_before_subscribe_section_hook',
				'hestia_before_subscribe_section_content_hook',
				'hestia_top_subscribe_section_content_hook',
				'hestia_bottom_subscribe_section_content_hook',
				'hestia_after_subscribe_section_content_hook',
				'hestia_after_subscribe_section_hook',
				'hestia_before_blog_section_hook',
				'hestia_before_blog_section_content_hook',
				'hestia_top_blog_section_content_hook',
				'hestia_bottom_blog_section_content_hook',
				'hestia_after_blog_section_content_hook',
				'hestia_after_blog_section_hook',
				'hestia_before_contact_section_hook',
				'hestia_before_contact_section_content_hook',
				'hestia_top_contact_section_content_hook',
				'hestia_bottom_contact_section_content_hook',
				'hestia_after_contact_section_content_hook',
				'hestia_after_contact_section_hook',
				'hestia_before_portfolio_section_hook',
				'hestia_before_portfolio_section_content_hook',
				'hestia_top_portfolio_section_content_hook',
				'hestia_bottom_portfolio_section_content_hook',
				'hestia_after_portfolio_section_content_hook',
				'hestia_after_portfolio_section_hook',
				'hestia_before_clients_bar_section_hook',
				'hestia_clients_bar_section_content_hook',
				'hestia_after_clients_bar_section_hook',
				'hestia_before_ribbon_section_hook',
				'hestia_after_ribbon_section_hook',
			),
			'post'       => array(
				'hestia_before_single_post_article',
				'hestia_after_single_post_article',
			),
			'page'       => array(
				'hestia_before_page_content',
			),
			'sidebar'    => array(
				'hestia_before_sidebar_content',
				'hestia_after_sidebar_content',
			),
			'blog'       => array(
				'hestia_before_index_posts_loop',
				'hestia_before_index_content',
				'hestia_after_archive_content',
			),
			'pagination' => array(
				'hestia_before_pagination',
				'hestia_after_pagination',
			),
		);
		return $hooks;
	}

	/**
	 * View hook page css.
	 */
	public function render_hook_placeholder_css() {
		$css = '
		.hestia-hook-wrapper {
			text-align: center; width: 100%;
		}
		.hestia-hook-placeholder {
			display: flex; 
			width: 98%; 
			justify-content: center;
			align-items: center;
			margin: 10px auto; 
			border: 2px dashed #A020F0;
			font-size: 14px; 
			padding: 6px 10px; 
			text-align: left; 
			word-break: break-word;
			color: #A020F0;
		}
		.hestia-hook-placeholder a, .hestia-hook-upsell a {
			align-items: center;
			justify-content: center;
			min-width: 250px;
			width: 100%;
			font-size: 14px !important;
			min-height: 32px;
			text-decoration: none;
			color: #A020F0 !important;
		}
		.hestia-hook-placeholder a:hover, .hestia-hook-upsell a:hover {
			color: #A020F0 !important;
		}
		.hestia-hook-placeholder a:hover, .hestia-hook-placeholder a:focus {
			text-decoration: none;
		}
		.hestia-hook-placeholder a:hover .hestia-hook-icon, .hestia-hook-placeholder a:focus .hestia-hook-icon {
			box-shadow: inset 0 0 0 1px  #A020F0;
			color: #A020F0;
			opacity: 1;
			display: block;
		 }
		.hestia-hook-placeholder a .hestia-hook-icon {
			box-shadow: inset 0 0 0 1px #A020F0;
			border-radius: 50%;
			width: 20px;
			height: 20px;
			font-size: 16px;
			padding: 3px 2px;
			margin-left: -2px;
			opacity: 0;
			transform:rotate(360deg);
			transition: all 300ms cubic-bezier(0.4, 0, 0.2, 1);
			position: absolute;
		}
		.hestia-hook-placeholder a .hestia-hook-label {
			transition: all 300ms cubic-bezier(0.4, 0, 0.2, 1);
			font-size: 14px;
			opacity: 1;
		}
		.hestia-hook-placeholder a:hover .hestia-hook-label, .hestia-hook-placeholder a:focus .hestia-hook-label {
			opacity: 0;
		}
		.section-image .hestia-hook-wrapper {
			position: relative;
			z-index: 2;
		}';
		echo '<style type="text/css">';
		echo esc_attr( hestia_minimize_css( $css ) );
		echo '</style>';
	}


}
