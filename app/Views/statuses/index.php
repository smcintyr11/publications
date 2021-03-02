<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-inline" action="/statuses/index/<?= $cur_sort ?>/<?= $rows ?>" method="post">
    <?= csrf_field() ?>
    <input class="form-control mr-sm-2" type="text" name="filter" placeholder="Search">
    <button class="btn btn-success m-1" type="submit">Search</button>
    <a class="btn btn-info m-1" href="/statuses/index/<?= $cur_sort ?>/<?= $rows ?>">Reset</a>
  </form>

  <a class="btn btn-primary my-3" href="/statuses/new/<?= $cur_sort ?>/<?= $rows ?>/<?= $page ?>/<?= $filter ?>">Create Status</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 17%">
      <col style="width: 51%">
      <col style="width: 17%">
      <col style="width: 15%">

      <?php
        $id_sort_param = "id_asc";
        $status_sort_param = "status_asc";
        $duration_sort_param = "duration_asc";
        if ($cur_sort == "id_asc") {
          $id_sort_param = "id_desc";
        } elseif ($cur_sort == "status_asc") {
          $status_sort_param = "status_desc";
        } elseif ($cur_sort == "duration_asc") {
          $duration_sort_param = "duration_desc";
        }
       ?>

       <thead class="thead-light">
         <th scope="col">
           <a class="btn btn-link" href="/statuses/index/<?= $id_sort_param ?>/<?= $rows ?>/1/<?= $filter ?>">Status ID</a>
           <?php
             if ($cur_sort == "id_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($cur_sort == "id_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col">
           <a class="btn btn-link" href="/statuses/index/<?= $status_sort_param ?>/<?= $rows ?>/1/<?= $filter ?>">Status</a>
           <?php
             if ($cur_sort == "status_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($cur_sort == "status_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col">
           <a class="btn btn-link" href="/statuses/index/<?= $duration_sort_param ?>/<?= $rows ?>/1/<?= $filter ?>">Expected Duration</a>
           <?php
             if ($cur_sort == "duration_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($cur_sort == "duration_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col"></th>
       </thead>

       <tbody>
         <?php if (! empty($statuses) && is_array($statuses)) : ?>
           <?php foreach ($statuses as $status): ?>
             <tr>
               <td><?= $status['StatusID']; ?></td>
               <td><?= $status['Status']; ?></td>
               <td><?= $status['ExpectedDuration']; ?></td>
               <td><a class="btn btn-link" href="/statuses/edit/<?= $status['StatusID'] ?>/<?= $cur_sort ?>/<?= $rows ?>/<?= $page ?>/<?= $filter ?>">Edit</a>
                 |<a class="btn btn-link" href="/statuses/delete/<?= $status['StatusID'] ?>/<?= $cur_sort ?>/<?= $rows ?>/1/<?= $filter ?>">Delete</a>
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
      " href="/statuses/index/<?= $cur_sort ?>/25/<?= $page ?>/<?= $filter ?>">25</a>
    <a class="btn btn-link
      <?php if ($rows == 50) { echo("disabled"); } ?>
      " href="/statuses/index/<?= $cur_sort ?>/50/<?= $page ?>/<?= $filter ?>">50</a>
    <a class="btn btn-link
      <?php if ($rows == 100) { echo("disabled"); } ?>
      " href="/statuses/index/<?= $cur_sort ?>/100/<?= $page ?>/<?= $filter ?>">100</a>
    </div>
  </div>
</div>
