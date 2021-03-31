<!-- Load Table Sorter -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.bootstrap_4.min.css" integrity="sha512-2C6AmJKgt4B+bQc08/TwUeFKkq8CsBNlTaNcNgUmsDJSU1Fg+R6azDbho+ZzuxEkJnCjLZQMozSq3y97ZmgwjA==" crossorigin="anonymous" />


<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

	<!-- Error List -->
	<?php
    $errorList = \Config\Services::validation()->listErrors();
    $count = count(\Config\Services::validation()->getErrors());
    if ($count > 0) {
      echo ('<div class="alert alert-warning" role="alert">');
      echo ($errorList);
      echo ('</div>');
    }
  ?>

  <!-- Tab links -->
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a id="tbGeneralLink" class="nav-link tablink active" onclick="openTab(event, 'tbGeneral')">General</a>
    </li>
    <li class="nav-item">
      <a class="nav-link tablink" onclick="openTab(event, 'tbStatus')">Status</a>
    </li>
    <li class="nav-item">
      <a class="nav-link tablink" onclick="openTab(event, 'tbDates')">Dates</a>
    </li>
  </ul>


  <form class="form-group" action="/publications/edit" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="page" value="<?= $page ?>">

    <!-- Tab content -->
    <!-- General Tab -->
    <div id="tbGeneral" class="tabcontent" style="display: block;">
			<br />
      <div class="form-group row">
        <label for="publicationID" class="col-2 col-form-label font-weight-bold">Publication ID:</label>
        <div class="col-10">
          <input type="text" readonly class="form-control-plaintext" name="publicationID" id="publicationID" value="<?= $publication['PublicationID'] ?>"><br />
        </div>
      </div>
      <div class="form-group row">
        <label for="primaryTitle" class="col-2 col-form-label font-weight-bold">Primary Title:</label>
        <div class="col-10">
          <input class="form-control" type="input" name="primaryTitle" value="<?= set_value('primaryTitle', $publication['PrimaryTitle']) ?>"/><br />
        </div>
      </div>
      <div class="form-group row">
        <label for="secondaryTitle" class="col-2 col-form-label font-weight-bold">Secondary Title:</label>
        <div class="col-10">
          <input class="form-control" type="input" name="secondaryTitle" value="<?= set_value('secondaryTitle', $publication['SecondaryTitle']) ?>"/><br />
        </div>
      </div>
    </div>

    <!-- Status Tab -->
    <div id="tbStatus" class="tabcontent" style="display: none;">
			<br />
      <div class="form-group row">
        <label for="statusID" class="col-2 col-form-label font-weight-bold">Status:</label>
        <div class="col-10">
          <select class="form-control" id="statusID" name="statusID" value="<?= set_value('statusID', $publication['StatusID']) ?>">
            <option value=''>-- Select a status --</option>
            <?php
              foreach ($statuses as $status) {
                echo ('<option value="' . $status->StatusID . '"');
                if ($status->StatusID == set_value('statusID', $publication['StatusID'])) {
                  echo (' selected="selected"');
                }
                echo('>' . $status->Status . '</option>');
              }
            ?>
          </select><br />
        </div>
      </div>
      <div class="form-group row">
        <label for="assignedTo" class="col-2 col-form-label font-weight-bold">Assigned To:</label>
        <div class="col-8">
          <input class="form-control" type="input" id="assignedTo" name="assignedTo" value="<?= set_value('assignedTo', $publication['StatusPerson']) ?>"  placeholder="-- Enter a name --"/>
					<br />
        </div>
				<div class="col-2">
					<button type="button" id="btnAddPerson" class="btn btn-success">Add Person</button>
				</div>
        <input type="hidden" id="statusPersonID" name="statusPersonID" value="<?= set_value('statusPersonID', $publication['StatusPersonID']) ?>">
      </div>
      <div class="form-group row">
        <label for="statusEstimatedCompletionDate" class="col-2 col-form-label font-weight-bold">Estimated Completion:</label>
        <div class="col-10">
          <input class="form-control" type="input" name="statusEstimatedCompletionDate" value="<?= set_value('statusEstimatedCompletionDate', $publication['StatusEstimatedCompletionDate']) ?>"/><br />
        </div>
      </div>
      <div class="form-group row">
        <h3>Status Log</h3>
			</div>
			<div class="form-group row">

        <div class="table-responsive">
          <table class="table table-striped table-bordered">
             <thead class="thead-light">
               <th scope="col">ID</th>
               <th scope="col">Date Modified</th>
               <th scope="col">Status</th>
               <th scope="col">Assigned To</th>
               <th scope="col">Estimated Completion Date</th>
               <th scope="col">Completion Date</th>
             </thead>
             <tbody>
               <?php if (! empty($statusLog) && is_array($statusLog)) : ?>
                 <?php foreach ($statusLog as $sr): ?>
                   <tr>
                     <td><?= $sr->PublicationsStatusesID; ?></td>
                     <td><?= $sr->DateModified; ?></td>
                     <td><?= $sr->Status; ?></td>
                     <td><?= $sr->DisplayName; ?></td>
                     <td><?= $sr->EstimatedCompletionDate; ?></td>
                     <td><?= $sr->CompletionDate; ?></td>
                   </tr>
                 <?php endforeach; ?>
               <?php endif ?>
             </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Dates Tab -->
		<br />
    <div id="tbDates" class="tabcontent" style="display: none;">
      <div class="form-group row">
        <label for="publicationDate" class="col-2 col-form-label font-weight-bold">Publication Date:</label>
        <div class="col-10">
          <input class="form-control" type="input" name="publicationDate" value="<?= set_value('publicationDate', $publication['PublicationDate']) ?>"/><br />
        </div>
      </div>
      <div class="form-group row">
        <label for="webPublicationDate" class="col-2 col-form-label font-weight-bold">Web Publication Date:</label>
        <div class="col-10">
          <input class="form-control" type="input" name="webPublicationDate" value="<?= set_value('webPublicationDate', $publication['WebPublicationDate']) ?>"/><br />
        </div>
      </div>
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Publication</button>
    <a class="btn btn-info m-1" href="/publications/index/<?= $page ?>">Back to Publications</a>
  </form>
</div>

<script>
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
};
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

  // Select the General Tab
  $("#tbGeneralLink").className += " active";

	// Add sorting to the various internal tables
	$(function() {
	  $("table").tablesorter({
	    theme : "bootstrap",
	  });
	});
});
</script>
