<?php
  // Use MyFormGeneration
  use App\Libraries\MyFormGeneration;

  // Calculate sort parameters
  $id_sort_param = "id_asc";
  $fy_sort_param = "fy_asc";
  if ($_SESSION["currentSort"] == "id_asc") {
    $id_sort_param = "id_desc";
  } elseif ($_SESSION["currentSort"] == "fy_asc") {
    $fy_sort_param = "fy_desc";
  }
?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIndexSearch("fiscalYears", csrf_field()); ?>

  <a class="btn btn-primary my-3" href="<?= base_url() ?>/fiscalYears/new/<?= $page ?>">Create Fiscal Year</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 15%">
      <col style="width: 15%">
      <col style="width: 70%">

       <thead class="thead-light">
         <thead class="thead-light">
         <th scope="col"><div class="btn">Edit | Delete</div></th>
           <?= MyFormGeneration::generateColumnHeader("fiscalYears", "Fiscal Year ID",
             $id_sort_param, $_SESSION["currentSort"], "id_asc", "id_desc"); ?>

           <?= MyFormGeneration::generateColumnHeader("fiscalYears", "Fiscal Year",
             $fy_sort_param, $_SESSION["currentSort"], "fy_asc", "fy_desc"); ?>
       </thead>

       <tbody>
         <?php if (! empty($fiscalYears) && is_array($fiscalYears)) : ?>
           <?php foreach ($fiscalYears as $fiscalYear): ?>
             <tr>
               <?= MyFormGeneration::generateIndexRowButtons("fiscalYears", $page, $fiscalYear->FiscalYearID, in_groups(['pubsAdmin','pubsRC']), in_groups(['pubsAdmin','pubsRC']), true, true, false, true); ?>
               <td><?= $fiscalYear->FiscalYearID; ?></td>
               <td><?= $fiscalYear->FiscalYear; ?></td>
             </tr>
           <?php endforeach; ?>
         <?php endif ?>
       </tbody>
    </table>
  </div>

  <?= MyFormGeneration::generateRowsPerPage($_SESSION["rowsPerPage"], $links); ?>
</div>
