<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <div class="col-6">
      <a class="btn btn-info my-3" href="<?= base_url() ?>/statuses/index/<?= $page ?>">Back to Statuses</a>
    </div>
    <?php
      $version = "Created by " . $createdBy . " on " . $status['Created'] . "<br>";
      if (is_null($client['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $status['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 6, "right"));
      ?>
  </div>

  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIDTextBox("statusID",
    $status['StatusID'], "Status ID"); ?>

  <?= MyFormGeneration::generateIDTextBox("status",
    $status['Status'], "Status"); ?>

  <?= MyFormGeneration::generateIDTextBox("expectedDuration",
    $status['ExpectedDuration'], "Expected Duration"); ?>

  <?= MyFormGeneration::generateIDTextBox("defaultStatus",
      ($status['DefaultStatus'] == 0 ? "No" : "Yes"), "Default Status"); ?>

</div>
