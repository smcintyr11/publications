<script type="text/javascript" src="<?= base_url() ?>/scripts/unique.js"></script>

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

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="<?= base_url() ?>/costCentres/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateIDTextBox("costCentreID",
      $costCentre['CostCentreID'], "Cost Centre ID"); ?>

      <?= MyFormGeneration::generateTextBox("costCentre",
        set_value('costCentre', $costCentre['CostCentre']),
        "-- Enter the cost centre --", "Cost Centre"); ?>

      <?= MyFormGeneration::generateTextBox("description",
        set_value('description', $costCentre['Description']),
        "-- Enter a description for the cost centre --", "Description"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Cost Centre</button>
    <a class="btn btn-info m-1" href="<?= base_url() ?>/costCentres/index/<?= $page ?>">Back to Cost Centres</a>
  </form>
</div>

<script type="text/javascript">
$(document).ready(function(){
  // Add uniqueness checking to the link type
  $("#costCentre").change(function(){uniqueCheck("<?= base_url() ?>/costCentres/uniqueCheck", "#costCentre", <?= $costCentre['CostCentreID'] ?>, "<?= $costCentre['CostCentre'] ?>");});
});
</script>
