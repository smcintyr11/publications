<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIndexSearch("linkTypes", csrf_field()); ?>

  <a class="btn btn-primary my-3" href="/linkTypes/new/<?= $page ?>">Create Link Type</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 15%">
      <col style="width: 70%">
      <col style="width: 15%">

      <?php
        $id_sort_param = "id_asc";
        $lt_sort_param = "lt_asc";
        if ($_SESSION["currentSort"] == "id_asc") {
          $id_sort_param = "id_desc";
        } elseif ($_SESSION["currentSort"] == "lt_asc") {
          $lt_sort_param  = "lt_desc";
        }
       ?>

       <thead class="thead-light">
         <?= MyFormGeneration::generateColumnHeader("linkTypes", "Link Type ID",
           $id_sort_param, $_SESSION["currentSort"], "id_asc", "id_desc"); ?>

         <?= MyFormGeneration::generateColumnHeader("linkTypes", "Link Type",
           $lt_sort_param, $_SESSION["currentSort"], "lt_asc", "lt_desc"); ?>

         <th scope="col"></th>
       </thead>

       <tbody>
         <?php if (! empty($linkTypes) && is_array($linkTypes)) : ?>
           <?php foreach ($linkTypes as $linkType): ?>
             <tr>
               <td><?= $linkType->LinkTypeID; ?></td>
               <td><?= $linkType->LinkType; ?></td>
               <td><a class="btn btn-link" href="/linkTypes/edit/<?= $page ?>/<?= $linkType->LinkTypeID ?>">Edit</a>
                 |<a class="btn btn-link" href="/linkTypes/delete/1/<?= $linkType->LinkTypeID ?>">Delete</a>
               </td>
             </tr>
           <?php endforeach; ?>
         <?php endif ?>
       </tbody>
    </table>
  </div>

  <?= MyFormGeneration::generateRowsPerPage($_SESSION["rowsPerPage"], $links); ?>
  
</div>
