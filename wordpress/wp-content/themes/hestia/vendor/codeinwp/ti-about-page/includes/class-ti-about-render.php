<?php
/**
 * Render class fot the about page
 */

class TI_About_Render {

	/**
	 * @var array - theme args
	 */
	private $theme = array();

	/**
	 * Regular tabs, any theme should have this information in the About Page.
	 */
	private $tabs = array();

	/**
	 * @var Ti_About_Page
	 */
	private $about_page = null;

	/**
	 * Custom tabs based on theme's particularities
	 */
	private $custom_tabs = array();

    /**
     * Branding notice.
     */
    private $branding_notice = array();

	/**
	 * TI_About_Render constructor.
	 *
	 * @param array         $theme_args - current theme args.
	 * @param array         $data       - about page content.
	 * @param Ti_About_Page $about_page - about page content.
	 */
	public function __construct( $theme_args, $data, $about_page ) {
		$this->theme      = $theme_args;
		$this->tabs       = $data;
		$this->about_page = $about_page;
        if ( isset( $data['branding_notice'] ) ){
            $this->branding_notice = $data['branding_notice'];
        }
		if ( isset( $this->tabs['custom_tabs'] ) ) {
			$this->custom_tabs = $data['custom_tabs'];
			unset( $this->tabs['custom_tabs'] );
		}

		$this->render();
	}

	/**
	 * The main render function
	 */
	private function render() {

		if ( empty( $this->tabs ) ) {
			return;
		}

		echo '<div class="loading-screen">';
		echo '<div class="updating-message">';
		echo '<p>' . esc_html__( 'Loading', 'hestia' ) . '...</p>';
		echo '</div>';
		echo '</div>';

		echo '<div class="ti-about-wrap">';
		$this->render_header();
		echo '<div class="main-content">';
		echo '<div id="about-tabs">';
		$this->render_tabs_content();
		echo '</div>';
		$this->render_sidebar();
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Render the header
	 */
	private function render_header() {

		?>
		<div class="header">
			<div class="header-info">
				<h1>Welcome to <?php echo esc_html( $this->theme['name'] ); ?>! - Version <span
						class="version-container"><?php echo esc_html( $this->theme['version'] ); ?></span></h1>
				<?php
				$white_label_options = get_option( 'ti_white_label_inputs' );
				$white_label_options = json_decode( $white_label_options, true );
				if ( empty( $white_label_options['theme_name'] ) ) { ?>
					<span class="ti-logo"><img
							src="<?php echo esc_url( TI_ABOUT_PAGE_URL . 'assets/img/logo.png' ) ?>"
							alt="logo"/>
					</span>
					<?php
				} ?>
			</div>
			<?php $this->render_tabs_list(); ?>
            <?php $this->render_branding_notice(); ?>
		</div>
		<?php
	}

	/**
	 * Render tabs list
	 */
	private function render_tabs_list() {
		echo '<ul class="ti-about-tablist">';
		foreach ( $this->tabs as $slug => $tab_data ) {
			if ( ! array_key_exists( 'type', $tab_data ) ) {
				continue;
			}
			if ( $tab_data['type'] === 'recommended_actions' && $this->about_page->get_recommended_actions_left() === 0 ) {
				continue;
			}
			if ( $slug === 'welcome_notice' || $slug === 'footer_messages' ) {
				continue;
			}

			echo '<li data-tab-id="' . esc_attr( $slug ) . '">';
			echo '<a class="tab';
			if ( $tab_data['type'] === 'recommended_actions' ) {
				echo ' recommended_actions';
			}
			if ( $slug === 'getting_started' ) {
				echo ' active';
			}
			echo '" href="#' . esc_attr( $slug ) . '">' . esc_html( $tab_data['title'] ) . '</a>';
			echo '</li>';
		}

		foreach ( $this->custom_tabs as $slug => $tab_data ) {
			echo '<li data-tab-id="' . esc_attr( $slug ) . '">';
			echo '<a class="tab" href="#' . esc_attr( $slug ) . '">' . esc_html( $tab_data['title'] ) . '</a>';
			echo '</li>';
		}
		echo '</ul>';
	}

    /**
     * Render the branding notice.
     */
    private function render_branding_notice() {
        if ( empty( $this->branding_notice ) ){
            return;
        }

        $style = '
        .ti-notice-wrap{
            background: #f0f0f1;
            width: 100%
        }
        .ti-branding-notice{
            margin: 30px 40px 0 40px;
            padding: 30px 40px;
            background: #2271b1;
            color: #fff;
            display: flex;
            align-items: center;
        }
        .ti-upgrade-btn{
            padding: 10px 15px;
            background-color: transparent;
            border: 2px solid #fff;
            border-radius: 2px;
            box-shadow: none;
            color: #fff;
            flex-shrink: 0;
            font-size: 14px;
            font-weight: 600;
            height: auto;
            line-height: normal;
            margin-left: auto;
        }
        .ti-upgrade-btn:hover{
            background: #fff;
            box-shadow: none;
            color: #0073aa;
        }
		';

        echo '<style>' . $style . '</style>';
        echo '<div class="ti-notice-wrap">';
        echo '<div class="ti-branding-notice">';
        if ( isset( $this->branding_notice['text'] ) ){
            echo '<p>' . wp_kses_post( $this->branding_notice['text'] ) . '</p>';
        }

        if ( isset( $this->branding_notice['url'] ) && isset( $this->branding_notice['cta'] ) ){
            echo '<a target="_blank" class="ti-upgrade-btn" href="' . esc_url($this->branding_notice['url']) . '">';
            echo wp_kses_post( $this->branding_notice['cta'] );
            echo '</a>';
        }
        echo '</div>';
        echo '</div>';
    }

	/**
	 * Render tab content
	 */
	private function render_tabs_content() {
		foreach ( $this->tabs as $slug => $tab_data ) {
			if ( ! array_key_exists( 'type', $tab_data ) ) {
				continue;
			}
			if ( $slug === 'recommended_actions' && $this->about_page->get_recommended_actions_left() === 0 ) {
				continue;
			}
			if ( $slug === 'welcome_notice' || $slug === 'footer_messages' ) {
				continue;
			}

			echo '<div id="' . esc_attr( $slug ) . '" class="' . esc_attr( $tab_data['type'] ) . ' tab-content ' . ( $slug === 'getting_started' ? 'active' : '' ) . '">';

			switch ( $tab_data['type'] ) {

				case 'recommended_actions':
					$this->render_recommended_actions( $tab_data['plugins'] );
					break;
				case 'plugins':
                    $external_plugins = isset( $tab_data['external'] ) ? $tab_data['external'] : array();
					$this->render_plugins_tab( $tab_data['plugins'], $external_plugins );
					break;
				case 'changelog':
					$this->render_changelog();
					break;
				default:
					$this->render_default_tab( $tab_data['content'] );
					break;
			}

			echo '</div>';
		}
		foreach ( $this->custom_tabs as $slug => $tab_data ) {

			echo '<div id="' . esc_attr( $slug ) . '" class="custom tab-content">';
			call_user_func( $tab_data['render_callback'] );
			echo '</div>';
		}
	}

	/**
	 * Render recommended actions
	 *
	 * @param array $plugins_list - recommended plugins.
	 */
	private function render_recommended_actions( $plugins_list ) {
		if ( empty( $plugins_list ) || $this->about_page->get_recommended_actions_left() === 0 ) {
			return;
		}

		$recommended_plugins_visbility = get_theme_mod( 'ti_about_recommended_plugins' );

		foreach ( $plugins_list as $slug => $plugin ) {
			if ( $recommended_plugins_visbility[ $slug ] === 'hidden' || Ti_About_Plugin_Helper::instance()->check_plugin_state( $slug ) === 'deactivate' ) {
				continue;
			}

			echo '<div class="ti-about-page-action-required-box ' . esc_attr( $slug ) . '">';
			echo '<span class="dashicons dashicons-visibility ti-about-page-required-action-button" data-slug="' . esc_attr( $slug ) . '"></span>';
			echo '<h3>' . $plugin['name'] . '</h3>';
			if ( ! empty( $plugin['description'] ) ) {
				echo '<p>' . $plugin['description'] . '</p>';
			} else {
				$plugin_description = $this->call_plugin_api( $slug );
				echo '<p>' . $plugin_description->short_description . '</p>';
			}
			echo Ti_About_Plugin_Helper::instance()->get_button_html( $slug, array( 'redirect' => add_query_arg( 'page', $this->theme['slug'] . '-welcome', admin_url( 'themes.php#recommended_actions' ) ) ) );
			echo '</div>';
		}
	}

	/**
	 * Call plugin api
	 *
	 * @param string $slug plugin slug.
	 *
	 * @return array|mixed|object
	 */
	private function call_plugin_api( $slug ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

		$call_api = get_transient( 'ti_about_plugin_info_' . $slug );

		if ( false === $call_api ) {
			$call_api = plugins_api(
				'plugin_information',
				array(
					'slug'   => $slug,
					'fields' => array(
						'downloaded'        => false,
						'rating'            => false,
						'description'       => false,
						'short_description' => true,
						'donate_link'       => false,
						'tags'              => false,
						'sections'          => true,
						'homepage'          => true,
						'added'             => false,
						'last_updated'      => false,
						'compatibility'     => false,
						'tested'            => false,
						'requires'          => false,
						'downloadlink'      => false,
						'icons'             => true,
						'banners'           => true,
					),
				)
			);
			set_transient( 'ti_about_plugin_info_' . $slug, $call_api, 30 * MINUTE_IN_SECONDS );
		}

		return $call_api;
	}

    /**
     * Render the plugin card in recommended plugins tab.
     *
     * @param string $plugin_slug Plugin slug.
     * @param array $data Plugin data.
     */
    private function render_plugin_card( $plugin_slug, $data ) {


        echo '<div class="plugin_box">';

        if( isset( $data['premium'] ) && $data['premium'] === true ){
            echo '<span class="premium-label">Premium</span>';
        }
        if ( isset( $data['banners']['low'] ) ){
            echo '<img class="plugin-banner" src="' . esc_attr( $data['banners']['low'] ) . '">';
        }
        echo '<div class="title-action-wrapper">';
        echo '<span class="plugin-name">' . esc_html( $data['name'] ) . '</span>';
        echo '<span class="plugin-desc">' . esc_html( $data['short_description'] ) . '</span>';
        echo '</div>';
        echo '<div class="plugin-box-footer">';
        echo '<div class="button-wrap">';
        if ( isset( $data['url'] ) ){
            echo '<a class="external-link" href="' . esc_url( $data['url'] ) . '" target="_blank" rel="external noreferrer noopener">';
            echo esc_html__('Learn More', 'hestia' );
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" class="components-external-link__icon css-6wogo1-StyledIcon etxm6pv0" role="img" aria-hidden="true" focusable="false"><path d="M18.2 17c0 .7-.6 1.2-1.2 1.2H7c-.7 0-1.2-.6-1.2-1.2V7c0-.7.6-1.2 1.2-1.2h3.2V4.2H7C5.5 4.2 4.2 5.5 4.2 7v10c0 1.5 1.2 2.8 2.8 2.8h10c1.5 0 2.8-1.2 2.8-2.8v-3.6h-1.5V17zM14.9 3v1.5h3.7l-6.4 6.4 1.1 1.1 6.4-6.4v3.7h1.5V3h-6.3z"></path></svg>';
            echo '</a>';
        } else {
            echo Ti_About_Plugin_Helper::instance()->get_button_html( $plugin_slug );
        }
        echo '</div>';
        echo '<div class="version-wrapper">';
        if ( isset( $data['version'] ) ){
            echo '<span class="version">' . esc_html( $data['version'] ) . '</span>';
            echo '<span class="separator"> | </span>';
        }
        echo  strtok( strip_tags( $data['author'] ), ',' );
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Check if the plugin has the required data to be displayed
     *
     * @param array $data Plugin data.
     * @return bool
     */
    private function has_required_data( $data ) {
        $required_data = [ 'banners', 'name', 'short_description', 'author' ];
        return count( array_filter( $required_data, function ( $element ) use ( $data ) {
           return ! array_key_exists( $element, $data );
        } ) ) === 0;
    }

	/**
	 * Render plugins tab content.
	 *
	 * @param array $plugins_list - list of useful plugins
     * @param array $external - list of plugins outside wp.org
	 */
	private function render_plugins_tab( $plugins_list, $external = [] ) {

		if ( empty( $plugins_list ) ) {
			return;
		}

		echo '<div class="recommended-plugins">';

		foreach ( $plugins_list as $plugin ) {
			$plugin_data = $this->call_plugin_api( $plugin );
            if ( ! is_object( $plugin_data ) ){
                continue;
            }

            $plugin_data = get_object_vars( $plugin_data );
            if ( ! $this->has_required_data( $plugin_data ) ) {
                continue;
            }

            $this->render_plugin_card( $plugin, $plugin_data );
		}

        foreach ( $external as $plugin => $plugin_data ){
            if ( ! $this->has_required_data( $plugin_data ) ) {
                continue;
            }
            $this->render_plugin_card( $plugin, $plugin_data );
        }

		echo '</div>';
	}

	/**
	 * Render changelog
	 */
	private function render_changelog() {
		$changelog = $this->parse_changelog();
		if ( ! empty( $changelog ) ) {
			echo '<div class="featured-section changelog">';
			foreach ( $changelog as $release ) {
				echo '<div class="release-wrap">';
				if ( ! empty( $release['title'] ) ) {
					echo '<h3>' . str_replace( '#', '', $release['title'] ) . ' </h3 > ';
				}
				echo '<ul class="release">';

				if ( ! empty( $release['changes'] ) ) {
					foreach ( $release['changes'] as $change ) {
						if ( empty( trim( $change ) ) ) {
							continue;
						}
						echo '<li>' . esc_html( ltrim( $change ) ) . '</li>';
					}
				}
				echo '</ul>';
				echo '</div>';

			}
			echo '</div>';
		}
	}

	/**
	 * Return the releases changes array.
	 *
	 * @return array $releases - changelog.
	 */
	private function parse_changelog() {
		WP_Filesystem();
		global $wp_filesystem;
		$changelog = $wp_filesystem->get_contents( get_template_directory() . '/CHANGELOG.md' );
		if ( is_wp_error( $changelog ) ) {
			$changelog = '';
		}
		$changelog = explode( PHP_EOL, $changelog );
		$releases  = array();
		foreach ( $changelog as $changelog_line ) {
			if ( strpos( $changelog_line, '**Changes:**' ) !== false || empty( $changelog_line ) ) {
				continue;
			}
			if ( substr( ltrim( $changelog_line ), 0, 3 ) === '###' ) {
				if ( isset( $release ) ) {
					$releases[] = $release;
				}
				$release = array(
					'title'   => substr( $changelog_line, 3 ),
					'changes' => array(),
				);
			} else {
				$release['changes'][] = str_replace( '*', '', $changelog_line );
			}
		}

		return $releases;


	}

	/**
	 * Render default tab content.
	 *
	 * @param array $tab_content - tab content, title, text, button.
	 */
	private function render_default_tab( $tab_content ) {
		foreach ( $tab_content as $content ) {
			echo '<div class="about-col">';
			echo '<h3>';
			if ( ! empty( $content['icon'] ) ) {
				echo '<i class="dashicons dashicons-' . esc_attr( $content['icon'] ) . '"></i>';
			}
			echo esc_html( $content['title'] ) . '</h3>';
			if ( ! empty( $content['text'] ) ) {
				echo '<p>' . esc_html( $content['text'] ) . '</p>';
			}
			if ( ! empty( $content['html_content'] ) ) {
				echo $content['html_content'];
			}
			if ( ! empty( $content['button'] ) ) {
				$this->render_button( $content['button'] );
			}
			echo '</div>';
		}
	}

	/**
	 * Render button.
	 *
	 * @param array $button - args: label, link, new tab.
	 */
	private function render_button( $button ) {
		if ( empty( $button ) ) {
			return;
		}

		if ( $button['link'] === '#recommended_actions' && $this->about_page->get_recommended_actions_left() === 0 ) {
			echo '<span>' . esc_html__( 'Recommended actions', 'hestia' ) . '</span>';

			return;
		}

		echo '<a href="' . esc_url( $button['link'] ) . '"';
		echo $button['is_button'] ? 'class="button button-primary"' : '';
		echo '>';
		echo $button['label'];
		echo '</a>';
	}

	/**
	 * Render footer messages.
	 */
	private function render_sidebar() {
		if ( ! array_key_exists( 'footer_messages', $this->tabs ) ) {
			return;
		}
		$footer_data = $this->tabs['footer_messages']['messages'];
		echo '<div class="about-sidebar">';
		do_action( 'ti-about-before-sidebar-content' );
		foreach ( $footer_data as $data ) {
			$heading   = ! empty( $data['heading'] ) ? $data['heading'] : '';
			$text      = ! empty( $data['text'] ) ? $data['text'] : '';
			$link_text = ! empty( $data['link_text'] ) ? $data['link_text'] : '';
			$link      = ! empty( $data['link'] ) ? $data['link'] : '';

			if ( empty( $heading ) && empty( $text ) && ( empty( $link_text ) || empty( $link ) ) ) {
				continue;
			}
			echo '<div class="about-sidebar-item">';
			if ( ! empty( $heading ) ) {
				echo '<h4>' . wp_kses_post( $heading ) . '</h4>';
			}

			echo '<div class="inside">';
			if ( ! empty( $text ) ) {
				echo '<p>' . wp_kses_post( $text ) . '</p>';
			}
			if ( ! empty( $link_text ) && ! empty( $link ) ) {
				echo '<a href="' . esc_url( $link ) . '">' . wp_kses_post( $link_text ) . '</a>';
			}
			echo '</div>';
			echo '</div>';
		}
		do_action( 'ti-about-after-sidebar-content' );
		echo '</div>';
	}
}
