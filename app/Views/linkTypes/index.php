<?php
  // Use MyFormGeneration
  use App\Libraries\MyFormGeneration;

  // Calculate sort parameters
  $id_sort_param = "id_asc";
  $lt_sort_param = "lt_asc";
  if ($_SESSION["currentSort"] == "id_asc") {
    $id_sort_param = "id_desc";
  } elseif ($_SESSION["currentSort"] == "lt_asc") {
    $lt_sort_param  = "lt_desc";
  }
?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIndexSearch("linkTypes", csrf_field()); ?>

  <a class="btn btn-primary my-3" href="<?= base_url() ?>/linkTypes/new/<?= $page ?>">Create Link Type</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 15%">
      <col style="width: 15%">
      <col style="width: 70%">

       <thead class="thead-light">
         <th scope="col"><div class="btn">View / Edit<br>Delete</div></th>
         <?= MyFormGeneration::generateColumnHeader("linkTypes", "Link Type ID",
           $id_sort_param, $_SESSION["currentSort"], "id_asc", "id_desc"); ?>

         <?= MyFormGeneration::generateColumnHeader("linkTypes", "Link Type",
           $lt_sort_param, $_SESSION["currentSort"], "lt_asc", "lt_desc"); ?>
       </thead>

       <tbody>
         <?php if (! empty($linkTypes) && is_array($linkTypes)) : ?>
           <?php foreach ($linkTypes as $linkType): ?>
             <tr>
               <?= MyFormGeneration::generateIndexRowButtons("linkTypes", $page, $linkType->LinkTypeID, in_groups(['pubsAdmin','pubsRC']), in_groups(['pubsAdmin','pubsRC']), true, true, false, true); ?>
               <td><?= $linkType->LinkTypeID; ?></td>
               <td><?= $linkType->LinkType; ?></td>
             </tr>
           <?php endforeach; ?>
         <?php endif ?>
       </tbody>
    </table>
  </div>

  <?= MyFormGeneration::generateRowsPerPage($_SESSION["rowsPerPage"], $links); ?>

</div>
