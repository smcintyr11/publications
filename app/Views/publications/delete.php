<!-- Load Table Sorter -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.bootstrap_4.min.css" integrity="sha512-2C6AmJKgt4B+bQc08/TwUeFKkq8CsBNlTaNcNgUmsDJSU1Fg+R6azDbho+ZzuxEkJnCjLZQMozSq3y97ZmgwjA==" crossorigin="anonymous" />
<script type="text/javascript" src="<?= base_url() ?>/scripts/publicationDelete.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>
<?php $hideDetailedFields = true; ?>

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

   <?php
     if ($publication['RushPublication'] == 1) {
       echo ('<div class="alert alert-primary" role="alert">
         This is a <strong>RUSH</strong> publication.
         </div>');
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
       <a class="nav-link tablink" onclick="openTab(event, 'tbAuthors')">Authors</a>
     </li>
     <li class="nav-item">
       <a class="nav-link tablink" onclick="openTab(event, 'tbReviewers')">Reviewers</a>
     </li>
     <li class="nav-item" <?= ($hideDetailedFields ? 'style="display: none;"' : '') ?> >
       <a class="nav-link tablink" onclick="openTab(event, 'tbAbstract')">Abstract</a>
     </li>
     <li class="nav-item" <?= ($hideDetailedFields ? 'style="display: none;"' : '') ?> >
       <a class="nav-link tablink" onclick="openTab(event, 'tbPLS')">PLS</a>
     </li>
     <li class="nav-item" <?= ($hideDetailedFields ? 'style="display: none;"' : '') ?> >
       <a class="nav-link tablink" onclick="openTab(event, 'tbPRS')">PRS</a>
     </li>
     <li class="nav-item" <?= ($hideDetailedFields ? 'style="display: none;"' : '') ?> >
       <a class="nav-link tablink" onclick="openTab(event, 'tbKeywords')">Keywords</a>
     </li>
     <li class="nav-item">
       <a class="nav-link tablink" onclick="openTab(event, 'tbPublishing')">Publishing</a>
     </li>
     <li class="nav-item" <?= ($hideDetailedFields ? 'style="display: none;"' : '') ?> >
       <a class="nav-link tablink" onclick="openTab(event, 'tbDates')">Dates</a>
     </li>
     <li class="nav-item">
       <a class="nav-link tablink" onclick="openTab(event, 'tbLinks')">Links</a>
     </li>
     <li class="nav-item">
       <a class="nav-link tablink" onclick="openTab(event, 'tbComments')">Comments</a>
     </li>
   </ul>

   <form class="form-group" action="<?= base_url() ?>/publications/delete" method="post" id="frmDeletePublication" >
     <br />
     <?= csrf_field() ?>

     <!-- Tab content -->
     <!-- General Tab -->
     <div id="tbGeneral" class="tabcontent" style="display: block;">

       <?= MyFormGeneration::generateIDTextBox("publicationID",
         $publication['PublicationID'], "Publication ID"); ?>

       <?= MyFormGeneration::generateIDTextBox("primaryTitle",
         $publication['PrimaryTitle'], "Primary Title"); ?>

       <?= MyFormGeneration::generateIDTextBox("secondaryTitle",
         $publication['SecondaryTitle'], "Secondary Title", $hideDetailedFields); ?>

       <?= MyFormGeneration::generateIDTextBox("reportType",
         $publication['ReportType'], "Report Type"); ?>

       <?= MyFormGeneration::generateIDTextBox("rushPublication",
         ($publication['RushPublication'] == 0 ? "No" : "Yes"), "Rush Publication"); ?>

       <?= MyFormGeneration::generateIDTextBox("reportNumber",
         $publication['ReportNumber'], "Report Number"); ?>

       <?= MyFormGeneration::generateIDTextBox("agreementNumber",
         $publication['AgreementNumber'], "Agreement Number", $hideDetailedFields); ?>

       <?= MyFormGeneration::generateIDTextBox("fiscalYear",
         $publication['FiscalYear'], "Fiscal Year", $hideDetailedFields); ?>

       <?= MyFormGeneration::generateIDTextBox("organization",
         $publication['Organization'], "Organization", $hideDetailedFields); ?>

       <?= MyFormGeneration::generateIDTextBox("costCentre",
         $publication['CostCentre'], "Cost Centre"); ?>

       <?= MyFormGeneration::generateIDTextBox("projectCode",
         $publication['ProjectCode'], "Project Code"); ?>

       <?= MyFormGeneration::generateIDTextBox("ipdNumber",
         $publication['IPDNumber'], "IPD Number", $hideDetailedFields); ?>

       <?= MyFormGeneration::generateIDTextBox("crossReferenceNumber",
         $publication['CrossReferenceNumber'], "Cross Reference Number", $hideDetailedFields); ?>
     </div>

     <!-- Status Tab -->
     <div id="tbStatus" class="tabcontent" style="display: none;">
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
              </thead>
              <tbody id="tblAuthors">
                <?php if (! empty($authorsList) && is_array($authorsList)) : ?>
                  <?php foreach ($authorsList as $al): ?>
                    <tr>
                      <td><?= $al->PublicationsAuthorsID; ?></td>
                      <td><?= $al->DisplayName; ?></td>
                      <td><?= $al->PrimaryAuthor == "1" ? "Yes" : "No" ?></td>
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
              </thead>
              <tbody id="tblReviewers">
                <?php if (! empty($reviewersList) && is_array($reviewersList)) : ?>
                  <?php foreach ($reviewersList as $rl): ?>
                    <tr>
                      <td><?= $rl->PublicationsReviewersID; ?></td>
                      <td><?= $rl->DisplayName; ?></td>
                      <td><?= $rl->LeadReviewer == "1" ? "Yes" : "No" ?></td>
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
           $publication['AbstractEnglish'], "", "Abstract (English)", 5, true); ?>

       <?= MyFormGeneration::generateMultilineTextBox("abstractFrench",
           $publication['AbstractFrench'], "", "Abstract (French)", 5, true); ?>
     </div>

     <!-- PLS Tab -->
     <div id="tbPLS" class="tabcontent" style="display: none;">
       <?= MyFormGeneration::generateMultilineTextBox("plsEnglish",
           $publication['PLSEnglish'], "", "PLS (English)", 5, true); ?>

       <?= MyFormGeneration::generateMultilineTextBox("plsFrench",
           $publication['PLSFrench'], "", "PLS (French)", 5, true); ?>
     </div>

     <!-- PRS Tab -->
     <div id="tbPRS" class="tabcontent" style="display: none;">
       <?= MyFormGeneration::generateMultilineTextBox("prsEnglish",
           $publication['PRSEnglish'], "", "PRS (English)", 5, true); ?>

       <?= MyFormGeneration::generateMultilineTextBox("prsFrench",
           $publication['PRSFrench'], "", "PRS (French)", 5, true); ?>
     </div>

     <!-- Keywords Tab -->
     <div id="tbKeywords" class="tabcontent" style="display: none;">
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
              </thead>
              <tbody id="tblKeywords">
                <?php if (! empty($keywordsList) && is_array($keywordsList)) : ?>
                  <?php foreach ($keywordsList as $kl): ?>
                    <tr>
                      <td><?= $kl->PublicationsKeywordsID; ?></td>
                      <td><?= $kl->KeywordEnglish; ?></td>
                      <td><?= $kl->KeywordFrench; ?></td>
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

       <?= MyFormGeneration::generateIDTextBox("client",
         $publication['Client'], "Client"); ?>

       <?= MyFormGeneration::generateIDTextBox("journal",
         $publication['Journal'], "Journal", $hideDetailedFields); ?>

       <?= MyFormGeneration::generateIDTextBox("manuscriptNumber",
         $publication['ManuscriptNumber'], "Manuscript Number", $hideDetailedFields); ?>

       <?= MyFormGeneration::generateIDTextBox("volume",
         $publication['Volume'], "Volume", $hideDetailedFields); ?>

       <?= MyFormGeneration::generateIDTextBox("startPage",
         $publication['StartPage'], "Start Page", $hideDetailedFields); ?>

       <?= MyFormGeneration::generateIDTextBox("endPage",
         $publication['EndPage'], "End Page", $hideDetailedFields); ?>

       <?= MyFormGeneration::generateIDTextBox("isbn",
         $publication['ISBN'], "ISBN / ISSN", $hideDetailedFields); ?>

       <?= MyFormGeneration::generateIDTextBox("doi",
         $publication['DOI'], "DOI", $hideDetailedFields); ?>

     </div>

     <!-- Dates Tab -->
     <div id="tbDates" class="tabcontent" style="display: none;">

       <?= MyFormGeneration::generateIDTextBox("publicationDate",
         $publication['PublicationDate'], "Publication Date"); ?>

       <?= MyFormGeneration::generateIDTextBox("webPublicationDate",
         $publication['WebPublicationDate'], "Web Publication Date"); ?>

       <?= MyFormGeneration::generateIDTextBox("journalSubmissionDate",
         $publication['JournalSubmissionDate'], "Journal Submission Date"); ?>

       <?= MyFormGeneration::generateIDTextBox("journalAcceptanceDate",
         $publication['JournalAcceptanceDate'], "Journal Acceptance Date"); ?>

       <?= MyFormGeneration::generateIDTextBox("conferenceSubmissionDate",
         $publication['ConferenceSubmissionDate'], "Conference Submission Date"); ?>

       <?= MyFormGeneration::generateIDTextBox("conferenceAcceptanceDate",
         $publication['ConferenceAcceptanceDate'], "Conference Acceptance Date"); ?>

       <?= MyFormGeneration::generateIDTextBox("embargoPeriod",
         $publication['EmbargoPeriod'], "Embargo Period (Months)"); ?>

       <?= MyFormGeneration::generateIDTextBox("embargoEndDate",
         $publication['EmbargoEndDate'], "Embargo End Date"); ?>

       <?= MyFormGeneration::generateIDTextBox("sentToClient",
         ($publication['SentToClient'] == 0 ? "No" : "Yes"), "Sent To Client"); ?>

       <?= MyFormGeneration::generateIDTextBox("sentToClientDate",
         $publication['SentToClientDate'], "Sent To Client Date"); ?>

       <?= MyFormGeneration::generateIDTextBox("reportFormatted",
         ($publication['ReportFormatted'] == 0 ? "No" : "Yes"), "Report Formatted"); ?>

     </div>

     <!-- Links Tab -->
     <div id="tbLinks" class="tabcontent" style="display: none;">

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
              </thead>
              <tbody id="tblLinks">
                <?php if (! empty($linksList) && is_array($linksList)) : ?>
                  <?php foreach ($linksList as $ll): ?>
                    <tr>
                      <td><?= $ll->PublicationsLinksID; ?></td>
                      <td>
                        <?php
                         $pattern = '/http|https|ftp/i';
                         if (preg_match($pattern, $ll->Link)) {
                           echo ('<a href="' . $ll->Link . '" target="_blank">' . $ll->Link . '</a>');
                         } else {
                           echo ($ll->Link);
                         }
                        ?>
                      </td>
                      <td><?= $ll->LinkType; ?></td>
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
         <h3>Comments</h3>
       </div>

       <div class="form-group row">
         <div class="table-responsive">
           <table class="table table-striped table-bordered">
              <thead class="thead-light">
                <th scope="col">ID</th>
                <th scope="col">Date Entered</th>
                <th scope="col">Comment</th>
                <th scope="col">View</th>
              </thead>
              <tbody id="tblComments">
                <?php if (! empty($commentsList) && is_array($commentsList)) : ?>
                  <?php foreach ($commentsList as $cl): ?>
                    <tr>
                      <td><?= $cl->PublicationsCommentsID; ?></td>
                      <td><?= $cl->DateEntered; ?></td>
                      <td><?= $cl->Comment; ?></td>
                      <td>
                        <button class="btn btn-info m-1 fas fa-info-circle" type="button" title="View Comment" data-toggle="modal" data-target="#commentModal" data-pcid="<?= $cl->PublicationsCommentsID ?>" />
                     </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif ?>
              </tbody>
           </table>
         </div>
       </div>

     </div>

     <?= MyFormGeneration::generateDeleteOptions(false, 'publications', 'publication', $page, session('publicationIndex') ?? 'index'); ?>

   </form>
 </div>
