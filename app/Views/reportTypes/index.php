<?php
  // Use MyFormGeneration
  use App\Libraries\MyFormGeneration;

  // Calculate sort parameters
  $id_sort_param = "id_asc";
  $rt_sort_param = "rt_asc";
  $abbr_sort_param = "abbr_asc";
  if ($_SESSION["currentSort"] == "id_asc") {
    $id_sort_param = "id_desc";
  } elseif ($_SESSION["currentSort"] == "rt_asc") {
    $rt_sort_param = "rt_desc";
  } elseif ($_SESSION["currentSort"] == "abbr_asc") {
    $abbr_sort_param = "abbr_desc";
  }
?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIndexSearch("reportTypes", csrf_field()); ?>

  <a class="btn btn-primary my-3" href="<?= base_url() ?>/reportTypes/new/<?= $page ?>">Create Report Type</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 15%">
      <col style="width: 17%">
      <col style="width: 51%">
      <col style="width: 17%">

       <thead class="thead-light">
        <th scope="col"><div class="btn">Edit | Delete</div></th>
         <?= MyFormGeneration::generateColumnHeader("reportTypes", "Report Type ID",
           $id_sort_param, $_SESSION["currentSort"], "id_asc", "id_desc"); ?>

         <?= MyFormGeneration::generateColumnHeader("reportTypes", "Report Type",
           $rt_sort_param, $_SESSION["currentSort"], "rt_asc", "rt_desc"); ?>

         <?= MyFormGeneration::generateColumnHeader("reportTypes", "Abbreviation",
           $abbr_sort_param, $_SESSION["currentSort"], "abbr_asc", "abbr_desc"); ?>
       </thead>

       <tbody>
         <?php if (! empty($reportTypes) && is_array($reportTypes)) : ?>
           <?php foreach ($reportTypes as $reportType): ?>
             <tr>
               <?= MyFormGeneration::generateIndexRowButtons("reportTypes", $page, $reportType->ReportTypeID, in_groups(['pubsAdmin','pubsRC']), in_groups(['pubsAdmin','pubsRC']), true, true, false, true); ?>
               <td><?= $reportType->ReportTypeID; ?></td>
               <td><?= $reportType->ReportType; ?></td>
               <td><?= $reportType->Abbreviation; ?></td>
             </tr>
           <?php endforeach; ?>
         <?php endif ?>
       </tbody>
    </table>
  </div>

  <?= MyFormGeneration::generateRowsPerPage($_SESSION["rowsPerPage"], $links); ?>
</div>
