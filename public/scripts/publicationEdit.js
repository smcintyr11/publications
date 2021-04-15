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

function displaySuccessMessage(message) {
  $("#alertSuccess").html('<div class="alert alert-success alert-dismissible fade show" role="alert">'+message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
}

function displayErrorMessage(message) {
  $("#alertFail").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
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

$(document).ready(function(){
  // Assigned to autocomplete
  $("#assignedTo").autocomplete({
    minLength: 1,
    source: function(request, response) {
      $.ajax({
        url: location.protocol + "//" + location.host + "/people/searchPerson",
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
      $("#statusPersonID").val(ui.item.id);
    }
  }).keyup(function(){
    if (event.which != 13) {
      $("#statusPersonID").val("");
    }
  });

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

	// Fiscal Year autocomplete
	$("#fiscalYear").autocomplete({
		minLength: 1,
		source: function(request, response) {
			$.ajax({
				url: location.protocol + "//" + location.host + "/fiscalYears/searchFiscalYear",
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
			$("#fiscalYearID").val(ui.item.id);
		}
	}).keyup(function(){
		if (event.which != 13) {
			$("#fiscalYearID").val("");
		}
	});

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

  // Author autocomplete
	$("#newAuthor").autocomplete({
		minLength: 1,
		source: function(request, response) {
			$.ajax({
				url: location.protocol + "//" + location.host + "/people/searchPerson",
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
			$("#authorID").val(ui.item.id);
		}
	}).keyup(function(){
		if (event.which != 13) {
			$("#authorID").val("");
		}
	});

  // Reviewer autocomplete
  $("#newReviewer").autocomplete({
    minLength: 1,
    source: function(request, response) {
      $.ajax({
        url: location.protocol + "//" + location.host + "/people/searchPerson",
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
      $("#reviewerID").val(ui.item.id);
    }
  }).keyup(function(){
    if (event.which != 13) {
      $("#reviewerID").val("");
    }
  });

  // Add author function
  $("#btnAddAuthor").click(function(){
    var authorName = $("#newAuthor").val();
    var authorID = $('#authorID').val();
    var publicationID = $("#publicationID").val();
    if (authorID == "") {
      alert("You must select an author first");
      return;
    }

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

            // Clear the author boxes
            $("#newAuthor").val("");
            $("#authorID").val("");
					}
					else if(dataResult.statusCode==201) {  // Error
            displayErrorMessage("Error occurred adding author");
					}
				}
			});
    });

    // Add reviewer function
    $("#btnAddReviewer").click(function(){
      var reviewerName = $("#newReviewer").val();
      var reviewerID = $('#reviewerID').val();
      var publicationID = $("#publicationID").val();
      if (reviewerID == "") {
        alert("You must select a reviewer first");
        return;
      }

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
              // Get the new publicationsReviewersID
              var PublicationsReviewersID = dataResult.publicationsReviewersID;

              // Success
              var html = '<tr id="rl_'+PublicationsReviewersID+'"><td>'+PublicationsReviewersID+'</td><td>'+reviewerName+'</td><td id="rl_lr_'+PublicationsReviewersID+'">No</td><td><button class="btn btn-info m-1 fas fa-toggle-on" id="btnER_'+PublicationsReviewersID+'" type="button" title="Toggle Lead Reviewer Flag" onClick="toggleReviewer(\'rl_lr_'+PublicationsReviewersID+'\', '+PublicationsReviewersID+', 0)" /><button class="btn btn-danger m-1 fas fa-trash-alt" id="btnDR_'+PublicationsReviewersID+'" type="button" title="Delete Reviewer" onclick="removeReviewer(\'rl_'+PublicationsReviewersID+'\', '+PublicationsReviewersID+')" /></td></tr>';
              $("#tblReviewers").append(html);
              displaySuccessMessage("Reviewer Added");

              // Clear the author boxes
              $("#newReviewer").val("");
              $("#reviewerID").val("");
            }
            else if(dataResult.statusCode==201) {  // Error
              displayErrorMessage("Error occurred adding reviewer");
            }
          }
        });
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
});
