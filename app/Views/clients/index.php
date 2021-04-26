<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIndexSearch("clients", csrf_field()); ?>

  <a class="btn btn-primary my-3" href="/clients/new/<?= $page ?>">Create Client / Publisher</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 20%">
      <col style="width: 65%">
      <col style="width: 15%">

      <?php
        $id_sort_param = "id_asc";
        $client_sort_param = "client_asc";
        if ($_SESSION["currentSort"] == "id_asc") {
          $id_sort_param = "id_desc";
        } elseif ($_SESSION["currentSort"] == "client_asc") {
          $client_sort_param = "client_desc";
        }
       ?>

      <thead class="thead-light">
        <?= MyFormGeneration::generateColumnHeader("clients", "Client / Publisher ID",
          $id_sort_param, $_SESSION["currentSort"], "id_asc", "id_desc"); ?>

        <?= MyFormGeneration::generateColumnHeader("clients", "Client / Publisher",
          $client_sort_param, $_SESSION["currentSort"], "client_asc", "client_desc"); ?>

        <th scope="col"></th>
      </thead>

      <tbody>
        <?php if (! empty($clients) && is_array($clients)) : ?>
          <?php foreach ($clients as $client): ?>
            <tr>
              <td><?= $client->ClientID; ?></td>
              <td><?= $client->Client; ?></td>
              <td><a class="btn btn-link" href="/clients/edit/<?= $page ?>/<?= $client->ClientID ?>">Edit</a>
                |<a class="btn btn-link" href="/clients/delete/1/<?= $client->ClientID ?>">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif ?>
      </tbody>

    </table>
  </div>

  <?= MyFormGeneration::generateRowsPerPage($_SESSION["rowsPerPage"], $links); ?>

</div>
