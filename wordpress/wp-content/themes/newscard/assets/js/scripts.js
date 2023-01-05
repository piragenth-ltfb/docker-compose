jQuery( function() {

	// toggle to display search form
	jQuery('.search-toggle').on( "click", function(event) {
		var that = jQuery('.search-toggle'),
			wrapper = jQuery('.search-block');

		that.toggleClass('active');
		wrapper.toggleClass('off').toggleClass('on');
		jQuery('.search-block.on').fadeIn();
		jQuery('.search-block.off').fadeOut();
		if ( that.is('.active') || jQuery('.search-toggle')[0] === event.target ) {
			wrapper.find('.s').focus();
		}

		// search form escape while pressing ESC key
		jQuery(document).on('keydown', function (e) {
			if ( e.keyCode === 27 && that.hasClass('active') ) {
				that.removeClass('active');
				wrapper.addClass('off').removeClass('on');
				jQuery('.search-block.off').fadeOut();
			}
		});
	});

	// toggle to display top right menu
	var nav = jQuery('.info-bar .infobar-links');
	if ( !nav ) {
		return;
	}
	button = nav.find('.info-bar-links-menu-toggle');
	if ( !button ) {
		return;
	}
	jQuery('.infobar-links-menu-toggle').on( 'click', function() {
		nav.toggleClass('toggled-link-on');
	} );

	// hide #back-top first
	jQuery(".back-to-top").hide();

	// fade in #back-top
	jQuery(function () {
		jQuery(window).scroll(function () {
			if (jQuery(this).scrollTop() > 1000) {
				jQuery('.back-to-top').fadeIn();
			} else {
				jQuery('.back-to-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		jQuery('.back-to-top a').on( "click", function () {
			jQuery('body,html,header').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});

	// toggle to hide and show the dropdown Submenu in smaller screen
	jQuery('.main-navigation').find( '.sub-menu, .children' ).before( '<span class="dropdown-toggle"><span class="dropdown-icon"></span></span>' );
	jQuery('.main-navigation').find( '.sub-menu, .children' ).parent().addClass('dropdown-parent');
	jQuery('.main-navigation').find( '.dropdown-toggle').on('click',function(e){
		e.preventDefault();
		jQuery(this).next('.sub-menu, .children').toggleClass('dropdown-active');
		jQuery(this).toggleClass( 'toggle-on' );
	});

	jQuery(window).on("load resize",function(e){
		var screenwidth = jQuery(window).outerWidth();
		if ( screenwidth >= 992 ) {
			jQuery('.main-navigation').find( '.sub-menu.dropdown-active, .children.dropdown-active' ).removeClass('dropdown-active');
			jQuery('.main-navigation').find( '.dropdown-toggle.toggle-on').removeClass('toggle-on');
		}
	});

});
