<?php
  use App\Libraries\MyFormGeneration;
 ?>

<!-- Load Table Sorter -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
<script type="text/javascript" src="/scripts/publicationEdit.js"></script>
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

  <!-- Alert section -->
  <div id="alertFail"></div>
  <div id="alertSuccess"></div>


  <!-- Tab links -->
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a id="tbGeneralLink" class="nav-link tablink active" onclick="openTab(event, 'tbGeneral')">General</a>
    </li>
    <li class="nav-item">
      <a class="nav-link tablink" onclick="openTab(event, 'tbStatus')">Status</a>
    </li>
    <li class="nav-item">
      <a class="nav-link tablink" onclick="openTab(event, 'tbAuthors')">Authors</a>
    </li>
    <li class="nav-item">
      <a class="nav-link tablink" onclick="openTab(event, 'tbReviewers')">Reviewers</a>
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

      <?= MyFormGeneration::generateSelect("costCentreID",
        set_value('costCentreID', $publication['CostCentreID']),
        "-- Select a cost centre --", "Cost Centre", $costCentres); ?>

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

      <?= MyFormGeneration::generateSelect("statusID",
        set_value('statusID', $publication['StatusID']),
        "-- Select a status --", "Status", $statuses); ?>

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
          <table id="tblStatusLog" class="table table-striped table-bordered">
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
                 <?php foreach ($statusLog as $sl): ?>
                   <tr id="sl_<?= $sl->PublicationsStatusesID ?>">
                     <td><?= $sl->PublicationsStatusesID; ?></td>
                     <td><?= $sl->DateModified; ?></td>
                     <td><?= $sl->Status; ?></td>
                     <td><?= $sl->DisplayName; ?></td>
                     <td><?= $sl->EstimatedCompletionDate; ?></td>
                     <td><?= $sl->CompletionDate; ?></td>
                   </tr>
                 <?php endforeach; ?>
               <?php endif ?>
             </tbody>
          </table>
        </div>
      </div>

    </div>

    <!-- Authors Tab -->
    <br />
    <div id="tbAuthors" class="tabcontent" style="display: none;">

      <div class="form-group row">
      <h3>Authors</h3>
			</div>

      <?= MyFormGeneration::generateLookupTextBox("newAuthor",
        null, "-- Enter a person --", "Author", null, "authorID", null, "btnAddAuthor"); ?>

      <div class="form-group row">
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
             <thead class="thead-light">
               <th scope="col">ID</th>
               <th scope="col">Author</th>
               <th scope="col">Primary Author</th>
               <th scope="col">Author Flag / Delete</th>
             </thead>
             <tbody id="tblAuthors">
               <?php if (! empty($authorsList) && is_array($authorsList)) : ?>
                 <?php foreach ($authorsList as $al): ?>
                   <tr id="al_<?= $al->PublicationsAuthorsID ?>">
                     <td><?= $al->PublicationsAuthorsID; ?></td>
                     <td><?= $al->DisplayName; ?></td>
                     <td id="al_pa_<?= $al->PublicationsAuthorsID ?>"><?= $al->PrimaryAuthor == "1" ? "Yes" : "No" ?></td>
                     <td id="al_btn_<?= $al->PublicationsAuthorsID ?>"><button class="btn btn-info m-1 fas fa-toggle-on" id="btnEA_<?= $al->PublicationsAuthorsID ?>" type="button" title="Toggle Primary Author Flag" onClick="toggleAuthor('al_pa_<?= $al->PublicationsAuthorsID ?>', <?= $al->PublicationsAuthorsID ?>, <?= $al->PrimaryAuthor ?>, 'al_btn_<?= $al->PublicationsAuthorsID ?>') " />
                       <button class="btn btn-danger m-1 fas fa-trash-alt" id="btnDA_<?= $al->PublicationsAuthorsID ?>" type="button" title="Delete Author" onclick="removeAuthor('al_<?= $al->PublicationsAuthorsID ?>', <?= $al->PublicationsAuthorsID ?>)" /></td>
                   </tr>
                 <?php endforeach; ?>
               <?php endif ?>
             </tbody>
          </table>
        </div>
      </div>

    </div>

    <!-- Reviewers Tab -->
    <br />
    <div id="tbReviewers" class="tabcontent" style="display: none;">

      <div class="form-group row">
      <h3>Reviewers</h3>
			</div>

      <?= MyFormGeneration::generateLookupTextBox("newReviewer",
        null, "-- Enter a person --", "Reviewer", null, "reviewerID", null, "btnAddReviewer"); ?>

      <div class="form-group row">
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
             <thead class="thead-light">
               <th scope="col">ID</th>
               <th scope="col">Reviewer</th>
               <th scope="col">Lead Reviewer</th>
               <th scope="col">Reviewer Flag / Delete</th>
             </thead>
             <tbody id="tblReviewers">
               <?php if (! empty($reviewersList) && is_array($reviewersList)) : ?>
                 <?php foreach ($reviewersList as $rl): ?>
                   <tr id="rl_<?= $rl->PublicationsReviewersID ?>">
                     <td><?= $rl->PublicationsReviewersID; ?></td>
                     <td><?= $rl->DisplayName; ?></td>
                     <td id="rl_lr_<?= $rl->PublicationsReviewersID ?>"><?= $rl->LeadReviewer == "1" ? "Yes" : "No" ?></td>
                     <td id="rl_btn_<?= $rl->PublicationsReviewersID ?>"><button class="btn btn-info m-1 fas fa-toggle-on" id="btnER_<?= $rl->PublicationsReviewersID ?>" type="button" title="Toggle Lead Reviewer Flag" onClick="toggleReviewer('rl_lr_<?= $rl->PublicationsReviewersID ?>', <?= $rl->PublicationsReviewersID ?>, <?= $rl->LeadReviewer ?>, 'rl_btn_<?= $rl->PublicationsReviewersID ?>')" />
                       <button class="btn btn-danger m-1 fas fa-trash-alt" id="btnDR_<?= $rl->PublicationsReviewersID ?>" type="button" title="Delete Reviewer" onclick="removeReviewer('rl_<?= $rl->PublicationsReviewersID ?>', <?= $rl->PublicationsReviewersID ?>)" /></td>
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

      <?= MyFormGeneration::generateTextBox("publicationDate",
          set_value('publicationDate', $publication['PublicationDate']),
          "-- Enter the estimated publication date (e.g. 2021-01-29) --", "Publication Date"); ?>

      <?= MyFormGeneration::generateTextBox("webPublicationDate",
          set_value('webPublicationDate', $publication['WebPublicationDate']),
          "-- Enter the web publication date (e.g. 2021-01-29) --", "Web Publication Date"); ?>
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit" value="save" >Save Publication</button>
    <a class="btn btn-info m-1" href="/publications/index/<?= $page ?>">Back to Publications</a>
  </form>
</div>
