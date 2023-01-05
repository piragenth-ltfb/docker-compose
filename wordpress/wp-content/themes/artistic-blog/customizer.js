jQuery(document).ready(function(){

	wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function( placement ) {

		var slider_arr = [
		    'artistic_blog_slider_category',
		    'slider_autoplay_delay',
		    'ab_slider_speed',
		    'ab_slider_meta'
		];

		if( jQuery.inArray( placement.partial.id , slider_arr ) !== -1 ){

			var interleaveOffset = 0.5;
		    var swiperOptions = {
		        loop: true,
		        speed: parseInt( wp.customize( 'ab_slider_speed' ).get() ),
		        grabCursor: true,
		        mousewheelControl: true,
		        keyboardControl: true,
		        navigation: {
		            nextEl: ".swiper-button-next",
		            prevEl: ".swiper-button-prev"
		        },
		        pagination: {
		            el: '.swiper-pagination',
		            clickable: true,
		          },
		        autoplay: {
		            delay: parseInt( wp.customize( 'slider_autoplay_delay' ).get() ),
		        },
		    };

		    var swiper = new Swiper(".swiper-container-blog", swiperOptions);

		}

	})

	wp.customize(
	    'slider_background_gradient_1', function( value ) {
	        value.bind(
	            function( to ) {
	            	var css = '.blog-banner .container::after { background:linear-gradient( 30deg, ' + to + ', ' + wp.customize( 'slider_background_gradient_2' ).get() + ') }';
	            	jQuery('.ab_customizer_inline').remove();
					jQuery('.artistic_blog_banner').append( '<div class="ab_customizer_inline"><style>' + css + '</style><div>' );
	            }
	        );
	    }
	);

	wp.customize(
	    'slider_background_gradient_2', function( value ) {
	        value.bind(
	            function( to ) {
	            	var css = '.blog-banner .container::after { background:linear-gradient( 30deg, ' + wp.customize( 'slider_background_gradient_1' ).get() + ', ' + to + ') }';
	            	jQuery('.ab_customizer_inline').remove();
					jQuery('.artistic_blog_banner').append( '<div class="ab_customizer_inline"><style>' + css + '</style><div>' );
	            }
	        );
	    }
	);

	/**
	* Start Title Border on Hover
	*/

	wp.customize(
	    'title_border_gradient_1', function( value ) {
	        value.bind(
	            function( to ) {
	            	var css = '.blog-banner .swiper-content h2 a { background-image:linear-gradient(to right, ' + to + ',' + wp.customize( 'title_border_gradient_2' ).get() + ') }';
	            	jQuery('.ab_customizer_inline_2').remove();
					jQuery('.artistic_blog_banner').append( '<div class="ab_customizer_inline_2"><style>' + css + '</style><div>' );
	            }
	        );
	    }
	);

	wp.customize(
	    'title_border_gradient_2', function( value ) {
	        value.bind(
	            function( to ) {
	            	var css = '.blog-banner .swiper-content h2 a { background-image:linear-gradient(to right, ' + wp.customize( 'title_border_gradient_1' ).get() + ',' + to + ') }';
	            	jQuery('.ab_customizer_inline_2').remove();
					jQuery('.artistic_blog_banner').append( '<div class="ab_customizer_inline_2"><style>' + css + '</style><div>' );
	            }
	        );
	    }
	);

	/**
	* End Title Border on Hover
	*/

});