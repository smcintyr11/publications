<?php
  // Use MyFormGeneration
  use App\Libraries\MyFormGeneration;

  // Calculate sort parameters
  $id_sort_param = "id_asc";
  $status_sort_param = "status_asc";
  $ed_sort_param = "ed_asc";
  $def_sort_param = "def_asc";
  if ($_SESSION["currentSort"] == "id_asc") {
    $id_sort_param = "id_desc";
  } elseif ($_SESSION["currentSort"] == "status_asc") {
    $status_sort_param = "status_desc";
  } elseif ($_SESSION["currentSort"] == "ed_asc") {
    $ed_sort_param = "ed_desc";
  } elseif ($_SESSION["currentSort"] == "def_asc") {
    $def_sort_param = "def_desc";
  }
?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIndexSearch("statuses", csrf_field()); ?>

  <a class="btn btn-primary my-3" href="<?= base_url() ?>/statuses/new/<?= $page ?>">Create Status</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 15%">
      <col style="width: 10%">
      <col style="width: 30%">
      <col style="width: 10%">
      <col style="width: 10%">
      <col style="width: 25%">

       <thead class="thead-light">
         <th scope="col"><div class="btn">View / Edit<br>Delete</div></th>
         <?= MyFormGeneration::generateColumnHeader("statuses", "Status ID",
           $id_sort_param, $_SESSION["currentSort"], "id_asc", "id_desc"); ?>

         <?= MyFormGeneration::generateColumnHeader("statuses", "Status",
           $status_sort_param, $_SESSION["currentSort"], "status_asc", "status_desc"); ?>

         <?= MyFormGeneration::generateColumnHeader("statuses", "Expected Duration",
           $ed_sort_param, $_SESSION["currentSort"], "ed_asc", "ed_desc"); ?>

         <?= MyFormGeneration::generateColumnHeader("statuses", "Default",
           $def_sort_param, $_SESSION["currentSort"], "def_asc", "def_desc"); ?>

         <th scope="col" class="align-top"><div class="btn">Instructions</div></th>
       </thead>

       <tbody>
         <?php if (! empty($statuses) && is_array($statuses)) : ?>
           <?php foreach ($statuses as $status): ?>
             <tr>
               <?= MyFormGeneration::generateIndexRowButtons("statuses", $page, $status->StatusID, in_groups(['pubsAdmin','pubsRC']), in_groups(['pubsAdmin','pubsRC']), true, true, false, true); ?>
               <td><?= $status->StatusID; ?></td>
               <td><?= $status->Status; ?></td>
               <td><?= $status->ExpectedDuration; ?></td>
               <td><?= $status->DefaultStatus == 0 ? "No" : "Yes" ?></td>
               <td><?= $status->Instructions; ?></td>
             </tr>
           <?php endforeach; ?>
         <?php endif ?>
       </tbody>
    </table>
  </div>

  <?= MyFormGeneration::generateRowsPerPage($_SESSION["rowsPerPage"], $links); ?>

</div>
