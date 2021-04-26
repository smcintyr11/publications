<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/costCentres/edit" method="post">
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
    <a class="btn btn-info m-1" href="/costCentres/index/<?= $page ?>">Back to Cost Centres</a>
  </form>
</div>
