// Featured Slider
jQuery('.featured-slider .owl-carousel').owlCarousel({
	loop:true,
	margin:0,
	nav:true,
	navText: ['', ''],
	autoplay: true,
	dots: false,
	smartSpeed: 800,
	autoplayTimeout: 5500,
	responsive:{
		0:{
			items:1
		},
	}
});