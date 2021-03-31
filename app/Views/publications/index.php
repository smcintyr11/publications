<div style="width: 2500px">
<div class="container-fluid my-5 my-5">
  <h1><?= esc($title); ?></h1>

  <form class="form-inline" action="/publications/index/1" method="post" id="frmSearch">
    <?= csrf_field() ?>
    <input class="form-control mr-sm-2" type="text" name="filter" placeholder="Search">
    <button class="btn btn-success m-1" type="submit">Search</button>
    <a class="btn btn-info m-1" href="/publications/index/1?filter=">Reset</a>
  </form>

  <a class="btn btn-primary my-3" href="/publications/new/<?= $page ?>">Create Publication</a>

  <div class="table-responsive">
    <table class="table table-striped table-bordered">
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
      <col style="width: 7%">

      <?php
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

      <thead class="thead-light">
        <th scope="col">
          <a class="btn btn-link" href="/publications/index/1?sort=<?= $id_sort_param ?>">Publication ID</a>
          <?php
            if ($_SESSION["currentSort"] == "id_asc") {
              echo("<i class=\"fas fa-sort-up\"></i>");
            } elseif ($_SESSION["currentSort"] == "id_desc") {
              echo ("<i class=\"fas fa-sort-down\"></i>");
            }
           ?>
        </th>
        <th scope="col">
          <a class="btn btn-link" href="/publications/index/1?sort=<?= $rn_sort_param ?>">Report Number</a>
          <?php
            if ($_SESSION["currentSort"] == "rn_asc") {
              echo("<i class=\"fas fa-sort-up\"></i>");
            } elseif ($_SESSION["currentSort"] == "rn_desc") {
              echo ("<i class=\"fas fa-sort-down\"></i>");
            }
           ?>
        </th>
        <th scope="col">
          <a class="btn btn-link" href="/publications/index/1?sort=<?= $pt_sort_param ?>">Primary title</a>
          <?php
            if ($_SESSION["currentSort"] == "pt_asc") {
              echo("<i class=\"fas fa-sort-up\"></i>");
            } elseif ($_SESSION["currentSort"] == "pt_desc") {
              echo ("<i class=\"fas fa-sort-down\"></i>");
            }
           ?>
        </th>
        <th scope="col">
          <a class="btn btn-link" href="/publications/index/1?sort=<?= $abbr_sort_param ?>">Report Type</a>
          <?php
            if ($_SESSION["currentSort"] == "abbr_asc") {
              echo("<i class=\"fas fa-sort-up\"></i>");
            } elseif ($_SESSION["currentSort"] == "abbr_desc") {
              echo ("<i class=\"fas fa-sort-down\"></i>");
            }
           ?>
        </th>
        <th scope="col">
          <a class="btn btn-link" href="/publications/index/1?sort=<?= $cc_sort_param ?>">Cost Centre</a>
          <?php
            if ($_SESSION["currentSort"] == "cc_asc") {
              echo("<i class=\"fas fa-sort-up\"></i>");
            } elseif ($_SESSION["currentSort"] == "cc_desc") {
              echo ("<i class=\"fas fa-sort-down\"></i>");
            }
           ?>
        </th>
        <th scope="col">
          <a class="btn btn-link" href="/publications/index/1?sort=<?= $pc_sort_param ?>">Project Code</a>
          <?php
            if ($_SESSION["currentSort"] == "pc_asc") {
              echo("<i class=\"fas fa-sort-up\"></i>");
            } elseif ($_SESSION["currentSort"] == "pc_desc") {
              echo ("<i class=\"fas fa-sort-down\"></i>");
            }
           ?>
        </th>
        <th scope="col">
          <a class="btn btn-link" href="/publications/index/1?sort=<?= $ipd_sort_param ?>">IPD Number</a>
          <?php
            if ($_SESSION["currentSort"] == "ipd_asc") {
              echo("<i class=\"fas fa-sort-up\"></i>");
            } elseif ($_SESSION["currentSort"] == "ipd_desc") {
              echo ("<i class=\"fas fa-sort-down\"></i>");
            }
           ?>
        </th>
        <th scope="col">
          <a class="btn btn-link" href="/publications/index/1?sort=<?= $xref_sort_param ?>">Cross Reference Number</a>
          <?php
            if ($_SESSION["currentSort"] == "xref_asc") {
              echo("<i class=\"fas fa-sort-up\"></i>");
            } elseif ($_SESSION["currentSort"] == "xref_desc") {
              echo ("<i class=\"fas fa-sort-down\"></i>");
            }
           ?>
        </th>


        <th scope="col">
          <a class="btn btn-link" href="/publications/index/1?sort=<?= $status_sort_param ?>">Status</a>
          <?php
            if ($_SESSION["currentSort"] == "status_asc") {
              echo("<i class=\"fas fa-sort-up\"></i>");
            } elseif ($_SESSION["currentSort"] == "status_desc") {
              echo ("<i class=\"fas fa-sort-down\"></i>");
            }
           ?>
        </th>
        <th scope="col">
          <a class="btn btn-link" href="/publications/index/1?sort=<?= $pa_sort_param ?>">Publication Authors</a>
          <?php
            if ($_SESSION["currentSort"] == "pa_asc") {
              echo("<i class=\"fas fa-sort-up\"></i>");
            } elseif ($_SESSION["currentSort"] == "pa_desc") {
              echo ("<i class=\"fas fa-sort-down\"></i>");
            }
           ?>
        </th>
        <th scope="col">
          <a class="btn btn-link" href="/publications/index/1?sort=<?= $pr_sort_param ?>">Publication Reviewers</a>
          <?php
            if ($_SESSION["currentSort"] == "pr_asc") {
              echo("<i class=\"fas fa-sort-up\"></i>");
            } elseif ($_SESSION["currentSort"] == "pr_desc") {
              echo ("<i class=\"fas fa-sort-down\"></i>");
            }
           ?>
        </th>
        <th scope="col"></th>
      </thead>

      <tbody>
        <?php if (! empty($publications) && is_array($publications)) : ?>
          <?php foreach ($publications as $publication): ?>
            <tr>
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
              <td><a class="btn btn-link" href="/publications/edit/<?= $page ?>/<?= $publication->PublicationID ?>">Edit</a>
                |<a class="btn btn-link" href="/publications/delete/1/<?= $publication->PublicationID ?>">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif ?>
      </tbody>

    </table>
  </div>

  <div class="row">
    <div class="col-1 btn">Page:</div>
    <div class="col-9"><?= $links ?></div>
    <div class="col-1 btn  text-right">Rows per page:</div>
    <div class="col-1">
      <select class="form-control mr-sm-2" name="rowsPerPage" id="rowsPerPage" form="frmSearch" onchange="this.form.submit()">
        <option value=25 <?= ($_SESSION["rowsPerPage"] == 25) ? ' selected' : '' ?> >25</option>
        <option value=50 <?= ($_SESSION["rowsPerPage"] == 50) ? ' selected' : '' ?> >50</option>
        <option value=100 <?= ($_SESSION["rowsPerPage"] == 100) ? ' selected' : '' ?> >100</option>
      </select>
    </div>
  </div>
</div>
</div>
