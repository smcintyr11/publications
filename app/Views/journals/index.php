<?php
  // Use MyFormGeneration
  use App\Libraries\MyFormGeneration;

  // Calculate sort parameters
  $id_sort_param = "id_asc";
  $journal_sort_param = "jour_asc";
  if ($_SESSION["currentSort"] == "id_asc") {
    $id_sort_param = "id_desc";
  } elseif ($_SESSION["currentSort"] == "jour_asc") {
    $journal_sort_param = "jour_desc";
  }
?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIndexSearch("journals", csrf_field()); ?>

  <a class="btn btn-primary my-3" href="<?= base_url() ?>/journals/new/<?= $page ?>">Create Journal</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 15%">
      <col style="width: 15%">
      <col style="width: 70%">


      <thead class="thead-light">
        <thead class="thead-light">
          <th scope="col"><div class="btn">View / Edit<br>Delete</div></th>
          <?= MyFormGeneration::generateColumnHeader("journals", "Journal ID",
            $id_sort_param, $_SESSION["currentSort"], "id_asc", "id_desc"); ?>

          <?= MyFormGeneration::generateColumnHeader("journals", "Journal",
            $journal_sort_param, $_SESSION["currentSort"], "jour_asc", "jour_desc"); ?>
      </thead>

      <tbody>
        <?php if (! empty($journals) && is_array($journals)) : ?>
          <?php foreach ($journals as $journal): ?>
            <tr>
              <?= MyFormGeneration::generateIndexRowButtons("journals", $page, $journal->JournalID, in_groups(['pubsAdmin','pubsRC']), in_groups(['pubsAdmin','pubsRC']), true, true, false, true); ?>
              <td><?= $journal->JournalID; ?></td>
              <td><?= $journal->Journal; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif ?>
      </tbody>

    </table>
  </div>

  <?= MyFormGeneration::generateRowsPerPage($_SESSION["rowsPerPage"], $links); ?>

</div>
