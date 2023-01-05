<?php

function econsulting_agency_achievements_lists(){

	ob_start();

	$data = bizberg_get_theme_mod( 'econsulting_agency_achievements_list' );
	$data = json_decode( $data, true );

	if( !empty( $data ) && is_array( $data ) ){ ?>

		<div class="econsult-counter-in">

			<?php 
			$i = count( $data ) - 1;
			foreach( $data as $key => $a ){
				$icon   = !empty( $a['icon'] ) ? $a['icon'] : '';
				$count  = !empty( $a['count'] ) ? $a['count'] : '';
				$label  = !empty( $a['label'] ) ? $a['label'] : '';
				$suffix = !empty( $a['suffix'] ) ? $a['suffix'] : '';
				$line   = ( $key < $i ? 'd-line' : '' ); ?>
				<div class="col-md-3 col-sm-6">
					<div class="counter-item <?php echo esc_attr( $line ); ?>">
						<i class="<?php echo esc_attr( $icon ); ?>"></i>
						<h2><span class="school-value"><?php echo esc_html( $count ); ?></span><?php echo esc_html( $suffix ); ?></h2>
						<p><?php echo esc_html( $label ); ?></p>
					</div>
				</div>
				<?php 
			} ?>

		</div>

		<?php

	}

	return ob_get_clean();

}

add_action( 'bizberg_before_homepage_blog', 'econsulting_agency_achievements_section' );
function econsulting_agency_achievements_section(){

	$status = bizberg_get_theme_mod( 'econsulting_agency_achievements_status' );

	if( $status != true ){
		return;
	} ?>

	<section class="econsult-counter">
		<div class="container">
			<?php echo wp_kses_post( econsulting_agency_achievements_lists() ); ?>
		</div>
	</section>

	<?php

	do_action( 'econsulting_agency_after_achievements' );
	
}