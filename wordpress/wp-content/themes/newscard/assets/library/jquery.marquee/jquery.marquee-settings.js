// marquee
var marquee_ltr = jQuery('.marquee.marquee-ltr')[0],
	marquee_rtl = jQuery('.marquee.marquee-rtl')[0],
	marquee_dir;

	if (marquee_ltr) {
		marquee_dir = 'left';
	}
	if (marquee_rtl) {
		marquee_dir = 'right';
	}

jQuery('.marquee').marquee({
	//speed in milliseconds of the marquee
	speed: 50,
	//gap in pixels between the tickers
	gap: 0,
	//time in milliseconds before the marquee will start animating
	delayBeforeStart: 0,
	//'left' or 'right'
	direction: marquee_dir,
	//true or false - should the marquee be duplicated to show an effect of continues flow
	duplicated: true,
	pauseOnHover: true,
	startVisible: true
});