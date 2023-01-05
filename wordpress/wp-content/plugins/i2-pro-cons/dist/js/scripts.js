(function($) {
	$(document).ready(function() {
		$('.i2-pros-cons-color-picker').wpColorPicker();
		var pattren = /_icon$/;
		$(document).on('focusin', '.i2-pros-cons-icons input', function(event) {
			if (pattren.test($(this).data('id'))) {
				bindIcons(this);
			}
		});
		$('input.i2-pros-cons-icons').each(function() {
			bindIcons(this);
		});
		//  $('input.i2-pros-cons-icons').on('iconpickerSelected', function(event){
		//     // console.log(event.iconpickerValue);
		//    // return false;
		//   });
		function bindIcons(obj) {
			// $(obj).on('iconpickerSelected', function(event){
			//       console.log(obj.value);
			//       var ev = new Event('input', { onChange : true});
			//       obj.dispatchEvent(ev);
			//   });
			$(obj).iconpicker({
				hideOnSelect: true,
				showFooter: false,
				templates: {
					popover:
						'<div class="iconpicker-popover popover"><div class="arrow"></div>' +
						'<div class="popover-title"></div><div class="popover-content"></div></div>',
					footer: '<div class="popover-footer"></div>',
					buttons:
						'<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">Cancel</button>' +
						' <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">Accept</button>',
					search: null,
					iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
					iconpickerItem: '<a role="button" href="#" class="iconpicker-item"><i></i></a>'
				},
				icons: [
					{
						title: 'icon icon-check-1',
						searchTerms: [ 'web' ]
					},
					{
						title: 'icon icon-check-2',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-check-3',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-check-4',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-check-5',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-square-1',
						searchTerms: [ 'web' ]
					},
					{
						title: 'icon icon-square-2',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-square-3',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-ban-1',
						searchTerms: [ 'web' ]
					},
					{
						title: 'icon icon-ban-2',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-ban-3',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-ban-4',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-ban-5',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-thumbs-up',
						searchTerms: [ 'web' ]
					},
					{
						title: 'icon icon-thumbs-down',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-thumbs-o-up',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-thumbs-o-down',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-thumbs-s-up',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-thumbs-s-down',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-cancle',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-cancle-2',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-cancle-3',
						searchTerms: [ 'style' ]
					},

					{
						title: 'icon icon-link-3',
						searchTerms: [ 'web' ]
					},
					{
						title: 'icon icon-cart-7',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-cart-1',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-cart-2',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-cart-3',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-cart-4',
						searchTerms: [ 'web' ]
					},
					{
						title: 'icon icon-cart-5',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-star-2',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-star-4',
						searchTerms: [ 'web' ]
					},
					{
						title: 'icon icon-star-3',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-star-1',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-cercle-1',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-ban-7',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-alert',
						searchTerms: [ 'web' ]
					},
					{
						title: 'icon icon-plus-5',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-minus-3',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-check-6',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-hand-o-right',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-hand-o-left',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-hand-paper-o',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-hand-peace-o',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-hand-scissors-o',
						searchTerms: [ 'style' ]
					},

					{
						title: 'icon icon-heart-5',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-heart-4',
						searchTerms: [ 'web' ]
					},
					{
						title: 'icon icon-heart-break-1',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-shopping-cart-1',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-unlock',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-unlock-alt',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-trophy',
						searchTerms: [ 'web' ]
					},
					{
						title: 'icon icon-issue-opened',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-issue-closed',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-gift',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-heart-1',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-heart-3',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-happy',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-ban-9',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-minus-thin',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-minus-thick',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-plus-thin',
						searchTerms: [ 'style' ]
					},
					{
						title: 'icon icon-plus-thick',
						searchTerms: [ 'style' ]
					}
				]
			});
		}
	});
})(jQuery);
