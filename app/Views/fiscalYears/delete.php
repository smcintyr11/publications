<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
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
