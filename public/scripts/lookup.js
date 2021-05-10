function lookup(lookupField, lookupID, url) {
  $(lookupField).autocomplete({
    minLength: 1,
    source: function(request, response) {
      $.ajax({
        url: location.protocol + "//" + location.host + url,
        datatype: "json",
        data: {
          term: request.term,
        },
        success: function(data) {
          data = $.parseJSON(data);
          response(data);
        },
      });
    },
    select: function(event, ui) {
      $(lookupID).val(ui.item.id);
    }
  }).keyup(function(){
    if (event.which != 13) {
      $(lookupID).val("");
    }
  });
}
