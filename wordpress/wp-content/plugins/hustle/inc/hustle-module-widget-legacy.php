<?php
/**
 * Legacy class for old widgets.
 *
 * @package Hustle
 */

/**
 * Class Hustle_Module_Widget_Legacy
 */
class Hustle_Module_Widget_Legacy extends WP_Widget {

	/**
	 * Widget Id.
	 *
	 * @var string
	 */
	const WIDGET_ID = 'inc_opt_widget';


	/**
	 * Registers the widget
	 */
	public function __construct() {
		parent::__construct(
			self::WIDGET_ID,
			__( 'Hustle Legacy', 'hustle' ),
			array( 'description' => __( 'A legacy widget to add Opt-ins', 'hustle' ) )
		);
	}

	/**
	 * Get module id from old optin id.
	 *
	 * @param  int $optin_id   Option ID.
	 * @return mixed           Module id or bool.
	 */
	private function get_module_id( $optin_id ) {
		global $wpdb;
		$data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM  `{$wpdb->prefix}optins` WHERE `optin_id`=%d", $optin_id ), OBJECT );

		if ( isset( $data->optin_name ) ) {
			$type = 'embedded';
			$type = ( 'social_sharing' === $data->optin_provider ) ? 'social_sharing' : $type;
			$data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM  `{$wpdb->prefix}hustle_modules` WHERE `module_name`=%s and `module_type` = %s", $data->optin_name, $type ), OBJECT );
			return ( isset( $data->module_id ) ) ? (int) $data->module_id : false;
		}
		return false;
	}

	/**
	 *
	 * Front-end display of widget.
	 *
	 * @param array $args     Args.
	 * @param array $instance Previously saved values from database.
	 * @return string
	 */
	public function widget( $args, $instance ) {
		if ( isset( $instance['optin_id'] ) && ! empty( $instance['optin_id'] ) ) {
			$instance['module_id'] = $this->get_module_id( $instance['optin_id'] );
		}
		$show_select = false;

		// phpcs:disable
		if ( empty( $instance['module_id'] ) ) {
			$show_select = true;
		}
		if ( ! $show_select ) {
			$module = new Hustle_Module_Model( $instance['module_id'] );
			if ( is_wp_error( $module ) ) {
				$show_select = true;
			}
		}
		if ( $show_select ) {
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}
			esc_attr_e( 'Select Module', 'hustle' );
			echo $args['after_widget'];
			return;
		}

		// if( !$module->settings->widget->show_in_front() ){
			// echo $args['before_widget'];
			// echo $args['after_widget'];
			// return;
		// }

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}

		$widget_css_class = ( 'social_sharing' === $module->module_type )
			? 'hustle_sshare_module_widget_wrap'
			: 'hustle_module_widget_wrap';

		?>

		<div class="<?php echo esc_attr( $widget_css_class ); ?> module_id_<?php echo esc_attr( $instance['module_id'] ); ?>" data-type="widget" data-id="<?php echo esc_attr( $instance['module_id'] ); ?>"></div>
		<?php

		echo $args['after_widget'];
		// phpcs:enable
	}


	/**
	 *
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 * @param array $instance Previously saved values from database.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		if ( isset( $instance['optin_id'] ) && ! empty( $instance['optin_id'] ) ) {
			$instance['module_id'] = $this->get_module_id( $instance['optin_id'] );
		}
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'hustle' );
		if ( empty( $instance['module_id'] ) ) {
			$instance['module_id'] = -1; }
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'hustle' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'module_id' ) ); ?>"><?php esc_attr_e( 'Select Module:', 'hustle' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'module_id' ) ); ?>" id="hustle_module_id">
				<option value=""><?php esc_attr_e( 'Select Module', 'hustle' ); ?></option>
				<?php
					$types = array( 'embedded', 'social_sharing' );
				foreach ( Hustle_Module_Collection::instance()->get_embed_id_names( $types ) as $mod ) :
					$module = new Hustle_Module_Model( $mod->module_id );
					if ( is_wp_error( $module ) ) {
						continue;
					}
					// if( $module->settings->widget->show_in_front() ):
					?>
					<option <?php selected( $instance['module_id'], $mod->module_id ); ?> value="<?php echo esc_attr( $mod->module_id ); ?>"><?php echo esc_attr( $mod->module_name ); ?></option>

					<?php
					// endif;
					endforeach;
				?>
			</select>
		</p>
		<?php
	}


	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance              = array();
		$instance['title']     = ! empty( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['module_id'] = ! empty( $new_instance['module_id'] ) ? wp_strip_all_tags( $new_instance['module_id'] ) : '';

		return $instance;
	}
}