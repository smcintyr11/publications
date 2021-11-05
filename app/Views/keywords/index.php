<?php
  // Use MyFormGeneration
  use App\Libraries\MyFormGeneration;

  // Calculate sort parameters
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

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIndexSearch("keywords", csrf_field()); ?>

  <a class="btn btn-primary my-3" href="<?= base_url() ?>/keywords/new/<?= $page ?>">Create Keyword</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 15%">
      <col style="width: 15%">
      <col style="width: 35%">
      <col style="width: 35%">


      <thead class="thead-light">
        <thead class="thead-light">
          <th scope="col"><div class="btn">Edit | Delete</div></th>
          <?= MyFormGeneration::generateColumnHeader("keywords", "Keyword ID",
            $id_sort_param, $_SESSION["currentSort"], "id_asc", "id_desc"); ?>

          <?= MyFormGeneration::generateColumnHeader("keywords", "Keyword (English)",
            $keye_sort_param, $_SESSION["currentSort"], "keye_asc", "keye_desc"); ?>

          <?= MyFormGeneration::generateColumnHeader("keywords", "Keyword (French)",
            $keyf_sort_param, $_SESSION["currentSort"], "keyf_asc", "keyf_desc"); ?>
      </thead>

      <tbody>
        <?php if (! empty($keywords) && is_array($keywords)) : ?>
          <?php foreach ($keywords as $keyword): ?>
            <tr>
              <?= MyFormGeneration::generateIndexRowButtons("keywords", $page, $keyword->KeywordID); ?>
              <td><?= $keyword->KeywordID; ?></td>
              <td><?= $keyword->KeywordEnglish; ?></td>
              <td><?= $keyword->KeywordFrench; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif ?>
      </tbody>
    </table>
  </div>

  <?= MyFormGeneration::generateRowsPerPage($_SESSION["rowsPerPage"], $links); ?>

</div>
