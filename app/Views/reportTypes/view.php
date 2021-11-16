<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <div class="col-6">
      <a class="btn btn-info my-3" href="<?= base_url() ?>/reportTypes/index/<?= $page ?>">Back to Report Types</a>
    </div>
    <?php
      $version = "Created by " . $createdBy . " on " . $reportType['Created'] . "<br>";
      if (is_null($reportType['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $reportType['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 6, "right"));
      ?>
  </div>

  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIDTextBox("reportTypeID",
    $reportType['ReportTypeID'], "Report Type ID"); ?>

  <?= MyFormGeneration::generateIDTextBox("reportType",
    $reportType['ReportType'], "Report Type"); ?>

  <?= MyFormGeneration::generateIDTextBox("abbreviation",
    $reportType['Abbreviation'], "Abbreviation"); ?>

</div>
