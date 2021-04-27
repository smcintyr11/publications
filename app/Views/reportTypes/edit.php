<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/reportTypes/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateIDTextBox("reportTypeID",
      $reportType['ReportTypeID'], "Report Type ID"); ?>

    <?= MyFormGeneration::generateTextBox("reportType",
      set_value('reportType', $reportType['ReportType']),
      "-- Enter the report type --", "Report Type"); ?>

    <?= MyFormGeneration::generateTextBox("abbreviation",
      set_value('abbreviation', $reportType['Abbreviation']),
      "-- Enter the abbreviation for the report type (e.g. JJ) --", "Abbreviation"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Report Type</button>
    <a class="btn btn-info m-1" href="/reportTypes/index/<?= $page ?>">Back to Report Types</a>
  </form>
</div>
