<?php
  // Use MyFormGeneration
  use App\Libraries\MyFormGeneration;

  // Calculate sort parameters
  $id_sort_param = "id_asc";
  $client_sort_param = "client_asc";
  if ($_SESSION["currentSort"] == "id_asc") {
    $id_sort_param = "id_desc";
  } elseif ($_SESSION["currentSort"] == "client_asc") {
    $client_sort_param = "client_desc";
  }
?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIndexSearch("clients", csrf_field(), set_value('filter', '')); ?>

  <a class="btn btn-primary my-3" href="<?= base_url() ?>/clients/new/<?= $page ?>">Create Client / Publisher</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 15%">
      <col style="width: 20%">
      <col style="width: 65%">

      <thead class="thead-light">
        <th scope="col"><div class="btn">View / Edit<br>Delete</div></th>
        <?= MyFormGeneration::generateColumnHeader("clients", "Client / Publisher ID",
          $id_sort_param, $_SESSION["currentSort"], "id_asc", "id_desc"); ?>

        <?= MyFormGeneration::generateColumnHeader("clients", "Client / Publisher",
          $client_sort_param, $_SESSION["currentSort"], "client_asc", "client_desc"); ?>
      </thead>

      <tbody>
        <?php if (! empty($clients) && is_array($clients)) : ?>
          <?php foreach ($clients as $client): ?>
            <tr>
              <?= MyFormGeneration::generateIndexRowButtons("clients", $page, $client->ClientID, in_groups(['pubsAdmin','pubsRC']), in_groups(['pubsAdmin','pubsRC']), true, true, false, true); ?>
              <td><?= $client->ClientID; ?></td>
              <td><?= $client->Client; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif ?>
      </tbody>

    </table>
  </div>

  <?= MyFormGeneration::generateRowsPerPage($_SESSION["rowsPerPage"], $links); ?>

</div>
