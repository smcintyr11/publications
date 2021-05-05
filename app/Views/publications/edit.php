<!-- Load Table Sorter -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.bootstrap_4.min.css" integrity="sha512-2C6AmJKgt4B+bQc08/TwUeFKkq8CsBNlTaNcNgUmsDJSU1Fg+R6azDbho+ZzuxEkJnCjLZQMozSq3y97ZmgwjA==" crossorigin="anonymous" />
<script type="text/javascript" src="/scripts/publicationEdit.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>

<!-- Edit Link Modal -->
 <div class="modal fade" id="linkModal" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title">Edit Link</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
       </div>
       <div class="modal-body">
         <form>
           <div class="form-group">
             <?= MyFormGeneration::generateIDTextBox("editPublicationsLinksID",
               null, "ID"); ?>
           </div>
           <div class="form-group">
             <?= MyFormGeneration::generateSelect("editLinkTypeID",
               null, "-- Select a link type --", "Link Type", $linkTypes); ?>
          </div>
           <div class="form-group">
             <?= MyFormGeneration::generateTextBox("editLink",
               null, "-- Enter the link --", "Link"); ?>
           </div>
         </form>
       </div>
       <div class="modal-footer">
         <button type="button" id="btnEditLinkSave" class="btn btn-success" onclick="editLink()">Save</button>
         <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
       </div>
     </div>
   </div>
 </div>

 <!-- View Comment Modal -->
  <div class="modal fade" id="commentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">View Comment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <?= MyFormGeneration::generateIDTextBox("viewPublicationsCommentsID",
                null, "ID"); ?>
            </div>
            <div class="form-group">
              <?= MyFormGeneration::generateIDTextBox("viewPublicationsCommentsDateEntered",
                null, "Date Entered"); ?>
            </div>
            <div class="form-group">
              <?= MyFormGeneration::generateMultilineTextBox("viewPublicationsCommentsComment",
                null, "-- Comment --", "Comment"); ?>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

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
      <a class="nav-link tablink" onclick="openTab(event, 'tbAbstract')">Abstract</a>
    </li>
    <li class="nav-item">
      <a class="nav-link tablink" onclick="openTab(event, 'tbPLS')">PLS</a>
    </li>
    <li class="nav-item">
      <a class="nav-link tablink" onclick="openTab(event, 'tbPRS')">PRS</a>
    </li>
    <li class="nav-item">
      <a class="nav-link tablink" onclick="openTab(event, 'tbKeywords')">Keywords</a>
    </li>
    <li class="nav-item">
      <a class="nav-link tablink" onclick="openTab(event, 'tbPublishing')">Publishing</a>
    </li>
    <li class="nav-item">
      <a class="nav-link tablink" onclick="openTab(event, 'tbDates')">Dates</a>
    </li>
    <li class="nav-item">
      <a class="nav-link tablink" onclick="openTab(event, 'tbLinks')">Links</a>
    </li>
    <li class="nav-item">
      <a class="nav-link tablink" onclick="openTab(event, 'tbComments')">Comments</a>
    </li>
  </ul>


  <form class="form-group" action="/publications/edit" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="page" value="<?= $page ?>">

    <br />
    <!-- Tab content -->
    <!-- General Tab -->
    <div id="tbGeneral" class="tabcontent" style="display: block;">

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
        MyFormGeneration::generateNewButtonURL("reportTypes"), "reportTypeID",
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
        MyFormGeneration::generateNewButtonURL("fiscalYears"), "fiscalYearID",
        set_value('fiscalYearID', $publication['FiscalYearID'])); ?>

      <?= MyFormGeneration::generateLookupTextBox("organization",
        set_value('organization', $publication['Organization']),
        "-- Enter an organization --", "Organization",
        MyFormGeneration::generateNewButtonURL("organizations"), "organizationID",
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

      <?= MyFormGeneration::generateHiddenInput('originalStatusID',
        set_value('originalStatusID', $publication['OriginalStatusID'])); ?>
      <?= MyFormGeneration::generateHiddenInput('originalStatusPersonID',
        set_value('originalStatusID', $publication['OriginalStatusPersonID'])); ?>
      <?= MyFormGeneration::generateHiddenInput('originalStatusDueDate',
        set_value('originalStatusID', $publication['OriginalStatusDueDate'])); ?>

      <?= MyFormGeneration::generateSelect("statusID",
        set_value('statusID', $publication['StatusID']),
        "-- Select a status --", "Status", $statuses); ?>

      <?= MyFormGeneration::generateLookupTextBox("assignedTo",
        set_value('assignedTo', $publication['StatusPerson']),
        "-- Enter a person --", "Assigned To",
        MyFormGeneration::generateNewButtonURL("people"), "statusPersonID",
        set_value('statusPersonID', $publication['StatusPersonID'])); ?>

      <?= MyFormGeneration::generateDateTextBox("statusDueDate",
          set_value('statusDueDate', $publication['StatusDueDate']),
          "Due Date"); ?>

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
               <th scope="col">Due Date</th>
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
                     <td><?= $sl->DueDate; ?></td>
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
    <div id="tbAuthors" class="tabcontent" style="display: none;">

      <?= MyFormGeneration::generateLookupTextBox("newAuthor",
        null, "-- Enter a person --", "Author", null, "authorID", null, "btnAddAuthor"); ?>

      <div class="form-group row">
      <h3>Authors</h3>
			</div>

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
    <div id="tbReviewers" class="tabcontent" style="display: none;">

      <?= MyFormGeneration::generateLookupTextBox("newReviewer",
        null, "-- Enter a person --", "Reviewer", null, "reviewerID", null, "btnAddReviewer"); ?>

      <div class="form-group row">
      <h3>Reviewers</h3>
			</div>

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

    <!-- Abstract Tab -->
    <div id="tbAbstract" class="tabcontent" style="display: none;">
      <?= MyFormGeneration::generateMultilineTextBox("abstractEnglish",
          set_value('abstractEnglish', $publication['AbstractEnglish']),
          "-- Enter the abstract --", "Abstract (English)", 5); ?>

      <?= MyFormGeneration::generateMultilineTextBox("abstractFrench",
          set_value('abstractFrench', $publication['AbstractFrench']),
          "-- Enter the abstract --", "Abstract (French)", 5); ?>
    </div>

    <!-- PLS Tab -->
    <div id="tbPLS" class="tabcontent" style="display: none;">
      <?= MyFormGeneration::generateMultilineTextBox("plsEnglish",
          set_value('plsEnglish', $publication['PLSEnglish']),
          "-- Enter the plain language summary --", "PLS (English)", 5); ?>

      <?= MyFormGeneration::generateMultilineTextBox("plsFrench",
          set_value('plsFrench', $publication['PLSFrench']),
          "-- Enter the plain language summary --", "PLS (French)", 5); ?>
    </div>

    <!-- PRS Tab -->
    <div id="tbPRS" class="tabcontent" style="display: none;">
      <?= MyFormGeneration::generateMultilineTextBox("prsEnglish",
          set_value('prsEnglish', $publication['PRSEnglish']),
          "-- Enter the policy relevance statement --", "PRS (English)", 5); ?>

      <?= MyFormGeneration::generateMultilineTextBox("prsFrench",
          set_value('prsFrench', $publication['PRSFrench']),
          "-- Enter the policy relevance statement --", "PRS (French)", 5); ?>
    </div>

    <!-- Keywords Tab -->
    <div id="tbKeywords" class="tabcontent" style="display: none;">

      <?= MyFormGeneration::generateLookupTextBox("newKeyword",
        null, "-- Enter a keyword --", "Keyword", null, "keywordID", null, "btnAddKeyword"); ?>

      <div class="form-group row">
      <h3>Keywords</h3>
      </div>

      <div class="form-group row">
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
             <thead class="thead-light">
               <th scope="col">ID</th>
               <th scope="col">Keyword (Enghlish)</th>
               <th scope="col">Keyword (French)</th>
               <th scope="col">Delete</th>
             </thead>
             <tbody id="tblKeywords">
               <?php if (! empty($keywordsList) && is_array($keywordsList)) : ?>
                 <?php foreach ($keywordsList as $kl): ?>
                   <tr id="kl_<?= $kl->PublicationsKeywordsID ?>">
                     <td><?= $kl->PublicationsKeywordsID; ?></td>
                     <td><?= $kl->KeywordEnglish; ?></td>
                     <td><?= $kl->KeywordFrench; ?></td>
                     <td><button class="btn btn-danger m-1 fas fa-trash-alt" type="button" title="Delete Keyword" onclick="removeKeyword('kl_<?= $kl->PublicationsKeywordsID ?>', <?= $kl->PublicationsKeywordsID ?>)" /></td>
                   </tr>
                 <?php endforeach; ?>
               <?php endif ?>
             </tbody>
          </table>
        </div>
      </div>

    </div>

    <!-- Publishing Tab -->
    <div id="tbPublishing" class="tabcontent" style="display: none;">

      <?= MyFormGeneration::generateLookupTextBox("client",
        set_value('client', $publication['Client']),
        "-- Enter a client / publisher --", "Client / Publisher",
        MyFormGeneration::generateNewButtonURL("clients"), "clientID",
        set_value('clientID', $publication['ClientID'])); ?>

      <?= MyFormGeneration::generateLookupTextBox("journal",
        set_value('journal', $publication['Journal']),
        "-- Enter a journal --", "Journal",
        MyFormGeneration::generateNewButtonURL("journals"), "journalID",
        set_value('journalID', $publication['JournalID'])); ?>

      <?= MyFormGeneration::generateTextBox("manuscriptNumber",
          set_value('manuscriptNumber', $publication['ManuscriptNumber']),
          "-- Enter the manuscript number --", "Manuscript Number"); ?>

      <?= MyFormGeneration::generateTextBox("volume",
          set_value('volume', $publication['Volume']),
          "-- Enter the volume --", "Volume"); ?>

      <?= MyFormGeneration::generateTextBox("startPage",
          set_value('startPage', $publication['StartPage']),
          "-- Enter the start page --", "Start Page"); ?>

      <?= MyFormGeneration::generateTextBox("endPage",
          set_value('endPage', $publication['EndPage']),
          "-- Enter the end page --", "End Page"); ?>

      <?= MyFormGeneration::generateTextBox("isbn",
          set_value('isbn', $publication['ISBN']),
          "-- Enter the ISBN or ISSN --", "ISBN / ISSN"); ?>

      <?= MyFormGeneration::generateTextBox("doi",
          set_value('doi', $publication['DOI']),
          "-- Enter the DOI --", "DOI"); ?>

    </div>

    <!-- Dates Tab -->
    <div id="tbDates" class="tabcontent" style="display: none;">

      <?= MyFormGeneration::generateDateTextBox("publicationDate",
          set_value('publicationDate', $publication['PublicationDate']), "Publication Date"); ?>

      <?= MyFormGeneration::generateDateTextBox("webPublicationDate",
          set_value('webPublicationDate', $publication['WebPublicationDate']), "Web Publication Date"); ?>

      <?= MyFormGeneration::generateDateTextBox("journalSubmissionDate",
          set_value('journalSubmissionDate', $publication['JournalSubmissionDate']), "Journal Submission Date"); ?>

      <?= MyFormGeneration::generateDateTextBox("journalAcceptanceDate",
          set_value('journalAcceptanceDate', $publication['JournalAcceptanceDate']), "Journal Acceptance Date"); ?>

      <?= MyFormGeneration::generateDateTextBox("conferenceSubmissionDate",
          set_value('conferenceSubmissionDate', $publication['ConferenceSubmissionDate']), "Conference Submission Date"); ?>

      <?= MyFormGeneration::generateDateTextBox("conferenceAcceptanceDate",
          set_value('conferenceAcceptanceDate', $publication['ConferenceAcceptanceDate']), "Conference Acceptance Date"); ?>

      <?= MyFormGeneration::generateNumberTextBox("embargoPeriod",
          set_value('embargoPeriod', $publication['EmbargoPeriod']),
          "-- Enter the embargo period in months --", "Embargo Period (Months)"); ?>

      <?= MyFormGeneration::generateDateTextBox("embargoEndDate",
          set_value('embargoEndDate', $publication['EmbargoEndDate']), "Embargo End Date"); ?>

      <?= MyFormGeneration::generateCheckBox("sentToClient",
          set_value('sentToClient', $publication['SentToClient']), "Sent To Client"); ?>

      <?= MyFormGeneration::generateDateTextBox("sentToClientDate",
          set_value('sentToClientDate', $publication['SentToClientDate']), "Sent To Client Date"); ?>

      <?= MyFormGeneration::generateCheckBox("reportFormatted",
          set_value('reportFormatted', $publication['ReportFormatted']), "Report Formatted"); ?>

    </div>

    <!-- Links Tab -->
    <div id="tbLinks" class="tabcontent" style="display: none;">

      <?= MyFormGeneration::generateSelect("newLinkTypeID",
        null, "-- Select a link type --", "Link Type", $linkTypes); ?>

      <div class="form-group row">
        <label for="newLink" class="col-2 col-form-label font-weight-bold">Link:</label>
        <div class="col-8">
          <input class="form-control" type="input" name="newLink" placeholder="-- Enter the link --" id="newLink" />
          <br />
        </div>
        <div class="col-2">
          <button type="button" class="btn btn-success" id="btnAddLink">Add Link</button>
        </div>
      </div>

      <div class="form-group row">
      <h3>Links</h3>
      </div>

      <div class="form-group row">
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
             <thead class="thead-light">
               <th scope="col">ID</th>
               <th scope="col">Link</th>
               <th scope="col">Link Type</th>
               <th scope="col">Edit / Delete</th>
             </thead>
             <tbody id="tblLinks">
               <?php if (! empty($linksList) && is_array($linksList)) : ?>
                 <?php foreach ($linksList as $ll): ?>
                   <tr id="ll_<?= $ll->PublicationsLinksID ?>">
                     <td><?= $ll->PublicationsLinksID; ?></td>
                     <td id="ll_l_<?= $ll->PublicationsLinksID ?>"><?= $ll->Link; ?></td>
                     <td id="ll_lt_<?= $ll->PublicationsLinksID ?>"><?= $ll->LinkType; ?></td>
                     <td>
                       <button class="btn btn-info m-1 fas fa-edit" id="btnEL_<?= $ll->PublicationsLinksID ?>" type="button" title="Edit Link" data-toggle="modal" data-target="#linkModal" data-id="<?= $ll->PublicationsLinksID ?>" />
                       <button class="btn btn-danger m-1 fas fa-trash-alt" type="button" title="Delete Link" onclick="removeLink('ll_<?= $ll->PublicationsLinksID ?>', <?= $ll->PublicationsLinksID ?>)" />
                    </td>
                   </tr>
                 <?php endforeach; ?>
               <?php endif ?>
             </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Comments Tab -->
    <div id="tbComments" class="tabcontent" style="display: none;">

      <div class="form-group row">
        <label for="newComment" class="col-2 col-form-label font-weight-bold">Comment:</label>
        <div class="col-8">
          <textarea class="form-control" name="newLink" placeholder="-- Enter the comment --" id="newComment" rows="5" ></textarea>
          <br />
        </div>
        <div class="col-2">
          <button type="button" class="btn btn-success" id="btnAddComment">Add Comment</button>
        </div>
      </div>

      <div class="form-group row">
      <h3>Comments</h3>
      </div>

      <div class="form-group row">
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
             <thead class="thead-light">
               <th scope="col">ID</th>
               <th scope="col">Date Entered</th>
               <th scope="col">Comment</th>
               <th scope="col">View / Delete</th>
             </thead>
             <tbody id="tblComments">
               <?php if (! empty($commentsList) && is_array($commentsList)) : ?>
                 <?php foreach ($commentsList as $cl): ?>
                   <tr id="cl_<?= $cl->PublicationsCommentsID ?>">
                     <td><?= $cl->PublicationsCommentsID; ?></td>
                     <td><?= $cl->DateEntered; ?></td>
                     <td><?= $cl->Comment; ?></td>
                     <td>
                       <button class="btn btn-info m-1 fas fa-info-circle" type="button" title="View Comment" data-toggle="modal" data-target="#commentModal" data-pcid="<?= $cl->PublicationsCommentsID ?>" />
                       <button class="btn btn-danger m-1 fas fa-trash-alt" type="button" title="Delete Comment" onclick="removeComment('cl_<?= $cl->PublicationsCommentsID ?>', <?= $cl->PublicationsCommentsID ?>)" />
                    </td>
                   </tr>
                 <?php endforeach; ?>
               <?php endif ?>
             </tbody>
          </table>
        </div>
      </div>

    </div>

    <button class="btn btn-success m-1" type="submit" name="submit" value="save" >Save Publication</button>
    <a class="btn btn-info m-1" href="/publications/index/<?= $page ?>">Back to Publications</a>
  </form>
</div>
