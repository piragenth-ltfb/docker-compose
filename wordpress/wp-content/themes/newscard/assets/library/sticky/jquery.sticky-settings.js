// sticky
jQuery(window).load(function(){
	var site_content_row = jQuery('.site-content-row')[0],
		wp_adminBar = jQuery('#wpadminbar').outerHeight(),
		doc_width = jQuery(window).outerWidth(),
		top_spacing = 20 + wp_adminBar;

	if ( site_content_row ) {
		var page_height  = jQuery('body.theme-body').outerHeight(),
			page_before_height = jQuery('body.theme-body').offset().top,
			total_page_height = page_height + page_before_height,
			before_content_height = jQuery('.site-content-row').offset().top,
			content_height = jQuery('.site-content-row').outerHeight(),
			after_height = total_page_height - before_content_height - content_height;
	}

	if (doc_width >= 992 ) {
		if (wp_adminBar){
			jQuery(".sticky-sidebar").sticky({ topSpacing: top_spacing, bottomSpacing: after_height, });
		} else {
			jQuery(".sticky-sidebar").sticky({ topSpacing: 20, bottomSpacing: after_height, });
		}
	}
});