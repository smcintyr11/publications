<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <div class="col-6">
      <a class="btn btn-info my-3" href="<?= base_url() ?>/fiscalYears/index/<?= $page ?>">Back to Fiscal Years</a>
    </div>
    <?php
      $version = "Created by " . $createdBy . " on " . $fiscalYear['Created'] . "<br>";
      if (is_null($fiscalYear['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $fiscalYear['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 6, "right"));
      ?>
  </div>

  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIDTextBox("fiscalYearID",
    $fiscalYear['FiscalYearID'], "Fiscal Year ID"); ?>

  <?= MyFormGeneration::generateIDTextBox("fiscalYear",
    $fiscalYear['FiscalYear'], "Fiscal Year"); ?>

</div>
