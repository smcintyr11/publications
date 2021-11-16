<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <div class="col-6">
      <a class="btn btn-info my-3" href="<?= base_url() ?>/costCentres/index/<?= $page ?>">Back to Cost Centres</a>
    </div>
    <?php
      $version = "Created by " . $createdBy . " on " . $costCentre['Created'] . "<br>";
      if (is_null($costCentre['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $costCentre['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 6, "right"));
      ?>
  </div>

  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIDTextBox("costCentreID",
    $costCentre['CostCentreID'], "Cost Centre ID"); ?>

  <?= MyFormGeneration::generateIDTextBox("costCentre",
    $costCentre['CostCentre'], "Cost Centre"); ?>

  <?= MyFormGeneration::generateIDTextBox("description",
    $costCentre['Description'], "Description"); ?>

</div>
