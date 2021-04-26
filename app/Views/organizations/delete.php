<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/organizations/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateDRAlert($dependentRecords); ?>

    <?= MyFormGeneration::generateIDTextBox("organizationID",
      $organization['OrganizationID'], "Organization ID"); ?>

    <?= MyFormGeneration::generateIDTextBox("organization",
      $organization['Organization'], "Organization"); ?>

    <?= MyFormGeneration::generateDeleteOptions($dependentRecords, 'organizations', 'organization', $page); ?>

  </form>

</div>
