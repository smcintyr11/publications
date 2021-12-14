// Global Variable
var baseUrl = 'http://s-dev-drupal/publications';
//var baseUrl = 'http://localhost:8080/';

// Checks to see if the ReportType textbox is filled in, and ReportTypeID
// is empty.  If so, that means what was typed in the ReportType field, does
// not exist in the database, and we need to offer the user a chance to create
// a new ReportType
function checkReportType(event) {
  // Get the fields
  var reportType = $("#reportTypeN").val();
  var reportTypeID = $("#reportTypeNID").val();

  // Check if reportType is not empty
  if ((reportType != "") && (reportTypeID == "")) {
    // Stop saving temporarily
    event.preventDefault();

    // Check if the reportType has an reportTypeID (e.g. the user didn't
    // select the reportType from the drop down)
    $.ajax({
        url: baseUrl + "/reportTypes/searchReportTypeID",
        type: "POST",
        data: {
          reportType: reportType,
        },
        cache: false,
        success: function(dataResult){
          var dataResult = JSON.parse(dataResult);
          if(dataResult.statusCode==200) {  // Success
            // Get the new OrganizationID
            var ReportTypeID = dataResult.reportTypeID;

            // Update the organization ID field
            $("#reportTypeNID").val(ReportTypeID);

            // Submit again
            $("#btnSubmit").click();
          } else {
            // Stop saving temporarily
            event.preventDefault();

            // Check to see if the user has permissions to add report types
            if ($("#AdvancedUser").val() == "1") {
              // Trigger the new report type dialog
              $("#newReportType").val(reportType);
              btnNewReportType.click();
            } else {
              alert("You must enter a report type that already exists in the system.\n\rIf you are unsure what report types are available, go to Lookup Tables -> Report Types.");
            }
          }
        }
      });
  }
}

// Function to add an organization to the database
function addReportType() {
  // Make sure report type and abbreviation are filled in
  if (($("#newReportType").val() == "") || ($("#newAbbreviation").val() == "")) {
    alert("You must enter both a Report Type and an Abbreviation.");
    return;
  }

  // Check to make sure the field is not too long
  if ($("#newReportType").val().length > 64) {
    alert("The report type can be at most 64 characters.");
    return;
  }

  if ($("#newAbbreviation").val().length > 16) {
    alert ("The abbreviation can be at most 16 characters.");
    return;
  }

  // Add the report type
  $.ajax({
      url: baseUrl + "/reportTypes/add",
      type: "POST",
      data: {
        reportType: $("#newReportType").val(),
        abbreviation: $("#newAbbreviation").val(),
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {  // Success
          // Get the new ReportTypeID
          var ReportTypeID = dataResult.reportTypeID;

          // Update the reportTypeID field
          $("#reportTypeNID").val(ReportTypeID);

          // Click the save button again
          $("#btnSubmit").click();
        }
        else if(dataResult.statusCode==201) {  // Error
          alert("Error adding report type.  Try adding it manually first, and then try adding the publication again.");
          $("#btnCloseModal").click();
        } else if (dataResult.statusCode==202) {  // Duplicate organization
          // Get the new ReportTypeID
          var ReportTypeID = dataResult.reportTypeID;

          // Update the ReportTypeID field
          $("#reportTypeNID").val(ReportTypeID);

          // Click the save button again
          $("#btnSubmit").click();
        }
      }
    });
}

$(document).ready(function(){
  // Report Type autocomplete
  lookup("#reportTypeN", "#reportTypeNID", "/reportTypes/searchReportType");

  // Intercept form submition
  const form = document.getElementById('frmNewPublication');
  form.addEventListener('submit', checkReportType);
});
