function openTab(evt, tabName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}

function closeAlerts() {
  $(".close").click();
  window.clearTimeout();
}

function displaySuccessMessage(message) {
  $("#alertSuccess").html('<div class="alert alert-success alert-dismissible fade show" role="alert">'+message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
  window.setTimeout(closeAlerts, 3000);
}

function displayErrorMessage(message) {
  $("#alertFail").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
  window.setTimeout(closeAlerts, 3000);
}

function removeAuthor(rowID, paID) {
  $.ajax({
      url: "/publicationsAuthors/remove",
      type: "POST",
      data: {
        publicationsAuthorsID: paID,
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {
          // Success
          $("#" + rowID).remove();
          displaySuccessMessage("Author removed.");
        }
        else if(dataResult.statusCode==201) {  // Error
          displayErrorMessage("Error occurred removing author");
        }
      }
    });
}

function toggleAuthor(cellID, paID, currentState, btnID) {
  var newState = 0;
  var newText = "No";
  if (currentState == 0) {
    newState = 1;
    newText = "Yes";
  }
  $.ajax({
      url: "/publicationsAuthors/update",
      type: "POST",
      data: {
        publicationsAuthorsID: paID,
        primaryAuthor: newState,
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {
          // Success
          $("#" + cellID).html(newText);
          $("#" + btnID).html('<button class="btn btn-info m-1 fas fa-toggle-on" id="btnEA_'+paID+'" type="button" title="Toggle Primary Author Flag" onClick="toggleAuthor(\'al_pa_'+paID+'\', '+paID+', '+newState+', \'al_btn_'+paID+'\') " /><button class="btn btn-danger m-1 fas fa-trash-alt" id="btnDA_'+paID+'" type="button" title="Delete Author" onclick="removeAuthor(\'al_'+paID+'\', '+paID+')" />');
          displaySuccessMessage("Author updated.");
        }
        else if(dataResult.statusCode==201) {  // Error
          displayErrorMessage("Error updating author");
        }
      }
    });
}

function removeReviewer(rowID, prID) {
  $.ajax({
      url: "/publicationsReviewers/remove",
      type: "POST",
      data: {
        publicationsReviewersID: prID,
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {
          // Success
          $("#" + rowID).remove();
          displaySuccessMessage("Reviewer removed.");
        }
        else if(dataResult.statusCode==201) {  // Error
          displayErrorMessage("Error occurred removing reviewer");
        }
      }
    });
}

function toggleReviewer(cellID, prID, currentState, btnID) {
  var newState = 0;
  var newText = "No";
  if (currentState == 0) {
    newState = 1;
    newText = "Yes";
  }
  $.ajax({
      url: "/publicationsReviewers/update",
      type: "POST",
      data: {
        publicationsReviewersID: prID,
        leadReviewer: newState,
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {
          // Success
          $("#" + cellID).html(newText);
          $("#" + btnID).html('<button class="btn btn-info m-1 fas fa-toggle-on" id="btnER_'+prID+'" type="button" title="Toggle Lead Reviewer Flag" onClick="toggleReviewer(\'rl_lr_'+prID+'\', '+prID+', '+newState+', \'rl_btn_'+prID+'\')" /><button class="btn btn-danger m-1 fas fa-trash-alt" id="btnDR_'+prID+'" type="button" title="Delete Reviewer" onclick="removeReviewer(\'rl_'+prID+'\', '+prID+')" />');
          displaySuccessMessage("Reviewer updated.");
        }
        else if(dataResult.statusCode==201) {  // Error
          displayErrorMessage("Error updating author");
        }
      }
    });
}

function removeKeyword(rowID, pkID) {
  $.ajax({
      url: "/publicationsKeywords/remove",
      type: "POST",
      data: {
        publicationsKeywordsID: pkID,
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {
          // Success
          $("#" + rowID).remove();
          displaySuccessMessage("Keyword removed.");
        }
        else if(dataResult.statusCode==201) {  // Error
          displayErrorMessage("Error occurred removing keyword");
        }
      }
    });
}

function editLink() {
  // Get data from the from
  var plID = $("#editPublicationsLinksID").val();
  var newLTID = $("#editLinkTypeID").val();
  var newLT = $("#editLinkTypeID option:selected").text();
  var newLink = $("#editLink").val();

  // Update the link
  $.ajax({
      url: "/publicationsLinks/update",
      type: "POST",
      data: {
        publicationsLinksID: plID,
        linkTypeID: newLTID,
        link: newLink
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {  // Success
          // Update the table cells
          $("#ll_lt_" + plID).html(newLT);
          $("#ll_l_" + plID).html(newLink);
          displaySuccessMessage("Link updated.");
          $('#linkModal').modal('hide');
        }
        else {  // Error
          displayErrorMessage("Error updating link");
          $('#linkModal').modal('hide');
        }
      }
    });

}

function removeLink(rowID, plID) {
  $.ajax({
      url: "/publicationsLinks/remove",
      type: "POST",
      data: {
        publicationsLinksID: plID,
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {
          // Success
          $("#" + rowID).remove();
          displaySuccessMessage("Link removed.");
        }
        else if(dataResult.statusCode==201) {  // Error
          displayErrorMessage("Error occurred removing link");
        }
      }
    });
}

function removeComment(rowID, pcID) {
  $.ajax({
      url: "/publicationsComments/remove",
      type: "POST",
      data: {
        publicationsCommentsID: pcID,
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {
          // Success
          $("#" + rowID).remove();
          displaySuccessMessage("Comment removed.");
        }
        else if(dataResult.statusCode==201) {  // Error
          displayErrorMessage("Error occurred removing comment");
        }
      }
    });
}

/* Name: checkLookups
 *
 * Purpose: Function to check each lookup field for new lookup values
 *
 * Parameters:
 *  event - The event parameters
 *
 * Returns:
 *  None
 */
function checkLookups(event) {
  // Check the report type
  if (checkReportType(event)) { return; }

  // Check the fiscal year
  if (checkFiscalYear(event)) { return; }

  // Check the organization
  if (checkOrganization(event)) { return; }

  // Check the client/publisher
  if (checkClient(event)) { return; }

  // Check the journal
  if (checkJournal(event)) { return; }

  // Check the status person
  // if (checkAssignedTo(event)) { return; }
}

/* Name: checkReportType
 *
 * Purpose: Checks to see if the ReportType textbox is filled in, and ReportTypeID
 * is empty.  If so, that means what was typed in the ReportType field, does
 * not exist in the database, and we need to offer the user a chance to create
 * a new ReportType
 *
 * Parameters:
 *  event - The event parameters
 *
 * Returns:
 *  None
 */
function checkReportType(event) {
  // Get the fields
  var reportType = $("#reportType").val();
  var reportTypeID = $("#reportTypeID").val();

  // Check if reportType is not empty
  if ((reportType != "") && (reportTypeID == "")) {
    // Stop saving temporarily
    event.preventDefault();

    // Check if the reportType has an reportTypeID (e.g. the user didn't
    // select the reportType from the drop down)
    $.ajax({
        url: "/reportTypes/searchReportTypeID",
        type: "POST",
        data: {
          reportType: reportType,
        },
        cache: false,
        success: function(dataResult){
          var dataResult = JSON.parse(dataResult);
          if(dataResult.statusCode==200) {  // Success
            // Get the new reportTypeID
            var ReportTypeID = dataResult.reportTypeID;

            // Update the reportTypeID field
            $("#reportTypeID").val(ReportTypeID);

            // Submit again
            $("#btnSubmit").click();

          } else {
            // Stop saving temporarily
            event.preventDefault();

            // Trigger the new report type dialog
            $("#newReportType").val(reportType);
            btnNewReportType.click();
          }
        }
      });
    return true;
  }
  return false;
}

/* Name: addReportType
 *
 * Purpose: Function to add a report type to the database
 *
 * Parameters:
 *  None
 *
 * Returns:
 *  None
 */
function addReportType() {
 // Make sure report type and abbreviation are filled in
 if (($("#newReportType").val() == "") || ($("#newAbbreviation").val() == "")) {
   alert("You must enter both a Report Type and an Abbreviation.");
   return;
 }

 // Add the report type
 $.ajax({
     url: "/reportTypes/add",
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
         $("#reportTypeID").val(ReportTypeID);

         // Close the dialog
         $("#btnCloseRTModal").click();

         // Click the save button again
         $("#btnSubmit").click();
       }
       else if(dataResult.statusCode==201) {  // Error
         alert("Error adding report type.  Try adding it manually first, and then try saving the publication again.");
         $("#btnCloseRTModal").click();
       } else if (dataResult.statusCode==202) {  // Duplicate organization
         // Get the new ReportTypeID
         var ReportTypeID = dataResult.reportTypeID;

         // Update the ReportTypeID field
         $("#reportTypeID").val(ReportTypeID);

         // Click the save button again
         $("#btnSubmit").click();
       }
     }
   });
}

/* Name: checkFiscalYear
 *
 * Purpose: Checks to see if the FiscalYear textbox is filled in, and FiscalYearID
 * is empty.  If so, that means what was typed in the FiscalYear field, does
 * not exist in the database, and we need to offer the user a chance to create
 * a new FiscalYear
 *
 * Parameters:
 *  event - The event parameters
 *
 * Returns:
 *  None
 */
function checkFiscalYear(event) {
  // Get the fields
  var fiscalYear = $("#fiscalYear").val();
  var fiscalYearID = $("#fiscalYearID").val();

  // Check if fiscalYear is not empty
  if ((fiscalYear != "") && (fiscalYearID == "")) {
    // Stop saving temporarily
    event.preventDefault();

    // Check if the fiscalYear has a fiscalYearID (e.g. the user didn't
    // select the fiscalYear from the drop down)
    $.ajax({
        url: "/fiscalYears/searchFiscalYearID",
        type: "POST",
        data: {
          fiscalYear: fiscalYear,
        },
        cache: false,
        success: function(dataResult){
          var dataResult = JSON.parse(dataResult);
          if(dataResult.statusCode==200) {  // Success
            // Get the new fiscalYearID
            var FiscalYearID = dataResult.fiscalYearID;

            // Update the fiscalYearID field
            $("#fiscalYearID").val(FiscalYearID);

            // Submit again
            $("#btnSubmit").click();
          } else {
            // Stop saving temporarily
            event.preventDefault();

            // Check to see if the fiscal year is in the correct format before
            // trying to add it to the database
            if (checkFiscalYearFormat()) {
              // Trigger the new report type dialog
              $("#newFiscalYear").val(fiscalYear);
              btnNewFiscalYear.click();
            } else {
              // Tell the user that fiscal year is not even in the correct format
              alert('Fiscal year must be in the format "#### / ####"\nFor example "2021 / 2022".');
            }
          }
        }
      });
    return true;
  }
  return false;
}

/* Name: checkFiscalYearFormat
 *
 * Purpose: Checks to see if the supplied fiscal year is in the correct format
 *  "#### / ####"
 *
 * Parameters:
 *  None
 *
 * Returns:
 *  true - If the fiscal year is in the correct format
 *  false - If the fiscal year is in the incorrect format
 */
function checkFiscalYearFormat() {
  // Get the fiscal year field
  var fy = $("#fiscalYear").val();

  // Creat the regex and test it
  let re = /\d{4} \/ \d{4}/;

  return re.test(fy);
}

/* Name: addFiscalYear
 *
 * Purpose: Function to add a fiscal year to the database
 *
 * Parameters:
 *  None
 *
 * Returns:
 *  None
 */
function addFiscalYear() {
 // Add the fiscal year
 $.ajax({
     url: "/fiscalYears/add",
     type: "POST",
     data: {
       fiscalYear: $("#newFiscalYear").val(),
     },
     cache: false,
     success: function(dataResult){
       var dataResult = JSON.parse(dataResult);
       if(dataResult.statusCode==200) {  // Success
         // Get the new FiscalYearID
         var FiscalYearID = dataResult.fiscalYearID;

         // Update the fiscalYearID field
         $("#fiscalYearID").val(FiscalYearID);

         // Close the dialog
         $("#btnCloseFYModal").click();

         // Click the save button again
         $("#btnSubmit").click();
       }
       else if(dataResult.statusCode==201) {  // Error
         alert("Error adding fiscal year.  Try adding it manually first, and then try saving the publication again.");
         $("#btnCloseFYModal").click();
       } else if (dataResult.statusCode==202) {  // Duplicate organization
         // Get the new FiscalYearID
         var FiscalYearID = dataResult.fiscalYearID;

         // Update the FiscalYearID field
         $("#fiscalYearID").val(FiscalYearID);

         // Click the save button again
         $("#btnSubmit").click();
       }
     }
   });
}

/* Name: checkOrganization
 *
 * Purpose: Checks to see if the Organization textbox is filled in, and OrganizationID
 * is empty.  If so, that means what was typed in the Organization field, does
 * not exist in the database, and we need to offer the user a chance to create
 * a new Organization
 *
 * Parameters:
 *  event - The event parameters
 *
 * Returns:
 *  None
 */
function checkOrganization(event) {
  // Get the fields
  var organization = $("#organization").val();
  var organizationID = $("#organizationID").val();

  // Check if organization is not empty
  if ((organization != "") && (organizationID == "")) {
    // Stop saving temporarily
    event.preventDefault();

    // Check if the organization has a organizationID (e.g. the user didn't
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
            // Get the new organizationID
            var OrganizationID = dataResult.organizationID;

            // Update the organizationID field
            $("#organizationID").val(OrganizationID);

            // Submit again
            $("#btnSubmit").click();
          } else {
            // Stop saving temporarily
            event.preventDefault();

            // Trigger the new report type dialog
            $("#newOrganization").val(organization);
            btnNewOrganization.click();
          }
        }
      });
    return true;
  }
  return false;
}

/* Name: addOrganization
 *
 * Purpose: Function to add an organization to the database
 *
 * Parameters:
 *  None
 *
 * Returns:
 *  None
 */
function addOrganization() {
 // Add the organization
 $.ajax({
     url: "/organizations/add",
     type: "POST",
     data: {
       organization: $("#newOrganization").val(),
     },
     cache: false,
     success: function(dataResult){
       var dataResult = JSON.parse(dataResult);
       if(dataResult.statusCode==200) {  // Success
         // Get the new FiscalYearID
         var OrganizationID = dataResult.organizationID;

         // Update the organizationID field
         $("#organizationID").val(OrganizationID);

         // Close the dialog
         $("#btnCloseOrgModal").click();

         // Click the save button again
         $("#btnSubmit").click();
       }
       else if(dataResult.statusCode==201) {  // Error
         alert("Error adding organization.  Try adding it manually first, and then try saving the publication again.");
         $("#btnCloseOrgModal").click();
       } else if (dataResult.statusCode==202) {  // Duplicate organization
         // Get the new FiscalYearID
         var OrganizationID = dataResult.organizationID;

         // Update the organizationID field
         $("#organizationID").val(OrganizationID);

         // Click the save button again
         $("#btnSubmit").click();
       }
     }
   });
}

/* Name: checkClient
 *
 * Purpose: Checks to see if the Client textbox is filled in, and ClientID
 * is empty.  If so, that means what was typed in the Client field, does
 * not exist in the database, and we need to offer the user a chance to create
 * a new Client
 *
 * Parameters:
 *  event - The event parameters
 *
 * Returns:
 *  None
 */
function checkClient(event) {
  // Get the fields
  var client = $("#client").val();
  var clientID = $("#clientID").val();

  // Check if client is not empty
  if ((client != "") && (clientID == "")) {
    // Stop saving temporarily
    event.preventDefault();

    // Check if the client has an clientID (e.g. the user didn't
    // select the client from the drop down)
    $.ajax({
        url: "/clients/searchClientID",
        type: "POST",
        data: {
          client: client,
        },
        cache: false,
        success: function(dataResult){
          var dataResult = JSON.parse(dataResult);
          if(dataResult.statusCode==200) {  // Success
            // Get the new clientID
            var ClientID = dataResult.clientID;

            // Update the clientID field
            $("#clientID").val(ClientID);

            // Submit again
            $("#btnSubmit").click();

          } else {
            // Stop saving temporarily
            event.preventDefault();

            // Trigger the new client dialog
            $("#newClient").val(client);
            btnNewClient.click();
          }
        }
      });
    return true;
  }
  return false;
}

/* Name: addClient
 *
 * Purpose: Function to add a client to the database
 *
 * Parameters:
 *  None
 *
 * Returns:
 *  None
 */
function addClient() {
  // Add the client
  $.ajax({
      url: "/clients/add",
      type: "POST",
      data: {
        client: $("#newClient").val(),
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {  // Success
          // Get the new clientID
          var ClientID = dataResult.clientID;

          // Update the clientID field
          $("#clientID").val(ClientID);

          // Close the dialog
          $("#btnCloseClientModal").click();

          // Click the save button again
          $("#btnSubmit").click();
        }
        else if(dataResult.statusCode==201) {  // Error
          alert("Error adding client.  Try adding it manually first, and then try saving the publication again.");
          $("#btnCloseClientModal").click();
        } else if (dataResult.statusCode==202) {  // Duplicate client
          // Get the new clientID
          var ClientID = dataResult.clientID;

          // Update the clientID field
          $("#clientID").val(ClientID);

          // Click the save button again
          $("#btnSubmit").click();
        }
      }
    });
}

/* Name: checkJournal
 *
 * Purpose: Checks to see if the Journal textbox is filled in, and JournalID
 * is empty.  If so, that means what was typed in the Journal field, does
 * not exist in the database, and we need to offer the user a chance to create
 * a new Journal
 *
 * Parameters:
 *  event - The event parameters
 *
 * Returns:
 *  None
 */
function checkJournal(event) {
  // Get the fields
  var journal = $("#journal").val();
  var journalID = $("#journalID").val();

  // Check if journal is not empty
  if ((journal != "") && (journalID == "")) {
    // Stop saving temporarily
    event.preventDefault();

    // Check if the journal has an journalID (e.g. the user didn't
    // select the journal from the drop down)
    $.ajax({
        url: "/journals/searchJournalID",
        type: "POST",
        data: {
          journal: journal,
        },
        cache: false,
        success: function(dataResult){
          var dataResult = JSON.parse(dataResult);
          if(dataResult.statusCode==200) {  // Success
            // Get the new journalID
            var JournalID = dataResult.journalID;

            // Update the journalID field
            $("#journalID").val(JournalID);

            // Submit again
            $("#btnSubmit").click();

          } else {
            // Stop saving temporarily
            event.preventDefault();

            // Trigger the new journal dialog
            $("#newJournal").val(journal);
            btnNewJournal.click();
          }
        }
      });
    return true;
  }
  return false;
}

/* Name: addJournal
 *
 * Purpose: Function to add a journal to the database
 *
 * Parameters:
 *  None
 *
 * Returns:
 *  None
 */
function addJournal() {
  // Add the journal
  $.ajax({
      url: "/journals/add",
      type: "POST",
      data: {
        journal: $("#newJournal").val(),
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {  // Success
          // Get the new journalID
          var JournalID = dataResult.journalID;

          // Update the journalID field
          $("#journalID").val(JournalID);

          // Close the dialog
          $("#btnCloseJournalModal").click();

          // Click the save button again
          $("#btnSubmit").click();
        }
        else if(dataResult.statusCode==201) {  // Error
          alert("Error adding journal.  Try adding it manually first, and then try saving the publication again.");
          $("#btnCloseJournalModal").click();
        } else if (dataResult.statusCode==202) {  // Duplicate journal
          // Get the new journalID
          var JournalID = dataResult.journalID;

          // Update the journalID field
          $("#journalID").val(JournalID);

          // Click the save button again
          $("#btnSubmit").click();
        }
      }
    });
}

/* Name: checkKeyword
 *
 * Purpose: Checks to see if the Keyword textbox is filled in, and KeywordID
 * is empty.  If so, that means what was typed in the Keyword field, does
 * not exist in the database, and we need to offer the user a chance to create
 * a new Keyword
 *
 * Parameters:
 *  keyword - The keyword to search for
 *  message - The message to populate the new keyword modal save question with
 *  click - The value to change the onclick attribute to for the save button
 *          in the new keyword Modal
 *  callback - The callback function to call after the keyword has been added
 *
 * Returns:
 *  None
 */
function checkKeyword(keyword) {

  // Get the fields
  var publicationID = $("#publicationID").val();

  // Check if the person exists
  $.ajax({
      url: "/keywords/searchExactKeyword",
      type: "POST",
      data: {
        keyword: keyword,
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {  // Success
          AddPublicationKeyword(dataResult.keywordID, publicationID);

          // Clear the keyword boxes
          $("#newKeyword").val("");
          $("#keywordID").val("");
        } else {  // No exact match
          // Fill in and launch the new keyword form
          $("#newKeywordE").val(keyword);
          $("#newKeywordF").val(keyword);
          $("#btnNewKeyword").click();
        }
      }
    });
}

/* Name: AddPublicationKeyword
 *
 * Purpose: Function to add a keyword to the publication
 *
 * Parameters:
 *  keywordID - The KeywordID to add
 *  publicationID - The PublicationID to add
 *
 * Returns:
 *  None
 */
function AddPublicationKeyword(keywordID, publicationID) {
  // Add the keyword to the publicationn
  $.ajax({
      url: "/publicationsKeywords/add",
      type: "POST",
      data: {
        publicationID: publicationID,
        keywordID: keywordID,
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {
          // Get the new publicationsAuthorsID
          var PublicationsKeywordsID = dataResult.publicationsKeywordsID;
          var KeywordE = dataResult.keywordEnglish;
          var KeywordF = dataResult.keywordFrench;

          // Success
          var html = '<tr id="kl_'+PublicationsKeywordsID+'"><td>'+PublicationsKeywordsID+'</td><td>'+KeywordE+'</td><td>'+KeywordF+'</td><td><button class="btn btn-danger m-1 fas fa-trash-alt" type="button" title="Delete Keyword" onclick="removeKeyword(\'kl_'+PublicationsKeywordsID+'\', '+PublicationsKeywordsID+')" /></td></tr>';
          $("#tblKeywords").append(html);
          displaySuccessMessage("Keyword Added");
          return;
        }
        else if(dataResult.statusCode==201) {  // Error
          displayErrorMessage("Error occurred adding keyword");
          return;
        } else if(dataResult.statusCode==202) {  // Row already exists
          var KeywordE = dataResult.keywordEnglish;
          var KeywordF = dataResult.keywordFrench;
          displayErrorMessage("\""+KeywordE+" | "+KeywordF+"\" already exists for this publication.");
          return;
        }
      }
    });
}

/* Name: checkAssignedTo
 *
 * Purpose: Checks to see if the Assigned To textbox is filled in, and statusPersonID
 * is empty.  If so, that means what was typed in the Assigned To field, does
 * not exist in the database, and we need to offer the user a chance to create
 * a new Person
 *
 * Parameters:
 *  None
 *
 * Returns:
 *  Array:
 *    boolean (T/F) - Person exits
 */
function checkAssignedTo() {
  // Get the fields
  var person = $("#assignedTo").val();
  var personID = $("#statusPersonID").val();
  var publicationID = $("#publicationID").val();

  // Check if client is not empty
  if ((person != "") && (personID == "")) {
    // Check to see if the person exists, but an ID was not select
    // (e.g. didn't select from the drop down)
    return CheckUser(person);
  }
  return false;
}

/* Name: AddPublicationAuthor
 *
 * Purpose: Function to add an author to the publication
 *
 * Parameters:
 *  personID - The PersonID to add
 *  publicationID - The PublicationID to add
 *
 * Returns:
 *  None
 */
function AddPublicationAuthor(authorID, authorName, publicationID) {
  $.ajax({
      url: "/publicationsAuthors/add",
      type: "POST",
      data: {
        publicationID: publicationID,
        authorID: authorID,
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {
          // Get the new publicationsAuthorsID
          var PublicationsAuthorsID = dataResult.publicationsAuthorsID;

          // Success
          var html = '<tr id="al_'+PublicationsAuthorsID+'"><td>'+PublicationsAuthorsID+'</td><td>'+authorName+'</td><td id="al_pa_'+PublicationsAuthorsID+'">No</td><td><button class="btn btn-info m-1 fas fa-toggle-on" id="btnEA_'+PublicationsAuthorsID+'" type="button" title="Toggle Primary Author Flag" onClick="toggleAuthor(\'al_pa_'+PublicationsAuthorsID+'\', '+PublicationsAuthorsID+', 0)" /><button class="btn btn-danger m-1 fas fa-trash-alt" id="btnDA_'+PublicationsAuthorsID+'" type="button" title="Delete Author" onclick="removeAuthor(\'al_'+PublicationsAuthorsID+'\', '+PublicationsAuthorsID+')" /></td></tr>';
          $("#tblAuthors").append(html);
          displaySuccessMessage("Author Added");
        }
        else if(dataResult.statusCode==201) {  // Error
          displayErrorMessage("Error occurred adding author");
        } else if(dataResult.statusCode==202) {  // Row already exists
          displayErrorMessage("\""+$("#newAuthor").val()+"\" is already an author for this publication.");
        }

        // Clear the author boxes
        $("#newAuthor").val("");
        $("#authorID").val("");
      }
    });
}

/* Name: AddPublicationReviewer
 *
 * Purpose: Function to add a reviewer to the publication
 *
 * Parameters:
 *  personID - The PersonID to add
 *  publicationID - The PublicationID to add
 *
 * Returns:
 *  None
 */
function AddPublicationReviewer(reviewerID, reviewerName, publicationID) {
  $.ajax({
      url: "/publicationsReviewers/add",
      type: "POST",
      data: {
        publicationID: publicationID,
        reviewerID: reviewerID,
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {
          // Get the new publicationsAuthorsID
          var PublicationsReviewersID = dataResult.publicationsReviewersID;

          // Success
          var html = '<tr id="rl_'+PublicationsReviewersID+'"><td>'+PublicationsReviewersID+'</td><td>'+reviewerName+'</td><td id="rl_lr_'+PublicationsReviewersID+'">No</td><td><button class="btn btn-info m-1 fas fa-toggle-on" id="btnER_'+PublicationsReviewersID+'" type="button" title="Toggle Lead Reviewer Flag" onClick="toggleReviewer(\'rl_lr_'+PublicationsReviewersID+'\', '+PublicationsReviewersID+', 0)" /><button class="btn btn-danger m-1 fas fa-trash-alt" id="btnDR_'+PublicationsReviewersID+'" type="button" title="Delete Reviewer" onclick="removeReviewer(\'rl_'+PublicationsReviewersID+'\', '+PublicationsReviewersID+')" /></td></tr>';
          $("#tblReviewers").append(html);
          displaySuccessMessage("Reviewer Added");
        }
        else if(dataResult.statusCode==201) {  // Error
          displayErrorMessage("Error occurred adding reviewer");
        } else if(dataResult.statusCode==202) {  // Row already exists
          displayErrorMessage("\""+$("#newReviewer").val()+"\" is already a reviewer for this publication.");
        }

        // Clear the reviewer boxes
        $("#newReviewer").val("");
        $("#reviewerID").val("");
      }
    });
}

/* Name: StatusAssignedTo
 *
 * Purpose: Function to handle success of finding a person based on name
 *
 * Parameters:
 *  personID - The personID that was found
 *  displayName - The displayname of the person that was found
 *  publicationID - Not used in this, case but consistent with other callback functions
 *
 * Returns:
 *  None
 */
function StatusAssignedTo(personID, displayName, publicationID) {
  // Update the form field
  $("#statusPersonID").val(personID);

  // Submit again
  $("#btnSubmit").click();
}

/* Name: StatusAssignedToSuccess
 *
 * Purpose: Function to handle success of adding a person from the Assigned To
 *
 * Parameters:
 *  personID - The personID that was found
 *  displayName - The displayname of the person that was found
 *  publicationID - Not used in this, case but consistent with other callback functions
 *
 * Returns:
 *  None
 */
function StatusAssignedToSuccess(personID, displayName, publicationID) {
  // Update the form fields
  $("#statusPersonID").val(personID);
  $("#assignedTo").val(displayName);

  // Close the dialog
  $("#btnClosePersonModal").click();

  // Click the save button again
  $("#btnSubmit").click();
}

/* Name: CheckPerson
 *
 * Purpose: Checks to see if the Person textbox (multiple different ones based
 *  on parameter passed in) is filled in, and PersonID is empty.  If so, that
 *  means what was typed in the Person textbox, does not exist in the database,
 *  and we need to offer the user a chance to create a new Person
 *
 * Parameters:
 *  person - The person's name to search for
 *  message - The message to populate the new person modal save question with
 *  click - The value to change the onclick attribute to for the save button
 *          in the new person Modal
 *  callback - The callback function to call after the person has been added
 *
 * Returns:
 *  None
 */
function CheckPerson(person, message, click, callback) {
  // Get the publicationID
  var publicationID = $("#publicationID").val();

  // Check if the person exists
  $.ajax({
      url: "/people/searchExactDisplayName",
      type: "POST",
      data: {
        displayName: person,
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {
          // Person found
          callback(dataResult.personID, dataResult.displayName, publicationID);
        }
        else {
          // Give the user a chance to create the person

          // Update the modal values & messaging
          $("#newDisplayName").val(person);
          $("#newPersonSaveMessage").text(message);

          // Update the onclick attribute of the save button
          $("#btnNewPersonSave").attr("onclick", click);

          // Show the modal
          $("#btnNewPerson").click();
        }
      }
    });
}

/* Name: CheckUser
 *
 * Purpose: Checks to see if the person passed in has a matching ID in the
 *  users.users table.  If it does, then update the StatusPersonID and
 *  continue, Otherwise display an error message to the user
 *
 * Parameters:
 *  person - The person's name to search for
 *
 * Returns:
 *    None
 */
function CheckUser(person) {
  // Check if the user exists
  $.ajax({
      url: "/users/searchExactDisplayName",
      type: "POST",
      data: {
        displayName: person,
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {
          // User found
          $("#statusPersonID").val(dataResult.ID);
          return;
        }
        else {
          // User not found
          alert("The user you have entered in the \"Assigned To\" field under status does not exist.\nThis most likely occured because you did not select the person's name from the drop down.\nThe field has been reset.");
          $("#statusPersonID").val("#OriginalStatusPersonID");
          $("#assignedTo").val("#originalAssignedTo");
        }
      }
    });
}

/* Name: addNewKeyword
 *
 * Purpose: Function to add a keyword to the database, and then add it to the
 *  publication
 *
 * Parameters:
 *  None
 *
 * Returns:
 *  None
 */
function addNewKeyword() {
  // Make sure everything is filled in
  keywordE = $("#newKeywordE").val();
  keywordF = $("#newKeywordF").val();

  if ((keywordE == "") || (keywordF == "")) {
    alert ("You must fill in both the English and French translation of the keyword");
    return;
  }

  // Add the keyword to the database
  $.ajax({
      url: "/keywords/add",
      type: "POST",
      data: {
        keywordE: keywordE,
        keywordF: keywordF,
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if ((dataResult.statusCode==200) || (dataResult.statusCode==202)) {
          // Get the KeywordID
          var KeywordID = dataResult.keywordID;
          var PublicationID = $("#publicationID").val();

          // Add the keyword to the publication
          AddPublicationKeyword(KeywordID, PublicationID);

          // Close the Modal
          $("#btnCloseKeywordModal").click();
          return;
        }
        else if(dataResult.statusCode==201) {  // Error
          displayErrorMessage("Error occurred creating keyword");
          return;
        }
      }
    });
}

/* Name: addJournal
 *
 * Purpose: Function to add a journal to the database
 *
 * Parameters:
 *  None
 *
 * Returns:
 *  None
 */
function addJournal() {
  // Add the journal
  $.ajax({
      url: "/journals/add",
      type: "POST",
      data: {
        journal: $("#newJournal").val(),
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {  // Success
          // Get the new journalID
          var JournalID = dataResult.journalID;

          // Update the journalID field
          $("#journalID").val(JournalID);

          // Close the dialog
          $("#btnCloseJournalModal").click();

          // Click the save button again
          $("#btnSubmit").click();
        }
        else if(dataResult.statusCode==201) {  // Error
          alert("Error adding journal.  Try adding it manually first, and then try saving the publication again.");
          $("#btnCloseJournalModal").click();
        } else if (dataResult.statusCode==202) {  // Duplicate journal
          // Get the new journalID
          var JournalID = dataResult.journalID;

          // Update the journalID field
          $("#journalID").val(JournalID);

          // Click the save button again
          $("#btnSubmit").click();
        }
      }
    });
}

/* Name: addNewPerson
 *
 * Purpose: Function to add a person to the database, and then add the person
 *  to the publication using the callback function
 *
 * Parameters:
 *  callback - The function to call after the person has been successfully added
 *             to the database
 *
 * Returns:
 *  None
 */
function addNewPerson(callback) {
  // Make sure everything is filled in
  displayName = $("#newDisplayName").val();

  if (displayName == "") {
    alert ("You must enter at least a display name for the person.");
    return;
  }

  // Add the person to the database
  $.ajax({
      url: "/people/add",
      type: "POST",
      data: {
        lastName: $("#newLastName").val(),
        firstName: $("#newFirstName").val(),
        displayName: displayName,
        organizationID: $("#newPOrganizationID").val(),
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if ((dataResult.statusCode==200) || (dataResult.statusCode==202)) {
          // Get the PersonID
          var PersonID = dataResult.personID;
          var DisplayName = dataResult.displayName;
          var PublicationID = $("#publicationID").val();

          // Callback function
          callback(PersonID, DisplayName, PublicationID);

          // Close the Modal
          $("#btnClosePersonModal").click();
          return;
        }
        else if(dataResult.statusCode==201) {  // Error
          displayErrorMessage("Error occurred creating person");
          return;
        }
      }
    });
}

$(document).ready(function(){
  // Report Type autocomplete
  lookup("#reportType", "#reportTypeID", "/reportTypes/searchReportType");

  // Assigned to autocomplete
  lookup("#assignedTo", "#statusPersonID", "/users/searchPerson");

	// Fiscal Year autocomplete
  lookup("#fiscalYear", "#fiscalYearID", "/fiscalYears/searchFiscalYear");

  // Organization autocomplete
  lookup("#organization", "#organizationID", "/organizations/searchOrganization");

  // Author autocomplete
  lookup("#newAuthor", "#authorID", "/people/searchPerson");

  // Reviewer autocomplete
  lookup("#newReviewer", "#reviewerID", "/people/searchPerson");

  // Keyword autocomplete
  lookup("#newKeyword", "#keywordID", "/keywords/searchKeyword");

  // Publisher autocomplete
  lookup("#client", "#clientID", "/clients/searchClient");

  // Journal autocomplete
  lookup("#journal", "#journalID", "/journals/searchJournal");

  // Person Modal Organization lookup
  lookup("#newPOrganization", "#newPOrganizationID", "/organizations/searchOrganization");

  // Add author function
  $("#btnAddAuthor").click(function(){
    // Get the form variables
    var authorName = $("#newAuthor").val();
    var authorID = $('#authorID').val();
    var publicationID = $("#publicationID").val();

    if ((authorID == "") && (authorName == "")) {
      // No authorn name entered
      alert("You must select an author first");
      return;
    } else if ((authorID == "") && (authorName != "")) {
      // Check to see if the person exists, but an ID was not select
      // (e.g. didn't select from the drop down)
      CheckPerson(authorName, "Do you want to add this person to the database and add them as an author?",
        "addNewPerson(AddPublicationAuthor)", AddPublicationAuthor);
    } else {
      // Author name entered, ID found in lookup

      // Add the Author to the publication
      AddPublicationAuthor(authorID, authorName, publicationID);
    }
  });

  // Add reviewer function
  $("#btnAddReviewer").click(function(){
    // Get the form variables
    var reviewerName = $("#newReviewer").val();
    var reviewerID = $('#reviewerID').val();
    var publicationID = $("#publicationID").val();

    if ((reviewerID == "") && (reviewerName == "")) {
      // No reviewer name entered
      alert("You must select a reviewer first");
      return;
    } else if ((reviewerID == "") && (reviewerName != "")) {
      // Check to see if the person exists, but an ID was not select
      // (e.g. didn't select from the drop down)
      CheckPerson(reviewerName, "Do you want to add this person to the database and add them as an reviewer?",
        "addNewPerson(AddPublicationReviewer)", AddPublicationReviewer);
    } else {
      // Reviewer name entered, ID found in lookup

      // Add the Reviewer to the publication
      AddPublicationReviewer(reviewerID, reviewerName, publicationID);
    }
  });

  // Add keyword function
  $("#btnAddKeyword").click(function(){
    // Get the form variables
    var keyword = $("#newKeyword").val();
    var keywordID = $('#keywordID').val();
    var publicationID = $("#publicationID").val();

    if ((keywordID == "") && (keyword == "")) {
      // No keyword entered
      alert("You must select a keyword first");
      return;
    } else if ((keywordID == "") && (keyword != "")) {
      // Check to see if the keyword exists, but an ID was not select
      // (e.g. didn't select from the drop downn)
      checkKeyword(keyword);
    } else {
        // Keyword entered, ID found in lookup

        // Add the keyword to the publication
        AddPublicationKeyword(keywordID, publicationID);
    }

  });

  // Add link function
  $("#btnAddLink").click(function(){
    // Get the form variables
    var publicationID = $("#publicationID").val();
    var linkTypeID = $("#newLinkTypeID").val();
    var linkType = $("#newLinkTypeID").find('option[value="'+linkTypeID+'"]').attr("selected",true).text();
    var link = $("#newLink").val();

    // Check that everything is filled in
    if (linkTypeID == "") {
      alert("You must select a link type first");
      return;
    }
    if (link == "") {
      alert("You must enter a link first");
      return;
    }

    // Do the insert
    $.ajax({
        url: "/PublicationsLinks/add",
        type: "POST",
        data: {
          publicationID: publicationID,
          linkTypeID: linkTypeID,
          link: link,
        },
        cache: false,
        success: function(dataResult){
          var dataResult = JSON.parse(dataResult);
          if(dataResult.statusCode==200) {
            // Get the new publicationsAuthorsID
            var PublicationsLinksID = dataResult.publicationsLinksID;

            // Success
            let re = /http|https|ftp/i;
            html = '<tr id="ll_'+PublicationsLinksID+'"><td>'+PublicationsLinksID+'</td><td id="ll_l_'+PublicationsLinksID+'">';
            if (re.test(link)) {
              html = html + '<a href="'+link+'" target="_blank">'+link+'</a>';
            } else {
              html = html + link;
            }
            html = html + '</td><td id="ll_lt_'+PublicationsLinksID+'">'+linkType+'</td><td><button class="btn btn-info m-1 fas fa-edit" id="btnEL_'+PublicationsLinksID+'" type="button" title="Edit Link" data-toggle="modal" data-target="#linkModal" data-id="'+PublicationsLinksID+'" /><button class="btn btn-danger m-1 fas fa-trash-alt" type="button" title="Delete Link" onclick="removeLink(\'ll_'+PublicationsLinksID+'\', '+PublicationsLinksID+')" /></td></tr>';
            $("#tblLinks").append(html);
            displaySuccessMessage("Link Added");
          }
          else {  // Error
            displayErrorMessage("Error occurred adding link");
          }

          // Clear the link boxes
          $('#newLinkTypeID').val(null).trigger('change');
          $("#newLink").val("");
        }
      });
  });

  // Add comment function
  $("#btnAddComment").click(function(){
    // Get the form variables
    var publicationID = $("#publicationID").val();
    var comment = $("#newComment").val();

    // Check that everything is filled in
    if (comment == "") {
      alert("You enter a comment first");
      return;
    }

    // Do the insert
    $.ajax({
        url: "/PublicationsComments/add",
        type: "POST",
        data: {
          publicationID: publicationID,
          comment: comment,
        },
        cache: false,
        success: function(dataResult){
          var dataResult = JSON.parse(dataResult);
          if(dataResult.statusCode==200) {
            // Get the new publicationsAuthorsID
            var PublicationsCommentsID = dataResult.publicationsCommentsID;

            // Success
            var formatter = new Intl.DateTimeFormat('en-ca', { dateStyle: 'short', timeStyle: 'medium', hour12: false });
            var html = '<tr id="cl_'+PublicationsCommentsID+'"><td>'+PublicationsCommentsID+'</td><td>'+formatter.format(Date.now())+'</td><td>'+comment+'</td><td><button class="btn btn-info m-1 fas fa-info-circle" type="button" title="View Comment" data-toggle="modal" data-target="#commentModal" data-pcid="'+PublicationsCommentsID+'" /><button class="btn btn-danger m-1 fas fa-trash-alt" type="button" title="Delete Comment" onclick="removeComment(\'cl_'+PublicationsCommentsID+'\', '+PublicationsCommentsID+')" /></td></tr>';
            $(html).prependTo('#tblComments');
            displaySuccessMessage("Comment Added");
          }
          else {  // Error
            displayErrorMessage("Error occurred adding comment");
          }

          // Clear the comment box
          $("#newComment").val("");
        }
      });
  });

  // Status change function
  $("#statusID").change(function(){
    // Clear the assignedTo
    $("#assignedTo").val("");
    $("#statusPersonID").val("");

    // Try to get the expected duration
    $.ajax({
      url: location.protocol + "//" + location.host + "/statuses/getExpectedDuration",
      type: "POST",
      data: {
        statusID: $("#statusID").val(),
      },
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {
          // Get the expected duration
          var expectedDuration = dataResult.expectedDuration;

          // If there is an expected duration populate the due date
          if (expectedDuration != null) {
            var formatter = new Intl.DateTimeFormat('en-ca', { dateStyle: 'short' });
            var nowDate = new Date();
            nowDate.setDate(nowDate.getDate() + parseInt(expectedDuration, 10));
            $("#statusDueDate").val(formatter.format(nowDate));
            $("#ipdNumber").val(formatter.format(nowDate));
          } else {
              $("#statusDueDate").val("");
          }
        }
        else if(dataResult.statusCode==201) {
          // Unknown expected duration
          $("#statusDueDate").val("");
        }
      },
    });
  });

  // Assigned to change function
  $("#assignedTo").change(function(){
    CheckUser($("#assignedTo").val());
  });

  // Select the General Tab
  $("#tbGeneralLink").className += " active";

  // Add sorting to the various internal tables
	$(function() {
	  $("table").tablesorter({
	    theme : "bootstrap",
	  });
	});

  // Turn on tooltips
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  });

  // Make the view comments text area read only
  //$('#viewPublicationsCommentsComment').attr('readonly','readonly');

  // Edit link modal loading
  $('#linkModal').on('shown.bs.modal', function (event) {
    // Variable declaration
    var button = $(event.relatedTarget);
    var id = button.data('id'); // Extract info from data-* attributes

    // Get link data
    $.ajax({
        url: "/PublicationsLinks/get",
        type: "POST",
        data: {
          publicationsLinksID: id,
        },
        cache: false,
        success: function(dataResult){
          var dataResult = JSON.parse(dataResult);
          if(dataResult.statusCode==200) {
            // Get the other data items
            var linkTypeID = dataResult.publicationLink.LinkTypeID
            var link = dataResult.publicationLink.Link
            // Populate the modal
            $("#editLinkTypeID").find('option[value="'+linkTypeID+'"]').attr("selected",true);
            $("#editLink").val(link)
          }
          else if(dataResult.statusCode==201) {  // Error
            $('#linkModal').modal('hide');
            displayErrorMessage("Error occurred adding keyword");
          }
        }
      });

    // Pre populate the form
    $("#editPublicationsLinksID").val(id);
  });

  // View comment modal loading
  $('#commentModal').on('shown.bs.modal', function (event) {
    // Variable declaration
    var button = $(event.relatedTarget);
    var id = button.data('pcid'); // Extract info from data-* attributes

    // Get link data
    $.ajax({
        url: "/PublicationsComments/get",
        type: "POST",
        data: {
          publicationsCommentsID: id,
        },
        cache: false,
        success: function(dataResult){
          var dataResult = JSON.parse(dataResult);
          if(dataResult.statusCode==200) {
            $("#viewPublicationsCommentsID").val(dataResult.publicationComment.PublicationsCommentsID);
            $("#viewPublicationsCommentsDateEntered").val(dataResult.publicationComment.DateEntered);
            $("#viewPublicationsCommentsComment").val(dataResult.publicationComment.Comment);
            created = "Create by ".concat(dataResult.publicationComment.CreatedBy, " on ", dataResult.publicationComment.Created);
            $("#viewPublicationsCreated").text(created);
            if (dataResult.publicationComment.Modified == null) {
              modified = "Not modified";
            } else {
              modified = "Modified by ".concat(dataResult.publicationComment.ModifiedBy, " on ", dataResult.publicationComment.Modified);
            }
            $("#viewPublicationsModified").text(modified);
            $("#viewPublicationsCommentVersion").html(created.concat("<br>", modified));
          }
          else if(dataResult.statusCode==201) {  // Error
            $('#commentModal').modal('hide');
            displayErrorMessage("Error occurred loading comment");
          }
        }
      });
  });

  // Intercept form submition
  const form = document.getElementById('frmEditPublication');
  form.addEventListener('submit', checkLookups);
});
