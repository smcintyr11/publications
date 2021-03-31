<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-inline" action="/reportTypes/index/1" method="post" id="frmSearch">
    <?= csrf_field() ?>
    <input class="form-control mr-2" type="text" name="filter" placeholder="Search">
    <button class="btn btn-success m-1" type="submit">Search</button>
    <a class="btn btn-info m-1" href="/reportTypes/index/1?filter=">Reset</a>
  </form>

  <a class="btn btn-primary my-3" href="/reportTypes/new/<?= $page ?>">Create Report Type</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 17%">
      <col style="width: 51%">
      <col style="width: 17%">
      <col style="width: 15%">

      <?php
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

       <thead class="thead-light">
         <th scope="col">
           <a class="btn btn-link" href="/reportTypes/index/1?sort=<?= $id_sort_param ?>">Report Type ID</a>
           <?php
             if ($_SESSION["currentSort"] == "id_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($_SESSION["currentSort"] == "id_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col">
           <a class="btn btn-link" href="/reportTypes/index/1?sort=<?= $rt_sort_param ?>">Report Type</a>
           <?php
             if ($_SESSION["currentSort"] == "rt_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($_SESSION["currentSort"] == "rt_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col">
           <a class="btn btn-link" href="/reportTypes/index/1?sort=<?= $abbr_sort_param ?>">Abbreviation</a>
           <?php
             if ($_SESSION["currentSort"] == "abbr_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($_SESSION["currentSort"] == "abbr_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col"></th>
       </thead>

       <tbody>
         <?php if (! empty($reportTypes) && is_array($reportTypes)) : ?>
           <?php foreach ($reportTypes as $reportType): ?>
             <tr>
               <td><?= $reportType->ReportTypeID; ?></td>
               <td><?= $reportType->ReportType; ?></td>
               <td><?= $reportType->Abbreviation; ?></td>
               <td><a class="btn btn-link" href="/reportTypes/edit/<?= $page ?>/<?= $reportType->ReportTypeID ?>">Edit</a>
                 |<a class="btn btn-link" href="/reportTypes/delete/1/<?= $reportType->ReportTypeID ?>">Delete</a>
               </td>
             </tr>
           <?php endforeach; ?>
         <?php endif ?>
       </tbody>
    </table>
  </div>

  <div class="row">
    <div class="col-1 btn">Page:</div>
    <div class="col-7"><?= $links ?></div>
    <div class="col-2 btn  text-right">Rows per page:</div>
    <div class="col-2">
      <select class="form-control mr-2" name="rowsPerPage" id="rowsPerPage" form="frmSearch" onchange="this.form.submit()">
        <option value=25 <?= ($_SESSION["rowsPerPage"] == 25) ? ' selected' : '' ?> >25</option>
        <option value=50 <?= ($_SESSION["rowsPerPage"] == 50) ? ' selected' : '' ?> >50</option>
        <option value=100 <?= ($_SESSION["rowsPerPage"] == 100) ? ' selected' : '' ?> >100</option>
      </select>
    </div>
  </div>
</div>
