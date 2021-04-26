<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIndexSearch("keywords", csrf_field()); ?>

  <a class="btn btn-primary my-3" href="/keywords/new/<?= $page ?>">Create Keyword</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 15%">
      <col style="width: 35%">
      <col style="width: 35%">
      <col style="width: 15%">

      <?php
        $id_sort_param = "id_asc";
        $keye_sort_param = "keye_asc";
        $keyf_sort_param = "keyf_asc";
        if ($_SESSION["currentSort"] == "id_asc") {
          $id_sort_param = "id_desc";
        } elseif ($_SESSION["currentSort"] == "keye_asc") {
          $keye_sort_param = "keye_desc";
        } elseif ($_SESSION["currentSort"] == "keyf_asc") {
          $keyf_sort_param = "keyf_desc";
        }
      ?>

      <thead class="thead-light">
        <thead class="thead-light">
          <?= MyFormGeneration::generateColumnHeader("keywords", "Keyword ID",
            $id_sort_param, $_SESSION["currentSort"], "id_asc", "id_desc"); ?>

          <?= MyFormGeneration::generateColumnHeader("keywords", "Keyword (English)",
            $keye_sort_param, $_SESSION["currentSort"], "keye_asc", "keye_desc"); ?>

          <?= MyFormGeneration::generateColumnHeader("keywords", "Keyword (French)",
            $keyf_sort_param, $_SESSION["currentSort"], "keyf_asc", "keyf_desc"); ?>

        <th scope="col"></th>
      </thead>

      <tbody>
        <?php if (! empty($keywords) && is_array($keywords)) : ?>
          <?php foreach ($keywords as $keyword): ?>
            <tr>
              <td><?= $keyword->KeywordID; ?></td>
              <td><?= $keyword->KeywordEnglish; ?></td>
              <td><?= $keyword->KeywordFrench; ?></td>
              <td><a class="btn btn-link" href="/keywords/edit/<?= $page ?>/<?= $keyword->KeywordID ?>">Edit</a>
                |<a class="btn btn-link" href="/keywords/delete/1/<?= $keyword->KeywordID ?>">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif ?>
      </tbody>
    </table>
  </div>

  <?= MyFormGeneration::generateRowsPerPage($_SESSION["rowsPerPage"], $links); ?>
  
</div>
