$(document).ready(function(){
  // Report Type autocomplete
  $("#reportType").autocomplete({
    minLength: 1,
    source: function(request, response) {
      $.ajax({
        url: location.protocol + "//" + location.host + "/reportTypes/searchReportType",
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
      $("#reportTypeID").val(ui.item.id);
    }
  }).keyup(function(){
    if (event.which != 13) {
      $("#reportTypeID").val("");
    }
  });
});
