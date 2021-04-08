<?php
  use App\Libraries\MyFormGeneration;
 ?>

<!-- Load Table Sorter -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.bootstrap_4.min.css" integrity="sha512-2C6AmJKgt4B+bQc08/TwUeFKkq8CsBNlTaNcNgUmsDJSU1Fg+R6azDbho+ZzuxEkJnCjLZQMozSq3y97ZmgwjA==" crossorigin="anonymous" />


<!-- Main Form -->
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
      <?= MyFormGeneration::generateIDTextBox("publicationID",
        $publication['PublicationID'], "Publication ID"); ?>

      <?= MyFormGeneration::generateTextBox("primaryTitle",
        set_value('primaryTitle', $publication['PrimaryTitle']),
        "-- Enter the primary title --", "Primary Title"); ?>

      <?= MyFormGeneration::generateTextBox("secondaryTitle",
        set_value('secondaryTitle', $publication['SecondaryTitle']),
        "-- Enter the secondary title --", "Secondary Title"); ?>

      <?= MyFormGeneration::generateLookupTextBox("reportType",
        set_value('reportType', $publication['ReportType']),
        "-- Enter a report type --", "Report Type",
        MyFormGeneration::generateNewButtonURL(current_url(), "reportTypes"), "reportTypeID",
        set_value('reportTypeID', $publication['ReportTypeID'])); ?>

      <?= MyFormGeneration::generateTextBox("reportNumber",
        set_value('reportNumber', $publication['ReportNumber']),
        "-- Enter the report number --", "Report Number"); ?>

      <?= MyFormGeneration::generateTextBox("agreementNumber",
        set_value('agreementNumber', $publication['AgreementNumber']),
        "-- Enter the agreement number --", "Agreement Number"); ?>

      <?= MyFormGeneration::generateLookupTextBox("fiscalYear",
        set_value('fiscalYear', $publication['FiscalYear']),
        "-- Enter a fiscal year (e.g. 2020 / 2021) --", "Fiscal Year",
        MyFormGeneration::generateNewButtonURL(current_url(), "fiscalYears"), "fiscalYearID",
        set_value('fiscalYearID', $publication['FiscalYearID'])); ?>

      <?= MyFormGeneration::generateLookupTextBox("organization",
        set_value('organization', $publication['Organization']),
        "-- Enter an organization --", "Organization",
        MyFormGeneration::generateNewButtonURL(current_url(), "organizations"), "organizationID",
        set_value('organizationID', $publication['OrganizationID'])); ?>

      <?php
        $optionList = '';
        foreach ($costCentres as $costCentre) {
          $optionList = $optionList . '<option value=' . $costCentre->CostCentreID . '"';
          if ($costCentre->CostCentreID == set_value('costCentreID', $publication['CostCentreID'])) {
            $optionList = $optionList . ' selected="selected"';
          }
          $optionList = $optionList . '>' . $costCentre->CostCentre . '</option>';
        }

        echo (MyFormGeneration::generateSelect("costCentreID",
          set_value('costCentreID', $publication['CostCentreID']),
          "-- Select a cost centre --", "Cost Centre", $optionList));
      ?>

      <?= MyFormGeneration::generateTextBox("projectCode",
        set_value('projectCode', $publication['ProjectCode']),
        "-- Enter the project code --", "Project Code"); ?>

      <?= MyFormGeneration::generateTextBox("ipdNumber",
        set_value('ipdNumber', $publication['IPDNumber']),
        "-- Enter the ipd number --", "IPD Number"); ?>

      <?= MyFormGeneration::generateTextBox("crossReferenceNumber",
        set_value('crossReferenceNumber', $publication['CrossReferenceNumber']),
        "-- Enter the cross reference number --", "Cross Reference Number"); ?>
    </div>

    <!-- Status Tab -->
    <div id="tbStatus" class="tabcontent" style="display: none;">
			<br />

      <?php
        $optionList = '';
        foreach ($statuses as $status) {
          $optionList = $optionList . '<option value=' . $status->StatusID . '"';
          if ($status->StatusID == set_value('statusID', $publication['StatusID'])) {
            $optionList = $optionList . ' selected="selected"';
          }
          $optionList = $optionList . '>' . $status->Status . '</option>';
        }

        echo (MyFormGeneration::generateSelect("statusID",
          set_value('statusID', $publication['StatusID']),
          "-- Select a status --", "Status", $optionList));
      ?>

      <?= MyFormGeneration::generateLookupTextBox("assignedTo",
        set_value('assignedTo', $publication['StatusPerson']),
        "-- Enter a person --", "Assigned To",
        MyFormGeneration::generateNewButtonURL(current_url(), "people"), "statusPersonID",
        set_value('statusPersonID', $publication['StatusPersonID'])); ?>

      <?= MyFormGeneration::generateTextBox("statusEstimatedCompletionDate",
          set_value('statusEstimatedCompletionDate', $publication['StatusEstimatedCompletionDate']),
          "-- Enter the estimated completion date (e.g. 2021-01-29) --", "Estimated Completion"); ?>

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


<script>
$(document).ready(function() {

});
</script>
