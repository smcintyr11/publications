// Checks to see if the Organization textbox is filled in, and OrganizationID
// is empty.  If so, that means what was typed in the Organization field, does
// not exist in the database, and we need to offer the user a chance to create
// a new organization
function checkOrganization(event) {
  // Get the fields
  var organization = $("#organization").val();
  var organizationID = $("#organizationID").val();

  // Check if organization is not empty
  if ((organization != "") && (organizationID == "")) {
    // Stop saving temporarily
    event.preventDefault();

    // Check if the organization has an organizationID (e.g. the user didn't
    // select the organization from the drop down)
    $.ajax({
        url: "/organizations/searchOrganizationID",
        type: "POST",
        data: {
          organization: organization,
        },
        cache: false,
        success: function(dataResult){
          var dataResult = JSON.parse(dataResult);
          if(dataResult.statusCode==200) {  // Success
            // Get the new OrganizationID
            var OrganizationID = dataResult.organizationID;

            // Update the organization ID field
            $("#organizationID").val(OrganizationID);

            // Submit again
            $("#btnSubmit").click();
          } else {
            // Stop saving temporarily
            event.preventDefault();

            // Trigger the new organization dialog
            $("#newOrganization").val(organization);
            btnNewOrganization.click();
          }
        }
      });
  }
}

// Function to add an organization to the database
function addOrganization() {
  $.ajax({
      url: "/organizations/add",
      type: "POST",
      data: {
        organization: $("#organization").val(),
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {  // Success
          // Get the new OrganizationID
          var OrganizationID = dataResult.organizationID;

          // Update the organization ID field
          $("#organizationID").val(OrganizationID);

          // Click the save button again
          $("#btnSubmit").click();
        }
        else if(dataResult.statusCode==201) {  // Error
          alert("Error adding organization.  Try adding it manually first, and then try adding the person again.");
          $("#btnCloseModal").click();
        } else if (dataResult.statusCode==202) {  // Duplicate organization
          // Get the new OrganizationID
          var OrganizationID = dataResult.organizationID;

          // Update the organization ID field
          $("#organizationID").val(OrganizationID);

          // Click the save button again
          $("#btnSubmit").click();
        }
      }
    });
}

$(document).ready(function(){
  // Organization autocomplete
  $("#organization").autocomplete({
    minLength: 1,
    source: function(request, response) {
      $.ajax({
        url: location.protocol + "//" + location.host + "/organizations/searchOrganization",
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
      $("#organizationID").val(ui.item.id);
    }
  }).keyup(function(){
    if (event.which != 13) {
      $("#organizationID").val("");
    }
  });

  // Function to automatically populate the display name based on first
  // and last name
  function generateDisplayName() {
    $("#displayName").val($("#lastName").val() + ", " + $("#firstName").val());
  };

  // Update the display name if the first name changes
  $("#firstName").change(function(){
    generateDisplayName();
  });

  // Update the display name if the last name changes
  $("#lastName").change(function(){
    generateDisplayName();
  });

  // Intercept form submition
  const form = document.getElementById('frmPerson');
  form.addEventListener('submit', checkOrganization);
});
