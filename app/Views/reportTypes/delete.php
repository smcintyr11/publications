<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <?php
      $version = "Created by " . $createdBy . " on " . $reportType['Created'] . "<br>";
      if (is_null($reportType['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $reportType['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 12, "right"));
      ?>
  </div>
  
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="<?= base_url() ?>/reportTypes/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateDRAlert($dependentRecords); ?>

    <?= MyFormGeneration::generateIDTextBox("reportTypeID",
      $reportType['ReportTypeID'], "Report Type ID"); ?>

    <?= MyFormGeneration::generateIDTextBox("reportType",
      $reportType['ReportType'], "Report Type"); ?>

    <?= MyFormGeneration::generateIDTextBox("abbreviation",
      $reportType['Abbreviation'], "Abbreviation"); ?>

    <?= MyFormGeneration::generateDeleteOptions($dependentRecords, 'reportTypes', 'report type', $page); ?>

  </form>

</div>
