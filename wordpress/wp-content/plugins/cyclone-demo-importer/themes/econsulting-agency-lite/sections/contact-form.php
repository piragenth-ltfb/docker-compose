<?php 

function econsulting_agency_contact_form_get_subtitle(){
	$subtitle = bizberg_get_theme_mod( 'econsulting_agency_contact_subtitle' );
	return esc_html( $subtitle );
}

function econsulting_agency_contact_form_get_title(){
	$title = bizberg_get_theme_mod( 'econsulting_agency_contact_title' );
	return esc_html( $title );
}

function econsulting_agency_contact_form_get_description(){
	$description = bizberg_get_theme_mod( 'econsulting_agency_contact_description' );
	return esc_html( $description );
}

function econsulting_agency_get_contact_form_7(){
	ob_start();
	$form_id = bizberg_get_theme_mod( 'econsulting_agency_contact_form_7_forms' );
	echo do_shortcode( '[contact-form-7 id="' . absint( $form_id ) . '"]' );
	return ob_get_clean();
}

function econsulting_agency_contact_form_locations_get_title(){
	$title = bizberg_get_theme_mod( 'econsulting_agency_contact_form_locations_title' );
	return esc_html( $title );
}

function econsulting_agency_contact_form_location_list(){
	ob_start();
	$data = bizberg_get_theme_mod( 'econsulting_agency_contact_list' );
	$data = json_decode( $data, true );
	if( !empty( $data ) && is_array( $data ) ){
		foreach ( $data as $key => $value ) {
			$location               = !empty( $value['location'] ) ? $value['location'] : '';
			$sub_location           = !empty( $value['sub_location'] ) ? $value['sub_location'] : '';
			$email                  = !empty( $value['email'] ) ? $value['email'] : '';
			$tel                    = !empty( $value['tel'] ) ? $value['tel'] : '';

			$custom_field_1_label   = !empty( $value['custom_field_1_label'] ) ? $value['custom_field_1_label'] : '';
			$custom_field_1_link    = !empty( $value['custom_field_1_link'] ) ? $value['custom_field_1_link'] : '';
			$custom_field_1_target  = !empty( $value['custom_field_1_target'] ) ? $value['custom_field_1_target'] : 'false'; 

			$custom_field_2_label   = !empty( $value['custom_field_2_label'] ) ? $value['custom_field_2_label'] : '';
			$custom_field_2_link    = !empty( $value['custom_field_2_link'] ) ? $value['custom_field_2_link'] : '';
			$custom_field_2_target  = !empty( $value['custom_field_2_target'] ) ? $value['custom_field_2_target'] : 'false'; ?>

			<div class="econsult-contact-item">
                <h4 class="contact-title"><?php echo esc_html( $location ); ?></h4>
                <p class="contact-text"><?php echo esc_html( $sub_location ); ?></p>
                <div class="contact-info">

                	<?php 
                	if( !empty( $email ) ){ ?>
                    	<a href="mailto:<?php echo esc_html( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                    	<?php
                    }

                    if( !empty( $tel ) ){ ?>
                    	<a href="tel:<?php echo esc_html( $tel ); ?>"><?php echo esc_html( $tel ); ?></a>
                    	<?php 
                    } 

                    /**
                    * Custom Field 1
                    */

                    if( !empty( $custom_field_1_label ) ){
                    	if( !empty( $custom_field_1_link ) ){ ?>
                    		<a 
                    		href="<?php echo esc_url( $custom_field_1_link ); ?>"
                    		target="<?php echo ( $custom_field_1_target == 'true' ? '_blank' : '_self' ); ?>">
                    			<?php echo esc_html( $custom_field_1_label ); ?>
                    		</a>
                    		<?php
                    	} else {
                    		echo '<p class="contact-text">' . esc_html( $custom_field_1_label ) . '</p>';
                    	}
                    }

                    /**
                    * Custom Field 2
                    */

                    if( !empty( $custom_field_2_label ) ){
                    	if( !empty( $custom_field_2_link ) ){ ?>
                    		<a 
                    		href="<?php echo esc_url( $custom_field_2_link ); ?>"
                    		target="<?php echo ( $custom_field_2_target == 'true' ? '_blank' : '_self' ); ?>">
                    			<?php echo esc_html( $custom_field_2_label ); ?>
                    		</a>
                    		<?php
                    	} else {
                    		echo '<p class="contact-text">' . esc_html( $custom_field_2_label ) . '</p>';
                    	}
                    } ?>

                </div>
			</div>
			<?php
		}
	}
	return ob_get_clean();
}

add_action( 'bizberg_before_homepage_blog', 'econsulting_agency_contact_form_section' );
function econsulting_agency_contact_form_section(){

	$status          = bizberg_get_theme_mod( 'econsulting_agency_contact_status' );
	$location_status = bizberg_get_theme_mod( 'econsulting_agency_contact_form_locations_status' );

	if( $status != true ){
		return;
	} ?>

	<section class="econsult-contact">
		<div class="container">
			<div class="econsult-contact-main">
				<div class="section-title">
					<p><?php echo econsulting_agency_contact_form_get_subtitle(); ?></p>
					<h2><?php echo econsulting_agency_contact_form_get_title(); ?></h2>
					<span class="description"><?php echo econsulting_agency_contact_form_get_description(); ?></span>
				</div>

				<div class="econsult-contact-form">
					<?php echo econsulting_agency_get_contact_form_7(); ?>
				</div>
			</div>

			<?php 
			if( $location_status == true ){ ?>
				<div class="econsult-contact-in">
					<h2><?php echo econsulting_agency_contact_form_locations_get_title(); ?></h2>
					<div class="econsult-contact-address">
						<?php echo econsulting_agency_contact_form_location_list(); ?>
					</div>
				</div>
				<?php 
			} ?>
		</div>
	</section>

	<?php

	do_action( 'econsulting_agency_after_contact_form' );
	
}