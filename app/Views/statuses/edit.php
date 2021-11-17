<script type="text/javascript" src="<?= base_url() ?>/scripts/unique.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <?php
      $version = "Created by " . $createdBy . " on " . $status['Created'] . "<br>";
      if (is_null($status['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $status['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 12, "right"));
      ?>
  </div>

  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="<?= base_url() ?>/statuses/edit" method="post">
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
    <a class="btn btn-info m-1" href="<?= base_url() ?>/statuses/index/<?= $page ?>">Back to Statuses</a>
  </form>
</div>

<script type="text/javascript">
$(document).ready(function(){
  // Add uniqueness checking to the link type
  $("#status").change(function(){uniqueCheck("<?= base_url() ?>/statuses/uniqueCheck", "#status", <?= $status['StatusID'] ?>, "<?= $status['Status'] ?>");});
});
</script>
