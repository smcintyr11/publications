// Global Variable
var baseUrl = 'http://s-dev-drupal/publications';
//var baseUrl = 'http://localhost:8080/';

function lookup(lookupField, lookupID, url, callback = null) {
  $(lookupField).autocomplete({
    minLength: 1,
    source: function(request, response) {
      $.ajax({
        url: baseUrl + url,
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
      if (callback != null) {
        callback();
      }
    }
  }).keyup(function(){
    if (event.which != 13) {
      $(lookupID).val("");
    }
  });
}
