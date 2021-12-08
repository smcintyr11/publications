<?php
  // Use MyFormGeneration
  use App\Libraries\MyFormGeneration;

  // Calculate sort parameters
  $cc_sort_param = "cc_asc";
  $pc_sort_param = "pc_asc";
  $rn_sort_param = "rn_asc";
  $rt_sort_param = "rt_asc";
  $pt_sort_param = "pt_asc";
  $status_sort_param = "status_asc";
  $pa_sort_param = "pa_asc";
  $dd_sort_param = "dd_asc";

  if ($_SESSION["currentSort"] == "cc_asc") {
    $cc_sort_param = "cc_desc";
  } elseif ($_SESSION["currentSort"] == "pc_asc") {
    $pc_sort_param = "pc_desc";
  } elseif ($_SESSION["currentSort"] == "rn_asc") {
    $rn_sort_param = "rn_desc";
  } elseif ($_SESSION["currentSort"] == "rt_asc") {
    $rt_sort_param = "rt_desc";
  } elseif ($_SESSION["currentSort"] == "pt_asc") {
    $pt_sort_param = "pt_desc";
  } elseif ($_SESSION["currentSort"] == "status_asc") {
    $status_sort_param = "status_desc";
  } elseif ($_SESSION["currentSort"] == "pa_asc") {
    $pa_sort_param = "pa_desc";
  } elseif ($_SESSION["currentSort"] == "dd_asc") {
    $dd_sort_param = "dd_desc";
  }
?>


<div class="container my-3 my-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-inline" action="<?= base_url() ?>/publications/indexDetailed/1" method="post" id="frmSearch">
    <?= csrf_field() ?>
    <input class="form-control mr-2" type="text" id="filter" name="filter" placeholder="Search" value="<?= set_value('filter', '') ?>" >
    <button class="btn btn-success m-1" type="submit">Search</button>
    <a class="btn btn-info m-1" href="<?= base_url() ?>/publications/indexDetailed/1?filter=">Reset</a>
  </form>

  <a class="btn btn-primary my-3" href="<?= base_url() ?>/publications/new/<?= $page ?>">Create Publication</a>

  <div class="table-fluid">
    <table class="table table-bordered">

      <thead class="thead-light">
        <th scope="col" class="align-top"><div class="btn">View / Edit<br>Delete / Rush</div></th>

        <?= MyFormGeneration::generateColumnHeader("publications", "Report Number",
          $rn_sort_param, $_SESSION["currentSort"], "rn_asc", "rn_desc"); ?>

        <?= MyFormGeneration::generateColumnHeaderWithFilter("publications", "Report Type",
          $rt_sort_param, $_SESSION["currentSort"], "rt_asc", "rt_desc",
          "reportTypeID", set_value('reportTypeID'), "---", "frmSearch", $reportTypes); ?>

        <?= MyFormGeneration::generateColumnHeader("publications", "Primary Title",
          $pt_sort_param, $_SESSION["currentSort"], "pt_asc", "pt_desc"); ?>

        <?= MyFormGeneration::generateColumnHeader("publications", "Authors",
          $pa_sort_param, $_SESSION["currentSort"], "pa_asc", "pa_desc"); ?>

        <?= MyFormGeneration::generateColumnHeaderWithFilter("publications", "Status",
          $status_sort_param, $_SESSION["currentSort"], "status_asc", "status_desc",
          "statusID", set_value('statusID'), "---", "frmSearch", $statuses); ?>

        <?= MyFormGeneration::generateColumnHeader("publications", "Due Date",
          $dd_sort_param, $_SESSION["currentSort"], "dd_asc", "dd_desc"); ?>

        <?= MyFormGeneration::generateColumnHeaderWithFilter("publications", "Cost Centre",
          $cc_sort_param, $_SESSION["currentSort"], "cc_asc", "cc_desc",
          "costCentreID", set_value('costCentreID'), "---", "frmSearch", $costCentres); ?>

        <?= MyFormGeneration::generateColumnHeader("publications", "Project Code",
          $pc_sort_param, $_SESSION["currentSort"], "pc_asc", "pc_desc"); ?>

      </thead>


      <tbody>
        <?php if (! empty($publications) && is_array($publications)) : ?>
          <?php foreach ($publications as $publication): ?>
            <?php
              $highlighting = "";
              if ($publication->DueDateDelta < 3) {
                $highlighting= 'text-danger';
              } else if ($publication->DueDateDelta < 5) {
                $highlighting= 'text-primary';
              }
            ?>

            <tr class="<?= $highlighting ?>" >
              <?= MyFormGeneration::generateIndexRowButtons("publications", $page, $publication->PublicationID, true, in_groups(['pubsAdmin','pubsRC']), true, true, $publication->RushPublication, true); ?>
              <td><?= $publication->ReportNumber; ?></td>
              <td><?= $publication->ReportType; ?></td>
              <td><?= $publication->PrimaryTitle; ?></td>
              <td><?= $publication->PublicationAuthors; ?></td>
              <td><?= $publication->Status; ?></td>
              <td><?= $publication->StatusDueDate; ?></td>
              <td><?= $publication->CostCentre; ?></td>
              <td><?= $publication->ProjectCode; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif ?>
      </tbody>

    </table>

    <table>
      <thead>
        <th class="font-weight-bold text-center">Legend</th>
      </thead>
      <tr>
        <td class="px-5"><i class="btn btn-warning m-1 far fa-star"></i></td>
        <td>Rush publication</td>
      </tr>
      <tr>
        <td class='text-danger px-5'>Red Text</td>
        <td>Status Due Date < 3 days away</td>
      </tr>
      <tr>
        <td class='text-primary px-5'>Blue Text</td>
        <td>Status Due Date < 5 days away</td>
      </tr>
    </table>
  </div>

  <?= MyFormGeneration::generateRowsPerPage($_SESSION["rowsPerPage"], $links); ?>
</div>
