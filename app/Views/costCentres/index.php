<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIndexSearch("costCentres", csrf_field()); ?>

  <a class="btn btn-primary my-3" href="/costCentres/new/<?= $page ?>">Create Cost Centre</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 17%">
      <col style="width: 17%">
      <col style="width: 51%">
      <col style="width: 15%">

      <?php
        $id_sort_param = "id_asc";
        $cc_sort_param = "cc_asc";
        $desc_sort_param = "desc_asc";
        if ($_SESSION["currentSort"] == "id_asc") {
          $id_sort_param = "id_desc";
        } elseif ($_SESSION["currentSort"] == "cc_asc") {
          $cc_sort_param = "cc_desc";
        } elseif ($_SESSION["currentSort"] == "desc_asc") {
          $desc_sort_param = "desc_desc";
        }
       ?>

      <thead class="thead-light">
        <?= MyFormGeneration::generateColumnHeader("costCentres", "Cost Centre ID",
          $id_sort_param, $_SESSION["currentSort"], "id_asc", "id_desc"); ?>

        <?= MyFormGeneration::generateColumnHeader("costCentres", "Cost Centre",
          $cc_sort_param, $_SESSION["currentSort"], "cc_asc", "cc_desc"); ?>

        <?= MyFormGeneration::generateColumnHeader("costCentres", "Description",
          $desc_sort_param, $_SESSION["currentSort"], "desc_asc", "desc_desc"); ?>
        <th scope="col"></th>
      </thead>

      <tbody>
        <?php if (! empty($costCentres) && is_array($costCentres)) : ?>
          <?php foreach ($costCentres as $costCentre): ?>
            <tr>
              <td><?= $costCentre->CostCentreID; ?></td>
              <td><?= $costCentre->CostCentre; ?></td>
              <td><?= $costCentre->Description; ?></td>
              <td><a class="btn btn-link" href="/costCentres/edit/<?= $page ?>/<?= $costCentre->CostCentreID ?>">Edit</a>
                |<a class="btn btn-link" href="/costCentres/delete/1/<?= $costCentre->CostCentreID ?>">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif ?>
      </tbody>
    </table>
  </div>

  <?= MyFormGeneration::generateRowsPerPage($_SESSION["rowsPerPage"], $links); ?>

</div>
