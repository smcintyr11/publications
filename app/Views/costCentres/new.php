<script type="text/javascript" src="<?= base_url() ?>/scripts/unique.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="<?= base_url() ?>/costCentres/new" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateTextBox("costCentre",
      set_value('costCentre'), "-- Enter the cost centre --", "Cost Centre"); ?>

    <?= MyFormGeneration::generateTextBox("description",
      set_value('description'), "-- Enter a description for the cost centre --", "Description"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Create Cost Centre</button>
    <a class="btn btn-info m-1" href="<?= base_url() ?>/costCentres/index/<?= $page ?>">Back to Cost Centres</a>
  </form>
</div>

<script type="text/javascript">
$(document).ready(function(){
  // Add uniqueness checking to the link type
  $("#costCentre").change(function(){uniqueCheck("<?= base_url() ?>/costCentres/uniqueCheck", "#costCentre");});
});
</script>
