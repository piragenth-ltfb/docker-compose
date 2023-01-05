<?php

function cdi_upgrade_to_pro_msg(){
	return '<div style="background-color: #afe4ff;padding: 15px; display: block;border-radius: 5px;font-weight: 700;">More options available in the PRO Version. <a target="blank" href="' . bizberg_get_pro_link() . '">Upgrade to PRO Version</a></div>';
}

add_action( 'init', 'cdi_upgrade_pro_messages' );
function cdi_upgrade_pro_messages(){

	$theme = wp_get_theme();
	if( !( $theme->get( 'Name' ) == 'Bizberg' || $theme->get( 'Template' ) == 'bizberg' ) ){
		return;
	}

	if( empty( bizberg_get_pro_link() ) ){
		return;
	}

	$data = array(
		'title_tagline',
		'top-header',
		'transparent_header',
		'primary_header',
		'header',
		'mobile_menu',
		'progress_bar',
		'footer_settings',
		'front_page_hero',
		'inner_pages_hero',
		'breadcrumb',
		'base-typography',
		'headings',
		'container',
		'theme_colors',
		'homepage',
		'detail_page',
		'404_settings'
	);

	foreach ( $data as $key => $value ) {
		Kirki::add_field( 'bizberg', array(
		    'type'        => 'custom',
		    'settings'    => 'bizberg_pro_message_' . $key,
		    'section'     => $value,
		    'default'     => cdi_upgrade_to_pro_msg(),
		    'priority'    => 100
		) );
	}	

}