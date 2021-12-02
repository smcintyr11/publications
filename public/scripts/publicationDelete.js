// Global Variable
var baseurl = 'http://s-dev-drupal/publicaions';
//var baseUrl = 'http://localhost:8080/';

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

function showConfirm(event) {
  var response = confirm("Are you sure you wish to delete this publication and all related data?\nThis CANNOT be undone.");

  if (response == false) {
    event.preventDefault();
  }
}

$(document).ready(function(){
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

  // View comment modal loading
  $('#commentModal').on('shown.bs.modal', function (event) {
    // Variable declaration
    var button = $(event.relatedTarget);
    var id = button.data('pcid'); // Extract info from data-* attributes

    // Get link data
    $.ajax({
        url: baseurl + "/PublicationsComments/get",
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
          }
          else if(dataResult.statusCode==201) {  // Error
            $('#commentModal').modal('hide');
            displayErrorMessage("Error occurred loading comment");
          }
        }
      });
  });

  const form = document.getElementById('frmDeletePublication');
  form.addEventListener('submit', showConfirm);
});
