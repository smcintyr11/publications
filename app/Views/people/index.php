<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-inline" action="/people/index/<?= $cur_sort ?>/<?= $rows ?>" method="post">
    <?= csrf_field() ?>
    <input class="form-control mr-sm-2" type="text" name="filter" placeholder="Search">
    <button class="btn btn-success m-1" type="submit">Search</button>
    <a class="btn btn-info m-1" href="/people/index/<?= $cur_sort ?>/<?= $rows ?>">Reset</a>
  </form>

  <a class="btn btn-primary my-3" href="/people/new/<?= $cur_sort ?>/<?= $rows ?>/<?= $page ?>/<?= $filter ?>">Create Person</a>

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
        if ($cur_sort == "id_asc") {
          $id_sort_param = "id_desc";
        } elseif ($cur_sort == "lname_asc") {
          $lname_sort_param = "lname_desc";
        } elseif ($cur_sort == "fname_asc") {
          $fname_sort_param = "fname_desc";
        } elseif ($cur_sort == "dname_asc") {
          $dname_sort_param = "dname_desc";
        } elseif ($cur_sort == "org_asc") {
          $org_sort_param = "org_desc";
        }
       ?>

       <thead class="thead-light">
         <th scope="col">
           <a class="btn btn-link" href="/people/index/<?= $id_sort_param ?>/<?= $rows ?>/1/<?= $filter ?>">Person ID</a>
           <?php
             if ($cur_sort == "id_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($cur_sort == "id_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col">
           <a class="btn btn-link" href="/people/index/<?= $dname_sort_param ?>/<?= $rows ?>/1/<?= $filter ?>">Display Name</a>
           <?php
             if ($cur_sort == "dname_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($cur_sort == "dname_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col">
           <a class="btn btn-link" href="/people/index/<?= $lname_sort_param ?>/<?= $rows ?>/1/<?= $filter ?>">Last Name</a>
           <?php
             if ($cur_sort == "lname_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($cur_sort == "lname_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col">
           <a class="btn btn-link" href="/people/index/<?= $fname_sort_param ?>/<?= $rows ?>/1/<?= $filter ?>">First Name</a>
           <?php
             if ($cur_sort == "fname_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($cur_sort == "fname_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col">
           <a class="btn btn-link" href="/people/index/<?= $org_sort_param ?>/<?= $rows ?>/1/<?= $filter ?>">Organization</a>
           <?php
             if ($cur_sort == "org_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($cur_sort == "org_desc") {
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
               <td><?= $person['PersonID']; ?></td>
               <td><?= $person['DisplayName']; ?></td>
               <td><?= $person['LastName']; ?></td>
               <td><?= $person['FirstName']; ?></td>
               <td><?= $person['Organization']; ?></td>
               <td><a class="btn btn-link" href="/people/edit/<?= $person['PersonID'] ?>/<?= $cur_sort ?>/<?= $rows ?>/<?= $page ?>/<?= $filter ?>">Edit</a>
                 |<a class="btn btn-link" href="/people/delete/<?= $person['PersonID'] ?>/<?= $cur_sort ?>/<?= $rows ?>/1/<?= $filter ?>">Delete</a>
               </td>
             </tr>
           <?php endforeach; ?>
         <?php endif ?>
       </tbody>
    </table>
  </div>

  <div class="row">
    <div class="col-lg-1 btn">Page:</div>
    <div class="col-lg-7"><?= $pager->makeLinks($page, $rows, $count, 'bootstrap_full', 5) ?></div>
    <div class="col-lg-2 btn  text-right">Rows per page:</div>
    <div class="col-lg-2">
    <a class="btn btn-link
      <?php if ($rows == 25) { echo("disabled"); } ?>
      " href="/people/index/<?= $cur_sort ?>/25/<?= $page ?>/<?= $filter ?>">25</a>
    <a class="btn btn-link
      <?php if ($rows == 50) { echo("disabled"); } ?>
      " href="/people/index/<?= $cur_sort ?>/50/<?= $page ?>/<?= $filter ?>">50</a>
    <a class="btn btn-link
      <?php if ($rows == 100) { echo("disabled"); } ?>
      " href="/people/index/<?= $cur_sort ?>/100/<?= $page ?>/<?= $filter ?>">100</a>
    </div>
  </div>
</div>
