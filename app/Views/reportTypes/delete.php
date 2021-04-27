<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/reportTypes/delete" method="post">
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
