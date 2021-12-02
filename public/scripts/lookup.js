// Global Variable
var baseUrl = 'http://s-dev-drupal/publications';
//var baseUrl = 'http://localhost:8080/';

function lookup(lookupField, lookupID, url) {
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
    }
  }).keyup(function(){
    if (event.which != 13) {
      $(lookupID).val("");
    }
  });
}
