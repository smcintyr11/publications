<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="<?= base_url() ?>/statuses/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateDRAlert($dependentRecords); ?>

    <?= MyFormGeneration::generateIDTextBox("statusID",
      $status['StatusID'], "Status ID"); ?>

    <?= MyFormGeneration::generateIDTextBox("status",
      $status['Status'], "Status"); ?>

    <?= MyFormGeneration::generateIDTextBox("expectedDuration",
      $status['ExpectedDuration'], "Expected Duration"); ?>

    <?= MyFormGeneration::generateIDTextBox("defaultStatus",
        ($status['DefaultStatus'] == 0 ? "No" : "Yes"), "Default Status"); ?>

    <?= MyFormGeneration::generateDeleteOptions($dependentRecords, 'statuses', 'status', $page); ?>

  </form>

</div>
