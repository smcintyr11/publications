<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
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
