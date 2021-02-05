<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-inline" action="/linkTypes/index/<?= $cur_sort ?>/<?= $rows ?>" method="post">
    <?= csrf_field() ?>
    <input class="form-control mr-sm-2" type="text" name="filter" placeholder="Search">
    <button class="btn btn-success m-1" type="submit">Search</button>
    <a class="btn btn-info m-1" href="/linkTypes/index/<?= $cur_sort ?>/<?= $rows ?>">Reset</a>
  </form>

  <a class="btn btn-primary my-3" href="/linkTypes/new/<?= $cur_sort ?>/<?= $rows ?>/<?= $page ?>/<?= $filter ?>">Create Link Type</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 15%">
      <col style="width: 70%">
      <col style="width: 15%">

      <?php
        $id_sort_param = "id_asc";
        $linkType_sort_param = "linkType_asc";
        if ($cur_sort == "id_asc") {
          $id_sort_param = "id_desc";
        } elseif ($cur_sort == "linkType_asc") {
          $linkType_sort_param  = "linkType_desc";
        }
       ?>

       <thead class="thead-light">
         <th scope="col">
           <a class="btn btn-link" href="/linkTypes/index/<?= $id_sort_param ?>/<?= $rows ?>/1/<?= $filter ?>">Link Type ID</a>
           <?php
             if ($cur_sort == "id_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($cur_sort == "id_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col">
           <a class="btn btn-link" href="/linkTypes/index/<?= $linkType_sort_param ?>/<?= $rows ?>/1/<?= $filter ?>">Link Type</a>
           <?php
             if ($cur_sort == "linkType_asc") {
               echo("<i class=\"fas fa-sort-up\"></i>");
             } elseif ($cur_sort == "linkType_desc") {
               echo ("<i class=\"fas fa-sort-down\"></i>");
             }
            ?>
         </th>
         <th scope="col"></th>
       </thead>

       <tbody>
         <?php if (! empty($linkTypes) && is_array($linkTypes)) : ?>
           <?php foreach ($linkTypes as $linkType): ?>
             <tr>
               <td><?= $linkType['LinkTypeID']; ?></td>
               <td><?= $linkType['LinkType']; ?></td>
               <td><a class="btn btn-link" href="/linkTypes/edit/<?= $linkType['LinkTypeID'] ?>/<?= $cur_sort ?>/<?= $rows ?>/<?= $page ?>/<?= $filter ?>">Edit</a>
                 |<a class="btn btn-link" href="/linkTypes/delete/<?= $linkType['LinkTypeID'] ?>/<?= $cur_sort ?>/<?= $rows ?>/1/<?= $filter ?>">Delete</a>
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
      " href="/linkTypes/index/<?= $cur_sort ?>/25/<?= $page ?>/<?= $filter ?>">25</a>
    <a class="btn btn-link
      <?php if ($rows == 50) { echo("disabled"); } ?>
      " href="/linkTypes/index/<?= $cur_sort ?>/50/<?= $page ?>/<?= $filter ?>">50</a>
    <a class="btn btn-link
      <?php if ($rows == 100) { echo("disabled"); } ?>
      " href="/linkTypes/index/<?= $cur_sort ?>/100/<?= $page ?>/<?= $filter ?>">100</a>
    </div>
  </div>
</div>
