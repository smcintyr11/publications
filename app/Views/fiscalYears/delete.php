<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <?php
      $version = "Created by " . $createdBy . " on " . $fiscalYear['Created'] . "<br>";
      if (is_null($fiscalYear['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $fiscalYear['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 12, "right"));
      ?>
  </div>
  
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="<?= base_url() ?>/fiscalYears/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateDRAlert($dependentRecords); ?>

    <?= MyFormGeneration::generateIDTextBox("fiscalYearID",
      $fiscalYear['FiscalYearID'], "Fiscal Year ID"); ?>

    <?= MyFormGeneration::generateIDTextBox("fiscalYear",
      $fiscalYear['FiscalYear'], "Fiscal Year"); ?>

    <?= MyFormGeneration::generateDeleteOptions($dependentRecords, 'fiscalYears', 'fiscal year', $page); ?>

  </form>
</div>
