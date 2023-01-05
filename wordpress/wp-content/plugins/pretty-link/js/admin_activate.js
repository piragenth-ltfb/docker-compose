(function($) {
  $(document).ready(function() {
    $('#plp_edge_updates').click( function(e) {
      e.preventDefault();
      var wpnonce = $(this).attr('data-nonce');

      $('#plp_edge_updates-wrap .prli_loader').show();
      $(this).prop('disabled',true);

      var data = {
        action: 'plp_edge_updates',
        edge: $(this).is(':checked'),
        wpnonce: wpnonce
      };

      var bigthis = this;

      $.post(ajaxurl, data, function(obj) {
        $('#plp_edge_updates-wrap .prli_loader').hide();
        $(bigthis).prop('disabled',false);

        if('error' in obj)
          alert(obj.error);
        else {
          $(bigthis).prop('checked',(obj.state=='true'));
        }
      }, 'json');
    });
  });
})(jQuery);
