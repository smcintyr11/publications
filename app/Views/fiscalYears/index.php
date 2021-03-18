<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-inline" action="/fiscalYears/index/1" method="post" id="frmSearch">
    <?= csrf_field() ?>
    <input class="form-control mr-sm-2" type="text" name="filter" placeholder="Search">
    <button class="btn btn-success m-1" type="submit">Search</button>
    <a class="btn btn-info m-1" href="/fiscalYears/index/1?filter=">Reset</a>
  </form>

  <a class="btn btn-primary my-3" href="/fiscalYears/new/<?= $page ?>">Create Fiscal Year</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 15%">
      <col style="width: 70%">
      <col style="width: 15%">

      <?php
        $id_sort_param = "id_asc";
        $fy_sort_param = "fy_asc";
        if ($_SESSION["currentSort"] == "id_asc") {
          $id_sort_param = "id_desc";
        } elseif ($_SESSION["currentSort"] == "fy_asc") {
          $fy_sort_param = "fy_desc";
        }
       ?>

       <thead class="thead-light">
         <th scope="col">
           <a class="btn btn-link" href="/fiscalYears/index/1?sort=<?= $id_sort_param ?>">Fiscal Year ID</a>
           <?php
             if ($_SESSION["currentSort"] == "id_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($_SESSION["currentSort"] == "id_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col">
           <a class="btn btn-link" href="/fiscalYears/index/1?sort=<?= $fy_sort_param ?>">Fiscal Year</a>
           <?php
             if ($_SESSION["currentSort"] == "fy_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($_SESSION["currentSort"] == "fy_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col"></th>
       </thead>

       <tbody>
         <?php if (! empty($fiscalYears) && is_array($fiscalYears)) : ?>
           <?php foreach ($fiscalYears as $fiscalYear): ?>
             <tr>
               <td><?= $fiscalYear->FiscalYearID; ?></td>
               <td><?= $fiscalYear->FiscalYear; ?></td>
               <td><a class="btn btn-link" href="/fiscalYears/edit/<?= $page ?>/<?= $fiscalYear->FiscalYearID ?>">Edit</a>
                 |<a class="btn btn-link" href="/fiscalYears/delete/1/<?= $fiscalYear->FiscalYearID ?>">Delete</a>
               </td>
             </tr>
           <?php endforeach; ?>
         <?php endif ?>
       </tbody>
    </table>
  </div>

  <div class="row">
    <div class="col-lg-1 btn">Page:</div>
    <div class="col-lg-7"><?= $links ?></div>
    <div class="col-lg-2 btn  text-right">Rows per page:</div>
    <div class="col-lg-2">
      <select class="form-control mr-sm-2" name="rowsPerPage" id="rowsPerPage" form="frmSearch" onchange="this.form.submit()">
        <option value=25 <?= ($_SESSION["rowsPerPage"] == 25) ? ' selected' : '' ?> >25</option>
        <option value=50 <?= ($_SESSION["rowsPerPage"] == 50) ? ' selected' : '' ?> >50</option>
        <option value=100 <?= ($_SESSION["rowsPerPage"] == 100) ? ' selected' : '' ?> >100</option>
      </select>
    </div>
  </div>
</div>
