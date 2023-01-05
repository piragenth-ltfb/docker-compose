<?php

function cdi_get_theme_data(){

	$my_theme          = wp_get_theme();
	$text_domain       = $my_theme->get( 'TextDomain' );
	$text_domain_split = explode( '-' , $text_domain ); 

	if( in_array( 'pro', $text_domain_split ) ){
		return true;
	}

	return false;

}

/***************************/
// Product search
/***************************/
add_action( 'init', 'bizberg_product_search_box_init' );
function bizberg_product_search_box_init() {
    add_shortcode( 'bizberg_product_search_box', 'bizberg_product_search_box' );
}

function bizberg_product_search_box(){
	ob_start(); ?>             
	<div id='bizberg-product-search-box'>
		<form 
		action='<?php echo esc_url( home_url( '/' ) ); ?>' 
		id='search-form' 
		class="woocommerce-product-search" 
		method='get'>
   			<input 
   			id='search-text' 
   			autocomplete="off" 
   			name='s' 
   			placeholder='<?php echo esc_attr( get_theme_mod( 'header_woo_search_placeholder' , esc_attr_x( 'Search for Product', 'placeholder', 'bizberg' ))); ?>' 
   			class="form-control" 
   			value='<?php echo esc_attr( get_search_query() ); ?>' 
   			type='text' />
   			<div class="vert-brd" ></div>
		   	<?php 
			if ( class_exists( 'WooCommerce' ) ):
				$args = array(
				   'taxonomy'          => 'product_cat',
				   'name'              => 'product_cat',
				   'value_field'       => 'slug',
				   'class'             => 'something',
				   'show_option_all'   => esc_attr( get_theme_mod( 'header_woo_search_dropdown_text' , __( 'All Category','bizberg' ) ) ),
				   'selected'          => get_query_var( 'product_cat' )
				);
				wp_dropdown_categories( $args );
			endif; ?>
            <button id='search-button' value="<?php echo esc_attr_x( 'Submit','submit button', 'bizberg' ); ?>" type='submit'>                     
                <?php esc_html_e( 'Search','bizberg' ); ?>
            </button>
            <input type="hidden" name="post_type" value="product" />
        </form>
 	</div>                    
	<?php 
	return ob_get_clean();
}

/***************************/
// Account Menu
/***************************/
add_action( 'init', 'bizberg_woocommerce_menu_header' );
function bizberg_woocommerce_menu_header() {
    add_shortcode( 'bizberg_woocommerce_account_menu', 'bizberg_woocommerce_account_menu' );
}

function bizberg_woocommerce_account_menu(){

	ob_start(); ?>

	<div class="header_dropdown_wrapper">

		<?php 
		if( !is_user_logged_in() ){
			$parent_menu_not_logged_in = get_theme_mod( 'woocommerce_account_parent_menu_not_logged_in' );
			$parent_menu_not_logged_in_icon = get_theme_mod( 'woocommerce_account_parent_menu_not_logged_in_icon' , 'far fa-user' );
			if( empty( $parent_menu_not_logged_in ) ){  ?>
	    		<a 
	    		class="bizberg_woocommerce_header_menu" 
	    		href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>">
	    		<i class="<?php echo esc_attr( $parent_menu_not_logged_in_icon ); ?>"></i>
	    		<?php esc_html_e( 'Login / Register', 'bizberg' ); ?></a>
	    		<?php 
	    	} else { ?>
	    		<a 
	    		class="bizberg_woocommerce_header_menu" 
	    		href="<?php echo esc_url( get_permalink( $parent_menu_not_logged_in ) ); ?>">
	    		<i class="<?php echo esc_attr( $parent_menu_not_logged_in_icon ); ?>"></i>
	    		<?php echo esc_html( get_the_title( $parent_menu_not_logged_in ) ); ?></a>
    			<?php 
    		}
    	} else { 
    		$parent_menu_logged_in = get_theme_mod( 'woocommerce_account_parent_menu_logged_in' ); 
    		$parent_menu_logged_in_icon = get_theme_mod( 'woocommerce_account_parent_menu_logged_in_icon' , 'far fa-user' );
    		if( empty( $parent_menu_logged_in ) ){ ?>
    			<a 
	    		class="bizberg_woocommerce_header_menu" 
	    		href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>">
	    		<i class="<?php echo esc_attr( $parent_menu_logged_in_icon ); ?>"></i>
	    		<?php esc_html_e( 'My Account', 'bizberg' ); ?>	    			
	    		</a>	    		
    			<?php 
    		} else { ?>
    			<a 
	    		class="bizberg_woocommerce_header_menu" 
	    		href="<?php echo esc_url( get_permalink( $parent_menu_logged_in ) ); ?>">
	    		<i class="<?php echo esc_attr( $parent_menu_logged_in_icon ); ?>"></i>
	    		<?php echo esc_html( get_the_title( $parent_menu_logged_in ) ); ?>	    			
	    		</a>	    		
    			<?php 
    		} 

    		$dropdown_menu_pages = get_theme_mod( 'woocommerce_account_drop_down_menu_header' );
    		$dropdown_menu_pages = is_array( $dropdown_menu_pages ) ? $dropdown_menu_pages : json_decode( urldecode( $dropdown_menu_pages ), true );

    		$logout_header_menu = get_theme_mod( 'woocommerce_disable_logout_header_menu' );
    		$hide_logout_icon_header_menu = get_theme_mod( 'woocommerce_hide_logout_icon_header_menu' ); ?>
	    	<ul>
	      		<?php cdi_get_woocommerce_header_dropdown( $dropdown_menu_pages );
	      		if( empty( $logout_header_menu ) ){ ?>
	      			<li>
	      				<a href="<?php echo wp_logout_url(); ?>">
	      					<?php echo ( empty( $hide_logout_icon_header_menu ) ? '<i class="fas fa-sign-out-alt"></i>' : '' ); ?>
	      					<?php esc_html_e( 'Logout', 'bizberg' ); ?>		
	      				</a>
	      			</li>
	      			<?php
	      		} ?>
	    	</ul>
	    	<?php 

	    } ?>

  	</div>

	<?php

	return ob_get_clean();

}

function cdi_get_woocommerce_header_dropdown( $dropdown_menu_pages ){

	if( !empty( $dropdown_menu_pages ) ){

		foreach ( $dropdown_menu_pages as $key => $value ) {
			
			if( empty( $value['page_id'] ) && empty( $value['url'] ) ){
				return;
			}

			echo '<li><a href="' . esc_url( !empty( $value['url'] ) ? $value['url'] : get_permalink( $value['page_id'] ) ) . '">';

			if( !empty( $value['icon'] ) ){
				echo '<i class="' . esc_attr( $value['icon'] ) . '"></i>';
			}

			if( !empty( $value['label'] ) ){
				echo esc_html( $value['label'] );
			} else {
				echo esc_html( get_the_title( $value['page_id'] ) );
			}
			
			echo '</a></li>';

		}

	}

}

/***************************/
// Wishlist Icon
/***************************/

add_action( 'init', 'bizberg_woocommerce_wishlist' );
function bizberg_woocommerce_wishlist() {

	if( !function_exists('YITH_WCWL') ){
		return;
	}

    add_shortcode( 'bizberg_woocommerce_wishlist', 'bizberg_woocommerce_wishlist_shortcode' );
}

function bizberg_woocommerce_wishlist_shortcode(){

	ob_start();

	$icon = cdi_get_theme_data() ? 'fal fa-heart' : 'far fa-heart'; ?>

	<div class="bizberg_wishlist_wrapper">
		<a href="<?php echo esc_url( YITH_WCWL()->get_wishlist_url() ); ?>" data-toggle="tooltip" title="<?php esc_attr_e( 'My Wishlist' , 'bizberg' ) ?>">
			<i class="<?php echo esc_attr( $icon ); ?>"></i>
			<span class="wishlist_count"><?php echo esc_html( yith_wcwl_count_all_products() ); ?></span>
		</a>
	</div>

	<?php

	return ob_get_clean();

}

/***************************/
// Compare Icon
/***************************/

add_action( 'init', 'bizberg_woocommerce_compare' );
function bizberg_woocommerce_compare() {

	if( !function_exists('yith_woocompare_constructor') ){
		return;
	}

    add_shortcode( 'bizberg_woocommerce_compare', 'bizberg_woocommerce_compare_shortcode' );
}

function bizberg_woocommerce_compare_shortcode(){

	ob_start();

	global $yith_woocompare;
	
	$count = !empty( $yith_woocompare->obj->products_list ) && is_array( $yith_woocompare->obj->products_list ) ? count( $yith_woocompare->obj->products_list ) : '0';

	$icon = cdi_get_theme_data() ? 'fal fa-sync' : 'fas fa-sync-alt';  ?>

	<div class="bizberg_compare_wrapper yith-woocompare-widget">
		<a 
		href="<?php echo esc_url( site_url() ); ?>/?action=yith-woocompare-view-table&iframe=yes" 
		class="compare" 
		onclick="return false" 
		data-toggle="tooltip" 
		title="<?php esc_attr_e( 'Compare Products' , 'bizberg' ); ?>">
			<i class="<?php echo esc_attr( $icon ); ?>"></i>
			<span class="compare_count"><?php echo absint( $count ); ?></span>
		</a>
	</div>

	<?php

	return ob_get_clean();

}

/***************************/
// Header Cart Items
/***************************/

add_action( 'init', 'bizberg_woocommerce_header_mini_cart' );
function bizberg_woocommerce_header_mini_cart() {
    add_shortcode( 'bizberg_woocommerce_mini_cart', 'bizberg_woocommerce_mini_cart' );
}

function bizberg_woocommerce_mini_cart(){

	ob_start();
	global $woocommerce;
	$icon = cdi_get_theme_data() ? 'fal fa-shopping-cart' : 'fas fa-shopping-cart'; ?>

	<div class="bizberg_header_mini_cart_wrapper">
		<a class="cart-contents" 
		href="<?php echo esc_url( wc_get_cart_url() ); ?>" 
		data-toggle="tooltip"  
		title="<?php esc_attr_e( 'Shopping Cart','bizberg' ); ?>">
			<span class="bizberg_header_cart_icon">
				<i class="<?php echo esc_attr( $icon ); ?>"></i>
				<span class="cart_content_count">
					<?php echo absint( WC()->cart->get_cart_contents_count() ); ?>
				</span>
			</span>
			<span class="bizberg_mini_cart_content_total">
				<?php echo wp_kses_post( WC()->cart->get_cart_total() ); ?>	
			</span>		
		</a>
		<div class="bizberg_mini_cart_dropdown">
			<?php 
			woocommerce_mini_cart(); 
			?>
		</div>	
	</div>
	
	<?php
	return ob_get_clean();

}

add_filter( 'woocommerce_add_to_cart_fragments', 'bizberg_woocommerce_header_add_to_cart_count' );
function bizberg_woocommerce_header_add_to_cart_count( $fragments ) {

	ob_start();

	$icon = cdi_get_theme_data() ? 'fal fa-shopping-cart' : 'fas fa-shopping-cart';	?>

	<a class="cart-contents" 
		href="<?php echo esc_url( wc_get_cart_url() ); ?>" 
		data-toggle="tooltip"  
		title="<?php esc_attr_e( 'Shopping Cart','bizberg' ); ?>">
		<span class="bizberg_header_cart_icon">
			<i class="<?php echo esc_attr( $icon ); ?>"></i>
			<span class="cart_content_count">
				<?php echo absint( WC()->cart->get_cart_contents_count() ); ?>
			</span>
		</span>
		<span class="bizberg_mini_cart_content_total">
			<?php echo wp_kses_post( WC()->cart->get_cart_total() ); ?>	
		</span>		
	</a>

	<?php
	
	$fragments['a.cart-contents'] = ob_get_clean();
	
	return $fragments;

}

add_filter( 'woocommerce_add_to_cart_fragments', 'bizberg_woocommerce_header_add_to_cart_content' );
function bizberg_woocommerce_header_add_to_cart_content( $fragments ) {

	ob_start();
	?>

	<div class="bizberg_mini_cart_dropdown">
		<?php 
		woocommerce_mini_cart(); 
		?>
	</div>	

	<?php
	
	$fragments['div.bizberg_mini_cart_dropdown'] = ob_get_clean();
	
	return $fragments;

}