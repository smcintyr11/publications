<?php
  // Use MyFormGeneration
  use App\Libraries\MyFormGeneration;

  // Calculate sort parameters
  $rn_sort_param = "rn_asc";
  $rt_sort_param = "rt_asc";
  $status_sort_param = "status_asc";
  $dd_sort_param = "dd_asc";
  $at_sort_param = "at_asc";

  if ($_SESSION["currentSort"] == "rn_asc") {
    $rn_sort_param = "rn_desc";
  } elseif ($_SESSION["currentSort"] == "rt_asc") {
    $rt_sort_param = "rt_desc";
  } elseif ($_SESSION["currentSort"] == "status_asc") {
    $status_sort_param = "status_desc";
  } elseif ($_SESSION["currentSort"] == "pa_asc") {
    $pa_sort_param = "pa_desc";
  } elseif ($_SESSION["currentSort"] == "dd_asc") {
    $dd_sort_param = "dd_desc";
  } elseif ($_SESSION["currentSort"] == "at_asc") {
    $at_sort_param = "at_desc";
  }
?>


<div class="container my-3 my-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-inline" action="/publications/index/1" method="post" id="frmSearch">
    <?= csrf_field() ?>
    <input class="form-control mr-2" type="text" name="filter" placeholder="Search">
    <button class="btn btn-success m-1" type="submit">Search</button>
    <a class="btn btn-info m-1" href="/publications/index/1?filter=">Reset</a>
  </form>

  <a class="btn btn-primary my-3" href="/publications/new/<?= $page ?>">Create Publication</a>

  <div class="table-fluid">
    <table class="table table-bordered">


      <thead class="thead-light">
        <th scope="col" class="align-top"><div class="btn">Edit / Delete</div></th>

        <?= MyFormGeneration::generateColumnHeader("publications", "Report Number",
          $rn_sort_param, $_SESSION["currentSort"], "rn_asc", "rn_desc"); ?>

        <?= MyFormGeneration::generateColumnHeaderWithFilter("publications", "Report Type",
          $rt_sort_param, $_SESSION["currentSort"], "rt_asc", "rt_desc",
          "reportTypeID", set_value('reportTypeID'), "---", "frmSearch", $reportTypes); ?>

        <?= MyFormGeneration::generateColumnHeaderWithFilter("publications", "Status",
          $status_sort_param, $_SESSION["currentSort"], "status_asc", "status_desc",
          "statusID", set_value('statusID'), "---", "frmSearch", $statuses); ?>

        <?= MyFormGeneration::generateColumnHeader("publications", "Due Date",
          $dd_sort_param, $_SESSION["currentSort"], "dd_asc", "dd_desc"); ?>

          <?= MyFormGeneration::generateColumnHeader("publications", "Assigned To",
            $at_sort_param, $_SESSION["currentSort"], "at_asc", "at_desc"); ?>

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
              <?= MyFormGeneration::generateIndexRowButtons("publications", $page, $publication->PublicationID, true, true, false, false, $publication->RushPublication); ?>
              <td><?= $publication->ReportNumber; ?></td>
              <td><?= $publication->ReportType; ?></td>
              <td><?= $publication->Status; ?></td>
              <td><?= $publication->StatusDueDate; ?></td>
              <td><?= $publication->StatusPerson; ?></td>
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
