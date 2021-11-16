<?php
  // Use MyFormGeneration
  use App\Libraries\MyFormGeneration;

  // Calculate sort parameters
  $id_sort_param = "id_asc";
  $lname_sort_param = "lname_asc";
  $fname_sort_param = "fname_asc";
  $dname_sort_param = "dname_asc";
  $org_sort_param = "org_asc";
  if ($_SESSION["currentSort"] == "id_asc") {
    $id_sort_param = "id_desc";
  } elseif ($_SESSION["currentSort"] == "lname_asc") {
    $lname_sort_param = "lname_desc";
  } elseif ($_SESSION["currentSort"] == "fname_asc") {
    $fname_sort_param = "fname_desc";
  } elseif ($_SESSION["currentSort"] == "dname_asc") {
    $dname_sort_param = "dname_desc";
  } elseif ($_SESSION["currentSort"] == "org_asc") {
    $org_sort_param = "org_desc";
  }
?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIndexSearch("people", csrf_field()); ?>

  <a class="btn btn-primary my-3" href="<?= base_url() ?>/people/new/<?= $page ?>">Create Person</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 15%">
      <col style="width: 15%">
      <col style="width: 17.5%">
      <col style="width: 17.5%">
      <col style="width: 17.5%">
      <col style="width: 17.5%">


       <thead class="thead-light">
         <th scope="col"><div class="btn">Edit | Delete</div></th>
         <?= MyFormGeneration::generateColumnHeader("people", "Person ID",
           $id_sort_param, $_SESSION["currentSort"], "id_asc", "id_desc"); ?>

         <?= MyFormGeneration::generateColumnHeader("people", "Display Name",
           $dname_sort_param, $_SESSION["currentSort"], "dname_asc", "dname_desc"); ?>

         <?= MyFormGeneration::generateColumnHeader("people", "Last Name",
           $lname_sort_param, $_SESSION["currentSort"], "lname_asc", "lname_desc"); ?>

         <?= MyFormGeneration::generateColumnHeader("people", "First Name",
           $fname_sort_param, $_SESSION["currentSort"], "fname_asc", "fname_desc"); ?>

           <?= MyFormGeneration::generateColumnHeader("people", "Organization",
             $org_sort_param, $_SESSION["currentSort"], "org_asc", "org_desc"); ?>
       </thead>

       <tbody>
         <?php if (! empty($people) && is_array($people)) : ?>
           <?php foreach ($people as $person): ?>
             <tr>
               <?= MyFormGeneration::generateIndexRowButtons("people", $page, $person->PersonID, in_groups(['pubsAdmin','pubsRC']), in_groups(['pubsAdmin','pubsRC']), true, true, false, true); ?>
               <td><?= $person->PersonID ?></td>
               <td><?= $person->DisplayName ?></td>
               <td><?= $person->LastName ?></td>
               <td><?= $person->FirstName ?></td>
               <td><?= $person->Organization ?></td>
             </tr>
           <?php endforeach; ?>
         <?php endif ?>
       </tbody>
    </table>
  </div>

  <?= MyFormGeneration::generateRowsPerPage($_SESSION["rowsPerPage"], $links); ?>

</div>
