<script type="text/javascript" src="<?= base_url() ?>/scripts/unique.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="<?= base_url() ?>/statuses/new" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateTextBox("status",
      set_value('status'), "-- Enter the status --", "Status"); ?>

    <?= MyFormGeneration::generateNumberTextBox("expectedDuration",
      set_value('expectedDuration'), "-- Enter the expected duration in days --", "Expected Duration"); ?>

    <?= MyFormGeneration::generateCheckBox("defaultStatus",
        set_value('defaultStatus'), "Make Default"); ?>

    <?= MyFormGeneration::generateMultilineTextBox("instructions",
        set_value('instructions'), "-- Enter any instructions related to this status --",
        "Instructions", 3); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Create Status</button>
    <a class="btn btn-info m-1" href="<?= base_url() ?>/statuses/index/<?= $page ?>">Back to Statuses</a>
  </form>

</div>

<script type="text/javascript">
$(document).ready(function(){
  // Add uniqueness checking to the link type
  $("#status").change(function(){uniqueCheck("<?= base_url() ?>/statuses/uniqueCheck", "#status", null);});
});
</script>
