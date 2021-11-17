<?php
  // Use MyFormGeneration
  use App\Libraries\MyFormGeneration;

  // Calculate sort parameters
  $id_sort_param = "id_asc";
  $org_sort_param = "org_asc";
  if ($_SESSION["currentSort"] == "id_asc") {
    $id_sort_param = "id_desc";
  } elseif ($_SESSION["currentSort"] == "org_asc") {
    $org_sort_param = "org_desc";
  }
?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= MyFormGeneration::generateIndexSearch("organizations", csrf_field()); ?>

  <a class="btn btn-primary my-3" href="<?= base_url() ?>/organizations/new/<?= $page ?>">Create Organization</a>

  <div class="table-responsive-lg">
    <table class="table table-striped table-bordered">
      <col style="width: 15%">
      <col style="width: 17%">
      <col style="width: 68%">

      <thead class="thead-light">
        <th scope="col"><div class="btn">View / Edit<br>Delete</div></th>
        <?= MyFormGeneration::generateColumnHeader("organizations", "Organization ID",
          $id_sort_param, $_SESSION["currentSort"], "id_asc", "id_desc"); ?>

        <?= MyFormGeneration::generateColumnHeader("organizations", "Organization",
          $org_sort_param, $_SESSION["currentSort"], "org_asc", "org_desc"); ?>
      </thead>

      <tbody>
        <?php if (! empty($organizations) && is_array($organizations)) : ?>
          <?php foreach ($organizations as $organization): ?>
            <tr>
              <?= MyFormGeneration::generateIndexRowButtons("organizations", $page, $organization->OrganizationID, in_groups(['pubsAdmin','pubsRC']), in_groups(['pubsAdmin','pubsRC']), true, true, false, true); ?>
              <td><?= $organization->OrganizationID; ?></td>
              <td><?= $organization->Organization; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif ?>
      </tbody>
    </table>
  </div>

  <?= MyFormGeneration::generateRowsPerPage($_SESSION["rowsPerPage"], $links); ?>

</div>
