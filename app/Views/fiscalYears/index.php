<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIndexSearch("fiscalYears", csrf_field()); ?>

  <a class="btn btn-primary my-3" href="/fiscalYears/new/<?= $page ?>">Create Fiscal Year</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 15%">
      <col style="width: 70%">
      <col style="width: 15%">

      <?php
        $id_sort_param = "id_asc";
        $fy_sort_param = "fy_asc";
        if ($_SESSION["currentSort"] == "id_asc") {
          $id_sort_param = "id_desc";
        } elseif ($_SESSION["currentSort"] == "fy_asc") {
          $fy_sort_param = "fy_desc";
        }
       ?>

       <thead class="thead-light">
         <thead class="thead-light">
           <?= MyFormGeneration::generateColumnHeader("fiscalYears", "Fiscal Year ID",
             $id_sort_param, $_SESSION["currentSort"], "id_asc", "id_desc"); ?>

           <?= MyFormGeneration::generateColumnHeader("fiscalYears", "Fiscal Year",
             $id_sort_param, $_SESSION["currentSort"], "fy_asc", "fy_desc"); ?>

         <th scope="col"></th>
       </thead>

       <tbody>
         <?php if (! empty($fiscalYears) && is_array($fiscalYears)) : ?>
           <?php foreach ($fiscalYears as $fiscalYear): ?>
             <tr>
               <td><?= $fiscalYear->FiscalYearID; ?></td>
               <td><?= $fiscalYear->FiscalYear; ?></td>
               <td><a class="btn btn-link" href="/fiscalYears/edit/<?= $page ?>/<?= $fiscalYear->FiscalYearID ?>">Edit</a>
                 |<a class="btn btn-link" href="/fiscalYears/delete/1/<?= $fiscalYear->FiscalYearID ?>">Delete</a>
               </td>
             </tr>
           <?php endforeach; ?>
         <?php endif ?>
       </tbody>
    </table>
  </div>

  <?= MyFormGeneration::generateRowsPerPage($_SESSION["rowsPerPage"], $links); ?>
</div>
