<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-inline" action="/people/index/1" method="post" id="frmSearch">
    <?= csrf_field() ?>
    <input class="form-control mr-sm-2" type="text" name="filter" placeholder="Search">
    <button class="btn btn-success m-1" type="submit">Search</button>
    <a class="btn btn-info m-1" href="/people/index/1?filter=">Reset</a>
  </form>

  <a class="btn btn-primary my-3" href="/people/new/<?= $page ?>">Create Person</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 15%">
      <col style="width: 17.5%">
      <col style="width: 17.5%">
      <col style="width: 17.5%">
      <col style="width: 17.5%">
      <col style="width: 15%">

      <?php
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

       <thead class="thead-light">
         <th scope="col">
           <a class="btn btn-link" href="/people/index/1?sort=<?= $id_sort_param ?>">Person ID</a>
           <?php
             if ($_SESSION["currentSort"] == "id_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($_SESSION["currentSort"] == "id_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col">
           <a class="btn btn-link" href="/people/index/1?sort=<?= $dname_sort_param ?>">Display Name</a>
           <?php
             if ($_SESSION["currentSort"] == "dname_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($_SESSION["currentSort"] == "dname_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col">
           <a class="btn btn-link" href="/people/index/1?sort=<?= $lname_sort_param ?>">Last Name</a>
           <?php
             if ($_SESSION["currentSort"] == "lname_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($_SESSION["currentSort"] == "lname_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col">
           <a class="btn btn-link" href="/people/index/1?sort=<?= $fname_sort_param ?>">First Name</a>
           <?php
             if ($_SESSION["currentSort"] == "fname_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($_SESSION["currentSort"] == "fname_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col">
           <a class="btn btn-link" href="/people/index/1?sort=<?= $org_sort_param ?>">Organization</a>
           <?php
             if ($_SESSION["currentSort"] == "org_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($_SESSION["currentSort"] == "org_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col"></th>
       </thead>

       <tbody>
         <?php if (! empty($people) && is_array($people)) : ?>
           <?php foreach ($people as $person): ?>
             <tr>
               <td><?= $person->PersonID ?></td>
               <td><?= $person->DisplayName ?></td>
               <td><?= $person->LastName ?></td>
               <td><?= $person->FirstName ?></td>
               <td><?= $person->Organization ?></td>
               <td><a class="btn btn-link" href="/people/edit/<?= $page ?>/<?= $person->PersonID ?>">Edit</a>
                 |<a class="btn btn-link" href="/people/delete/1/<?= $person->PersonID ?>">Delete</a>
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
        <option value=25 <?= ($rowsPerPage == 25) ? ' selected' : '' ?> >25</option>
        <option value=50 <?= ($rowsPerPage == 50) ? ' selected' : '' ?> >50</option>
        <option value=100 <?= ($rowsPerPage == 100) ? ' selected' : '' ?> >100</option>
      </select>
    </div>
  </div>
</div>
