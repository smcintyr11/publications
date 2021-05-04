<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/statuses/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateIDTextBox("statusID",
      $status['StatusID'], "Status ID"); ?>

    <?= MyFormGeneration::generateTextBox("status",
      set_value('status', $status['Status']),
      "-- Enter the status --", "Status"); ?>

    <?= MyFormGeneration::generateNumberTextBox("expectedDuration",
      set_value('expectedDuration', $status['ExpectedDuration']),
      "-- Enter the expected duration in days --", "Expected Duration"); ?>

    <?= MyFormGeneration::generateCheckBox("defaultStatus",
        set_value('defaultStatus', $status['DefaultStatus']), "Make Default"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Status</button>
    <a class="btn btn-info m-1" href="/statuses/index/<?= $page ?>">Back to Statuses</a>
  </form>
</div>
