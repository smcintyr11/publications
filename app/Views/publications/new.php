<script type="text/javascript" src="<?= base_url() ?>/scripts/lookup.js"></script>
<script type="text/javascript" src="<?= base_url() ?>/scripts/publicationsNew.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?php
    $errorList = \Config\Services::validation()->listErrors();
    $count = count(\Config\Services::validation()->getErrors());
    if ($count > 0) {
      echo ('<div class="alert alert-warning" role="alert">');
      echo ($errorList);
      echo ('</div>');
    }
  ?>

  <!-- New Report Type Modal -->
  <div class="modal fade" id="newReportTypeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">New Report Type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <?= MyFormGeneration::generateIDTextBox("newReportType",
              null, "Report Type"); ?>
            <?= MyFormGeneration::generateTextBox("newAbbreviation",
              null, "-- Enter the abbreviation for the report type (e.g. JJ) --", "Abbreviation"); ?>

            <div class="form-group row">
              <p class="mx-3">The report type you enetred does not exist in the database.</p>
              <p class="mx-3">Do you want to add this report type to the database and continue adding the publication to the database?</p>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" id="btnNewReportTypeSave" class="btn btn-success" onclick="addReportType()">Yes</button>
          <button type="button" class="btn btn-info" data-dismiss="modal" id="btnCloseModal">No</button>
        </div>
      </div>
    </div>
  </div>

  <form class="form-group" action="<?= base_url() ?>/publications/new" method="post" id="frmNewPublication">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateMultilineTextBox("primaryTitle",
      set_value('primaryTitle'),
      "-- Enter the primary title --", "Primary Title", 3); ?>

    <?= MyFormGeneration::generateLookupTextBox("reportTypeN",
      set_value('reportTypeN'), "-- Enter a report type --", "Report Type", "reportTypeNID",
      set_value('reportTypeNID')); ?>

    <!-- Hidden button to trigger modal -->
    <div style="display: none;">
      <button type="button" data-toggle="modal" data-target="#newReportTypeModal" id="btnNewReportType" />
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit" id="btnSubmit">Create Publication</button>
    <a class="btn btn-info m-1" href="<?= base_url() ?>/publications/<?= session('publicationIndex') ?>/<?= $page ?>">Back to Publications</a>
  </form>
</div>
