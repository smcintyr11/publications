<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <?php
      $version = "Created by " . $createdBy . " on " . $costCentre['Created'] . "<br>";
      if (is_null($costCentre['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $costCentre['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 12, "right"));
      ?>
  </div>
  
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="<?= base_url() ?>/costCentres/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateDRAlert($dependentRecords); ?>

    <?= MyFormGeneration::generateIDTextBox("costCentreID",
      $costCentre['CostCentreID'], "Cost Centre ID"); ?>

    <?= MyFormGeneration::generateIDTextBox("costCentre",
      $costCentre['CostCentre'], "Cost Centre"); ?>

    <?= MyFormGeneration::generateIDTextBox("description",
      $costCentre['Description'], "Description"); ?>

    <?= MyFormGeneration::generateDeleteOptions($dependentRecords, 'costCentres', 'cost centre', $page); ?>

  </form>
</div>
