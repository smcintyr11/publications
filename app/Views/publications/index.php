<?php
  // Use MyFormGeneration
  use App\Libraries\MyFormGeneration;

  // Calculate sort parameters
  $id_sort_param = "id_asc";
  $cc_sort_param = "cc_asc";
  $pc_sort_param = "pc_asc";
  $ipd_sort_param = "ipd_asc";
  $xref_sort_param = "xref_asc";
  $rn_sort_param = "rn_asc";
  $abbr_sort_param = "abbr_asc";
  $pt_sort_param = "pt_asc";
  $status_sort_param = "status_asc";
  $pa_sort_param = "pa_asc";
  $pr_sort_param = "pr_asc";
  if ($_SESSION["currentSort"] == "id_asc") {
    $id_sort_param = "id_desc";
  } elseif ($_SESSION["currentSort"] == "cc_asc") {
    $cc_sort_param = "cc_desc";
  } elseif ($_SESSION["currentSort"] == "pc_asc") {
    $pc_sort_param = "pc_desc";
  } elseif ($_SESSION["currentSort"] == "ipd_asc") {
    $ipd_sort_param = "ipd_desc";
  } elseif ($_SESSION["currentSort"] == "xref_asc") {
    $xref_sort_param = "xref_desc";
  } elseif ($_SESSION["currentSort"] == "rn_asc") {
    $rn_sort_param = "rn_desc";
  } elseif ($_SESSION["currentSort"] == "abbr_asc") {
    $abbr_sort_param = "abbr_desc";
  } elseif ($_SESSION["currentSort"] == "pt_asc") {
    $pt_sort_param = "pt_desc";
  } elseif ($_SESSION["currentSort"] == "status_asc") {
    $status_sort_param = "status_desc";
  } elseif ($_SESSION["currentSort"] == "pa_asc") {
    $pa_sort_param = "pa_desc";
  } elseif ($_SESSION["currentSort"] == "pr_asc") {
    $pr_sort_param = "pr_desc";
  }
?>

<div style="width: 2500px">
<div class="container-fluid my-5 my-5">
  <h1><?= esc($title); ?></h1>

  <form class="form-inline" action="/publications/index/1" method="post" id="frmSearch">
    <?= csrf_field() ?>
    <input class="form-control mr-2" type="text" name="filter" placeholder="Search">
    <button class="btn btn-success m-1" type="submit">Search</button>
    <a class="btn btn-info m-1" href="/publications/index/1?filter=">Reset</a>
  </form>

  <a class="btn btn-primary my-3" href="/publications/new/<?= $page ?>">Create Publication</a>

  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <col style="width: 7%">
      <col style="width: 7%">
      <col style="width: 7%">
      <col style="width: 17%">
      <col style="width: 7%">
      <col style="width: 7%">
      <col style="width: 7%">
      <col style="width: 7%">
      <col style="width: 7%">
      <col style="width: 7%">
      <col style="width: 10%">
      <col style="width: 10%">

      <thead class="thead-light">
        <th scope="col"><div class="btn">Edit / Delete</div></th>
        <?= MyFormGeneration::generateColumnHeader("publications", "Publication ID",
          $id_sort_param, $_SESSION["currentSort"], "id_asc", "id_desc"); ?>

        <?= MyFormGeneration::generateColumnHeader("publications", "Report Number",
          $rn_sort_param, $_SESSION["currentSort"], "rn_asc", "rn_desc"); ?>

        <?= MyFormGeneration::generateColumnHeader("publications", "Primary Title",
          $pt_sort_param, $_SESSION["currentSort"], "pt_asc", "pt_desc"); ?>

        <?= MyFormGeneration::generateColumnHeader("publications", "Report Type",
          $abbr_sort_param, $_SESSION["currentSort"], "abbr_asc", "abbr_desc"); ?>

        <?= MyFormGeneration::generateColumnHeader("publications", "Cost Centre",
          $cc_sort_param, $_SESSION["currentSort"], "cc_asc", "cc_desc"); ?>

        <?= MyFormGeneration::generateColumnHeader("publications", "Project Code",
          $pc_sort_param, $_SESSION["currentSort"], "pc_asc", "pc_desc"); ?>

        <?= MyFormGeneration::generateColumnHeader("publications", "IPD Number",
          $ipd_sort_param, $_SESSION["currentSort"], "ipd_asc", "ipd_desc"); ?>

        <?= MyFormGeneration::generateColumnHeader("publications", "Cross Reference Number",
          $xref_sort_param, $_SESSION["currentSort"], "xref_asc", "xref_desc"); ?>

        <?= MyFormGeneration::generateColumnHeader("publications", "Status",
          $status_sort_param, $_SESSION["currentSort"], "status_asc", "status_desc"); ?>

        <?= MyFormGeneration::generateColumnHeader("publications", "Authors",
          $pa_sort_param, $_SESSION["currentSort"], "pa_asc", "pa_desc"); ?>

        <?= MyFormGeneration::generateColumnHeader("publications", "Reviewers",
          $pr_sort_param, $_SESSION["currentSort"], "pr_asc", "pr_desc"); ?>
      </thead>

      <tbody>
        <?php if (! empty($publications) && is_array($publications)) : ?>
          <?php foreach ($publications as $publication): ?>
            <tr>
              <?= MyFormGeneration::generateIndexRowButtons("publications", $page, $publication->PublicationID); ?>
              <td><?= $publication->PublicationID; ?></td>
              <td><?= $publication->ReportNumber; ?></td>
              <td><?= $publication->PrimaryTitle; ?></td>
              <td><?= $publication->Abbreviation; ?></td>
              <td><?= $publication->CostCentre; ?></td>
              <td><?= $publication->ProjectCode; ?></td>
              <td><?= $publication->IPDNumber; ?></td>
              <td><?= $publication->CrossReferenceNumber; ?></td>
              <td><?= $publication->Status; ?></td>
              <td><?= $publication->PublicationAuthors; ?></td>
              <td><?= $publication->PublicationReviewers; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif ?>
      </tbody>

    </table>
  </div>

  <?= MyFormGeneration::generateRowsPerPage($_SESSION["rowsPerPage"], $links); ?>
</div>
</div>
